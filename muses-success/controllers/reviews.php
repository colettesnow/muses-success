<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Reviews extends Controller {

        function Reviews() {
                parent::Controller();
        }

        function fix_ini()
        {
        
                $query = $this->db->query('SELECT * FROM `reviews`');
                foreach ($query->result() AS $review)
                {
                
                        $helpful_query = $this->db->query('SELECT * FROM `revratings` WHERE `review_id` = \''.$review->review_id.'\' AND `rating` = \'2\'');
                        $nothelpful_query = $this->db->query('SELECT * FROM `revratings` WHERE `review_id` = \''.$review->review_id.'\' AND `rating` = \'1\'');
                        $helpful = $helpful_query->num_rows();
                        $nothelpful = $nothelpful_query->num_rows();
                        $order = $helpful-$nothelpful;

                        $this->db->simple_query('UPDATE `reviews` SET `review_helpful_count` = \''.$helpful.'\', `review_not_helpful_count` = \''.$nothelpful.'\', `review_helpful_order` = \''.$order.'\' WHERE `review_id` = \''.$review->review_id.'\' LIMIT 1');
                
                }
                
                $this->load->view('header');
        
        }

        function view()
        {
                $review_id = $this->uri->segment(3);
                
                $this->load->model(array('novels', 'reviews_model', 'thumbnails'));
                
                $review = $this->reviews_model->get_review($review_id);
                
                if ($review != false)
                {
                        $novel = $this->novels->get_novel($review['listing_id']);
                        $novel['tab'] = 'reviews';
                        $novel['tab_anyway'] = true;
                        $novel['in_bookshelf'] = $this->in_library($novel["id"]);
                        $rating_count_members_query = $this->db->query('SELECT COUNT(`rating_id`) AS `rating_count` FROM `ratings` WHERE `user_id` > 0 AND `story_id` = \''.$novel['id'].'\' LIMIT 1');
                        $rating_count_members = $rating_count_members_query->row();
                        $novel['rating_member_votes'] = $rating_count_members->rating_count;
                        $rating_count_guests_query = $this->db->query('SELECT COUNT(`rating_id`) AS `rating_count` FROM `ratings` WHERE `user_id` = 0 AND `story_id` = \''.$novel['id'].'\' LIMIT 1');
                        $rating_count_guests = $rating_count_guests_query->row();
                        $ranking_query = $this->db->query('SELECT @rank:=@rank+1 AS rank FROM `stories` WHERE `story_id` = \''.$novel['id'].'\' ORDER BY `rating_order` DESC;');
                        $ranking_result = $ranking_query->row();
                        $novel['rating_guests_votes'] = $rating_count_guests->rating_count;
                        $rating_error = 0;
                        if ($this->users->logged_in == true)
                        {
                        if ($this->input->post('rate') == 'Yes')
                        {
                                $rating_check = $this->db->get_where('revratings', array('user_id' => $this->users->cur_user['user_id'], 'review_id' => intval($review_id)), 1);
                                if ($rating_check->num_rows() == 0)
                                {
                                        $rating_error = 2;
                                        $rating = array('user_id' => $this->users->cur_user['user_id'], 'review_id' => intval($review_id), 'rating' => '2');
                                        $this->db->insert('revratings', $rating);
                                        $this->db->query('UPDATE `reviews` SET `review_helpful_count` = (`review_helpful_count`) + 1, `review_helpful_order` = (`review_helpful_order`) + 1 WHERE `review_id` = \''.intval($review_id).'\' LIMIT 1');
                                } else {
                                        $rating_error = 1;
                                }
                        } elseif ($this->input->post('rate') == 'No')
                        {
                                $rating_check = $this->db->get_where('revratings', array('user_id' => $this->users->cur_user['user_id'], 'review_id' => intval($review_id)), 1);
                                if ($rating_check->num_rows() == 0)
                                {
                                        $rating_error = 2;
                                        $rating = array('user_id' => $this->users->cur_user['user_id'], 'review_id' => intval($review_id), 'rating' => '2');
                                        $this->db->insert('revratings', $rating);
                                        $this->db->query('UPDATE `reviews` SET `review_helpful_count` = (`review_not_helpful_count`) + 1, `review_helpful_order` = (`review_helpful_order`) - 1 WHERE `review_id` = \''.intval($review_id).'\' LIMIT 1');
                                } else {
                                        $rating_error = 1;
                                }
                        }
                        } else {
                                $rating_error = 3;
                        }

                        $data = array(
                                'review' => $review,
                                'rating_error' => $rating_error
                        );
                        
                        $page_data = array(
                        				'rating' => true, 
                        				'page_title' => $review["tagline"]." by ".$review["author"]." - ".$novel["title"]." Review", 
                        				'id' => $novel['id'],
                        				'use_javascript' => true,
                        				'javascript' => array('jqueryui/js/jquery-ui-1.8.4.custom.min.js','star_rating/jquery.ui.stars.min.js', 'rate.js')
                        				);
                        				
                        $page_data['breadcrumbs'] = array('<a href="'.site_url('browse').'">Web Fiction Listings</a>', '<a href="'.site_url($novel['slug']).'">'.$novel['title'].'</a>', '<a href="'.site_url($novel['slug'].'/reviews').'">Reviews</a>', 'Review by '.$review['author']);
                        
                        $this->load->view('header', $page_data);
                        $this->load->view('novel/listing_top', $novel);
                        $this->load->view('novel/view_review', $data);
                        $this->load->view('footer');
                } else {
                        show_404();
                }

        }
        
        function report()
        {

                if ($this->users->logged_in == false)
                        redirect('accounts/login');

                $review_id = $this->uri->segment(3);

                $this->load->model('novels');

                $review = $this->novels->get_review($review_id);

                $this->load->library('validation');

                $this->load->helper(array('form', 'url'));

                if ($review != false)
                {

                        $novel = $this->novels->get_novel($review['story_id']);

                        $page_data['page_title'] = 'Report Inappropriate Review';

                        $rules['reason'] = 'required|min_length[3]';
                        $fields['reason'] = 'Reason';
                        
                        $this->validation->set_rules($rules);
                        $this->validation->set_fields($fields);

                        if ($this->validation->run() == FALSE)
                        {
                                $data['success'] = false;
                        } else {
                                $data['success'] = true;
                                
                                $report_message = array();
                                $report_message['message_subject'] = '[Report] "'.$review['tagline'].'" for '.$novel['title'].'';
                                $report_message['message_from'] = $this->users->cur_user['user_id'];
                                $report_message['message_to'] = 1;
                                $report_message['message_date'] = time();
                                $report_message['message_body'] = '[b]Review:[/b] [url='.$review['url'].']'.$review['tagline'].'[/url] by '.$review['author'].'
[b]Listing:[/b] [url='.$novel['listing_url'].']'.$novel['title'].'[/url]

The following reason was given for reporting this review as inappropriate:

[quote]'.$this->input->post('reason').'[/quote]

Please check that the review meets the minimum submission requirements and take appropriate action.';

                                $this->db->insert('pms',$report_message);
                        }
                        
                        $this->load->view('header');

                        $data['review'] = $review;
                        $data['novel'] = $novel;

                        $this->load->view('novel/report_review', $data);
                        $this->load->view('footer');
                } else {
                        show_404();
                }
        
        
        }
        
        function main()
        {
        
            $this->load->model('reviews_model');
            $this->load->library('simple_cache');
            
            $this->simple_cache->expire_after = 3600;
            
            if ($this->uri->segment(2) == 'most-helpful')
            {
                $crumbs = array('<a href="'.site_url('reviews').'">Reviews</a>', 'Most Helpful Reviews');
                
                if (!$this->simple_cache->is_cached('most-helpful-reviews'))
                {
                    $reviews = $this->reviews_model->get_reviews('helpful', 0, 20);
                    $this->simple_cache->cache_item('most-helpful-reviews', $reviews);
                } else {
                    $reviews = $this->simple_cache->get_item('most-helpful-reviews');
                }
            } else {
                
                $crumbs = array('Reviews');
                if (!$this->simple_cache->is_cached('most-recent-reviews'))
                {
                    $reviews = $this->reviews_model->get_reviews('recent', 0, 20);
                    $this->simple_cache->cache_item('most-recent-reviews', $reviews);
                } else {
                    $reviews = $this->simple_cache->get_item('most-recent-reviews');
                }    
            }
            
            $data = array('reviews' => $reviews);
            
            $this->load->view('header', array('page_title' => 'Reviews', 'breadcrumbs' => $crumbs));
            $this->load->view('reviews', $data);
            $this->load->view('footer');
        
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
}

?>
