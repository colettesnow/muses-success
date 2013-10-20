<h1>Private Messaging</h1>

<p>
<ul class="ui_element_tabs">
        <li class="current"><a href="<?php echo site_url('pms/compose/'); ?>">Compose</a></li>
        <li><a href="<?php echo site_url('pms/'); ?>">Inbox</a></li>
        <li><a href="<?php echo site_url('pms/outbox/'); ?>">Outbox</a></li>
        <li><a href="<?php echo site_url('pms/sent/'); ?>">Sent</a></li>
        <li><a href="<?php echo site_url('pms/trash/'); ?>">Trash</a></li>
</ul>
</p>
<form method="post">
        <?php echo $this->validation->error_string; ?>
        <p><label for="to">Send To:</label> <input type="text" name="to" id="to" value="<?php echo $to; ?>" /> (Username, Display Name, or User ID)</p>
        <p><label for="subject">Subject:</label> <input type="text" name="subject" id="subject" value="<?php echo $subject; ?>" /></p>
        <p>Enter your message text below.</p>
        <p><textarea name="message" id="message_box"><?php echo $message; ?></textarea></p>
        <p><input type="submit" name="send" value="Send Message" /></p>
</form>
