<h1>Browse Tags <a href="<?php echo site_url('contribute/add_new_tag'); ?>" class="edit-btn"><small>Add New Tag</small></a></h1>

<?php

function child_tags($tags, $deep = 0, $current = 0, $limit = 0)
{
        ++$current;
        echo '<ul>';
        $i = 0;
        $count = count($tags['children']);
        foreach ($tags['children'] as $tag)
        {
                ++$i;
                if ($limit != 0 && $i >= $limit)
                {
                        echo '<li><a href="'.site_url('tags/'.$tags['slug']).'"><em>'.($count-5).' More Tags</em></a></li>';
                        break;
                }
        
                echo '<li><a href="'.site_url('tags/'.$tag['slug'].'').'">'.$tag['name'].'</a>';
                if ($tag['usable'] == 1 && $tag['count'] > 0)
                {
                        echo ' ('.$tag['count'].')';
                }
                if (count($tag['children']) != 0)
                {
                        if ($deep != 0 && $deep == $current)
                        {
                                continue;
                        } else {
                                echo child_tags($tag, $deep, $current);
                        }
                } else {
                        continue;
                }
                echo '</li>';
                
        }
        echo '</ul>';
}

?>

<ul class="tags_list">
<?php foreach ($tags as $tag) { ?>
        <li class="tag_block"><a href="<?php echo site_url('tags/'.$tag['slug'].''); ?>"><?php echo $tag['name']; ?></a> <?php echo child_tags($tag, 1, 0, 6); ?></li>
<?php } ?>
</ul>

<div class="tag_block_widget">
	<div class="tag_widget_heading">Recently Added</div>
	<div class="tag_widget_content">
		<ul class="tags_list">
			<?php foreach ($recent_tags as $tag): ?>
			<li><?php echo $tag['date']; ?>: <a href="<?php echo site_url('tags/'.$tag['slug']); ?>"><?php echo $tag['name']; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>

<div class="tag_block_widget">
	<div class="tag_widget_heading"><a href="<?php echo site_url('tags/special:pending-tags'); ?>">Awaiting Moderation</a></div>
	<div class="tag_widget_content">
		<ul class="tags_list">
			<?php foreach ($pending_tags as $tag): ?>
			<li><?php echo $tag['date']; ?>: <a href="<?php echo site_url('tags/'.$tag['slug']); ?>"><?php echo $tag['name']; ?></a></li>
			<?php endforeach; ?>
		</ul>
		
		<p><a href="<?php echo site_url('tags/special:pending-tags'); ?>">Pending Tags</a> | <a href="<?php echo site_url('tags/special:denied-tags'); ?>">Denied Tags</a></p>
	</div>
</div>