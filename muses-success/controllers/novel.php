<?php
/*

    This file is part of the Muse's Success Web Fiction Directory script.

    Muse's Success Web Fiction Directory is free software: you can redistribute
    it and/or modify it under the terms of the GNU Affero General Public License
    as published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    Muse's Success Web Fiction Directory is distributed in the hope that it
    will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
    of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero
    General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with Muse's Success Web Fiction Directory. If not, see
    <http://www.gnu.org/licenses/>.

*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Novel extends Controller {

        function Novel() {
                parent::Controller();
        }

        function index()
        {
                show_404('novel');
        }

        function view()
        {

                if ($this->uri->total_segments() == 1)
                {
                    $novel_id = $this->uri->segment(1);
                } else {
                    if ($this->uri->segment(1) == 'browse' && $this->uri->segment(2) == 'view')
                    {
                        $novel_id = $this->uri->segment(3);
                    } else {
                        $novel_id = 0;
                    }
                }

                $this->load->model(array('novels','thumbnails', 'reviews_model', 'tags'));
                $this->load->helper('text');
                $this->load->library(array('simple_cache', 'disqus_sso'));

                if ($this->config->item("use_disqus_sso") == true && $this->users->logged_in == true)
                {
                    $avatar = 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($this->users->cur_user['email_address'])));

                    $this->disqus_sso->set_public_key($this->config->item("disqus_public_key"));
                    $this->disqus_sso->set_secret_key($this->config->item("disqus_secret_key"));
                    $this->disqus_sso->set_user($this->users->cur_user["user_id"], $this->users->cur_user["username"], $this->users->cur_user["email_address"]);
                    $this->disqus_sso->set_avatar($avatar);
                    $this->disqus_sso->set_url(site_url('profile/view/'.$this->users->cur_user['user_id']));
                }

                if (($novel_id != '' && $this->novels->novel_exists($novel_id) == true) || (intval($novel_id) != 0 && $this->novels->novel_exists($novel_id) == true))
                {
                        if (!$this->simple_cache->is_cached('viewnovel-'.$novel_id) && !$this->simple_cache->is_cached('viewnovel-'.$novel_id.'-page-data'))
                        {

                            $this->load->model('recs');

                            $novel = $this->novels->get_novel($novel_id);
                            $novel['reviews'] = $this->reviews_model->get_reviews('helpful', $novel['id'], 2);
                            $novel['recommendations'] = $this->recs->list_recommendations($novel['id'], 2);

                            $comments_closed = false;
                            if ($this->config->item("use_disqus") == false)
                            {
                                $novel['comments'] = array_reverse($this->novels->get_comments($novel['id']));
                            }


                            $novel['in_bookshelf'] = $this->in_library($novel["id"]);

                            $rating_count_members_query = $this->db->query('SELECT COUNT(`rating_id`) AS `rating_count` FROM `ratings` WHERE `user_id` > 0 AND `story_id` = \''.$novel['id'].'\' LIMIT 1');
                            $rating_count_members = $rating_count_members_query->row();
                            $novel['rating_member_votes'] = $rating_count_members->rating_count;
                            $rating_count_guests_query = $this->db->query('SELECT COUNT(`rating_id`) AS `rating_count` FROM `ratings` WHERE `user_id` = 0 AND `story_id` = \''.$novel['id'].'\' LIMIT 1');
                            $rating_count_guests = $rating_count_guests_query->row();
                            $ranking_query = $this->db->query('SELECT @rank:=@rank+1 AS rank FROM `stories` WHERE `story_id` = \''.$novel['id'].'\' ORDER BY `rating_order` DESC;');
                            $ranking_result = $ranking_query->row();
                            $novel['rating_guests_votes'] = $rating_count_guests->rating_count;

                            $page_data = array('page_title' => $novel['title'], 'rating' => true, 'id' => $novel['id']);

                            $this->simple_cache->cache_item('viewnovel-'.$novel_id.'-page-data', $page_data);
                            $this->simple_cache->cache_item('viewnovel-'.$novel_id, $novel);

                        } else {

                            $page_data = $this->simple_cache->get_item('viewnovel-'.$novel_id.'-page-data');
                            $novel = $this->simple_cache->get_item('viewnovel-'.$novel_id);

                        }

                        $tagss = $this->tags->get_tags($novel['id']);
                        $tag_temp = array();
                        foreach ($tagss as $tag)
                        {
                            $tag_temp[] = '<a href="'.site_url('tags/'.$tag['slug']).'">'.$tag['term'].'</a>';
                        }

                        $page_data['use_javascript'] = true;
                        $page_data['comment_thread_id'] = 'novel-'.$novel['id'].'-comments';
                        $page_data['comment_thread_title'] = $novel['title'].' by '.$novel['author_pen'];
                        $page_data['comments_closed'] = false;
                        $page_data['javascript'] = array('jqueryui/js/jquery-ui-1.8.4.custom.min.js','star_rating/jquery.ui.stars.min.js', 'rate.js');
                        $page_data['canonical'] = site_url($novel['slug']);
                        $page_data['breadcrumbs'] = array('<a href="'.site_url('browse').'">Web Fiction Listings</a>', $novel['title']);
						$page_data["facebook"] = array();
						$page_data["facebook"]["title"] = $novel["title"]." by ".$novel["author_pen"];
						$page_data["facebook"]["type"] = "book";
						$page_data["facebook"]["image"] = $this->thumbnails->get($novel["slug"], 270, 360);
						$page_data["facebook"]["author:username"] = $novel["author_pen"];
						$split_name = explode(" ", $novel["author_pen"]);
						$name_count = count($split_name);

						$page_data["facebook"]["author:first_name"] = $split_name[0];
						if (isset($split_name[$name_count-1])) {
							$page_data["facebook"]["author:last_name"] = $split_name[$name_count-1];
						}
						$page_data["facebook"]["url"] = $novel["listing_url"];
						$page_data["facebook"]["description"] = character_limiter(strip_tags($novel["summary"]),140);

                        $summary = $novel["summary"];
                        if ($summary < 5)
                        {
                            $summary = $novel["secondary_genre"];
                            if ($novel["update_schedule"] == "Completed" || $novel["update_schedule"] == "Hiatus" || $novel["update_schedule"] == "No Longer Updated")
                            {
                                $summary .= " - ".$novel["update_schedule"];
                            } else {
                                $summary .= " - Updated ".$novel["update_schedule"];
                            }
                        }

						$page_data['meta_description'] = character_limiter("By ".$novel["author_pen"]." - ".$novel["primary_genre"]." - ".strip_tags($summary)."", 140);

                        $novel['in_library'] = $this->in_library(intval($novel['id']));
                        $novel['tags'] = implode(', ', $tag_temp);

                        $this->load->view('header', $page_data);
                        $this->load->view('novel/home', $novel);
                        $this->load->view('footer');
                } else {
                        show_404('browse/view/'.$novel_id.'/');
                }
        }

        function reviews()
        {
                if ($this->uri->total_segments() == 2)
                {
                    $novel_id = $this->uri->segment(1);
                } elseif ($this->uri->total_segments() == 4)
                {
                   $novel_id = $this->uri->segment(1);
                }
                else {
                    $novel_id = $this->uri->segment(3);
                }
                $this->load->model(array('novels','thumbnails', 'reviews_model', 'recs'));
                if ($this->novels->novel_exists($novel_id) == true)
                {
                        $novel = $this->novels->get_novel($novel_id);
                        $novel['reviews'] = $this->reviews_model->get_reviews('helpful', $novel['id'], false);
                        $novel['recommendations'] = $this->recs->list_recommendations($novel['id'], 2);
                        $page_data = array('page_title' => 'Reviews - '.$novel['title'], 'rating' => true, 'id' => $novel['id']);
                        $novel['in_bookshelf'] = $this->in_library($novel["id"]);
                        $helpful = $this->uri->segment(3);
                        $reviewid = $this->uri->segment(4);
                        $exists = false;

                            $rating_count_members_query = $this->db->query('SELECT COUNT(`rating_id`) AS `rating_count` FROM `ratings` WHERE `user_id` > 0 AND `story_id` = \''.$novel['id'].'\' LIMIT 1');
                            $rating_count_members = $rating_count_members_query->row();
                            $novel['rating_member_votes'] = $rating_count_members->rating_count;
                            $rating_count_guests_query = $this->db->query('SELECT COUNT(`rating_id`) AS `rating_count` FROM `ratings` WHERE `user_id` = 0 AND `story_id` = \''.$novel['id'].'\' LIMIT 1');
                            $rating_count_guests = $rating_count_guests_query->row();
                            $ranking_query = $this->db->query('SELECT @rank:=@rank+1 AS rank FROM `stories` WHERE `story_id` = \''.$novel['id'].'\' ORDER BY `rating_order` DESC;');
                            $ranking_result = $ranking_query->row();
                            $novel['rating_guests_votes'] = $rating_count_guests->rating_count;

                        if ($reviewid!=0)
                        {
                                $review = $this->novels->get_review($reviewid);
                                if ($review != false)
                                {
                                        $exists = true;
                                }
                        }

                        if ($exists == true && $this->users->logged_in == true)
                        {
                        if ($helpful == 'helpful')
                        {
                                $rating_check = $this->db->get_where('revratings', array('user_id' => $this->users->cur_user['user_id'], 'review_id' => intval($reviewid)), 1);
                                if ($rating_check->num_rows() == 0)
                                {
                                        $rating_error = 2;

                                        $rating = array('user_id' => $this->users->cur_user['user_id'], 'review_id' => intval($reviewid), 'rating' => '1');
                                        $this->db->insert('revratings', $rating);
                                        $page_data['alert'] = 'Thank you for taking the time to rate a review.';
                                } else {
                                        $rating_error = 1;
                                        $page_data['alert'] = 'You\'ve already rated this review.';
                                }



                        } elseif ($helpful == 'nothelpful')
                        {

                                $rating_check = $this->db->get_where('revratings', array('user_id' => $this->users->cur_user['user_id'], 'review_id' => intval($reviewid)), 1);
                                if ($rating_check->num_rows() == 0)
                                {
                                        $rating_error = 2;
                                        $rating = array('user_id' => $this->users->cur_user['user_id'], 'review_id' => intval($reviewid), 'rating' => '2');
                                        $this->db->insert('revratings', $rating);
                                        $page_data['alert'] = 'Thank you for taking the time to rate a review.';
                                } else {
                                        $rating_error = 1;
                                        $page_data['alert'] = 'You\'ve already rated this review.';
                                }



                        }
                        }
                        $page_data['use_javascript'] = true;
                        $page_data['javascript'] = array('jqueryui/js/jquery-ui-1.8.4.custom.min.js','star_rating/jquery.ui.stars.min.js', 'rate.js');
                        $page_data['canonical'] = site_url($novel['slug'].'/reviews');
                        $page_data['breadcrumbs'] = array('<a href="'.site_url('browse').'">Web Fiction Listings</a>', '<a href="'.site_url($novel['slug']).'">'.$novel['title'].'</a>', 'Reader Reviews');
                        $this->load->view('header', $page_data);
                        $this->load->view('novel/reviews', $novel);
                        $this->load->view('footer');
                } else {
                        show_404('browse/view/'.$novel_id.'/');
                }
        }

        function readers()
        {
                if ($this->uri->total_segments() == 2)
                {
                    $novel_id = $this->uri->segment(1);
                } else {
                    $novel_id = $this->uri->segment(3);
                }
                $this->load->model(array('novels','thumbnails', 'reviews_model', 'recs'));
                if ($this->novels->novel_exists($novel_id) == true)
                {
                        $novel = $this->novels->get_novel($novel_id);
                        $novel['reviews'] = $this->reviews_model->get_reviews('helpful', $novel['id'], false);
                        $novel['recommendations'] = $this->recs->list_recommendations($novel['id'], 2);
                        $novel['reading'] = $this->library_get('current', $novel['id']);
                        $novel['plan'] = $this->library_get('planned', $novel['id']);
                        $novel['onhold'] = $this->library_get('onhold', $novel['id']);
                        $novel['complete'] = $this->library_get('complete', $novel['id']);
                        $novel['dropped'] = $this->library_get('dropped', $novel['id']);
                        $novel['in_bookshelf'] = $this->in_library($novel["id"]);
                        $rating_count_members_query = $this->db->query('SELECT COUNT(`rating_id`) AS `rating_count` FROM `ratings` WHERE `user_id` > 0 AND `story_id` = \''.$novel['id'].'\' LIMIT 1');
                        $rating_count_members = $rating_count_members_query->row();
                        $novel['rating_member_votes'] = $rating_count_members->rating_count;
                        $rating_count_guests_query = $this->db->query('SELECT COUNT(`rating_id`) AS `rating_count` FROM `ratings` WHERE `user_id` = 0 AND `story_id` = \''.$novel['id'].'\' LIMIT 1');
                        $rating_count_guests = $rating_count_guests_query->row();
                        $ranking_query = $this->db->query('SELECT @rank:=@rank+1 AS rank FROM `stories` WHERE `story_id` = \''.$novel['id'].'\' ORDER BY `rating_order` DESC;');
                        $ranking_result = $ranking_query->row();
                        $novel['rating_guests_votes'] = $rating_count_guests->rating_count;

                        $page_data = array('page_title' => 'Readers List - '.$novel['title'], 'rating' => true, 'id' => $novel['id']);
                        $page_data['canonical'] = site_url($novel['slug'].'/readers');
                        $page_data['use_javascript'] = true;
                        $page_data['javascript'] = array('jqueryui/js/jquery-ui-1.8.4.custom.min.js','star_rating/jquery.ui.stars.min.js', 'rate.js');
                        $page_data['breadcrumbs'] = array('<a href="'.site_url('browse').'">Web Fiction Listings</a>', '<a href="'.site_url($novel['slug']).'">'.$novel['title'].'</a>', 'Readers');
                        $this->load->view('header', $page_data);
                        $this->load->view('novel/readers', $novel);
                        $this->load->view('footer');
                } else {
                        show_404('browse/view/'.$novel_id.'/');
                }
        }

        function recommendations()
        {
                if ($this->uri->total_segments() == 2)
                {
                    $novel_id = $this->uri->segment(1);
                } else {
                    $novel_id = $this->uri->segment(3);
                }
                $this->load->model(array('novels','recs', 'thumbnails', 'reviews_model'));
                if ($this->novels->novel_exists($novel_id) == true)
                {
                    $novel = $this->novels->get_novel($novel_id);

                    $page_data = array();
                    $page_data['page_title'] = 'Recommendations - '.$novel['title'];
                    $page_data['use_javascript'] = true;
                    $page_data['javascript'] = array('jqueryui/js/jquery-ui-1.8.4.custom.min.js','star_rating/jquery.ui.stars.min.js', 'rate.js');
                    $page_data['canonical'] = site_url($novel['slug'].'/reviews');
                    $page_data['breadcrumbs'] = array('<a href="'.site_url('browse').'">Web Fiction Listings</a>', '<a href="'.site_url($novel['slug']).'">'.$novel['title'].'</a>', 'Similar Recommendations');

                    $rating_count_members_query = $this->db->query('SELECT COUNT(`rating_id`) AS `rating_count` FROM `ratings` WHERE `user_id` > 0 AND `story_id` = \''.$novel['id'].'\' LIMIT 1');
                    $rating_count_members = $rating_count_members_query->row();
                    $novel['rating_member_votes'] = $rating_count_members->rating_count;
                    $rating_count_guests_query = $this->db->query('SELECT COUNT(`rating_id`) AS `rating_count` FROM `ratings` WHERE `user_id` = 0 AND `story_id` = \''.$novel['id'].'\' LIMIT 1');
                    $rating_count_guests = $rating_count_guests_query->row();
                    $ranking_query = $this->db->query('SELECT @rank:=@rank+1 AS rank FROM `stories` WHERE `story_id` = \''.$novel['id'].'\' ORDER BY `rating_order` DESC;');
                    $ranking_result = $ranking_query->row();
                    $novel['rating_guests_votes'] = $rating_count_guests->rating_count;
                    $novel['in_bookshelf'] = $this->in_library($novel["id"]);
                    $novel['recommendations'] = $this->recs->list_recommendations($novel['id']);
                    $novel['reviews'] = $this->reviews_model->get_reviews('helpful', $novel['id'], false);
                    $this->load->view('header', $page_data);
                    $this->load->view('novel/recommendations', $novel);
                    $this->load->view('footer');

                } else {

                    show_404('browse/view/'.$novel_id.'/');

                }

        }


        function library_get($reading_status, $book_id)
        {
                $books = array();
                $i = 0;
                $this->db->where(array('book_status' => $reading_status, 'book_id' => $book_id));
                $query = $this->db->get('library');
                foreach ($query->result() as $item)
                {
                        $user = $this->users->get_user_info($item->library_user);

                        ++$i;
                        $books[$i] = array();
                        $books[$i]['user'] = '<a href="'.site_url('profile/view/'.$user['user_id'].'').'">'.((strlen($user['display_name']) >= 1) ? $user['display_name'] : $user['screen_name']).'</a>';
                        $books[$i]['rating'] = $item->book_rating;
                }

                return $books;
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
