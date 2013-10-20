<?php
/**
 * Reviews_model
 *
 */
class Reviews_model extends Model {

    /**
     * get_reviews
     *
     * @param string $type
     * @param int $limit = 10
     * @return array
     */
    function get_reviews($type, $story_id = 0, $limit = 10)
    {
        $this->load->helper('markdown');

        $this->db->join('stories', 'reviews.review_story = stories.story_id', 'left');
        $this->db->join('users', 'reviews.review_author = users.user_id', 'left');
        
        if ($story_id != 0)
        {
            $this->db->where('review_story', $story_id);
        }

        switch ($type)
        {
            case 'recent':
                $this->db->order_by('review_id', 'DESC');
            break;
            case 'helpful':
                $this->db->order_by('review_helpful_order', 'DESC');                
            break;            
        }
        
        $this->db->order_by('review_id', 'DESC');
        $query = $this->db->get('reviews', $limit);
        $reviews = array();
        $i = 0;
        foreach ($query->result() as $row)
        {
            ++$i;
            $reviews[$i] = array();
            // review
            $reviews[$i]['id'] = $row->review_id;
            $reviews[$i]['tagline'] = $row->review_tagline;
            $reviews[$i]['review'] = parse_markdown($row->review_text);
            $reviews[$i]['rating'] = round($row->review_rating);
            $reviews[$i]['review_url'] = site_url($row->story_slug.'/reviews/'.$row->review_id);
            $reviews[$i]['date'] = $row->review_date;
                                                            
            // story info
            $reviews[$i]['title'] = $row->story_title;
            $reviews[$i]['author'] = $row->story_author;
            $reviews[$i]['read_url'] = ($row->story_url);
            $reviews[$i]['listing_url'] = site_url($row->story_slug);
            $reviews[$i]['slug'] = $row->story_slug;

            // helpfulness of review
            $reviews[$i]['helpful'] = $row->review_helpful_count;
            $reviews[$i]['total_ratings'] = $row->review_helpful_count+$row->review_not_helpful_count;            
            
            // user info / review author
            $reviews[$i]['user_id'] = round($row->user_id);
            $reviews[$i]['username'] = ($row->display_name == '') ? $row->screen_name : $row->display_name;
            $reviews[$i]['avatar'] = 'http://www.gravatar.com/avatar/'.md5($row->email_address).'?s=100&amp;d=identicon';                            
            $reviews[$i]['profile_url'] = site_url('profile/view/'.$row->user_id);
            
        }
        
        return $reviews;
    }

    /**
     * get_review
     *
     * @param int $review_id
     * @param bool $editing = false
     * @return array
     */
    function get_review($review_id, $editing = false)
    {
        if ($editing == false)
        {
            $this->load->helper('markdown');
        }

        $this->db->select('review_id, review_tagline, review_text, review_rating, review_story, reviews.ip_address, review_helpful_count, review_not_helpful_count, review_author, screen_name, display_name, user_id, email_address');
        $this->db->join('users', 'reviews.review_author = users.user_id', 'left');
        $query = $this->db->get_where('reviews', array('review_id' => intval($review_id)));
        $row = $query->row();
        $review = array();

        if ($query->num_rows() == 1)
        {
            $review['id'] = $row->review_id;
            $review['listing_id'] = $row->review_story;
            $review['tagline'] = $row->review_tagline;
            $review['review'] = ($editing == false) ? parse_markdown($row->review_text) : $row->review_text;
            $review['author_id'] = $row->review_author;
            $review['author'] = ($row->display_name != '') ? $row->display_name : $row->screen_name;
            $review['helpful'] = $row->review_helpful_count;
            $review['not_helpful'] = $row->review_not_helpful_count;
            $review['rating_total'] = $row->review_helpful_count+$row->review_not_helpful_count;
            $review['total_ratings'] = $review['rating_total'];
            $review['rating'] = $row->review_rating;
        
            // user info / review author
            $review['user_id'] = round($row->user_id);
            $review['username'] = ($row->display_name == '') ? $row->screen_name : $row->display_name;
            $review['avatar'] = 'http://www.gravatar.com/avatar/'.md5($row->email_address).'?s=100&amp;d=identicon';                            
            $review['profile_url'] = site_url('user/'.$row->user_id);        
        }

        return $review;
    }
    
    /**
     * add_review
     *
     * @param int $listing_id
     * @param string $tagline
     * @param int $rating
     * @param string $text
     * @param int $user_id = 0
     * @return void
     */    
    function add_review($listing_id, $tagline, $rating, $text, $user_id = 0)
    {
        if ($this->users->has_permission('g_create_review') == 1)
        {
            $review_data = array(
                'review_tagline' => $tagline,
                'review_rating' => intval($rating),
                'review_text' => $text,
                'review_story' => intval($listing_id),
                'review_author' => ($user_id != 0) ? $user_id : $this->users->cur_user['user_id'],
                'review_date' => time()
            );
        
            $this->db->insert('reviews', $review_data);
        }
    }
    
    function edit_review($listing_id, $tagline, $rating, $text, $user_id = 0)
    {
        if ($this->users->has_permission('g_edit_review') != 0)
        {
            if (($user_id != 0 || $user_id != $this->users->cur_user['user_id']) && $this->users->has_permission('g_edit_review') == 2)
            {
                $review_id = $this->review_exists($listing_id, $user_id);
            } else {
                if ($user_id == 0 || $user_id != $this->users->cur_user['user_id'])
                {
                    $review_id = $this->review_exists($listing_id, $user_id);
                } else {
                    return false;
                }
            }

            $review_data = array(
                'review_tagline' => $tagline,
                'review_rating' => intval($rating),
                'review_text' => $text,
                'review_story' => intval($listing_id),
                'review_author' => ($user_id != 0) ? $user_id : $this->users->cur_user['user_id'],
                'review_edited' => time()
            );
            
            $this->db->where('review_id', $review_id);
            $this->db->update('reviews', $review_data);
            
            return true;
        
        } else {
        
            return false;

        }
    }
    
    /**
     * delete_review
     *
     * @param int $review_id
     * @return void
     */
    function delete_review($review_id)
    {
        if ($this->users->has_permission('g_delete_review') != 0)
        {
            $this->db->where('review_id', $review_id);
            if ($this->users->has_permission('g_delete_review') == 1)
            {
                $this->db->where('review_user', $this->users->cur_user['user_id']);
            }
            $this->db->delete('reviews');
        }
    }
    
    /**
     * review_exists
     *
     * @return void
     */
    function review_exists()
    {    
        $this->db->select('review_id');

        if (func_num_args() == 2)
        {
            $this->db->where('review_author', intval(func_get_arg(1)));
            $this->db->where('review_story', intval(func_get_arg(0)));
            $return_id = true;
        } else {
            $this->db->where('review_id', intval(func_get_arg(0)));
        }
        
        $query = $this->db->get('reviews');
        $row = $query->row();
        
        if ($row->num_rows() == 1)
        {
            return $row->review_id;
        } else {
            return 0;
        }
    }

}

?>
