<div id="forums">

        <table cellspacing="0">
                <tr class="heading1">
                        <th colspan="4"><?php echo $forum['title']; ?></th>
                </tr>
                <tr class="heading2">
                        <th style="width:45%">Topic</th>
                        <th>Created By</th>
                        <th style="width:10%" class="col-centre">Messages</th>
                        <th style="width:20%">Last Post</th>
                </tr>
                <?php foreach ($topics as $topic) { ?>
                <tr class="content">
                        <td><a href="<?php echo $topic['url']; ?>"><?php echo $topic['subject']; ?></a></td>
                        <td><?php echo $topic['author']; ?></td>
                        <td class="col-centre"><?php echo $topic['post_count']; ?></td>
                        <td><?php echo $topic['last_post']; ?></td>
                </tr>
                <?php } ?>
        </table>

</div>


