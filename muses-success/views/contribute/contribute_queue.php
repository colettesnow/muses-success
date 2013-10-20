<div id="forums">

<h1>Contribution Queue</h1>

<p>The queues for updates and new additions are combined. Those are that are not duplicates and rather updates to existing items will be marked with "<small>updated</small>", without the quotes, of course.</p>

<form method="post">
<table cellspacing="0">
        <tr class="heading1">
                <th colspan="7">Listings</th>
        </tr>
        <tr class="heading2">
                <th></th>
                <th>ID</th>
                <th width="30%">Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Updated</th>
                <th>Submitting User</th>
        </tr>
        <?php $i = 0; foreach ($new_novel as $novel) { $i = 1; ?>
        <tr class="content">
                <td><input type="checkbox" name="newlistings[]" value="<?php echo $novel['id']; ?>|<?php echo $novel['author']['user_id']; ?>|<?php if (strlen($novel['summary']) > 119) { echo '2'; } else { echo '1'; } ?>" /></td>
                <td><a href="<?php echo site_url('contribute/listing_detail/'.$novel['id']); ?>"><?php echo $novel['id']; ?></a></td>
                <td><a href="<?php echo site_url('contribute/listing_detail/'.$novel['id']); ?>"><?php echo $novel['title']; ?></a> <?php if ($novel['is_update'] == 1) { echo '<small>updated</small>'; } ?></td>
                <td><a href="<?php echo $novel['author_url']; ?>"><?php echo $novel['author_pen']; ?></a></td>
                <td><?php echo $novel['primary_genre']; ?> / <?php echo $novel['secondary_genre']; ?></td>
                <td><?php echo $novel['update_schedule']; ?></td>
                <td><a href="<?php echo site_url('profile/view/'.$novel['author']['user_id'].'/'); ?>"><?php echo (strlen($novel['author']['display_name']) > 1) ? $novel['author']['display_name'] : $novel['author']['screen_name']; ?></a></td>
        </tr>
        <?php } if ($i == 0) { echo "<tr class=\"content\"><td colspan=\"7\">The listing queue is empty. Good work!</td></tr>"; } ?>
</table>

<table cellspacing="0">
        <tr class="heading1">
                <th colspan="7">Reviews</th>
        </tr>
        <tr class="heading2">
                <th></th>
                <th>ID</th>
                <th width="30%">Review</th>
                <th width="30%">For</th>
                <th>Rating</th>
                <th>Word Count</th>
                <th>Submitting User</th>
        </tr>
        <?php $x = 0; foreach ($new_reviews as $review) { $x = 1; ?>
        <tr class="content">
                <td><input type="checkbox" name="newreviews[]" value="<?php echo $review['id']; ?>|<?php echo $review['author']['user_id']; ?>" /></td>
                <td><a href="<?php echo site_url('contribute/review_detail/'.$review['id']); ?>"><?php echo $review['id']; ?></a></td>
                <td><a href="<?php echo site_url('contribute/review_detail/'.$review['id']); ?>"><?php echo $review['tagline']; ?></a></td>
                <td><?php echo $review['story']; ?></td>
                <td><?php echo $review['rating']; ?> / 10</td>
                <td><?php echo $review['word_count']; ?></td>
                <td><a href="<?php echo site_url('profile/view/'.$review['author']['user_id'].'/'); ?>"><?php echo (strlen($review['author']['display_name']) > 1) ? $review['author']['display_name'] : $review['author']['screen_name']; ?></a></td>
        </tr>
        <?php } if ($x == 0) { echo "<tr class=\"content\"><td colspan=\"7\">The review queue appears to be empty. You can relax today!</td></tr>"; } ?>
</table>

</form>
</div>

