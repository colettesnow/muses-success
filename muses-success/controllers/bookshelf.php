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
                                $data['currently_reading'] = $this->library_get('current', $user_id);
                                $data['completed_reading'] = $this->library_get('complete', $user_id);
                                $data['plantoread'] = $this->library_get('planned', $user_id);
                                $data['onhold'] = $this->library_get('onhold', $user_id);
                                $data['dropped'] = $this->library_get('dropped', $user_id);
                                $this->load->view('library/bookshelf_all', $data);
                        } elseif ($tab == 'current')
                        {
                                $data['currently_reading'] = $this->library_get('current', $user_id);
                                $this->load->view('library/bookshelf_current', $data);
                        } elseif ($tab == 'complete')
                        {
                                $data['completed_reading'] = $this->library_get('complete', $user_id);
                                $this->load->view('library/bookshelf_complete', $data);
                        } elseif ($tab == 'plantoread')
                        {
                                $data['plantoread'] = $this->library_get('planned', $user_id);
                                $this->load->view('library/bookshelf_planned', $data);
                        } elseif ($tab == 'onhold')
                        {
                                $data['onhold'] = $this->library_get('onhold', $user_id);
                                $this->load->view('library/bookshelf_onhold', $data);
                        } elseif ($tab == 'dropped')
                        {
                                $data['dropped'] = $this->library_get('dropped', $user_id);
                                $this->load->view('library/bookshelf_dropped', $data);
                        }

                
                } else {

                        $this->load->view('header', array('page_title' => 'Bookshelf', 'breadcrumbs' => array($user_info['screen_name'].'\'s Profile', 'Bookshelf')));
                        $this->load->view('page_template', array('page_title' => 'Not Found', 'content' => 'This bookshelf is unused.'));

                }

                $this->load->view('footer');
        
        }

        function library_get($type, $id)
        {
                $library = array();

                $this->load->model('novels');

                $i = 0;
                $query = $this->db->query('SELECT * FROM `library` WHERE `book_status` = \''.$type.'\' AND `library_user` = \''.$id.'\'');
                foreach ($query->result() as $item)
                {
                        ++$i;
                        $library[$i] = array();
                        $library[$i]['id'] = $item->book_id;
                        $novel = $this->novels->get_novel($item->book_id);
                        if ($novel['chapters'] == 0 || $novel['chapters'] == '')
                        {
                                $library[$i]['total_chapters'] = '??';
                        } else {
                                $library[$i]['total_chapters'] = round($novel['chapters']);
                        }
                        if ($item->chapters_read == 0 || $item->chapters_read == '')
                        {
                                $library[$i]['chapter_count'] = '??';
                        } else {
                                $library[$i]['chapter_count'] = round($item->chapters_read);
                        }
                        $library[$i]['novel'] = '<a href="'.$novel['listing_url'].'">'.$novel['title'].'</a> by '.$novel['author_pen'].'';
                        $library[$i]['rating'] = $item->book_rating;
                }

                return $library;
        }
}


