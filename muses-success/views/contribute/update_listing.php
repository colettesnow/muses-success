<h1>Submit Updated Information for an Existing Listing</h1>

<form method="post">
        <div class="notice">
        <h3>Before you edit:</h3>
        <ul class="submissionguide">
            <li>Read the <a href="<?php echo site_url('pages/guidelines') ?>">submission guidelines</a>!</li>
        </ul>
        </div>
        <?php echo $this->validation->error_string; ?>
        <p><label for="title">Title <span class="required">*</span></label> <input type="text" name="title" id="title" value="<?php echo $form['title']; ?>" /> <span class="help">Use title case as specified by <a href="">Wikipedia</a>. If a series with multiple books, see the <a href="<?php echo site_url('pages/guidelines') ?>">guidelines</a> for how you should format the title.</span></p>
        <p><label for="tagline">Tagline <span class="required">*</span></label> <input type="text" name="tagline" id="tagline" value="<?php echo $form['tagline']; ?>" /> <span class="help">Memorable phrase or sentence that sums up the tone and premise of the web fiction.</span></p>
        <p><label for="story_url">Read URL <span class="required">*</span></label> <input type="text" name="story_url" id="story_url" value="<?php echo $form['story_url']; ?>" /> <span class="help">This should link to either the web fictions blurb page, table of contents or the first chapter, in that order of preference.</span></p>
        <p><label for="story_rss">RSS Feed URL</label> <input type="text" name="story_rss" id="story_rss" value="<?php echo $form['story_rss']; ?>" /><span class="help">URL to the web fiction's official RSS feed containing the latest chapters, preferably only containing chapters.</span></p>
        <p><label for="purchase_url">Purchase URL</label> <input type="text" name="purchase_url" id="purchase_url" value="<?php echo $form['purchase_url']; ?>" /> <span class="help">URL to where a print or e-book version of the story can be purchased such as <a href="http://amazon.com">Amazon</a> or <a href="http://lulu.com">Lulu</a>. Please no affiliate links.</span></p>

        <hr />
        <p><label for="author_pen">Author's Name <span class="required">*</span></label> <input type="text" name="author_pen" id="author_pen" value="<?php echo $form['author_pen']; ?>" /><span class="help">Given Name followed by Surname or pen name if real name is not available. If multiple authors, separate each using commas.</span></p>
        <p><label for="author_hp">Author's Homepage</label> <input type="text" name="author_hp" id="author_hp" value="<?php echo $form['author_hp']; ?>" /> <span class="help">URL to the authors blog or homepage. If multiple authors, separate homepages using pipes (|) in the same order as above.</p>
        <hr />
        <p><label for="chapter_count">Chapter Count:</label> <input type="text" style="width: 20px;" name="chapter_count" value="<?php echo $form['chapter_count']; ?>" id="chapter_count" /> <span class="help">If completed, abandoned or on hiatus, the total number of chapters published. Otherwise, leave blank.</p></p>              
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
        </select><span class="help">Preferably the official genres as specified by the author. Otherwise, use your best judgement.</span></p>
        <p><label for="update_schedule">Update Schedule</label> <select name="update_schedule" id="update_schedule">
        <option value="none">Please Select</option>
        <?php foreach ($update_schedules as $upsched)
        {
                echo '<option value="'.$upsched.'" '.(($form['update_schedule'] == $upsched) ? ' selected="selected"' : '').'>'.$upsched.'</option>';
        }
        ?>
        </select> <span class="help help_upsched">How often is the web fiction updated? If the author doesn't say, take a look at duration between post dates of each chapter and see if you can see a pattern.</span></p>
        <p class="radio"><span class="label">Mature Content:</span>
        <div class="mature">
        <div class="coarse">
                <label><input type="radio" name="coarse" value="1" <?php echo ($form['coarse'] == 1) ? ' checked="checked"' : ''; ?>> No Coarse Language</label>
                <label><input type="radio" name="sex" value="1" <?php echo ($form['sex'] == 1) ? ' checked="checked"' : ''; ?>> No Sexual Content</label>
                <label><input type="radio" name="violent" value="1" <?php echo ($form['violent'] == 1) ? ' checked="checked"' : ''; ?>> No Violent Content</label>
              
        </div>
        <div class="sex">
                <label><input type="radio" name="coarse" value="2" <?php echo ($form['coarse'] == 2) ? ' checked="checked"' : ''; ?>> Some Coarse Language</label>
                <label><input type="radio" name="sex" value="2" <?php echo ($form['sex'] == 2) ? ' checked="checked"' : ''; ?>> Some Sexual Content</label>
                <label><input type="radio" name="violent" value="3" <?php echo ($form['violent'] == 3) ? ' checked="checked"' : ''; ?>> Frequent Violent Content</label>
        </div>
        <div class="violent">
                <label><input type="radio" name="coarse" value="3" <?php echo ($form['coarse'] == 3) ? ' checked="checked"' : ''; ?>> Frequent Language</label>
                <label><input type="radio" name="sex" value="3" <?php echo ($form['sex'] == 3) ? ' checked="checked"' : ''; ?>> Frequent Sexual Content</label>
                <label><input type="radio" name="violent" value="2" <?php echo ($form['violent'] == 2) ? ' checked="checked"' : ''; ?>> Some Violent Content</label>
        </div>
        </div>
        </p>
        <div class="clear"></div>
        <p><label for="audience">Intended Audience</label> <input type="text" name="audience" value="<?php echo $form['audience']; ?>" id="audience" /> <span class="help">Comma-separated list of demographics and interest groups.</span></p>
        <hr />
        <p><label for="synopsis">Synopsis</label> <textarea name="synopsis" id="synopsis"><?php echo $form['synopsis']; ?></textarea> <span class="help help_synopsis">Short description of the main story. No spoilers. If the web fiction requires registration to read, mention that here.<br /><br />If you didn't write the synopsis yourself, be sure to cite your source at the end. See the guidelines for the preferred way to do this.</span></p>
        <hr />
        <p><label for="comment">Edit Summary</label> <textarea name="comment" id="comment"></textarea> <span class="help help_synopsis">Short summary describing your edit.</span></p>
        <p><input type="submit" name="approve" value="Submit Update" /></p>
        <p class="fineprint">Fields marked with <span class="required">*</span> (asterisk/star) are required.</p>
</form>
