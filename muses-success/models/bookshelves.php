<?php

class Bookshelves extends Model {
    
    function Main() {
        parent::Controller();
    }
    
    /**
     * Returns the content's of the user's bookshelf or a specific shelf
     *
     * @param int $user_id User ID of the user whose shelf to return
     * @param string $shelf Short name of the specific shelf to return
     * @return array containing the contents of the user's bookshelf
     */
    
    function get_bookshelf($user_id = 0, $shelf = 'current')
    {
        $user_id = ($user_id != 0) ? $user_id : (($this->users->logged_in == true) ? $this->users->cur_user["user_id"] : 0);
        
        if ($this->users->logged_in == true && $user_id != 0)
        {
            $this->db->select('stories.story_id, stories.story_slug, stories.story_title,
            stories.story_author, stories.chapter_total, library.chapters_read, library.book_rating');
            $this->db->join('stories', 'stories.story_id = library.book_id', 'left');
            $query = $this->db->get_where("library", array("book_status" => $shelf, "library_user" => $user_id));
            
            $items = array();
            foreach ($query->result() as $item)
            {
                $items[] = array(
                'id' => $item->story_id,
                'title' => $item->story_title,
                'story_slug' => $item->story_slug,
                'author' => $item->story_author,
                'read_chapter_count' => $item->chapters_read,
                'total_chapter_count' => $item->chapter_total,
                'rating' => $item->book_rating
                );
            }
            
            return $items;
        } else {
            return array();
        }
    }
   
    /**
     * Increases the read chapter count of a book by one
     *
     * @param int $book_id 
     * @param string $shelf User ID of the user that is reading the book
     * @return int|boolean The new read chapter count
     */   
    function increment_read_chapter_count($book_id, $user_id = 0)
    {
        $user_id = ($user_id != 0) ? $user_id : (($this->users->logged_in == true) ? $this->users->cur_user["user_id"] : 0);
        
        $book = $this->get_bookshelf_item($book_id, $user_id);
        
        if ($book != false)
        {
            $data = array(
            'chapters_read' => $book['read_chapter_count'] + 1
            );
            
            $this->db->where(array('book_id' => $book_id, 'library_user' => $user_id));
            $this->db->update('library', $data);
            
            return $data['chapters_read'];
        }
        
        return false;
    }
    
    /**
     * Decreases the read chapter count of a book by one
     *
     * @param int $book_id 
     * @param int $user_id User ID of the user that is reading the book
     * @return int|boolean  The new read chapter count
     */      
    function decrement_read_chapter_count($book_id, $user_id = 0)
    {
        $user_id = ($user_id != 0) ? $user_id : (($this->users->logged_in == true) ? $this->users->cur_user["user_id"] : 0);
        
        $book = $this->get_bookshelf_item($book_id, $user_id);
        
        if ($book != false)
        {
            $data = array(
            'chapters_read' => $book['read_chapter_count'] - 1
            );
            
            $this->db->where(array('book_id' => $book_id, 'library_user' => $user_id));
            $this->db->update('library', $data);
            
            return $data['chapters_read'];
        }
        
        return false;
    }
    
    
     /**
     * Retrieve a single item from the bookshelf and related details
     *
     * @param int $book_id
     * @param int $user_id User ID of the user whose shelf to search
     * @return array|boolean  Details of the book in Array format.
     */     
    function get_bookshelf_item($book_id, $user_id)
    {
        $user_id = ($user_id != 0) ? $user_id : (($this->users->logged_in == true) ? $this->users->cur_user["user_id"] : 0);
        
        $this->db->select('stories.story_id, stories.story_slug, stories.story_title,
        stories.story_author, stories.chapter_total, library.chapters_read, library.book_rating, library.book_status');
        $this->db->join('stories', 'stories.story_id = library.book_id', 'left');
        $query = $this->db->get_where("library", array("book_id" => $book_id, "library_user" => $user_id));
        if ($query->num_rows() == 1)
        {
            $result = $query->row();
            
            $item = array(
            'id' => $result->story_id,
            'title' => $result->story_title,
            'story_slug' => $result->story_slug,
            'author' => $result->story_author,
            'read_chapter_count' => $result->chapters_read,
            'total_chapter_count' => $result->chapter_total,
            'rating' => $result->book_rating,
            'status' => $result->book_status
            );
                       
            return $item;
            
        } else {
            return false;
        }
        
    }
    
    /**
     * Post to the users timeline announcing that user read chapter
     *
     * @param int $book_id Book identifier
     * @param int $user_id User to update.
     * @param string $title Book title
     * @param string $text Update text.
     * @param string $link Link to the book listing.
     * @return void
     * @todo Move to own model should updates table be used beyond bookshelves
     */
     
    function update_shelf_timeline($book_id, $user_id, $title, $text, $link)
    {
        
        
        $data = array(
        'update_type' => 1,
        'user_id' => $user_id,
        'update_date' => time(),
        'update_rel_id' => $book_id,
        'update_text' => $text,
        'update_title' => $title,
        'update_link' => $link
        );
        $this->db->insert('updates', $data);
    }
    
}