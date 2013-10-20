<h1>Submit New Review</h1>

<h3>Review Requirements</h3>
<ul>
<li>The review should use correct spelling and grammar.</li>
<li>The review must be original.</li>
<li>The review must not contain excessive coarse language or tasteless remarks.</li>
<li>The review must be at least 100 words.</li>
<li>The review should not contain spoilers.</li>
<li>The review must not contain your name or email address, and other irrelevant information.</li>
</ul>
<h3>The Review</h3>
<form method="post">
<?php echo $this->validation->error_string; ?>
<p><label>Review For</label> <a href="<?php echo $novel['listing_url']; ?>" target="_new"><?php echo $novel['title']; ?></a></p>
<p>The tagline is your review title, shown both on the review list page and as the first line of your review.</p>
<p><label for="tagline">Tagline</label> <input type="text" name="tagline" value="<?php echo $this->validation->tagline; ?>" /></p>
<p>The rating is the overall rating you are giving the web novel, shown both on the review list page and at the bottom of your review.</p>
<p><label for="rating">Rating</label> <select name="rating">
        <option>Please Select</option>
        <option value="1">1 / 10 - Unreadable</option>
        <option value="2">2 / 10 - Terrible</option>
        <option value="3">3 / 10 - Bad</option>
        <option value="4">4 / 10 - Poor</option>
        <option value="5">5 / 10 - Alright</option>
        <option value="6">6 / 10 - Fair</option>
        <option value="7">7 / 10 - Good</option>
        <option value="8">8 / 10 - Great</option>
        <option value="9">9 / 10 - Outstanding</option>
        <option value="10">10 / 10 - Flawless</option>
</select></p>
<p>Enter the text of your review below.</p>
<p><label for="review">Review Text</label> <textarea name="review" style="height: 400px;"><?php echo $this->validation->review; ?></textarea></p>
<p><input type="submit" name="submit" value="Submit Review" /></p>
</form>