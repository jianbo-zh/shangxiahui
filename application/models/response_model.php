<?php
	require_once(APPPATH . '/Response_message.php');
	class Response_model extends CI_Model {
		
		public function factory($type, $from, $to)
		{
			switch ($type)
			{
				case 'text' :
					return new Text_response_message($from, $to);
				case 'image' :
					return new Image_response_message($from, $to);
				case 'voice' :
					return new Voice_response_message($from, $to);
				case 'video' :
					return new Video_response_message($from, $to);
				case 'music' :
					return new Music_response_message($from, $to);
				case 'news' :
					return new News_response_message($from, $to);
				default:
					throw new Exception('没有'.$type.'类型的回复类');
			}
		}
	}