<?php
	class Response_message {
	
		protected $to_user_name;
		protected $from_user_name;
		protected $create_time;
	
		/**
		 * 初始化属性
		 */
		public function __construct($from_user_name, $to_user_name)
		{
			$this->set_to_user_name($to_user_name);
			$this->set_from_user_name($from_user_name);
			$this->set_create_time(time());
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
		public function get_output()
		{
			return '';
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
	class Text_response_message extends Response_message {
		
		protected $msg_type = 'text';
		protected $content;

		
		public function get_output()
		{
			$template = <<<"EOD"
<xml>
<ToUserName><![CDATA[{$this->get_to_user_name()}]]></ToUserName>
<FromUserName><![CDATA[{$this->get_from_user_name()}]]></FromUserName>
<CreateTime>{$this->get_create_time()}</CreateTime>
<MsgType><![CDATA[{$this->get_msg_type()}]]></MsgType>
<Content><![CDATA[{$this->get_content()}]]></Content>
</xml>
EOD;
			return $template;
		}
	
	}
	
	/**
	 * 图片消息
	 * @author jianbo
	 *
	 */
	class Image_response_message extends Response_message {
		
		protected $msg_type = 'image';
		protected $media_id;

		
		public function get_output()
		{
			$template = <<<"EOD"
<xml>
<ToUserName><![CDATA[{$this->get_to_user_name()}]]></ToUserName>
<FromUserName><![CDATA[$this->get_from_user_name()}]]></FromUserName>
<CreateTime>{$this->get_create_time()}</CreateTime>
<MsgType><![CDATA[{$this->get_msg_type()}]]></MsgType>
<Image>
<MediaId><![CDATA[{$this->get_media_id()}]]></MediaId>
</Image>
</xml>
EOD;
			return $template;
		}
	}
	
	/**
	 * 声音消息
	 * @author jianbo
	 *
	 */
	class Voice_response_message extends Response_message {

		protected $msg_type = 'voice';
		protected $media_id;

		
		public function get_output()
		{
			$template = <<<"EOD"
<xml>
<ToUserName><![CDATA[$this->get_to_user_name()}]]></ToUserName>
<FromUserName><![CDATA[$this->get_from_user_name()}]]></FromUserName>
<CreateTime>{$this->get_create_time()}</CreateTime>
<MsgType><![CDATA[{$this->get_msg_type()}]]></MsgType>
<Voice>
<MediaId><![CDATA[{$this->get_media_id()}]]></MediaId>
</Voice>
</xml>
EOD;
			return $template;
		}
	}
	
	/**
	 * 视频消息
	 * @author jianbo
	 *
	 */
	class Video_response_message extends Response_message {

		protected $msg_type = 'video';
		protected $media_id;
		protected $title;
		protected $description;

		
		public function get_output()
		{
			$template = <<<"EOD"
<xml>
<ToUserName><![CDATA[$this->get_to_user_name()}]]></ToUserName>
<FromUserName><![CDATA[$this->get_from_user_name()}]]></FromUserName>
<CreateTime>{$this->get_create_time()}</CreateTime>
<MsgType><![CDATA[{$this->get_msg_type()}]]></MsgType>
<Video>
<MediaId><![CDATA[{$this->get_media_id()}]]></MediaId>
<Title><![CDATA[{$this->get_title()}]]></Title>
<Description><![CDATA[{$this->get_description()}]]></Description>
</Video> 
</xml>
EOD;
			return $template;
		}
	}
	
	/**
	 * 回复音乐消息
	 * @author jianbo
	 *
	 */
	class Music_response_message extends Response_message {

		protected $msg_type = 'music';
		protected $title;
		protected $description;
		protected $music_url;
		protected $hq_music_url;
		protected $thumb_media_id;
		
		public function get_output()
		{
			$template = <<<"EOD"
<xml>
<ToUserName><![CDATA[{$this->get_to_user_name()}]]></ToUserName>
<FromUserName><![CDATA[{$this->get_from_user_name()}]]></FromUserName>
<CreateTime>{$this->get_create_time()}</CreateTime>
<MsgType><![CDATA[{$this->get_msg_type()}]]></MsgType>
<Music>
<Title><![CDATA[{$this->get_msg_title()}]]></Title>
<Description><![CDATA[{$this->get_description()}]]></Description>
<MusicUrl><![CDATA[{$this->get_music_url()}]]></MusicUrl>
<HQMusicUrl><![CDATA[{$this->get_hq_music_url()}]]></HQMusicUrl>
<ThumbMediaId><![CDATA[{$this->get_thumb_media_id()}]]></ThumbMediaId>
</Music>
</xml>
EOD;
			return $template;
		}
	}
	
	/**
	 * 回复图文消息,未完成！！！！！
	 * @author jianbo
	 *
	 */
	class News_response_message extends Response_message {

		protected $msg_type = 'news';
	/* 	protected $title;
		protected $description;
		protected $url;
		protected $media_id;
		protected $msg_id;
		
		
		public function __construct($from_user_name, $to_user_name)
		{
			parent::__construct($from_user_name, $to_user_name);
			
		} */
		public function get_output()
		{
			return '';
		}
	}