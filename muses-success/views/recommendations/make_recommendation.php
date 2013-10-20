<h1>Make a Similar Recommendation for <?php echo $listing; ?></h1>

<form method="post" action="<?php echo site_url('recommendations/make/'.intval($this->uri->segment(3))); ?>">
<?php echo validation_errors(); ?>
        <p><label for="book">Similar Title:</label> <select name="book" id="book">
                <?php foreach ($books as $book): ?>
                        <option value="<?php echo $book['story_id']; ?>"><?php echo $book['title']; ?> by <?php echo $book['author']; ?></option>
                <?php endforeach; ?>
        </select></p>
        <p>How is this similar to <strong><?php echo $listing; ?></strong>?</p>
        <p><textarea name="comment" rows="10" cols="40"></textarea></p>
        <p><small>Tip: Don't recommend sequels or prequels</small></p>
        <p><input type="submit" name="submit" value="Make Recommendation" /></p>
</form>
