<?php $name = ($user_info['display_name'] != '') ? ucwords($user_info['display_name']) : ucwords($user_info['screen_name']); ?><div class="profile_head">
        <div class="avatar">
                <img src="http://www.gravatar.com/avatar.php?gravatar_id=<?php echo md5($user_info['email_address']); ?>&size=100&d=identicon" />
        </div>
        <div class="user_blurb">
                <h1><?php echo ($user_info['display_name'] != '') ? ucwords($user_info['display_name']) : ucwords($user_info['screen_name']); ?>'s Profile</h1>

                <p><a href="<?php echo site_url('pms/compose/'.$user_info['user_id']); ?>">Send Message</a></p>
        </div>
        <div class="clear"></div>
</div>

<ul class="ui_element_tabs">
        <li><a href="<?php echo site_url('profile/view/'.$user_info['user_id'].'/'); ?>">Home</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$user_info['user_id'].'/'); ?>">Bookshelf</a></li>
        <li class="current">Reviews</li>
</ul>

<div class="reviews">

<?php foreach ($reviews as $review) { ?>

<div class="review">

        <p class="rating_review"><?php echo $review['rating']; ?>/10</p>
        <h3><a href="<?php echo $review['review_url']; ?>"><?php echo $review['tagline']; ?></a></h3>
        <p>A review of <?php echo $review['story']; ?></p>
            
        <?php echo $this->novels->truncate($review['text'], 200); ?>
        
        <p><a href="<?php echo $review['review_url']; ?>">Read Full Review</a></p>
</div>     
<?php } ?>
</div>

<div class="clear"></div>
