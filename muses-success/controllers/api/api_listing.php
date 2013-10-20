<?php

class Api_listing extends Controller {

    function get_listing_info($request)
    {

        $parameters = $request->output_parameters();

        $this->load->library('xmlrpc');
        $this->load->model('novels');

        $listing_id = intval($request['1']);

        $novel_info = $this->novels->get_novel($listing_id);

        if (count($novel_info) != 0)
        {
        
            $response = array(
                array(
                    'listing_id' => array($novel_info['id'], 'int'),
                    'listing_title' => array($novel_info['title'], 'string')
                ),
                'struct'
            );
        
            return $this->xmlrpc->send_response($response);

        } else {
        
            return $this->xmlrpc->send_error_message('1', 'Requested listing does not exist.');
        
        }
    
    }

}

?>
