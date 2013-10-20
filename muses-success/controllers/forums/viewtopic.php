<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Viewtopic extends Controller {

        function index()
        {
                $topic_id = $this->uri->segment(3);
                
                $this->load->model('forums');
                
                $topic = $this->forums->get_topic($topic_id);

                if ($topic != false)
                {

                        $forum = $this->forums->get_forum($topic['forum_id']);
                        $posts = $this->forums->get_posts($topic['id']);

                        $page = array(
                                'page_name' => $forum['title'],
                                'breadcrumbs' => array('<a href="'.$forum['url'].'">'.$forum['title'].'</a>', $topic['subject']),
                                'page_options' => array('<a href="'.site_url('forums/post/message/'.$topic['id'].'').'">Post New Message</a>')
                        );

                        $data = array(
                                'forum' => $forum,
                                'topic' => $topic,
                                'posts' => $posts
                        );

                        $this->load->view('header', array('forum_markup' => true, 'page_title' => $topic['subject']));
                        $this->load->view('forums/forum_header', $page);
                        $this->load->view('forums/viewtopic', $data);
                        $this->load->view('footer');

                } else {
                        show_404('forums/viewtopic/'.$topic_id.'');
                }


        }
        
        function report()
        {
        
                $post_id = $this->uri->segment(4);

                $this->load->model('forums');

                $post = $this->forums->get_post($post_id);
        

                $topic = $this->forums->get_topic($post['topic_id']);
                $forum = $this->forums->get_forum($topic['forum_id']);

                        $page = array(
                                'page_name' => 'Report Message',
                                'breadcrumbs' => array('<a href="'.$forum['url'].'">'.$forum['title'].'</a>', '<a href="'.$topic['url'].'">'.$topic['subject'].'</a>', 'Report Message'),
                                'page_options' => array('<a href="'.site_url('forums/post/message/'.$topic['id'].'').'">Post New Message</a>')
                        );
                        
                        $data = array('topic' => $topic, 'forum' => $forum, 'post' => $post);
                        
                        $this->load->view('header', array('forum_markup' => false, 'page_title' => 'Report Message'));
                        $this->load->view('forums/forum_header', $page);
                        $this->load->view('forums/abuse', $data);
                        $this->load->view('footer');

        }

}

?>
