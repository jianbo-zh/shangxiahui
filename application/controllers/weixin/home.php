<?php
	class Home extends CI_Controller {
		
		public function __construct()
		{
			parent::__construct();

			$this->load->model('request_model', 'request');
			$this->load->model('response_model', 'response');
		}
		
		/**
		 * 微信后台入口，前端控制器
		 */
		public function index()
		{
			$msg_object = $this->request->parse();
			
			switch ($msg_object->get_msg_type())
			{
				case 'text' :
					$this->message_text($msg_object);
					break;
				case 'image' :
					$this->message_image($msg_object);
					break;
				case 'voice' :
					$this->message_voice($msg_object);
					break;
				case 'video' :
					$this->message_video($msg_object);
					break;
				case 'location' :
					$this->message_location($msg_object);
					break;
				case 'link' :
					$this->message_link($msg_object);
					break;
				case 'event' :
					switch ($msg_object->get_event())
					{
						case 'subscribe' :
							$this->event_subscribe($msg_object);
							break;
						case 'unsubscribe' :
							$this->event_unsubscribe($msg_object);
							break;
						case 'scan' :
							$this->event_scan($msg_object);
							break;
						case 'location' :
							$this->event_location($msg_object);
							break;
						case 'click' :
							$this->event_click($msg_object);
							break;
						case 'view' :
							$this->event_view($msg_object);
							break;
						default:
							throw new Exception('没有对应的事件');
					}
				default:
					throw new Exception('没有对应的消息');
			}
		}
		
		/**
		 * 处理文本消息类型
		 * @param object $text_object
		 */
		public function message_text($text_object)
		{
			
		}

		/**
		 * 处理图片消息类型
		 * @param object $image_object
		 */
		public function message_image($image_object)
		{
				
		}
		
		/**
		 * 处理声音消息类型
		 * @param object $voice_object
		 */
		public function message_voice($voice_object)
		{
			
		}
		
		/**
		 * 处理视屏消息类型
		 * @param object $video_object
		 */
		public function message_video($video_object)
		{
			
		}
		
		/**
		 * 处理定位消息类型
		 * @param object $location_object
		 */
		public function message_location($location_object)
		{
			
		}
		
		/**
		 * 处理链接消息类型
		 * @param object $link_object
		 */
		public function message_link($link_object)
		{
			
		}
		
		/**
		 * 处理订阅事件
		 * @param unknown $subscribe_object
		 */
		public function event_subscribe($subscribe_object)
		{
			$from = $subscribe_object->get_to_user_name();
			$to = $subscribe_object->get_from_user_name();
			$response = $this->response->factory('text', $from, $to);
			$response->set_content('欢迎订阅shangxiahui ^.^');
			
			$output = $response->get_output();
			echo $output;
			exit;
		}

		/**
		 * 处理取消订阅视图
		 * @param unknown $subscribe_object
		 */
		public function event_unsubscribe($subscribe_object)
		{
				
		}
		
		/**
		 * 处理二维码扫描
		 * @param unknown $subscribe_object
		 */
		public function event_scan($subscribe_object)
		{
				
		}
		
		/**
		 * 处理定位事件
		 * @param unknown $subscribe_object
		 */
		public function event_location($subscribe_object)
		{
				
		}
		
		/**
		 * 处理用户单击菜单事件
		 * @param unknown $subscribe_object
		 */
		public function event_click($subscribe_object)
		{
				
		}
		
		/**
		 * 处理用户单击菜单链接
		 * @param unknown $subscribe_object
		 */
		public function event_view($subscribe_object)
		{
				
		}
	}