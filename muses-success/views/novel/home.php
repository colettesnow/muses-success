<?php $tab = 'detail'; include 'listing_top.php'; ?>

<h3>Synopsis</h3>

<div class="summary">

<?php if (isset($subtitle) && $subtitle != "") {  ?><p><strong>"<?php echo $subtitle; ?>"</strong></p><?php } ?>

<div itemprop="description">

<?php if (isset($summary) && $summary != "") { echo $summary; } else {
    
    echo "<p>".$title." by ".$author_pen." is a ".strtolower($primary_genre)." novel updated ".strtolower($update_schedule).".</p>";  
    
} ?></div>

<style type="text/css">
.social_media_item {
	float: left;
	margin-right: 10px;
	min-width: 40px;
	height: 40px;
	vertical-align: middle;
}
</style>

<p><div class="social_media_item"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $listing_url; ?>" data-text="<?php echo $title; ?> by <?php echo $author_pen; ?> #weblit:" data-count="horizontal" data-via="muses_success" data-related="webnovels:5-10 random web fiction per day from Muse's Success' listings.">Tweet</a></div><div class="social_media_item"><div class="g-plusone" data-width="120" data-size="medium" data-href="<?php echo $listing_url; ?>"></div></div><div class="social_media_item"><div class="fb-like" data-href="<?php echo $listing_url; ?>" data-send="false" data-layout="button_count" data-width="80" data-show-faces="false" data-action="recommend"></div></div></p>

<div class="clear"></div>

<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>

</div>

<h3>Tags <span class="edit-btn"><a
        href="<?php echo site_url('tags/manage/'.$id.'/'.$slug.''); ?>">Add/Remove
Tags</a></span></h3>

<p><?php if (strlen($tags) != 0) { echo $tags; } else { echo $title." has not been tagged yet. Help others find ".$title." by <a href=\"".site_url("tags/manage/".$id."/".$slug)."\">tagging it</a>!"; } ?></p>

<h3>Reviews <span class="edit-btn"><?php if ($this->users->logged_in == true) { ?><a
        href="<?php echo site_url('contribute/submit_review/'.$id.''); ?>">Write
Review</a><?php } else { ?><a href="<?php echo site_url('accounts/login'); ?>">Login</a> to review.<?php } if ($review_count >= 1) { ?> | <a href="<?php echo $review_url; ?>">More Reviews</a><?php if ($review_count > 2) { ?>
 (<?php echo $review_count; ?>)<?php } } ?></span></h3>

<div class="listing_reviews"><?php if (count($reviews) >= 1) { foreach ($reviews as $review) { ?>

<div class="review hreview">

<div class="review_user">
<div class="avatar"><a href="<?php echo $review['profile_url']; ?>"> <img
        src="<?php echo str_replace('s=100', 's=60', $review['avatar']); ?>"
        alt="Gravatar/Avatar" /> </a></div>
<div class="user_info">
<p class="reviewer"><a href="<?php echo $review['profile_url']; ?>"
        class="url"><span class="fn"><?php echo $review['username']; ?></span></a></p>
<p><?php echo $review['helpful']; ?> out of <?php echo $review['total_ratings']; ?>
 users found this review helpful</p>
<p><a
        href="<?php echo site_url('profile/reviews/'.$review['user_id'].''); ?>">Other
reviews by <?php echo $review['username']; ?></a></p>
</div>
<div class="clear"></div>
</div>

<p class="review_tagline"><strong>"<span class="summary"><?php echo $review['tagline']; ?></span>"</strong>
<a href="<?php echo $review['review_url']; ?>" class="permalink"
        rel="self bookmark">permalink</a>
<div class="review_rating"><strong>Rating:</strong> <span class="rating"><?php echo $review['rating']; ?></span>
/ <span class="best">10</span></div>
</p>

<div class="review_text"><?php echo character_limiter($review['review'], 400); ?>
<a href="<?php echo $review['review_url']; ?>">Read More</a>
</p>
</div>

<p class="report_link"><a
        href="<?php echo site_url('report/review/'.$review['id'].'/'); ?>">Report</a></p>
</div>

<?php } } else {
        if ($this->users->logged_in == true) {
                   echo "<p>".$title." has not been reviewed yet. Be the first and <a href=\"".site_url("contribute/submit_review/".$id)."\">write one</a>.</p>";
        } else {
            echo "<p>".$title." has not been reviewed yet.</p>";        
        }
} ?></div>

<h3>Recommendations</h3>

<div class="recommendations">

<?php if (count($recommendations) >= 1) {
    foreach ($recommendations as $recommendation) {
        ?>

<div class="recommendation">

<div class="thumbnail"><a href=""><img
        src="<?php echo $this->thumbnails->get($recommendation['similar_id'], 90, 120); ?>"
        height="90" width="120" class="thumbnail-smaller"
        alt="Thumbnail for <?php echo $title; ?>" /></a></div>

<div class="details">
<h4><?php echo $recommendation['similar_to']; ?></h4>

        <?php echo $recommendation['comment']; ?>

<p><small>Recommended by <?php echo $recommendation['by']; ?> - <a
        href="<?php echo site_url('report/recommendation/'.$recommendation['id'].'/'.$recommendation['user_id'].'/'.$recommendation['listing_id'].'/'.$recommendation['similar_id'].''); ?>">Report</a></small></p>
</div>

</div>

        <?php }
} else { ?>
<p><?php echo $title; ?> has not been recommended yet. <?php 

if ($this->users->logged_in == true) {  
    
    if ($in_bookshelf != false) { ?>
    <a href="<?php echo site_url("recommendations/make/".$id); ?>">Recommend It!</a><?php 
    } else { ?>You can recommend it but you must <a href="#addbookshelf">add it to your bookshelf</a> first.<?php } } ?></p>
<?php } ?>

</div>

<h3>Comments</h3>

<div class="comments">

<?php if (count($comments) >= 1) {
    foreach ($comments as $comment) {
        ?>

                <div class="comment">
                        <div class="avatar"><a href="<?php echo $comment['profile']; ?>"><img src="<?php echo $comment['avatar']; ?>" height="60" width="60" alt="Avatar" /></a></div>
                        <div class="comment_text">                        
                                <p class="commentor"><?php echo $comment['user']; ?> says 
                                <span class="comment_date"><?php echo $comment['date']; ?> | <a href="<?php echo site_url('report/comment/'.$comment['id'].''); ?>">Report</a></span></p>
                                        
                        <?php echo $comment['comment']; ?>                                                
                        </div>
                        <div class="anchor"></div>
                </div>

        <?php }
} else { ?>

<p>No one has commented on <?php echo $title; ?> yet.</p>

<?php } ?>

</div>

<?php if ($this->users->logged_in == true && (isset($older_revision) == false)) { ?>

<h3>Write Comment</h3>

<form method="post" action="<?php echo site_url("contribute/add_comment"); ?>">
        <p><textarea name="comment" id="addcommentform"></textarea></p>
        <input type="hidden" name="listing_id" value="<?php echo $id; ?>" />
        <p><input type="submit" name="add" value="Add Comment" /></p>
</form>

<?php } ?>

</div>

</div>
<script type="text/javascript">
  window.___gcfg = {lang: 'en-GB'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=163926932039";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>