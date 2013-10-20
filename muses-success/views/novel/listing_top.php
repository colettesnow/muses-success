<div class="edit-btn-box"<?php if ($feature_image_position != 'habove') { echo " style=\"margin-top: -22px !important; margin-right: 20px;float:right;\""; } ?>><?php if ($this->users->logged_in == true) { ?><a href="<?php echo site_url('contribute/update_listing/'.$id.''); ?>">Edit</a> | <a href="<?php echo site_url('tags/manage/'.$id.'/'.$slug.''); ?>">Modify Tags</a><?php } else { ?><a href="<?php echo site_url('accounts/login'); ?>">Login</a> to edit.<?php } ?> | <a href="<?php echo site_url('changeset/history/'.$id.''); ?>">History</a></div>

<?php if ($feature_image_position == 'above') { ?><div class="feature_image" style="margin-top: 5px; margin-bottom: -10px;">
<a href="<?php echo $listing_url; ?>"><img src="http://static.sorrowfulunfounded.com/muses-success/images/feature/<?php echo $slug; ?>.jpg" width="940" border="0" /></a>
</div><?php } ?>
<div class="listing-heading">
    <h1>
        <span class="title" itemprop="name"><a href="<?php echo $url; ?>"><?php echo $title; ?></a></span> 
        <span class="by">by <span class="author"><?php echo $author_pen; ?></span></span>
    </h1>

</div>

<?php if ($feature_image_position == 'below') { ?><div class="feature_image">
<img src="http://static.sorrowfulunfounded.com/muses-success/images/feature/<?php echo $slug; ?>.jpg" width="940" />
</div><?php } ?>


<?php if (isset($older_revision) && $older_revision == true) { ?>

<p class="alert">
    This is an old revision of this page, as edited by <a href="<?php echo site_url('profile/view/'.$changeset['user_id'].''); ?>"><?php echo $changeset['user_name']; ?></a> at <?php echo date('h:i, d F Y', $changeset['date']); ?>. It may differ significantly from the <a href="<?php echo $listing_url; ?>">current revision</a>.     
</p>

<p><?php if ($previous_exists) { ?><?php if ($comparision_possible_with_last != 0) { ?>(<a href="<?php echo $previous_diff; ?>">Diff</a>)<?php } ?> <a href="<?php echo $previous_revision; ?>">« Previous Revision</a> | <?php } ?><a href="<?php echo $listing_url; ?>">Current Revision</a> <?php if ($previous_exists == 1) { ?>(<a href="<?php echo $current_diff; ?>">Diff</a>)<?php } if ($next_exists != 0) { ?> | <a href="<?php echo $newer_revision; ?>">Newer Revision »</a> (<a href="<?php echo $newer_diff; ?>">Diff</a>)<?php } ?></p>

<?php } ?>



<div class="listing-thumbnail">
    <a href="<?php echo $url; ?>"><img src="<?php echo $this->thumbnails->get($slug, 270, 360); ?>"  itemprop="image" height="210" width="280" class="thumbnail-medium" alt="Listing Thumbnail" onerror="this.src='http://muses-success.info/static/images/default-thumb-210.jpg'" /></a>
    
    <ul class="purchase-books">
        <li><a href="<?php echo $url; ?>">Read Book</a></li>
        <?php if (strlen($rss_feed) >= 10) { ?><li><a href="<?php echo $rss_feed; ?>">Subscribe via RSS</a></li><?php } ?>
        <?php if (strlen($purchase_link) >= 10) { ?><li><a href="<?php echo $purchase_link; ?>">Purchase</a></li><?php } ?>
        <?php if ($this->users->logged_in == true) { $cur = $this->users->cur_user['upload_thumbs']; if ($cur == 1) { ?><li><a href="http://static.sorrowfulunfounded.com/muses-success/images/web-fiction-thumbnails/upload.php?image=<?php echo $slug; ?>">Upload Thumbnail</a></li><?php } } ?>
    </ul>
    
    <h3>Rate Listing</h3>
    <?php $star_rating = round($rating, 0); ?>
        <div id="stars-wrapper1">
        <p class="rating">
                <input type="radio" name="newrate" value="1" title="Unreadable" <?php if ($star_rating == 1) { echo " checked=\"checked\""; } ?> />
                <input type="radio"  name="newrate" value="2" title="Terrible"<?php if ($star_rating == 2) { echo " checked=\"checked\""; } ?> />
                <input type="radio"  name="newrate" value="3" title="Bad"<?php if ($star_rating == 3) { echo " checked=\"checked\""; } ?> />
                <input type="radio"  name="newrate" value="4" title="Poor"<?php if ($star_rating == 4) { echo " checked=\"checked\""; } ?> />
                <input type="radio"  name="newrate" value="5" title="Alright"<?php if ($star_rating == 5) { echo " checked=\"checked\""; } ?> />
                <input type="radio"  name="newrate" value="6" title="Fair"<?php if ($star_rating == 6) { echo " checked=\"checked\""; } ?> />
                <input type="radio"  name="newrate" value="7" title="Good"<?php if ($star_rating == 7) { echo " checked=\"checked\""; } ?> />
                <input type="radio"  name="newrate" value="8" title="Great"<?php if ($star_rating == 8) { echo " checked=\"checked\""; } ?> />
                <input type="radio"  name="newrate" value="9" title="Outstanding"<?php if ($star_rating == 9) { echo " checked=\"checked\""; } ?> />
                <input type="radio"  name="newrate" value="10" title="Flawless"<?php if ($star_rating == 10) { echo " checked=\"checked\""; } ?> />
        </p>
        </div>
        <input type="hidden" name="listing_id" id="listing_id" value="<?php echo $id; ?>" />
   
    <p class="rated">Rated <?php echo round($rating, 2); ?> out of 10 <small><a href="#statistics">Statistics</a></small></p>
    
    <h3>Information</h3>

    <div class="detail_info">
    
        <?php if ($chapters != 0 && $chapters != '') { ?><p><strong>Chapters:</strong> <?php echo $chapters; ?></p><?php } ?>
        <p><strong>Genre:</strong> <a href="<?php echo $primary_genre_url; ?>"><?php echo $primary_genre; ?></a><?php if (strlen($secondary_genre) >= 3) { ?> / <a href="<?php echo $secondary_genre_url; ?>"><?php echo $secondary_genre; ?></a><?php } ?></p>
        <?php if ($intended_audience != 'Unknown') { ?><p><strong>Audience:</strong> <?php echo $intended_audience; ?></p><?php } ?>
        <?php if ($update_schedule != 'Unknown') { ?><p><strong>Updated:</strong> <?php echo $update_schedule; ?></p><?php } ?>

    </div>

    <?php if (round($sex) > 1 || round($coarse) > 1 || round($violence) > 1) { ?>
    <h3>Content Advisory</h3>
    
    <div class="detail_info">
        <?php if ($sex == 2) { echo '<p>Occasional Sexual Content</p>'; } elseif ($sex == 3) { echo '<p>Frequent Sexual Content</p>'; } ?>
        <?php if ($coarse == 2) { echo '<p>Occasional Coarse Language</p>'; } elseif ($coarse == 3) { echo '<p>Frequent Coarse Language</p>'; } ?>
        <?php if ($violence == 2) { echo '<p>Occasional Violence</p>'; } elseif ($violence == 3) { echo '<p>Frequent Violence</p>'; } ?>
    </div>
    <?php } ?>
    
    <h3 id="statistics">Statistics</h3>
    
    <div class="detail_info">
        <p><strong>Overall Rating:</strong> <?php echo round($rating,2); ?><sup>1</sup></p>
        <p><strong>Average Rating:</strong> <?php echo round($guest_average, 2); ?> (Guests), <?php echo round($user_average, 2); ?> (Members)</p>
        <p><strong>Ranking:</strong> #<?php echo $ranking; ?></p>
        <p><strong>Rating Count:</strong> <?php echo $rating_count; ?> (<?php echo $rating_guests_votes; ?> Guests, <?php echo $rating_member_votes; ?> members)</p>
        <p><sup>1</sup> indicates a weighted rating.</p>
    </div>
    
        <?php if ($this->users->logged_in == true) { ?>
        <?php if (!isset($older_revision)) { ?>
        <?php if ($in_bookshelf == false) { ?>

            <h3 id="addbookshelf">Add to Bookshelf</h3>
            <form method="post" action="<?php echo site_url("/browse/add_library"); ?>" class="addbookshelf"> 
                <p><label for="status">Status:</label> <select name="status" id="status"> 
                   <option value="0">Select</option> 
                   <option value="1">Currently Reading</option> 
                   <option value="2">Plan to Read</option> 
                   <option value="3">On Hold</option> 
                   <option value="4">Completed</option> 
                   <option value="5">Dropped</option> 
                   </select>
                </p>
                <p><label for="score">Score:</label> <select id="score" name="score"> 
                        <option value="0">Select</option> 
                        <option value="10">10 - Flawless</option> 
                        <option value="9">9 - Outstanding</option> 
                        <option value="8">8 - Great</option>
                        <option value="7">7 - Good</option> 
                        <option value="6">6 - Fair</option> 
                        <option value="5">5 - Alright</option> 
                        <option value="4">4 - Poor</option> 
                        <option value="3">3 - Bad</option> 
                        <option value="2">2 - Terrible</option> 
                        <option value="1">1 - Unreadable</option> 
                   </select></p>
                   <p><label for="progress">Progress:</label> 
                   <input type="text" name="progress" size="3" maxlength="3" /><?php if ($chapters != 0) { ?> / <?php echo $chapters; ?><?php } ?> Chapters
                    </p>
                    <input type="hidden" name="listing_id" value="<?php echo $id; ?>" /> 
                    <p><input type="submit" value="Add to Bookshelf" /></p>
                    
                <?php } else { ?>
            <h3 id="addbookshelf">Update Bookshelf</h3>                
            <form method="post" action="<?php echo site_url("/browse/add_library"); ?>" class="addbookshelf"> 
                <p><label for="status">Status:</label> <select name="status" id="status"> 
                   <option value="1"<?php if ($in_bookshelf["status"] == "current") { echo " selected=\"selected\""; } ?>>Currently Reading</option> 
                   <option value="2"<?php if ($in_bookshelf["status"] == "planned") { echo " selected=\"selected\""; } ?>>Plan to Read</option> 
                   <option value="3"<?php if ($in_bookshelf["status"] == "onhold") { echo " selected=\"selected\""; } ?>>On Hold</option> 
                   <option value="4"<?php if ($in_bookshelf["status"] == "complete") { echo " selected=\"selected\""; } ?>>Completed</option> 
                   <option value="5"<?php if ($in_bookshelf["status"] == "dropped") { echo " selected=\"selected\""; } ?>>Dropped</option> 
                   </select>
                </p>
                <p><label for="score">Score:</label> <select id="score" name="score"> 
                        <option value="10"<?php if ($in_bookshelf["score"] == 10) { echo " selected=\"selected\""; } ?>>10 - Flawless</option> 
                        <option value="9"<?php if ($in_bookshelf["score"] == 9) { echo " selected=\"selected\""; } ?>>9 - Outstanding</option> 
                        <option value="8"<?php if ($in_bookshelf["score"] == 8) { echo " selected=\"selected\""; } ?>>8 - Great</option>
                        <option value="7"<?php if ($in_bookshelf["score"] == 7) { echo " selected=\"selected\""; } ?>>7 - Good</option> 
                        <option value="6"<?php if ($in_bookshelf["score"] == 6) { echo " selected=\"selected\""; } ?>>6 - Fair</option> 
                        <option value="5"<?php if ($in_bookshelf["score"] == 5) { echo " selected=\"selected\""; } ?>>5 - Alright</option> 
                        <option value="4"<?php if ($in_bookshelf["score"] == 4) { echo " selected=\"selected\""; } ?>>4 - Poor</option> 
                        <option value="3"<?php if ($in_bookshelf["score"] == 3) { echo " selected=\"selected\""; } ?>>3 - Bad</option> 
                        <option value="2"<?php if ($in_bookshelf["score"] == 2) { echo " selected=\"selected\""; } ?>>2 - Terrible</option> 
                        <option value="1"<?php if ($in_bookshelf["score"] == 1) { echo " selected=\"selected\""; } ?>>1 - Unreadable</option> 
                   </select></p>
                   <p><label for="progress">Progress:</label> 
                   <input type="text" name="progress" size="3" maxlength="3" value="<?php echo round($in_bookshelf["progress"]); ?>" /><?php if ($chapters != 0) { ?> / <?php echo $chapters; ?><?php } ?> Chapters
                    </p>
                    <input type="hidden" name="listing_id" value="<?php echo $id; ?>" /> 
                    <p><input type="submit" value="Update Bookshelf" /></p>         
                
                <?php } ?>
                </form>
                <?php } } ?> 
    
</div>

<div class="top-listing-right">

    <?php
    
    $menu_items = array(
        'detail' => array('Detail', $listing_url),
        'reviews' => array('Reviews', $review_url),
        'recommendations' => array('Recommendations', $recommendation_url),
        'readers' => array('Readers', $reader_url)
    );
    ?>
    <ul class="ui_element_tabs">
    <?php foreach ($menu_items as $current => $item) { ?>
        <?php if (($current == 'detail') || ($current == 'reviews' && count($reviews) >= 1) || ($current == 'recommendations' && count($recommendations) >= 1) || ($current == 'readers' && ($in_bookshelf != false || count($in_bookshelf) != 0))) { ?><li<?php if ($current == $tab) { echo ' class="current"'; } ?>><a href="<?php echo $item[1]; ?>"><?php echo $item[0]; ?></a></li><?php } ?>
    <?php } ?>
    </ul>
    
<?php if ($is_in_index == 2) { ?>
<p class="alert"><strong><?php echo $title; ?></strong> is no longer available online. This page is kept around for archival reasons and does not appear in our main index.</p>
<?php  } 
elseif ($is_in_index == 1) { ?>
<p class="alert"><strong><?php echo $title; ?></strong> is no longer available freely (with or without registration) and thus, no longer meets our <a href="<?php echo site_url('p/guidelines'); ?>">submission guidelines</a> for listable web fiction. This page is kept around for archival reasons and does not appear in our main index.</p>
<?php } ?>
