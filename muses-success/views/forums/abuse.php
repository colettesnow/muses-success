<p>Are you sure you want to report the following the message?</p>

<div id="forums">

        <table cellspacing="0">
                <tr class="heading1">
                        <th colspan="3"><?php echo $topic['subject']; ?></th>
                </tr>
                <tr class="content post">
                        <td class="post_author" style="width: 20%;"><?php echo $post['author']; ?></td>
                        <td class="post_date">Posted <?php echo $post['post_date']; ?></td>
                </tr>
                <tr class="content post">
                        <td class="post_author_info">
                                <a href="http://gravatar.com" title="Powered by Gravatar.com"><img src="<?php echo $post['avatar']; ?>" alt="User Avatar" height="100" width="100" /></a>
                                <p><strong>Posts:</strong> <?php echo $post['post_count']; ?><br />
                                <strong>Gender:</strong> <?php echo $post['user_gender']; ?><?php if ($post['user_location']!='') { ?><br />
                                <strong>Location:</strong> <?php echo $post['user_location']; } ?></p>
                        </td>
                        <td class="post_content" valign="top" colspan="2"><?php echo $post['content']; ?><hr /><?php echo $post['signature']; ?></td>
                </tr>
        </table>

</div>

<p><input type="submit" name="report" value="Report Message" /></p>
