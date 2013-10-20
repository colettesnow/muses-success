<h1>My Bookshelf</h1>
<div id="forums">
<p class="ui_note">You can add a title to your bookshelf from its respective listing page. To find a title to add, please either <a href="<?php echo site_url('browse'); ?>">Browse</a>, or use our <a href="<?php echo site_url('search'); ?>">search engine</a>.</p>
<p>You can track updates to your bookshelf using <a href="<?php echo site_url('rss/bookshelf/'.$this->users->cur_user['user_id'].'/'); ?>">RSS</a>.</p>
<?php
/* ' */
$count_current = count($reading_current);
$count_planned = count($reading_planned);
$count_onhold = count($reading_onhold);
$count_complete = count($reading_complete);
$count_dropped = count($reading_dropped);
$count_total = round($count_current+$count_planned+$count_onhold+$count_complete+$count_dropped);

function novel_item($novel_id, $novel, $read_ch, $total_ch, $rating)
{
        echo '<tr class="content"><td><input type="checkbox" name="reading[]" value="'.$novel_id.'" /></td><td>'.$novel.'</td><td><span id="read_num_'.$novel_id.'">'.$read_ch.'</span>/'.$total_ch.' <a href="javascript:addchapter('.$novel_id.');" title="Click to increase your read chapter number by one">+</a></td><td>'.$rating.'/10</td></tr>';
}

if ($count_total != 0)
{
?>
<form method="post" action="<?php echo site_url('accounts/bookshelf/'); ?>">
<table cellspacing="0" width="100%">
        <tr class="heading1">
               <th style="width: 5%;"></th>
               <th style="width: 70%;">Title</th>
               <th style="width: 15%;">Progress</th>
               <th style="width: 15%;">Rating</th>
        </tr>
        <?php if ($count_current != 0) { ?>
        <tr class="heading2">
                <th colspan="4">Currently Reading</th>
        </tr>
        <?php foreach ($reading_current as $novel) { ?>
        <?php novel_item($novel['id'], $novel['novel'], $novel['chapter_count'], $novel['total_chapters'], $novel['rating']); ?>
        <?php } ?>
        <?php } ?>
        <?php if ($count_complete != 0) { ?>
        <tr class="heading2">
                <th colspan="4">Finished Reading</th>
        </tr>
        <?php foreach ($reading_complete as $novel) {?>
        <?php novel_item($novel['id'], $novel['novel'], $novel['chapter_count'], $novel['total_chapters'], $novel['rating']); ?>
        <?php } ?>
        <?php } ?>
        <?php if ($count_planned != 0) { ?>
        <tr class="heading2">
                <th colspan="4">Plan to Read</th>
        </tr>
        <?php foreach ($reading_planned as $novel) { ?>
        <?php novel_item($novel['id'], $novel['novel'], $novel['chapter_count'], $novel['total_chapters'], $novel['rating']); ?>
        <?php } ?>
        <?php } ?>
        <?php if ($count_onhold != 0) { ?>
        <tr class="heading2">
                <th colspan="4">On-Hold</th>
        </tr>
        <?php foreach ($reading_onhold as $novel) {?>
        <?php novel_item($novel['id'], $novel['novel'], $novel['chapter_count'], $novel['total_chapters'], $novel['rating']); ?>
        <?php } ?>
        <?php } ?>
        <?php if ($count_dropped != 0) { ?>
        <tr class="heading2">
                <th colspan="4">Dropped</th>
        </tr>
        <?php foreach ($reading_dropped as $novel) { ?>
        <?php novel_item($novel['id'], $novel['novel'], $novel['chapter_count'], $novel['total_chapters'], $novel['rating']); ?>
        <?php } ?>
        <?php } ?>
</table>
<p>With Selected
<input type="submit" name="delete" value="Delete" / > or change status to <select name="status">
                                        <option value="1">Currently Reading</option>
                                        <option value="2">Plan to Read</option>
                                        <option value="3">On Hold</option>
                                        <option value="4">Completed</option>
                                        <option value="5">Dropped</option>
</select> <input type="submit" name="change" value="Change Status" />
</p></form>
<?php } else { echo '<p>You currently have no titles on your bookshelf.</p>'; } ?>

</div>


