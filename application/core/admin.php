<?php

	abstract class Admin extends CI_Controller {
		
		/**
		 * ��֤�Ƿ��¼
		 */
		public function __construct()
		{
			parent::__construct();
			
			// ��û�е�¼
			if(false)
			{
				header('Location: ' . base_url('admin/login/login'));
				exit;
			}
		}
		
		/**
		 * չʾͷ��Ϣ
		 */
		public function header()
		{
			
		}
		
		
		/**
		 * չʾβ��Ϣ
		 */
		public function footer()
		{
			
		}
		
		/**
		 * չʾ��ҳ
		 */
		public function index()
		{
			$this->header();
			$this->sidebar();
			$this->main();
			$this->footer();
		}

		/**
		 * չʾ�����
		 */
		abstract public function sidebar();
		
		/**
		 * ��ʾ��������
		 */
		abstract public function main();
	}