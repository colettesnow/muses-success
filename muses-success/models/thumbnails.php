<?php

class Thumbnails extends Model {
    
    function insert($story_id, $file_name)
    {
        $data['thumbnail_url'] = '/images/thumbnails/'.$file_name;
        $this->db->where('story_id', $story_id);
        $this->db->update('stories', $data);
        
        $data = array();
        $data['request_status'] = 'filled';
        $this->db->where('story_id', $story_id);
        $this->db->where('request_status', 'requested');
        $this->db->update('thumbnail_reset', $data);
    }
    
    function request($story_id)
    {
        $data['story_id'] = $story_id;
        $data['request_date'] = time();
        $data['request_status'] = 'requested';
        $this->db->insert('thumbnail_reset', $data);
    }
    
    function get($story_id, $height = 90, $width = 120)
    {
        if (is_numeric($story_id) == false)
	    {
	    	$slug = $story_id;	        	
	    } else {    	
	    	$this->db->select('story_slug, story_url');
	        $this->db->where('story_id', $story_id);
	        $stories = $this->db->get('stories');
	        $story = $stories->row();
			$slug = $story->story_slug;	        
        }
		
        return $this->config->item('static_resources_url').'images/web-fiction-thumbnails/'.$slug.'-'.$width.'x'.$height.'.png';
 
    }
    
    function fetch_requests($status)
    {
        $this->db->select('thumbnail_reset.story_id, thumbnail_reset.request_status, stories.story_title, stories.story_url, stories.story_author');
        $this->db->from('thumbnail_reset');
        $this->db->join('stories', 'stories.story_id = thumbnail_reset.story_id');
        $result = $this->db->get();
        
        $items = array();
        $i = 0;
        foreach ($result->result() as $thumbnail)
        {
            $items[$i] = array();
            $items[$i]['id'] = $thumbnail->story_id;
            $items[$i]['title'] = stripslashes($thumbnail->story_title);
            $items[$i]['url'] = $thumbnail->story_url;
            $items[$i]['author'] = stripslashes($thumbnail->story_author);
            $items[$i]['status'] = $thumbnail->request_status;             
        }
        
        return $items;   
    }
    
    function last_reset($id)
    {
                
        $this->db->order_by('thumbnailreset_id', 'DESC');
        $thumb = $this->db->get_where('thumbnail_reset', array('story_id' => $id), 1);
                
        $thumb_info = $thumb->row();
                
        if ($thumb->num_rows == 1)
        {              
            return $thumb_info->request_date;                
        } else {                
            return 0;                
        }
    }    
    
}