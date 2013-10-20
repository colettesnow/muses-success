<?php
/*

This file is part of the Muse's Success Web Fiction Directory script.

Muse's Success Web Fiction Directory is free software: you can redistribute
it and/or modify it under the terms of the GNU Affero General Public License
as published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

Muse's Success Web Fiction Directory is distributed in the hope that it
will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero
General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with Muse's Success Web Fiction Directory. If not, see
<http://www.gnu.org/licenses/>.

*/

if (!defined('BASEPATH')) exit('No direct script access allowed');
class Accounts extends Controller {

    function Accounts() {
        parent::Controller();
    }

    function login()
    {
        if ($this->users->logged_in == true)
        {
            redirect('accounts/');
        }

        $this->load->helper(array('form', 'url'));

        $this->load->library('validation');

        $rules['username']    = "required|min_length[3]|max_length[20]|callback_check_username";
        $rules['password']    = "required|min_length[6]|max_length[25]|callback_check_password";

        $this->validation->set_rules($rules);

        $fields['username']    = 'Username';
        $fields['password']    = 'Password';

        $this->validation->set_fields($fields);

        $this->validation->set_error_delimiters('<p class="error">', '</p>');

        $this->load->view('header', array('page_title' => 'Log In', 'breadcrumbs' => array('Log In')));
        if ($this->validation->run() == FALSE)
        {
            $this->load->view('accounts/login.php');
        }
        else
        {
            $login = $this->users->login($this->input->post('username'), $this->input->post('password'));
            if ($login['code'] == 'success')
            {
                redirect('login-success');
            } else {
                $this->load->view('accounts/login_failure.php');
            }
        }

        $this->load->view('footer');
    }

    function check_username($username)
    {
        $user = $this->db->get_where('users', array('screen_name' => $username));
        if ($user->num_rows() == 1)
        {
            return true;
        } else {
            $this->validation->set_message('check_username', 'We\'re sorry, but we cannot find an account for this username and/or password.');
            return false;
        }
    }


    function check_password($password)
    {
        if ($this->users->auth($_POST['username'], $password, true))
        {
            return true;
        } else {
            if ($this->check_username($_POST['username']) == true)
            {
                $this->validation->set_message('check_password', 'The username and password combination does not match our records');
                return false;
            } else {
                return true;
            }
        }
    }

    function register()
    {
        if ($this->users->logged_in == true)
        {
            redirect('accounts/');
        }

        $this->load->helper(array('form', 'url'));

        $this->load->library('validation');
        $this->load->library('recaptcha');

        $rules['a35099353beea9ca858de0e4c02d2dfee'] = "required|min_length[3]|max_length[20]|alpha_dash";
        $rules['R6JZS7fjyIxO8oqSoSxg'] = "required|min_length[6]";
        $rules['a119b78fdcd7b53be6e51989da6de4220'] = "required|valid_email";
        $rules['recaptcha_response_field'] = 'required|callback_check_captcha';

        $fields['a35099353beea9ca858de0e4c02d2dfee']    = 'Username';
        $fields['R6JZS7fjyIxO8oqSoSxg']    = 'Password';
        $fields['a119b78fdcd7b53be6e51989da6de4220'] = 'Email Address';
        $fields['recaptcha_response_field'] = 'Security Code';

        $this->validation->set_rules($rules);
        $this->validation->set_fields($fields);

        $data = array(
                        'captcha' => $this->recaptcha->recaptcha_get_html()
        );

        if ($this->validation->run() == FALSE)
        {
            $this->load->view('header', array('page_title' => 'Account Registration', 'breadcrumbs' => array('Register')));
            $this->load->view('accounts/register.php', $data);
        }
        else
        {
            $user_data = array(
                                'username' => $this->input->post('a35099353beea9ca858de0e4c02d2dfee'),
                                'password' => $this->input->post('R6JZS7fjyIxO8oqSoSxg'),
                                'email_address' => $this->input->post('a119b78fdcd7b53be6e51989da6de4220'),
                                'ip_address' => $this->input->ip_address()
            );
            $register = $this->users->register($user_data);
            if ($register['code'] == 'success')
            {
                $this->users->login($user_data['username'], $user_data['password']);
                $this->load->view('header');
                $this->load->view('accounts/register_success.php');
            } else {
                $this->load->view('header');
                $this->load->view('accounts/register_failure.php', array('error_message' => $register['message']));
            }
        }

        $this->load->view('footer');

    }

    function index()
    {

        $this->load->view('header', array('page_title' => 'Manage Account', 'breadcrumbs' => array('My Account')));

        if ($this->users->logged_in == true)
        {

            $this->load->view('accounts/main_view');

        } else {

            $this->load->view('accounts/login_required');

        }

        $this->load->view('footer');

    }

    function editprofile()
    {
        $this->load->view('header', array('page_title' => 'Edit Profile', 'breadcrumbs' => array('<a href="'.site_url('accounts').'">My Account</a>', 'Edit Profile')));

        if ($this->users->logged_in == true)
        {
            $this->load->helper(array('form', 'url'));

            $this->load->library('validation');

            $rules['email_address']         = "required|valid_email|xss_clean";
            $rules['website_url']           = "prep_url|xss_clean";
            $rules['display_name']          = "alphanumeric|min_length[3]|max_length[25]";
            $rules['location']              = "htmlspecialchars|min_length[3]|max_length[50]";
            $rules['gender']                = "max_length[100]";
            $rules['windowslive']           = "htmlspecialchars|valid_email|max_length[320]";
            $rules['yahoo']                 = "htmlspecialchars|max_length[32]";
            $rules['aol']                   = "htmlspecialchars|min_length[3]|max_length[16]";
            $rules['google']                = "htmlspecialchars|valid_email|max_length[320]";
            $rules['signature']             = "xss_clean|min_length[3]|max_length[500]";

            $this->validation->set_rules($rules);

            $fields['email_address']         = "Email Address";
            $fields['website_url']           = "Website URL";
            $fields['display_name']          = "Display Name";
            $fields['location']              = "Location";
            $fields['gender']                = "Gender";
            $fields['windowslive']           = "Windows Live IM";
            $fields['yahoo']                 = "Yahoo IM";
            $fields['aol']                   = "AOL IM";
            $fields['google']                = "Google Talk IM";
            $fields['signature']             = "Signature";

            $this->validation->set_fields($fields);

            if ($this->validation->run() == FALSE)
            {
                $fields['field_email_address']  = $this->set_field_value('email_address', $this->users->cur_user['data']->email_address);
                $fields['field_website_url']    = $this->set_field_value('website_url', $this->users->cur_user['data']->website_url);
                $fields['field_display_name']   = $this->set_field_value('display_name', $this->users->cur_user['display_name']);
                $fields['field_location']       = $this->set_field_value('location', $this->users->cur_user['data']->location);
                $fields['field_gender']         = $this->set_field_value('gender', $this->users->cur_user['data']->gender_new);
                $fields['field_windowslive']    = $this->set_field_value('windowslive', $this->users->cur_user['data']->windowslive_im);
                $fields['field_yahoo']          = $this->set_field_value('yahoo', $this->users->cur_user['data']->yahoo_im);
                $fields['field_aol']            = $this->set_field_value('aol', $this->users->cur_user['data']->aol_im);
                $fields['field_google']         = $this->set_field_value('google', $this->users->cur_user['data']->google_im);
                $fields['field_signature']      = $this->set_field_value('signature', $this->users->cur_user['data']->signature);

                $this->load->view('accounts/editprofile', $fields);
            }
            else
            {
                $this->users->update_profile_field('email_address', $this->input->post('email_address'));
                $this->users->update_profile_field('website_url', $this->input->post('website_url', true));
                $this->users->update_profile_field('display_name', $this->input->post('display_name', true));
                $this->users->update_profile_field('location', $this->input->post('location', true));
                $this->users->update_profile_field('windowslive_im', $this->input->post('windowslive', false));
                $this->users->update_profile_field('yahoo_im', $this->input->post('yahoo', true));
                $this->users->update_profile_field('aol_im', $this->input->post('aol', true));
                $this->users->update_profile_field('google_im', $this->input->post('google', false));
                $this->users->update_profile_field('gender_new', $this->input->post('gender', false));
                $this->users->update_profile_field('signature', $this->input->post('signature', true));
                $this->users->update_profile_commit();
                $this->load->view('accounts/editprofile_success');
            }

        } else {

            $this->load->view('accounts/login_required');

        }

        $this->load->view('footer');
    }

    function changepassword()
    {
        $this->load->view('header', array('page_title' => 'Change Password', 'breadcrumbs' => array('<a href="'.site_url('accounts').'">My Account</a>', 'Change Password')));

        if ($this->users->logged_in == true)
        {
            $this->load->helper(array('form', 'url'));

            $this->load->library('validation');

            $rules['old_password']              = "required|min_length[6]|max_length[25]|alpha_dash|callback_oldpasswordcorrect";
            $rules['new_password']              = "required|min_length[6]|max_length[25]|alpha_dash|matches[confirm_password]";
            $rules['confirm_password']          = "required|min_length[6]|max_length[25]|alpha_dash";
            $this->validation->set_rules($rules);

            $fields['old_password']             = 'Current Password';
            $fields['new_password']             = 'New Password';
            $fields['confirm_password']         = 'Confirm Password';
            $this->validation->set_fields($fields);

            if ($this->validation->run() == FALSE)
            {
                $this->load->view('accounts/changepassword');
            } else {
                $this->users->change_password($this->input->post('new_password'));
                $this->users->login($this->users->cur_user['username'], $this->input->post('new_password'));
                $this->load->view('accounts/changepassword_success');
            }

        } else {

            $this->load->view('accounts/login_required');

        }

        $this->load->view('footer');
    }

    function oldpasswordcorrect($password)
    {

        if ($this->users->auth($this->users->cur_user['username'], $password, true) == false)
        {
            $this->validation->set_message('oldpasswordcorrect', 'Your Current Password is incorrect. You must successfully confirm your Current Password before it can be changed.');
            return false;
        } else
        return true;

    }

    // Log Out Method

    function logout()
    {
        $this->users->logout();
        redirect();
    }

    function lostpass()
    {

        if ($this->users->logged_in == true)
        {
            redirect('accounts/');
        }

        $this->load->helper(array('form', 'url'));
        $this->load->model('accounts/password');
        $this->load->library('validation');
        $this->load->library('recaptcha');

        $rules['email_address'] = 'required|valid_email|callback_checkemail';
        $rules['recaptcha_response_field'] = 'required|callback_check_captcha';
        $this->validation->set_rules($rules);

        $fields['email_address'] = 'Email Address';
        $this->validation->set_fields($fields);

        $data = array(
            'captcha' => $this->recaptcha->recaptcha_get_html()
        );

        $this->load->view('header', array('page_title' => 'Lost Password Recovery'));

        if ($this->validation->run() == FALSE)
        {

            $this->load->view('accounts/resetpass', $data);

        } else {

            $this->password->send_password_reset_email($this->input->post('email_address'));
            $this->load->view('accounts/resetpass_sent');

        }

        $this->load->view('footer');
    }

    function passconf()
    {
        $confirmation_id = $this->uri->segment(3);

        $this->load->model('accounts/password');

        if ($this->password->send_new_password($confirmation_id))
        {

            $this->load->view('header');
            $this->load->view('accounts/resetpass_passsent');
            $this->load->view('footer');

        } else {
            show_error('This password reset link has expired.', 200);
        }
    }

    function check_captcha($val) {
        $this->recaptcha->recaptcha_check_answer($_SERVER["REMOTE_ADDR"],$this->input->post('recaptcha_challenge_field'),$val);
        if ($this->recaptcha->is_valid) {
            return true;
        } else {
            $this->validation->set_message('check_captcha','Incorrect Security Image Response');
            return false;
        }
    }

    // set message
    function _set_message($msg, $val = '', $sub = '%s')
    {
        return str_replace($sub, $val, $this->lang->line($msg));
    }

    function bookshelf()
    {
        if ($this->users->logged_in == false)
        redirect('accounts/login');

        $pt = array('page_title' => 'My Bookshelf - My Account', 'breadcrumbs' => array('<a href="'.site_url('accounts').'">My Account</a>', 'My Bookshelf'), 'bookshelf_js' => true);
        $pt['use_javascript'] = true;
        $pt['javascript'] = array('bookshelf.js');
        
        if ($this->input->post('delete') == 'Delete')
        {
            $i = 0;
            if ($this->input->post('reading') != '')
            {

                foreach ($this->input->post('reading') as $book_id)
                {
                    $this->db->delete('library', array('book_id' => intval($book_id), 'library_user' => $this->users->cur_user['user_id']));
                    ++$i;
                }
            }

            if ($i == 1)
            {
                $pt['alert'] = 'Success! The selected title has been removed from your bookshelf.';
            } elseif ($i == 0) {
                $pt['alert'] = 'You did not select a title to remove from your bookshelf.';
            } else {
                $pt['alert'] = 'Success! All '.count($this->input->post('reading')).' selected title\'s have been removed from your bookshelf.';
            }
        }

        if ($this->input->post('change') == 'Change Status' && ($this->input->post('status') >= 1 && $this->input->post('status') <=5))
        {

            switch ($this->input->post('status')) {
                case 1:
                    $book_status = 'current';
                    $status_nice = 'Currently Reading';
                    break;
                case 2:
                    $book_status = 'planned';
                    $status_nice = 'Plan to Read';
                    break;
                case 3:
                    $book_status = 'onhold';
                    $status_nice = 'On-Hold';
                    break;
                case 4:
                    $book_status = 'complete';
                    $status_nice = 'Completed Reading';
                    break;
                case 5:
                    $book_status = 'dropped';
                    $status_nice = 'Dropped';
                    break;
            }
            $i = 0;
            if ($this->input->post('reading') != '')
            {


                foreach ($this->input->post('reading') as $book_id)
                {
                    ++$i;
                    $this->db->where(array('book_id' => intval($book_id), 'library_user' => $this->users->cur_user['user_id']));
                    $this->db->update('library', array('book_status' => $book_status));
                }

            }

            if ($i == 1)
            {
                $pt['alert'] = '<strong>Success!</strong> The selected title has had its status changed to '.$status_nice.'.';
            } elseif ($i == 0) {
                $pt['alert'] = 'You did not select a title to change the status of.';
            } else {
                $pt['alert'] = '<strong>Success!</strong> All '.count($this->input->post('reading')).' selected title\'s have had their status changed to '.$status_nice.'.';
            }
        }

        $data = array();
        $data['reading_current'] = $this->library_get('current', $this->users->cur_user['user_id']);
        $data['reading_planned'] = $this->library_get('planned', $this->users->cur_user['user_id']);
        $data['reading_onhold'] = $this->library_get('onhold', $this->users->cur_user['user_id']);
        $data['reading_complete'] = $this->library_get('complete', $this->users->cur_user['user_id']);
        $data['reading_dropped'] = $this->library_get('dropped', $this->users->cur_user['user_id']);

        $this->load->view('header', $pt);
        $this->load->view('accounts/library', $data);
        $this->load->view('footer');


    }

    function library_get($type, $id)
    {
        $library = array();

        $this->load->model('novels');

        $i = 0;
        $query = $this->db->query('SELECT * FROM `library` WHERE `book_status` = \''.$type.'\' AND `library_user` = \''.$id.'\'');
        foreach ($query->result() as $item)
        {
            ++$i;
            $library[$i] = array();
            $library[$i]['id'] = $item->book_id;
            $novel = $this->novels->get_novel($item->book_id);
            if ($novel['chapters'] == 0 || $novel['chapters'] == '')
            {
                $library[$i]['total_chapters'] = '??';
            } else {
                $library[$i]['total_chapters'] = round($novel['chapters']);
            }
            if ($item->chapters_read == 0 || $item->chapters_read == '')
            {
                $library[$i]['chapter_count'] = '??';
            } else {
                $library[$i]['chapter_count'] = round($item->chapters_read);
            }
            $library[$i]['novel'] = '<a href="'.$novel['listing_url'].'">'.$novel['title'].'</a> by '.$novel['author_pen'].'';
            $library[$i]['rating'] = $item->book_rating;
        }

        return $library;
    }

    function addchapter()
    {
        if ($this->users->logged_in == true)
        {
            $book_id = intval($this->input->post('bookID'));

            $story = $this->db->get_where('stories', array('story_id' => $book_id, 'story_approved' => '1'));

            $query = $this->db->query('SELECT * FROM `library` WHERE `book_id` = \''.$book_id.'\' AND `library_user` = \''.$this->users->cur_user['user_id'].'\' LIMIT 1');
            if ($query->num_rows() == 1 && $story->num_rows() == 1)
            {

                $book = $query->row();
                $story = $story->row();

                $chapters = round($book->chapters_read+1);

                $data['chapters_read'] = $chapters;

                $this->db->where(array('book_id' => $book_id, 'library_user' => $this->users->cur_user['user_id']));
                $this->db->update('library', $data);

                $newupdate = array();
                $newupdate['user_id'] = $this->users->cur_user['user_id'];
                $newupdate['update_type'] = 1;
                $newupdate['update_date'] = time();
                $newupdate['update_rel_id'] = $book_id;

                if ($book->book_status == 'current')
                {
                    $status = 'Reading';
                } elseif ($book->book_status == 'onhold') {
                    $status = 'On-Hold';
                } elseif ($book->book_status == 'planned') {
                    $status = 'Plan to Read';
                } elseif ($book->book_status == 'complete') {
                    $status = 'Completed Reading';
                } elseif ($book->book_status == 'dropped') {
                    $status = 'Dropped';
                }

                $newupdate['update_text'] = $status.' - '.$chapters.' of '.(($story->chapter_total != 0) ? $story->chapter_total : '??').' Chapters';
                $newupdate['update_title'] = $story->story_title;
                $newupdate['update_link'] = site_url('browse/view/'.$story->story_slug);
                $this->db->insert('updates', $newupdate);

            }
        }
    }

    function minuschapter()
    {
        if ($this->users->logged_in == true)
        {
            $book_id = intval($this->input->post('bookID'));

            $story = $this->db->get_where('stories', array('story_id' => $book_id, 'story_approved' => '1'));

            $query = $this->db->query('SELECT * FROM `library` WHERE `book_id` = \''.$book_id.'\' AND `library_user` = \''.$this->users->cur_user['user_id'].'\' LIMIT 1');
            if ($query->num_rows() == 1 && $story->num_rows() == 1)
            {

                $book = $query->row();
                $story = $story->row();

                $chapters = round($book->chapters_read-1);

                $data['chapters_read'] = $chapters;

                $this->db->where(array('book_id' => $book_id, 'library_user' => $this->users->cur_user['user_id']));
                $this->db->update('library', $data);

                $newupdate = array();
                $newupdate['user_id'] = $this->users->cur_user['user_id'];
                $newupdate['update_type'] = 1;
                $newupdate['update_date'] = time();
                $newupdate['update_rel_id'] = $book_id;

                if ($book->book_status == 'current')
                {
                    $status = 'Reading';
                } elseif ($book->book_status == 'onhold') {
                    $status = 'On-Hold';
                } elseif ($book->book_status == 'planned') {
                    $status = 'Plan to Read';
                } elseif ($book->book_status == 'complete') {
                    $status = 'Completed Reading';
                } elseif ($book->book_status == 'dropped') {
                    $status = 'Dropped';
                }

                $newupdate['update_text'] = $status.' - '.$chapters.' of '.(($story->chapter_total != 0) ? $story->chapter_total : '??').' Chapters';
                $newupdate['update_title'] = $story->story_title;
                $newupdate['update_link'] = site_url('browse/view/'.$story->story_slug);
                $this->db->insert('updates', $newupdate);

            }
        }
    }    
    
    function set_field_value($field_name, $default_value)
    {
        if ($this->validation->$field_name == '')
        {
            return $default_value;
        } else {
            return $this->validation->$field_name;
        }
    }

}
?>
