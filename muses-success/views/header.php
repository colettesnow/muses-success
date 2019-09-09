<!DOCTYPE html>

<html itemscope itemtype="http://schema.org/Book" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
    <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title><?php echo isset($page_title) ? $page_title.' - ' : ''; ?>Muse's Success</title>
                <link rel="stylesheet" media="screen" type="text/css" href="<?php echo site_url('static/css/new-layout/style.min.css?v=2'); ?>" />		
		<!--[if lte IE 7]>
		<link rel="stylesheet" type="text/css" href="<?php echo site_url('static/css/new-layout/ie7.css'); ?>" media="screen" />
		<![endif]-->
                <?php if (isset($canonical)) { echo '<link rel="canonical" href="'.$canonical.'"/>'; } ?>
                <?php if (isset($use_javascript) && $use_javascript == true) { ?>
                <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
                <?php foreach ($javascript as $javascript_file) { ?>
                <script src="<?php echo site_url('static/js/'.$javascript_file.''); ?>" type="text/javascript"></script>
<?php } }
                if (isset($link_nav_next)) { echo "\t\t<link rel=\"next\" href=\"",$link_nav_next,"\" />\n"; }
                if (isset($link_nav_previous)) { echo "\t\t<link rel=\"prev\" href=\"",$link_nav_previous,"\" />\n"; }                
                if (isset($meta_description)) { echo "\t\t<meta name=\"description\" content=\"",$meta_description,"\" />\n"; }
                if (isset($meta_author)) { echo "\t\t<meta name=\"author\" content=\"",implode(", ", $meta_author),"\" />\n"; } 
				if (isset($facebook)) { ?>
                    <meta name="twitter:card" content="summary" />
                    <meta name="twitter:site" content="@muses_success" /><?php
					foreach ($facebook as $key => $value) {  echo "\t\t<meta property=\"og:".$key."\" content=\"".$value."\"/>\n"; }
					echo "\t\t<meta property=\"og:site_name\" content=\"Muse's Success\"/>\n";
				} 				
				?>
                <link rel="alternate" type="application/rss+xml" title="Muse's Success - Latest Web Fiction" href="http://feeds.feedburner.com/MusesSuccess-LatestAdditions" />
                <link rel="alternate" type="application/rss+xml" title="Muse's Success - Latest Reviews" href="http://feeds.feedburner.com/MusesSuccess-LatestReviews" />
                <script type="application/ld+json">
		{
		  "@context" : "http://schema.org",
		  "@type" : "Organization",
		  "name" : "Muse's Success",
		  "url" : "https://muses-success.info/",
		  "sameAs" : [
		    "https://www.facebook.com/muses.success",
		    "https://twitter.com/muses_success",
		    "https://plus.google.com/+Muses-SuccessInfo"
		  ]
		}
		</script>
		<link rel="publisher" href="https://plus.google.com/108660956896900398737" />
		<meta name="msvalidate.01" content="AE73D056C5A1C87485BED0E32B616D56" />		
		<meta name="referrer" content="unsafe-url" />
                <style type="text/css">
                    .muse_content { float: left; width: 760px; }
                    .ad {  float: right; width: 180px; }
                </style>
        <script type="text/javascript" src="//cdn.jsdelivr.net/cookie-bar/1/cookiebar-latest.js?tracking=1&thirdparty=1&privacyPage=https%3A%2F%2Fmuses-success.info%2Fp%2Fprivacy"></script>
        <script src="https://www.google.com/recaptcha/api.js?render=6Lc2T7cUAAAAADHta_Pz6Bixv16GxBM6AbDpM0tO"></script>
        <?php if (isset($recaptcha_action)) { ?>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute('6Lc2T7cUAAAAADHta_Pz6Bixv16GxBM6AbDpM0tO', {action: '<?php echo $recaptcha_action; ?>'}).then(function(token) {
                    recaptchaElement = document.getElementById("recaptcha_response_field");
                    recaptchaElement.value = token;
                });
            });
        </script>
        <?php } ?>
</head>

    <body itemscope itemtype="http://schema.org/WebPage">

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
												<li><hr /></li>
                                                <li><a href="<?php echo site_url('contribute/submit_review'); ?>">Write Review</a></li>
												<li><a href="<?php echo site_url('recommendations/make'); ?>">Make a Recommendation</a></li>
												<li><hr /></li>												
												<li><a href="<?php echo site_url('contribute/add_new_tag'); ?>">Add New Tag</a></li>
												<li><hr /></li>
												<li><a href="<?php echo site_url('recent-changes'); ?>">Recent Changes</a></li>												
                                                <li><a href="<?php echo site_url('contribute/top-contributors'); ?>">Top Contributors</a></li>
                    </ul>
                            </li>
                            <li>Info <span>&#x25BC;</span>
                                    <ul>
                                            <li><a href="<?php echo site_url('p/about'); ?>">About Muse's Success</a></li>
                                            <li><a href="<?php echo site_url('faqs'); ?>">FAQs</a></li>
											<li><a href="<?php echo site_url('p/guidelines'); ?>">Submission Guidelines</a></li>
											<li><a href="<?php echo site_url('p/tag-guidelines'); ?>">Tag Guidelines</a></li>											 
                                            <li><a href="<?php echo site_url('p/syndication'); ?>">RSS/Atom Feeds</a></li>
                                    </ul>
                            </li>
                        </ul>

                        <div id="user_search_head_group">

                            <div id="user_nav">
                                <?php if ($this->users->logged_in == true) { ?>
                                <ul>
                                        <li>Hello, <a href="<?php echo site_url('whois/'.$this->users->cur_user['user_id']); ?>"><?php echo $this->users->cur_user['display_name']; ?></a></li>
                                        <li><a href="<?php echo site_url('accounts'); ?>">My Account</a>
                                        <ul>
                                                <li><a href="<?php echo site_url('accounts/editprofile'); ?>">Edit Profile</a></li>
                                                <li><a href="http://en.gravatar.com/emails/">Change Avatar (Gravatar)</a></li>
                                                <li><a href="<?php echo site_url('accounts/bookshelf'); ?>">My Bookshelf</a></li>
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
            
            <div align="center" style="margin-bottom: 10px;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Muse Top -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-4250402539296088"
     data-ad-slot="8556337903"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script></div>

                <?php if (isset($breadcrumbs) && count($breadcrumbs) >= 1) { ?><p class="breadcrumbs" itemprop="breadcrumb"><a href="<?php echo site_url(); ?>">Muse's Success</a> » <?php echo implode(' » ', $breadcrumbs); ?></p><?php } ?>

            <?php if (isset($alert) && $alert != "") { ?><p class="alert"><?php echo $alert; ?></p><?php } ?>