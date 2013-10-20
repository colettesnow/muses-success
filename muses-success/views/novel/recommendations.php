<?php $tab = 'recommendations'; include 'listing_top.php'; ?>

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

</div>

</div>