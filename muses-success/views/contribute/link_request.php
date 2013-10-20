<h1>Link My Web Fiction with My Muse's Success Account</h1>

<p>You can use this form to claim that you are the author of a listing (a web novel or serial).</p>

<p>The benefits of linking your account with fiction we list that you have written include:</p>

<ul>
	<li><strong>Announce New Chapters</strong> (and set our chapter field)<br /><small>No longer will the bookshelf contend that your fiction is ?? chapters in length.</small></li>
	<li><strong>Request a thumbnail reset</strong><br /><small>Useful if your listing's thumbnail is outdated or Thumbshots.org doesn't provide a thumbnail at all.</small></li>
	<li><strong>Publications Tab Added to Your Profile</strong><br /><small>New profile tab listing web fiction that you have written.</small></li>
</ul>

<p>To facilitate the linkage, we request that you supply us with the URL of the listing for the web fiction which you wrote. Please make sure that you have an email address on your web fiction's website. We will use this address, and not the address in your Muse's Success profile in order to verify your claim.</p>

<?php echo $this->validation->error_string; ?>

<form method="post" action="<?php echo site_url('contribute/link_request'); ?>">
	<p>
		<label for="url">Listing URL:</label> 
		<span class="slug_field">http://muses-success.info/<input type="text" maxlength="100" name="url" class="slug_field" id="url" />/</span>
	</p>
	<p>
		<input type="submit" name="request" value="Request Linkage" />
	</p>
</form>