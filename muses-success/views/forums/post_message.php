<div id="forums">

        <table>
                <tr class="heading1">
                        <th>Posting Message in Reply to <a href="<?php echo $topic['url']; ?>"><?php echo $topic['subject']; ?></a></th>
                </tr>
                <tr>
                        <td>
                                <form method="post">
                                        <?php echo $this->validation->error_string; ?>
                                        <p>Enter your message text below.</p>
                                        <?php if ($quote == true) { ?><p>The quoted message will be automatically included at the start of your message.</p><?php } ?>
                                        <p><textarea name="message" id="message_box"></textarea></p>
                                        <hr />
                                        <p><input type="submit" name="submit" value="Post Message" /> <input type="reset" /></p>
                                </form>
                        </td>
                </tr>
        </table>
        
</div>
