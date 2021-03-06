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
        $this->load->library('recaptchalib');

        $rules['username']    = "required|min_length[3]|max_length[20]|callback_check_username[exists]";
        $rules['password']    = "required|min_length[6]|max_length[25]|callback_check_password";
        $fields['recaptcha_response_field'] = 'required|callback_check_captcha';

        $this->validation->set_rules($rules);

        $fields['username']    = 'Username';
        $fields['password']    = 'Password';
        $fields['recaptcha_response_field'] = 'Security Code';

        $this->validation->set_fields($fields);

        $this->validation->set_error_delimiters('<p class="error">', '</p>');

        $this->load->view('header', array('page_title' => 'Log In', 'recaptcha_action' => 'login', 'breadcrumbs' => array('Log In')));
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

    function check_username($username, $check_for = 'exists')
    {
        $user = $this->db->get_where('users', array('screen_name' => $username));

        $user_count = 1;
        $error_message = 'We\'re sorry, but we cannot find an account for this username and/or password.';
        if ($check_for == 'unique')
        {
          $user_count = 0;
          $error_message = 'We\'re sorry, but the requested username is already in use.';
        }

        if ($user->num_rows() == $user_count)
        {
            return true;
        } else {
            $this->validation->set_message('check_username', $error_message);
            return false;
        }
    }

    function check_email_unique($email_address)
    {
        $user = $this->db->get_where('users', array('email_address' => $email_address));
        if ($user->num_rows() == 0)
        {
            return true;
        } else {
            $this->validation->set_message('check_email_unique', 'We\'re sorry, but the requested email address is already in use. If you have forgot your password, please use the <a href="/accounts/lostpass">Lost Password</a> form.');
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
        $this->load->library('Recaptchalib');

        $rules['username'] = "required|min_length[3]|max_length[20]|alpha_dash|callback_check_username[unique]";
        $rules['password'] = "required|min_length[6]";
        $rules['email_address'] = "required|valid_email|callback_check_email_unique";
        $rules['recaptcha_response_field'] = 'required|callback_check_captcha';

        $fields['username']    = 'Username';
        $fields['password']    = 'Password';
        $fields['email_address'] = 'Email Address';
        $fields['recaptcha_response_field'] = 'Security Code';

        $this->validation->set_rules($rules);
        $this->validation->set_fields($fields);

        $data = array(
                        'captcha' => ""
        );

        if ($this->validation->run() == FALSE)
        {
            $this->load->view('header', array('page_title' => 'Account Registration', 'breadcrumbs' => array('Register'), "recaptcha_action" => "register"));
            $this->load->view('accounts/register.php', $data);
        }
        else
        {
            $user_data = array(
                                'username' => $this->input->post('username'),
                                'password' => $this->input->post('password'),
                                'email_address' => $this->input->post('email_address'),
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
        $this->load->view('header', array('page_title' => 'Edit Profile', 'recaptcha_action' => 'editprofile', 'breadcrumbs' => array('<a href="'.site_url('accounts').'">My Account</a>', 'Edit Profile')));

        if ($this->users->logged_in == true)
        {
            $this->load->helper(array('form', 'url'));

            $this->load->library('validation');
            $this->load->library('recaptchalib');

            $rules['email_address']            = "required|valid_email|xss_clean";
            $rules['website_url']              = "prep_url|xss_clean";
            $rules['display_name']             = "alphanumeric|min_length[3]|max_length[25]";
            $rules['location']                 = "htmlspecialchars|min_length[3]|max_length[50]";
            $rules['gender']                   = "max_length[100]";
            $rules['windowslive']              = "htmlspecialchars|valid_email|max_length[320]";
            $rules['yahoo']                    = "htmlspecialchars|max_length[32]";
            $rules['aol']                      = "htmlspecialchars|min_length[3]|max_length[16]";
            $rules['google']                   = "htmlspecialchars|valid_email|max_length[320]";
            $rules['signature']                = "xss_clean|min_length[3]|max_length[500]";
            $rules['recaptcha_response_field'] = 'required|callback_check_captcha';

            $this->validation->set_rules($rules);

            $fields['email_address']            = "Email Address";
            $fields['website_url']              = "Website URL";
            $fields['display_name']             = "Display Name";
            $fields['location']                 = "Location";
            $fields['gender']                   = "Gender";
            $fields['windowslive']              = "Windows Live IM";
            $fields['yahoo']                    = "Yahoo IM";
            $fields['aol']                      = "AOL IM";
            $fields['google']                   = "Google Talk IM";
            $fields['signature']                = "Signature";
            $fields['recaptcha_response_field'] = 'Security Code';

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
        $this->load->view('header', array('page_title' => 'Change Password', 'recaptcha_action' => 'changepassword', 'breadcrumbs' => array('<a href="'.site_url('accounts').'">My Account</a>', 'Change Password')));

        if ($this->users->logged_in == true)
        {
            $this->load->helper(array('form', 'url'));

            $this->load->library('validation');
            $this->load->library('recaptchalib');

            $rules['old_password']              = "required|min_length[6]|max_length[25]|alpha_dash|callback_oldpasswordcorrect";
            $rules['new_password']              = "required|min_length[6]|max_length[25]|alpha_dash|matches[confirm_password]";
            $rules['confirm_password']          = "required|min_length[6]|max_length[25]|alpha_dash";
            $rules['recaptcha_response_field'] = 'required|callback_check_captcha';
            $this->validation->set_rules($rules);

            $fields['old_password']             = 'Current Password';
            $fields['new_password']             = 'New Password';
            $fields['confirm_password']         = 'Confirm Password';
            $fields['recaptcha_response_field'] = 'Security Code';
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
        $this->load->library('Recaptchalib');

        $rules['email_address'] = 'required|valid_email|callback_checkemail';
        $rules['recaptcha_response_field'] = 'required|callback_check_captcha';
        $this->validation->set_rules($rules);

        $fields['email_address'] = 'Email Address';
        $this->validation->set_fields($fields);

        $data = array(
            'captcha' => ''
        );

        $this->load->view('header', array('page_title' => 'Lost Password Recovery', 'recaptcha_action' => 'lostpass'));

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

    function check_captcha($val) 
    {
        $recaptcha = new \ReCaptcha\ReCaptcha(
            $this->config->item('recaptcha_secret_key')
        );
        $resp = $recaptcha->setExpectedHostname('muses-success.info')->verify(
            $this->input->post('recaptcha_response_field'), $_SERVER["CF-Connecting-IP"]
        );

        if ($resp->isSuccess()) {
            return true;
        } else {
            $this->validation->set_message( 
                'check_captcha',
                'Google Recaptcha verification failed: It thinks you are not human. If you believe this to be in error, please contact us.' 
            );
            return false;
        }
    }

    // set message
    function _set_message($msg, $val = '', $sub = '%s')
    {
        return str_replace($sub, $val, $this->lang->line($msg));
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
