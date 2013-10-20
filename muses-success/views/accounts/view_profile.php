<div class="vcard">
<?php $name = ($user_info['display_name'] != '') ? ucwords($user_info['display_name']) : ucwords($user_info['screen_name']); ?><div class="profile_head">
        <div class="avatar">
                <img class="photo" src="http://www.gravatar.com/avatar.php?gravatar_id=<?php echo md5($user_info['email_address']); ?>&size=100&d=identicon" />
        </div>
        <div class="user_blurb">
                <h1><span class="fn n nickname"><?php echo ($user_info['display_name'] != '') ? ucwords($user_info['display_name']) : ucwords($user_info['screen_name']); ?></span>'s Profile</h1>

                <?php if ($this->users->has_permission('g_send_pm') != 0) { ?><p><a href="<?php echo site_url('pms/compose/'.$user_info['user_id']); ?>">Send Message</a><?php } ?></p>
        </div>
        <div class="clear"></div>
</div>

<ul class="ui_element_tabs">
        <li class="current"><a>Home</a></li>
        <li><a href="<?php echo site_url('bookshelf/'.$user_info['user_id'].'/'); ?>">Bookshelf</a></li>
        <?php if ($user_info['review_count'] >= 1) { ?><li><a href="<?php echo site_url('profile/reviews/'.$user_info['user_id'].'/'); ?>">Reviews</a></li><?php } ?>
</ul>

<div class="profile_body_left">
        <div class="profile_contact_info box">
                <h3><?php echo $name; ?>'s Details</h3>
                
                <table width="100%">
                        <?php if ($user_info['gender_new'] != 'Unspecified') { ?>
                        <tr>
                                <td><strong>Gender:</strong></td>
                                <td class="gender"><?php echo $user_info['gender_new'];  ?></td>
                        </tr>
                        <?php } if (strlen($user_info['location']) != 0) { ?>
                        <tr>
                                <td><strong>Location:</strong></td>
                                <td><?php echo $user_info['location']; ?></td>
                        </tr>
                        <?php } if (strlen($user_info['website_url']) != 0) { ?>
                        <tr>
                                <td><strong>Website:</strong></td>
                                <td><a href="<?php echo $user_info['website_url']; ?>" class="url" rel="nofollow"><?php echo $user_info['website_url']; ?></a></td>
                        </tr>
                        <?php } if (($user_info['show_email'] == 1 && $this->users->has_permission('g_emails_visible') != 0) || $this->users->has_permission('g_emails_visible') == 2) { ?>
                        <tr>
                                <td><strong>E-mail Address:</strong></td>
                                <td><a href="mailto:<?php echo $user_info['email_address']; ?>"><?php echo $user_info['email_address']; ?></a></td>
                        </tr>
                        <?php } if (strlen($user_info['windowslive_im']) != 0) { ?>
                        <tr>
                                <td><strong>MSN:</strong></td>
                                <td><?php echo $user_info['windowslive_im']; ?></td>
                        </tr>
                        <?php } if (strlen($user_info['yahoo_im']) != 0) { ?>
                        <tr>
                                <td><strong>Yahoo:</strong></td>
                                <td><?php echo $user_info['yahoo_im']; ?></td>
                        </tr>
                        <?php } if (strlen($user_info['aol_im']) != 0) { ?>
                        <tr>
                                <td><strong>AIM:</strong></td>
                                <td><?php echo $user_info['aol_im']; ?></td>
                        </tr>
                        <?php } if (strlen($user_info['google_im']) != 0) { ?>
                        <tr>
                                <td><strong>Google Talk:</strong></td>
                                <td><?php echo $user_info['google_im']; ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                                <td width="30%"><strong>User Group:</strong></td>
                                <td class="role"><?php echo $user_group; ?></td>
                        </tr>
                        <tr>
                                <td style="width: 30%;"><strong>Forum Posts:</strong></td>
                                <td><?php echo round($user_info['post_count']); ?></td>
                        </tr>
                        <?php if ($this->users->has_permission('g_ip_address_visible') == 2) { ?>
                        <tr>
                                <td style="width: 30%;"><strong>IP Address:</strong></td>
                                <td><a href="http://who.is/whois-ip/ip-address/<?php echo ($user_info['ip_address']); ?>/"><?php echo ($user_info['ip_address']); ?></a></td>
                        </tr>
                        <?php } ?>
                        <?php if ($this->users->has_permission('g_user_agent_visible') == 2) { ?>
                        <tr>
                                <td style="width: 30%;"><strong>User Agent:</strong></td>
                                <td><?php echo ($user_info['user_agent']); ?></td>
                        </tr>
                        <?php } ?>
                </table>
                <h3><?php echo $name; ?>'s Forum Signature</h3>
                
                <?php
                
                function nofollow($str)
                {
                    $str = str_replace("<a ", "<a rel=\"nofollow\" ", $str);
                    return $str;
                }
                
                ?>
                
                <p><?php echo nofollow($signature); ?></p>
        </div>
</div>

<div class="profile_body_right">
        <div class="box contribution_stats">
        <h3><?php echo $name; ?>'s Contribution Statistics</h3>
            <div style="padding: 4px;">
                <p>Published <a href="<?php echo site_url('profile/reviews/'.$user_info['user_id'].'/'); ?>"><strong><?php echo round($user_info['review_count']); ?></strong> Reviews</a></p>
                <p>Added <strong><?php echo round($listings_submitted); ?></strong> Listings</p>
                <p>Updated <strong><?php $listings_updated = $listings_updated-$listings_submitted; if ($listings_updated < 0) { $listings_updated = '0'; } echo round($listings_updated); ?></strong> Listings<small><sup>1</sup></small></p>
                <p>Gained <strong><?php echo round($user_info['contrib_points']); ?></strong> Contribution Points</p>
                <p><small><sup>1</sup> not including updated listings which were also added by this user.</small></p>
            </div>
        </div>
        <div class="box">
        <h3>Recent <a href="<?php echo site_url('bookshelf/'.$user_info['user_id'].'/'); ?>">Bookshelf</a> Updates</h3>
                
        <?php if (count($recent_readings) >= 1) { foreach ($recent_readings as $book) { echo $book; } } else { echo '<p>This user has not yet added any titles to their bookshelf.</p>'; } ?>
        </div>
</div>

<div class="clear"></div>

<p>This content uses <a rel="profile" href="http://microformats.org/profile/hcard">hCard</a>.</p>

</div>
