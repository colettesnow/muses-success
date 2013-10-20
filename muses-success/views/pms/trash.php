<h1>Private Messaging</h1>

<ul class="ui_element_tabs">
        <li><a href="<?php echo site_url('pms/compose/'); ?>">Compose</a></li>
        <li><a href="<?php echo site_url('pms/'); ?>">Inbox</a></li>
        <li><a href="<?php echo site_url('pms/outbox/'); ?>">Outbox</a></li>
        <li><a href="<?php echo site_url('pms/sent/'); ?>">Sent</a></li>
        <li class="current"><a href="<?php echo site_url('pms/trash/'); ?>">Trash</a></li>
</ul>

<div id="forums">
        <form method="post" action="<?php echo site_url('pms/permadelete'); ?>">
        <p class="ui_note">Messages more then 10 days old will be deleted automatically.</p>
        <table cellspacing="0">
                <tr class="heading2">
                        <th></th>
                        <th width="50%">Subject</th>
                        <th>From</th>
                        <th>Date</th>
                </tr>
                <?php foreach ($pms as $item) { $user_info = $this->users->get_user_info($item['message_from']); ?>
                <tr class="content">
                        <td><input type="checkbox" name="pm[]" value="<?php echo $item['message_id']; ?>" /></td>
                        <td><a href="<?php echo site_url('pms/view/'.$item['message_id']); ?>"><?php echo $item['message_subject']; ?></a></td>
                        <td><a href="<?php echo site_url('profile/view/'.$item['message_from'].'/'); ?>"><?php echo strlen($user_info['display_name']) > 2 ? $user_info['display_name'] : $user_info['screen_name']; ?></a></td>
                        <td><?php echo date('d/m/Y h:i:sA', $item['message_date']); ?></td>
                </tr>
                <?php } ?>
        </table>

        <p><strong>WARNING:</strong> There will be no confirmation. Once the message has been deleted, it will be gone forever and cannot be recovered.</p>
        
        <p><input type="submit" name="delete" value="Delete Selected" /> <input type="submit" name="delete" value="Empty Trash" /></p>
        </form>
</div>
