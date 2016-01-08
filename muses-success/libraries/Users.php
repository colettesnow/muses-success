<?php
class Users
{
        /*

                TODO: Rewrite Entire Library

        */

        /*
                #############################################################

                DON'T EDIT AFTER THIS POINT UNLESS YOU KNOW WHAT YOU ARE DOING

                #############################################################

                This ensures all users are logged out at default.

                You probably should not change this.

        */

        public $logged_in = false;

        /*

                This array determines the data that is
                returned when using cur_user to display
                information about the current user when
                no user is actually logged in.

        */

        public $cur_user = array(
                                        'username' => 'Guest',
                                        'access_level' => '0'
                                );

        /*

                This is merely used to pass an encrypted string
                to the login function, so the appropriate cookie
                can be set.

        */

        private $password;

        /*

                The Users method get's called as soon as the class
                is initiallised, thus we can call auth to check if we
                should report that a user is logged in or not.

        */

        private $user_fields = array();

        public $permissions = array();

        public $user_group = 4;

        public function Users()
        {
                return $this->auth();
        }

        /*

                This function is the most important.

                It takes the supplied user details, whether passed
                from the login function or from a cookie, then checks
                if the user exists, and if there password is correct,
                then proceeds to fill the $cur_user array with information
                about the currently logged in user and reports true on success
                and false on failure.

                The function takes three paramaters. Generally all the fields are
                optional but if you specify one, you should specify them all.

                Username           - the username of the user you want authenticated.
                                     If not set, we will check the appropriate cookie,
                                     otherwise we will return false.

                Password           - the password of the user you want authenticated.
                                     If not set, we will check the appropriate cookie,
                                     otherwise we will return false.

                not_encrypted      - set to true if the password you supplied was not hashed for use in a cookie,
                                     otherwise leave blank or set to false.

                This function is private and generally only needs to be called by Users(); and login();.

        */

        public function auth($username = NULL, $password = NULL, $not_encrypted = false)
        {
                $CI = & get_instance();

                $CI->load->helper('cookie');
                $CI->load->library('user_agent');

                if (!isset($username) && !isset($password) && $username == '' && $password == '')
                {
                        $username = get_cookie('usn');
                        $password = get_cookie('pwd');
                }

                if ($username != '' && $password != '' && (strlen($password) == 40 || $not_encrypted == true) && strlen($username) >= 3 && strlen($username) <= 20)
                {
                        $check = $CI->db->get_where('users', array('screen_name' => $username), 1);
                        if ($check->num_rows() == 1)
                        {
                                $cur_user = $check->row();
                                $hashed_password_with_useragent = sha1($CI->config->item('encryption_key').$cur_user->password.$CI->agent->agent_string());
                                if ($password == $hashed_password_with_useragent || $not_encrypted == true)
                                {
                                        if ($not_encrypted == true)
                                        {
                                                // hash the password appropriately
                                                $secure_salt = $cur_user->secure_salt;
                                                $hashed_password = sha1($CI->config->item('encryption_key').(sha1($password.$secure_salt)).$CI->agent->agent_string());
                                                $this->password = $hashed_password;
                                        } else {
                                        	$hashed_password = $password;
                                        }

                                        if ($hashed_password == $hashed_password_with_useragent)
                                        {

                                                $this->logged_in = true;
                                                $this->user_group = $cur_user->user_group;
                                                $this->cur_user = array(
                                                                        'user_id' => $cur_user->user_id,
                                                                        'username' => $cur_user->screen_name,
                                                                        'display_name' => ((strlen($cur_user->display_name) > 2) ? $cur_user->display_name : $cur_user->screen_name),
                                                                        'access_level' => $cur_user->access_level,
                                                                        'email_address' => $cur_user->email_address,
                                                                        'inbox_unread' => $cur_user->inbox_unread,
                                                                        'data' => $cur_user,
                                                                        'upload_thumbs' => $cur_user->upload_thumbs
                                                                );

                                                if ($cur_user->last_access < time()-(15*60))
                                                {

                                                    $data = array(
                                                        'ip_address' => $CI->input->ip_address(),
                                                        'user_agent' => $CI->input->user_agent(),
                                                        'last_access' => time()
                                                    );

                                                    $CI->db->where('user_id', $cur_user->user_id);
                                                    $CI->db->update('users', $data);

                                                }

                                                return true;

                                        }

                                }

                        }

                }

                if (!isset($this->logged_in))
                {
                        $this->logged_in = false;
                        return false;
                }
        }

        public function login($username, $password)
        {
                $CI =& get_instance();

                $CI->load->helper('cookie');

                if ($this->auth($username, $password, true) == true)
                {
                        set_cookie('usn', $username, 2678400);
                        set_cookie('pwd', $this->password, 2678400);
                        return array('code' => 'success', 'message' => 'You have successfully logged in.');
                } else {
                        delete_cookie('usn');
                        delete_cookie('pwd');
                        return array('code' => 'error', 'message' => 'You have entered an invalid username or password.');
                }
        }

        function has_permission($permission)
        {

            $CI = & get_instance();

            if (count($this->permissions) == 0)
            {

                $query = $CI->db->get_where('user_groups', array('g_id' => $this->user_group));
                foreach ($query->row_array() as $perm => $allowedornot)
                {
                    $this->permissions[$perm] = $allowedornot;
                }

            }

            return $this->permissions[$permission];

        }

        public function logout()
        {
                $CI =& get_instance();

                $CI->load->helper('cookie');

                delete_cookie('usn');
                delete_cookie('pwd');
                return array('code' => 'success', 'message' => 'You have successfully logged out.');
        }

        function get_user_info($user_id)
        {
                $CI =& get_instance();

                $query = $CI->db->get_where('users', array('user_id' => $user_id), 1);
                if ($query->num_rows() == 1)
                {
                        return $query->row_array();
                } else {
                        return false;
                }
        }

        public function register($user_data = array())
        {
                $CI =& get_instance();

                if (count($user_data) == 0)
                {
                        return 'false';
                } else {

                        if (!isset($user_data['username']))
                        {
                                $error_message = 'You must choose a username.';
                        } elseif (!isset($user_data['password']))
                        {
                                $error_message = 'You must choose a password.';
                        } elseif (!isset($user_data['email_address']))
                        {
                                $error_message = 'You must specify a valid email address.';
                        } elseif (strlen($user_data['username']) < 3 || strlen($user_data['username']) > 20)
                        {
                                $error_message = 'Your username must be between 3 and 20 characters in length. Your username is `'.strlen($user_data['username']).'` characters in length.';
                        } elseif (!preg_match('/^[A-z0-9_\040]+$/', $user_data['username']))
                        {
                                $error_message = 'You can only use alphanumeric characters, underscores, and spaces in usernames.';
                        } elseif (strlen($user_data['password']) < 6)
                        {
                                $error_message = 'Your password must be be at least 6 characters in length. Your password is `'.strlen($user_data['password']).'` characters in length.';
                        } elseif (!filter_var($user_data['email_address'], FILTER_VALIDATE_EMAIL))
                        {
                                $error_message = 'The supplied e-mail address is not valid.';
                        }

                        if (!isset($error_message))
                        {

                                $CI->db->where(array('screen_name' => $user_data['username']));
                                $CI->db->from('users');
                                $check_username = $CI->db->count_all_results();
                                $CI->db->where(array('email_address' => $user_data['email_address']));
                                $CI->db->from('users');
                                $check_email = $CI->db->count_all_results();

                                if ($check_username >= 1)
                                {
                                        $error_message = 'The username you have chosen is already taken.';
                                }
                                if ($check_email >= 1)
                                {
                                        $error_message = 'The e-mail address you are attempting to use is already associated with another account.';
                                }
                                if (!isset($error_message))
                                {
                                        $CI->load->helper('string');

                                        $data['screen_name'] = $user_data['username'];
                                        $data['secure_salt'] = random_string('alnum', 10);
                                        $data['password'] = sha1($user_data['password'].$data['secure_salt']);
                                        $data['email_address'] = $user_data['email_address'];
                                        $data['registration_date'] = time();
                                        $data['ip_address'] = $user_data['ip_address'];
                                        $data['access_level'] = 1;
                                        $data['user_group'] = 1;
                                        $CI->db->insert('users', $data);

                                        return array('code' => 'success', 'message' => 'The user account was created successfully.');

                                } else {

                                        return array('code' => 'error', 'message' => $error_message);

                                }
                        }

                }
        }

        public function change_password($new_password)
        {
                $CI =& get_instance();
                $CI->load->helper('string');

                $secure_salt = random_string('alnum', 10);

                $CI->db->where('screen_name', $this->cur_user['username']);
                $CI->db->update('users', array('password' => sha1($new_password.$secure_salt), 'secure_salt' => $secure_salt));

                return true;

        }

        public function change_access($username, $level)
        {
                $CI =& get_instance();

                $CI->db->where('screen_name', $this->cur_user['username']);
                $CI->db->update('users', array('access_level' => $level));
        }

        public function update_profile_field($field_to_update, $value_for_field)
        {
                if ($field_to_update != 'screen_name' || $field_to_update != 'access_level' || $field_to_update != 'password' || $field_to_update != 'secure_salt' || $field_to_update != 'user_id' || $field_to_update != 'registration_date')
                {
                        $this->user_fields[$field_to_update] = $value_for_field;
                }
        }

        public function update_profile_commit($user_id = '')
        {
                $CI =& get_instance();

                if ($user_id == '' && $this->cur_user['username'] != 'Guest')
                {
                        $CI->db->where('screen_name', $this->cur_user['username']);
                        $CI->db->update('users', $this->user_fields);
                        return array('code' => 'success', 'message' => 'Your preferences have been updated successfully.');
                } elseif ($user_id != '' && is_numeric($user_id))
                {
                        $CI->db->where('user_id', intval($user_id));
                        $CI->db->update('users', $this->user_fields);
                        return array('code' => 'success', 'message' => 'The user fields were updated successfully.');
                } else {
                        return array('code' => 'error', 'message' => 'A user must be logged in or an ID specified to update user data.');
                }
        }

}
