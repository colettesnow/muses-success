<h1>Create a New Muse's Success Account </h1>

<form method="post" action="<?php echo site_url('accounts/register'); ?>" name="registration">
        <p>Creating an account is completely free and painless. We just need to know your preferred username, password, and email address.</p>
        
        <?php echo $this->validation->error_string; ?>
        <p><label for="username">Username</label> <input type="text" id="uxn232da" name="username" value="<?php echo $this->validation->username;?>" /></p>
        <p><label for="password">Password (<a href="javascript:show_hide();" id="show_hide">show</a>)</label> <input type="password" id="password" name="password" value="<?php echo $this->validation->password;?>" /></p>
        <p><label for="email_address">Email Address</label> <input type="text" id="email_address" name="email_address" value="<?php echo $this->validation->email_address;?>" /></p>
        <p><label for="captcha">Security Code</label><?php echo $captcha ?></p>
        <p><input type="submit" name="register" value="Register" /></p>
</form>

<script type="text/javascript">
	function show_hide()
	{
		if (document.getElementById("show_hide").innerHTML == "show")
		{
			registration.password.type = "text";
			document.getElementById("show_hide").innerHTML = "hide";
		} else if (document.getElementById("show_hide").innerHTML == "hide")
		{
			registration.password.type = "password";
			document.getElementById("show_hide").innerHTML = "show";		
		}
	}
</script>