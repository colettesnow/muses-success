<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Profile extends Controller {

        function Profile() {
                parent::Controller();
        }

        function view()
        {

        		if ($this->uri->segment(1) == 'whois')
        		{
        			$user_id = $this->uri->segment(2);        			
        		} else {
                	$user_id = $this->uri->segment(3);
        		}
                $user_info = $this->users->get_user_info($user_id);

                if ($user_info != false)
                {
                        $this->load->helper('bbcode');
                        $this->load->helper('typography');

                        $count_submitted = $this->db->query('SELECT COUNT(`story_id`) AS `submitted` FROM `stories` WHERE `story_submit_by` = \''.$user_info['user_id'].'\' LIMIT 1');

                        $count_sub_result = $count_submitted->row();

                        $count_updated = $this->db->query('SELECT COUNT(`story_id`) AS `updated` FROM `stories` WHERE `story_credit` LIKE  \'%,'.$user_info['user_id'].'%\' OR `story_credit` =  \''.$user_info['user_id'].'\' LIMIT 1');

                        $recently_read_get = $this->db->query('SELECT * FROM `updates` WHERE `user_id` = \''.$user_info['user_id'].'\' AND `update_type` = \'1\' ORDER BY `update_id` DESC LIMIT 0,5');

                        $itemsx = array();

                        foreach ($recently_read_get->result() as $item)
                        {
                                $itemsx[] = '<p><a href="'.stripslashes($item->update_link).'">'.stripslashes($item->update_title).'</a><br /><small>'.$item->update_text.'</small></p>';
                        }

                        $count_up_result = $count_updated->row();

                        $data = array('user_info' => $user_info,
                                      'signature' => parse_bbcode(auto_typography($user_info['signature'])),
                                      'listings_submitted' => round($count_sub_result->submitted),
                                      'listings_updated' => round($count_up_result->updated),
                                      'recent_readings' => $itemsx,
                                      'user_group' => $this->user_group($user_info['user_group']));
                        $this->load->view('header', array('page_title' => $user_info['screen_name'].'\'s Profile', 'breadcrumbs' => array($user_info['screen_name'].'\'s Profile'), 'canonical' => site_url('profile/view/'.$user_info['user_id'])));
                        $this->load->view('accounts/view_profile', $data);
                        $this->load->view('footer');

                } else {
                        show_404('profile/view/'.$user_id.'');
                }

        }

        function reviews()
        {

                $user_id = $this->uri->segment(3);

                $user_info = $this->users->get_user_info($user_id);

                if ($user_info != false || $user_info['review_count'] != 0)
                {

                        $this->load->helper('typography');
                        $this->load->model('novels');

                        $get_reviews = $this->db->get_where('reviews', array('review_author' => ''.$user_info['user_id'].'', 'review_approved' => '1'));

                        $reviews = array();

                        foreach ($get_reviews->result() as $review)
                        {

                                $story_nfo = $this->novels->get_novel($review->review_story);

                                $reviews[] = array(
                                                'tagline' => $review->review_tagline,
                                                'text' => auto_typography($review->review_text),
                                                'story' => '<a href="'.$story_nfo['listing_url'].'">'.$story_nfo['title'].'</a>',
                                                'rating' => $review->review_rating,
                                                'review_url' => site_url('reviews/view/'.$review->review_id)
                                        );

                        }

                        $data = array('user_info' => $user_info, 'reviews' => $reviews);

                        $this->load->view('header', array('page_title' => $user_info['screen_name'].'\'s Profile', 'breadcrumbs' => array('<a href="'.site_url('profile/view/'.$user_info['user_id']).'">'.$user_info['screen_name'].'\'s Profile</a>', 'Reviews')));
                        $this->load->view('accounts/view_reviews', $data);
                        $this->load->view('footer');

                } else {
                        show_404('profile/view/'.$user_id.'');
                }

        }

        function user_group($g_id)
        {
            $this->db->select('g_user_title');
            $this->db->where('g_id', $g_id);
            $query = $this->db->get('user_groups');
            $row = $query->row();

            if ($query->num_rows() == 1)
            {
            	return $row->g_user_title;
            } else {
            	return 'Member';
            }
       	}

}
