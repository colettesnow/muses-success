<?php
class Novels extends Model
{
    function novel_exists($id)
    {
        $identifier_table = is_int($id) ? 'story_id' : 'story_slug';

        $query = $this->db->get_where('stories', array($identifier_table => $id, 'story_approved' => '1'), 1);

        if ($query->num_rows() == 1)
        {
            return true;
        } else {
            return false;
        }
    }

    function get_comments($id)
    {
        $this->db->cache_on();
        $query = $this->db->get_where('comments', array('listing_id' => $id));
        $this->load->helper(array('typography', 'date_relative'));

        $i = 0;
        $comments = array();
        foreach ($query->result() as $comment) {
            ++$i;
            $comments[$i] = array();
            $comments[$i]['id'] = $comment->comment_id;
            $comments[$i]['comment'] = auto_typography($comment->comment_text);
            $comments[$i]['date'] = date_relative($comment->date);
            $user = $this->users->get_user_info($comment->user_id);
            $comments[$i]['avatar'] = 'http://www.gravatar.com/avatar/'.md5(trim(strtolower($user['email_address']))).'?s=60';
            $comments[$i]['profile'] = site_url('profile/view/'.$comment->user_id);
            $comments[$i]['user'] = '<a href="' . site_url('profile/view/' . $comment->user_id . '/') . '">' . ((strlen($user['display_name']) > 1) ? $user['display_name'] : $user['screen_name']) . '</a>';
        }


        $this->db->cache_off();

        return $comments;
    }

    function get_novel($id, $allow_unapproved = false, $dataset = NULL)
    {
        if (is_numeric($id)) {
            $id_table = 'story_id';
        } else {
            $id_table = 'story_slug';
        }

        if ($allow_unapproved == false) {
            $query = $this->db->get_where('stories', array($id_table => $id, 'story_approved' => '1'), 1);
        } else {
            $query = $this->db->get_where('stories', array($id_table => $id), 1);
        }

        if ($query->num_rows() == 1) {
            $this->load->helper(array('markdown', 'title'));

            $row = $query->row_array();

			if ($dataset != NULL)
			{
				$row = array_merge($row, $dataset);
			}

            $novel = array();
            $novel['id'] = $row['story_id'];
            $novel['title'] = $this->strip_book_num($row['story_title']);
            $novel['subtitle'] = $row['story_subtitle'];
            $novel['summary'] = (strlen($row['story_summary']) == 0 && $allow_unapproved == false) ? '' : parse_markdown($row['story_summary']);
            $novel['summary_raw'] = (strlen($row['story_summary']) == 0 && $allow_unapproved == false) ? '' : ($row['story_summary']);            
            $novel['primary_genre'] = $row['story_primary_genre'];
            $novel['primary_genre_url'] = site_url('browse/listing/1/' . ($row['story_primary_genre']) . '/all/');
            $novel['secondary_genre'] = $row['story_secondary_genre'];
            $novel['secondary_genre_url'] = site_url('browse/listing/1/' . ($row['story_secondary_genre']) . '/all/');
            $novel['update_schedule'] = $row['story_update_schedule'];
            $novel['url'] = $row['story_url'];
            $novel['purchase_link'] = $row['story_purchase_link'];
            $novel['rss_feed'] = $row['story_rss'];
            $novel['author_pen'] = $row['story_author'];
            $novel['author_url'] = $row['story_author_url'];
            $novel['listing_url'] = site_url($row['story_slug'] . '/');
            $novel['review_url'] = site_url($row['story_slug'] . '/reviews/');
            $novel['recommendation_url'] = site_url($row['story_slug'] . '/recommendations/');
            $novel['reader_url'] = site_url($row['story_slug'] . '/readers/');
            $novel['tags'] = $row['story_tags'];
            $novel['views'] = $row['story_views'];
            $novel['mature'] = $row['story_mature'];
            $novel['slug'] = $row['story_slug'];
            $novel['is_in_index'] = $row['not_in_index'];            
            $novel['intended_audience'] = (strlen($row['story_audience']) > 1) ? $row['story_audience'] : 'Unknown';
            $novel['coarse'] = $row['story_mature_coarse'];
            $novel['sex'] = $row['story_mature_sex'];
            $novel['violence'] = $row['story_mature_violence'];
            $novel['submit_user'] = $row['story_submit_by'];
            $novel['is_update'] = $row['is_update'];
            $novel['chapters'] = $row['chapter_total'];
            $novel['chapter_count'] = $novel['chapters'];
            $novel['update_to'] = $row['update_to'];
            $novel['credit'] = explode(',', $row['story_credit']);
            $novel['thumbnail_url'] = 'http://open.thumbshots.org/image.aspx?url=' . urlencode($row['story_url']);
            $novel['feature_image_position'] = ($row['story_feature_image_position'] == 0) ? 'none' : (($row['story_feature_image_position'] == 1) ? 'above' : 'below');

            $novel['mature_content'] = array();
            $o = 0;
            if ($row['story_mature_coarse'] == 2) {
                $o = 1;
                $novel['mature_content'][] = 'Some Coarse Language';
            }
            if ($row['story_mature_coarse'] == 3) {
                $o = 1;
                $novel['mature_content'][] = 'Frequent Coarse Language';
            }
            if ($row['story_mature_sex'] == 2) {
                $o = 1;
                $novel['mature_content'][] = 'Some Sexual Content';
            }
            if ($row['story_mature_sex'] == 3) {
                $o = 1;
                $novel['mature_content'][] = 'Frequent Sexual Content';
            }
            if ($row['story_mature_violence'] == 2) {
                $o = 1;
                $novel['mature_content'][] = 'Some Violence';
            }
            if ($row['story_mature_violence'] == 3) {
                $o = 1;
                $novel['mature_content'][] = 'Frequent Violence';
            }
            if ($o == 0 && $row['story_mature'] == 1) {
                $novel['mature_content'][] = 'Yes';
            }
            $novel['formats'] = array('<a href="">Blog</a>', '<a href="">Print</a>');
            $novel['audience'] = $row['story_audience'];
            $novel['rating'] = $row['story_weighted_rating'];
            $novel['guest_average'] = $row['story_rating_guest_average'];
            $novel['user_average'] = $row['story_rating_user_average'];            
            $novel['ranking'] = $row['rating_order'];
            $novel['rating_count'] = $row['story_rating_count'];
            $novel['review_count'] = $row['story_review_count'];
            $novel['rating_url'] = '';

            return $novel;
        } else
        return array();
    }

    function add_novel()
    {
    }

    function update_novel($id)
    {
    }

    function strip_book_num($title)
    {
        for ($i = 1; $i <= 20; ++$i) {
            $title = str_replace(' (Book ' . $i . ')', '', $title);
        }

        return $title;
    }

    function delete_novel($id)
    {
        $this->db->delete('stories', array('story_id' => $id));
    }


    function create_list($sort, $genre, $letter, $limit = 25, $offset = 0, $allow_unapproved = false)
    {
        $this->db->start_cache();
        $this->load->helper(array('title'));
        $clauses = array();

        if ($allow_unapproved == false) {
            $this->db->where("story_approved", '1');
        }

        if ($genre != 'all') {
            $this->db->where("(`story_primary_genre` = " . $this->db->escape($genre) . " OR `story_secondary_genre` = " . $this->db->escape($genre) . ")");
        }

        if ($letter != 'all') {
            $this->db->like('story_index_title', $letter, 'after');
        }

        switch ($sort) {
            case 1:
                $this->db->order_by("rating_order", "asc");
                break;
            case 2:
                $this->db->order_by("rating_order", "desc");
                break;
            case 3:
                $this->db->order_by("story_review_count", "desc");
                break;
            case 4:
                $this->db->order_by("story_review_count", "asc");
                break;
            case 5:
                $this->db->order_by("story_index_title", "asc");
                break;
        }

        $novels = array();
        $cc = 0;
        $this->db->where('not_in_index', 0);
        $this->db->from('stories');
        $this->db->stop_cache();
        $this->db->limit($limit, $offset);

        $query = $this->db->get('stories');
        /* die(print_r($this->db->queries)); */
        foreach ($query->result() as $row) {
            ++$cc;
            $novels[$cc] = array();
            $novels[$cc]['title'] = strtotitle($row->story_title);
            $novels[$cc]['subtitle'] = $row->story_subtitle;
            $novels[$cc]['summary'] = $row->story_summary;
            $novels[$cc]['primary_genre'] = $row->story_primary_genre;
            $novels[$cc]['secondary_genre'] = $row->story_secondary_genre;
            $novels[$cc]['update_schedule'] = $row->story_update_schedule;
            $novels[$cc]['url'] = $row->story_url;
            $novels[$cc]['purchase_link'] = $row->story_purchase_link;
            $novels[$cc]['rss_feed'] = $row->story_rss;
            $novels[$cc]['author_pen'] = $row->story_author;
            $novels[$cc]['author_url'] = $row->story_author_url;
            $novels[$cc]['listing_url'] = site_url($row->story_slug . '/');
            $novels[$cc]['tags'] = $row->story_tags;
            $novels[$cc]['slug'] = $row->story_slug;
            $novels[$cc]['views'] = $row->story_views;
            $novels[$cc]['review_count'] = $row->story_review_count;
            $novels[$cc]['mature'] = $row->story_mature;
            $novels[$cc]['thumbnail_url'] = 'http://open.thumbshots.org/image.aspx?url=' . urlencode($row->story_url);
            $novels[$cc]['rating'] = $row->story_weighted_rating;
        }

        $this->db->select('COUNT(`story_id`) as num');

        $clauses = array();

        if ($allow_unapproved == false) {
            $this->db->where("story_approved", '1');
        }

        if ($genre != 'all') {
            $this->db->where("(`story_primary_genre` = " . $this->db->escape($genre) . " or `story_secondary_genre` = " . $this->db->escape($genre) . ")");
        }

        if ($letter != 'all') {
            $this->db->like('story_index_title', $letter, 'after');
        }

        switch ($sort) {
            case 1:
                $this->db->order_by("rating_order", "asc");
                break;
            case 2:
                $this->db->order_by("rating_order", "desc");
                break;
            case 3:
                $this->db->order_by("story_review_count", "desc");
                break;
            case 4:
                $this->db->order_by("story_review_count", "asc");
                break;
            case 5:
                $this->db->order_by("story_index_title", "asc");
                break;
        }

        $query = $this->db->get('stories');
        $this->db->flush_cache();

        $novel_return = array();
        $novel_return['novels'] = $novels;
        $novel_return['num_novels'] = $query->row()->num;

        return $novel_return;
    }

    function list_novels($list_type, $param = '', $param2 = '', $limit = '10', $allow_unapproved = false, $cache = false)
    {
        if ($cache == true) {
            $this->db->cache_on();
        }

        if ($list_type == 'author' && $param != '') {
            $this->db->where('author_pen', $param);
        } elseif ($list_type == 'newest' && $param == '' && $param2 == '') {
            $this->db->order_by('story_id', 'DESC');
        } elseif ($list_type == 'highest_rated' && $param == '' && $param2 == '') {
            $this->db->order_by("rating_order", "ASC");
        } elseif ($list_type == 'genre' && $param != '' && $param2 == '') {
            $this->db->where('story_primary_genre', $param);
            $this->db->or_where('story_secondary_genre', $param);
        } elseif ($list_type == 'genre' && $param != '' && $param2 != '') {
            $this->db->where('story_primary_genre', $param);
            $this->db->or_where('story_secondary_genre', $param);
            $this->db->like('story_index_title', $param2, 'after');
        } elseif ($list_type == 'letter' && $param != '' && $param2 == '') {
            $this->db->like('story_index_title', $param, 'after');
        } elseif ($list_type == 'letter' && $param != '' && $param2 != '') {
            $this->db->like('story_index_title', $param, 'after');
            $this->db->where('story_primary_genre', $param2);
            $this->db->or_where('story_secondary_genre', $param2);
        } elseif ($list_type == 'undiscovered') {
            $this->db->where('`story_average_rating` > 3 AND `story_average_rating` < 7');
            $this->db->where('story_review_count', '0');
            $this->db->where('story_rating_count <= 1');
            $this->db->order_by('RAND()');
        } else {
            return 'You didn\'t select a valid listing-type.';
        }
        
        $this->db->where('not_in_index', 0);

        $novels = array();
        $cc = 0;
        if ($allow_unapproved == false) {
            $this->db->where('story_approved', '1');
        }
        $this->db->limit($limit);
        $query = $this->db->get('stories');

        foreach ($query->result() as $row) {
            ++$cc;
            $novels[$cc] = array();
            $novels[$cc]['title'] = $row->story_title;
            $novels[$cc]['subtitle'] = $row->story_subtitle;
            $novels[$cc]['summary'] = $row->story_summary;
            $novels[$cc]['primary_genre'] = $row->story_primary_genre;
            $novels[$cc]['secondary_genre'] = $row->story_secondary_genre;
            $novels[$cc]['update_schedule'] = $row->story_update_schedule;
            $novels[$cc]['url'] = $row->story_url;
            $novels[$cc]['rss_feed'] = $row->story_rss;
            $novels[$cc]['author_pen'] = $row->story_author;
            $novels[$cc]['author_url'] = $row->story_author_url;
            $novels[$cc]['listing_url'] = site_url($row->story_slug . '/');
            $novels[$cc]['tags'] = $row->story_tags;
            $novels[$cc]['views'] = $row->story_views;
            $novels[$cc]['review_count'] = $row->story_review_count;
            $novels[$cc]['added'] = $row->story_added;
            $novels[$cc]['rating'] = $row->story_weighted_rating;
            $novels[$cc]['mature'] = $row->story_mature;
            $novels[$cc]['thumbnail_url'] = 'http://open.thumbshots.org/image.aspx?url=' . urlencode($row->story_url);
            $novels[$cc]['purchase_link'] = $row->story_purchase_link;
        }

        if ($cache == true) {
            $this->db->cache_off();
        }

        return $novels;
    }



    function get_reviews($novel_id, $truncate = true)
    {
        $this->db->order_by('review_helpful_order', 'ASC');
        $query = $this->db->get_where('reviews', array('review_story' => intval($novel_id), 'review_approved' => '1', 'is_update' => '0'));

        $i = 0;
        $reviews = array();
        $this->load->helper('typography');
        foreach ($query->result() as $row) {
            ++$i;
            $reviews[$i] = array();
            $reviews[$i]['id'] = $row->review_id;
            $reviews[$i]['rating'] = $row->review_rating;
            $reviews[$i]['tagline'] = $row->review_tagline;
            if ($truncate == true) {
                $reviews[$i]['review_text'] = auto_typography($this->truncate($row->review_text, 255));
            } else {
                $reviews[$i]['review_text'] = auto_typography($row->review_text);
            }
            $author = $this->users->get_user_info($row->review_author);
            $reviews[$i]['author_id'] = $row->review_author;
            $helpful = $this->db->query('SELECT COUNT(`review_id`) as count FROM `revratings` WHERE `rating` = \'1\' AND `review_id` = \'' . $row->review_id . '\'');
            $nothelpful = $this->db->query('SELECT COUNT(`review_id`) as count FROM `revratings` WHERE `rating` = \'2\' AND `review_id` = \'' . $row->review_id . '\'');
            $reviews[$i]['helpful'] = $helpful->row()->count;
            $reviews[$i]['not_helpful'] = $nothelpful->row()->count;
            $reviews[$i]['author'] = ((strlen($author['display_name']) > 1) ? $author['display_name'] : $author['screen_name']);
            $reviews[$i]['url'] = site_url('reviews/view/' . $row->review_id);
        }


        return $reviews;
    }

    function get_review($review_id, $allow_unapproved = false)
    {
        if ($allow_unapproved == false) {
            $query = $this->db->get_where('reviews', array('review_id' => intval($review_id), 'review_approved' => '1'), 1);
        } else {
            $query = $this->db->get_where('reviews', array('review_id' => intval($review_id)), 1);
        }

        if ($query->num_rows() == 1) {
            $this->load->helper('typography');
            $row = $query->row();

            $this->db->select(array('display_name', 'screen_name'));
            $this->db->where('user_id', $row->review_author);
            $user_query = $this->db->get('users', 1);
            $user = $user_query->row();
            $review_author = (strlen($user->display_name) > 1) ? $user->display_name : $user->screen_name;


            $review = array();
            $review['id'] = $row->review_id;
            $review['tagline'] = $row->review_tagline;
            $review['author'] = '<a href="' . site_url('profile/view/' . $row->review_author . '') . '">' . $review_author . '</a>';
            $review['author_id'] = $row->review_author;
            $review['rating'] = $row->review_rating;
            $review['story_id'] = $row->review_story;
            $review['is_update'] = $row->is_update;
            $review['content'] = auto_typography($row->review_text);
            $review['url'] = site_url('reviews/view/' . $row->review_id);
            return $review;
        } else {
            return false;
        }
    }

    function truncate($string, $limit, $break = ".", $pad = "...")
    {
        // return with no change if string is shorter than $limit
        if (strlen($string) <= $limit)
        return $string;

        // is $break present between $limit and the end of the string?

        if (false !== ($breakpoint = strpos($string, $break, $limit))) {
            if ($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }

        return $string;
    }

    function list_unapproved_reviews()
    {
        $query = $this->db->get_where('reviews', array('review_approved' => '0'));
        $i = 0;
        $reviews = array();
        foreach ($query->result() as $row) {
            ++$i;
            $novel = $this->get_novel($row->review_story);
            $reviews[$i] = array();
            $reviews[$i]['id'] = $row->review_id;
            $reviews[$i]['rating'] = $row->review_rating;
            $reviews[$i]['story'] = '<a href="' . $novel['listing_url'] . '">' . $novel['title'] . '</a>';
            $reviews[$i]['tagline'] = $row->review_tagline;
            $reviews[$i]['author'] = $this->users->get_user_info($row->review_author);
            $reviews[$i]['word_count'] = str_word_count($row->review_text);
            $reviews[$i]['url'] = site_url('reviews/view/' . $row->review_id);
        }
        return $reviews;
    }

    function list_unapproved_novels()
    {
        $this->db->where('story_approved', '0');
        $this->db->or_where('story_approved', '');
        $query = $this->db->get('stories');

        $cc = 0;
        $novels = array();

        foreach ($query->result() as $row) {
            ++$cc;
            $novels[$cc] = array();
            $novels[$cc]['id'] = $row->story_id;
            $novels[$cc]['title'] = $row->story_title;
            $novels[$cc]['subtitle'] = $row->story_subtitle;
            $novels[$cc]['summary'] = $row->story_summary;
            $novels[$cc]['primary_genre'] = $row->story_primary_genre;
            $novels[$cc]['secondary_genre'] = $row->story_secondary_genre;
            $novels[$cc]['update_schedule'] = $row->story_update_schedule;
            $novels[$cc]['url'] = $row->story_url;
            $novels[$cc]['rss_feed'] = $row->story_rss;
            $novels[$cc]['author_pen'] = $row->story_author;
            $novels[$cc]['author_url'] = $row->story_author_url;
            $novels[$cc]['listing_url'] = site_url($row->story_slug . '/');
            $novels[$cc]['tags'] = $row->story_tags;
            $novels[$cc]['views'] = $row->story_views;
            $novels[$cc]['added'] = $row->story_added;
            $novels[$cc]['is_update'] = $row->is_update;
            $novels[$cc]['rating'] = $row->story_weighted_rating;
            $novels[$cc]['mature'] = $row->story_mature;
            $novels[$cc]['author'] = $this->users->get_user_info($row->story_submit_by);
        }

        return $novels;
    }

    function list_latest_reviews()
    {
        $query = $this->db->query('SELECT * FROM `reviews` WHERE `review_approved` = \'1\' ORDER BY `review_id` DESC LIMIT 0, 10');
        $reviews = array();
        $i = 0;

        $this->load->helper('typography');

        foreach ($query->result() as $row) {
            ++$i;
            $reviews[$i] = array();
            $reviews[$i]['tagline'] = $row->review_tagline;
            $reviews[$i]['review_url'] = site_url('reviews/view/' . $row->review_id . '/');
            $story = $this->db->query('SELECT `story_title` FROM `stories` WHERE `story_id` = \'' . $row->review_story . '\' LIMIT 1');
            $story_t = $story->row();
            $reviews[$i]['review_story'] = $story_t->story_title;
            $reviews[$i]['date'] = $row->review_date;
            $reviews[$i]['review_text'] = auto_typography(htmlspecialchars(strip_tags(html_entity_decode($row->review_text))));
			$reviews[$i]['review_text'] = str_replace('&nbsp;', '', $reviews[$i]['review_text']);
            
            $author = $this->db->query('SELECT `screen_name` FROM `users` WHERE `user_id` = \'' . $row->review_author . '\'');
            $review_by = $author->row();
            $reviews[$i]['review_by'] = $review_by->screen_name;
        }

        return $reviews;
    }

    function homepage_featured_fiction()
    {
        $where = array();
        $where['story_approved'] = '1';

        $this->db->order_by('rating_order', 'ASC');
        $this->db->where('`story_review_count` >= 1');
        $this->db->where('`story_weighted_rating` >= 7');
        $this->db->where('`story_summary` != \'\'');
        $this->db->where($where);
        $query = $this->db->get('stories', 20, 0);
        $this->load->helper('typography');

        $return_data['some_popular'] = '';

        return $return_data;
    }
}
?>