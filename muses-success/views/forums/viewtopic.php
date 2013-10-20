<div id="forums">

        <table cellspacing="0">
                <tr class="heading1">
                        <th colspan="3"><?php echo $topic['subject']; ?></th>
                </tr>
                <?php foreach ($posts as $post) { ?>
                <tr class="content post">
                        <td class="post_author" style="width: 20%;"><?php echo $post['author']; ?></td>
                        <td class="post_date">Posted <?php echo $post['post_date']; ?></td>
                        <td style="text-align: right;"><a href="<?php echo $topic['post_url'].'/'.$post['id']; ?>">Quote</a> | <a href="<?php echo site_url('report/post/'.$post['id']); ?>">Report Abuse</a></td>
                </tr>
                <tr class="content post">
                        <td class="post_author_info" valign="top">
                                <a href="http://gravatar.com" title="Powered by Gravatar.com"><img src="<?php echo $post['avatar']; ?>" alt="User Avatar" height="100" width="100" /></a>
                                <p><strong>Posts:</strong> <?php echo $post['post_count']; ?><br />
                                <strong>Gender:</strong> <?php echo $post['user_gender']; ?><?php if ($post['user_location']!='') { ?><br />
                                <strong>Location:</strong> <?php echo $post['user_location']; } ?></p>
                        </td>
                        <td class="post_content" valign="top" colspan="2"><?php echo $post['content']; ?><hr /><?php echo $post['signature']; ?></td>
                </tr>
                <?php } ?>
        </table>
        <?php if ($this->users->logged_in == true) { ?>
        <table cellspacing="0">
                <tr class="heading1">
                        <th>Post New Message</th>
                </tr>
                <tr>
                        <td>
                                <form method="post" action="<?php echo $topic['post_url']; ?>">
                                        <textarea name="message" id="message_box"></textarea>
                                        <hr />
                                        <p><input type="submit" name="submit" value="Post Message" /></p>
                                </form>
                        </td>
                </tr>
        </table>
        <?php } ?>
        
</div>
