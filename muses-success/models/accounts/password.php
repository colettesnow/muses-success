<?php

class Password extends Model {

    function Password()
    {
        parent::Model();
    }

    function send_password_reset_email($email)
    {
    		$this->load->library('email');

			$this->email->from('no-reply@muses-success.info', 'Muse\'s Success');
			$this->email->to($email);

			$this->email->subject('Password Reset Confirmation');
			$this->load->helper('string');
			$check_name = random_string('alnum','20');
			$this->email->message('Hello,

You or someone claiming to be you has requested that your Muse\'s Success password be reset.

To reset your password, please visit the following URL:

'.site_url('accounts/passconf/'.$check_name.'/').'

If you did not reset your password, please ignore this email.

Regards,
Muse\'s Success Staff');

			$this->db->insert('updatepass', array('email_address' => $email, 'check_id' => $check_name));

			$this->email->send();
    }

    function send_new_password($confirmation_id)
    {
    	$check = $this->db->query('SELECT `email_address` FROM `updatepass` WHERE `check_id` = '.$this->db->escape($confirmation_id).' LIMIT 1');
		if ($check->num_rows() == 1)
		{
			$usermail = $check->row();
			$useremail = $usermail->email_address;

			$this->load->helper('string');

			$secure_salt = random_string('alnum', 10);
			$new_password = random_string('alnum', 15);

			$this->db->where('email_address', $useremail);
			$this->db->update('users', array('password' => sha1($new_password.$secure_salt), 'secure_salt' => $secure_salt));
			$this->db->where('email_address', $useremail);
			$this->db->delete('updatepass');

			$this->load->library('email');

			$this->email->from('no-reply@muses-success.info', 'Muse\'s Success');
			$this->email->to($useremail);

			$this->email->subject('Password Reset - Your New Password');
			$this->email->message('Hello,

Your new password is: '.$new_password.'

Regards,
Muse\'s Success Staff');
			$this->email->send();

			return true;

		} else {

			return false;

		}
    }

}

?>
