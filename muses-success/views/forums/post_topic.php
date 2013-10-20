<div id="forums">

        <table>
                <tr class="heading1">
                        <th>Posting Topic in <a href="<?php echo $forum['url']; ?>"><?php echo $forum['title']; ?></a></th>
                </tr>
                <tr>
                        <td>
                                <form method="post">
                                        <?php echo $this->validation->error_string; ?>
                                        <p>To create a new topic, enter a subject for the topic below and create the first message.</p>
                                        <p><label for="subject">Topic Subject</label> <input type="text" name="subject" id="subject" maxlength="80" /></p>
                                        <p>Enter your message text below.</p>
                                        <p><textarea name="message" id="message_box"></textarea></p>
                                        <hr />
                                        <p><input type="submit" name="submit" value="Post Message" /> <input type="reset" /></p>
                                </form>
                        </td>
                </tr>
        </table>
        
</div>
