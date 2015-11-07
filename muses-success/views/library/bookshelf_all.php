<div id="forums">

<h1><?php echo $page_title; ?></h1>

<p>You can track updates to <?php echo ($user['display_name'] != '') ? $user['display_name'] : $user['screen_name']; ?>'s bookshelf using <a href="<?php echo site_url('rss/bookshelf/'.$shelf_id.'/'); ?>">RSS</a>.</p>

<p><ul class="ui_element_tabs">
        <li class="current"><a>All</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/current'); ?>">Currently Reading</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/complete'); ?>">Completed</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/plantoread'); ?>">Plan to Read</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/onhold'); ?>">On-Hold</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/dropped'); ?>">Dropped</a></li>
</ul></p>
<?php if (count($currently_reading) >= 1 || count($completed_reading) >= 1 || count($plantoread) >= 1 || count($onhold) >= 1 || count($dropped) >= 1) { ?>
<table cellspacing="0">
        <tr class="heading1">
                <th>Book Title</th>
                <th style="width: 15%;">Progress</th>
                <th style="width: 15%;">Rating</th>
        </tr>
        <?php if (count($currently_reading) >= 1) { ?>
        <tr class="heading2">
                <th colspan="3">Currently Reading</th>
        </tr>
        <?php foreach ($currently_reading as $book) {
                echo '<tr class="content"><td><a href="'.site_url($book['story_slug']).'">'.$book['title'].'</a> by '.$book['author'].'</td><td>'.$book['read_chapter_count'].'/'.$book['total_chapter_count'].'</td><td>'.(($book['rating'] != 0) ? $book['rating'].'/10' : 'Not Rated').'</td></tr>';
        } } ?>
        <?php if (count($completed_reading) >= 1) { ?>
        <tr class="heading2">
                <th colspan="3">Completed Reading</th>
        </tr>
        <?php foreach ($completed_reading as $book) {
                echo '<tr class="content"><td><a href="'.site_url($book['story_slug']).'">'.$book['title'].'</a> by '.$book['author'].'</td><td>'.$book['read_chapter_count'].'/'.$book['total_chapter_count'].'</td><td>'.(($book['rating'] != 0) ? $book['rating'].'/10' : 'Not Rated').'</td></tr>';
        } } ?>
        <?php if (count($plantoread) >= 1) { ?>
        <tr class="heading2">
                <th colspan="3">Plan to Read</th>
        </tr>
        <?php foreach ($plantoread as $book) {
                echo '<tr class="content"><td><a href="'.site_url($book['story_slug']).'">'.$book['title'].'</a> by '.$book['author'].'</td><td>'.$book['read_chapter_count'].'/'.$book['total_chapter_count'].'</td><td>'.(($book['rating'] != 0) ? $book['rating'].'/10' : 'Not Rated').'</td></tr>';
        } } ?>
        <?php if (count($onhold) >= 1) { ?>
        <tr class="heading2">
                <th colspan="3">On-Hold</th>
        </tr>
        <?php foreach ($onhold as $book) {
                echo '<tr class="content"><td><a href="'.site_url($book['story_slug']).'">'.$book['title'].'</a> by '.$book['author'].'</td><td>'.$book['read_chapter_count'].'/'.$book['total_chapter_count'].'</td><td>'.(($book['rating'] != 0) ? $book['rating'].'/10' : 'Not Rated').'</td></tr>';
        } } ?>
        <?php if (count($dropped) >= 1) { ?>
        <tr class="heading2">
                <th colspan="3">Dropped</th>
        </tr>
        <?php foreach ($dropped as $book) {
                echo '<tr class="content"><td><a href="'.site_url($book['story_slug']).'">'.$book['title'].'</a> by '.$book['author'].'</td><td>'.$book['read_chapter_count'].'/'.$book['total_chapter_count'].'</td><td>'.(($book['rating'] != 0) ? $book['rating'].'/10' : 'Not Rated').'</td></tr>';
        } } ?>
</table>
<?php } else { echo '<p>This user currently has no books on their bookshelf.</p>'; }  ?>
</div>
