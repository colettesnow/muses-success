<h1>Reviews <a href="<?php echo site_url('contribute/submit_review'); ?>" class="edit-btn"><small>Write Review</small></a></h1>

<ul class="ui_element_tabs">
	<li<?php if ($this->uri->segment(2) != 'most-helpful') { echo ' class="current"'; } ?>><a href="<?php echo site_url('reviews'); ?>">Recent Reviews</a></li>
	<li<?php if ($this->uri->segment(2) == 'most-helpful') { echo ' class="current"'; } ?>><a href="<?php echo site_url('reviews/most-helpful'); ?>">Most Helpful</a></li>
</ul>

<div class="reviews main_reviews">

	<?php foreach ($reviews as $review) { ?>
	
		<div class="review hreview">
		
			<p class="review_time"><?php echo date_relative($review['date']); ?></p>
		
			<p class="review_title item"><span class="fn"><a href="<?php echo $review['listing_url']; ?>" class="url"><strong><?php echo $review['title']; ?></strong></a> <span class="author">by <?php echo $review['author']; ?></span></span></p>
				<div class="clear"></div>
			<div class="review_user">
				<div class="avatar">
					<a href="<?php echo $review['profile_url']; ?>">
						<img src="<?php echo $review['avatar']; ?>" alt="Gravatar/Avatar" />
					</a>
				</div>
				<div class="user_info">
				 	<p class="reviewer"><a href="<?php echo $review['profile_url']; ?>" class="url"><span class="fn"><?php echo $review['username']; ?></span></a></p>
					<p><?php echo $review['helpful']; ?> out of <?php echo $review['total_ratings']; ?> users found this review helpful</p>
					<p><a href="<?php echo site_url('profile/reviews/'.$review['user_id'].''); ?>">Other reviews by <?php echo $review['username']; ?></a></p>
				</div>
				<div class="clear"></div>
			</div>
		
			<p class="review_tagline"><strong>"<span class="summary"><?php echo $review['tagline']; ?></span>"</strong> <a href="<?php echo $review['review_url']; ?>" class="permalink" rel="self bookmark">permalink</a> <div class="review_rating"><strong>Rating:</strong> <span class="rating"><?php echo $review['rating']; ?></span> / <span class="best">10</span></div></p>

			<div class="review_text">
				<?php echo $review['review']; ?>
			</div>
			
			<p class="report_link"><a href="<?php echo site_url('report/review/'.$review['id'].'/'); ?>">Report</a></p>
		</div>
	
	<?php } ?>

</div>
