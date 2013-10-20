<?php

class Bookshelves extends Model {

    function Main() {
        parent::Controller();
    }

    function get_bookshelf($user_id = 0, $shelf = 'current')
    {
        $user_id = ($user_id != 0) ? $user_id : (($this->users->logged_in == true) ? $this->users->cur_user["user_id"] : 0);
        
        if ($this->users->logged_in == true && $user_id != 0)
        {
            $this->db->select('stories.story_id, stories.story_slug, stories.story_title,
            stories.story_author, stories.chapter_total, library.chapters_read');
            $this->db->join('stories', 'stories.story_id = library.book_id', 'left');
            $query = $this->db->get_where("library", array("book_status" => $shelf, "library_user" => $user_id));
    
            $items = array();
            foreach ($query->result() as $item)
            {
                $items[] = array(
                        'id' => $item->story_id,
                        'title' => $item->story_title,
                		'story_slug' => $item->story_title,
                        'author' => $item->story_author,
                        'read_chapter_count' => $item->chapters_read,
                        'total_chapter_count' => $item->chapter_total 
                );
            }
    
            return $items;
        } else {
            return array();
        }
    }

}