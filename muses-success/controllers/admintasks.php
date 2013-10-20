<?php

class Admintasks extends Controller {

    function cvs()
    {
        $query = $this->db->query('SELECT * FROM `stories` WHERE `not_in_index` = 0');
        echo 'Web Fiction Title | Author | Genre | URL | 120x90px IMG | 360x270px IMG | 540x405 IMG | 640x480 IMG'."\n";
        foreach ($query->result() as $story)
        {
            echo $story->story_title.'|'.$story->story_author.'|'.$story->story_primary_genre.'|'.$story->story_url.'|'.$story->story_slug.'-120x90.png|'.$story->story_slug.'-360x270.png|'.$story->story_slug.'-540x405.png|'.$story->story_slug.'-640x480.png'."\n";
        }
        die();

    }

    function listingstochangeset()
    {
        $field_white_list = array(
            'story_id', 'story_title', 'story_index_title', 'story_subtitle',
            'story_author', 'story_author_url', 'story_url', 'story_rss',
            'story_primary_genre', 'story_secondary_genre',
            'story_mature_coarse', 'story_mature_sex', 'story_mature_violence',
            'story_audience', 'story_slug', 'chapter_total', 'story_submit_by', 'story_credit'
        );

        $query = $this->db->query("SELECT ".implode(', ', $field_white_list)." FROM stories WHERE story_approved = '1'");
        foreach ($query->result_array() as $story)
        {

            $creator = $this->users->get_user_info($story['story_submit_by']);
            $creator_name = $creator['screen_name'];

            $users = explode(',', $story['story_credit']);
            $names = array();
            foreach ($users as $user_id)
            {
                if ($user_id != 0 && $user_id != '' && $user_id != $story['story_submit_by'])
                {
                        $temp_user = $this->users->get_user_info($user_id);
                        $names[] = $temp_user['screen_name'];
                }
            }

            $updated_since = '';

            if (count($names) != 0)
            {
                $updated_since = ' Updated since by '.implode(', ', $names).'.';
            }

            $data = array();
            $data['changeset_story_id'] = $story['story_id'];
            $data['changeset_user_id'] = 2;
            $data['changeset_date'] = time();
            $data['changeset_comment'] = 'Initial data import. Listing originally created by '.$creator_name.'.'.$updated_since;
            $data['changeset_type'] = 'added';
            $data['changeset_points'] = 0;

            $this->db->insert('changesets', $data);
            $changeset_id = $this->db->insert_id();
            foreach ($story as $field => $value)
            {
                if ($field != 'story_id' && $field != 'story_submit_by' && $field != 'story_credit')
                {
                    $data = array();
                    $data['change_set_id'] = $changeset_id;
                    $data['change_table'] = 'stories';
                    $data['change_listing_id'] = $story['story_id'];
                    $data['change_field'] = $field;
                    $data['change_value_before'] = gzdeflate('');
                    $data['change_value_after'] = gzdeflate($value);
                    $this->db->insert('changes', $data);
                }
            }
        }
        die('done');
    }

    function notinindex()
    {

        $query = $this->db->query('SELECT * FROM `tagged` WHERE `tag_id` = 27 OR `tag_id` = 28');
        foreach ($query->result() as $item)
        {
                if ($item->tag_id == 27)
                {
                        $this->db->query('UPDATE `stories` SET `not_in_index` = 2 WHERE `story_id` = '.$item->story_id.' LIMIT 1');
                }
                if ($item->tag_id == 28)
                {
                        $this->db->query('UPDATE `stories` SET `not_in_index` = 1 WHERE `story_id` = '.$item->story_id.' LIMIT 1');
                }
        }

        echo 'Not in index complete.';


    }
    
    function fixchangecount()
    {
    	$users = $this->db->query('SELECT * FROM `users`');
    	foreach ($users->result() as $user)
    	{
            $count_updated = $this->db->query('SELECT COUNT(`story_id`) AS `updated` FROM `stories` WHERE `story_credit` LIKE  \'%,'.$user->user_id.'%\' OR `story_credit` =  \''.$user->user_id.'\' LIMIT 1');
    		$count = $this->db->query('SELECT COUNT(`changeset_id`) AS `chc` FROM `changesets` WHERE `changeset_user_id` = '.$user->user_id.' LIMIT 1');
            $count_submitted = $this->db->query('SELECT COUNT(`story_id`) AS `submitted` FROM `stories` WHERE `story_submit_by` = \''.$user->user_id.'\' LIMIT 1');
    		
    		
    		$update_count = $count->row()->chc+$count_updated->row()->updated+$count_submitted->row()->submitted;
    		$this->db->where('user_id', $user->user_id);    		
    		$this->db->update('users', array('contrib_change_count' => $update_count));		
    	}  	
    }

}

?>
