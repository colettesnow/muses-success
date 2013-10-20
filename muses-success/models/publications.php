<?php

class Publications extends Model {

    private $temp_story_cache = array();

    /**
     * list_publications
     *
     * This function returns an array of stories by the currently logged in
     * user.
     *
     * @return array containing all the stories we have verified as the current
     * user has written.
     */
    function list_publications()
    {

        $get_published = $this->db->get_where('publications', array('author_id' => $this->users->cur_user['user_id']));

        $i = 0;
        $publications = array();
        foreach ($get_published->result() as $listing)
        {

            if (!isset($this->temp_story_cache[$listing->novel_id]))
            {
                $story_get = $this->db->get_where('stories', array('story_id' => $listing->novel_id));
                $story = $story_get->row();
                $this->temp_story_cache[$listing->novel_id] = $story;
            } else {
                $story = $this->temp_story_cache[$listing->novel_id];
            }

            $tweet_limit = $this->get_tweet_limit($listing->novel_id);

            ++$i;
            $publications[$i] = array();
            $publications[$i]['id'] = $story->story_id;
            $publications[$i]['name'] = $story->story_title;
            $publications[$i]['tweet_limit_data'] = $tweet_limit;
            $publications[$i]['shelf_count'] = $this->shelf_count($listing->novel_id);
            $publications[$i]['chapter_count'] = $story->chapter_total;
            $publications[$i]['review_count'] = $story->story_review_count;
            $publications[$i]['comment_count'] = $this->get_comment_count($listing->novel_id);
            $publications[$i]['listing_url'] = site_url($story->story_slug.'/');
            $publications[$i]['reviews_url'] = site_url($story->story_slug.'/reviews');
            $publications[$i]['readers_url'] = site_url($story->story_slug.'/readers');
            if ($tweet_limit[1] == 'None')
            {
                $publications[$i]['tweet_limit'] = 'None';
            } else {
                $publications[$i]['tweet_limit'] = $tweet_limit[0].' '.$tweet_limit[1];
            }
        }

        return $publications;

    }

    /**
     * get_tweet_limit
     *
     * This function returns the amount of chapter announcements this user is
     * allowed to make in a specific time frame for the specific story based on
     * the update frequency that is listed in our database.
     *
     * @param int $book_id listing/story ID
     * @return array containing the number of tweets and over what period this.
     */
    function get_tweet_limit($book_id)
    {

        if (!isset($this->temp_story_cache[$book_id]))
        {

            $story_get = $this->db->get_where('stories', array('story_id' => $book_id));
            $story = $story_get->row();

        } else {

            $story = $this->temp_story_cache[$book_id];

        }

        $updated =  array(
                                                'Daily' => array(7, 'Weekly'), 
                                                                'Almost Daily' => array(5, 'Weekly'), 
                                                                'Every Few Days' => array(4, 'Weekly'), 
                                                                'Weekly' => array(1, 'Weekly'), 
                                                                'Fortnightly' => array(1, 'Fortnightly'), 
                                                                'Monthly' => array(2, 'Monthly'), 
                                                                'Quarterly' => array(2, 'Every Three Months'), 
                                                                'Bi-Annually' => array(3, 'Yearly'), 
                                                                'Annually' => array(2, 'Yearly'), 
                                                                'Hiatus' => array(0, 'None'), 
                                                                'Almost Never' => array(1, 'Monthly'), 
                                                                'No Longer Updated' => array(0, 'None'), 
                                                                'Completed' => array(0, 'None')
        );

        if (isset($updated[$story->story_update_schedule]) == true)
        {

            $update_limit = $updated[$story->story_update_schedule];

        } else {

            $update_limit = array(1, 'Weekly');

        }

        return $update_limit;

    }

    /**
     * shelf_count
     *
     * Returns the number of users who have this book on their bookshelf.
     *
     * @param int $book_id - book/story ID
     * @return int number of users who have this book on bookshelf
     */
    function shelf_count($book_id)
    {
        $this->db->select('library_id');
        $this->db->where('book_id', $book_id);
        $this->db->from('library');

        return $this->db->count_all_results();
    }

    function get_comment_count($book_id)
    {

        $this->db->where('listing_id', $book_id);
        $this->db->from('comments');

        return $this->db->count_all_results();

    }

    function cur_user_is_author($id)
    {
        $this->db->where('author_id', $this->users->cur_user['user_id']);
        $this->db->where('novel_id', $id);
        $this->db->from('publications');
    }

}
?>