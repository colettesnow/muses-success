<?php

class Disqus_sso {

	private $user = array();
	private $secret_key, $public_key;

	function set_secret_key($secret_key)
	{
		$this->secret_key = $secret_key;
	}

	function set_public_key($public_key)
	{
		$this->public_key = $public_key;
	}

	function set_user($id, $username, $email_address)
	{
		$this->user = array(
			'id' => $id,
			'username' => $username,
			'email' => $email_address
			);
	}

	function set_avatar($avatar)
	{
		$this->user['avatar'] = $avatar;
	}

	function set_url($avatar)
	{
		$this->user['url'] = $avatar;
	}	

	function get_secret_key()
	{
		return $this->secret_key;
	}

	function get_public_key()
	{
		return $this->public_key;
	}

	function get_hmac($data, $key)
	{
		// taken from PHP SSO recipe example
		// https://github.com/disqus/DISQUS-API-Recipes/blob/master/sso/php/sso.php
	    $blocksize=64;
	    $hashfunc='sha1';
	    if (strlen($key)>$blocksize)
	        $key=pack('H*', $hashfunc($key));
	    $key=str_pad($key,$blocksize,chr(0x00));
	    $ipad=str_repeat(chr(0x36),$blocksize);
	    $opad=str_repeat(chr(0x5c),$blocksize);
	    $hmac = pack(
	                'H*',$hashfunc(
	                    ($key^$opad).pack(
	                        'H*',$hashfunc(
	                            ($key^$ipad).$data
	                        )
	                    )
	                )
	            );
	    return bin2hex($hmac);
	}

	function get_remote_auth()
	{
		// taken from PHP SSO recipe example
		// https://github.com/disqus/DISQUS-API-Recipes/blob/master/sso/php/sso.php
		$message = base64_encode(json_encode($this->user));
		$timestamp = time();
		$hmac = $this->get_hmac($message . ' ' . $timestamp, $this->get_secret_key());
		return $message.' '.$hmac.' '.$timestamp;
	}

}
