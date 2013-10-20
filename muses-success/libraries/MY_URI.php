<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_URI extends CI_URI {

    function MY_URI() {
        parent::CI_URI();
    }

    // --------------------------------------------------------------------
    
    /**
     * Override the _parse_request_uri method so it allows query strings through
     *
     * @access    private
     * @return    string
     */    
    function _parse_request_uri()
    {
        if ( ! isset($_SERVER['REQUEST_URI']) OR $_SERVER['REQUEST_URI'] == '')
        {
            return '';
        }
        
        $uri = explode("?",$_SERVER['REQUEST_URI']); // This line is added to the original 
        $request_uri = preg_replace("|/(.*)|", "\\1", str_replace("\\", "/", $uri[0])); // This line changed 
        // Everything else is just the same

        if ($request_uri == '' OR $request_uri == SELF)
        {
            return '';
        }
        
        $fc_path = FCPATH;        
        if (strpos($request_uri, '?') !== FALSE)
        {
            $fc_path .= '?';
        }
        
        $parsed_uri = explode("/", $request_uri);
                
        $i = 0;
        foreach(explode("/", $fc_path) as $segment)
        {
            if (isset($parsed_uri[$i]) AND $segment == $parsed_uri[$i])
            {
                $i++;
            }
        }
        
        $parsed_uri = implode("/", array_slice($parsed_uri, $i));
        
        if ($parsed_uri != '')
        {
            $parsed_uri = '/'.$parsed_uri;
        }

        return $parsed_uri;
    }

}
