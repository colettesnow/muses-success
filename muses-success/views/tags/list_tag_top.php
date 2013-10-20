<div class="muse_content">

<h1>Listings Tagged "<?php echo $tag['term']; ?>" <a href="<?php echo site_url('contribute/add_new_tag/'.$tag['id']); ?>" class="edit-btn"><small>Add New Tag</small></a></h1>

<?php if ($tag['approved'] == 0): ?>
<div class="alert waiting">This tag has not been approved yet. You can still use it to tag web fiction as you would a normal tag.</div>
<?php endif;

if ($tag['approved'] == 2): ?>
<div class="alert denied">This tag has been removed from the database, and cannot be used or re-added.</div>
<?php endif;

if ($this->users->has_permission('g_admin_panel') == 1 || $this->users->has_permission('g_admin_panel') == 2)
{
	if ($tag['approved'] == 0)
	{
		echo '<p><a href="'.site_url('tags/mod-action/approve/'.$tag['id'].'').'">Approve Tag</a> | <a href="'.site_url('tags/mod-action/deny/'.$tag['id'].'').'">Deny Tag</a></p>';
	}
}
	
$the_user = $this->users->get_user_info($tag['user_id']);

if (strlen($tag['description']) > 2)
{
	echo '<p>'.parse_markdown($tag['description']).'</p>';
}
	if (count($aliases) > 0)
	{
		echo '<p><strong>Aliases:</strong> '.implode(', ', $aliases).'</p>';
	}
	echo '<p><small style="font-size:8pt;">Tag added by <a href="'.site_url('profile/view/'.$the_user['user_id']).'">'.$the_user['screen_name'].'</a></small></p>';


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
                if ($tag['usable'] == 1 && $tag['count'] >= 1)
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
<?php foreach ($childtags as $tag) { ?>
        <li class="tag_block"><a href="<?php echo site_url('tags/'.$tag['slug'].''); ?>"><?php echo $tag['name']; ?></a> <?php if ($tag['count'] >= 1) { echo " (".$tag['count'].")"; }  ?> <?php echo child_tags($tag, 1, 0, 6); ?></li>
<?php } ?>
</ul>  
<div>