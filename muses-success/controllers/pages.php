<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Pages extends Controller {

        function Pages() {
                parent::Controller();
        }

        function view()
        {
	       	$page_slug = $this->uri->segment(2);
        	
        	$query = $this->db->get_where('pages', array('page_slug' => $page_slug));
        	
        	$page_data = $query->row();
        	
        	if ($query->num_rows() == 1)
        	{
        		$this->load->helper('markdownextra');        	
	        	
	        	$head = array();
	        	$head['page_title'] = $page_data->page_title;
	        	$head['breadcrumbs'] = array($page_data->page_title);
	        	$head['canonical'] = site_url('p/'.$page_data->page_slug);
        		
        		$page = array();
	        	$page['page_title'] = $head['page_title'];
	        	$page['content'] = markdown($page_data->page_content);
      	
	        	$this->load->view('header', $head);
	        	$this->load->view('page_template', $page);
	        	$this->load->view('footer');
        
        	} else {
        		show_404('p/'.$page_slug);	
        	}
        
        }
        
        function faqs()
        {
        	/* It really is not worth making a model for this just yet, at least not until I have an admin interface for it
        	 * which isn't a high priority as I am the only person really managing it. */
        	$this->load->helper('markdown');    
        	
        	$query = $this->db->query('SELECT * FROM `faqs_categories` ORDER BY `cat_order` ASC');
        	$faqs = array();
        	$i = 0;
        	foreach ($query->result() as $question_category)
        	{
        		++$i;
        		$faqs[$i] = array();
        		$faqs[$i]['id'] = $i;
        		$faqs[$i]['cat_name'] = $question_category->cat_name;
        		$faqs[$i]['questions'] = array();
        		$questions = $this->db->query('SELECT * FROM `faqs_questions` WHERE `question_category` = '.$question_category->cat_id.' ORDER BY `question_question` ASC');
        		$c = 0;
        		foreach ($questions->result() as $question)
        		{
        			++$c;
        			$faqs[$i]['questions'][$c] = array();
        			$faqs[$i]['questions'][$c]['id'] = $c;
        			$faqs[$i]['questions'][$c]['perma-id'] = $question->question_id;
        			$faqs[$i]['questions'][$c]['question'] = $question->question_question;
        			$faqs[$i]['questions'][$c]['answer'] = parse_markdown($question->question_answer);         			
        		}
        	}
        	
        	$head_data = array();
        	$head_data['page_title'] = 'Frequently Asked Questions';
        	$head_data['breadcrumbs'] = array('Frequently Asked Questions');
        	$page_data = array();
        	$page_data['faqs'] = &$faqs;	
        	
        	$this->load->view('header', $head_data);
        	$this->load->view('pages/faqs', $page_data);
        	$this->load->view('footer');
        	
        }
        
}