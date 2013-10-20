<?php

class Report extends Controller {

	function Report()
	{
	
		parent::Controller();

		$this->config->load('report');
	
	}

	function main()
	{
	
	    if ($this->users->logged_in == false)
	    {
	        show_error('You must be logged in to make a report.', 200);	        
	    } else {
	    
    		$report_type = $this->uri->segment(2);
    		$report_pid = $this->uri->segment(3);
    		
    		$report_types = $this->config->item('report_types');
    		$report_reasons = $this->config->item('report_reasons');	
    			
    		if (isset($report_types[$report_type]) && is_numeric($report_pid))
    		{
    		
    		    $this->load->helper('form');
    		    $this->load->library('form_validation');
    		    
    		    $this->form_validation->set_rules('reason', 'Report Reason', 'required|is_int');

        		$page_data = array();
        		$page_data['page_title'] = 'Report '.ucwords($report_type);
        		$page_data['breadcrumbs'] = array($page_data['page_title']);    		    
    		    
    		    if ($this->form_validation->run() == FALSE)
    		    {
    		    
        			$data = array('type' => $report_types[$report_type], 'reasons' => $report_reasons[$report_type]);		
        			$data['data'] = $this->get_data($report_type, $report_pid);
        			

        			
        			$this->load->view('header', $page_data);
        			$this->load->view('report/report_head', $data);
        			$this->load->view('report/report_head_'.$report_type.'', $data);
        			$this->load->view('report/report_form', $data);
        			$this->load->view('footer');
    		
    		    } else {
    		        
    		        $data = array();
    		        $data['report_user'] = $this->users->cur_user['user_id'];
    		        $data['report_type'] = ($report_type);
    		        $data['report_reason'] = intval($this->input->post('reason'));
    		        $data['report_primary_id'] = intval($report_pid);
    		        $data['report_date'] = time();
    		        		        
    		        $this->db->insert('reports', $data);
    		        
    		        $this->load->view('header', $page_data);
    		        $this->load->view('report/report_success');
    		        $this->load->view('footer');
    		        
    		    }
    			
    		}

	    }
	
	}
	
	function get_data($type, $pid)
	{
		switch ($type)
		{
			case 'post':
				$this->load->model('forums');
				$forum = $this->forums->get_post($pid);
				return $forum;
			break;
			case 'listing':
				$this->load->model('novels');
				$novel = $this->novels->get_novel($pid);
				return $novel;
			break;
			case 'changeset':
				$this->load->model('changes');
				$changes = $this->changes->get_changes($pid);
				return $changes;			
			break;			
			case 'review':
				$this->load->model(array('reviews_model', 'novels'));
				$review = $this->reviews_model->get_review($pid);
				$data = array();
				$data['user'] = '<a href="'.$review['author_id'].'">'.ucwords($review['author']).'</a>';		
				$book = $this->novels->get_novel($review['listing_id']);
				
				$data['review'] = site_url($book['slug'].'/reviews/'.$review['id']);
			
				$data['story'] = '<a href="'.site_url($book['slug']).'">'.$book['title'].'</a>';
				return $data;
			break;	
			case 'recommendation':
				$this->load->model('novels');
				
				$user = $this->uri->segment(4);
				$listing_a = $this->uri->segment(5);
				$listing_b = $this->uri->segment(6);

				$listing_one = $this->novels->get_novel($listing_a);
				$listing_two = $this->novels->get_novel($listing_b);
				$user = $this->users->get_user_info($user);

				$data = array();
				$data['listing_one'] = '<a href="'.site_url($listing_one['slug']).'">'.$listing_one['title'].'</a>';
				$data['listing_two'] = '<a href="'.site_url($listing_two['slug']).'">'.$listing_two['title'].'</a>';
				$data['user'] = '<a href="'.site_url('profile/view/'.$user['user_id']).'">'.ucwords($user['screen_name']).'</a>';
				
				return $data;
			break;
			case 'comment':
			    $data = array();
	            $data = $this->get_comment($pid);
	            return $data;  
			break;
			case 'user':
	
			break;													
		}
	}
	
	function get_comment($id)
	{
	    $this->load->helper('date_relative');
	    
	    $query = $this->db->get_where('comments', array('comment_id' => $id));
	    $comment = array();
	    if ($query->num_rows() == 1)
	    {
	        $comment_data = $query->row();
	        $comment['id'] = $comment_data->comment_id;
	        $comment['comment'] = $comment_data->comment_text;
	        $comment['user_id'] = $comment_data->user_id;
	        $comment['date'] = date_relative($comment_data->date);
	        
	        $user = $this->users->get_user_info($comment_data->user_id);
	        
	        $comment['profile'] = site_url('profile/view/'.$comment['user_id']);
	        $comment['user'] = $user['screen_name'];
	        $comment['avatar'] = 'http://www.gravatar.com/avatar/'.md5(trim(strtolower($user['email_address']))).'?s=60';

	        return $comment; 
	    }
	    

	   
	}

}
