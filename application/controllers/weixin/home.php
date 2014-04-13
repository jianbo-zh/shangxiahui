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
							throw new Exception('no relate event');
					}
					break;
				default:
					throw new Exception('no relate message');
			}
		}
		
		/**
		 * 处理文本消息类型
		 * @param object $text_object
		 */
		public function message_text($text_object)
		{
			$to = $text_object->get_to_user_name();
			$from = $text_object->get_from_user_name();
			$key = $text_object->get_content();
			$response = $this->response->factory('text', $to, $from);
			$response->set_content('你输入的是'.$key);
			
			$output = $response->get_output();
			echo $output;
			exit;
		}

		/**
		 * 处理图片消息类型
		 * @param object $image_object
		 */
		public function message_image($image_object)
		{
			$to = $image_object->get_to_user_name();
			$from = $image_object->get_from_user_name();
			$pic_url = $image_object->get_pic_url();
			$response = $this->response->factory('text', $to, $from);
			$response->set_content("你的图片链接为：\n" . $pic_url);
			
			$output = $response->get_output();
			echo $output;
			exit;
		}
		
		/**
		 * 处理声音消息类型
		 * @param object $voice_object
		 */
		public function message_voice($voice_object)
		{
			$from = $voice_object->get_from_user_name();
			$to = $voice_object->get_to_user_name();
			$recongnition = $voice_object->get_recongnition();
			
			$response = $this->response->factory('text', $to, $from);
			$response->set_content("你是在说：\n" . $recongnition);
			$output = $response->get_output();
			echo $output;
			exit;
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
			$from = $location_object->get_from_user_name();
			$to = $location_object->get_to_user_name();
			$address = '你当前的位置：' . $location_object->get_label();
			$location_x = '维度：' . $location_object->get_location_x();
			$location_y = '经度：' . $location_object->get_location_y();
			$scale = '缩放大小：' . $location_object->get_scale();
			
			$response = $this->response->factory('text', $to, $from);
			$response->set_content($address . "\n" . $location_x . "\n" . $location_y . "\n" . $scale);
			$output = $response->get_output();
			echo $output;
			exit;
		}
		
		/**
		 * 处理链接消息类型
		 * @param object $link_object
		 */
		public function message_link($link_object)
		{
			$to = $link_object->get_to_user_name();
			$from = $link_object->get_from_user_name();
			$title = '标题：' . $link_object->get_title();
			$description = '描述' . $link_object->get_description();
			$url = 'URL：' . $link_object->get_url();
			
			$response = $this->response->factory('text', $to, $from);
			$response->set_content('你的链接为：' . "\n" . $title . "\n" . $description . "\n" . $url);
			
			$output = $response->get_output();
			echo $output;
			exit;
		}
		
		/**
		 * 处理订阅事件
		 * @param unknown $subscribe_object
		 */
		public function event_subscribe($subscribe_object)
		{
			$to = $subscribe_object->get_to_user_name();
			$from = $subscribe_object->get_from_user_name();
			$response = $this->response->factory('text', $to, $from);
			$response->set_content("感谢你关注上下汇 ^.^!");
			
			$output = $response->get_output();
			echo $output;
			exit;
		}

		/**
		 * 处理取消订阅视图
		 * @param unknown $subscribe_object
		 */
		public function event_unsubscribe($unsubscribe_object)
		{
			$to = $unsubscribe_object->get_to_user_name();
			$from = $unsubscribe_object->get_from_user_name();
			$response = $this->response->factory('text', $to, $from);
			$response->set_content("期待你再次关注上下汇 ^.^!");
			
			$output = $response->get_output();
			echo $output;
			exit;
		}
		
		/**
		 * 处理二维码扫描
		 * @param unknown $subscribe_object
		 */
		public function event_scan($scan_object)
		{
			$to = $scan_object->get_to_user_name();
			$from = $scan_object->get_from_user_name();
			$event_key = '二维码参数：' . $scan_object->get_event_key();
			$ticket = '二维码Ticket：' . $scan_object->get_ticket();
			
			$response = $this->response->factory('text', $to, $from);
			$response->set_content("二维码为：\n" . $event_key . "\n" . $ticket);
			
			$output = $response->get_output();
			echo $output;
			exit;
		}
		
		/**
		 * 处理定位事件
		 * @param unknown $subscribe_object
		 */
		public function event_location($location_object)
		{
			$to = $location_object->get_to_user_name();
			$from = $location_object->get_from_user_name();
			$latitude = '纬度：' . $location_object->get_latitude();
			$longitude = '经度：' . $location_object->get_longitude();
			$precision = '精确度：' . $location_object->get_precision();
			
			$response = $this->response->factory('text', $to, $from);
			$response->set_content("你的当前坐标为：\n".$latitude . "\n" . $longitude . "\n" . $precision);
			
			$output = $response->get_output();
			echo $output;
			exit;
		}
		
		/**
		 * 处理用户单击菜单事件
		 * @param unknown $subscribe_object
		 */
		public function event_click($click_object)
		{
				
		}
		
		/**
		 * 处理用户单击菜单链接
		 * @param unknown $subscribe_object
		 */
		public function event_view($view_object)
		{
				
		}
	}