<h1>Edit Profile</h1>

<form method="post">
<?php echo $this->validation->error_string; ?>
<p><label for="display_name">Display Name</label> <input type="text" name="display_name" id="display_name" value="<?php echo $field_display_name;?>"  /></p>
<p><label for="email_address">Email Address</label> <input type="text" name="email_address" id="email_address" value="<?php echo $field_email_address;?>"  /></p>
<p><label for="website_url">Website URL</label> <input type="text" name="website_url" id="website_url" value="<?php echo $field_website_url;?>"  /></p>
<p><label for="gender">Gender</label> <input type="text" name="gender" id="gender" value="<?php echo $field_gender; ?>"  /> (Male, Female, Neutrois, Androgyne, etc)</p>
<p><label for="location">Location</label> <input type="text" name="location" id="location" value="<?php echo $field_location;?>"  /></p>
<hr />
<p><label for="windowslive">Windows Live IM</label> <input type="text" name="windowslive" id="windowslive" value="<?php echo $field_windowslive;?>"  /></p>
<p><label for="yahoo">Yahoo IM</label> <input type="text" name="yahoo" id="yahoo" value="<?php echo $field_yahoo;?>"  /></p>
<p><label for="aol">America Online IM</label> <input type="text" name="aol" id="aol" value="<?php echo $field_aol;?>"  /></p>
<p><label for="google">Google Talk IM</label> <input type="text" name="google" id="google" value="<?php echo $field_google;?>"  /></p>
<hr />
<p><label for="signature">Signature</label> <textarea name="signature" id="signature"><?php echo $field_signature;?></textarea></p>
<hr />
<p><input type="submit" value="Update Preferences" /></p>
</form>
