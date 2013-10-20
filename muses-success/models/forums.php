<?php

class Forums extends Model {

        function get_forums()
        {
                $foras = array();
                $i = 0;
                $categories = $this->db->get_where('forum_categories', array('show_category' => '1'));
                foreach ($categories->result() as $category)
                {
                        ++$i;
                        $f = 0;
                        $foras[$i] = array();
                        $foras[$i]['id'] = $category->category_id;
                        $foras[$i]['title'] = $category->category_title;
                        $foras[$i]['forums'] = array();
                        $forums = $this->db->get_where('forum_boards', array('category_id' => $category->category_id, 'show_forum' => '1'));
                        foreach ($forums->result() as $forum)
                        {
                                ++$f;
                                $foras[$i]['forums'][$f] = array();
                                $foras[$i]['forums'][$f]['id'] = $forum->forum_id;
                                $foras[$i]['forums'][$f]['title'] = $forum->forum_title;
                                $foras[$i]['forums'][$f]['url'] = site_url('forums/viewforum/'.$forum->forum_id.'/');
                                $foras[$i]['forums'][$f]['description'] = $forum->forum_description;
                                $foras[$i]['forums'][$f]['topic_count'] = $forum->forum_topic_count;
                                $foras[$i]['forums'][$f]['post_count'] = $forum->forum_post_count;
                                $foras[$i]['forums'][$f]['last_post'] = (strlen($forum->forum_lastpost) != 1) ? date_relative($forum->forum_lastpost) : 'None yet.';
                        }
                }
                return $foras;
        }
        
        function get_forum($fora_id)
        {

                $forums = $this->db->get_where('forum_boards', array('forum_id' => intval($fora_id)), 1);
                if ($forums->num_rows() == 1)
                {
                        $forum = $forums->row();
                        $fora = array();
                        $fora['id'] = $forum->forum_id;
                        $fora['title'] = $forum->forum_title;
                        $fora['url'] = site_url('forums/viewforum/'.$forum->forum_id.'/');
                        $fora['description'] = $forum->forum_description;
                        $fora['topic_count'] = $forum->forum_topic_count;
                        $fora['post_count'] = $forum->forum_post_count;
                        $fora['last_post'] = (strlen($forum->forum_lastpost) != 1) ? date_relative($forum->forum_lastpost) : 'None yet.';

                        return $fora;

                } else {
                        return false;
                }
        }
        
        function get_category($cat_id)
        {
                $category = $this->db->get_where('forum_categories', array('category_id' => intval($cat_id)), 1);
                if ($category->num_rows() == 1)
                {
                        $category = $category->row();
                        $cat = array();
                        $cat['id'] = $category->category_id;
                        $cat['title'] = $category->category_title;

                        return $cat;

                } else {
                        return false;
                }
        }
        
        function get_topics($fora_id)
        {
                $t = 0;
                $topics = array();
                $this->db->where('forum_id', intval($fora_id));
                $this->db->from('forum_topics');
                $this->db->order_by('topic_last_post', 'DESC');
                
                $query = $this->db->get();
                foreach ($query->result() as $topic)
                {
                        ++$t;
                        $topics[$t] = array();
                        $topics[$t]['id'] = $topic->topic_id;
                        $topics[$t]['subject'] = $topic->topic_subject;
                                
                        $this->db->select(array('display_name', 'screen_name'));
                        $this->db->where('user_id', $topic->topic_by);
                        $user_query = $this->db->get('users', 1);
                        $user = $user_query->row();
                        $topic_author = (strlen($user->display_name) > 1) ? $user->display_name : $user->screen_name;

                        $topics[$t]['author'] = '<a href="'.site_url('profile/view/'.$topic->topic_by.'').'">'.$topic_author.'</a>';
                        $topics[$t]['url'] = site_url('forums/viewtopic/'.$topic->topic_id.'/');
                        $topics[$t]['post_count'] = round($topic->topic_post_count);
                        $topics[$t]['last_post'] = date_relative($topic->topic_last_post);
                }
                        
                return $topics;
        }
        
        function get_topic($topic_id)
        {
                $query = $this->db->get_where('forum_topics', array('topic_id' => $topic_id), 1);
                if ($query->num_rows() == 1)
                {
                        $topic_data = $query->row();

                        $this->db->select(array('display_name', 'screen_name'));
                        $this->db->where('user_id', $topic_data->topic_by);
                        $user_query = $this->db->get('users', 1);
                        $user = $user_query->row();
                        $topic_author = (strlen($user->display_name) > 1) ? $user->display_name : $user->screen_name;

                        $topic = array();
                        $topic['id'] = $topic_data->topic_id;
                        $topic['url'] = site_url('forums/viewtopic/'.$topic_data->topic_id.'/');
                        $topic['post_url'] = site_url('forums/post/message/'.$topic_data->topic_id.'/');
                        $topic['forum_id'] = $topic_data->forum_id;
                        $topic['subject'] = $topic_data->topic_subject;
                        $topic['author'] = '<a href="'.site_url('profile/view/'.$topic_data->topic_by.'').'">'.$topic_author.'</a>';
                        $topic['post_date'] = date_relative($topic_data->topic_date);
                        $topic['last_post'] = date_relative($topic_data->topic_last_post);
                        $topic['post_count'] = $topic_data->topic_post_count;

                        return $topic;
                        
                } else {
                        return false;
                }
        }
        
        function get_posts($topic_id)
        {

                $this->load->helper('bbcode');
                $this->load->helper('typography');

                $p = 0;
                $posts = array();
                $query = $this->db->get_where('forum_posts', array('topic_id' => intval($topic_id)));
                foreach ($query->result() as $post)
                {
                        ++$p;

                        $this->db->select(array('display_name', 'screen_name', 'email_address', 'signature', 'post_count', 'location', 'gender'));
                        $this->db->where('user_id', $post->user_id);
                        $user_query = $this->db->get('users', 1);
                        $user = $user_query->row();
                        $post_author = (strlen($user->display_name) > 1) ? $user->display_name : $user->screen_name;

                        if ($user->gender == 1) { $gender = 'Male'; } elseif ($user->gender == 2) { $gender = 'Female'; } else { $gender = 'Unknown'; }

                        $posts[$p] = array();
                        $posts[$p]['id'] = $post->post_id;
                        $posts[$p]['avatar'] = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($user->email_address)."&size=100&d=identicon";
                        $posts[$p]['author'] = '<a href="'.site_url('profile/view/'.$post->user_id.'').'">'.$post_author.'</a>';
                        $posts[$p]['user_id'] = $post->user_id;
                        $posts[$p]['user_gender'] = $gender;
                        $posts[$p] ['post_count'] = $user->post_count;
                        $posts[$p]['user_location'] = ($user->location != '') ? $user->location : '';
                        $posts[$p]['content'] = parse_bbcode(auto_typography($post->message));
                        $posts[$p]['signature'] = parse_bbcode($user->signature);
                        $posts[$p]['url'] = site_url('forums/viewpost/'.$post->post_id.'/');
                        $posts[$p]['post_date'] = date_relative($post->post_date);
                }

                return $posts;

        }

        function get_post($post_id)
        {
                $this->load->helper('bbcode');
                $this->load->helper('typography');

                $p = 0;
                $posts = array();
                $query = $this->db->get_where('forum_posts', array('post_id' => intval($post_id)));
                foreach ($query->result() as $post)
                {
                        ++$p;

                        $this->db->select(array('display_name', 'screen_name', 'email_address', 'signature', 'post_count', 'location', 'gender'));
                        $this->db->where('user_id', $post->user_id);
                        $user_query = $this->db->get('users', 1);
                        $user = $user_query->row();
                        $post_author = (strlen($user->display_name) > 1) ? $user->display_name : $user->screen_name;

                        if ($user->gender == 1) { $gender = 'Male'; } elseif ($user->gender == 2) { $gender = 'Female'; } else { $gender = 'Unknown'; }

                        $posts[$p] = array();
                        $posts[$p]['id'] = $post->post_id;
                        $posts[$p]['avatar'] = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($user->email_address)."&size=100&d=identicon";
                        $posts[$p]['author_name'] = $post_author;
                        $posts[$p]['author'] = '<a href="'.site_url('profile/view/'.$post->user_id.'').'">'.$post_author.'</a>';
                        $posts[$p]['content'] = parse_bbcode(auto_typography($post->message));
                        $posts[$p]['content_notparse'] = $post->message;
                        $posts[$p]['user_gender'] = $gender;
                        $posts[$p] ['post_count'] = $user->post_count;
                        $posts[$p]['user_location'] = ($user->location != '') ? $user->location : '';
                        $posts[$p]['signature'] = parse_bbcode($user->signature);
                        $posts[$p]['url'] = site_url('forums/viewpost/'.$post->post_id.'/');
                        $posts[$p]['post_date'] = date('d/m/Y h:iA', $post->post_date);
                        $posts[$p]['topic_id'] = $post->topic_id;
                }

                return $posts[1];
        }
        
        function add_topic($subject, $author, $forum_id, $content)
        {
                $data = array(
                        'topic_subject' => htmlspecialchars(trim($subject)),
                        'topic_by' => intval($author),
                        'forum_id' => intval($forum_id),
                        'topic_date' => time(),
                        'topic_last_post' => time(),
                        'topic_status' => 'open',
                        'topic_post_count' => '1'
                        );
                $this->db->insert('forum_topics', $data);
                
                $topic_id = $this->db->insert_id();
                
                $post = array(
                        'topic_id' => $topic_id,
                        'message' => htmlspecialchars($content),
                        'user_id' => intval($author),
                        'post_date' => time()
                        );

                $this->db->insert('forum_posts', $post);
                
                $this->db->query('UPDATE `forum_boards` SET `forum_post_count` = (`forum_post_count` + 1), `forum_topic_count` = (`forum_topic_count` + 1) WHERE `forum_id` = \''.intval($forum_id).'\' LIMIT 1');
                $this->db->query('UPDATE `users` SET `post_count` = (`post_count` + 1) WHERE `user_id` = \''.intval($author).'\' LIMIT 1');
                
                return $topic_id;
        }
        
        function add_post($topic_id, $forum_id, $author, $content)
        {

                $post = array(
                        'topic_id' => $topic_id,
                        'message' => htmlspecialchars($content),
                        'user_id' => intval($author),
                        'post_date' => time()
                        );

                $this->db->insert('forum_posts', $post);

                $this->db->query('UPDATE `forum_boards` SET `forum_post_count` = (`forum_post_count` + 1), `forum_lastpost` = \''.time().'\' WHERE `forum_id` = \''.intval($forum_id).'\' LIMIT 1');
                $this->db->query('UPDATE `forum_topics` SET `topic_post_count` = (`topic_post_count` + 1), `topic_last_post` = \''.time().'\' WHERE `topic_id` = \''.intval($topic_id).'\' LIMIT 1');
                $this->db->query('UPDATE `users` SET `post_count` = (`post_count` + 1) WHERE `user_id` = \''.intval($author).'\' LIMIT 1');

                return $topic_id;
        
        }
        
        function delete_topic($topic_id)
        {
                $post = $this->get_posts($topic_id);
                foreach ($post as $post_a)
                {
                        $author = $post_a['user_id'];
                        $this->db->query('UPDATE `users` SET `post_count` = (`post_count` - 1) WHERE `user_id` = \''.intval($author).'\' LIMIT 1');
                }
                
                $this->db->where('topic_id', $topic_id);
                $this->db->delete('forum_topics');
                $this->db->where('topic_id', $topic_id);
                $this->db->delete('forum_posts');
                return true;
        }

        function delete_post($post_id)
        {
                $post = $this->get_post($post_id);
                $this->db->query('UPDATE `users` SET `post_count` = (`post_count` - 1) WHERE `user_id` = \''.intval($post['user_id']).'\' LIMIT 1');
                $this->db->where('post_id', $post_id);
                $this->db->delete('forum_posts');
                return true;
        }

}

?>
