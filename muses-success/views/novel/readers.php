<?php $tab = 'readers'; include 'listing_top.php'; ?>

<div class="clear"></div>

<div class="bigbox">

<p>This is a list of Muse's Success members who have added <?php echo $title; ?> to their bookshelf grouped by status.</p>

<?php $x = 0; if (count($reading) > 0) { ?>
<table style="width:100%;">
		<tr><th colspan="2">Currently Reading</th></tr>
        <?php foreach ($reading as $library_user) { ?>
                <tr>
                        <td width="90%"><?php echo $library_user['user']; ?></td>
                        <td><?php if ($library_user['rating'] >= 1 && $library_user['rating'] <= 10) { echo $library_user['rating']; ?> / 10<?php } else { echo 'Hasn\'t Rated'; } ?></td>
                </tr>
        <?php } ?>
</table>
<?php $x = 1; } ?>

<?php if (count($complete) >= 1) { ?>
<table style="width:100%;">
		<tr><th colspan="2">Completed Reading</th></tr>
        <?php foreach ($complete as $library_user) { ?>
                <tr>
                        <td width="90%"><?php echo $library_user['user']; ?></td>
                        <td><?php if ($library_user['rating'] >= 1 && $library_user['rating'] <= 10) { echo $library_user['rating']; ?> / 10<?php } else { echo 'Hasn\'t Rated'; } ?></td>
                </tr>
        <?php } ?>
</table>
<?php $x = 1; } ?>

<?php if (count($plan) >= 1) { ?>
<table style="width:100%;">
		<tr><th colspan="2">Plan to Read</th></tr>		
        <?php foreach ($plan as $library_user) { ?>
                <tr>
                        <td width="100%"><?php echo $library_user['user']; ?></td>
                </tr>
        <?php } ?>
</table>
<?php $x = 1; } ?>

<?php if (count($onhold) >= 1) { ?>

<table style="width:100%;">
		<tr><th colspan="2">On Hold</th></tr>
        <?php foreach ($onhold as $library_user) { ?>
                <tr>
                        <td width="90%"><?php echo $library_user['user']; ?></td>
                        <td><?php if ($library_user['rating'] >= 1 && $library_user['rating'] <= 10) { echo $library_user['rating']; ?> / 10<?php } else { echo 'Hasn\'t Rated'; } ?></td>
                </tr>
        <?php } ?>
</table>
<?php $x = 1; } ?>

<?php if (count($dropped) >= 1) { ?>
<table style="width:100%;">
		<tr><th colspan="2">Dropped</th></tr>
        <?php foreach ($dropped as $library_user) { ?>
                <tr>
                        <td width="90%"><?php echo $library_user['user']; ?></td>
                        <td><?php if ($library_user['rating'] >= 1 && $library_user['rating'] <= 10) { echo $library_user['rating']; ?> / 10<?php } else { echo 'Hasn\'t Rated'; } ?></td>
                </tr>
        <?php $x = 1; } ?>
</table>
<?php } if ($x == 0) { echo '<p>Unfortunately, no member of Muse\'s Success has added this title to their bookshelf yet.</p>'; }?>

</div>
</div>

<div class="clear"></div>
