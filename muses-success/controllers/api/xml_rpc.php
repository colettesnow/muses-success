<?php

class Xml_rpc extends Controller {

    function main()
    {
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');

        // Listings
        $config['functions']['get_listing_info'] = array('function' => 'api_listing.get_listing_info');
        $config['functions']['get_listings'] = array('function' => 'api_listing.get_listings');

        // Reviews
        $config['functions']['get_review'] = array('function' => 'api_review.get_review');
        $config['functions']['get_reviews'] = array('function' => 'api_review.get_reviews');

        // Bookshelfs
        $config['functions']['get_bookshelf_contents'] = array('function' => 'api_bookshelf.get_bookshelf_contents');

        $config['object'] = $this;

        $this->xmlrpcs->initialize($config);
        $this->xmlrpcs->serve();
    }

}

?>
