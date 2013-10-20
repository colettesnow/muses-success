<?php

class Changes extends Model {

    public $changes = array();
    public $table_cache = array();
    public $change_id = 0;
    public $book_id;
    public $changeset_comment = '';
    public $user_id = 0;
    public $type = 'edited';
    public $table_id_field_map = array(
        'stories' => 'story_id',
        'reviews' => 'review_story'
    );
    public $changeset_exists = array();

    function Changes()
    {
        parent::Model();

        if ($this->users->logged_in == true)
        {
            $this->user_id = $this->users->cur_user['user_id'];
        }
    }

    function change($table, $field, $value, $id_field = 'story_id')
    {
        if (!isset($this->table_cache[$table]))
        {
            $result = $this->db->get_where($table, array($id_field => $this->book_id));
            $this->table_cache[$table] = $result->row_array();
            $row = $this->table_cache[$table];
        } else {
            $row = $this->table_cache[$table];
        }

        if ($this->table_cache[$table][$field] != $value)
        {

        ++$this->change_id;
        $change_id = $this->change_id;
        $this->changes[$change_id] = array();
        $this->changes[$change_id]['table'] = $table;
        $this->changes[$change_id]['field'] = $field;
        $this->changes[$change_id]['value_before'] = $this->table_cache[$table][$field];
        $this->changes[$change_id]['value_after'] = $value;
        $this->changes[$change_id]['id_field'] = $id_field;

        }

    }

    function discard()
    {
        $this->changes = array();
        $this->table_cache = array();
        $this->change_id = 0;
    }

    function commit()
    {
        if (count($this->changes) >= 1)
        {

            $temp_tables = array();
            $id_fields = array();

            $changeset_data = array();
            $changeset_data['changeset_story_id'] = $this->book_id;
            $changeset_data['changeset_user_id'] = $this->user_id;
            $changeset_data['changeset_type'] = $this->type;
            $changeset_data['changeset_comment'] = $this->changeset_comment;
            $changeset_data['changeset_date'] = time();

            $this->db->insert('changesets', $changeset_data);

            $changeset_id = $this->db->insert_id();

            foreach ($this->changes as $change)
            {
                if (!isset($temp_tables[$change['table']]))
                {
                    $temp_tables[$change['table']] = array();
                }

                $temp_tables[$change['table']][$change['field']] = $change['value_after'];
                $id_fields[$change['table']] = $change['id_field'];

                $change_data = array();
                $change_data['change_set_id'] = $changeset_id;
                $change_data['change_listing_id'] = $this->book_id;
                $change_data['change_table'] = $change['table'];
                $change_data['change_field'] = $change['field'];
                $change_data['change_value_before'] = gzdeflate($change['value_before']);
                $change_data['change_value_after'] = gzdeflate($change['value_after']);
                $this->db->insert('changes', $change_data);

            }

            foreach ($temp_tables as $table => $data)
            {
                $this->db->where($id_fields[$table], $this->book_id);
                $this->db->update($table, $data);
            }

        }

        $this->discard();
    }

    function rollback($changeset_id, $story_id, $change_id = 0)
    {
        $temp_tables = array();

        $query = $this->db->query('SELECT `change_field`, `change_value_after` FROM `changes` WHERE `change_listing_id` = '.$story_id.' AND `change_set_id` <= '.$changeset_id.' ORDER BY `change_id` ASC'); 
        foreach ($query->result_array() as $changes)
        {
			$temp_tables[$changes['change_field']] = gzinflate($changes['change_value_after']);			
        }
        
        return $temp_tables;
    }

    function list_changesets($book_id, $current_page = 0, $per_page = 20)
    {
        $changesets = array();
        $i = 0;
    
        $this->db->select('changeset_id, story_id, story_slug, story_title, story_approved, changeset_date, changeset_voters, changeset_type, changeset_comment, changeset_user_id');
        $this->db->join('stories', 'changeset_story_id = story_id', 'left');
        $this->db->from('changesets');
        if ($book_id != 'all')
        {
            $this->db->where('changeset_story_id', $book_id);
        }
        $this->db->order_by('changeset_id', 'DESC');
        $this->db->limit($per_page, $current_page);            
        $result = $this->db->get();
        
        foreach ($result->result() as $changeset)
        {
            $user = $this->users->get_user_info($changeset->changeset_user_id);

            ++$i;
            $changesets[$i] = array();
            $changesets[$i]['id'] = $changeset->changeset_id;
            $changesets[$i]['date'] = date_relative($changeset->changeset_date);
            $changesets[$i]['date_raw'] = $changeset->changeset_date;
            $changesets[$i]['book'] = '<a href="'.site_url($changeset->story_slug).'">'.$changeset->story_title.'</a>';
            $changesets[$i]['book_id'] = $changeset->story_id;
            $changesets[$i]['type'] = $changeset->changeset_type;
            $changesets[$i]['approved'] = $changeset->story_approved;
            $changesets[$i]['user_id'] = $user['user_id'];
            $changesets[$i]['voters'] = explode(',', $changeset->changeset_voters);
            $changesets[$i]['user'] = '<a href="'.site_url('profile/view/'.$user['user_id'].'').'">'.$user['screen_name'].'</a>';
            $changesets[$i]['comments'] = strip_tags($changeset->changeset_comment);

        }
        
        $cs = array();
        $cs['changesets'] = $changesets;
        $cs['total_changes'] = $this->db->count_all('changesets');
        
        return $cs;

    }

    function get_changes($changeset_id, $is_base = 0)
    {
        $changes = array();
        $i = 0;
        $this->db->order_by('change_id', 'ASC');
        $result = $this->db->get_where('changes', array('change_set_id' => $changeset_id));
        
        if ($is_base != 0)
        {
            $base = $this->rollback($changeset_id, $is_base); 
        }
        
        foreach ($result->result() as $change)
        {
            ++$i;
            $changes[$i] = array();
            $changes[$i]['id'] = $change->change_id;
            $changes[$i]['field'] = $change->change_field;
            $changes[$i]['old'] = addslashes(gzinflate($change->change_value_before));
            $changes[$i]['new'] = addslashes(gzinflate($change->change_value_after));
            if ($is_base != 0 && isset($base[$change->change_field]))
            {
                if ($changes[$i]['new'] == addslashes($base[$change->change_field]))
                {
                    $changes[$i]['new'] = addslashes($base[$change->change_field]);
                }
            }
        }

        return $changes;
    }
    
    function changeset_span($current_changeset, $story_id)
    {
   
        $result = $this->db->query('SELECT `changeset_id` FROM `changesets` WHERE `changeset_story_id` = '.$story_id.'');
        
        $last_change = 0;
        $next_change = 0;
        $is_next_last = 0;
        $second_last = 0;
        foreach ($result->result() as $changeset)
        {
            if ($is_next_last == 1)
            {
                $next_change = $changeset->changeset_id;
            }
            if ($changeset->changeset_id == $current_changeset)
            {
                $is_next_last = 1;
            }
            if ($is_next_last == 0)
            {
                $second_last = $last_change;
                $last_change = $changeset->changeset_id;            
            }
        }
        
        $data = array();
        $data['second_last'] = $second_last;        
        $data['previous'] = $last_change;
        $data['current'] = $current_changeset;
        $data['next'] = $next_change;
        
        return $data;        
    
    }
    
    function changeset_exists($id)
    {
        $count = 0;
    	if (isset($this->changeset_exists[$id]) == false)
		{
	        $this->db->select('COUNT(`changeset_id`) as `count`');
	        $result = $this->db->get_where('changesets', array('changeset_id' => $id));
	        $row = $result->row();
	        $count = $row->count;
	    }
        if ($count == 1 || isset($this->changeset_exists[$id]) == true)
        {
            $this->changeset_exists[$id] = $id;
        	return true;
        } else {
            return false;
        }
    }

    function get_changeset($changeset_id)
    {
		if ($this->changeset_exists($changeset_id) == true)
		{

	        $this->db->select('changeset_id, screen_name, changeset_story_id, changeset_user_id, changeset_points, changeset_voters, changeset_date, changeset_comment, changeset_type');
	        $this->db->join('users', 'changesets.changeset_user_id = users.user_id', 'left');
	        $query = $this->db->get_where('changesets', array('changeset_id' => $changeset_id));
	        
	        $row = $query->row();
	        
	        $changeset = array();
	        $changeset['id'] = $row->changeset_id;
	        $changeset['book_id'] = $row->changeset_story_id;
	        $changeset['user_id'] = $row->changeset_user_id;
	        $changeset['user_name'] = $row->screen_name;
	        $changeset['date'] = $row->changeset_date;
	        $changeset['comment'] = $row->changeset_comment;
	        $changeset['type'] = $row->changeset_type;
	        $changeset['points'] = $row->changeset_points;
	        $changeset['voters'] = explode(',', $row->changeset_voters);
	        
	        return $changeset;
	        
		} else {
			return array();			
		}       
    }
    
    function diff($base, $compare_with, $story_id)
    {
    	$base_changes = $this->get_changes($base, $story_id);
    	$compare_changes = $this->get_changes($compare_with);

    	$this->load->helper('diff');
    	
    	$base_origin = array();
    	$compare_new = array();
    	$fields = array();    	
    	
    	foreach ($base_changes as $change)
    	{
    		$fields[$change['field']] = array('base' => true, 'compared' => false);
    		$base_origin[$change['field']] = array('current' => $change['new'], 'prior' => $change['old']);		    		
    	}
    	
    	foreach ($compare_changes as $change)
    	{
    		if (!isset($fields[$change['field']]))
    		{
    			$fields[$change['field']] = array('base' => false, 'compared' => true);    			   			
    		} else {
    			$fields[$change['field']] = array('base' => true, 'compared' => true);    			
    		}
    		$compare_new[$change['field']] = array('current' => $change['new'], 'prior' => $change['old']);		    		    		
    	}
    	
    	$comparing = array();
    	
    	foreach ($fields as $field => $need_compare)
    	{
    		if ($need_compare['base'] == true && $need_compare['compared'] == true)
    		{
    			$comparing[$field] = htmlDiff($base_origin[$field]['current'], $compare_new[$field]['current']);	    		
    		} elseif ($need_compare['base'] == true && $need_compare['compared'] == false)
    		{
    			$comparing[$field] = htmlDiff($base_origin[$field]['prior'], $base_origin[$field]['current']);    			
    		} elseif ($need_compare['base'] == false && $need_compare['compared'] == true)
    		{
    			$comparing[$field] = htmlDiff($compare_new[$field]['prior'], $compare_new[$field]['current']);      			
    		}
    	}

    	return $comparing;
    
    }
    
    function vote($changeset_id, $vote_type)
    {
        $this->load->model("contribution");

        if ($this->changeset_exists($changeset_id) == true)
        {
            $changeset = $this->get_changeset($changeset_id);
            if ($vote_type == 'plus')
            {
				if (in_array($this->users->cur_user['user_id'], $changeset['voters']) == false)
				{
            	
	            	if ($changeset['points'] < 15)
					{
                        // add_points($user_id, $reason, $amount, $object_type, $object_id)
                        $this->contribution->add_points($changeset['user_id'], "User approves of change", 1, "changeset_vote", $changeset_id);

	        			$changeset['voters'][] = $this->users->cur_user['user_id'];
	        			$this->db->set('changeset_points', 'changeset_points + 1', false);
						$this->db->set('changeset_voters', implode(',', $changeset['voters']));
						$this->db->where('changeset_id', $changeset_id);
	        			$this->db->update('changesets');
					}
        		
        		}				
			}
            elseif ($vote_type == 'minus')
            {
				if (in_array($this->users->cur_user['user_id'], $changeset['voters']) == false)
				{

	            	if ($changeset['points'] < -5)
		            {
                        // add_points($user_id, $reason, $amount, $object_type, $object_id)
                        $this->contribution->subtract_points($changeset['user_id'], "User disapproves of change", 1, "changeset_vote", $changeset_id);
	        			$changeset['voters'][] = $this->users->cur_user['user_id'];
	        			$this->db->set('changeset_points', 'changeset_points - 1', false);	        			
						$this->db->set('changeset_voters', implode(',', $changeset['voters']));
						$this->db->where('changeset_id', $changeset_id);
	        			$this->db->update('changesets');
	        		}
        		
    			}
        	}
    	}
	}
	
}
