<?php

class Tags extends Model {

	private $tag_lookup_cache;
	public $total_listings_tagged = 0;

    function Tags() {
        parent::Model();
		$this->load->helper('title');
	}

    /**
     * get_tags
     *
     * Retrieves all the tags assigned to the listing specified by listing_id
     *
     * @param $listing_id
     */
    function get_tags($listing_id)
    {
        $tags = $this->db->query('SELECT tags.tag_slug as slug, tags.tag_term as term FROM tagged LEFT JOIN tags ON tags.tag_id = tagged.tag_id WHERE story_id = '.intval($listing_id).' ORDER BY tags.tag_term ASC');
        return $tags->result_array();
    }
	
	function get_tag($tag_identifier, $identifier_type = 'id')
	{
		switch ($identifier_type)
		{
			case 'id':
				$this->db->where('tag_id', $tag_identifier);			
				break;
			case 'term':
				$this->db->where('tag_term', $tag_identifier);				
				break;
			case 'slug':
				$this->db->where('tag_slug', $tag_identifier);				
				break;
		}
		$this->db->limit(1);
		$tags = $this->db->get('tags');
		
		if ($tags->num_rows() == 1)
		{
			$row = $tags->row();
		
			$tag = array();
			$tag['id'] = $row->tag_id;
			$tag['term'] = $row->tag_term;
			$tag['slug'] = $row->tag_slug;
			$tag['description'] = $row->tag_description;
			$tag['approved'] = $row->tag_approved;
			$tag['user_id'] = $row->tag_user_id;
			$tag['date'] = array();
			$tag['date']['unix_timestamp'] = $row->tag_added_date;
			return $tag;
		} else {
			return false;
		}
		
	}

	function get_aliases($tag_id)
	{
		$this->db->where('alias_tag', $tag_id);
		$tags = $this->db->get('tag_aliases');
		$aliases = array();
		foreach ($tags->result() as $alias)
		{
			$aliases[] = $alias->alias_name;
		}
		return $aliases;
	}
	
    function get_listings_tagged($tag, $current_page = 0, $per_page = 25)
    {
		$tag_id = $this->get_tag_id_by_slug($tag);

		$tagged_count = $this->db->query('SELECT DISTINCT(`story_id`) FROM tagged WHERE tagged.tag_id = '.$tag_id);
        $this->total_listings_tagged = $tagged_count->num_rows();
		
		$tagged = $this->db->query('SELECT stories.story_id as id, story_title, story_slug, story_url, story_author, story_author_url, story_primary_genre, story_secondary_genre FROM tagged LEFT JOIN stories ON tagged.story_id = stories.story_id WHERE tagged.tag_id = '.$tag_id.' LIMIT '.$current_page.', '.$per_page);
		
		$listings_tagged = array();
		
		if ($tagged->num_rows() > 0)
		{		
			$i = 0;
			foreach ($tagged->result() as $listing)
			{
				++$i;
				$listings_tagged[$i] = array();
				$listings_tagged[$i]['id'] = $listing->id;
				$listings_tagged[$i]['title'] = $listing->story_title;
				$listings_tagged[$i]['slug'] = $listing->story_slug;
				$listings_tagged[$i]['url'] = $listing->story_url;
				$listings_tagged[$i]['listing_url'] = site_url($listing->story_slug);
				$listings_tagged[$i]['author_pen'] = $listing->story_author;				
				$listings_tagged[$i]['author_url'] = $listing->story_author_url;									
				$listings_tagged[$i]['primary_genre'] = $listing->story_primary_genre;
				$listings_tagged[$i]['secondary_genre'] = $listing->story_secondary_genre;
				$listings_tagged[$i]['mature'] = 1;
				$listings_tagged[$i]['review_count'] = 10;
				$listings_tagged[$i]['rating'] = 4;		
	 		}
		}
		return $listings_tagged;
    }

    /**
     * add_tag_to_listing
     *
     * Links a tag with a listing.
     *
     * @param $listing_id int ID of the listing to tag
     * @param $tag_id int ID of the tag to assign to the listing
     */
    function add_tag_to_listing($listing_id, $tag_id)
    {
		$tagged = array();
		$tagged['tag_id'] = $tag_id;
		$tagged['story_id'] = $listing_id;
    	$this->db->insert('tagged', $tagged);
    }

    function get_tag_id_by_slug($term)
    {
		$this->db->select('tag_id');
    	$result = $this->db->get_where('tags', array('tag_slug' => $term));
		$tag = $result->row();
		return $tag->tag_id;
    }

    function get_tag_by_id($tag_id)
    {
		$this->db->select('tag_term');
    	$result = $this->db->get_where('tags', array('tag_id' => $tag_id));
		$tag = $result->row();
		return $tag['tag_term'];
    }
    
    function lookup_tags_by_name($tag)
    {
    	$tags = $this->db->query('SELECT `tag_id`, `tag_term` FROM `tags` WHERE `tag_term` LIKE "'.urldecode($tag).'%%%" AND `tag_usable` = 1 LIMIT 5');
    	return $tags->result_array();    	
    }
	
	function get_tag_id_by_name($tag)
	{
		$this->db->like('tag_term', $tag);
		$tags = $this->db->get("tags");
		if ($tags->num_rows() == 0)
		{
			return false;
		}
		
		return $tags->row()->tag_id;
	}

    function get_tag_by_slug($slug)
    {
		$this->db->select('tag_term');
    	$result = $this->db->get_where('tags', array('tag_slug' => $slug));
		$tag = $result->row();
		return $tag->tag_term;
    }


	/**$listings_tagged
	 * create_tag
	 *
	 * Create's a tag that can be assigned to listings.
	 *
	 * @param $tag_name string name of the tag
	 * @param $tag_description string description of the tag
	 * @param $tag_type int ID of the tag's type/category
	 *
	 */
    function create_tag($tag_name, $tag_aliases, $tag_description, $tag_parents)
    {	
		$this->db->set('tag_term', $tag_name);
		$this->db->set('tag_description', $tag_description);
		$this->db->set('tag_parent', $tag_parents[0]);
		$this->db->set('tag_slug', strtoslug($tag_name));
		$this->db->set('tag_user_id', $this->users->cur_user['user_id']);
		$this->db->set('tag_added_date', time());
		$this->db->insert('tags');
		
		$tag_id = $this->db->insert_id();
		foreach ($tag_parents as $id => $parent_id)
		{
			if ($id != 0)
			{
				$this->add_alternate_parent($tag_id, $parent_id);
			}
		}
		
		foreach ($tag_aliases as $alias_tag)
		{
			$this->add_alias($tag_id, $alias_tag);
		}
		
		return $tag_id;
    }
	
	function add_alternate_parent($tag_id, $parent_id)
	{
		$this->db->set('tag_id', intval($tag_id));
		$this->db->set('tag_parent', $parent_id);
		$this->db->insert('alternate_tag_parents');
	}
	
	function add_alias($tag_id, $alias)
	{
		$this->db->set('alias_name', $alias);
		$this->db->set('alias_tag', $tag_id);
		$this->db->set('alias_slug', strtoslug($alias));
		$this->db->insert('tag_aliases');
	}
	
	function get_alias_by_term($tag)
	{
		$alias = $this->db->get_where('tag_aliases', array('alias_name' => $tag));
		if ($alias->num_rows == 1)
		{
			return $tag;
		} else {
			return FALSE;
		}
	}
    
    function get_all_tags()
    {
    	$query = $this->db->get_where('tags', array('tag_parent' => '0'));
    	$tags = array();
    	$i = 0;
    	foreach ($query->result() as $tag)
    	{
    		++$i;
    		$tags[$i] = array();
    		$tags[$i]['id'] = $tag->tag_id;
    		$tags[$i]['name'] = $tag->tag_term;
    		$tags[$i]['children'] = $this->get_children_tags($tag->tag_id);
    		$tags[$i]['slug'] = $tag->tag_slug;
    		$tags[$i]['usable'] = $tag->tag_usable;    		
    		$tags[$i]['count'] = $tag->tag_count;
			$tags[$i]['user_id'] = $tag->tag_user_id;
    	}
    	
    	return $tags;
    }
	
	function get_tags_flat($approved = 0, $order = 'latest', $limit = 10)
	{
		if ($order == 'latest')
		{
			$this->db->order_by('tag_added_date', 'DESC');
		}
		$this->db->limit($limit);
    	$query = $this->db->get_where('tags', array('tag_approved' => $approved));
    	$tags = array();
    	$i = 0;
    	foreach ($query->result() as $tag)
    	{
    		++$i;
    		$tags[$i] = array();
    		$tags[$i]['id'] = $tag->tag_id;
    		$tags[$i]['name'] = $tag->tag_term;
    		$tags[$i]['slug'] = $tag->tag_slug;
    		$tags[$i]['usable'] = $tag->tag_usable; 
			$tags[$i]['date'] = date_relative($tag->tag_added_date);
    		$tags[$i]['count'] = $tag->tag_count;
			$tags[$i]['user_id'] = $tag->tag_user_id;			
    	}
    	
    	return $tags;	
	}
    
    function get_children_tags($tag_id)
    {    
    	if (!is_numeric($tag_id))
    	{
    		$tag_id = $this->get_tag_id_by_slug($tag_id);
    	}
    	$query = $this->db->get_where('tags', array('tag_parent' => $tag_id));
		if ($query->num_rows() != 0)
		{
			$tags = array();
			$i = 0;
			foreach ($query->result() as $tag)
			{
				++$i;
				$tags[$i] = array();
				$tags[$i]['id'] = $tag->tag_id;
				$tags[$i]['name'] = $tag->tag_term;
				$tags[$i]['slug'] = $tag->tag_slug;
      			$tags[$i]['usable'] = $tag->tag_usable;  			
    			$tags[$i]['count'] = $tag->tag_count;				
				$tags[$i]['children'] = $this->get_children_tags($tag->tag_id);
			}
    	
    		return $tags;
    	} else {
    		return array();
    	} 	
    }
    
	function approve_tag($tag_id)
	{
		$this->db->set('tag_approved', 1);
		$this->db->where('tag_id', $tag_id);
		$this->db->update('tags');
	}
	
	function deny_tag($tag_id)
	{
		$this->db->set('tag_approved', 2);
		$this->db->where('tag_id', $tag_id);
		$this->db->update('tags');
		
		$this->db->where('tag_id', $tag_id);
		$this->db->delete('tagged');
	}	
	
}

