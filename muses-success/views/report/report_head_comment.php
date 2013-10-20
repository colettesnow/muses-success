<p>You have chosen to report this:</p>

		<div class="comment">
			<div class="avatar"><a href="<?php echo $data['profile']; ?>"><img src="<?php echo $data['avatar']; ?>" height="60" width="60" alt="Avatar" /></a></div>
			<div class="comment_text">			
			<p class="commentor"><?php echo $data['user']; ?> says 
			<span class="comment_date"><?php echo $data['date']; ?> | <a href="<?php echo site_url('report/comment/'.$data['id'].''); ?>">Report</a></span></p>
					
		   <?php echo $data['comment']; ?>						
		</div>
		<div class="anchor"></div>
	</div>