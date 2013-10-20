<?php $this->load->helper('date'); echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<rss version="2.0">
<channel>
        <title>Muse's Success - Latest Reviews</title>
        <link>http://muses-success.info/</link>
        <description>This feed contains the latest reviews published on Muse's Success.</description>
        <language>en-au</language>
        <lastBuildDate><?php echo standard_date('DATE_RSS', $reviews[1]['date']) ?></lastBuildDate> 
        <ttl>180</ttl>
        <?php foreach ($reviews as $item) { ?>
        <item>
                <title>"<?php echo $item['tagline']; ?>" <?php echo $item['review_by']; ?> reviewed <?php echo $item['review_story']; ?></title>
                <link><?php echo $item['review_url']; ?>/</link>
                <pubDate><?php echo standard_date('DATE_RSS', $item['date']) ?></pubDate>
                <guid><?php echo $item['review_url']; ?>/</guid>
                <description><![CDATA[ <?php echo $item['review_text']; ?> ]]></description>
                <category>weblit</category>
        </item>
        <?php } ?>
</channel>
</rss>

