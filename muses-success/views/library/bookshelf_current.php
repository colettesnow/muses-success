<div id="forums">

<h1><?php echo $page_title; ?></h1>

<p>You can track updates to <?php echo ($user['display_name'] != '') ? $user['display_name'] : $user['screen_name']; ?>'s bookshelf using <a href="<?php echo site_url('rss/bookshelf/'.$shelf_id.'/'); ?>">RSS</a>.</p>

<p><ul class="ui_element_tabs">
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/all'); ?>">All</a></li>
        <li class="current"><a>Currently Reading</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/complete'); ?>">Completed</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/plantoread'); ?>">Plan to Read</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/onhold'); ?>">On-Hold</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/dropped'); ?>">Dropped</a></li>
</ul></p>
<?php if (count($currently_reading) >= 1) { ?>
<table cellspacing="0">
        <tr class="heading1">
                <th>Book Title</th>
                <th style="width: 15%;">Progress</th>
                <th style="width: 15%;">Rating</th>
        </tr>
        <tr class="heading2">
                <th colspan="3">Currently Reading</th>
        </tr>
        <?php foreach ($currently_reading as $book) {
                echo '<tr class="content"><td>'.$book['novel'].'</td><td>'.$book['chapter_count'].'/'.$book['total_chapters'].'</td><td>'.(($book['rating'] != 0) ? $book['rating'].'/10' : 'Not Rated').'</td></tr>';
        } ?>
</table>
<?php } else { echo '<p>This user either is not reading anything or they haven\'t chosen to list what they are reading on their bookshelf.</p>'; }  ?>
</div>
