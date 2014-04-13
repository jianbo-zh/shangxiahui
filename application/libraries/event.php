<?php
	/**
	 * 事件基类
	 * @author jianbo
	 *
	 */
	class Event {
	
		protected $to_user_name;
		protected $from_user_name;
		protected $create_time;
		protected $msg_type;
		protected $event;
	
		/**
		 * 初始化属性
		 */
		public function __construct($xml_obj)
		{
			$this->set_to_user_name($xml_obj->ToUserName);
			$this->set_from_user_name($xml_obj->FromUserName);
			$this->set_create_time($xml_obj->CreateTime);
			$this->set_msg_type($xml_obj->MsgType);
			$this->set_event($xml_obj->Event);
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
	 * 订阅事件，包括一般订阅，和扫描二维码订阅
	 * @author jianbo
	 *
	 */
	class Subscribe_event extends Event {
		
		protected $event_key;
		protected $ticket;
		
		public function __construct($xml_obj)
		{
			parent::__construct($xml_obj);
			
			if(isset($xml_obj->EventKey))
			{
				$this->set_event_key($xml_obj->EventKey);
			}
			if(isset($xml_obj->Ticket))
			{
				$this->set_ticket($xml_obj->Ticket);
			}
		}
	}
	
	/**
	 * 取消订阅事件
	 * @author jianbo
	 *
	 */
	class Unsubscribe_event extends Event {}

	/**
	 * 扫描二维码事件
	 * @author jianbo
	 *
	 */
	class Scan_event extends Event {
	
		protected $event_key;
		protected $ticket;
	
		public function __construct($xml_obj)
		{
			parent::__construct($xml_obj);
			$this->set_event_key($xml_obj->EventKey);
			$this->set_ticket($xml_obj->Ticket);
		}
	}
	
	/**
	 * 上报定位事件
	 * @author jianbo
	 *
	 */
	class Location_event extends Event {

		protected $latitude;
		protected $longitude;
		protected $precision;
		
		public function __construct($xml_obj)
		{
			parent::__construct($xml_obj);
			$this->set_latitude($xml_obj->Latitude);
			$this->set_longitude($xml_obj->Longitude);
			$this->set_precision($xml_obj->Precision);
		}
	}
	
	/**
	 * 点击菜单拉取消息事件
	 * @author jianbo
	 *
	 */
	class Click_event extends Event {

		protected $event_key;
		
		public function __construct($xml_obj)
		{
			parent::__construct($xml_obj);
			$this->set_event_key($xml_obj->EventKey);
		}
	}
	
	/**
	 * 点击菜单链接事件
	 * @author jianbo
	 *
	 */
	class View_event extends Event {

		protected $event_key;
		
		public function __construct($xml_obj)
		{
			parent::__construct($xml_obj);
			$this->set_event_key($xml_obj->EventKey);
		}
	}
	
	
