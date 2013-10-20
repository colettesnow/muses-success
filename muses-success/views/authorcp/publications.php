<h1>Manage My Web Fiction</h1>

<div id="forums">

<table cellspacing="0" width="100%">
        <tr class="heading1">
                <th width="50%" colspan="2">Listing</th>
                <th>Chapters</th>
                <th>Tweet Quota</th>
                <th>Shelf Count</th>                
        </tr>
        <?php foreach ($publications as $publication) { ?>
        <tr class="content">
                <td><strong><a href="<?php echo $publication['listing_url']; ?>"><?php echo $publication['name']; ?></a></strong></td>
                <td width="20%" align="right"><small><?php if ($publication['review_count'] > 0) { ?><a href="<?php echo $publication['reviews_url']; ?>"><?php echo $publication['review_count']; ?> Reviews</a><?php } if ($publication['review_count'] > 0) { echo ' ,'; } if ($publication['comment_count'] > 0) { ?><a href="<?php echo $publication['listing_url']; ?>#comments"><?php echo $publication['comment_count']; ?> Comments<?php } ?></a></small></td>
                <td><?php echo $publication['chapter_count']; ?> <?php if ($publication['chapter_count'] > 0) { ?><a href="">+</a><?php } ?> (<a href="">Set</a>)</td>
                <td><?php echo $publication['tweet_limit']; ?> <?php if ($publication['tweet_limit'] != 'None') { ?>(<a href="">Tweet</a>)<?php } ?></td>
                <td><?php if ($publication['shelf_count'] != 0) { ?><a href="<?php echo $publication['readers_url']; ?>"><?php echo $publication['shelf_count']; ?> <?php if ($publication['shelf_count'] == 1) { echo 'User'; } else { echo 'Users'; } ?></a><?php } else { ?>None Yet<?php } ?></td>                
        </tr>
        <tr class="content">
                <td colspan="5">
                        <small>
                                <a href="<?php echo site_url("contribute/update_listing/".$publication["id"].""); ?>">Update Listing</a>
                                <?php /* | <a href="">Edit Excerpt</a> */ ?> |
                                <a href="<?php echo site_url("authorcp/promoimage/".$publication["id"].""); ?>">Upload Promo Image</a> | 
                                <a href="<?php echo site_url("authorcp/thumbnailreset/".$publication["id"].""); ?>">Request Thumbnail Reset</a> | 
                                <a href="<?php echo site_url("authorcp/requestremoval/".$publication["id"].""); ?>">Request Removal</a> | 
                                <a href="<?php echo site_url("authorcp/options/".$publication["id"].""); ?>">Misc. Options</a></small></td>                
        </tr>
        <?php } ?>        
</table>

</div>
