<?php $this->load->helper('date'); echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<rss version="2.0">
<channel>
        <title>Muse's Success - Latest Additions</title>
        <link>http://muses-success.info/</link>
        <description>This feed contains the latest additions to Muse's Success.</description>
        <lastBuildDate><?php echo standard_date('DATE_RSS', $newest[1]['added']) ?></lastBuildDate>
        <language>en-au</language>
        <ttl>180</ttl>
        <?php foreach ($newest as $item) {

        	$summary = $item['summary'];
        	$summary = str_replace('& ', '&amp; ', $summary);
        	
        	?>
        <item>
                <title><?php echo $item['title']; ?> by <?php echo $item['author_pen']; ?></title>
                <link><?php echo $item['listing_url']; ?></link>
                <guid><?php echo $item['listing_url']; ?></guid>
                <pubDate><?php echo standard_date('DATE_RSS', $item['added']) ?></pubDate>
                <category>weblit</category>
                <category><?php echo strtolower($item['primary_genre']); ?></category>
                <?php if (strlen($item['secondary_genre']) > 3) { ?><category><?php echo strtolower($item['secondary_genre']); ?></category><?php } ?>
                <description>[CDATA[ <?php echo (strlen($summary) > 1) ? $summary : 'We currently don\'t have a synopsis for this web novel.'; ?> ]]</description>
        </item>
        <?php } ?>
</channel>
</rss>

