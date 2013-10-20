<h1>Create a New Muse's Success Account </h1>

<form method="post" action="<?php echo site_url('accounts/register'); ?>" name="registration">
        <p>Creating an account is completely free and painless. We just need to know your preferred username, password, and email address.</p>
        
        <?php echo $this->validation->error_string; ?>
        <p><label for="uxn232da">Username</label> <input type="text" id="uxn232da" name="a35099353beea9ca858de0e4c02d2dfee" value="<?php echo $this->validation->a35099353beea9ca858de0e4c02d2dfee;?>" /></p>
        <p><label for="pwxw34xna">Password (<a href="javascript:show_hide();" id="show_hide">show</a>)</label> <input type="password" id="pwxw34xna" name="R6JZS7fjyIxO8oqSoSxg" value="<?php echo $this->validation->R6JZS7fjyIxO8oqSoSxg;?>" /></p>
        <p><label for="e32ml23u7a">Email Address</label> <input type="text" id="e32ml23u7a" name="a119b78fdcd7b53be6e51989da6de4220" value="<?php echo $this->validation->a119b78fdcd7b53be6e51989da6de4220;?>" /></p>
        <p><label for="captcha">Security Code</label><?php echo $captcha ?></p>
        <p><input type="submit" name="register" value="Register" /></p>
</form>

<script type="text/javascript">
	function show_hide()
	{
		if (document.getElementById("show_hide").innerHTML == "show")
		{
			registration.R6JZS7fjyIxO8oqSoSxg.type = "text";
			document.getElementById("show_hide").innerHTML = "hide";
		} else if (document.getElementById("show_hide").innerHTML == "hide")
		{
			registration.R6JZS7fjyIxO8oqSoSxg.type = "password";
			document.getElementById("show_hide").innerHTML = "show";		
		}
	}
</script>