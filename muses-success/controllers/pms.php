<?php

class PMS extends Controller {

        public $user_id = 0;

        function PMS() {
                parent::Controller();
        }

        function index()
        {
                if ($this->users->logged_in == false)
                        redirect('accounts/login');

                $this->db->order_by('message_date', 'DESC');
                $query = $this->db->get_where('pms', array('message_to' => $this->users->cur_user['user_id'], 'trash' => '0'));

                $results = $query->result_array();

                $data = array('pms' => $results);

                $c = 0;
                foreach($results as $del_test)
                {
                        ++$c;
                        if ($c >= 30)
                        {
                                $this->db->where('message_id', $del_test['message_id']);
                                $this->db->update('pms', array('trash' => '1', 'read' => '0'));
                        }
                }

                $header_data = array(
                	'page_title' => 'Private Messages - Inbox',
                	'breadcrumbs' => array(
                						'Private Messages'						
                )
                );
                
                $this->load->view('header', $header_data);
                $this->load->view('pms/inbox', $data);
                $this->load->view('footer');
        }

        function sent()
        {
                if ($this->users->logged_in == false)
                        redirect('accounts/login');

                $this->db->order_by('message_date', 'DESC');
                $query = $this->db->get_where('pms', array('message_from' => $this->users->cur_user['user_id'], 'trash' => '0', 'read' => '1'));

                $results = $query->result_array();

                $data = array('pms' => $results);

                $header_data = array(
                	'page_title' => 'Private Messages - Sent Data',
                	'breadcrumbs' => array(
                						'<a href="'.site_url('pms').'">Private Messages</a>',
                						'Sent Messages'							
                )
                );                
                
                $this->load->view('header', $header_data);
                $this->load->view('pms/sent', $data);
                $this->load->view('footer');
        }

        function trash()
        {
                if ($this->users->logged_in == false)
                        redirect('accounts/login');

                $this->db->order_by('message_date', 'DESC');
                $query = $this->db->get_where('pms', array('message_to' => $this->users->cur_user['user_id'], 'trash' => '1'));

                $results = $query->result_array();

                $data = array('pms' => $results);

                $header_data = array(
                	'page_title' => 'Private Messages - Trash',
                	'breadcrumbs' => array(
                						'<a href="'.site_url('pms').'">Private Messages</a>',
                						'Trash'							
                )
                );
                
                $this->load->view('header', $header_data);
                $this->load->view('pms/trash', $data);
                $this->load->view('footer');
        }

        function outbox()
        {
                if ($this->users->logged_in == false)
                        redirect('accounts/login');

                $this->db->order_by('message_date', 'DESC');
                $query = $this->db->get_where('pms', array('message_from' => $this->users->cur_user['user_id'], 'trash' => '0', 'read' => '0'));

                $results = $query->result_array();

                $data = array('pms' => $results);
                $header_data = array(
                	'page_title' => 'Private Messages - Outbox',
                	'breadcrumbs' => array(
                						'<a href="'.site_url('pms').'">Private Messages</a>',
                						'Outbox'							
                )
                );
                $this->load->view('header', $header_data);
                $this->load->view('pms/outbox', $data);
                $this->load->view('footer');
        }
        
        function view()
        {
                if ($this->users->logged_in == false)
                        redirect('accounts/login');
                        
                $pm_id = $this->uri->segment(3);

                $this->db->where('message_id', intval($pm_id));
                $this->db->where('message_to', $this->users->cur_user['user_id']);
                $this->db->or_where('message_from', $this->users->cur_user['user_id']);
                $this->db->where('message_id', intval($pm_id));
                $this->db->from('pms');
                $get_pm = $this->db->get();

                if ($get_pm->num_rows() == 1)
                {
                        $data['item'] = $get_pm->row_array();
                        
                        if ($data['item']['read'] == '0' && $data['item']['message_to'] == $this->users->cur_user['user_id'])
                        {
                                $this->db->query('UPDATE `users` SET `inbox_unread` = (`inbox_unread` - 1) WHERE `user_id` = \''.$this->users->cur_user['user_id'].'\' LIMIT 1');
                                $this->db->where('message_id', intval($pm_id));
                                $this->db->update('pms', array('read' => '1'));
                        }
                        
                        $header_data = array(
                	'page_title' => 'Private Messages - Inbox',
                	'breadcrumbs' => array(
                						'<a href="'.site_url('pms').'">Private Messages</a>',
                						'View Message - '.$data['item']['message_subject'].''
                                    )
               			      );
                        
                        $this->load->view('header', $header_data);
                        $this->load->view('pms/view', $data);
                        $this->load->view('footer');
                } else {
                        show_404();
                }
                        
        }
        
        function flood_protection()
        {
                $x = 0;
                $result = $this->db->query('SELECT COUNT(`message_id`) AS num FROM pms WHERE message_date >= ' . (time() - 86400) . ' AND message_from = ' . $this->users->cur_user['user_id'] . '');
                $tops_boa_last_day = $result->row();
                if ($tops_boa_last_day->num >= 10)
                {
                        $x = 1;
                        $this->validation->set_message('flood_protection','To prevent flooding, users cannot send more than 25 messages per day. Please wait and try sending your message again tomorrow.');
                        return false;
                }
                $result = $this->db->query('SELECT COUNT(`message_id`) AS num FROM pms WHERE message_date >= ' . (time() - 60) . ' AND message_from = ' . $this->users->cur_user['user_id'] . '');
                $tops_boa_last_day = $result->row();
                if ($tops_boa_last_day->num >= 2)
                {
                        $x = 1;
                        $this->validation->set_message('flood_protection','To prevent flooding, users cannot send more than 2 messages per minute. Please wait and try sending your message again later.');
                        return false;
                }
                if ($x = 0)
                {
                        return true;
                }
        }
        
        function compose()
        {
                if ($this->users->logged_in == false)
                        redirect('accounts/login');

                $this->load->library('validation');
                
                $send_to = $this->uri->segment(3);
                $reply_to = $this->uri->segment(4);
                
                $to = '';
                if (isset($send_to) && is_numeric($send_to))
                {
                        $to = intval($send_to);
                }
                $subject = '';
                if (isset($reply_to) && is_numeric($reply_to))
                {
                        $reply_query = $this->db->get_where('pms', array('message_id' => $reply_to));
                        $reply = $reply_query->row();
                        $subject = 'Re: '.$reply->message_subject.'';
                }

                $rules['to'] = 'required|alpha_dash|callback_check_user';
                $rules['subject'] = 'min_length[5]|max_length[80]|required';
                $rules['message'] = 'min_length[5]|max_length[4096]|required';
                $rules['send'] = 'callback_flood_protection';
                $this->validation->set_rules($rules);

                $fields['to'] = 'Send To';
                $fields['subject'] = 'Subject';
                $fields['message'] = 'Message Text';
                $this->validation->set_fields($fields);

                $form = array(
                        'to' => $this->set_field_value('to', $to),
                        'subject' => $this->set_field_value('subject', $subject),
                        'message' => $this->set_field_value('message', '')
                );

                if ($this->validation->run() == FALSE)
                {
                        $page_data['page_title'] = 'Compose Private Message';
                        $page_data['forum_markup'] = true;
                        $page_data['breadcrumbs'] = array(
                						'<a href="'.site_url('pms').'">Private Messages</a>',
                						'Compose Message'							
                                       );                    
                    
                        $this->load->view('header', $page_data);
                        $this->load->view('pms/compose', $form);
                        $this->load->view('footer');
                } else {
                        $data = array(
                                'message_from' => $this->users->cur_user['user_id'],
                                'message_to' => $this->user_id,
                                'message_subject' => htmlentities(trim($this->input->post('subject', true))),
                                'message_body' => htmlentities(trim($this->input->post('message', true))),
                                'message_date' => time(),
                                'read' => '0',
                                'trash' => '0'
                        );
                        
                        $this->db->insert('pms', $data);
                        $this->db->query('UPDATE `users` SET `inbox_unread` = (`inbox_unread` + 1) WHERE `user_id` = \''.$this->user_id.'\' LIMIT 1');

                        $this->load->view('header');
                        $this->load->view('pms/send_success');
                        $this->load->view('footer');
                }
        }
        
        function check_user($user)
        {
        
                if (is_numeric($user))
                {
                        $check = $this->db->get_where('users', array('user_id' => intval($user)), 1);
                        if ($check->num_rows() == 0)
                        {
                                $this->validation->set_message('check_user','You did not specify a valid user to send a message to.');
                                return false;
                        } else {
                                $this->user_id = intval($user);
                                return true;
                        }
                } else {
                        $this->db->where('screen_name', $user);
                        $this->db->or_where('display_name', $user);
                        $this->db->from('users');
                        $check = $this->db->get();
                        if ($check->num_rows() == 0)
                        {
                                $this->validation->set_message('check_user','You did not specify a valid user to send a message to.');
                                return false;
                        } else {
                                $check_to = $check->row();
                                $this->user_id = $check_to->user_id;
                                return true;
                        }
                }
        
        }
        
        function delete()
        {
                if ($this->users->logged_in == false)
                        redirect('accounts/login');
                        
                $_MESSAGES = is_array($_POST['pm']) ? $_POST['pm'] : array();
                
                foreach ($_MESSAGES as $pm)
                {
                        $this->db->where('message_to', $this->users->cur_user['user_id']);
                        $this->db->where('message_id', $pm);
                        $this->db->update('pms', array('trash' => '1'));
                }

                $this->load->view('header');
                $this->load->view('pms/trash_success');
                $this->load->view('footer');

        }
        
        function permadelete()
        {
                if ($this->users->logged_in == false)
                        redirect('accounts/login');

                if ($this->input->post('delete') == 'Delete Select')
                {

                $_MESSAGES = is_array($_POST['pm']) ? $_POST['pm'] : array();

                foreach ($_MESSAGES as $pm)
                {
                        $this->db->where('message_to', $this->users->cur_user['user_id']);
                        $this->db->where('message_id', $pm);
                        $this->db->where('trash', '1');
                        $this->db->delete('pms');
                }
                
                } elseif ($this->input->post('delete') == 'Empty Trash') {
                        $this->db->where('message_to', $this->users->cur_user['user_id']);
                        $this->db->where('trash', '1');
                        $this->db->delete('pms');
                }

                $this->load->view('header');
                $this->load->view('pms/delete_success');
                $this->load->view('footer');
        }

        function set_field_value($field_name, $default_value)
        {
                if ($this->validation->$field_name == '')
                {
                        return $default_value;
                } else {
                        return $this->validation->$field_name;
                }
        }

}

?>
