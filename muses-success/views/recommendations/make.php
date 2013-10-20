<h1>Make a Recommendation</h1>

<p>You can only make similar recommendations for listings you have on your <a href="<?php echo site_url('accounts/bookshelf'); ?>">bookshelf</a>.</p>

<p>Choose a title to make a recommendation for:</p>

<ul>
	<?php foreach ($books as $book): ?>
	<li><a href="<?php echo site_url('recommendations/make/'.$book['story_id']); ?>"><?php echo $book['title']; ?></a> by <?php echo $book['author']; ?></li>
	<?php endforeach; ?>
</ul>
