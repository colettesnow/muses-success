<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h1>Review Detail</h1>

<p>From this page, you may edit this review before publication.</p>

<p>Remember, our review requirements are as follows:</p>

<ul>
        <li>The review must attempt to use correct spelling and grammar. We will correct a reasonable amount of mistakes, but anymore then that and it should be rejected. Spelling mistakes in the tagline almost ensure that a review will not be posted.</li>
        <li>The review must be original.</li>
        <li>The review must not contain excessive coarse language or tasteless remarks.</li>
        <li>The review must be longer then 100 words.</li>
        <li>The review should not contain spoilers.</li>
        <li>The review must not contain your name or email address, and other irrelevant information.</li>
</ul>

<form method="post">
        <?php echo $this->validation->error_string; ?>
        <p><label for="tagline">Tagline</label> <input type="text" name="tagline" id="tagline" value="<?php echo $form['tagline']; ?>" /></p>
        <p>You should keep edits to the review text to spelling mistakes.</p>
        <p><label for="review">Review Text</label> <textarea name="review" id="review"><?php echo $form['review_text']; ?></textarea></p>
        <p><label for="rating">Rating</label> 8 / 10</p>
        <p>If your going to reject this review, please select a reason:</p>
        <p><label for="reason">Reason for Rejection</label>

        <select name="reason">
                <option value="Spelling / Grammar Issues" <?php echo $this->validation->set_select('reason', 'Spelling / Grammar Issues'); ?>>Spelling / Grammar Issues</option>
                <option value="Novel or Author Bashing" <?php echo $this->validation->set_select('reason', 'Novel or Author Bashing'); ?>>Novel or Author Bashing</option>
                <option value="Excessive Coarse Language" <?php echo $this->validation->set_select('reason', 'Excessive Coarse Language'); ?>>Excessive Coarse Language</option>
                <option value="Plagiarism" <?php echo $this->validation->set_select('reason', 'one'); ?>>Plagiarism</option>
                <option value="Irrelevant Information" <?php echo $this->validation->set_select('reason', 'Irrelevant Information'); ?>>Irrelevant Information</option>
                <option value="Word Length Met, But Due to Padding" <?php echo $this->validation->set_select('reason', 'Word Length Met, But Due to Padding'); ?>>Word Length Met, But Due to Padding</option>
                <option value="Language Other Then English" <?php echo $this->validation->set_select('reason', 'Language Other Then English'); ?>>Language Other Then English</option>
                <option value="Overall Poor Quality" <?php echo $this->validation->set_select('reason', 'Overall Poor Quality'); ?>>Overall Poor Quality</option>
                <option value="Other" <?php echo $this->validation->set_select('reason', 'Other'); ?>>Other</option>
        </select></p>
        
        <p>If you chose other, please elaborate. You may also use this field to go into detail about any of the other reasons. Regardless of the review text, please be formal and polite.</p>
        <p><label for="rejectionreason">Detailed Reason:</label> <textarea name="rejectionreason"><?php echo $this->validation->rejectionreason; ?></textarea></p>
        <p>Points are rewarded to contributors with each contribution they make. The standard amount of points for a review is 10 points. For exceptional reviews, you may use your discretion to modify this number. The maximum is 20 points and a minimum of 8.</p>
        <p><label for="points">Contribution Points</label> <input type="text" name="points" id="points" value="<?php echo $form['points']; ?>" size="2" maxlength="2" /></p>
        <p><input type="submit" name="submit" value="Approve" /> <input type="submit" name="disapprove" value="Disapprove" /></p>
</form>
