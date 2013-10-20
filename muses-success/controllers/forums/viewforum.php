<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Viewforum extends Controller {

        function index()
        {
                $forum_id = $this->uri->segment(3);
                
                $this->load->model('forums');
                
                $forum = $this->forums->get_forum($forum_id);
                
                if ($forum != false)
                {
                        $topics = $this->forums->get_topics($forum_id);

                        $data = array('forum' => $forum, 'topics' => $topics);
                        $page = array(
                                'page_name' => $forum['title'],
                                'breadcrumbs' => array($forum['title']),
                                'page_options' => array('<a href="'.site_url('forums/post/topic/'.$forum['id'].'').'">Create New Topic</a>')
                        );

                        $this->load->view('header', array('page_title' => $forum['title']));
                        $this->load->view('forums/forum_header', $page);
                        $this->load->view('forums/viewforum', $data);
                        $this->load->view('footer');
                } else {
                        show_404('forums/viewforum/'.intval($forum_id).'');
                }
        }

}

?>
