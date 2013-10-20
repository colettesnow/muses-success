<p><strong>Why are you reporting this?</strong></p>

<?php echo validation_errors(); ?>

<?php echo form_open(current_url()); ?>
<ul class="mod_reasons">
	<?php foreach ($reasons as $id => $reason): ?>
	<li><label><input type="radio" name="reason" value="<?php echo $id; ?>" /> <strong><?php echo $reason[0]; ?>:</strong> <?php echo $reason[1]; ?></label></li>
	<?php endforeach; ?>
</ul>

<p><input type="submit" name="mark" value="Report <?php echo $type; ?>" /></p>
</form>
