<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Search extends Controller {

        function Search() {
                parent::Controller();
        }

        function index()
        {
                $page_data = array();
                $page_data['page_title'] = 'Site Search';
                $page_data['breadcrumbs'] = array('Site Search');
            
                $this->load->view('header', $page_data);
                $this->load->view('search_form');
                $this->load->view('footer');
        }

        function generate($array, $perPage = 10, $cur_page)
        {

        if ($cur_page == '')
        {
                $cur_page = 0;
        }

        // Return the part of the array we have requested
        return array_slice($array, $cur_page, $perPage);
        }

        function getmicrotime()
        {
                list($usec, $sec) = explode(" ",microtime());
                return ((float)$usec + (float)$sec);
        }
        
        function results()
        {

                $search_id = $this->uri->segment(3);
        
                if ((isset($_POST['q']) && strlen($_POST['q']) > 3) || $search_id != '')
                {

                        if (isset($_POST['q']) && $search_id == '')
                        {
                                $q = $_POST['q'];
                                $this->db->query('INSERT INTO `search_terms` SET `search_term` = ?, `ip_address` = ?', array($_POST['q'], $this->input->ip_address()));
                                $search_id = $this->db->insert_id();
                        } elseif (!isset($_POST['q']) && is_numeric($search_id))
                        {
                                $search_id_q = $this->db->query('SELECT * FROM `search_terms` WHERE `search_id` = \''.$search_id.'\' AND `ip_address` = \''.$this->input->ip_address().'\'');
                                $sear = $search_id_q->row();
                                $q = $sear->search_term;
                        } else {
                                redirect('search');
                                die();
                        }


                $q_orig = strip_tags(htmlspecialchars(trim($q)));
                
                $q = stripslashes($q);
                $q = htmlspecialchars_decode($q);
                $q = strip_tags($q);
                $q = trim($q);
                
                $keywords = explode(' ', $q);
                
                $words_sql = array();
                
                foreach ($keywords as $word)
                {
                        $words_sql[] = 'w.word = '.$this->db->escape($word);
                }
                 $time_one = $this->getmicrotime();
                 $query = $this->db->query('SELECT p.item_url AS url, p.item_type AS type, p.item_relation_id AS listing_id, COUNT(*) AS occurrences FROM search_items p, search_words w, search_occurences o WHERE p.item_id = o.page_id AND w.word_id = o.word_id AND ('.implode(' OR ', $words_sql).') GROUP BY p.item_id ORDER BY occurrences DESC');
                 $time_two = $this->getmicrotime();
                 $time_taken = (substr($time_two-$time_one,0,5));

                 $results = array();
                 $i = 0;
                 foreach ($query->result() as $item)
                 {
                        if ($item->type == 'listing')
                        {
                                $results[$i] = array();
                                $result = $this->db->query('SELECT `story_title`, `story_primary_genre`, `story_secondary_genre`, `story_author`, `story_url`, `story_review_count`, `story_average_rating` FROM `stories` WHERE `story_id` = \''.$item->listing_id.'\' LIMIT 1');
                                $listing = $result->row();
                                $results[$i]['title'] = $listing->story_title;
                                $results[$i]['url'] = $item->url;
                                $results[$i]['author'] = $listing->story_author;
                                $results[$i]['summary'] = '<small><a href="'.$listing->story_url.'">Read This</a> - '.$listing->story_primary_genre.' / '.((strlen($listing->story_secondary_genre) > 2) ? $listing->story_secondary_genre : $listing->story_primary_genre).' - '.$listing->story_review_count.' Reviews - Rated '.floor($listing->story_average_rating).' out of 10</small>';
                                $results[$i]['type'] = 'listing';
                                ++$i;
                        } elseif ($item->type == 'review') {
                                $results[$i] = array();
                                $result = $this->db->query('SELECT `review_tagline`, `review_author`, `review_rating` FROM `reviews` WHERE `review_id` = \''.$item->listing_id.'\' LIMIT 1');
                                $review = $result->row();
                                $result = $this->db->query('SELECT `story_title`, `story_author`, `story_slug` FROM `stories` WHERE `story_id` = \''.$item->listing_id.'\' LIMIT 1');
                                $listing = $result->row();
                                $result = $this->db->query('SELECT `display_name`, `screen_name` FROM `users` WHERE `user_id` = \''.$review->review_author.'\' LIMIT 1');
                                $user = $result->row();
                                $results[$i]['title'] = $review->review_tagline;
                                $results[$i]['url'] = $item->url;
                                $results[$i]['author'] = '<a href="'.site_url('profile/view/'.$review->review_author.'/').'">'.(($user->display_name == '') ? $user->screen_name : $user->display_name).'</a>';
                                $results[$i]['summary'] = '<small>Review of <a href="'.site_url('browse/view/'.$listing->story_slug.'/').'">'.$listing->story_title.'</a> by '.$listing->story_author.' - Rated '.floor($review->review_rating).' out of 10</small>';
                                $results[$i]['type'] = 'review';
                                ++$i;
                        }
                 }
                 $this->load->library('pagination');

                 $config['base_url'] = site_url('search/results/'.$search_id.'/page');
                 $config['total_rows'] = $query->num_rows();
                 $config['per_page'] = '20';
                 $config['uri_segment'] = 5;
                 $config['num_links'] = 10;

                 $this->pagination->initialize($config);

                 $pt = array(
                        'page_title' => $q_orig
                 );
                 
                 $pt['breadcrumbs'] = array('<a href="'.site_url('search').'">Site Search</a>', $q_orig);
                 
                 $page_id = $this->uri->segment(5);
                 
                 if ($page_id == '')
                 {
                        $result_page = 1;
                        $result_end = ($config['total_rows'] >= 20) ? 20 : $config['total_rows'];
                 } else {
                        $result_page = $page_id+1;
                        $result_end = $page_id+20;
                        if ($result_end > $config['total_rows'])
                        {
                                $result_end = $config['total_rows'];
                        }
                 }
                 
                 $data = array(
                        'q' => $q_orig,
                        'num_qued' => 'Results <strong>'.$result_page.'</strong> - <strong>'.$result_end.'</strong> of about <strong>'.$config['total_rows'].'</strong> for <strong>'.$q_orig.'</strong>',
                        'time_taken' => $time_taken,
                        'results' => $this->generate($results, '20', $this->uri->segment(5)),
                        'page_links' => $this->pagination->create_links()
                 );

                 $this->load->view('header', $pt);
                 $this->load->view('search_results', $data);
                 $this->load->view('footer');
                 
                 } else {
                 
                        redirect('search');
                 
                 }
        
        }
        /*
        function xresults()
        {
                $this->load->view('header');
                $search_id = 0;
                $search_id = $this->uri->segment(3);
                
                if ($search_id != '0' && is_numeric($search_id))
                {
                        $search_id_q = $this->db->query('SELECT * FROM `search_terms` WHERE `search_id` = \''.$search_id.'\' AND `ip_address` = \''.$this->input->ip_address().'\'');
                        $sear = $search_id_q->row();
                        $_POST['q'] = $sear->search_term;
                } else {

                if (isset($_POST['q']) == true)
                {

                        $_POST['q'] = strip_tags($_POST['q']);
                        $_POST['q'] = htmlspecialchars_decode($_POST['q']);
                        $_POST['q'] = htmlspecialchars($_POST['q']);

                        if (isset($_POST['q']) && strlen($_POST['q']) > 3)
                        {
                                $this->db->query('INSERT INTO `search_terms` SET `search_term` = ?, `ip_address` = ?', array($_POST['q'], $this->input->ip_address()));
                                $search_id = $this->db->insert_id();
                        }

                }
                }
                
                if ($search_id >= '1' || isset($_POST['q']) == true)
                {
                
                $_POST['q'] = strip_tags($_POST['q']);
                $_POST['q'] = htmlspecialchars_decode($_POST['q']);
                $_POST['q'] = htmlspecialchars($_POST['q']);

                $results = array();
                $c = 0;
                
                $query_listings = $this->db->query('SELECT * FROM `stories` WHERE MATCH (`story_title`) AGAINST (?) OR MATCH (`story_summary`) AGAINST (?)', array($_POST['q'],$_POST['q']));

                foreach ($query_listings->result() as $row)
                {
                        ++$c;
                        $results[$c] = array();
                        $results[$c]['title'] = $row->story_title;
                        $results[$c]['url']   = site_url('browse/view/'.$row->story_slug.'/');
                        $results[$c]['rating'] = $row->story_average_rating;
                        $results[$c]['author'] = $row->story_author;
                        $results[$c]['author_url'] = $row->story_author_url;
                        $results[$c]['review_count'] = $row->story_review_count;
                        $results[$c]['primary_genre'] = $row->story_primary_genre;
                        $results[$c]['secondary_genre'] = $row->story_secondary_genre;
                        $results[$c]['content'] = str_replace($_POST['q'], '<span style="background-color: yellow;">'.$_POST['q'].'</span>', htmlspecialchars(strip_tags(htmlspecialchars_decode($row->story_summary))));
                        $results[$c]['listing_id'] = 0;
                        $results[$c]['mature'] = 0;
                        $results[$c]['story_url'] = $row->story_url;
                        $results[$c]['type'] = 'listing';
                }

                $query = $this->db->query('SELECT * FROM `search_index` WHERE MATCH (`indexed_content`) AGAINST (?) OR MATCH (`indexed_title`) AGAINST (?)', array($_POST['q'],$_POST['q']));

                foreach ($query->result() as $row)
                {
                        ++$c;
                        $results[$c] = array();
                        $results[$c]['title'] = $row->indexed_title;
                        $results[$c]['url']   = $row->indexed_url;
                        $results[$c]['date']   = $row->indexed_date;
                        $results[$c]['content'] = str_replace($_POST['q'], '<span style="background-color: yellow;">'.$_POST['q'].'</span>', htmlspecialchars(strip_tags(htmlspecialchars_decode($row->indexed_content))));
                        $results[$c]['listing_id'] = $row->listing_id;
                        $results[$c]['type'] = $row->indexed_type;
                        
                        if ($row->indexed_type == 'fiction')
                        {

                        $listing = $this->db->query('SELECT * FROM `stories` WHERE `story_id` = \''.$results[$c]['listing_id'].'\' LIMIT 1');
                        $listing_info = $listing->row();
                        
                        $results[$c]['listing_name'] = $listing_info->story_title;
                        $results[$c]['listing_url'] = site_url('browse/view/'.$listing_info->story_slug.'/');
                        $results[$c]['listing_author'] = $listing_info->story_author;

                        }
                }
                
                $this->load->library('pagination');

                $config['base_url'] = site_url('search/results/'.$search_id.'/page');
                $config['total_rows'] = count($results);
                $config['per_page'] = '20';
                $config['uri_segment'] = 5;

                $this->pagination->initialize($config);

                $data = array('page_links' => $this->pagination->create_links(),'results' => $this->generate($results, '20', $this->uri->segment(5)), 'search_term' => $_POST['q']);
                $this->load->view('search_results', $data);
                $this->load->view('footer');

                }
        }
        */
        
}

?>
