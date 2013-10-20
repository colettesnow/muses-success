<h1>Manage Account</h1>

<p>You are currently logged in as <a href="<?php echo site_url('profile/view/'.$this->users->cur_user['user_id'].'/'); ?>"><?php echo $this->users->cur_user['display_name']; ?></a>.</p>

<p>From this page, you can manage all aspects of your Muse's Success account.</p>

        <p><a href="<?php echo site_url('accounts/editprofile/'); ?>">Edit Profile</a><br />
        <small>Update your email address, signature, and other information.</small></p>
        <p><a href="http://en.gravatar.com/emails/">Avatar Preferences</a><br />
        <small>We use the <a href="http://gravatar.com">Gravatar.com</a> service for Avatars. If you want an avatar, sign up there with the email address associated with your account here at Muse's Success.</small></p>
        <p><a href="<?php echo site_url('accounts/bookshelf/'); ?>">My Bookshelf</a><br />
        <small>View and manage the contents of your bookshelf.</small></p>
        <?php /* <p><a href="<?php echo site_url('authorcp/'); ?>">My Web Fiction</a><br />
        <small>View and manage web novels and serials that you wrote that are listed on Muse's Success.</small></p> */ ?>
        <p><a href="<?php echo site_url('accounts/changepassword/'); ?>">Change Password</a><br />
        <small>Change your account login password for your Muse's Success account.</small></p>

