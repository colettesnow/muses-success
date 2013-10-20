<div id="forums">

        <h1>Muse's Success Community Forums</h1>

        <table cellspacing="0">
                <tr class="heading1">
                        <th style="width: 60%;">Forum Title</th>
                        <th style="width: 10%;" class="col-centre">Topics</th>
                        <th style="width: 10%;" class="col-centre">Messages</th>
                        <th style="width: 20%;">Last Post</th>
                </tr>
        <?php foreach ($categories as $category) { ?>
                <tr class="heading2"><th colspan="4"><?php echo $category['title']; ?></th></tr>
                <?php foreach($category['forums'] as $forum) { ?>
                <tr class="content">
                        <td class="col">
                                <a href="<?php echo $forum['url']; ?>"><strong><?php echo $forum['title']; ?></strong></a>
                                <span class="caption"><?php echo $forum['description']; ?></span>
                        </td>
                        <td class="col-centre"><?php echo $forum['topic_count']; ?></td>
                        <td class="col-centre"><?php echo $forum['post_count']; ?></td>
                        <td class="col"><?php echo $forum['last_post']; ?></td>
                </tr>
                <?php } ?>
        <?php } ?>
        </table>

</div>
