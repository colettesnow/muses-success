<h1>Change Password</h1>

<form method="post">
<?php echo $this->validation->error_string; ?>
<p><label for="old_password">Current Password</label> <input type="password" name="old_password" id="old_password" /></p>
<p><label for="new_password">New Password</label> <input type="password" name="new_password" id="new_password" /></p>
<p><label for="confirm_password">Confirm Password</label> <input type="password" name="confirm_password" id="confirm_password" /></p>
<p><input type="submit" name="submit" value="Change Password" /></p>
</form>
