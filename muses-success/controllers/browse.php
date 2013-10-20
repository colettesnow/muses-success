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
class Browse extends Controller {

        function Browse() {
                parent::Controller();
                
                $this->config->load('web_fiction');
        }


        function index()
        {
                redirect('browse/listing/0/all/all/');
        }

        function add_library()
        {

                $this->load->view('header');

                $this->load->library('validation');
                $this->load->model('novels');
                
                if ($this->users->logged_in == true)
                {
                
                        $rules['status'] = 'required|min_length[1]|max_length[1]|callback_valid_status';
                        $rules['score'] = 'required|min_length[1]|max_length[2]|callback_valid_rating';
                        $rules['listing_id'] = 'required|callback_valid_listing';

                        $this->validation->set_rules($rules);

                        $fields['status'] = 'Status';
                        $fields['score'] = 'Rating';
                        $fields['listing_id'] = 'Listing ID';
                        
                        $this->validation->set_fields($fields);
                        
                        if ($this->validation->run() == FALSE)
                        {

                                $this->load->view('library/add_failure');

                        } else {

                                switch ($this->input->post('status')) {
                                        case 1:
                                                $book_status = 'current';
                                                $book_nice = 'Reading';
                                                break;
                                        case 2:
                                                $book_status = 'planned';
                                                $book_nice = 'Planned';
                                                break;
                                        case 3:
                                                $book_status = 'onhold';
                                                $book_nice = 'On-Hold';
                                                break;
                                        case 4:
                                                $book_status = 'complete';
                                                $book_nice = 'Completed';
                                                break;
                                        case 5:
                                                $book_status = 'dropped';
                                                $book_nice = 'Dropped';
                                                break;
                                }

                                $data['book_status'] = $book_status;
                                $data['library_user'] = $this->users->cur_user['user_id'];
                                $data['book_rating'] = intval($this->input->post('score'));
                                $data['chapters_read'] = intval($this->input->post('progress'));
                                $data['book_id'] = intval($this->input->post('listing_id'));
                                
                                $pd = array();
                                $book_data = $this->novels->get_novel(intval($this->input->post('listing_id')));
                                $pd['book_title'] = '<a href="'.$book_data['listing_url'].'">'.$book_data['title'].'</a>';
                                $pd['url'] = $book_data['listing_url'];

                                if ($this->is_already_in_library(intval($this->input->post('listing_id'))) == true)
                                {

                                        $library_already = $this->db->query('SELECT `chapters_read` FROM `library` WHERE `library_user` = \''.$this->users->cur_user['user_id'].'\' AND `book_id` = \''.intval($this->input->post('listing_id')).'\' LIMIT 1');
                                        $liball = $library_already->row();

                                        $this->db->where(array('library_user' => $this->users->cur_user['user_id'], 'book_id' => intval($this->input->post('listing_id'))));
                                        $this->db->update('library', $data);
                                        
                                        if ($this->rated_already($data['book_id']) == true && $data['book_rating'] != '0')
                                        {
                                                $this->change_rating($data['book_id'], $data['book_rating']);
                                        } elseif ($data['book_rating'] != '0') {
                                                $this->new_rating($data['book_id'], $data['book_rating']);
                                        }
                                        
                                        if ($liball->chapters_read != $data['chapters_read'])
                                        {
                                        
                                                if ($data['chapters_read'] > $liball->chapters_read)
                                                {
                                                
                                                        $update = array();
                                                        $update['update_text'] = $book_nice.' - '.(($data['chapters_read'] != '' || $data['chapters_read'] != 0) ? $data['chapters_read'] : '??').' of '.(($book_data['chapters'] != '' || $book_data['chapters'] != 0) ? $book_data['chapters'] : '??').' Chapters';
                                                        $update['update_title'] = $book_data['title'];
                                                        $update['update_link'] = $book_data['listing_url'];
                                                        $update['update_date'] = time();
                                                        $update['update_rel_id'] = $book_data['id'];
                                                        $update['update_type'] = 1;
                                                        $update['user_id'] = $this->users->cur_user['user_id'];
                                                        
                                                        $this->db->insert('updates', $update);
                                                        
                                                }
                                        
                                        }
                                        
                                        
                                        $this->load->view('library/update_success', $pd);
                                } else {
                                        $this->db->insert('library', $data);
                                        if ($this->rated_already($data['book_id']) == true && $data['book_rating'] != '0')
                                        {
                                                $this->change_rating($data['book_id'], $data['book_rating']);
                                        } elseif ($data['book_rating'] != '0') {
                                                $this->new_rating($data['book_id'], $data['book_rating']);
                                        }
                                        
                                        $update = array();
                                        $update['update_text'] = $book_nice.' - '.(($data['chapters_read'] != '' || $data['chapters_read'] != 0) ? $data['chapters_read'] : '??').' of '.(($book_data['chapters'] != '' || $book_data['chapters'] != 0) ? $book_data['chapters'] : '??').' Chapters';
                                        $update['update_title'] = $book_data['title'];
                                        $update['update_link'] = $book_data['listing_url'];
                                        $update['update_date'] = time();
                                        $update['update_rel_id'] = $book_data['id'];
                                        $update['update_type'] = 1;
                                        $update['user_id'] = $this->users->cur_user['user_id'];
                                        
                                        $this->db->insert('updates', $update);
                                        
                                        $this->load->view('library/add_success', $pd);
                                }

                        }
                
                }

                $this->load->view('footer');
        }
        
        function rated_already($novel_id)
        {
                $query = $this->db->get_where('ratings', array('user_id' => $this->users->cur_user['user_id'], 'story_id' => $novel_id));
                if ($query->num_rows() == 1)
                {
                        return true;
                } else {
                        return false;
                }
        }
        
        function change_rating($novel_id, $new_rating)
        {
                $data = array();
                $data['rated'] = round($new_rating, 0);
                $data['ip_address'] = $this->input->ip_address();                
                $where = array();
                $where['user_id'] = $this->users->cur_user['user_id'];
                $where['story_id'] = $novel_id;
                $this->db->where($where);
                $this->db->update('ratings', $data);
                $this->calculate_average_rating(intval($novel_id));
                return true;
        }

        function new_rating($novel_id, $new_rating)
        {
                $data = array();
                $data['rated'] = round($new_rating, 0);
                $data['user_id'] = $this->users->cur_user['user_id'];
                $data['story_id'] = $novel_id;
                $data['ip_address'] = $this->input->ip_address();
                $this->db->insert('ratings', $data);
                $this->calculate_average_rating(intval($novel_id));
                return true;
        }
        
        function valid_listing($id)
        {
                $this->load->model('novels');
                
                if ($this->novels->novel_exists_id($id))
                {
                        return true;
                } else {
                        $this->validation->set_message('valid_listing', 'You don\'t seem to have supplied a valid listing ID.');
                        return false;
                }
        }
        
        function valid_status($id)
        {
                if ($id >= 1 && $id <=5)
                        return true;
                else {
                        $this->validation->set_message('valid_status', 'In order to add a title to your bookshelf, you must select a valid status. Please return to the previous page, and select a status.');
                        return false;
                }
        }

        function valid_rating($id)
        {
                if ($id >= 0 && $id <=10)
                        return true;
                else {
                        $this->validation->set_message('valid_rating', 'Your rating must be between 1 and 10. Please return to the previous page, and select a rating.');
                        return false;
                        }
        }

        function is_already_in_library($id)
        {
                $query = $this->db->get_where('library', array('book_id' => intval($id), 'library_user' => $this->users->cur_user['user_id']));
                $in_library = $query->num_rows();
                if ($in_library == 1)
                {
                        return true;
                } else {
                        return false;
                }
                
        }
        
        function createlist()
        {
        
                if (count($_POST) != 0 && isset($_POST['sort']) && isset($_POST['genre']) && isset($_POST['letter']))
                {
                        $build_url = 'browse/listing/';
                        if ($_POST['sort'] >= '1' && $_POST['sort'] <= 5)
                        {
                                $build_url .= intval($_POST['sort']).'/';
                        } else {
                                $build_url .= '1/';
                        }
                        if (in_array($_POST['genre'], $this->config->item('genres')))
                        {
                                $build_url .= $_POST['genre'].'/';
                        } else {
                                $build_url .= 'all/';
                        }
                        $letters = array('A','B','C','D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
                        if (in_array($_POST['letter'], $letters))
                        {
                                $build_url .= $_POST['letter'].'/';
                        } else {
                                $build_url .= 'all/';
                        }
                        
                        if (isset($_POST['show_thumbnails']) && $_POST['show_thumbnails'] == 1)
                        {
                                $build_url .= 'thumbnails/';
                        }

                        redirect($build_url);
                } else {
                        redirect('browse/');
                }
        
        }
        
        function listing()
        {
                $this->load->model(array('novels', 'thumbnails'));

                $sort = $this->uri->segment(3);
                $genre = $this->uri->segment(4);
                $letter = $this->uri->segment(5);
                $thumbnails = $this->uri->segment(6);

                if ($sort >= '1' && $sort <= 5)
                {
                        $sort = $sort;
                } else {
                        $sort = 1;
                }
                if (in_array($genre, $this->config->item('genres')))
                {
                        $genre = $genre;
                } else {
                        $genre = 'all';
                }
                $letters = array('A','B','C','D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
                if (in_array($letter, $letters))
                {
                        $letter = $letter;
                } else {
                        $letter = 'all';
                }

                $this->load->library('pagination');

                if ($this->uri->segment(7) != '' || $this->uri->segment(7) != '1')
                {

                        $current_offset = ($this->uri->segment(7));

                }

                $novels = $this->novels->create_list($sort, $genre, $letter, '25', $current_offset);
                $config['first_link'] = 'First';
                $config['last_link'] = 'Last';
                $config['num_links'] = 10;
                $config['uri_segment'] = 7;
                $config['page_query_string'] = FALSE;
                $config['base_url'] = site_url('browse/listing/'.$sort.'/'.$genre.'/'.$letter.'/page/');
                $config['total_rows'] = $novels['num_novels'];
                $config['per_page'] = '25';

                $this->pagination->initialize($config);

                $data = array('novels' => $novels['novels']);
                $data['genres'] = $this->config->item('genres');
                $data['letters']= $letters;
                $data['view_info'] = array(
                        'genre'  => $genre,
                        'sort'  => $sort,
                        'letter' => $letter
                        );

                $head_data = array();
                // Navigate Next / Previous Links
                if ($config['total_rows'] > $current_offset + 25)
                {
                    if ($current_offset != 0)
                    {
                        $previous_page_number = intval($current_offset) - $config['per_page'];
                        if ($previous_page_number == 0)
                        {
                            $head_data["link_nav_previous"] = str_replace("page", "", $config['base_url']);
                        } else {
                            $head_data["link_nav_previous"] = $config['base_url'].'/'.$previous_page_number; 
                        }                           
                    }
                    $next_page_number = intval($current_offset) + $config['per_page'];
                    $head_data["link_nav_next"] = $config['base_url'].'/'.$next_page_number;    
                }


                $data['page_links'] = $this->pagination->create_links();

                if ($thumbnails == 'thumbnails')
                {
                        $data['show_thumbnails'] = true;
                } else {
                        $data['show_thumbnails'] = false;
                }
                
                $head_data['page_title'] = '';

                // Add Page Number to Page Title
                if ($current_offset != 0)
                {
                    $current_page_number = ($current_offset/25)+1;
                    $head_data['page_title'] = 'Page '.$current_page_number.' - ';
                }

                $head_data['breadcrumbs'] = array();
                $head_data['breadcrumbs'][] = '<a href="'.site_url('browse').'">Web Fiction Listings</a>'; 
                if ($sort == 1) {
                	$head_data['page_title'] .= 'Highest Rated (Most Popular) - ';                	
                } elseif ($sort == 2)
                {
                	$head_data['page_title'] .= 'Lowest Rated (Least Popular) - ';                   	                	
                } elseif ($sort == 3)
                {
                	$head_data['page_title'] .= 'Most Reviewed - ';                   	
                } elseif ($sort == 4)
                {
                	$head_data['page_title'] .= 'Least Reviewed - ';                   	
                }
                
                if ($letter != 'all' && $genre == 'all')
                {
                	$head_data['page_title'] .= 'Web Fiction Starting with '.$letter.' - ';
                	$head_data['breadcrumbs'][] = '<a href="'.site_url('browse/listing/'.$sort.'/all/'.$letter.'').'">Letter: '.$letter.'</a>';                	
                } elseif ($letter == 'all' && $genre != 'all')
                {
                	$head_data['page_title'] .= $genre.' Web Fiction - ';
                	$head_data['breadcrumbs'][] = '<a href="'.site_url('browse/listing/'.$sort.'/'.$genre.'/all').'">Genre: '.$genre.'</a>';                	
                } elseif ($letter != 'all' && $genre != 'all')
                {
                	$head_data['page_title'] .= $genre.' Web Fiction Starting with '.$letter.' - '; 
                	$head_data['breadcrumbs'][] = '<a href="'.site_url('browse/listing/'.$sort.'/'.$genre.'/all').'">Genre: '.$genre.'</a>'; 
                	$head_data['breadcrumbs'][] = '<a href="'.site_url('browse/listing/'.$sort.'/all/'.$letter.'').'">Letter: '.$letter.'</a>';                 	
                }

                if ($sort == 1) {
                	$head_data['breadcrumbs'][] = 'Highest Rated (Most Popular)';                	
                } elseif ($sort == 2)
                {
                	$head_data['breadcrumbs'][] = 'Lowest Rated (Least Popular)';                   	                	
                } elseif ($sort == 3)
                {
                	$head_data['breadcrumbs'][] = 'Most Reviewed';                   	
                } elseif ($sort == 4)
                {
                	$head_data['breadcrumbs'][] = 'Least Reviewed';                   	
                }                
                
                $head_data['page_title'] .= 'Browse Web Fiction Listings - Web Novels and Serials';
                
                $this->load->view('header', $head_data);
                $this->load->view('browse/index', $data);
                $this->load->view('browse/list_view', $data);
                $this->load->view('footer');
        
        }
        
        function rate()
        {
                $this->load->library('user_agent');

                if ($this->agent->is_robot() == false)
                {

                $item_id = $this->input->post("listing_id");
                $this->load->model('novels');
                $novel = $this->novels->get_novel(intval($item_id));
                $this->output->set_header('Content-Type: text/plain');
                
                $rating = $this->input->post('value');
                
                if ($novel != false)
                {
                if ($rating == '0' || $rating == '1' || $rating == '2' || $rating == '3' || $rating == '4' || $rating == '5' || $rating == '6' || $rating == '7' || $rating == '8' || $rating == '9' || $rating == '10')
                {
                if ($this->users->logged_in == false)
                {
                        $rating_check = $this->db->get_where('ratings', array('user_id' => 0, 'story_id' => intval($item_id), 'ip_address' => $this->input->ip_address()), 1);
                        if ($rating_check->num_rows() == 0)
                        {
                                if ($rating != 0)
                                {
                                    $rating_error = 2;
                                    $rating = array('user_id' => 0, 'story_id' => intval($item_id), 'ip_address' => $this->input->ip_address(), 'rated' => round($rating));
                                    $this->db->insert('ratings', $rating);
                                    $this->calculate_average_rating(intval($item_id));
                                    $this->output->set_output('Thank you for rating!');
                                }
                        } else {
                                if ($rating != 0)
                                {
                                    $rating_error = 2;
                                    $rating = array('user_id' => 0, 'story_id' => intval($item_id), 'ip_address' => $this->input->ip_address(), 'rated' => round($rating));
                                    $this->db->where(array('user_id' => 0, 'story_id' => intval($item_id), 'ip_address' => $this->input->ip_address()));
                                    $this->db->update('ratings', $rating);
                                    $this->calculate_average_rating(intval($item_id));
                                    $this->output->set_output('Thanks! Your previous rating has been updated.');
                                } else {
                                    $this->db->where(array('user_id' => 0, 'story_id' => intval($item_id), 'ip_address' => $this->input->ip_address()));
                                    $this->db->delete('ratings');
                                    $this->output->set_output('Rating cancelled successfully.');
                                }
                       }
                } else {
                        $rating_check = $this->db->get_where('ratings', array('user_id' => $this->users->cur_user['user_id'], 'story_id' => intval($item_id)), 1);
                        if ($rating_check->num_rows() == 0)
                        {
                                $rating_error = 2;
                                $rating = array('user_id' => $this->users->cur_user['user_id'], 'story_id' => intval($item_id), 'ip_address' => $this->input->ip_address(), 'rated' => round($rating));
                                $this->db->insert('ratings', $rating);
                                $this->calculate_average_rating(intval($item_id));
                                $this->output->set_output('Thank you for rating!');
                        } else {
                                if ($rating != 0)
                                {
                                    $rating_error = 2;
                                    $rating = array('user_id' => $this->users->cur_user['user_id'], 'story_id' => intval($item_id), 'ip_address' => $this->input->ip_address(), 'rated' => round($rating));
                                    $this->db->where(array('user_id' => 0, 'story_id' => intval($item_id), 'ip_address' => $this->input->ip_address()));
                                    $this->db->update('ratings', $rating);
                                    $this->calculate_average_rating(intval($item_id));
                                    $this->output->set_output('Thanks! Your previous rating has been updated.');
                                } else {
                                    $this->db->where(array('user_id' => $this->users->cur_user['user_id'], 'story_id' => intval($item_id)));
                                    $this->db->delete('ratings');
                                    $this->output->set_output('Rating cancelled successfully.');
                                }    
                        }
                }
                } else {
                        $this->output->set_output('You have submitted an invalid rating.');
                }
                } else {
                        $this->output->set_output('You can\'t rate something that does not exist.');
                }
                } else {
                        $this->output->set_output('You must be human in order to rate a listing. You appear to be a robot.');
                }
                
        }
        
        
        function calculate_average_rating($story_id)
        {
                $this->db->select('rated');
                $this->db->where('story_id', intval($story_id));
                $this->db->from('ratings');
                $query = $this->db->get();
                $sum = 0;
                $num = 0;
                foreach ($query->result() as $row)
                {
                        ++$num;
                        $sum = ($sum+$row->rated);
                }
                $rating = $sum / $num;
                $this->db->where('story_id', $story_id);
                $this->db->update('stories', array('story_average_rating' => $rating, 'story_rating_count' => $num));
        }

}

?>