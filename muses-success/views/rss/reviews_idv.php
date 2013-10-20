<?php $this->load->helper('date'); echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<rss version="2.0">
<channel>
        <title>Muse's Success - Reviews for <?php echo $novel['title']; ?></title>
        <link><?php echo $novel['listing_url']; ?></link>
        <description>This feed contains the latest additions to Muse's Success.</description>
        <language>en-au</language>
        <ttl>180</ttl>
        <?php foreach ($reviews as $item) { ?>
        <item>
                <title>Review "<?php echo $item['tagline']; ?>"</title>
                <link><?php echo $item['url']; ?></link>
                <guid><?php echo $item['url']; ?></guid>
                <description>Reviewed by <?php echo $item['author']; ?></description>
        </item>
        <?php } ?>
</channel>
</rss>
