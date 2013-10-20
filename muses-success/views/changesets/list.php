<h1>Revision history of <?php echo $title; ?></h1>

<p>For any version listed below, click on its date to view it. To compare an edit with the previous edit, click on it's revision ID.</p>

<table cellspacing="0">

<tr class="heading2">
	<th style="width: 15%;">Revision ID</th>
	<th style="width: 20%;">Date</th>
	<th style="width: 20%;">User</th>
	<th>Comments</th>
</tr>

<?php $count = count($changesets); 
if ($count >= 1) {
foreach ($changesets as $id => $changeset): 

if (!isset($last_id) && $count > 2) { $last_id = isset($changesets[2]['id']) ? $changesets[2]['id'] : $id; }?>

<tr class="content">
	<td><?php if ($id != $count) { ?><a href="<?php echo site_url('changeset/diff/'.$book_id.'/'.$changeset['id'].'/'.(isset($last_id) ? $last_id : $changeset['id']).''); ?>">#<?php echo $changeset['id']; ?></a> <?php if ($id == 1) {  echo ' (current)';  } } else { echo "#".$changeset["id"].' (initial)'; } ?></td>
	<td><a href="<?php echo site_url('changeset/revision/'.$changeset['id']); ?>"><?php echo $changeset['date']; ?></a></td>
	<td><?php echo $changeset['user']; ?></td>
	<td><?php echo $changeset['comments']; ?></td>
</tr>

<?php
if ($count != $id)
{ 
	$c = $id+2;
	if (isset($changesets[$c]['id']))
	{ 
	$last_id = $changesets[$c]['id']; 
	}
}
endforeach; 
}
?>

</table>

<form method="post" action="<?php echo site_url("changeset/compare"); ?>">
    <input type="hidden" name="listing_id" value="<?php echo $book_id; ?>" />
	<p>Compare revision <select name="revision"><?php foreach ($changesets as $changeset) { echo '<option value="'.$changeset['id'].'">'.$changeset['id'].'</option>'; } ?></select> with revision <select name="revision2"><?php foreach ($changesets as $changeset) { echo '<option value="'.$changeset['id'].'">'.$changeset['id'].'</option>'; } ?></select> <input type="submit" name="compare" value="Compare Revisions" /></p>
</form>
