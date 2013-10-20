<div id="forums">
<h1>Comparing Change Set <a href="<?php echo site_url('changeset/revision/'.$revision_base.''); ?>">#<?php echo $revision_base; ?></a> with <a href="<?php echo site_url('changeset/revision/'.$revision_dest.''); ?>">#<?php echo $revision_dest; ?></a> for <a href="<?php echo site_url($book['slug']); ?>"><?php echo $book['title']; ?></a></h1>

<?php function filter_fields($field) {
    
    $fields = array(
    	'story_title' => 'Book Title',
        'story_index_title' => 'Index Sorting Title',
        'story_subtitle' => 'Book Tagline',
        'story_url' => 'Book URL / Read URL',
        'story_rss' => 'Book RSS Feed',
        'story_author' => 'Book Author(s)',
        'story_primary_genre' => 'Primary Genre',
        'story_secondary_genre' => 'Secondary Genre',
        'story_update_schedule' => 'Update Schedule',
        'story_summary' => 'Book Synopsis',
        'story_mature' => 'Contain Mature Content?',
        'story_mature_coarse' => 'Contain Coarse Language?',
        'story_mature_sex' => 'Contain Sexual Language?',
        'story_slug' => 'URL fragment (slug)',
        'chapter_total' => 'Chapter Count',    
        'story_mature_violence' => 'Contain Violent Content?',
        'story_audience' => 'Intended Audience',
        'story_author_url' => 'Author Homepage'
    );

    if (isset($fields[$field]))
    {
    
        return $fields[$field];
    
    } else {
        
        return $field;
     
    }
} ?>

<table cellspacing="0">
	<tr class="heading2">
		<th width="20%">Field</th>
		<th>Diff</th>
	</tr>
	<?php foreach ($changes as $field => $change) { ?>
	<tr class="content">
		<td><?php echo filter_fields($field); ?></td>
		<td class="diff"><?php echo $change; ?></td>
	</tr>
	<?php } ?>
</table>
</div>
