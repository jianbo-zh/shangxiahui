<?php

	abstract class Admin extends CI_Controller {
		
		/**
		 * 验证是否登录
		 */
		public function __construct()
		{
			parent::__construct();
			
			// 如没有登录
			if(false)
			{
				header('Location: ' . base_url('admin/login/login'));
				exit;
			}
		}
		
		/**
		 * 展示头信息
		 */
		public function header()
		{
			
		}
		
		
		/**
		 * 展示尾信息
		 */
		public function footer()
		{
			
		}
		
		/**
		 * 展示首页
		 */
		public function index()
		{
			$this->header();
			$this->sidebar();
			$this->main();
			$this->footer();
		}

		/**
		 * 展示侧边栏
		 */
		abstract public function sidebar();
		
		/**
		 * 显示主内容区
		 */
		abstract public function main();
	}