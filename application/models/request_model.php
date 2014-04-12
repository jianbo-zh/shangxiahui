<?php
	require_once(APPPATH . '/libraries/message.php');
	
	class Requst_model extends CI_Model {
		
		public function parse()
		{
			$post_data = file_get_contents('php://input');
			$xml_obj = simplexml_load_string($post_data, 'SimpleXMLElement', LIBXML_NOCDATA);
			
			switch ($xml_obj->Msgtype)
			{
				case 'text' :
					return new Text_message($xml_obj);
				case 'image' :
					return new Image_message($xml_obj);
				case 'voice' :
					return new Voice_message($xml_obj);
				case 'video' :
					return new Video_message($xml_obj);
				case 'location' :
					return new Location_message($xml_obj);
				case 'link' :
					return new Link_message($xml_obj);
				case 'event' :
					switch ($xml_obj->Event)
					{
						case 'subscribe' :
							return new Subscribe_event($xml_obj);
						case 'unsubscribe' :
							return new Unsubscribe_event($xml_obj);
						case 'SCAN' :
							return new Scan_event($xml_obj);
						case 'LOCATION' :
							return new Location_event($xml_obj);
						case 'CLICK' :
							return new Click_event($xml_obj);
						case 'VIEW' :
							return new View_event($xml_obj);
						default:
							throw new Exception('解析错误，没有对应的消息类型');
					}
				default:
					throw new Exception('解析错误，没有对应的消息类型');
			}
		}
	}