<h1>Listing Detail</h1>

<form method="post">
        <h3>Submission Guidelines</h3>
        <?php if ($novel['is_update'] == 1)
        {
                echo '<p class="error">This is an update to the listing <a href="'.$original['listing_url'].'">'.$original['title'].'</a> and any changes the submitter has made will be made to the live listing upon approval. Also, please award points accordingly. The automatic point calculation does not work with updates.</p>';
        } ?>
        <p></p>
        <ul class="submissionguide">
                <li>We list both web novels and serials, but not short stories unless they are related in some way (ie. common theme, characters, world).</li>
                <li>The novel / serial must not be primarily classed as erotica. Written pornography is not acceptable.</li>
                <li>The novel / serial must be available freely via a web accessible medium.</li>
                <li>The novel / serial may be available in print but the full novel must be available to be read for free online.</li>
                <li>The novel / serial must be original. Fan Fiction is not acceptable.</li>
                <li>Information submitted using this form will be made available under the terms of the <a href="http://creativecommons.org/licenses/by-nc-sa/2.5/au/">Creative Commons Attribution-Noncommercial-Share Alike 2.5 Australia License</a>. You will be credited if your information is used.</li>
        </ul>
        <?php echo $this->validation->error_string; ?>
        <p><label for="title">Title <span class="required">*</span></label> <input type="text" name="title" id="title" value="<?php echo $form['title']; ?>" /></p>
        <p><label for="tagline">Tagline <span class="required">*</span></label> <input type="text" name="tagline" id="tagline" value="<?php echo $form['tagline']; ?>" /></p>
        <p><label for="story_url">Read URL <span class="required">*</span></label> <input type="text" name="story_url" id="story_url" value="<?php echo $form['story_url']; ?>" /></p>
        <p><label for="story_rss">RSS Feed URL</label> <input type="text" name="story_rss" id="story_rss" value="<?php echo $form['story_rss']; ?>" /></p>
        <p><label for="purchase_url">Purchase URL</label> <input type="text" name="purchase_url" id="purchase_url" value="<?php echo $form['purchase_url']; ?>" /></p>
        <hr />
        <p><label for="author_pen">Author's Name <span class="required">*</span></label> <input type="text" name="author_pen" id="author_pen" value="<?php echo $form['author_pen']; ?>" /></p>
        <p><label for="author_hp">Author's Homepage</label> <input type="text" name="author_hp" id="author_hp" value="<?php echo $form['author_hp']; ?>" /></p>
        <hr />
        <p><label for="genres">Genre(s) <span class="required">*</span></label> <select name="primary_genre" id="genres">
        <option>Primary Genre</option>
        <?php foreach ($genres as $genre)
        {
                echo '<option value="'.$genre.'" '.(($form['primary_genre'] == $genre) ? ' selected="selected"' : '').'>'.$genre.'</option>';
        }
        ?>
        </select> / <select name="secondary_genre">
        <option>Secondary Genre</option>
        <option value="None">None</option>
        <?php foreach ($genres as $genre)
        {
                echo '<option value="'.$genre.'" '.(($form['secondary_genre'] == $genre) ? ' selected="selected"' : '').'>'.$genre.'</option>';
        }
        ?>
        </select></p>
        <p><label for="update_schedule">Update Schedule:</label> <select name="update_schedule" id="update_schedule">
        <option value="none">Please Select</option>
        <?php foreach ($update_schedules as $upsched)
        {
                echo '<option value="'.$upsched.'" '.(($form['update_schedule'] == $upsched) ? ' selected="selected"' : '').'>'.$upsched.'</option>';
        }
        ?>
        </select></p>
        <p class="radio"><span class="label">Mature Content:</span>
        <div class="mature">
        <div class="coarse">
                <label><input type="radio" name="coarse" value="1" <?php echo ($form['coarse'] == 1) ? ' checked="checked"' : ''; ?>> No Coarse Language</label>
                <label><input type="radio" name="coarse" value="2" <?php echo ($form['coarse'] == 2) ? ' checked="checked"' : ''; ?>> Some Coarse Language</label>
                <label><input type="radio" name="coarse" value="3" <?php echo ($form['coarse'] == 3) ? ' checked="checked"' : ''; ?>> Frequent Language</label>
        </div>
        <div class="sex">
                <label><input type="radio" name="sex" value="1" <?php echo ($form['sex'] == 1) ? ' checked="checked"' : ''; ?>> No Sexual Content</label>
                <label><input type="radio" name="sex" value="2" <?php echo ($form['sex'] == 2) ? ' checked="checked"' : ''; ?>> Some Sexual Content</label>
                <label><input type="radio" name="sex" value="3" <?php echo ($form['sex'] == 3) ? ' checked="checked"' : ''; ?>> Frequent Sexual Content</label>
        </div>
        <div class="violent">
                <label><input type="radio" name="violent" value="1" <?php echo ($form['violent'] == 1) ? ' checked="checked"' : ''; ?>> No Violent Content</label>
                <label><input type="radio" name="violent" value="2" <?php echo ($form['violent'] == 2) ? ' checked="checked"' : ''; ?>> Some Violent Content</label>
                <label><input type="radio" name="violent" value="3" <?php echo ($form['violent'] == 3) ? ' checked="checked"' : ''; ?>> Frequent Violent Content</label>
        </div>
        </div>
        </p>
        <div class="clear"></div>
        <p><label for="audience">Intended Audience</label> <input type="text" name="audience" value="<?php echo $form['audience']; ?>" id="audience" /> (comma-seperated list, ie. Female, Teens)</p>
        <hr />
        <p><label for="synopsis">Synopsis</label> <textarea name="synopsis" id="synopsis"><?php echo $form['synopsis']; ?></textarea></p>
        <hr />
        <p>For listings without a synopsis, award 5 points. With a synopsis, award between 10 - 15 at your discretion.</p>
        <p><label for="points">Contribution Points:</label> <input type="text" name="points" id="points" value="<?php echo $form['points']; ?>" /></p>
        <hr />
        <p><input type="submit" name="approve" value="Approve Listing" /> <input type="submit" name="reject" value="Reject Listing" /></p>
        <p class="fineprint">Fields marked with <span class="required">*</span> (asterisk/star) are required.</p>
</form>
