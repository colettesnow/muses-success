<h1>Top Contributors</h1>

<table>
    <thead>
        <tr>
            <th width="3%">#</th>
            <th>Name</th>
            <th width="30%">Contribution Summary</th>
            <th width="20%">Contribution Points</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0; foreach ($contributors as $user): ++$i; ?>
        <tr>
            <td><?php echo $i; ?>.</td>
            <td><a href="<?php echo site_url("profile/view/".$user["user_id"]); ?>"><?php echo ($user["display_name"] != "") ? $user["display_name"] : $user["screen_name"]; ?></a></td>
            <td><?php echo $user["contrib_change_count"]; ?> <a title="This number includes both new listings and updates to existing listings.">updates</a><?php if ($user["review_count"] >= 1) { ?>, <a href="<?php echo site_url("profile/reviews/".$user["user_id"]); ?>"><?php echo $user["review_count"]; ?></a> reviews <?php } ?></td>
            <td><?php echo $user["contrib_points"]; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
