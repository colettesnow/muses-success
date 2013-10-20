<h1>Reset Website Thumbnail for <?php echo $listing['title']; ?></h1>

<p>This tool will allow you to request that a new screenshot/thumbnail be generated for <a href="<?php echo $listing['listing_url']; ?>"><?php echo $listing['title']; ?></a>. You may wish to do this if the screenshot automatically provided by <a href="http://thumbshots.org/">Thumbshots.org</a> is outdated or not displaying correctly. You may request a thumbnail reset only once per month.</p>

<p>The current thumbnail for <?php echo $listing['title']; ?> is:</p>

<p><img src="<?php echo $listing['thumbnail_url']; ?>" /></p>

<p>To request that a new thumbnail be generated for <?php echo $listing['title']; ?>, press the following button.</p>

<?php if ($too_soon == 1) { ?>

<form method="post" action=""><input type="submit" name="reset" value="Reset Thumbnail" /></form>

<?php } else { ?>

<p>You last requested a thumbnail reset on <?php echo $date_req; ?>. </p>

<?php } ?>