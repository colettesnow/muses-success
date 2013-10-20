<div class="column lcol">

    <h1>Latest Reviews <a href="<?php echo site_url('reviews'); ?>" class="edit-btn more more-hp">see more…</a></h1>

        <div class="homepage_revs">

    <?php foreach ($reviews as $review): ?>

    <div class="hreview">

        <div class="review_blurb">
            <p class="rating"><?php echo $review['rating']; ?> out of <span class="best">10</span></p>

            <div class="review_thumb item">
                <a href="<?php echo $review['read_url']; ?>" class="url"><img class="photo thumbnail-smaller" src="<?php echo $this->thumbnails->get($review['slug']); ?>" alt="<?php echo $review['title']; ?>'s Thumbnail"  title="Read <?php echo $review['title']; ?>" height="90" width="120" border="0" onerror="this.src='http://muses-success.info/static/images/default-thumb-90.jpg'" /></a>
            </div>

            <div class="review_title">
                <p class="item fn"><a href="<?php echo $review['listing_url']; ?>"><strong><?php echo $review['title']; ?></strong></a><br /> by <?php echo $review['author']; ?></p>
                <p class="reviewer">Reviewed by <a href="<?php echo $review['profile_url']; ?>" class="url"><span class="fn"><?php echo $review['username']; ?></span></a>, <span class="dtreviewed" title="<?php echo standard_date('DATE_ISO8601', $review['date']); ?>"><?php echo date_relative($review['date']); ?></span></p>
            </div>
        </div>

        <div class="clear"></div>

        <div class="description"><?php echo character_limiter($review['review'], 140); ?> <a href="<?php echo $review['review_url']; ?>" rel="self bookmark" class="permalink">Read More</a></p></div>

        <p class="report_link"><a href="<?php echo site_url('report/review/'.$review['id'].'/'); ?>">Report</a></p>

    </div>

    <?php endforeach; ?>

    </div>

    <div class="homepage-recs">

    <h1>Latest Recommendations <a href="<?php echo site_url('recommendations'); ?>" class="edit-btn more more-hp">see more…</a></h1>

    <?php foreach ($recommendations as $recommendation): ?>

        <div class="recommendation">

            <p class="liked"><a href="<?php echo $recommendation['listing_url']; ?>"><img src="<?php echo $this->thumbnails->get($recommendation['listing_slug']); ?>" class="thumbnail-smallest" alt="Thumbnail" height="60" width="90" onerror="this.src='http://muses-success.info/static/images/default-thumb-90.jpg'" /></a> If you liked<br /><?php echo $recommendation['listing']; ?></p>
            <p class="mightlike"><a href="<?php echo $recommendation['similar_url']; ?>"><img src="<?php echo $this->thumbnails->get($recommendation['similar_slug']); ?>" class="thumbnail-smallest" alt="Thumbnail" height="60" width="90" onerror="this.src='http://muses-success.info/static/images/default-thumb-90.jpg'" /></a> ...then you might like<br /><?php echo $recommendation['similar_to']; ?></p>

            <div class="clear"></div>

            <!--- mostly useless div, but we can't style markdown easily --->
            <div class="rec_comment">
                <?php echo $recommendation['comment']; ?>
            </div>

            <p class="reced_by">Recommended by <?php echo $recommendation['by']; ?> - <?php echo date_relative($recommendation['date_raw']); ?></p>

            <p class="report_link"><a href="<?php echo site_url('report/recommendation/'.$recommendation['id'].'/'.$recommendation['user_id'].'/'.$recommendation['listing_id'].'/'.$recommendation['similar_id'].''); ?>">Report</a></p>

        </div>

    <?php endforeach; ?>

    </div>

</div>

<div class="column rcol">


<?php if ($this->users->logged_in == false) { ?>

        <h1>What is Muse's Success?</h1>

        <div class="welcome_info">

            <p>Muse's Success is a directory of freely available web novels and serials, collectively web fiction.</p>

            <p>Muse's Success is a structured wiki. That means anyone is free to contribute to our database. All content has been contributed by members of the web fiction community like you.</p>

            <p><strong>Last Updated:</strong> <?php echo $last_updated; ?>. (<a href="<?php echo site_url('recent-changes'); ?>">Recent Changes</a>)</p>

            <p><strong>New Blog Post:</strong> <a href="http://blog.muses-success.info/2012/10/13/feature-images-added-to-listing-pages/">‘Feature Images’ feature added to Listing Pages
</a> (<time datetime="2012-10-13">13/10/2012</time>)</p>

            <p><a href="http://twitter.com/muses_success" title="Follow Muse's Success on Twitter" rel="me"><img src="http://static.sorrowfulunfounded.com/blog/buttons/twitter.png" alt="Twitter" /></a> <a href="https://www.facebook.com/muses.success" title="Like Muse's Success on Facebook" rel="me"><img src="http://static.sorrowfulunfounded.com/blog/buttons/facebook.png" alt="Facebook" /></a> <a href="https://plus.google.com/108660956896900398737" title="Circle Muse's Success on Google+" rel="me"><img src="http://www.google.com/images/icons/ui/gprofile_button-32.png" alt="Google+" /></a></p>

        </div>

 <?php } else { ?>

         <h1>Currently Reading <a href="<?php echo site_url('accounts/bookshelf'); ?>" class="edit-btn more more-hp">manage bookshelf</a></h1>

         <?php if (count($currently_reading) != 0): ?>

         <?php foreach ($currently_reading as $reading): ?>
         <ul class="newest">
                 <li><span style="font-size: 8pt;"><?php echo $reading['author']; ?></span><br /><a href="<?php echo $reading['id']; ?>"><?php echo $reading['title']; ?></a><br /><small>Chapter <span id="read_num_<?php echo $reading['id']; ?>"><?php echo $reading['read_chapter_count']; ?></span><?php if ($reading['total_chapter_count'] != 0) { ?> of <?php echo $reading['total_chapter_count']; ?><?php } ?> <a href="javascript:minuschapter(<?php echo $reading['id']; ?>);" title="Decrease read chapter count">-</a> / <a href="javascript:addchapter(<?php echo $reading['id']; ?>);" title="Increase read chapter count">+</a></small></li>
         </ul>
         <?php endforeach; else: ?>
         <p>You are not currently reading anything. Add a book to your bookshelf to track your progress. Once you do you will be able to update your read chapter count from this page.</p> <p>How do I <a href="<?php echo site_url('faqs#perma-17'); ?>">add a book to my bookshelf</a>?</p>

         <p><a href="<?php echo site_url('accounts/bookshelf'); ?>">Manage My Bookshelf</a></p>
 <?php endif; } ?>

        <h1>Newest Additions</h1>

        <ul class="newest">
        <?php foreach ($newest_novels as $novel): ?>
            <li><a href="<?php echo $novel['listing_url']; ?>"><?php echo $novel['title']; ?></a> by <?php echo $novel['author_pen']; ?></li>
        <?php endforeach; ?>
        </ul>

        <h1>Statistics</h1>
        <div class="welcome_info">
<?php if ($this->users->logged_in == true) { ?>
           <p><strong>Last Updated:</strong> <?php echo $last_updated; ?>. (<a href="<?php echo site_url('recent-changes'); ?>">Recent Changes</a>)</p>

            <p><strong>New Blog Post:</strong> <a href="http://blog.muses-success.info/2012/10/13/feature-images-added-to-listing-pages/">‘Feature Images’ feature added to Listing Pages
</a> (<time datetime="2012-10-13">13/10/2012</time>)</p>
<?php } ?>

            <p>We currently list <a href="<?php echo site_url('browse'); ?>"><?php echo $stats['total_novels']; ?> novels</a> containing <a href="<?php echo site_url('reviews'); ?>"><?php echo $stats['total_reviews']; ?> reviews</a> which have been rated <?php echo $stats['total_ratings']; ?> times.</p>

            <p>We have <?php echo $stats['total_users']; ?> members. A warm welcome goes out to <?php echo $stats['newest_member']; ?>, our newest member.</p>
        </div>
</div>