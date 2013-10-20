<?php

function date_relative($timestamp){
    $difference = time() - $timestamp;

    if($difference < 60)
    {
        return $difference." seconds ago";
    } else {
        $difference = round($difference / 60);
        if($difference < 60)
        {
            return $difference." minutes ago";
        } else {
            $difference = round($difference / 60);
            if($difference < 24)
            {
                return $difference." hours ago";
            
            } else {
                $difference = round($difference / 24);
                
                if ($difference == 1)
                {
                	return "Yesterday, ".date('h:iA', $timestamp);
                } elseif ($difference < 7)
                {
                    return date('l, g:iA', $timestamp);
                } else
                {
                    $current_year = date('Y');
                    $stamp_year =  date('Y', $timestamp);
					if ($current_year == $stamp_year) {
                		return date('F jS, g:iA', $timestamp);
                	} else {
                		return date('F jS Y', $timestamp);                	
                	}
                }
            }
        }
    }
}


?>
