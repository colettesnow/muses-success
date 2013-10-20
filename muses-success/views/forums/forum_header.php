<h1><?php echo isset($page_name) ? $page_name : 'Muse\'s Success Community Forums'; ?></h1>

<div id="forum_breadcrumbs">
        <a href="<?php echo site_url('forums'); ?>">Forum Index</a> » <?php echo implode(' » ', $breadcrumbs); ?>
</div>

<?php if (isset($page_options)) { ?>
<div id="forum_navigation">
        <?php echo implode(' | ', $page_options); ?>
</div>
<?php } ?>
<div class="clear"></div>
