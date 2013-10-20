<h1>Log In</h1>

<form method="post">
        <?php echo $this->validation->error_string; ?>
        <p><label for="username">Username</label> <input type="text" id="username" name="username" value="<?php echo $this->validation->username;?>" maxlength="20" /></p>
        <p><label for="password">Password</label> <input type="password" id="password" name="password" value="<?php echo $this->validation->password;?>" maxlength="25" /></p>
        <p><input type="submit" name="login" value="Log In" /></p>
        <p>Don't have an account yet? <a href="<?php echo site_url('accounts/register'); ?>">Register</a>, it's free!</p>
        <p><a href="<?php echo site_url('accounts/lostpass/'); ?>">Have you forgotten your password?</a></p>
</form>
