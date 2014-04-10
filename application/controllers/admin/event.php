<?php
	require_once(APPPATH.'core/admin.php');
	
	class Event extends Admin {
		
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('event_model', 'event');
		}

		public function sidebar()
		{
			$this->load->helper('url');
			$this->load->view('admin/event/sidebar');
		}
		
		public function main()
		{
			
		}
		
		public function track_subject()
		{
			$subject_id = $this->uri->segment(4);
			$this->header();
			$this->sidebar();
			$this->load->helper('url');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', '标题名', 'trim|required|max_length[20]|xss_clean');
			$this->form_validation->set_rules('summary', '摘要', 'trim|required|max_length[100]|xss_clean');
			$this->form_validation->set_rules('event_time', '事件时间', 'trim|required|callback_check_time');
			if($this->form_validation->run())
			{
				$event = array();
				$event['prev_eid'] = 0;
				$event['event_time'] = strtotime($this->input->post('event_time'));
				// 管理员ID
				$event['user_id'] = 1;
				$event['type'] = $this->input->post('type');
				$event['title'] = $this->input->post('title');
				$event['summary'] = $this->input->post('summary');
			
				if($this->event->add_event($event))
				{
					$this->load->view('admin/event/add_success');
				}
			}
			else 
			{
				$this->load->view('admin/event/track_subject', array('subject_id'=>$subject_id));
			}
			$this->footer();
		}
		
		public function add_subject()
		{
			$this->header();
			$this->sidebar();
			$this->load->helper('url');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', '标题名', 'trim|required|max_length[20]|xss_clean');
			$this->form_validation->set_rules('summary', '摘要', 'trim|required|max_length[100]|xss_clean');
			$this->form_validation->set_rules('event_time', '事件时间', 'trim|required|callback_check_time');
			if($this->form_validation->run())
			{
				$event = array();
				$event['prev_eid'] = 0;
				$event['event_time'] = strtotime($this->input->post('event_time'));
				// 管理员ID
				$event['user_id'] = 1;
				$event['type'] = $this->input->post('type');
				$event['title'] = $this->input->post('title');
				$event['summary'] = $this->input->post('summary');
				
				if($this->event->add_event($event))
				{
					$this->load->view('admin/event/add_success');
				}
			}
			else 
			{
				$this->load->view('admin/event/add_subject');
			}
			$this->footer();
		}
		
		public function mod_event()
		{
			$this->header();
			$this->sidebar();
			
			$id = $this->uri->segment(4);
			$event = $this->event->get_event_by_id($id);
			$this->load->view('admin/event/mod_event', array('event'=>$event));			
			$this->footer();
		}
		
		public function ajax_mod_event()
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', '标题名', 'trim|required|max_length[20]|xss_clean');
			$this->form_validation->set_rules('summary', '摘要', 'trim|required|max_length[100]|xss_clean');
			$this->form_validation->set_rules('event_time', '事件时间', 'trim|required|callback_check_time');
			if($this->form_validation->run())
			{
				$event = array();
				$event['event_time'] = strtotime($this->input->post('event_time'));
				$event['type'] = $this->input->post('type');
				$event['title'] = $this->input->post('title');
				$event['summary'] = $this->input->post('summary');
				$event_id = $this->input->post('id');
				
				$this->event->mod_event($event_id, $event);
				echo json_encode(array('status'=>'succ', 'message'=>'修改成功!'));
			}
			else 
			{
				echo json_encode(array('status'=>'fail', 'message'=>'验证失败!' . $this->form_validation->error_string()));
			}
			
		}
		
		public function ajax_del_subject()
		{
			$subject_id = $this->uri->segment(4);
			
			$result = $this->event->del_subject($subject_id);
			if($result)
			{
				echo json_encode(array('status'=>'succ', 'message'=>'删除成功！'));
			}
			else 
			{
				echo json_encode(array('status'=>'fail', 'message'=>'删除失败！'));
			}
			exit;
		}
		
		public function ajax_del_event()
		{
			$id = $this->uri->segment(4);
			
			$result = $this->event->del_event($id);
			if($result)
			{
				echo json_encode(array('status'=>'succ', 'message'=>'删除成功！'));
			}
			else 
			{
				echo json_encode(array('status'=>'fail', 'message'=>'删除失败！'));
			}
			exit;
		}
		
		public function subject_list()
		{
			$this->header();
			$this->sidebar();
			$this->show_subjects();
			$this->footer();
		}
		
		public function subject_detail()
		{
			$this->header();
			$this->sidebar();
			$this->show_subject();
			$this->footer();
		}
		
		public function show_subject()
		{
			$cnd = array();
			$cnd['limit'] = $this->input->post('limit');
			$cnd['offset'] = $this->input->post('offset');
			$cnd['start_time'] = $this->input->post('start_time');
			$cnd['end_time'] = $this->input->post('end_time');
			$cnd['search_title'] = $this->input->post('search_title');
			
			$subject_id = $this->uri->segment(4);
			$limit = !empty($cnd['limit']) ? $cnd['limit'] : null;
			$offset = !empty($cnd['offset']) ? $cnd['offset'] : null;
			$start_time = !empty($cnd['start_time']) ? strtotime($cnd['start_time']) : null;
			$end_time = !empty($cnd['end_time']) ? strtotime($cnd['end_time']) : null;
			$search_title = !empty($cnd['search_title']) ? trim($cnd['search_title']) : null;
				
			$events = $this->event->get_subject($subject_id,
					$limit,
					$offset,
					$start_time,
					$end_time,
					$search_title);
			$data = $cnd;
			$data['events'] = $events;
			$data['subject_id'] = $subject_id;
			$this->load->view('admin/event/subject_detail', $data);
		}
		
		public function show_subjects()
		{
			$cnd = array();
			$cnd['type'] = $this->input->post('type');
			$cnd['limit'] = $this->input->post('limit');
			$cnd['offset'] = $this->input->post('offset');
			$cnd['start_time'] = $this->input->post('start_time');
			$cnd['end_time'] = $this->input->post('end_time');
			$cnd['search_title'] = $this->input->post('search_title');

			$type = !empty($cnd['type']) ? $cnd['type'] : null;
			$limit = !empty($cnd['limit']) ? $cnd['limit'] : null;
			$offset = !empty($cnd['offset']) ? $cnd['offset'] : null;
			$start_time = !empty($cnd['start_time']) ? strtotime($cnd['start_time']) : null;
			$end_time = !empty($cnd['end_time']) ? strtotime($cnd['end_time']) : null;
			$search_title = !empty($cnd['search_title']) ? trim($cnd['search_title']) : null;
			
			$events = $this->event->get_subjects($type, 
												$limit, 
												$offset, 
												$start_time, 
												$end_time, 
												$search_title);
			$data = $cnd;
			$data['events'] = $events;
			$this->load->view('admin/event/subject_list', $data);
		}
		
		public function check_time($str)
		{
			$event_time = strtotime($str);
			if($event_time)
			{
				return true;
			}
			$this->form_validation->set_message('check_time', '事件时间不正确啊');
			return false;
		}
		
	}