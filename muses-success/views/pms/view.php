<?php $this->load->helper('typography'); $this->load->helper('bbcode'); ?><h1>Private Messaging</h1>

<p>
<ul class="ui_element_tabs">
        <li><a href="<?php echo site_url('pms/compose/'); ?>">Compose</a></li>
        <li><a href="<?php echo site_url('pms/'); ?>">Inbox</a></li>
        <li><a href="<?php echo site_url('pms/outbox/'); ?>">Outbox</a></li>
        <li><a href="<?php echo site_url('pms/sent/'); ?>">Sent</a></li>
        <li><a href="<?php echo site_url('pms/trash/'); ?>">Trash</a></li>
</ul>
</p>
<?php
$user_info = $this->users->get_user_info($item['message_from']);
$avatar =  "http://www.gravatar.com/avatar.php?gravatar_id=".md5($user_info["email_address"])."&size=100&d=identicon";
$display_name = strlen($user_info['display_name']) > 2 ? $user_info['display_name'] : $user_info['screen_name'];

?>

<div id="forums">
        <table cellspacing="0">
                <tr class="heading1">
                        <th colspan="3"><?php echo $item['message_subject']; ?></th>
                </tr>
                <tr class="content post">
                        <td class="post_author" style="width: 20%;"><a href="<?php echo site_url('profile/view/'.$item['message_from'].''); ?>"><?php echo $display_name; ?></a></td>
                        <td class="post_date">Posted <?php echo date('d/m/Y h:i:sA', $item['message_date']); ?></td>
                        <td style="text-align: right;"><a href="<?php echo site_url('pms/compose/'.$item['message_from'].'/'.$item['message_id'].'/'); ?>">Reply</a></td>
                </tr>
                <tr class="content post">
                        <td class="post_author_info" valign="top">
                                <a href="http://gravatar.com" title="Powered by Gravatar.com"><img src="<?php echo $avatar; ?>" alt="User Avatar" height="100" width="100" /></a>
                                <p><strong>Posts:</strong> <?php echo $user_info['post_count']; ?><br />
                                <strong>Gender:</strong> <?php if ($user_info['gender'] == 1) echo 'Male'; elseif ($user_info['gender'] == 2) echo 'Female'; else echo 'Unknown'; ?><br />
                                <?php if ($user_info['location'] != '') { ?><strong>Location:</strong> <?php echo $user_info['location']; } ?></p>
                        </td>
                        <td class="post_content" valign="top" colspan="2"><?php echo parse_bbcode(auto_typography($item['message_body'])); ?></td>
                </tr>
        </table>
</div>
