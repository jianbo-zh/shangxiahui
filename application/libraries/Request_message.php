<?php
	class Request_message {
	
		protected $to_user_name;
		protected $from_user_name;
		protected $create_time;
		protected $msg_type;
	
		/**
		 * 初始化属性
		 */
		public function __construct($xml_obj)
		{
			$this->set_to_user_name($xml_obj->ToUserName);
			$this->set_from_user_name($xml_obj->FormUserName);
			$this->set_create_time($xml_obj->CreateTime);
			$this->set_msg_type($xml_obj->MsgType);
		}
		
		public function __call($method_name, $args)
		{
			if (preg_match('/^(set|get)_(.*)$/', $method_name, $matches)) 
			{
	             $property = strtolower($matches[2]);
	             
	             if ( ! property_exists($this, $property)) 
	             {
	                 throw new Exception('Property ' . $property . ' not exists');
	             }
	             
	             switch($matches[1]) 
	             {
	                 case 'set':
	                     return $this->set($property, $args[0]);
	                 case 'get':
	                     return $this->get($property);
	                 case 'default':
	                     throw new Exception('Method ' . $method_name . ' not exists');
	             }
         	}
			
		}
		
		public function get($property)
		{
			return $this->$property;
		}
		
		public function set($property, $value)
		{
			$this->$property = $value;
			return $this;
		}
		
	}

	/**
	 * 文本消息
	 * @author jianbo
	 *
	 */
	class Text_request_message extends Request_message {
		
		protected $content;
		protected $msg_id;
		
		public function __construct($xml_obj)
		{
			parent::__construct($xml_obj);
			
			$this->set_content($xml_obj->Content);
			$this->set_msg_id($xml_obj->MsgId);
		}
	
	}
	
	/**
	 * 图片消息
	 * @author jianbo
	 *
	 */
	class Image_request_message extends Request_message {
		
		protected $pic_url;
		protected $media_id;
		protected $msg_id;
		
		public function __construct($xml_obj)
		{
			parent::__construct($xml_obj);
			$this->set_pic_url($xml_obj->PicUrl);
			$this->set_media_id($xml_obj->MediaId);
			$this->set_msg_id($xml_obj->MsgId);
		}
	}
	
	/**
	 * 声音消息
	 * @author jianbo
	 *
	 */
	class Voice_request_message extends Request_message {

		protected $format;
		protected $media_id;
		protected $msg_id;
		
		public function __construct($xml_obj)
		{
			parent::__construct($xml_obj);
			$this->set_format($xml_obj->Format);
			$this->set_media_id($xml_obj->MediaId);
			$this->set_msg_id($xml_obj->MsgId);
		}
	}
	
	/**
	 * 视频消息
	 * @author jianbo
	 *
	 */
	class Video_request_message extends Request_message {

		protected $thumb_media_id;
		protected $media_id;
		protected $msg_id;
		
		public function __construct($xml_obj)
		{
			parent::__construct($xml_obj);
			$this->set_thumb_media_id($xml_obj->ThumbMediaId);
			$this->set_media_id($xml_obj->MediaId);
			$this->set_msg_id($xml_obj->MsgId);
		}
	}
	
	/**
	 * 定位消息
	 * @author jianbo
	 *
	 */
	class Location_request_message extends Request_message {

		protected $location_x;
		protected $location_y;
		protected $scale;
		protected $label;
		protected $media_id;
		protected $msg_id;
		
		public function __construct($xml_obj)
		{
			parent::__construct($xml_obj);
			$this->set_pic_url($xml_obj->Location_X);
			$this->set_pic_url($xml_obj->Location_Y);
			$this->set_pic_url($xml_obj->Scale);
			$this->set_pic_url($xml_obj->Label);
			$this->set_media_id($xml_obj->MediaId);
			$this->set_msg_id($xml_obj->MsgId);
		}
	}
	
	/**
	 * 链接消息
	 * @author jianbo
	 *
	 */
	class Link_request_message extends Request_message {

		protected $title;
		protected $description;
		protected $url;
		protected $media_id;
		protected $msg_id;
		
		public function __construct($xml_obj)
		{
			parent::__construct($xml_obj);
			$this->set_pic_url($xml_obj->Title);
			$this->set_pic_url($xml_obj->Description);
			$this->set_pic_url($xml_obj->Url);
			$this->set_media_id($xml_obj->MediaId);
			$this->set_msg_id($xml_obj->MsgId);
		}
	}