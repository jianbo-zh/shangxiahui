<?php
	class Recorder_model extends CI_Model {
		
		protected $table = 'log';
		
		public function record($user, $msg_type, $content)
		{
			$this->db->insert($this->table, array('user'=>$user, 'msg_type'=>$msg_type, 'content'=>$content));
			
			if($this->db->affected_rows() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}