<?php $this->load->helper('date'); echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<rss version="2.0">
<channel>
        <title><?php echo $user['screen_name']; ?>'s Web Fiction from Muse's Success</title>
        <link>http://muses-success.info/bookshelf/<?php echo $user['user_id']; ?>/</link>
        <description>Recently Read Web Fiction from <?php echo $user['screen_name']; ?>'s Bookshelf</description>
        <lastBuildDate><?php echo standard_date('DATE_RSS', $updates[0]['update_date']) ?></lastBuildDate>
        <language>en-au</language>
        <ttl>180</ttl>
        <?php foreach ($updates as $item) { ?>
        <item>
                <title><?php echo $item['update_title']; ?></title>
                <link><?php echo $item['update_link']; ?></link>
                <guid><?php echo $item['update_link']; ?></guid>
                <pubDate><?php echo standard_date('DATE_RSS', $item['update_date']) ?></pubDate>
                <description><?php echo $item['update_text']; ?></description>
        </item>
        <?php } ?>
</channel>
</rss>

