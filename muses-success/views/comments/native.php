<div class="comments">
<?php if ($comments_closed == true) { ?><p>Comments are closed.</p><?php } ?>

<?php if (count($comments) >= 1) {
    foreach ($comments as $comment) {
        ?>

                <div class="comment">
                        <div class="avatar"><a href="<?php echo $comment['profile']; ?>"><img src="<?php echo $comment['avatar']; ?>" height="60" width="60" alt="Avatar" /></a></div>
                        <div class="comment_text">                        
                                <p class="commentor"><?php echo $comment['user']; ?> says 
                                <span class="comment_date"><?php echo $comment['date']; ?> | <a href="<?php echo site_url('report/comment/'.$comment['id'].''); ?>">Report</a></span></p>
                                        
                        <?php echo $comment['comment']; ?>                                                
                        </div>
                        <div class="anchor"></div>
                </div>

        <?php }
} else {

    if ($comments_closed == false) {
?>

<p>No one has commented on <?php echo $title; ?> yet.</p>

<?php 
    }
} ?>

</div>

<?php if ($this->users->logged_in == true && $comments_closed == false) { ?>

<h3>Write Comment</h3>

<form method="post" action="<?php echo site_url("contribute/add_comment"); ?>">
        <p><textarea name="comment" id="addcommentform"></textarea></p>
        <input type="hidden" name="listing_id" value="<?php echo $id; ?>" />
        <p><input type="submit" name="add" value="Add Comment" /></p>
</form>

<?php } ?>

</div>