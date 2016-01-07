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
class Contribute extends Controller {

    function Contribute() {
        parent::Controller();

        $this->config->load('web_fiction');
    }

    function index()
    {
        $this->load->view('header', array('page_title' => 'Contribute', 'breadcrumbs' => array('Contribute')));
        $this->load->view('contribute/main');
        $this->load->view('footer');
    }

    function is_checked($form_value, $value)
    {
        if ($form_value == $value)
        {
            echo ' checked="checked"';
        }
    }

    function add_comment()
    {
        if ($this->users->logged_in == false)
        {
            show_error('You must <a href="'.site_url('accounts/login').'">log in</a> to be able to post a comment.', 200);
        } elseif ($this->users->has_permission('g_create_comment') == 0)
        {
            show_error('You are not allowed to post comments.', 403);
        }

        $this->load->library('validation');

        $rules['comment'] = 'required|min_length[5]';
        $rules['listing_id'] = 'required|callback_valid_listing_id';
        $this->validation->set_rules($rules);
        $fields['comment'] = 'Comment Text';
        $fields['listing_id'] = 'Listing ID';
        $this->validation->set_fields($fields);

        if ($this->validation->run() == FALSE)
        {
            $this->load->view('header', array('page_title' => 'Add Comment Error'));
            $this->load->view('contribute/add_comment_failure');
            $this->load->view('footer');

        } else {
            $this->db->insert('comments', array('user_id' => $this->users->cur_user['user_id'], 'comment_text' => htmlspecialchars($_POST['comment']), 'listing_id' => intval($_POST['listing_id']), 'ip_address' => $_SERVER['REMOTE_ADDR'], 'date' => time()));
            $this->load->view('header', array('page_title' => 'Comment Added'));
            $this->load->view('contribute/add_comment_success');
            $this->load->view('footer');
        }


    }

    function valid_listing_id($id)
    {
        $this->load->model('novels');
        $i = 0;
        if ($this->novels->novel_exists($id) == true)
        {
            $i = 1;
            return true;
        }

        if ($i == 0)
        {
            $this->validation->set_message('valid_listing_id', 'The listing ID or slug provided was invalid.');
            return false;
        }
    }

    function new_listing()
    {
        $this->load->model('changes');

        if ($this->users->logged_in == false)
        {
            redirect('accounts/login');
            die();
        }

        $this->load->library('validation');

        $rules['title']              = 'required|callback_check_title';
        $rules['tagline']            = '';
        $rules['story_url']          = 'required|callback_valid_url';
        $rules['story_rss']          = 'callback_valid_url';
        $rules['purchase_url']       = 'callback_valid_url';
        $rules['author_pen']         = 'required';
        $rules['primary_genre']      = 'required|callback_check_genre[1]';
        $rules['secondary_genre']    = 'callback_check_genre[2]';
        $rules['update_schedule']    = 'callback_check_update';
        $rules['synopsis']           = 'min_length[5]';
        $rules['audience']           = '';
        $rules['coarse']             = 'numeric';
        $rules['sex']                = 'numeric';
        $rules['violent']            = 'numeric';
        $rules['author_hp']          = 'callback_valid_url';
        $this->validation->set_rules($rules);

        $fields['title']              = 'Title';
        $fields['tagline']            = 'Tagline';
        $fields['story_url']          = 'Read URL';
        $fields['purchase_url']       = 'Purchase URL';
        $fields['story_rss']          = 'RSS Feed URL';
        $fields['author_pen']         = 'Author\'s Name';
        $fields['author_hp']          = 'Author\'s Homepage';
        $fields['primary_genre']      = 'Primary Genre';
        $fields['secondary_genre']    = 'Secondary Genre';
        $fields['update_schedule']    = 'Update Schedule';
        $fields['coarse']             = 'Coarse Language';
        $fields['sex']                = 'Sex Scenes';
        $fields['violent']            = 'Violence';
        $fields['synopsis']           = 'Synopsis';
        $fields['audience']           = 'Audience';

        $this->validation->set_fields($fields);

        $this->validation->set_error_delimiters('<p class="error">', '</p>');

        $data = array(
                        'genres' => $this->config->item('genres'),
                        'update_schedules' => $this->config->item('update_schedules')
        );
        if ($this->validation->run() == FALSE)
        {
            $this->load->view('header', array('page_title' => 'Submit New Listing', 'breadcrumbs' => array('<a href="'.site_url('contribute').'">Contribute</a>', 'Submit New Web Fiction Listing')));
            $this->load->view('contribute/add_listing', $data);
            $this->load->view('footer');
        } else {

            $mature = 0;
            $coarse = 0;
            $sex = 0;
            $violent = 0;
            if (isset($_POST['coarse']) && ($_POST['coarse'] != '1' || $_POST['coarse'] != '0'))
            {
                $mature = 1;
                $coarse = intval($_POST['coarse']);
            }
            if (isset($_POST['sex']) && ($_POST['sex'] != '1' || $_POST['sex'] != '0'))
            {
                $mature = 1;
                $sex = intval($_POST['sex']);
            }
            if (isset($_POST['violent']) && ($_POST['violent'] != '1' || $_POST['violent'] != '0'))
            {
                $mature = 1;
                $violent = intval($_POST['violent']);
            }
            $db_insert = array(
                                'story_added' => time(),
                                'story_approved' => 0,
                                'story_submit_by' => $this->users->cur_user['user_id'],
                                'rating_order' => '400'
            );

            $changes = array(
                                'story_title' => htmlspecialchars($this->input->post('title', true)),
                                'story_subtitle' => htmlspecialchars($this->input->post('tagline', true)),
                                'story_url' => $this->input->post('story_url', true),
                                'story_rss' => $this->input->post('story_rss', true),
                                'story_author' => $this->input->post('author_pen', true),
                                'story_author_url' => $this->input->post('author_hp', true),
                                'story_purchase_link' => $this->input->post('purchase_url', true),
                                'story_primary_genre' => $this->input->post('primary_genre', true),
                                'story_secondary_genre' => $this->input->post('secondary_genre', true),
                                'story_update_schedule' => $this->input->post('update_schedule', true),
                                'story_summary' => $this->input->post('synopsis', true),
                                'story_mature' => $mature,
                                'story_mature_coarse' => $coarse,
                                'story_mature_sex' => $sex,
                                'story_mature_violence' => $violent,
                                'story_audience' => $this->input->post('audience', true),
            );

            $index = array(
                    'story_slug' => $this->create_slug($this->input->post('title')),
                    'story_index_title' => $this->create_sort_title($this->input->post('title', true)),            
            );

            $this->db->insert('stories', $db_insert);
            $this->load->model('changes');

            $listing_id = $this->db->insert_id();
            $this->changes->book_id = $listing_id;
            $this->changes->changeset_comment = $this->input->post('comment', true);
            
            $this->changes->type = 'added'; 
            foreach ($changes as $field => $value)
            {
                $this->changes->change('stories', $field, $value);            
            }
            
            $this->changes->commit();
            
            $this->changes->type = 'edited';
            $this->changes->book_id = $listing_id;
            $this->changes->changeset_comment = 'Housekeeping. Generated index title and slug.';            
            foreach ($index as $field => $value)
            {
                $this->changes->change('stories', $field, $value);                           
            }
            $this->changes->user_id = 2;
            $this->changes->commit();
                        
            $this->load->view('header');
            $this->load->view('contribute/add_success');
            $this->load->view('footer');
        }

    }

    function check_genre($genre, $value)
    {
        if (!in_array($genre, $this->config->item('genres')))
        {
            if ($value == 1)
            {
                $this->validation->set_message('check_genre', 'The Primary Genre field is required.');
            } else {
                $this->validation->set_message('check_genre', 'The Secondary Genre field is required.');
            }
            return false;
        } else {
            return true;
        }
    }

    function check_update($update)
    {
        if (!in_array($update, $this->config->item('update_schedules')) && $update != 'none')
        {
            $this->validation->set_message('check_update', 'You may only choose an update schedule from the list.');

            return false;
        } else {
            return true;
        }
    }

    function create_slug($str)
    {
        $str = strtolower($str);
        $str = trim($str);
        $str = str_replace(' ', '-', $str);
        $str = str_split($str);
        $allowed_charas = 'abcdefghijklmnopqrstuvwxyz1234567890-';
        $allowed_charas = str_split($allowed_charas);
        $slug_array = array();
        foreach($str as $chara)
        {
            if (in_array($chara, $allowed_charas))
            {
                $slug_array[] = $chara;
            }
        }
        $str = implode('', $slug_array);
        $str = trim($str, '-');

        return $str;
    }

    // just remove leading articles from the internal title which we use to sort
    function create_sort_title($str)
    {
        $str = str_replace('The ', '', $str);
        $str = str_replace('A ', '', $str);
        $str = str_replace('An ', '', $str);
        return $str;
    }



    function valid_url($str)
    {
        $check_url = ( ! preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $str)) ? FALSE : TRUE;
        if ($check_url == false)
        {
            $this->validation->set_message('valid_url', 'The url `'.htmlspecialchars($str).'` is not valid.');
            return false;
        } else {
            return true;
        }
    }

    function check_title($title)
    {
        $slug = $this->create_slug($title);
        $check = $this->db->get_where('stories', array('story_slug' => $slug), 1);
        if ($check->num_rows() == 1)
        {
            $this->validation->set_message('check_title', 'We already list a web novel with this title. You can view it <a href="'.site_url('browse/view/'.$slug.'/').'">here</a>.');
            return false;
        } else {
            return true;
        }
    }

    function set_field_value($field_name, $default_value)
    {
        if ($this->validation->$field_name == '')
        {
            return htmlspecialchars(strip_tags($default_value));
        } else {
            return $this->validation->$field_name;
        }
    }

    function min_words($str, $count)
    {
        $c = str_word_count($str);
        if ($c >= $count)
        return true;
        else {
            $this->validation->set_message('min_words', 'The %s must be at least '.$count.' words in length. Your %s is only '.$c.' words.');

            return false;
        }
    }

    function submit_review()
    {
        if ($this->users->logged_in == false)
        {
            redirect('accounts/login');
            die();
        }

        $listing_id = $this->uri->segment(3);

        if ($listing_id != '')
        {

            $this->load->model(array('novels', 'contribution'));

            $novel = $this->novels->get_novel($listing_id);

            if ($novel != false)
            {

                $this->load->library('validation');
                $rules['tagline']            = 'required|max_length[80]|min_length[5]';
                $rules['review']             = 'required|min_length[150]|max_length[10000]|callback_min_words[100]';
                $rules['rating']             = 'required|min_length[1]|max_length[2]|numeric';
                $this->validation->set_rules($rules);

                $fields['tagline']           = 'Tagline';
                $fields['review']            = 'Review Text';
                $fields['rating']            = 'Rating';
                $this->validation->set_fields($fields);

                if ($this->validation->run() == FALSE)
                {
                    $this->load->view('header', array('page_title' => 'Submit New Review', 'breadcrumbs' => array('<a href="'.site_url('contribute').'">Contribute</a>', 'Submit Review for '.$novel['title'])));
                    $this->load->view('contribute/add_review', array('novel' => $novel));
                    $this->load->view('footer');
                } else {
                    $check_update = $this->db->query('SELECT `review_id` FROM `reviews` WHERE `review_author` = \''.$this->users->cur_user['user_id'].'\' AND `review_story` = \''.$listing_id.'\' LIMIT 1');
                    $is_update = 0;
                    if ($check_update->num_rows() == 1)
                    {
                        $is_update = 1;
                    }
                    $db_insert = array(
                                        'review_tagline' => strip_tags(htmlspecialchars($this->input->post('tagline', true))),
                                        'review_rating' => intval($this->input->post('rating', true)),
                                        'review_text' => htmlspecialchars(strip_tags($this->input->post('review', true))),
                                        'review_author' => $this->users->cur_user['user_id'],
                                        'review_story' => $listing_id,
                                        'is_update' => $is_update,
                                        'review_date' => time(),
                                        'review_approved' => '1'
                                        );

                                        $page_data = array();
                                        $page_data['breadcrumbs'] = array('<a href="'.site_url('contribute').'">Contribute</a>', '<a href="'.site_url('contribute/submit_review').'">Submit New Review</a>', 'Submission Successful');
                                        $page_data['page_title'] = 'Submission Successful - Submit New Review';                                        
                                        
                                        $this->db->insert('reviews', $db_insert);

                                        $review_id = $this->db->insert_id();

                                        $this->contribution->add_points($this->users->cur_user['user_id'], "Review added", 10, "review", $review_id);

                                        $this->load->view('header');
                                        $this->load->view('contribute/review_submitted');
                                        $this->load->view('footer');
                }

            } else {
                show_404();
            }

        } else {

            if ($this->input->post('search') == 'Search Listings' && ($this->input->post('title') != ''))
            {
                $data = array();
                $data['listings'] = array();
                $c = 0;
                $query = $this->db->query('SELECT `story_title`, `story_id` FROM `stories` WHERE MATCH(`story_title`) AGAINST('.$this->db->escape('%'.$this->input->post('title', true).'%').') AND `story_approved` = \'1\' AND `is_update` = \'0\' LIMIT 0, 10');
                foreach ($query->result() as $listing)
                {
                    ++$c;
                    $data['listings'][$c] = array();
                    $data['listings'][$c]['title'] = $listing->story_title;
                    $data['listings'][$c]['update_url'] = site_url('contribute/submit_review/'.$listing->story_id.'/');
                }

                if ($c == 0)
                {
                    $data['none'] = true;
                } else {
                    $data['none'] = false;
                }

                $page_data = array();
                $page_data['breadcrumbs'] = array('<a href="'.site_url('contribute').'">Contribute</a>', '<a href="'.site_url('contribute/submit_review').'">Submit New Review</a>', 'Search Results');
                $page_data['page_title'] = 'Search Results - Submit New Review';
                
                $this->load->view('header', $page_data);
                $this->load->view('contribute/existing_results_review', $data);
                $this->load->view('footer');
                
            } else {
                $page_data = array();
                $page_data['breadcrumbs'] = array('<a href="'.site_url('contribute').'">Contribute</a>', 'Submit New Review');
                $page_data['page_title'] = 'Submit New Review';                
                
                $this->load->view('header', $page_data);
                $this->load->view('contribute/existing_review');
                $this->load->view('footer');
            }

        }

    }

    function update_listing()
    {
        if ($this->users->logged_in == false)
        {
            redirect('accounts/login');
            die();
        }

        $listing_id = $this->uri->segment(3);

        if ($listing_id == '0' || $listing_id == '')
        {

            if ($this->input->post('search') == 'Search Listings' && ($this->input->post('title') != ''))
            {
                $data = array();
                $data['listings'] = array();
                $c = 0;
                $query = $this->db->query('SELECT `story_title`, `story_id` FROM `stories` WHERE MATCH(`story_title`) AGAINST('.$this->db->escape('%'.$this->input->post('title', true).'%').') AND `story_approved` = \'1\' AND `is_update` = \'0\' LIMIT 0, 10');
                foreach ($query->result() as $listing)
                {
                    ++$c;
                    $data['listings'][$c] = array();
                    $data['listings'][$c]['title'] = $listing->story_title;
                    $data['listings'][$c]['update_url'] = site_url('contribute/update_listing/'.$listing->story_id.'/');
                }

                if ($c == 0)
                {
                    $data['none'] = true;
                } else {
                    $data['none'] = false;
                }

                $page_data['page_title'] = 'Update Listing - Search Results';
                $page_data['breadcrumbs'] = array('<a href="'.site_url('contribute').'">Contribute</a>', '<a href="'.site_url('contribute/update_listing').'">Update Listing</a>', 'Search Results');
                
                $this->load->view('header', $page_data);
                $this->load->view('contribute/existing_results', $data);
                $this->load->view('footer');
            } else {             

                $page_data['page_title'] = 'Update Listing';
                $page_data['breadcrumbs'] = array('<a href="'.site_url('contribute').'">Contribute</a>', 'Update Listing');
                              
                
                $this->load->view('header', $page_data);
                $this->load->view('contribute/existing');
                $this->load->view('footer');
            }
        } else {

            /* Start Listing Update Submission */

            $this->load->model('novels');
            $this->load->library('validation');

            $novel = $this->novels->get_novel($listing_id);

            if ($novel != false)
            {

                $rules['title']              = 'required';
                $rules['tagline']            = '';
                $rules['story_url']          = 'required|callback_valid_url';
                $rules['purchase_url']       = 'callback_valid_url';
                $rules['story_rss']          = 'callback_valid_url';
                $rules['author_pen']         = 'required';
                $rules['primary_genre']      = 'required|callback_check_genre[1]';
                $rules['update_schedule']    = 'callback_check_update';
                $rules['synopsis']           = 'min_length[120]';
                $rules['audience']           = '';
                $rules['coarse']             = 'numeric';
                $rules['sex']                = 'numeric';
                $rules['violent']            = 'numeric';
                $rules['author_hp']          = 'callback_valid_url';
                $rules['chapter_count']         = 'numeric';
                $this->validation->set_rules($rules);

                $fields['title']              = 'Title';
                $fields['tagline']            = 'Tagline';
                $fields['story_url']          = 'Read URL';
                $fields['purchase_url']       = 'Purchase URL';
                $fields['story_rss']          = 'RSS Feed URL';
                $fields['author_pen']         = 'Author\'s Name';
                $fields['author_hp']          = 'Author\'s Homepage';
                $fields['primary_genre']      = 'Primary Genre';
                $fields['secondary_genre']    = 'Secondary Genre';
                $fields['update_schedule']    = 'Update Schedule';
                $fields['coarse']             = 'Coarse Language';
                $fields['chapter_count']      = 'Chapter Count';
                $fields['sex']                = 'Sex Scenes';
                $fields['violent']            = 'Violence';
                $fields['synopsis']           = 'Synopsis';
                $fields['audience']           = 'Audience';

                $this->validation->set_fields($fields);

                if ($this->validation->run() == FALSE) {

                    $data = array();
                    $data['genres'] = $this->config->item('genres');
                    $data['id'] = $listing_id;
                    $data['update_schedules'] = $this->config->item('update_schedules');
                    $data['form'] = array();
                    $data['form']['title'] = $this->set_field_value('title', $novel['title']);
                    $data['form']['tagline'] = $this->set_field_value('tagline', $novel['subtitle']);
                    $data['form']['story_url'] = $this->set_field_value('story_url', $novel['url']);
                    $data['form']['story_rss'] = $this->set_field_value('story_rss', $novel['rss_feed']);
                    $data['form']['purchase_url'] = $this->set_field_value('purchase_url', $novel['purchase_link']);
                    $data['form']['author_pen'] = $this->set_field_value('author_pen', $novel['author_pen']);
                    $data['form']['author_hp'] = $this->set_field_value('author_hp', $novel['author_url']);
                    $data['form']['primary_genre'] = $this->set_field_value('primary_genre', $novel['primary_genre']);
                    $data['form']['secondary_genre'] = $this->set_field_value('secondary_genre', $novel['secondary_genre']);
                    $data['form']['update_schedule'] = $this->set_field_value('update_schedule', $novel['update_schedule']);
                    $data['form']['coarse'] = $this->set_field_value('coarse', $novel['coarse']);
                    $data['form']['sex'] = $this->set_field_value('sex', $novel['sex']);
                    $data['form']['chapter_count'] = $this->set_field_value('chapter_count', $novel['chapters']);
                    $data['form']['violent'] = $this->set_field_value('violent', $novel['violence']);
                    $data['form']['audience'] = $this->set_field_value('audience', $novel['audience']);
                    $data['form']['synopsis'] = $this->set_field_value('synopsis', (strip_tags($novel['summary']) != 'We currently don\'t have a synopsis for this novel. You could assist us by submitting a synopsis.') ? $novel['summary_raw'] : '');

                    $page_data['page_title'] = 'Update Listing - '.$data['form']['title'];
                    $page_data['breadcrumbs'] = array('<a href="'.site_url('contribute').'">Contribute</a>', '<a href="'.site_url('contribute/update_listing').'">Update Listing</a>', $data['form']['title']);
              
                    $this->load->view('header', $page_data);
                    $this->load->view('contribute/update_listing', $data);
                    $this->load->view('footer');

                } else {

                    $mature = 0;
                    $coarse = 0;
                    $sex = 0;
                    $violent = 0;
                    if ($this->input->post('coarse') == '2' || $this->input->post('coarse') == '3')
                    {
                        $mature = 1;
                        $coarse = intval($this->input->post('coarse'));
                    }
                    if ($this->input->post('sex') == '2' || $this->input->post('sex') == '3')
                    {
                        $mature = 1;
                        $sex = intval($this->input->post('sex'));
                    }
                    if ($this->input->post('violent') == '2' || $this->input->post('violent') == '3')
                    {
                        $mature = 1;
                        $violent = intval($this->input->post('violent'));
                    }


                    $db_insert = array(
                                                'story_title' => htmlspecialchars($this->input->post('title', true)),
                                                'story_subtitle' => htmlspecialchars($this->input->post('tagline', true)),
                                                'story_url' => $this->input->post('story_url', true),
                                                'story_rss' => $this->input->post('story_rss', true),
                                                'story_purchase_link' => $this->input->post('purchase_url', true),
                                                'story_author' => $this->input->post('author_pen', true),
                                                'story_author_url' => $this->input->post('author_hp', true),
                                                'story_primary_genre' => $this->input->post('primary_genre', true),
                                                'story_secondary_genre' => $this->input->post('secondary_genre', true),
                                                'story_update_schedule' => $this->input->post('update_schedule', true),
                                                'story_summary' => $this->input->post('synopsis', true),
                                                'story_mature' => $mature,
                                                'story_mature_coarse' => $coarse,
                                                'story_mature_sex' => $sex,
                                                'chapter_total' => intval($this->input->post('chapter_count')),
                                                'story_mature_violence' => $violent,
                                                'story_audience' => $this->input->post('audience', true)
                    );

                    $this->load->model(array('changes', 'contribution'));

                    $this->changes->book_id = $listing_id;
                    $this->changes->changeset_comment = $this->input->post('comment', true);
                    foreach ($db_insert as $field => $value)
                    {
                        $this->changes->change('stories', $field, $value);
                    }

                    $this->changes->commit();

                    $this->changes->user_id = 2;
                    $this->changes->book_id = $listing_id;
                    $this->changes->changeset_comment = 'Housekeeping. Name changed in previous commit. Regenerating index title.';
                    $this->changes->change('stories', 'story_index_title', $this->create_sort_title($this->input->post('title', true)));
                    $this->changes->commit();

                    $this->contribution->add_points($this->users->cur_user['user_id'], "Listing edited", 2.5, "listing", $listing_id);

                    $this->load->view('header');
                    $this->load->view('contribute/update_listing_success');
                    $this->load->view('footer');

                }


            } else {
                show_404();
            }


        }
    }

    function reject_listing()
    {
    
        if ($this->users->logged_in == false || $this->users->cur_user['access_level'] <= 6)
        {
            show_404();        
        }
        
        $listing_id = $this->uri->segment(3);
        
        $this->db->where('story_id', $listing_id);
        $this->db->update('stories', array('story_approved' => '-1'));
 
        $this->load->view('header');        
        $this->load->view('contribute/reject_listing_message');
        $this->load->view('footer');                
    
    }

    function approve_listing()
    {
    
        if ($this->users->logged_in == false || $this->users->cur_user['access_level'] <= 6)
        {
            show_404();        
        }
        
        $listing_id = $this->uri->segment(3);
        
        $this->db->where('story_id', $listing_id);
        $this->db->update('stories', array('story_approved' => '1'));

        $this->load->model(array("contribution", "novels"));

        $listing = $this->novels->get_novel($listing_id, true);

        $this->contribution->add_points($listing['submit_user'], "Added novel.", ((strlen($listing['summary']) > 5) ? 10 : 5), "web_fiction", $listing_id);
 
        $this->load->view('header');        
        $this->load->view('contribute/approve_listing_message');
        $this->load->view('footer');                
    
    }

    function top_contributors()
    {
    
        $this->db->order_by("contrib_points", "DESC");
        $users = $this->db->get_where("users", "user_id != 1 AND user_id != 2 AND user_id != 3", 50);
    
        $data = array(
            "contributors" => $users->result_array()
        );
    
        $page_data = array();
        $page_data['page_title'] = 'Top Contributors';
        $page_data['breadcrumbs'] = array('<a href="'.site_url('contribute').'">Contribute</a>', 'Top Contributors');
        
        $this->load->view('header', $page_data);
        $this->load->view('contribute/top_contributors', $data);
        $this->load->view('footer');
    
    }

    function link_request()
    {
        if ($this->users->logged_in == false)
        {
            redirect('accounts/login');
            die();
        }
        $this->load->library('validation');
        $rules['url'] = 'required|max_length[100]|callback_valid_listing_id';
        $fields['url'] = 'Listing URL';
        $this->validation->set_rules($rules);
        $this->validation->set_fields($fields);
        $this->validation->set_error_delimiters('<div class="error_alert">', '</div>');

        if ($this->validation->run() == FALSE) {
            $this->load->view('header');
            $this->load->view('contribute/link_request');
            $this->load->view('footer');
        } else {
            $data = array(
                                        'message_subject' => 'Linkage Request',
                                        'message_body' => $this->input->post('url'),
                                        'message_to' => 1,
                                        'message_from' =>  $this->users->cur_user['user_id'],
                                        'message_date' => time()
            );
            $this->db->insert('pms', $data);
            $this->load->view('header');
            $this->load->view('contribute/request_success');
            $this->load->view('footer');

        }

    }


}
