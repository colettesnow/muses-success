<?php echo "<?xml version='1.0' encoding='UTF-8'?>"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
			    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
	<?php foreach ($urls as $url) { ?>
	<url>
		<loc><?php echo $url[0]; ?></loc>
		<changefreq><?php echo $url[1]; ?></changefreq>
		<priority><?php echo $url[2]; ?></priority>
	</url>
	<?php } ?>
</urlset>
