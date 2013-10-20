<div class="clear"></div>
<div class="listing_reviews">
		<div class="review hreview">

            <div class="review_user">
                <div class="avatar">
                    <a href="<?php echo $review['profile_url']; ?>">
                        <img src="<?php echo str_replace('s=100', 's=60', $review['avatar']); ?>" alt="Gravatar/Avatar" />
                    </a>
                </div>
                <div class="user_info">
                     <p class="reviewer"><a href="<?php echo $review['profile_url']; ?>" class="url"><span class="fn"><?php echo $review['username']; ?></span></a></p>
                    <p><?php echo $review['helpful']; ?> out of <?php echo $review['total_ratings']; ?> users found this review helpful</p>
                    <p><a href="<?php echo site_url('profile/reviews/'.$review['user_id'].''); ?>">Other reviews by <?php echo $review['username']; ?></a></p>
                </div>
                <div class="clear"></div>
            </div>


			<div class="hidden-micro-format-data">
				<div class="item">
					<span class="fn"><?php echo $title; ?> by <?php echo $author_pen; ?></span>
					<span class="type">product</span>
					<a href="<?php echo $url; ?>" class="url">Read This</a>
				</div>
				<a href="<?php echo site_url('reviews/view/'.$review['id'].''); ?>" rel="bookmark" class="permalink">Permalink</a>
			</div>
		    
		    <p><strong>"<span class="summary"><?php echo $review['tagline']; ?></span>"</strong></p>
		    
		    <?php echo $review['review']; ?>
		    
		    <p><strong>Rating:</strong> <span class="rating"><?php echo $review['rating']; ?></span>/<span class="best">10</span> </p>

		</div>

        <p><strong>Rate this review:</strong><br />Did you find this review helpful and/or informative?</p>

        <?php if ($rating_error == 0) { ?>

        <form method="post">
                <p><input type="submit" name="rate" value="Yes" /> <input type="submit" name="rate" value="No" /></p>
        </form>
        
        <?php } elseif ($rating_error == 1) { ?>
                <p class="error">Our records indicate that you have already rated this review. You can only rate reviews once per review.</p>
        <?php } elseif ($rating_error == 3) { ?>
                <p class="error">You must be <a href="<?php echo site_url('accounts/login'); ?>">logged in</a> to rate a review.</p>
        <?php } else { ?>
                <p>Thank you for rating this review.</p>
        <?php } ?>

</div>
</div>
