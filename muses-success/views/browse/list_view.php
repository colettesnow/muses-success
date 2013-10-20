<?php
if (count($novels) >= 1) { ?>
<p><?php echo $this->pagination->create_links(); ?></p>
<?php $show_thumbnails = true; foreach ($novels as $novel) { ?>
<div class="listingthumb">
<p class="thumbnail">
<a href="<?php echo $novel['listing_url']; ?>"><img class="thumbnail-smaller" src="<?php echo $this->thumbnails->get($novel['slug']); ?>" alt="<?php echo $novel['title']; ?>" title="<?php echo $novel['title']; ?>" height="90" width="120" border="0"  onerror="this.src='http://muses-success.info/static/images/default-thumb-90.jpg'" /></a>
</p>
<p class="mini_listing">
<a href="<?php echo $novel['listing_url']; ?>"><strong><?php echo $novel['title']; ?></strong></a> by <?php echo (($novel['author_url']!='') ? '<a href="'.$novel['author_url'].'">'.$novel['author_pen'].'</a>' : $novel['author_pen']); ?><br />
<small><a href="<?php echo $novel['url']; ?>">Read This</a> - <?php echo $novel['primary_genre']; ?><?php echo (($novel['secondary_genre'] != '') ? ' / '.$novel['secondary_genre'] : ''); if ($novel['mature'] == 1) { ?> - Mature Content<?php } ?> - <?php echo round($novel['review_count']); ?> Reviews - Rated <?php echo floor($novel['rating']); ?> out of 10</small>
</p>
</div>
<?php }  ?>
<div class="clear"></div>
<p><?php echo $this->pagination->create_links(); ?></p>
</div>
    <?php } ?>

<div class="clear"></div>
</div>
<div class="ad">
<?php $this->load->view('ads/sidebar'); ?>
</div><div class="clear"></div>