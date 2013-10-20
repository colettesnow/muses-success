<h1>Submit New Web Fiction</h1>

<form method="post">
        <div class="notice">
        <h3>Before you begin:</h3>
        <ul class="submissionguide">
            <li>Read the <a href="<?php echo site_url('pages/guidelines') ?>">submission guidelines</a>!</li>
            <li><a href="<?php echo site_url('search') ?>">Search the catalogue</a> to see if we already have information about this web fiction.</li>
        </ul>
        </div>
        <?php echo $this->validation->error_string; ?>
        <p><label for="title">Title: <span class="required">*</span></label> <input type="text" name="title" id="title" value="<?php echo $this->validation->title; ?>" /> <span class="help">Use title case as specified by <a href="">Wikipedia</a>. If a series with multiple books, see the <a href="<?php echo site_url('pages/guidelines') ?>">guidelines</a> for how you should format the title.</span></p>
        <p><label for="tagline">Tagline: <span class="required">*</span></label> <input type="text" name="tagline" id="tagline" value="<?php echo $this->validation->tagline; ?>" /> <span class="help">Memorable phrase or sentence that sums up the tone and premise of the web fiction.</span></p>
        <p><label for="story_url">Read URL: <span class="required">*</span></label> <input type="text" name="story_url" id="story_url" value="<?php echo $this->validation->story_url; ?>" /> <span class="help">This should link to either the web fictions blurb page, table of contents or the first chapter, in that order of preference.</span></p>
        <p><label for="story_rss">RSS Feed URL:</label> <input type="text" name="story_rss" id="story_rss" value="<?php echo $this->validation->story_rss; ?>" /> <span class="help">URL to the web fiction's official RSS feed containing the latest chapters, preferably only containing chapters.</span></p>
        <p><label for="purchase_url">Purchase URL:</label> <input type="text" name="purchase_url" id="purchase_url" value="<?php echo $this->validation->purchase_url; ?>" /> <span class="help">URL to where a print or e-book version of the story can be purchased such as <a href="http://amazon.com">Amazon</a> or <a href="http://lulu.com">Lulu</a>. Please no affiliate links.</span></p>
        <hr />
        <p><label for="author_pen">Author Name: <span class="required">*</span></label> <input type="text" name="author_pen" id="author_pen" value="<?php echo $this->validation->author_pen; ?>" /> <span class="help">Given Name followed by Surname or pen name if real name is not available. If multiple authors, separate each using commas.</span></p>
        <p><label for="author_hp">Author URL: </label> <input type="text" name="author_hp" id="author_hp" value="<?php echo $this->validation->author_hp; ?>" /> <span class="help">URL to the authors blog or homepage. If multiple authors, separate homepages using pipes (|).</span></p>
        <hr />
        <p><label for="chapter_count">Chapter Count:</label> <input type="text" style="width: 30px;" name="chapter_count" value="<?php /* echo $this->validation->chapter_count; */ ?>" id="chapter_count" /> <span class="help">If completed, abandoned or on hiatus, the total number of chapters published. Otherwise, leave blank.</p></p>        
        <p><label for="genres">Genre(s): <span class="required">*</span></label> <select name="primary_genre" id="genres">
        <option>Primary Genre</option>
        <?php foreach ($genres as $genre)
        {
                echo '<option value="'.$genre.'" '.$this->validation->set_select('primary_genre', $genre).'>'.$genre.'</option>';
        }
        ?>
        </select> / <select name="secondary_genre">
        <option>Secondary Genre:</option>
        <option value="None">None</option>
        <?php foreach ($genres as $genre)
        {
                echo '<option value="'.$genre.'" '.$this->validation->set_select('secondary_genre', $genre).'>'.$genre.'</option>';
        }
        ?>
        </select> <span class="help">Preferably the official genres as specified by the author. Otherwise, use your best judgement.</span></p>
        <p><label for="update_schedule">Update Schedule:</span></label> <select name="update_schedule" id="update_schedule">
        <option value="none">Please Select</option>
        <?php foreach ($update_schedules as $upsched)
        {
                echo '<option value="'.$upsched.'">'.$upsched.'</option>';
        }
        ?>
        </select> <span class="help help_upsched">How often is the web fiction updated? If the author doesn't say, take a look at duration between post dates of each chapter and see if you can see a pattern.</span></p>
        <p class="radio"><span class="label">Mature Content:</span>
        <div class="mature">
        <div class="coarse">
                <label><input type="radio" name="coarse" value="1" <?php echo $this->validation->set_radio('coarse', '1'); ?>> No Coarse Language</label>
                <label><input type="radio" name="sex" value="1" <?php echo $this->validation->set_radio('sex', '1'); ?>> No Sexual Content</label>
                <label><input type="radio" name="violent" value="1" <?php echo $this->validation->set_radio('violent', '1'); ?>> No Violent Content</label>
                
 </div>
        <div class="sex">
                <label><input type="radio" name="coarse" value="2" <?php echo $this->validation->set_radio('coarse', '2'); ?>> Some Coarse Language</label>
                <label><input type="radio" name="sex" value="2" <?php echo $this->validation->set_radio('sex', '2'); ?>> Some Sexual Content</label>
                <label><input type="radio" name="violent" value="2" <?php echo $this->validation->set_radio('violent', '2'); ?>> Some Violent Content</label>

        </div>
        <div class="violent">
                <label><input type="radio" name="coarse" value="3" <?php echo $this->validation->set_radio('coarse', '3'); ?>> Frequent Language</label>
                <label><input type="radio" name="sex" value="3" <?php echo $this->validation->set_radio('sex', '3'); ?>> Frequent Sexual Content</label>
                <label><input type="radio" name="violent" value="3" <?php echo $this->validation->set_radio('violent', '3'); ?>> Frequent Violent Content</label>
    
        </div>
        </div>
        </p>
        <div class="clear"></div>
        <p><label for="audience">Intended Audience:</label> <input type="text" name="audience" value="<?php echo $this->validation->audience; ?>" id="audience" /> <span class="help">Comma-separated list of demographics and interest groups.</span></p>
        <hr />
        <p><label for="synopsis">Synopsis:</label> <textarea name="synopsis" id="synopsis"><?php echo $this->validation->synopsis; ?></textarea> <span class="help help_synopsis">Short description of the main story. No spoilers. If the web fiction requires registration to read, mention that here.<br /><br />If you didn't write the synopsis yourself, be sure to cite your source at the end. See the guidelines for the preferred way to do this.</span></p>
        <hr />
        <p><label for="comment">Edit Summary:</label> <textarea name="comment" id="comment">Initial commit.</textarea> <span class="help help_synopsis">Short summary describing your edit.</span></p>
        <p><input type="submit" name="submit" value="Submit Listing" /></p>
        <p class="fineprint"><span class="required">*</span> indicates a required field.</p>
</form>
