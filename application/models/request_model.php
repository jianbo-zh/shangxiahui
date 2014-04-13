<?php
	require_once(APPPATH . 'libraries/request_message.php');
	require_once(APPPATH . 'libraries/event.php');
	
	class Request_model extends CI_Model {
		
		public function parse()
		{
			$this->load->model('recorder_model', 'recorder');
			
			$post_data = file_get_contents('php://input');
			
			file_put_contents('/mnt/htdocs/weixin/log.txt', $post_data);
			
			$xml_obj = simplexml_load_string($post_data, 'SimpleXMLElement', LIBXML_NOCDATA);

			//$this->recorder->record($xml_obj->FromUserName, $xml_obj->MsgType, $post_data);
			
			switch ($xml_obj->MsgType)
			{
				case 'text' :
					return new Text_request_message($xml_obj);
				case 'image' :
					return new Image_request_message($xml_obj);
				case 'voice' :
					return new Voice_request_message($xml_obj);
				case 'video' :
					return new Video_request_message($xml_obj);
				case 'location' :
					return new Location_request_message($xml_obj);
				case 'link' :
					return new Link_request_message($xml_obj);
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
							throw new Exception('parse error, no relate event');
					}
					break;
				default:
					throw new Exception('parse error, no relate message');
			}
		}
	}