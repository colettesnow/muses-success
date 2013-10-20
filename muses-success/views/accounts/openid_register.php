<h1>OpenID Account Setup</h1>
<form method="post" action="<?php echo site_url('accounts/openid_register'); ?>">
<?php echo $this->validation->error_string; ?>
<p>Thank you for logging in using OpenID. Since this is your first time logging in, we have a few things we need to know. You will not be asked again to provide this information. You must set this information now to continue browsing the site. We mainly need this information to avoid referring to you as openid-xxxx.</p>
<p><label for="display_name">Display Name <span class="required">*</span>*</label> <input type="text" name="display_name" /></p>
<p><label for="email_address">Email Address <span class="required">*</span></label> <input type="text" name="email_address" /></p>
<p><input type="submit" name="continue_login" value="Continue Login" /></p>
</form>
