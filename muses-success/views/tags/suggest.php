<h1>Request New Tag</h1>

<ul>
	<li>Your tag must be approved by a moderator before it will be visible around the site.</li>
	<li>Please read the <a href="<?php echo site_url('p/tag-guidelines'); ?>">tag guidelines</a> before requesting a tag.</li>
</ul>

<?php echo $this->validation->error_string; ?>
<form method="post" action="<?php echo site_url("contribute/add_new_tag"); ?>">
	<p><label for="tag_title">Primary Name:</label> <input type="text" name="tag_name" id="tag_title" value="<?php echo $this->input->post('tag_name', true); ?>" /></p>
	<p><label for="alt_tag_names">Aliases:</label> <input type="text" name="tag_alias" id="alt_tag_names" value="<?php echo $this->input->post('tag_alias', true); ?>" /> <span class="help">Comma-separated list of alternative names for this tag.</span></p>
	<p><label for="description">Description:</label> <textarea name="tag_description"><?php echo $this->input->post('tag_description', true); ?></textarea></p>
	<p><label for="parent">Parent Tags:</label> <input type="text" name="tag_parent" value="<?php echo $this->input->post('tag_parent', true); ?>" id="parent" /> <span class="help">Comma-separated list of parent tags. Tag names must exist and exactly match the tags name. If unsure, browse the <a href="<?php echo site_url('tags'); ?>">tag tree</a> and then click the 'Add New Tag' link at the top of the tag's specific page. This tag will become the parent.</span></p>
	<p><input type="submit" name="request" value="Request Tag" /></p>
</form>