<div itemscope itemtype="https://schema.org/FAQPage" class="muse_content">

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
<div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<h3 class="question" id="perma-<?php echo $question['perma-id']; ?>" itemprop="name"><?php echo $category['id']; ?>.<?php echo $question['id']; ?>. <?php echo $question['question']; ?></h3>
<div class="answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
<div itemprop="text">
<?php echo $question['answer']; ?>
</div>
</div>
</div>
</div>
<?php endforeach; ?>
<?php endforeach; ?>
</div>

<div class="ad">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Muse's Success - Sidebar -->
<ins class="adsbygoogle"
     style="display:inline-block;width:160px;height:600px"
     data-ad-client="ca-pub-4250402539296088"
     data-ad-slot="1318512252"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>

<div class="clear"></div>