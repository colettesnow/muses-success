<?php if ($success == false) { ?><h1>Report Inappropriate Review</h1>

<p>You have chosen to report the review "<?php echo $review['tagline']; ?>" for <?php echo $novel['title']; ?> as inappropriate. Please describe while you feel this is the case, and we will take appropriate action once we verify your claims.</p>

<?php echo form_open('reviews/report/'.$review['id'].''); ?>

<p><textarea name="reason" rows="6" cols="50"></textarea></p>

<p><input type="submit" name="add" value="Submit Report" /></p>

</form>
<?php } else { ?>

<h1>Review Reported</h1>

<p>Thank you for your report. It has been sent to an administrator and will be dealt with if he/she deems neccessary in accordance with our minimum submission requirements.</p>

<?php } ?>
