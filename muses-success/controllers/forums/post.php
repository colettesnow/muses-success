<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Post extends Controller {

        function index()
        {
                show_404();
        }
        
        function topic()
        {
                if ($this->users->logged_in == false)
                {
                        $this->load->view('header');
                        $this->load->view('accounts/login_required');
                        $this->load->view('footer');
                } else {

                $forum_id = $this->uri->segment(4);

                $this->load->library('validation');
                $this->load->model('forums');
                
                $forum = $this->forums->get_forum($forum_id);

                if ($forum != false)
                {

                $rules['subject'] = 'required|min_length[5]|max_length[80]';
                $rules['message'] = 'required|min_length[3]|max_length[4096]';
                $rules['submit'] = 'callback_flood_control[2]';

                $this->validation->set_rules($rules);
                
                if ($this->validation->run() == FALSE)
                {

                        $data = array('forum' => $forum);

                        $page = array(
                                'page_name' => 'Create New Topic',
                                'breadcrumbs' => array('<a href="'.$forum['url'].'">'.$forum['title'].'</a>', 'Create New Topic')
                        );

                        $this->load->view('header', array('forum_markup' => true, 'page_title' => 'Create New Topic'));
                        $this->load->view('forums/forum_header', $page);
                        $this->load->view('forums/post_topic', $data);
                        $this->load->view('footer');

                } else {
                
                        $add_topic = $this->forums->add_topic($this->input->post('subject'), $this->users->cur_user['user_id'], $forum_id, $this->input->post('message', true));

                        $data = array('message_list' => site_url('forums/viewtopic/'.$add_topic.'/'), 'topic_list' => site_url('forums/viewforum/'.$forum_id.'/'));

                        $this->load->view('header');
                        $this->load->view('forums/post_topic_success', $data);
                        $this->load->view('footer');

                }
                
                } else {
                        show_404();
                }
                
                }
                
        }
        
        function message()
        {
                if ($this->users->logged_in == false)
                {
                        $this->load->view('header');
                        $this->load->view('accounts/login_required');
                        $this->load->view('footer');
                } else {

                $topic_id = $this->uri->segment(4);
                $quote_id = $this->uri->segment(5);
                
                if (round($quote_id) == 0)
                {
                        unset($quote_id);
                }
                
                $this->load->library('validation');
                $this->load->model('forums');

                $topic = $this->forums->get_topic($topic_id);

                if ($topic != false)
                {

                $forum = $this->forums->get_forum($topic['forum_id']);

                $rules['message'] = 'required|min_length[3]|max_length[4096]';
                $rules['submit'] = 'callback_flood_control[1]';
                
                $this->validation->set_rules($rules);

                if ($this->validation->run() == FALSE)
                {
                        $page = array(
                                'page_name' => 'Create New Topic',
                                'breadcrumbs' => array('<a href="'.$forum['url'].'">'.$forum['title'].'</a>', 'Create New Topic')
                        );
                        
                        $data = array('forum' => $forum, 'topic' => $topic, 'quote' => (isset($quote_id)) ? true : false);

                        $this->load->view('header', array('forum_markup' => true, 'page_title' => 'Post Message'));
                        $this->load->view('forums/forum_header', $page);
                        $this->load->view('forums/post_message', $data);
                        $this->load->view('footer');

                } else {

                        if (isset($quote_id) == true && round($quote_id) != 0)
                        {
                                $post = $this->forums->get_post($quote_id);
                                if ($post != false)
                                {
                                        $quote_content = '[quote='.$post['author_name'].']'.trim($post['content_notparse']).'[/quote]';
                                }
                        } else {
                                $quote_content = '';
                        }

                        $data = array('message_list' => site_url('forums/viewtopic/'.$topic_id.'/'), 'topic_list' => site_url('forums/viewforum/'.$forum['id'].'/'));

                        $add_post = $this->forums->add_post($topic_id, $forum['id'], $this->users->cur_user['user_id'], $quote_content.$this->input->post('message', true));

                        $this->load->view('header');
                        $this->load->view('forums/post_message_success', $data);
                        $this->load->view('footer');

                }

                } else {
                        show_404();
                }

        }
        }

        function parse_bbcode()
        {
                $message = $this->input->post('data');

                $this->load->helper('bbcode');
                $this->load->helper('typography');

                $message = trim($message);
                $message = htmlspecialchars($message);
                $message = auto_typography($message);
                $message = parse_bbcode($message);
                
                $this->load->view('bbcode', array('message' => $message));
        }
        
        function flood_control($useless, $type)
        {

                if ($type == 1)
                {
                        $result = $this->db->query('SELECT COUNT(`post_id`) AS num FROM forum_posts WHERE post_date >= ' . (time() - 60) . ' AND user_id = ' . $this->users->cur_user['user_id']);
                        $msgs_last_min = $result->row();
                        if ($msgs_last_min->num >= 2)
                        {
                                $this->validation->set_message('flood_control','To prevent flooding, users cannot post more than two messages per minute. Please wait and try your post again later.');
                                return false;
                        } else {
                                return true;
                        }
                } else {
                        $result = $this->db->query('SELECT COUNT(`post_id`) AS num FROM forum_posts WHERE post_date >= ' . (time() - 60) . ' AND user_id = ' . $this->users->cur_user['user_id']);
                        $msgs_last_min = $result->row();
                        $result = $this->db->query('SELECT COUNT(`topic_id`) AS num FROM forum_topics WHERE topic_date >= ' . (time() - 900) . ' AND topic_by = ' . $this->users->cur_user['user_id']);
                        $tops_last_15_mins = $result->row();
                        $result = $this->db->query('SELECT COUNT(`topic_id`) AS num FROM forum_topics WHERE topic_date >= ' . (time() - 86400) . ' AND topic_by = ' . $this->users->cur_user['user_id'] . ' AND forum_id = \'' . intval($this->uri->segment(4)) . '\'');
                        $tops_boa_last_day = $result->row();

                        if ($msgs_last_min->num >= 2)
                        {
                                $this->validation->set_message('flood_control','To prevent flooding, users cannot post more than two messages per minute. Please wait and try your post again later.');
                                return false;
                        } elseif ($tops_last_15_mins->num >= 3)
                        {
                                $this->validation->set_message('flood_control','To prevent flooding, users cannot create more than three topics in any fifteen minute period. Please wait and try your post agin later.');
                                return false;
                        } elseif ($tops_boa_last_day->num >= 10)
                        {
                                $this->validation->set_message('flood_control','To prevent flooding, users cannot create more than ten topics on any single board in one day. Please wait and try your post agin later.');
                                return false;
                        } else {
                                return true;
                        }
                }
        }

}

?>
