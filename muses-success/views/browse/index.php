<div class="muse_content">
<div class="listings">

<h1>Browse Web Fiction Listings</h1>

<div class="browse">
<form method="post" action="<?php echo site_url('browse/createlist'); ?>">
<p>
        <span class="label">Listing Options</span>
        <select name="sort">
                <option>Sort: Highest Rated</option>
                <option value="5"<?php if ($view_info['sort'] == 5) { echo " selected=\"selected\""; } ?>>First-Letter</option>
                <option value="1"<?php if ($view_info['sort'] == 1) { echo " selected=\"selected\""; } ?>>Highest Rated</option>
                <option value="2"<?php if ($view_info['sort'] == 2) { echo " selected=\"selected\""; } ?>>Lowest Rated</option>
                <option value="3"<?php if ($view_info['sort'] == 3) { echo " selected=\"selected\""; } ?>>Most Reviewed</option>
                <option value="4"<?php if ($view_info['sort'] == 4) { echo " selected=\"selected\""; } ?>>Least Reviewed</option>
        </select>
        <select name="genre">
                <option>Genre: All</option>
        <?php foreach ($genres as $genre) { ?>
                <option value="<?php echo $genre; ?>"<?php if ($view_info['genre'] == $genre) { echo " selected=\"selected\""; } ?>><?php echo $genre; ?></option>
        <?php } ?>
        </select>
        <select name="letter">
                <option>Letter: All</option>
        <?php foreach ($letters as $letter) { ?>
                <option value="<?php echo $letter; ?>"<?php if ($view_info['letter'] == $letter) { echo " selected=\"selected\""; } ?>><?php echo $letter; ?></option>
        <?php } ?>
        </select>
        <input type="submit" name="go" value="Go" />
</p>
</form>
</div>
