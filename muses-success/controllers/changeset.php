<?php

class Changeset extends Controller {

    function Changeset()
    {
        parent::Controller();
        $this->load->model('changes');
    }

    function history()
    {
        $book_id = $this->uri->segment(3);

        $this->load->model(array('changes','novels'));
        $novel = $this->novels->get_novel($book_id);

        $changesets = $this->changes->list_changesets($book_id, 0, 20);
        $data = array('changesets' => $changesets['changesets']);
        $data['title'] = $novel['title'];
        $data['book_id'] = $book_id;

        $head_data = array('page_title' => 'Revision History for '.$novel['title'],
                                           'breadcrumbs' => array('<a href="'.site_url('browse').'">Web Fiction Listings</a>', '<a href="'.site_url($novel['slug']).'">'.$novel['title'].'</a>', 'Revision History')
        );
        
        $this->load->view('header', $head_data);
        $this->load->view('changesets/list', $data);
        $this->load->view('footer');
    }

    function revision()
    {
        $changeset_id = $this->uri->segment(3);
    
        if ($this->changes->changeset_exists($changeset_id) == true)
        {
            $this->load->model(array('novels', 'thumbnails', 'recs', 'tags', 'reviews_model'));
            $this->load->helper(array('text', 'markdown'));
            
            $this->load->library('disqus_sso');

            if ($this->config->item("use_disqus_sso") == true && $this->users->logged_in == true)
            {
                $avatar = 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($this->users->cur_user['email_address'])));

                $this->disqus_sso->set_public_key($this->config->item("disqus_public_key"));
                $this->disqus_sso->set_secret_key($this->config->item("disqus_secret_key"));
                $this->disqus_sso->set_user($this->users->cur_user["user_id"], $this->users->cur_user["username"], $this->users->cur_user["email_address"]);
                $this->disqus_sso->set_avatar($avatar);
                $this->disqus_sso->set_url(site_url('profile/view/'.$this->users->cur_user['user_id']));
            }

            $data = array('revision' => $changeset_id, 'changes' => $this->changes->get_changes($changeset_id));

                    $changeset = $this->changes->get_changeset($changeset_id);
                    $changeset_changes = $this->changes->rollback($changeset_id, $changeset['book_id']);
                    $data['changeset'] = $changeset;

                    $listing_data = $this->novels->get_novel($changeset['book_id'], true, $changeset_changes);
                    $span = $this->changes->changeset_span($changeset_id, $changeset['book_id']);
                    $listing_data['older_revision'] = true;
                    $listing_data['comparision_possible_with_last'] = $span['second_last'];
                    $listing_data['previous_exists'] = ($span['previous'] != 0 && $span['previous'] != $span['second_last'] && $span['previous'] != $changeset_id) ? 1 : 0;                
                    $listing_data['current_diff'] = site_url('changeset/diff/'.$changeset['book_id'].'/'.$span['previous'].'/'.$changeset_id.'');
                    $listing_data['newer_diff'] =  site_url('changeset/diff/'.$changeset['book_id'].'/'.$changeset_id.'/'.$span['next'].'/');
                    $listing_data['previous_diff'] = site_url('changeset/diff/'.$changeset['book_id'].'/'.$span['second_last'].'/'.$span['previous'].'');
                    $listing_data['next_exists'] = $span['next'];
                    $listing_data['newer_revision'] = site_url('changeset/revision/'.$span['next'].'');
                    $listing_data['previous_revision'] = site_url('changeset/revision/'.$span['previous'].'');
            $data['reviews'] = $this->reviews_model->get_reviews('helpful', $listing_data['id'], 2);
                    $data['comments'] = array_reverse($this->novels->get_comments($listing_data['id']));
            
                    $data['listing_url'] = $listing_data['listing_url'];
            $rating_count_members_query = $this->db->query('SELECT COUNT(`rating_id`) AS `rating_count` FROM `ratings` WHERE `user_id` > 0 AND `story_id` = \''.$listing_data['id'].'\' LIMIT 1');
            $rating_count_members = $rating_count_members_query->row();
            $data['rating_member_votes'] = $rating_count_members->rating_count;
            $rating_count_guests_query = $this->db->query('SELECT COUNT(`rating_id`) AS `rating_count` FROM `ratings` WHERE `user_id` = 0 AND `story_id` = \''.$listing_data['id'].'\' LIMIT 1');
            $rating_count_guests = $rating_count_guests_query->row();
            $ranking_query = $this->db->query('SELECT @rank:=@rank+1 AS rank FROM `stories` WHERE `story_id` = \''.$listing_data['id'].'\' ORDER BY `rating_order` DESC;');
            $ranking_result = $ranking_query->row();
            $data['rating_guests_votes'] = $rating_count_guests->rating_count;
            $data['in_bookshelf'] = $this->in_library($listing_data["id"]);
            $data['recommendations'] = $this->recs->list_recommendations($listing_data['id']);

            $tagss = $this->tags->get_tags($listing_data['id']);
            $tag_temp = array();
            foreach ($tagss as $tag)
            {
                $tag_temp[] = '<a href="'.site_url('tags/'.$tag['slug']).'">'.$tag['term'].'</a>';
            }
            $data['tags'] = implode(', ', $tag_temp);
            
            
                    $listing_data = array_merge($data, $listing_data);

                    
                    
                    $page_data = array();
                    $page_data['comments_closed'] = true;
                    $page_data['breadcrumbs'] = array('<a href="'.site_url('browse').'">Web Fiction Listings</a>', '<a href="'.site_url($listing_data['slug']).'">'.$listing_data['title'].'</a>', 'Change Set #'.$changeset_id);
                    $page_data['page_title'] = 'Prior Revision of Listing '.$listing_data['title'];
                    $page_data['use_javascript'] = true;
                    $page_data['canonical'] = site_url($listing_data['slug']);
            $page_data['javascript'] = array('jqueryui/js/jquery-ui-1.8.4.custom.min.js','star_rating/jquery.ui.stars.min.js', 'rate.js');
  
                    
            $this->load->view('header', $page_data);
            $this->load->view('novel/home', $listing_data);
            $this->load->view('footer');    
        } else {
            show_error('The requested revision does not exist.', 404);
        }
    }
        function in_library($novel_id)
        {
                if ($this->users->logged_in == true)
                {

                        $query = $this->db->get_where('library', array('book_id' => intval($novel_id), 'library_user' => $this->users->cur_user['user_id']));

                        $library = $query->row();

                        if ($query->num_rows() == 1)
                        {

                                $data['status'] = ($library->book_status);
                                $data['score'] = intval($library->book_rating);
                                $data['progress'] = intval($library->chapters_read);

                                return $data;

                        } else {
                                return false;
                        }

                } else {
                        return false;
                }
        }    
    function diff()
    {
    
             $listing_id = $this->uri->segment(3);           
            $changeset_base_id = $this->uri->segment(4);
            $changeset_compare_id = $this->uri->segment(5);
            $this->load->model('novels');
            
        if ($this->changes->changeset_exists($changeset_base_id) == true && $this->changes->changeset_exists($changeset_compare_id) == true && $this->novels->novel_exists_id($listing_id) == true)
        {                
    
                $this->load->helper('diff');
                
                $changeset = $this->changes->diff($changeset_base_id, $changeset_compare_id, $listing_id);
                $data = array();
                $data['revision_base'] = $changeset_base_id;
                $data['revision_dest'] = $changeset_compare_id;
                $data['book'] = $this->novels->get_novel($listing_id, true);
                
                $data['changes'] = $changeset;
                
                $page_data = array();
                $page_data['page_title'] = 'Compare Change Set #'.intval($changeset_base_id).' with #'.intval($changeset_compare_id).' - '.$data['book']['title'];
                $page_data['canonical'] = site_url($data['book']['slug']);
                
            $this->load->view('header', $page_data);
            $this->load->view('changesets/view', $data);
            $this->load->view('footer');                    
   
        } else {
            show_error('You cannot make a comparison between items when not all of them exist.', 200);        
        }
                        
    }
    
    function recent()
    {

        $config['base_url'] = site_url('recent-changes/page');
        $config['per_page'] = '30';
        $config['page_query_string'] = false; 
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<p><strong>Pages:</strong> ';
        $config['full_tag_close'] = '</p>';
    
        $current_page = $this->uri->segment(3);
        $changesets = $this->changes->list_changesets('all', $current_page, $config['per_page']);

        $config['total_rows'] = $changesets['total_changes'];
        
        $data = array('changesets' => $changesets['changesets']);
        
        $head = array();
        $head['use_javascript'] = true;
        $head['page_title'] = 'Recent Changes';
        $head['breadcrumbs'] = array('<a href="'.site_url('contribute').'">Contribute</a>', 'Recent Changes');
        $head['javascript'] = array('recent-changes.js');
        
        $this->load->library('pagination');
        $this->pagination->initialize($config); 
       
        $this->load->view('header', $head);
        $this->load->view('changesets/recent', $data);
        $this->load->view('footer');        
    
    }
    
    function vote()
    {
        if ($this->users->logged_in == true)
        {

            $changeset_id = $this->input->post('cid');
            $vote_type = ($this->input->post('opt') == 'plus') ? 'plus' : 'minus';

            if ($this->changes->changeset_exists($changeset_id) == true)
            {
                $this->changes->vote($changeset_id, $vote_type);
            }
        
        } else {
            die();
        }
    }
    
    function compare()
    {
    
        $this->load->helper('url');
        $this->load->library("form_validation");
        
        $this->form_validation->set_rules("revision", "Revision 1", "required|numeric");
        $this->form_validation->set_rules("revision2", "Revision 2", "required|numeric");
                
        if ($this->form_validation->run() == false)
        {
            show_error('You shouldn\'t access this page directly.', 403);
        } else {
        
            $listing_id = intval($this->input->post('listing_id'));
        
            $revision1 = $this->input->post('revision');
            $revision2 = $this->input->post('revision2');
            
            redirect('changeset/diff/'.$listing_id.'/'.$revision1.'/'.$revision2.'');
        
        }
    
    }

}
