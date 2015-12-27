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
class Bookshelf extends Controller {
    
    function Bookshelf() {
        parent::Controller();
    }
    
    function index()
    {
        $this->load->model('bookshelves');
        
        $user_id = intval($this->uri->segment(2));
        
        $user_info = $this->users->get_user_info(intval($user_id));
        $tab = $this->uri->segment(3);
        
        if ($user_info != false)
        {
            $pt_data = array();
            $pt_data['page_title'] = (($user_info['display_name'] != '') ? $user_info['display_name'] : $user_info['screen_name']).'\'s Bookshelf';
            $pt_data['breadcrumbs'] = array('<a href="'.site_url('profile/view/'.$user_info['user_id'].'').'">'.$user_info['screen_name'].'\'s Profile</a>', 'Bookshelf');
            $this->load->view('header', $pt_data);
            
            $data['page_title'] = (($user_info['display_name'] != '') ? $user_info['display_name'] : $user_info['screen_name']).'\'s Bookshelf';
            $data['shelf_id'] = $user_info['user_id'];
            
            $data['user'] = $user_info;
            
            if ($tab == '' || $tab == 'all')
            {
                $data['currently_reading'] = $this->bookshelves->get_bookshelf($user_id, 'current');
                $data['completed_reading'] = $this->bookshelves->get_bookshelf($user_id, 'complete');
                $data['plantoread'] = $this->bookshelves->get_bookshelf($user_id, 'planned');
                $data['onhold'] = $this->bookshelves->get_bookshelf($user_id, 'onhold');
                $data['dropped'] = $this->bookshelves->get_bookshelf($user_id, 'dropped');
                $this->load->view('library/bookshelf_all', $data);
            } elseif ($tab == 'current')
            {
                $data['currently_reading'] = $this->bookshelves->get_bookshelf($user_id, 'current');
                $this->load->view('library/bookshelf_current', $data);
            } elseif ($tab == 'complete')
            {
                $data['completed_reading'] = $this->bookshelves->get_bookshelf($user_id, 'complete');
                $this->load->view('library/bookshelf_complete', $data);
            } elseif ($tab == 'plantoread')
            {
                $data['plantoread'] = $this->bookshelves->get_bookshelf($user_id, 'planned');
                $this->load->view('library/bookshelf_planned', $data);
            } elseif ($tab == 'onhold')
            {
                $data['onhold'] = $this->bookshelves->get_bookshelf($user_id, 'onhold');
                $this->load->view('library/bookshelf_onhold', $data);
            } elseif ($tab == 'dropped')
            {
                $data['dropped'] =  $this->bookshelves->get_bookshelf($user_id, 'dropped');
                $this->load->view('library/bookshelf_dropped', $data);
            }
            
            
        } else {
            
            $this->load->view('header', array('page_title' => 'Bookshelf', 'breadcrumbs' => array($user_info['screen_name'].'\'s Profile', 'Bookshelf')));
            $this->load->view('page_template', array('page_title' => 'Not Found', 'content' => 'This bookshelf is unused.'));
            
        }
        
        $this->load->view('footer');
        
    }
    
    function user_bookshelf()
    {
        if ($this->users->logged_in == false)
            redirect('accounts/login');
        
        $this->load->model("bookshelves");
        
        $pt = array('page_title' => 'My Bookshelf - My Account', 'breadcrumbs' => array('<a href="'.site_url('accounts').'">My Account</a>', 'My Bookshelf'), 'bookshelf_js' => true);
        $pt['use_javascript'] = true;
        $pt['javascript'] = array('bookshelf.js');
        
        if ($this->input->post('delete') == 'Delete')
        {
            $i = 0;
            if ($this->input->post('reading') != '')
            {
                
                foreach ($this->input->post('reading') as $book_id)
                {
                    $this->db->delete('library', array('book_id' => intval($book_id), 'library_user' => $this->users->cur_user['user_id']));
                    ++$i;
                }
            }
            
            if ($i == 1)
            {
                $pt['alert'] = 'Success! The selected title has been removed from your bookshelf.';
            } elseif ($i == 0) {
                $pt['alert'] = 'You did not select a title to remove from your bookshelf.';
            } else {
                $pt['alert'] = 'Success! All '.count($this->input->post('reading')).' selected title\'s have been removed from your bookshelf.';
            }
        }
        
        if ($this->input->post('change') == 'Change Status' && ($this->input->post('status') >= 1 && $this->input->post('status') <=5))
        {
            
            switch ($this->input->post('status')) {
                case 1:
                $book_status = 'current';
                $status_nice = 'Currently Reading';
                break;
                case 2:
                $book_status = 'planned';
                $status_nice = 'Plan to Read';
                break;
                case 3:
                $book_status = 'onhold';
                $status_nice = 'On-Hold';
                break;
                case 4:
                $book_status = 'complete';
                $status_nice = 'Completed Reading';
                break;
                case 5:
                $book_status = 'dropped';
                $status_nice = 'Dropped';
                break;
            }
            $i = 0;
            if ($this->input->post('reading') != '')
            {
                
                
                foreach ($this->input->post('reading') as $book_id)
                {
                    ++$i;
                    $this->db->where(array('book_id' => intval($book_id), 'library_user' => $this->users->cur_user['user_id']));
                    $this->db->update('library', array('book_status' => $book_status));
                }
                
            }
            
            if ($i == 1)
            {
                $pt['alert'] = '<strong>Success!</strong> The selected title has had its status changed to '.$status_nice.'.';
            } elseif ($i == 0) {
                $pt['alert'] = 'You did not select a title to change the status of.';
            } else {
                $pt['alert'] = '<strong>Success!</strong> All '.count($this->input->post('reading')).' selected title\'s have had their status changed to '.$status_nice.'.';
            }
        }
        
        $data = array();
        $data['reading_current'] = $this->bookshelves->get_bookshelf($this->users->cur_user['user_id'], 'current');
        $data['reading_planned'] = $this->bookshelves->get_bookshelf($this->users->cur_user['user_id'], 'planned');
        $data['reading_onhold'] =  $this->bookshelves->get_bookshelf($this->users->cur_user['user_id'], 'onhold');
        $data['reading_complete'] =  $this->bookshelves->get_bookshelf($this->users->cur_user['user_id'], 'complete');
        $data['reading_dropped'] =  $this->bookshelves->get_bookshelf($this->users->cur_user['user_id'], 'dropped');
        
        $this->load->view('header', $pt);
        $this->load->view('accounts/library', $data);
        $this->load->view('footer');
        
        
    }
    
    function add_chapter()
    {
        if ($this->users->logged_in == true)
        {
            $book_id = intval($this->input->post('bookID'));
            
            $this->load->model(array('bookshelves', 'novels'));
            
            $book = $this->novels->get_novel($book_id);
            
            $shelf_item = $this->bookshelves->get_bookshelf_item($book_id, $this->users->cur_user['user_id']);
            if ($shelf_item != false && $book != false)
            {
                
                $chapters_read = $this->bookshelves->increment_read_chapter_count($book_id);
                               
                switch ($shelf_item['status']) {
                    case "current":
                    $status = 'Reading';
                    break;
                    case "onhold":
                    $status = "On-Hold";
                    break;
                    case "planned":
                    $status = "Plan to Read";
                    break;
                    case "complete":
                    $status = "Completed Reading";
                    break;
                    case "dropped":
                    $status = "Dropped";
                    break;
                }
                
                $status_text = $status.' - '.$chapters_read.' of '.(($book['chapter_count'] != 0) ? $book['chapter_count'] : '??').' Chapters';
                
                $this->bookshelves->update_shelf_timeline($book_id, $this->users->cur_user['user_id'], $book['title'], $status_text, site_url($book['slug']));
                
            }
        }
    }
    
    function minus_chapter()
    {
        if ($this->users->logged_in == true)
        {
            $book_id = intval($this->input->post('bookID'));
            
            $this->load->model(array("bookshelves", "novels"));
            
            $book = $this->novels->get_novel($book_id);
            
            $shelf_item = $this->bookshelves->get_bookshelf_item($book_id, $this->users->cur_user['user_id']);
            if ($shelf_item != false && $book != false)
            {
                
                $chapters_read = $this->bookshelves->decrement_read_chapter_count($book_id);
                               
                switch ($shelf_item['status']) {
                    case "current":
                    $status = 'Reading';
                    break;
                    case "onhold":
                    $status = "On-Hold";
                    break;
                    case "planned":
                    $status = "Plan to Read";
                    break;
                    case "complete":
                    $status = "Completed Reading";
                    break;
                    case "dropped":
                    $status = "Dropped";
                    break;
                }
                
                $status_text = $status.' - '.$chapters_read.' of '.(($book['chapter_count'] != 0) ? $book['chapter_count'] : '??').' Chapters';
                
                $this->bookshelves->update_shelf_timeline($book_id, $this->users->cur_user['user_id'], $book['title'], $status_text, site_url($book['slug']));
                
            }
        }
    }
    
}