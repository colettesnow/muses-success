<?php 
if ($comments_closed == true) { 
?>
<p>Comments are closed.</p>
<?php 
} else { 
?>
<?php if ($this->config->item("use_disqus_sso") == true): ?>
<script type="text/javascript">
var disqus_config = function() {
    this.page.remote_auth_s3 = "<?php echo $this->disqus_sso->get_remote_auth(); ?>";
    this.page.api_key = "<?php echo $this->disqus_sso->get_public_key(); ?>";
}
</script>
<?php endif; ?>

 <div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = "<?php echo $this->config->item("disqus_short_name"); ?>"; // required: replace example with your forum shortname
        var disqus_identifier = "<?php echo $comment_thread_id; ?>";
        <?php if (isset($comment_thread_title)): ?>var disqus_title = "<?php echo $comment_thread_title; ?>";<?php endif; ?>
        <?php if (isset($canonical)): ?>var disqus_url = "<?php echo $canonical; ?>";<?php endif; ?>
        <?php if (isset($comment_thread_category)): ?>var disqus_category_id = "<?php echo $comment_thread_category; ?>";<?php endif; ?>

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
<?php } ?>