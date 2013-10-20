<?php

function flood_check($table, $timestamp_column, $user_id_column, $minutes, $limit)
{

    $CI = & get_instance();
    
    $CI->db->select('COUNT(*) as `flood_count`');
    $CI->db->where($user_id_column, $CI->users->cur_user['user_id']);
    $CI->db->where('`'.$timestamp_column.'` > '.(time()-($minutes*60)).'');
    $query = $CI->db->get($table);
    $row = $query->row();
    
    if ($limit > $row->flood_count)
    {
        return true;
    } else {
        return false;
    }

}

?>
