<h1>Contribute to Muse's Success</h1>

<?php if ($this->users->logged_in == true) { ?>

<p><a href="<?php echo site_url('contribute/new_listing/'); ?>">Submit a Web Novel / Serial</a></p>
<p><a href="<?php echo site_url('contribute/update_listing/'); ?>">Submit Updated Information for an Existing Listing</a></p>
<p><a href="<?php echo site_url('contribute/submit_review/'); ?>">Submit a Review</a></p>
<?php /* <p><a href="<?php echo site_url('authorcp'); ?>">Manage My Web Fiction</a></p>
<p><a href="<?php echo site_url('contribute/link_request/'); ?>">Link My Web Fiction with My Muse's Success Account</a></p> */ ?>
<?php if ($this->users->cur_user['access_level'] >= 9) { ?>
<p><a href="<?php echo site_url('contribute/queue/'); ?>">Contribution Queue</a></p>
<?php } ?>
<?php } else { ?>

<p>You must be <a href="<?php echo site_url('accounts/login/'); ?>">logged in</a> to your Muse's Success account in order to contribute. If you don't have a Muse's Success account, you'll need to <a href="<?php echo site_url('accounts/register/'); ?>">register</a> first. Registration is free and available to anyone with a valid e-mail address.</p>

<?php } ?>
