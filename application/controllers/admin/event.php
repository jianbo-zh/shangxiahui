<?php
	require_once(APPPATH.'core/admin.php');
	
	class Event extends Admin {
		
		public function __construct()
		{
			parent::__construct();
		}

		public function sidebar()
		{
			$this->load->view('admin/event/sidebar');
		}
		
		public function main()
		{
			$this->load->view('admin/event/sidebar');
		}
		
		public function show_event()
		{
			$this->load->view('admin/event/event_list');
		}
		
	}