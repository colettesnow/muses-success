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
class Main extends Controller {

        function Main() {
                parent::Controller();
        }

        function index()
        {

                $this->load->model(array('novels', 'reviews_model', 'recs', 'thumbnails', 'bookshelves'));
                $this->load->helper(array('text', 'date', 'date_relative'));

                $hp_data = $this->novels->homepage_featured_fiction();

                $data = array();

                $data['reviews'] = $this->reviews_model->get_reviews('recent', 0, 2);
                $data['newest_novels'] = $this->novels->list_novels('newest');
                // $data['popular_novels'] = $this->novels->list_novels('highest_rated', '', '', 9, false, true);
                // $data['undiscovered_novels'] = $this->novels->list_novels('undiscovered', '', '', 4, false, true);
                $data['recommendations'] = $this->recs->recent(2);
                $data['currently_reading'] = $this->bookshelves->get_bookshelf();

                $query = $this->db->query('SELECT `changeset_date` FROM `changesets` ORDER BY `changeset_date` DESC LIMIT 1');
                $data['last_updated'] = ($query->num_rows == 1) ? date_relative($query->row()->changeset_date) : 'Never';

                $data['stats'] = array();
                $query = $this->db->query('SELECT COUNT(`story_id`) AS `num_stories` FROM `stories` WHERE `story_approved` = \'1\' AND `is_update` = \'0\' LIMIT 1');
                $data['stats']['total_novels'] = $query->row()->num_stories;
                $query = $this->db->query('SELECT COUNT(`review_id`) AS `num_reviews` FROM `reviews` WHERE `is_update` = \'0\' LIMIT 1');
                $data['stats']['total_reviews'] = $query->row()->num_reviews;
                $query = $this->db->query('SELECT COUNT(`rating_id`) AS `num_ratings` FROM `ratings` LIMIT 1');
                $data['stats']['total_ratings'] = $query->row()->num_ratings;
                $query = $this->db->query('SELECT COUNT(`user_id`) AS `num_users` FROM `users` LIMIT 1');
                $data['stats']['total_users'] = $query->row()->num_users;
                if ($query->row()->num_users != 0)
                {
                    $query = $this->db->query('SELECT `user_id`, `screen_name` FROM `users` ORDER BY `user_id` DESC LIMIT 1');
                    $data['stats']['newest_member'] = '<a href="'.site_url('profile/view/'.$query->row()->user_id.'/').'">'.$query->row()->screen_name.'</a>';
                } else {
                    $data['stats']['newest_member'] = 'No one';
                }

                $header = array();
				if ($this->uri->segment(1) == 'login-success')
				{
					$header['alert'] = 'You have logged in successfully.';
				}
				$header['page_title'] = 'Web Fiction Wiki - Web Novel and Serials';
                $header['use_javascript'] = true;
                $header['javascript'] = array('bookshelf.js');

                $this->load->view('header', $header);
                $this->load->view('homepage', $data);
                $this->load->view('footer');
        }
}

/* End of file main.php */
/* Location: ./system/application/controllers/main.php */
