<?php
	require_once(APPPATH.'core/admin.php');
	
	class Home extends Admin {
		
		public function __construct()
		{
			parent::__construct();
		}

		public function sidebar()
		{
			$this->load->view('admin/home/');
		}
		
		public function main()
		{
			
		}
		
	}