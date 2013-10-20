<?php

class Tag extends Controller {

	function Tag()
	{
		parent::Controller();
		
		$this->load->scaffolding('tags');
		$this->load->helper('date_relative');

	
	}

	function suggest_tag()
	{
	    if ($this->users->logged_in == false)
	    {
	    
	        show_error('You must be logged in to add a tag.', 200);
	    
	    } else {
	        
	        $this->load->library('validation');
	        
			$rules = array();
			$rules['tag_name'] = 'required|min_length[3]|callback_valid_tag|max_length[200]';
			$rules['tag_description'] = 'min_length[5]';
			$rules['tag_alias'] = 'callback_valid_aliases';
			$rules['tag_parent'] = 'required|callback_valid_parents';

			$this->validation->set_rules($rules);
			
			$fields = array();
			$fields['tag_name'] = 'Tag Name';
			$fields['tag_description'] = 'Tag Description';
			$fields['tag_alias'] = 'Tag Aliases';
			$fields['tag_parent'] = 'Tag Parents';
			$this->validation->set_fields($fields);

            $head_data = array();
            $head_data['use_javascript'] = true;
            $head_data['javascript'] = array('jquery-autocomplete/jquery.autocomplete.min.js');
            $head_data['breadcrumbs'] = array('<a href="'.site_url('contribute').'">Contribute</a>', 'Add New Tag');
         
            if ($this->validation->run() == FALSE)
            {

                $head_data['page_title'] = 'Add New Tag';            

                $this->load->view('header', $head_data);
                $this->load->view('tags/suggest');
                $this->load->view('footer');       
         
            } else {
            
                $head_data['page_title'] = 'Tag Suggested Successfully';            
                $head_data['use_javascript'] = false;
                
                $this->load->model('tags');
             
				$aliases = $this->filter_list($this->input->post('tag_alias', true));
				$parents = $this->filter_parents($this->input->post('tag_parent', true));
			 
                $this->tags->create_tag($this->input->post('tag_name', true), $aliases, $this->input->post('tag_description', true), $parents);
            
                $this->load->view('header', $head_data);
                $this->load->view('tags/suggestion_success');
                $this->load->view('footer');
                
           	}       
	    }
	}
	
	public function valid_tag($str)
	{
        $this->load->model('tags');	
		if ($this->tags->get_tag_id_by_name($str) != FALSE || $this->tags->get_alias_by_term($str) != FALSE)
		{
			$this->validation->set_message('valid_tag', 'The tag "'.$str.'" already exists. Tags must be unique.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function valid_aliases($str)
	{
		$this->load->model('tags');
	
		$existing = array();
		$aliases = $this->filter_list($str);
		
		foreach ($aliases as $alias)
		{
			if ($this->tags->get_tag_id_by_name($alias) != FALSE || $this->tags->get_alias_by_term($alias) != FALSE)
			{
				$existing[] = $alias;
			}
		}
		
		if (count($existing) > 0)
		{
			$this->validation->set_message('valid_aliases', 'The following tags already exist: '.implode(', ', $existing).'. Aliases are fully fledged tags and must be unique.');		
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function valid_parents($str)
	{
        $this->load->model('tags');	
	
		$parents_filtered = $this->filter_list($str);
		$temp = array();
		$not_exist = array();
		foreach ($parents_filtered as $parent)
		{
			$parent_id = $this->tags->get_tag_id_by_name($parent);
			if ($parent_id != false)
			{
				$temp[] = $parent_id;
			} else {
				$not_exist[] = $parent;
			}
		}
		
		if (count($not_exist) > 0)
		{
			$this->validation->set_message('valid_parents', 'The following parent tags do not exist: '.implode(', ', $not_exist).'.');
			return FALSE;
		} else {
			return TRUE;
		}	
	}
	
	function filter_list($comma_list)
	{
		$temp = array();
		$split_list = explode(',', $comma_list);
		foreach ($split_list as $item)
		{
			if (strlen(trim($item)) > 2)
			{
				$temp[] = trim($item);
			}
		}
		return $temp;
	}
	
	function filter_parents($parents)
	{
        $this->load->model('tags');	
	
		$parents_filtered = $this->filter_list($parents);
		$temp = array();
		foreach ($parents_filtered as $parent)
		{
			$parent_id = $this->tags->get_tag_id_by_name($parent);
		
			if ($this->tags->get_tag_id_by_name($parent) != 0)
			{
				$temp[] = $parent_id;
			}
		}
		
		return $temp;
	}

	function manage()
	{

		if ($this->users->logged_in == true)
		{
		
		$this->load->model(array('novels', 'tags'));

		$listing_id = $this->uri->segment(3);
        
        $success = 0;
        
		if (is_numeric($listing_id) && $this->novels->novel_exists_id($listing_id))
		{
		
			$this->load->library('form_validation');
								
			$listing = $this->novels->get_novel($listing_id);
			$tags = $this->tags->get_tags(intval($listing_id));

            $this->form_validation->set_rules('new_tags', 'Tags', 'required');

			if ($this->form_validation->run() == FALSE)
			{

			} else {
			
				$this->load->library('simple_cache');
			
				$new = $this->input->post('new_tags');
				$new_tags = explode(', ', $new);
				
				if ($this->simple_cache->is_cached('big_tags_list_flat'))
				{
					$tags_list = $this->simple_cache->get_item('big_tags_list_flat');
				} else {
					$query = $this->db->get_where('tags', array('tag_usable' => 1));
					$tags_list = array();
					foreach ($query->result() as $t)
					{
						$tags_list[$t->tag_id] = $t->tag_term;
					}
					$this->simple_cache->cache_item('big_tags_list_flat', $tags_list);
				}
				
				foreach ($new_tags as $nt)
				{
    			    if (in_array($nt, $tags_list) == true && in_array($nt, $tags) == false)
				    {
				        $tag_id = array_search($nt, $tags_list);
				        $this->tags->add_tag_to_listing($listing_id, $tag_id);
				    }
				}
				
				$success = 1;
				
				$tags = $this->tags->get_tags(intval($listing_id));				
							
			}

			$header_data = array(
								'page_title' => $listing['title'].' - Add Tag',
								'use_javascript' => true,
								'breadcrumbs' => array('<a href="'.site_url('browse').'">Web Fiction Listings</a>', '<a href="'.site_url($listing['slug']).'">'.$listing['title'].'</a>', 'Modify Tags'),
								'javascript' => array(
									'jquery-autocomplete/jquery.autocomplete.js'
								),
								'alert' => (($success == 1) ? "You have successfully added tags." : '')
								);				
			
			$data = array('listing' => $listing, 'tags' => $tags);

			$this->load->view('header', $header_data);
			$this->load->view('tags/add_tag', $data);			
			$this->load->view('footer');		
		
		} else {
	        show_error('You may not manage tags for a listing  which does not exist.', 404);
		}
		
		} else {
			show_error('You must be <a href="'.site_url('accounts/login').'">logged in</a> to tag a listing.', 200);			
		}
		
	}

	function edit()
	{

	}
	/*
	function proc()
	{
	
	/*
		$this->load->helper('title');

		$tags = file('tags2.txt');
		$tag_type_id = 0;
		$tag_parent = 148;

		$c = 0;
		$hello = '';
		foreach ($tags as $tag)
		{	
			++$c;
			$this->db->insert('tags', array('tag_term' => trim($tag), 'tag_slug' => strtoslug($tag), 'tag_parent' => $tag_parent));
			$hello .= $tag.' added<br />';
		}
		$this->output->set_output($hello);
	
	
	$query = $this->db->query('SELECT * FROM `stories` WHERE `story_summary` = "" OR  `story_summary` = ""');
	$c= 0;
	foreach ($query->result() as $story)
	{
	++$c;
	$this->db->insert('tagged', array('tag_id' => 180, 'story_id' => $story->story_id));
	}
	
	$this->db->query('UPDATE `tags` SET `tag_count` = '.$c.' WHERE `tag_id` = 180');	
	
	}
	*/

	function browse()
	{
	
		$this->load->model('tags');
	
		$page_data = array();
		$page_data['tags'] = $this->tags->get_all_tags();
		$page_data['pending_tags'] = $this->tags->get_tags_flat(0);
		$page_data['recent_tags'] = $this->tags->get_tags_flat(1, 'latest');	
		$head_data = array();
		$head_data['page_title'] = 'Browse Tags';
		$head_data['breadcrumbs'] = array('<a href="'.site_url('browse').'">Web Fiction Listings</a>', 'Tags');
	
		$this->load->view('header', $head_data);
		$this->load->view('tags/browse', $page_data);
		$this->load->view('footer');
	
	}

	function view()
	{
	    $this->load->library('pagination');
		$this->load->model(array('novels', 'tags', 'thumbnails'));
		$this->load->helper('markdown');
		
		$tag = $this->uri->segment(2);
		$tag_data = $this->tags->get_tag($tag, 'slug');
		
		if ($tag_data != false)
		{
		
			$current_page = intval($this->uri->segment(3));			
			$listings = $this->tags->get_listings_tagged($tag, $current_page);
			$tag_id = $tag_data['id'];
			$aliases = $this->tags->get_aliases($tag_id);
			$tag_crumbs = '';

			$config['base_url'] = site_url('tags/'.$tag.'/');
			$config['uri_segment'] = 3;
			$config['total_rows'] = $this->tags->total_listings_tagged;
			$config['per_page'] = '25';
			$config['num_links'] = 5;
			$config['full_tag_open'] = '<p><strong>Pages:</strong> ';
			$config['full_tag_close'] = '</p>'; 

			$this->pagination->initialize($config);		
			
			$tag_info = array('tag_term' => $tag_data['term'], 'tag_id' => $tag_id, 'tag' => $tag_data, 'aliases' => $aliases);
			$header_data = array(
									'page_title' => 'Web Fiction Listing\'s tagged '.$tag_data['term'],
									'use_javascript' => true,
									'javascript' => array(
										'autocomplete.js'
									));
			$header_data['breadcrumbs'] = array('<a href="'.site_url('browse').'">Web Fiction Listings</a>', '<a href="'.site_url('tags').'">Tags</a>', 'Tag: '.$tag_data['term']);		
			$page_data = array('page_links' => "", 'novels' => $listings);
			$tag_info['childtags'] = $this->tags->get_children_tags($tag);

			$this->load->view('header', $header_data);
			$this->load->view('tags/list_tag_top', $tag_info);
			if ($this->tags->total_listings_tagged > 0)
			{		
				$this->load->view('browse/list_view', $page_data);			
			}
			$this->load->view('footer');

		} else {
			show_404('tags/'.$tag.'');
		}
	}
	
	function fetch_tags()
	{
		$this->load->model('tags');
		$tag = $this->input->post('q');
		$tags = $this->tags->lookup_tags_by_name($tag);
		foreach ($tags as $tag)
		{
			echo $tag["tag_term"]."\n";	
		}
		die();
	}
	
	function denied_tags()
	{
		$this->load->model('tags');
		$page_data = array();
		$page_data['page_title'] = 'Denied Tags';
		$page_data['breadcrumbs'] = array('<a href="'.site_url('tags').'">Tags</a>', 'Denied Tags');
		
		$data = array();
		$data['denied_tags'] = $this->tags->get_tags_flat(2);
	
		$this->load->view('header', $page_data);
		$this->load->view('tags/denied_tags', $data);		
		$this->load->view('footer');
	}

	function pending_tags()
	{
		$this->load->model('tags');
		$page_data = array();
		$page_data['page_title'] = 'Denied Tags';
		$page_data['breadcrumbs'] = array('<a href="'.site_url('tags').'">Tags</a>', 'Pending Tags');
		
		$data = array();
		$data['pending_tags'] = $this->tags->get_tags_flat(0);
	
		$this->load->view('header', $page_data);
		$this->load->view('tags/pending_tags', $data);		
		$this->load->view('footer');
	}
	
	function mod_action()
	{
		$this->load->model('tags');
	
		if ($this->users->has_permission('g_admin_panel') == 1 || $this->users->has_permission('g_admin_panel') == 2)
		{	
			$action = $this->uri->segment(3);
			$tag_id = $this->uri->segment(4);
			
			$tag = $this->tags->get_tag($tag_id, 'id');
			
			if ($tag != false)
			{	
				if ($action == 'approve')
				{
					$this->tags->approve_tag($tag_id);
					show_error('The tag \''.$tag['term'].'\' was approved successfully.', 200);
				} elseif ($action = 'deny') {
					$this->tags->deny_tag($tag_id);
					show_error('The tag \''.$tag['term'].'\' was denied successfully.', 200);
				}	
			} else {
				show_error('The selected tag does not exist.', 404);				
			}
					
		} else {
			show_error('Access Denied.', 403);
		}
	}
	
}
