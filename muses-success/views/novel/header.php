<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" itemscope itemtype="http://schema.org/Book">
    <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title><?php echo isset($page_title) ? $page_title.' - ' : ''; ?>Muse's Success</title>
                <link rel="stylesheet" media="screen" type="text/css" href="<?php echo site_url('static/css/new-layout/base.css'); ?>" />
                <link rel="stylesheet" media="screen" type="text/css" href="<?php echo site_url('static/css/new-layout/admin.css'); ?>" />                
                <link rel="stylesheet" media="screen" type="text/css" href="<?php echo site_url('static/css/new-layout/structure.css'); ?>" />
            <link rel="stylesheet" media="screen" type="text/css" href="<?php echo site_url('static/css/new-layout/style-blue.css'); ?>" />
        <!--[if lte IE 7]>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('static/css/new-layout/ie7.css'); ?>" media="screen" />
        <![endif]-->
                <?php if (isset($canonical)) { echo '<link rel="canonical" href="'.$canonical.'"/>'; } ?>
                <?php if (isset($use_javascript) && $use_javascript == true) { ?>
                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
                <?php foreach ($javascript as $javascript_file) { ?>
                <script src="<?php echo site_url('static/js/'.$javascript_file.''); ?>" type="text/javascript"></script>
                <?php } }
                if (isset($meta_description)) { echo "<meta name=\"description\" content=\"",$description,"\" />\n"; }
                if (isset($meta_author)) { echo "<meta name=\"author\" content=\"",implode(", ", $meta_author),"\" />\n"; } ?>
                <link rel="alternate" type="application/rss+xml" title="Muse's Success - Latest Web Fiction" href="http://feeds.feedburner.com/MusesSuccess-LatestAdditions" />
                <link rel="alternate" type="application/rss+xml" title="Muse's Success - Latest Reviews" href="http://feeds.feedburner.com/MusesSuccess-LatestReviews" />
                <meta name="microid" content="mailto+http:sha1:3e4166417233e8c94e1e099236cbce428138507d" />
				<script type="text/javascript">
				(function() {
				    if (typeof window.janrain !== 'object') window.janrain = {};
				    window.janrain.settings = {};
				    
				    janrain.settings.tokenUrl = '<?php echo site_url("accounts/process_login"); ?>';
				
				    function isReady() { janrain.ready = true; };
				    if (document.addEventListener) {
				      document.addEventListener("DOMContentLoaded", isReady, false);
				    } else {
				      window.attachEvent('onload', isReady);
				    }
				
				    var e = document.createElement('script');
				    e.type = 'text/javascript';
				    e.id = 'janrainAuthWidget';
				
				    if (document.location.protocol === 'https:') {
				      e.src = 'https://rpxnow.com/js/lib/muses-success/engage.js';
				    } else {
				      e.src = 'http://widget-cdn.rpxnow.com/js/lib/muses-success/engage.js';
				    }
				
				    var s = document.getElementsByTagName('script')[0];
				    s.parentNode.insertBefore(e, s);
				})();
				</script>
</head>

    <body>


                <div id="head_wrap">

                    <div id="header">
                        <div id="site_title"><a href="<?php echo site_url(); ?>">Muse's Success</a></div>

                        <ul id="menu">
                            <li>
                                    <a href="<?php echo site_url('browse'); ?>"><span class="webfiction">Web Fiction</span> Listings <span>&#x25BC;</span></a>
                                                <ul>
                                            <li><a href="<?php echo site_url('accounts/bookshelf'); ?>">My Bookshelf</a></li>
                                            <li><a href="<?php echo site_url('browse'); ?>">Top 20</a></li>
                                            <li><a href="<?php echo site_url('tags/theme'); ?>">Genres</a>
                                                    <ul>
                                                <li><a href="<?php echo site_url('browse/listing/1/Adventure/all'); ?>">Adventure</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Angst/all'); ?>">Angst</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Crime/all'); ?>">Crime</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Drama/all'); ?>">Drama</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Family/all'); ?>">Family</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Fantasy/all'); ?>">Fantasy</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Friendship/all'); ?>">Friendship</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/General/all'); ?>">General</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Horror/all'); ?>">Horror</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Humor/all'); ?>">Humor</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Hurt/Comfort/all'); ?>">Hurt/Comfort</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Mystery/all'); ?>">Mystery</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Parody/all'); ?>">Parody</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Romance/all'); ?>">Romance</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Sci-Fi/all'); ?>">Sci-Fi</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Superhero/all'); ?>">Superhero</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Spiritual/all'); ?>">Spiritual</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Supernatural/all'); ?>">Supernatural</a></li>
                                               <li><a href="<?php echo site_url('browse/listing/1/Suspense/all'); ?>">Suspense</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Tragedy/all'); ?>">Tragedy</a></li>
                                                <li><a href="<?php echo site_url('browse/listing/1/Western/all'); ?>">Western</a></li>
                                                    </ul>
                                            </li>
                                            <li><a href="<?php echo site_url('tags'); ?>">Tags</a></li>
                                                  <li><a href="<?php echo site_url('reviews'); ?>">Reviews</a></li>
                                                  <li><a href="<?php echo site_url('recommendations'); ?>">Recommendations</a></li>
                                                  <li><a href="http://random.muses-success.info/">Random Web Fiction</a></li>
                                    </ul>
                            </li>
                            <li id="contribute_menu"><a href="<?php echo site_url('contribute'); ?>">Contribute <span>&#x25BC;</span></a>
                                    <ul>
                                                <li><a href="<?php echo site_url('contribute/new_listing'); ?>">Add Web Fiction</a></li>
                                                <li><a href="<?php echo site_url('contribute/update_listing'); ?>">Update Web Fiction</a></li>
                                                    <li><a href="<?php echo site_url('contribute/submit_review'); ?>">Write Review</a></li>
                                                <li><a href="<?php echo site_url('recent-changes'); ?>">Recent Changes</a></li>
                                                <li><a href="<?php echo site_url('contribute/top-contributors'); ?>">Top Contributors</a></li>
                    </ul>
                            </li>
                            <li>Info <span>&#x25BC;</span>
                                    <ul>
                                            <li><a href="<?php echo site_url('p/about'); ?>">About Muse's Success</a></li>
                                            <li><a href="<?php echo site_url('faqs'); ?>">FAQs</a></li>
                                            <li><a href="<?php echo site_url('p/syndication'); ?>">RSS/Atom Feeds</a></li>
                                    </ul>
                            </li>
                        </ul>

                        <div id="user_search_head_group">

                            <div id="user_nav">
                                <?php if ($this->users->logged_in == true) { ?>
                                <ul>
                                        <li>Hello, <a href="<?php echo site_url('profile/view/'.$this->users->cur_user['user_id']); ?>"><?php echo $this->users->cur_user['display_name']; ?></a></li>
                                        <li><a href="<?php echo site_url('accounts'); ?>">My Account</a>
                                        <ul>
                                                <li><a href="<?php echo site_url('accounts/editprofile'); ?>">Edit Profile</a></li>
                                                <li><a href="http://en.gravatar.com/emails/">Change Avatar (Gravatar)</a></li>
                                                <li><a href="<?php echo site_url('accounts/bookshelf'); ?>">My Bookshelf</a></li>
                                                <li><a href="<?php echo site_url('accounts/openid'); ?>">Manage OpenIDs</a></li>
                                                <li><a href="<?php echo site_url('accounts/changepassword'); ?>">Change Password</a></li>
                       
                                                <li><a href="<?php echo site_url('accounts/logout'); ?>">Log Out</a></li>
                                        </ul>
                                        </li>
                                        <li><a href="<?php echo site_url('pms'); ?>">Inbox</a>
                                                <ul>
                                                        <li><a href="<?php echo site_url('pms/compose'); ?>">Compose Message</a></li>
                                                        <li><a href="<?php echo site_url('pms/outbox'); ?>">Outbox</a></li>
                                                        <li><a href="<?php echo site_url('pms/sent'); ?>">Sent Messages</a></li>
                                                        <li><a href="<?php echo site_url('pms/trash'); ?>">Trashed Messages</a></li>
                                                </ul>

                                        </li>
                                </ul>
                                <?php } else { ?>
                                <ul>
                                        <li><a href="<?php echo site_url('accounts/login'); ?>">Login</a></li>
                                        <li><a href="<?php echo site_url('accounts/register'); ?>">Register</a></li>
                                    </ul>
                                    <?php } ?>
                            </div>

                            <div id="search">
                                <form action="<?php echo site_url('search/results'); ?>" method="post">
                                    <p><input type="text" name="q" x-webkit-speech="x-webkit-speech" speech="speech" onwebkitspeechchange="this.form.submit();" /><input type="submit" value="Search" /></p>
                                </form>
                            </div>

                        </div>
                    </div>

        </div>

        <div id="main_page_content">

                <?php if (isset($breadcrumbs) && count($breadcrumbs) >= 1) { ?><p class="breadcrumbs"><a href="<?php echo site_url(); ?>">Muse's Success</a> » <?php echo implode(' » ', $breadcrumbs); ?></p><?php } ?>

            <?php if (isset($alert) && $alert != "") { ?><p class="alert"><?php echo $alert; ?></p><?php } ?>