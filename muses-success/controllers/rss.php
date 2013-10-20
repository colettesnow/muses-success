<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class RSS extends Controller {

        function RSS() {
                parent::Controller();
        }

        function index()
        {
                $this->output->cache(180);

                $this->load->model('novels');

                $data = array('newest' => $this->novels->list_novels('newest'));

                $this->output->set_header('Content-Type: application/rss+xml;');
                $this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', $data['newest'][1]['added']).' GMT');

                $this->load->view('rss/newest.php', $data);
        }
        
        function reviews()
        {
                /* $this->output->cache(180);
                */
                $this->load->model('novels');

                $data = array('reviews' => $this->novels->list_latest_reviews());

                $this->output->set_header('Content-Type: application/rss+xml;');
                $this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', $data['reviews'][1]['date']).' GMT');
                
                $this->load->view('rss/reviews.php', $data);
        }
 
        function listing()
        {
                $this->load->model('novels');

                $novel_id = $this->uri->segment(3);

                if ($this->novels->novel_exists($novel_id) == true)
                {

$novel = $this->novels->get_novel($novel_id);                       
$data = array('novel' => $novel, 'reviews' => $this->novels->get_reviews($novel['id']));

                        $this->output->cache(180);

                        $this->load->view('rss/reviews_idv.php', $data);
                        
                } else {
                        error_404();
                }
        }
       
        function novel()
        {
        
        }
        
        function user()
        {
        
        }
        
        function bookshelf()
        {
                $this->output->set_header('Content-Type: application/rss+xml;');

                $user_id = $this->uri->segment(3);
                
                $check_user = $this->db->get_where('users', array('user_id' => intval($user_id)));

                if ($check_user->num_rows() == 1)
                {

                        $this->db->order_by('update_date', 'DESC');
                        $updates = $this->db->get_where('updates', array('user_id' => intval($user_id), 'update_type' => '1'), 10);

                        $data['user'] = $check_user->row_array();
                        $data['updates'] = $updates->result_array();

                        $this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', $data['updates'][0]['update_date']).' GMT');
                        
                        $this->load->view('rss/bookshelf', $data);
                
                } else {
                
                        $this->output->set_output('You must supply a valid userID.');
                
                }
        
        }
        
}

?>
