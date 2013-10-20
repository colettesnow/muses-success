<h1>Recent Recommendations <a href="<?php echo site_url('recommendations/make'); ?>" class="edit-btn"><small>Make a Recommendation</small></a></h1>

<?php foreach ($recommendations as $recommendation)
{ ?>
<div class="recommendation">
            <p class="liked"><a href="<?php echo $recommendation['listing_url']; ?>"><img src="<?php echo $this->thumbnails->get($recommendation['listing_slug']); ?>" class="thumbnail-smallest" alt="Thumbnail" height="60" width="90" /></a> If you liked<br /><?php echo $recommendation['listing']; ?></p>
            <p class="mightlike"><a href="<?php echo $recommendation['similar_url']; ?>"><img src="<?php echo $this->thumbnails->get($recommendation['similar_slug']); ?>" class="thumbnail-smallest" alt="Thumbnail" height="60" width="90" /></a> ...then you might like<br /><?php echo $recommendation['similar_to']; ?></p>    
	<div class="recommendation_comment"><?php echo $recommendation['comment']; ?></div>
	<p><small>Recommended by <?php echo $recommendation['by']; ?> - <?php echo $recommendation['date']; ?></small></p>	
</div>
<?php } ?>
