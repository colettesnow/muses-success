<div id="forums">

	<table cellspacing="0">
		<tr class="heading1">
			<th colspan="2">Post Detail</th>
		</tr>
		<tr class="content">
			<td class="post_author" style="width: 20%;"><?php echo $data['author']; ?></td>
            <td class="post_date">Posted <?php echo $data['post_date']; ?></td>
		</tr>
		<tr class="content post">
			<td class="post_author_info" valign="top">
				<a href="http://gravatar.com" title="Powered by Gravatar.com"><img src="<?php echo $data['avatar']; ?>" alt="User Avatar" height="100" width="100" /></a>
                <p><strong>Posts:</strong> <?php echo $data['post_count']; ?><br />
                <strong>Gender:</strong> <?php echo $data['user_gender']; ?><?php if ($data['user_location']!='') { ?><br />
                <strong>Location:</strong> <?php echo $data['user_location']; } ?></p>
            </td>
			<td valign="top"><?php echo $data['content']; ?></td>
		</tr>
	</table>

</div>


