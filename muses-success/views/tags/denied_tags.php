<h1>Denied Tags</h1>

<div class="alert">These tags have been denied and will not be re-added. If you disagree with this discision, please <a href="mailto:info@muses-success.info">contact us</a>.</div>

<table>
	<tr>
		<th width="50%">Tag</th>
		<th>Date Requested</th>
		<th>Contributor</th>		
	</tr>
	<?php foreach ($denied_tags as $tag):
		$user = $this->users->get_user_info($tag['user_id']); ?>
	<tr>
		<td><a href="<?php echo site_url('tags/'.$tag['slug']); ?>"><?php echo $tag['name']; ?></a></td>
		<td valign="top"><?php echo $tag['date']; ?></td>
		<td valign="top"><a href="<?php echo site_url('profile/view/'.$tag['user_id']); ?>"><?php echo $user['screen_name']; ?></a></td>		
	</tr>	
	<?php endforeach; ?>
</table>