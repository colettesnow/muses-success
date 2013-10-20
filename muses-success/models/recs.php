<?php

class Recs extends Model
{

	function list_novels()
	{

		$this->db->distinct();
		$this->db->select('story_id, story_title as title, story_author as author');
		$this->db->from('library');
		$this->db->where('library_user', $this->users->cur_user['user_id']);
		$this->db->join('stories', 'book_id = story_id', 'left');
		$this->db->order_by('story_title', 'ASC');
		$result = $this->db->get();

		return $result->result_array();

	}

	function add_recommendation($listing1, $listing2, $comment)
	{

		$data = array('listing_a' => $listing1, 'listing_b' => $listing2, 'date' => time(), 'comment' => $comment, 'user_id' => $this->users->cur_user['user_id']);

		$this->db->insert('recommendations', $data);

		return true;

	}

	function list_recommendations($book_id, $limit = 20, $offset = 0)
	{

		$this->load->helper('markdown');

		$this->db->select('storya.story_title as listing_title, storya.story_id as listing_id, storyb.story_id as similar_id, storyb.story_title as similar_title, storya.story_slug as listing_slug, storyb.story_slug as similar_slug, comment, screen_name, users.user_id, recommend_id');
		$this->db->where('listing_a', $book_id);
		$this->db->or_where('listing_b', $book_id);
		$this->db->join('stories as storya', 'listing_a = storya.story_id', 'left');
		$this->db->join('stories as storyb', 'listing_b = storyb.story_id', 'left');
		$this->db->join('users', 'recommendations.user_id = users.user_id', 'left');
		$this->db->from('recommendations');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();

		$recommendation = array();

		$i = 0;
		foreach ($query->result() as $rec)
		{
			++$i;

			$recommendation[$i] = array();
			$recommendation[$i]['id'] = $rec->recommend_id;
			$recommendation[$i]['by'] = '<a href="'.site_url('profile/view/'.$rec->user_id.'').'">'.$rec->screen_name.'</a>';
			$recommendation[$i]['user_id'] = $rec->user_id;
			if ($book_id == $rec->listing_id)
			{
				$recommendation[$i]['listing'] = '<a href="'.site_url($rec->listing_slug).'">'.$rec->listing_title.'</a>';
				$recommendation[$i]['listing_id'] = $rec->listing_id;
				$recommendation[$i]['similar_to'] = '<a href="'.site_url($rec->similar_slug).'">'.$rec->similar_title.'</a>';
				$recommendation[$i]['similar_id'] = $rec->similar_id;
			} else {
				$recommendation[$i]['listing'] = '<a href="'.site_url($rec->similar_slug).'">'.$rec->similar_title.'</a>';
				$recommendation[$i]['listing_id'] = $rec->similar_id;
				$recommendation[$i]['similar_to'] = '<a href="'.site_url($rec->listing_slug).'">'.$rec->listing_title.'</a>';
				$recommendation[$i]['similar_id'] = $rec->listing_id;
			}
			$recommendation[$i]['comment'] = parse_markdown($rec->comment);

		}

		return $recommendation;

	}

	function recent($limit = 20)
	{
		$this->load->helper('markdown');

		$this->db->select('storya.story_title as listing_title, storya.story_url as listing_read_url, storyb.story_url as similar_read_url , recommendations.recommend_id as rec_id, recommendations.date as date_when, storya.story_id as listing_id, storyb.story_id as similar_id, storyb.story_title as similar_title, storya.story_slug as listing_slug, storyb.story_slug as similar_slug, comment, screen_name, users.user_id');
		$this->db->join('stories as storya', 'listing_a = storya.story_id', 'left');
		$this->db->join('stories as storyb', 'listing_b = storyb.story_id', 'left');
		$this->db->join('users', 'recommendations.user_id = users.user_id', 'left');
		$this->db->from('recommendations');
		$this->db->limit($limit);
		$this->db->order_by('recommend_id', 'DESC');
		$query = $this->db->get();

		$recommendation = array();

		$i = 0;
		foreach ($query->result() as $rec)
		{
			++$i;

			$recommendation[$i] = array();
			$recommendation[$i]['id'] = $rec->rec_id;
			$recommendation[$i]['user_id'] = $rec->user_id;
			$recommendation[$i]['by'] = '<a href="'.site_url('profile/view/'.$rec->user_id.'').'">'.$rec->screen_name.'</a>';
			$recommendation[$i]['listing'] = '<a href="'.site_url($rec->listing_slug).'">'.$rec->listing_title.'</a>';
			$recommendation[$i]['listing_url'] = site_url($rec->listing_slug);
			$recommendation[$i]['listing_slug'] = $rec->listing_slug;
			$recommendation[$i]['listing_id'] = $rec->listing_id;
			$recommendation[$i]['similar_slug'] = $rec->similar_slug;
			$recommendation[$i]['similar_to'] = '<a href="'.site_url($rec->similar_slug).'">'.$rec->similar_title.'</a>';
			$recommendation[$i]['similar_url'] = site_url($rec->similar_slug);
			$recommendation[$i]['similar_id'] = $rec->similar_id;
			$recommendation[$i]['comment'] = parse_markdown($rec->comment);
			$recommendation[$i]['date'] = date_relative($rec->date_when);
			$recommendation[$i]['date_raw'] = ($rec->date_when);
		}

		return $recommendation;
	}

}

?>
