<div id="forums">

<h1><?php echo $page_title; ?></h1>

<p>You can track updates to <?php echo ($user['display_name'] != '') ? $user['display_name'] : $user['screen_name']; ?>'s bookshelf using <a href="<?php echo site_url('rss/bookshelf/'.$shelf_id.'/'); ?>">RSS</a>.</p>

<p><ul class="ui_element_tabs">
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/all'); ?>">All</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/current'); ?>">Currently Reading</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/complete'); ?>">Completed</a></li>
        <li class="current"><a>Plan to Read</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/onhold'); ?>">On-Hold</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$shelf_id.'/dropped'); ?>">Dropped</a></li>
</ul></p>
<?php if (count($plantoread) >= 1) { ?>
<table cellspacing="0">
        <tr class="heading1">
                <th>Book Title</th>
                <th style="width: 15%;">Progress</th>
                <th style="width: 15%;">Rating</th>
        </tr>
        <tr class="heading2">
                <th colspan="3">Plan to Read</th>
        </tr>
        <?php foreach ($plantoread as $book) {
                echo '<tr class="content"><td><a href="'.site_url($book['story_slug']).'">'.$book['title'].'</a> by '.$book['author'].'</td><td>'.$book['read_chapter_count'].'/'.$book['total_chapter_count'].'</td><td>'.(($book['rating'] != 0) ? $book['rating'].'/10' : 'Not Rated').'</td></tr>';
        } ?>
</table>
<?php } else { echo '<p>This user has not placed any books that they plan to read on their bookshelf.</p>'; }  ?>
</div>
