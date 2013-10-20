<div id="forums">

<h1>Recent Changes</h1>
<?php if ($this->users->logged_in == true) { ?>
<p>Please review the changes being made, and approve or disapprove of each
 change using the plus and minus signs respectively. This directly affects the 
 amount of points the contributor will be awarded with a maximum of 10 either 
 way, and a default of 5.</p>
<?php } else { ?>
<p></p>
<?php } ?>
<?php echo $this->pagination->create_links(); ?>

<table cellspacing="0" class="changes">

<tr class="heading2">
    <th width="6%"><acronym title="Changeset">CS</acronym></th>
    <th>Change</th>
    <th width="6%">- / +</th>
</tr>

<?php foreach ($changesets as $changeset): ?>

<tr class="content<?php if ($changeset['approved'] == 0) { echo ' notapproved'; } elseif ($changeset['approved'] == -1) { echo ' rejected'; } elseif ($changeset['approved'] == 1 && $changeset['type'] == 'added') { echo ' approved'; } ?>">
    <td valign="top"><a href="<?php echo site_url('changeset/revision/'.$changeset['id']); ?>"><?php echo $changeset['id']; ?></a></td>
    <td>Listing "<?php echo ($changeset['approved'] == -1) ? strip_tags($changeset['book']) : $changeset['book']; ?>" was <strong><?php echo $changeset['type']; ?></strong> by <?php echo $changeset['user']; ?><br /><small><?php echo $changeset['date']; ?></small>
    <?php if (strlen($changeset['comments']) > 2) { ?><br /><br /><?php echo $changeset['user'] ?> summarised their edit:<blockquote><?php echo $changeset['comments']; ?></blockquote><?php } ?>
    <?php if ($changeset['approved'] == 0 && $changeset['type'] == 'added') { echo '<small style="color: red;">This listing is awaiting approval from a moderator. It could take up to 48 hours before it appears in our index.</small>'; } ?>
    <?php if ($changeset['approved'] == -1 && $changeset['type'] == 'added') { echo '<small style="color: red;">This listing was rejected as it did not meet our <a href="'.site_url('pages/guidelines').'">guidelines</a> for acceptable web fiction.</small>'; } ?>
    <?php if ($this->users->cur_user['access_level'] >= 6 && $changeset['approved'] == 0 && $changeset['type'] == 'added') { echo '<p><small><a href="'.site_url('contribute/approve_listing/'.$changeset['book_id']).'">Approve</a> - <a href="'.site_url('contribute/reject_listing/'.$changeset['book_id']).'">Reject</a></small></p>'; } ?>
    <?php if ($this->users->cur_user['access_level'] >= 6 && $changeset['approved'] == -1 && $changeset['type'] == 'added') { echo '<p><small><a href="'.site_url('report/contest-listing-rejection/'.$changeset['id'].'').'">Contest Rejection</a></small></p>'; } ?>
    </td>
    <td valign="top" id="vote-<?php echo $changeset['id']; ?>"><?php if ($this->users->logged_in == true && $changeset['type'] != 'deleted' && $changeset['approved'] == 1 && $changeset['user_id'] != 2 && in_array($this->users->cur_user['user_id'], $changeset['voters']) == false) { ?><a href="javascript:vote(<?php echo $changeset['id']; ?>, 'minus');">-</a> / <a href="javascript:vote(<?php echo $changeset['id']; ?>, 'plus');">+</a><?php } if ($this->users->logged_in == true && in_array($this->users->cur_user['user_id'], $changeset['voters']) == true) { echo 'Voted'; } ?></td>
</tr>

<?php endforeach; ?>

</table>
<?php echo $this->pagination->create_links(); ?>

</div>
