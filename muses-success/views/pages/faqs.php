<h1>Frequently Asked Questions</h1>

<ol class="faqs faq-list">
<?php foreach ($faqs as $category): ?>
<li><a href="#c-<?php echo $category['id']; ?>"><?php echo $category['id']; ?>. <?php echo $category['cat_name']; ?></a>
<ol>
	<?php foreach ($category['questions'] as $question): ?>
	<li><a href="#perma-<?php echo $question['perma-id']; ?>" class="question answer-at"><?php echo $category['id']; ?>.<?php echo $question['id']; ?>. <?php echo $question['question']; ?></a></li>
	<?php endforeach; ?>
</ol>
</li>
<?php endforeach; ?>
</ol>

<?php foreach ($faqs as $category): ?>
<h2 id="c-<?php echo $category['id']; ?>"><?php echo $category['id']; ?>. <?php echo $category['cat_name']; ?></h2>
<?php foreach ($category['questions'] as $question): ?>
<div class="faq">
<h3 class="question" id="perma-<?php echo $question['perma-id']; ?>"><?php echo $category['id']; ?>.<?php echo $question['id']; ?>. <?php echo $question['question']; ?></h3>
<div class="answer">
<?php echo $question['answer']; ?>
</div>
</div>
<?php endforeach; ?>
<?php endforeach; ?>