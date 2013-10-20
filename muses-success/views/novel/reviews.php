<?php $tab = 'reviews'; include 'listing_top.php'; ?>

<div class="listing_reviews">

        <?php if (count($reviews) == 0): ?>
        <p>This web fiction has not been reviewed just yet. If you have read <?php echo $title; ?>, please <a href="<?php echo site_url('contribute/submit_review/'.$id.''); ?>">submit a review</a>.</p>
        <?php endif; ?>

    <?php foreach ($reviews as $review) { ?>

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

            <p class="review_tagline"><strong>"<span class="summary"><?php echo $review['tagline']; ?></span>"</strong> <a href="<?php echo $review['review_url']; ?>" class="permalink" rel="self bookmark">permalink</a> <div class="review_rating"><strong>Rating:</strong> <span class="rating"><?php echo $review['rating']; ?></span> / <span class="best">10</span></div></p>

            <div class="review_text">
                <?php echo ($review['review']); ?>
            </div>
            <p class="helpful_summary">Did you find this review helpful? <a href="<?php echo site_url("".$slug."/reviews/helpful/".intval($review['id']).""); ?>">Helpful</a> <a href="<?php echo site_url("".$slug."/reviews/nothelpful/".intval($review['id']).""); ?>">Not Helpful</a></p>

            <p class="report_link"><a href="<?php echo site_url('report/review/'.$review['id'].'/'); ?>">Report</a></p>
        </div>

    <?php } ?>
</div>
    
</div>
