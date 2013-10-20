INSERT INTO `user_groups` (`g_id`, `g_admin_panel`, `g_title`, `g_user_title`, `g_create_listing`, `g_edit_listing`, `g_delete_listing`, `g_create_review`, `g_edit_review`, `g_delete_review`, `g_create_comment`, `g_edit_comment`, `g_delete_comment`, `g_suspend_user`, `g_ban_user`, `g_create_post`, `g_edit_post`, `g_delete_post`, `g_read_pm`, `g_send_pm`, `g_edit_pm`, `g_delete_pm`, `g_pm_inbox_size`, `g_edit_forums`, `g_report`, `g_rollback_changeset`, `g_edit_profile`, `g_rate_listing`, `g_rate_review`, `g_emails_visible`, `g_ip_address_visible`, `g_user_agent_visible`, `g_create_recommendation`, `g_edit_recommendation`, `g_delete_recommendation`) VALUES
(1, 0, 'Members', 'Reader', 1, 1, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 30, 0, 1, 0, 1, 1, 1, 1, 0, 0, 1, 0, 0),
(2, 1, 'Moderators', 'Moderator', 1, 2, 1, 1, 2, 1, 1, 2, 2, 1, 0, 1, 2, 3, 2, 1, 1, 1, 300, 0, 2, 1, 2, 1, 1, 1, 1, 1, 1, 2, 2),
(3, 2, 'Administrators', 'Administrator', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 30, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(4, 0, 'Guest', 'Guest', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 30, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);