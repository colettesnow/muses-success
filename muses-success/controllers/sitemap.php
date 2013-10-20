<?php

        class Sitemap extends Controller {

        function Sitemap() {
                parent::Controller();
        }                

                function index()
                {

                        $this->output->cache(1440);

                        $this->output->set_header("Content-Type: text/xml");

                        $url = array();
                        $url[] = array('http://muses-success.info/', 'hourly', '1.0');
                        $url[] = array('http://muses-success.info/recommendations', 'daily', '0.9');
                        $url[] = array('http://muses-success.info/reviews', 'daily', '0.9');
                        $url[] = array('http://muses-success.info/rss', 'daily', '0.5');
                        $url[] = array('http://muses-success.info/rss/reviews', 'daily', '0.5');
                        $url[] = array('http://muses-success.info/faqs', 'monthly', '0.3');
                        $url[] = array('http://muses-success.info/p/privacy', 'monthly', '0.3');
                        $url[] = array('http://muses-success.info/p/copyright', 'monthly', '0.3');
                        $url[] = array('http://muses-success.info/p/about', 'monthly', '0.6');
                        $url[] = array('http://muses-success.info/p/links', 'monthly', '0.4');
                        $url[] = array('http://muses-success.info/contribute', 'monthly', '1.0');
                        $url[] = array('http://muses-success.info/browse/listing/0/all/all', 'hourly', '1.0');
                        $url[] = array('http://muses-success.info/tags', 'daily', '1.0');
                                        
                        $this->db->select('story_slug, story_review_count');
                        $get_stories = $this->db->get_where('stories', array('story_approved' => '1'));
                        
                        foreach ($get_stories->result() as $story)
                        {
                                $url[] = array('http://muses-success.info/'.$story->story_slug, 'daily', '1.0');
                                if ($story->story_review_count >= 1)
                                {
                                        $url[] = array('http://muses-success.info/'.$story->story_slug.'/reviews', 'daily', '0.7');
                                }
                        }

                        $this->load->view('site_map', array('urls' => $url));
                }
                
        }

?>
