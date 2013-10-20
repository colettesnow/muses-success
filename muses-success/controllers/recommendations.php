<?php

class Recommendations extends Controller {

        function main()
        {

                $this->load->model(array('recs', 'thumbnails'));

                $recent = $this->recs->recent();

                $data = array();
                $data['recommendations'] = &$recent;

                $page_data = array();
                $page_data['page_title'] = 'Recent Recommendations';
                $page_data['breadcrumbs'] = array('Recent Recommendations');
                $this->load->view('header', $page_data);
                $this->load->view('recommendations/recent', $data);
                $this->load->view('footer');

        }

        function make()
        {

                if ($this->users->logged_in == false)
                {
                        redirect('accounts/login');
                }

                $this->load->model('recs');
                $this->load->model('novels');

                $listing_id = $this->uri->segment(3);

                if ($listing_id == 0)
                {

                        $data = array(
                                'books' => $this->recs->list_novels()
                        );

                        $page_data['page_title'] = 'Make Recommendation';
                        $page_data['breadcrumbs'] = array('<a href="'.site_url('recommendations').'">Recommendations</a>', 'Make Recommendation');                        
      
                        
                        $this->load->view('header', $page_data);
                        $this->load->view('recommendations/make', $data);
                        $this->load->view('footer');

                } else {

                        $this->load->library('form_validation');

                        $this->form_validation->set_rules('book', 'Similar Title', 'required|integer|is_natural_no_zero');
                        $this->form_validation->set_rules('comment', 'Similar Explanation', 'required|min_length[50]');

                        $novel = $this->novels->get_novel($listing_id);

                        $data = array(
                                'books' => $this->recs->list_novels(),
                                'listing' => $novel['title']
                        );

                        $page_data['page_title'] = 'Make Recommendation for '.$novel['title'];
                        $page_data['breadcrumbs'] = array('<a href="'.site_url('recommendations').'">Recommendations</a>', 'Make Recommendation for '.$novel['title']);                        
                        
                        if ($this->form_validation->run() == FALSE)
                        {

                                $this->load->view('header', $page_data);
                                $this->load->view('recommendations/make_recommendation', $data);
                                $this->load->view('footer');

                        } else {

                                $this->recs->add_recommendation($listing_id, $this->input->post('book'), $this->input->post('comment'));

                                $this->load->view('header', array('page_title' => 'Recommendation Successful', 'alert' => 'Your similar recommendation for '.$novel['title'].' has been made successfully.'));
                                $this->load->view('recommendations/make_recommendation', $data);
                                $this->load->view('footer');

                        }
                }

        }

}

?>
