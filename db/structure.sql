
-- --------------------------------------------------------

--
-- Table structure for table `alternate_tag_parents`
--

CREATE TABLE IF NOT EXISTS `alternate_tag_parents` (
  `alt_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `tag_parent` int(11) NOT NULL,
  PRIMARY KEY (`alt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `changes`
--

CREATE TABLE IF NOT EXISTS `changes` (
  `change_id` int(11) NOT NULL AUTO_INCREMENT,
  `change_set_id` int(11) NOT NULL,
  `change_table` varchar(40) NOT NULL,
  `change_listing_id` int(11) NOT NULL,
  `change_field` varchar(40) NOT NULL,
  `change_value_before` longblob NOT NULL,
  `change_value_after` longblob NOT NULL,
  `change_value_type` set('edit','replace','addition','deletion') NOT NULL DEFAULT 'edit',
  PRIMARY KEY (`change_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `changesets`
--

CREATE TABLE IF NOT EXISTS `changesets` (
  `changeset_id` int(11) NOT NULL AUTO_INCREMENT,
  `changeset_story_id` int(11) NOT NULL,
  `changeset_user_id` int(11) NOT NULL,
  `changeset_date` int(11) NOT NULL,
  `changeset_comment` text NOT NULL,
  `changeset_type` enum('added','edited','deleted') NOT NULL,
  `changeset_points` int(11) NOT NULL DEFAULT '5',
  `changeset_voters` text,
  PRIMARY KEY (`changeset_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_text` text NOT NULL,
  `listing_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` text NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faqs_categories`
--

CREATE TABLE IF NOT EXISTS `faqs_categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` text NOT NULL,
  `cat_order` int(11) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faqs_questions`
--

CREATE TABLE IF NOT EXISTS `faqs_questions` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_question` text NOT NULL,
  `question_answer` longtext NOT NULL,
  `question_category` int(11) NOT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `library`
--

CREATE TABLE IF NOT EXISTS `library` (
  `library_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `book_status` enum('current','planned','onhold','complete','dropped') NOT NULL,
  `book_rating` int(2) NOT NULL,
  `chapters_read` int(11) NOT NULL,
  `library_user` int(11) NOT NULL,
  PRIMARY KEY (`library_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` text NOT NULL,
  `page_slug` varchar(100) NOT NULL,
  `page_content` longtext NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pms`
--

CREATE TABLE IF NOT EXISTS `pms` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_subject` text NOT NULL,
  `message_body` longtext NOT NULL,
  `message_to` int(11) NOT NULL,
  `message_from` int(11) NOT NULL,
  `message_date` int(11) NOT NULL,
  `read` int(11) NOT NULL DEFAULT '0',
  `trash` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `publications`
--

CREATE TABLE IF NOT EXISTS `publications` (
  `publication_id` int(11) NOT NULL AUTO_INCREMENT,
  `novel_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  PRIMARY KEY (`publication_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `story_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rated` int(2) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  PRIMARY KEY (`rating_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

CREATE TABLE IF NOT EXISTS `recommendations` (
  `recommend_id` int(11) NOT NULL AUTO_INCREMENT,
  `listing_a` int(11) NOT NULL,
  `listing_b` int(11) NOT NULL,
  `comment` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`recommend_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `relations`
--

CREATE TABLE IF NOT EXISTS `relations` (
  `relation_id` int(11) NOT NULL,
  `relation_book_id` int(11) NOT NULL,
  `relation_related_book_id` int(11) NOT NULL,
  `relation_type` enum('sequel','prequel','same setting','shares characters','side story','parent story','same series','original story') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `report_user` int(11) NOT NULL,
  `report_type` varchar(40) NOT NULL,
  `report_reason` int(11) NOT NULL,
  `report_primary_id` int(11) NOT NULL,
  `report_date` int(11) NOT NULL,
  `report_actioned` int(11) NOT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `review_tagline` varchar(150) NOT NULL,
  `review_rating` int(2) NOT NULL,
  `review_text` longtext NOT NULL,
  `review_story` int(11) NOT NULL,
  `review_author` int(11) NOT NULL,
  `review_approved` set('0','1') NOT NULL DEFAULT '0',
  `is_update` int(1) NOT NULL DEFAULT '0',
  `review_helpful_count` int(11) NOT NULL DEFAULT '0',
  `review_not_helpful_count` int(11) NOT NULL DEFAULT '0',
  `review_helpful_order` int(11) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) DEFAULT NULL,
  `review_date` int(11) NOT NULL,
  PRIMARY KEY (`review_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `revratings`
--

CREATE TABLE IF NOT EXISTS `revratings` (
  `rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` set('1','2') NOT NULL,
  PRIMARY KEY (`rating_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `search_items`
--

CREATE TABLE IF NOT EXISTS `search_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_url` text NOT NULL,
  `item_type` enum('url','listing','review','listing_rss') NOT NULL,
  `item_relation_id` int(11) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `search_occurences`
--

CREATE TABLE IF NOT EXISTS `search_occurences` (
  `occurence_id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`occurence_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `search_terms`
--

CREATE TABLE IF NOT EXISTS `search_terms` (
  `search_id` int(11) NOT NULL AUTO_INCREMENT,
  `search_term` varchar(255) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  PRIMARY KEY (`search_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `search_words`
--

CREATE TABLE IF NOT EXISTS `search_words` (
  `word_id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(100) NOT NULL,
  PRIMARY KEY (`word_id`),
  UNIQUE KEY `word_2` (`word`),
  FULLTEXT KEY `word` (`word`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

CREATE TABLE IF NOT EXISTS `stories` (
  `story_id` int(11) NOT NULL AUTO_INCREMENT,
  `story_title` text NOT NULL,
  `story_index_title` text NOT NULL,
  `story_subtitle` text NOT NULL,
  `story_summary` longtext NOT NULL,
  `story_author` text NOT NULL,
  `story_author_url` text NOT NULL,
  `story_url` text NOT NULL,
  `story_rss` text NOT NULL,
  `story_primary_genre` varchar(30) NOT NULL,
  `story_secondary_genre` varchar(30) NOT NULL,
  `story_mature` set('0','1') NOT NULL,
  `story_views` int(11) NOT NULL,
  `story_added` int(11) NOT NULL,
  `story_update_schedule` text NOT NULL,
  `story_mature_coarse` int(1) NOT NULL,
  `story_mature_sex` int(1) NOT NULL,
  `story_mature_violence` int(1) NOT NULL,
  `story_audience` text NOT NULL,
  `story_tags` text NOT NULL,
  `story_approved` set('-1','0','1') NOT NULL DEFAULT '0',
  `story_slug` varchar(100) NOT NULL,
  `story_average_rating` float NOT NULL,
  `story_weighted_rating` float NOT NULL,
  `story_rating_count` int(11) NOT NULL,
  `story_review_count` int(11) NOT NULL,
  `story_submit_by` int(11) NOT NULL,
  `story_credit` text NOT NULL,
  `story_thumbnail_url` text NOT NULL,
  `is_update` int(11) NOT NULL DEFAULT '0',
  `update_to` int(11) NOT NULL DEFAULT '0',
  `rating_order` int(11) NOT NULL,
  `story_purchase_link` text NOT NULL,
  `chapter_total` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `thumb_exists` int(11) NOT NULL DEFAULT '0',
  `story_rating_user_average` float NOT NULL,
  `story_rating_guest_average` float NOT NULL,
  `not_in_index` int(1) NOT NULL DEFAULT '0',
  `story_feature_image_position` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`story_id`),
  FULLTEXT KEY `story_summary` (`story_summary`),
  FULLTEXT KEY `story_title` (`story_title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tagged`
--

CREATE TABLE IF NOT EXISTS `tagged` (
  `tagged_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `story_id` int(11) NOT NULL,
  PRIMARY KEY (`tagged_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_term` varchar(200) NOT NULL,
  `tag_description` text NOT NULL,
  `tag_slug` varchar(40) NOT NULL,
  `tag_parent` int(11) NOT NULL,
  `tag_usable` int(1) NOT NULL DEFAULT '1',
  `tag_count` int(11) NOT NULL DEFAULT '0',
  `tag_added_date` int(11) NOT NULL,
  `tag_approved` int(1) NOT NULL,
  `tag_user_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `tag_term` (`tag_term`),
  FULLTEXT KEY `tag_term_2` (`tag_term`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tag_aliases`
--

CREATE TABLE IF NOT EXISTS `tag_aliases` (
  `alias_id` int(11) NOT NULL AUTO_INCREMENT,
  `alias_name` varchar(255) NOT NULL,
  `alias_tag` int(11) NOT NULL,
  `alias_slug` varchar(300) NOT NULL,
  PRIMARY KEY (`alias_id`),
  UNIQUE KEY `alias_slug` (`alias_slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tag_types`
--

CREATE TABLE IF NOT EXISTS `tag_types` (
  `tt_id` int(11) NOT NULL AUTO_INCREMENT,
  `tt_name` varchar(100) NOT NULL,
  `tt_parent` int(11) NOT NULL,
  PRIMARY KEY (`tt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `thumbnail_reset`
--

CREATE TABLE IF NOT EXISTS `thumbnail_reset` (
  `thumbnailreset_id` int(11) NOT NULL AUTO_INCREMENT,
  `story_id` int(11) NOT NULL,
  `request_date` int(11) NOT NULL,
  `request_status` enum('requested','rejected','fulfilled') NOT NULL DEFAULT 'requested',
  PRIMARY KEY (`thumbnailreset_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `updatepass`
--

CREATE TABLE IF NOT EXISTS `updatepass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `check_id` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `email_address` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE IF NOT EXISTS `updates` (
  `update_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `update_type` int(11) NOT NULL,
  `update_date` int(11) NOT NULL,
  `update_rel_id` int(11) NOT NULL,
  `update_text` text NOT NULL,
  `update_title` text NOT NULL,
  `update_link` text NOT NULL,
  PRIMARY KEY (`update_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `screen_name` varchar(25) NOT NULL,
  `openid_url` text NOT NULL,
  `openid_account` set('0','1','2') NOT NULL,
  `password` varchar(64) NOT NULL,
  `secure_salt` varchar(10) NOT NULL,
  `ip_address` varchar(16) NOT NULL,
  `email_address` text NOT NULL,
  `show_email` set('0','1') NOT NULL,
  `registration_date` int(11) NOT NULL,
  `access_level` int(11) NOT NULL,
  `user_group` int(11) NOT NULL DEFAULT '1',
  `display_name` varchar(25) NOT NULL,
  `website_url` text NOT NULL,
  `location` varchar(50) NOT NULL,
  `windowslive_im` varchar(320) NOT NULL,
  `yahoo_im` varchar(32) NOT NULL,
  `aol_im` varchar(16) NOT NULL,
  `google_im` varchar(320) NOT NULL,
  `gender` set('0','1','2') NOT NULL,
  `signature` text NOT NULL,
  `wp_flag` int(1) NOT NULL,
  `contrib_points` int(11) NOT NULL DEFAULT '0',
  `contrib_change_count` int(11) NOT NULL,
  `inbox_unread` int(11) NOT NULL DEFAULT '0',
  `post_count` int(11) NOT NULL DEFAULT '0',
  `review_count` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(200) NOT NULL,
  `last_access` int(11) NOT NULL,
  `upload_thumbs` int(11) NOT NULL DEFAULT '0',
  `story_rating_user_average` float NOT NULL,
  `story_rating_guest_average` float NOT NULL,
  `gender_new` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `g_id` int(11) NOT NULL AUTO_INCREMENT,
  `g_admin_panel` int(1) NOT NULL,
  `g_title` varchar(50) NOT NULL,
  `g_user_title` varchar(50) NOT NULL,
  `g_create_listing` tinyint(1) NOT NULL DEFAULT '0',
  `g_edit_listing` tinyint(1) NOT NULL DEFAULT '0',
  `g_delete_listing` tinyint(1) NOT NULL DEFAULT '0',
  `g_create_review` tinyint(1) NOT NULL DEFAULT '0',
  `g_edit_review` tinyint(1) NOT NULL DEFAULT '0',
  `g_delete_review` tinyint(1) NOT NULL DEFAULT '0',
  `g_create_comment` tinyint(1) NOT NULL DEFAULT '0',
  `g_edit_comment` tinyint(1) NOT NULL DEFAULT '0',
  `g_delete_comment` tinyint(1) NOT NULL DEFAULT '0',
  `g_suspend_user` tinyint(1) NOT NULL DEFAULT '0',
  `g_ban_user` tinyint(1) NOT NULL DEFAULT '0',
  `g_create_post` tinyint(1) NOT NULL DEFAULT '0',
  `g_edit_post` tinyint(1) NOT NULL DEFAULT '0',
  `g_delete_post` tinyint(1) NOT NULL DEFAULT '0',
  `g_read_pm` tinyint(1) NOT NULL DEFAULT '0',
  `g_send_pm` tinyint(1) NOT NULL DEFAULT '0',
  `g_edit_pm` tinyint(1) NOT NULL DEFAULT '0',
  `g_delete_pm` tinyint(1) NOT NULL DEFAULT '0',
  `g_pm_inbox_size` int(11) NOT NULL DEFAULT '30',
  `g_edit_forums` tinyint(1) NOT NULL DEFAULT '0',
  `g_report` tinyint(1) NOT NULL DEFAULT '0',
  `g_rollback_changeset` tinyint(1) NOT NULL DEFAULT '0',
  `g_edit_profile` tinyint(1) NOT NULL DEFAULT '0',
  `g_rate_listing` tinyint(1) NOT NULL DEFAULT '0',
  `g_rate_review` tinyint(1) NOT NULL DEFAULT '0',
  `g_emails_visible` tinyint(1) NOT NULL DEFAULT '0',
  `g_ip_address_visible` tinyint(1) NOT NULL DEFAULT '0',
  `g_user_agent_visible` tinyint(1) NOT NULL DEFAULT '0',
  `g_create_recommendation` tinyint(1) NOT NULL DEFAULT '0',
  `g_edit_recommendation` tinyint(1) NOT NULL DEFAULT '0',
  `g_delete_recommendation` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`g_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Table structure for table `contrib_point_record`
--

CREATE TABLE IF NOT EXISTS `contrib_point_record` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_amount` int(11) NOT NULL,
  `record_amount_result` int(11) NOT NULL,
  `record_type` enum('addition','subtraction') NOT NULL,
  `record_reason` varchar(255) NOT NULL,
  `record_object_type` varchar(20) NOT NULL,
  `record_object_id` int(11) NOT NULL,
  `record_user_id` int(11) NOT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;