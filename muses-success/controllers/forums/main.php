<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Main extends Controller {

        function index()
        {
                $data = array('page_title' => 'Community Forums');
                $this->load->view('header', $data);
                $this->load->model('forums');
                $data = array('categories' => $this->forums->get_forums());
                $this->load->view('forums/boardlist', $data);
                $this->load->view('footer');
        }

}

?>
