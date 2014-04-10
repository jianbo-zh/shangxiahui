<?php
	class Event_model extends CI_Model {
		
		private $table = 'event';
		
		private $errmsg = array();
		
		/**
		 * 新增一个主题
		 * @param array $event
		 * @return boolean
		 */
		public function add_event($event)
		{
			$e = array();
			$subject_id = $this->generate_subject_id();
			
			if( ! $subject_id)
			{
				return false;
			}
			$e['prev_eid'] = 0;
			$e['subject_id'] = $subject_id;
			$e['event_time'] = $event['event_time'];
			$e['edit_time'] = time();
			$e['user_id'] = $event['user_id'];
			$e['type'] = $event['type'];
			$e['title'] = $event['title'];
			$e['summary'] = $event['summary'];
			
			$this->db->insert($this->table, $e);
			
			if($this->db->affected_rows() > 0)
			{
				return $this->db->insert_id();
			}
			$this->set_error('新增事件失败，sql:' . $this->db->last_query());
			return false;
		}
		
		/**
		 * 给一个主题追究一个事件
		 * @param unknown $event
		 * @return boolean
		 */
		public function track_event($event)
		{
			if(empty($event['subject_id']) or 
				empty($event['event_time']) or 
					empty($event['user_id']) or 
						empty($event['type']) or 
							empty($event['title']) or 
								empty($event['summary']))
			{
				$this->set_error('添加一个追踪内容失败，事件内容不全!');
				return false;
			}
			$subject_id = $event['subject_id'];
			$e = array();
			
			$prev_event = $this->_get_prev_event_by_time($event['event_time']);
			$e['prev_eid'] = $prev_event ? $prev_event['id'] : 0;
			
			$e['subject_id'] = $subject_id;
			$e['event_time'] = $event['event_time'];
			$e['edit_time'] = time();
			$e['user_id'] = $event['user_id'];
			$e['type'] = $event['type'];
			$e['title'] = $event['title'];
			$e['summary'] = $event['summary'];
			
			$this->db->insert($this->table, $e);
			if($this->db->affected_rows() > 0)
			{
				$insert_id = $this->db->insert_id();

				$next_event = $this->_get_next_event_by_time($event['event_time']);
				if($next_event)
				{
					if( ! $this->set_event_prev_id($next_event['id'], $insert_id))
					{
						$this->del_event($insert_id);
						return false;
					}
				}
				return true;
			}
			
			$this->set_error('添加追踪事件失败，sql:' . $this->db->last_query());
			
			return true;
		}
		
		public function mod_event($event_id, $event)
		{
			if(empty($event['title']) or 
				empty($event['summary']) or 
					empty($event['event_time']) or 
						empty($event['type']))
			{
				return false;
			}
			$e = array();
			$e['title'] = $event['title'];
			$e['summary'] = $event['summary'];
			$e['type'] = $event['type'];
			$event_time = $event['event_time'];
			
			$cur_event = $this->get_event_by_id($event_id);
			
			if($cur_event['event_time'] == $event_time)
			{
				$this->db->update($this->table, $e, array('id'=>$event_id));
			}
			else 
			{

				$e['event_time'] = $event_time;
				// 更新自己时间
				$this->db->update($this->table, $e, array('id'=>$event_id));
				
				// 后继
				$old_next_event = $this->get_next_event($event_id);
				
				if($old_next_event)
				{
					$this->set_event_prev_id($old_next_event['id'], $cur_event['prev_eid']);
				}
				
				$new_prev_event = $this->_get_prev_event_by_time($event_time);
				$new_next_event = $this->_get_next_event_by_time($event_time);
				
				if($new_prev_event)
				{
					$prev_eid = $new_prev_event['id'];
				}
				else 
				{
					$prev_eid = 0;
				}


				// 更新自己
				$this->db->update($this->table, array('prev_eid'=>$prev_eid), array('id'=>$event_id));
				
				if($new_next_event)
				{
					$this->set_event_prev_id($new_next_event['id'], $event_id);
				}

			}
		}
		
		/**
		 * 删除主题
		 * @param number $subject_id
		 * @return boolean
		 */
		public function del_subject($subject_id)
		{
			
			$this->db->delete($this->table, array('subject_id'=>$subject_id));
			
			if($this->db->affected_rows() > 0)
			{
				return true;
			}
			else
			{
				$this->set_error('删除事件失败, sql:' . $this->db->last_query());
				return false;
			}
		}
		
		public function del_event($event_id)
		{
			$cur_event = $this->get_event_by_id($event_id);
			
			$this->db->delete($this->table, array('id'=>$event_id));
			if($this->db->affected_rows() > 0)
			{
				$next_event = $this->get_next_event($event_id);
				if($next_event)
				{
					$this->set_event_prev_id($next_event['id'], $cur_event['prev_eid']);
				}
				return true;
			}
			
			$this->set_error('删除事件失败, sql:' . $this->db->last_query());
			return false;
		}
		
		public function set_event_prev_id($event_id, $prev_event_id)
		{
			$this->db->update($this->table, array('prev_eid'=>$prev_event_id), array('id'=>$event_id));
			if($this->db->affected_rows() > 0)
			{
				return true;
			}
			$this->set_error('修改事件前一个事件ID失败, sql:' . $this->db->last_query());
			return false;
		}
		
		
		/**
		 * 获取该主题的上一次发生的事件ID
		 * @param unknown $subject_id
		 * @return unknown|boolean
		 */
		public function get_last_event($subject_id)
		{
			$this->db->where('subject_id', $subject_id);
			$this->db->order_by('event_time', 'desc');
			$result = $this->db->get($this->table);
			
			if($result->num_rows() > 0)
			{
				return $result->row_array();
			}
			return false;
		}
		
		/**
		 * 获取一个事件
		 * @param number $event_id 当前事件ID
		 * @return array|boolean
		 */
		public function get_next_event($event_id)
		{
			$resutl = $this->db->get_where($this->table, array('prev_eid'=>$event_id));
			if($resutl->num_rows() > 0)
			{
				return $row = $resutl->row_array();
				
			}
			$this->set_error('获取下一条事件失败, sql:' . $this->db->last_query());
			
			return false;
		}
		
		/**
		 * 获取上一条记录信息
		 * @param number $event_id
		 * @return array|boolean
		 */
		public function get_prev_event($event_id)
		{
			$event = $this->get_event_by_id($event_id);
			if( ! $event)
			{
				return false;
			}
			$result = $this->db->get_where($this->table, array('id'=>$event['prev_eid']));
			if($result->num_rows() > 0)
			{
				return $result->row_array();
			}
			$this->set_error('获取上一条事件失败, sql:' . $this->db->last_query());
			
			return false;
		}
		
		/**
		 * 获取事件信息
		 * @param number $event_id
		 * @return array|boolean
		 */
		public function get_event_by_id($event_id)
		{
			$result = $this->db->get_where($this->table, array('id'=>$event_id));
			
			if($result->num_rows() > 0)
			{
				return $result->row_array();
			}
			
			$this->set_error('获取事件失败, sql:' . $this->db->last_query());
			
			return false;
		}
		
		/**
		 * 生成主题ID，成功则返回主题ID,失败则返回false
		 * @return number|boolean
		 */
		public function generate_subject_id()
		{
			$this->db->select('max(subject_id) as max_subject');
			$result = $this->db->get($this->table);
			if($result->num_rows() > 0)
			{
				$row = $result->row_array();
				return $row['max_subject'] + 1;
			}
			$this->set_error('生成主题ID失败，sql:'.$this->db->last_query());
			return false;
		}
		
		/**
		 * 获取主题系列
		 * @param string $type
		 * @param string $limit
		 * @param string $offset
		 * @param string $start_time
		 * @param string $end_time
		 * @param string $search_title
		 * @return array
		 */
		public function get_subjects($type=null, $limit=null, $offset=null, 
										$start_time=null, $end_time=null, 
											$search_title=null)
		{
			
			$this->db->where('prev_eid', 0);
			
			if($type)
			{
				$this->db->where('type', $type);
			}
			if($limit)
			{
				$this->db->limit($limit);
			}
			if($offset)
			{
				$this->db->where('offset', $offset);
			}
			if($start_time)
			{
				$this->db->where('event_time >=', $start_time);
			}
			if($end_time)
			{
				$this->db->where('event_time <', $end_time);
			}
			if($search_title)
			{
				$this->db->like('title', $search_title, 'both');
			}
			
			$this->db->order_by('event_time', 'asc');
			
			$result = $this->db->get($this->table);
			
			return $result->result_array();
		}
		
		/**
		 * 获取主题事件系列
		 * @param number $subject_id
		 * @param string $limit
		 * @param string $offset
		 * @param string $start_time
		 * @param string $end_time
		 * @param string $search_title
		 * @return array
		 */
		public function get_subject($subject_id, $limit=null, $offset=null, 
										$start_time=null, $end_time=null, 
											$search_title=null)
		{
			
			$this->db->where('subject_id', $subject_id);

			if($limit)
			{
				$this->db->limit($limit);
			}
			if($offset)
			{
				$this->db->where('offset', $offset);
			}
			if($start_time)
			{
				$this->db->where('event_time >=', $start_time);
			}
			if($end_time)
			{
				$this->db->where('event_time <', $end_time);
			}
			if($search_title)
			{
				$this->db->like('title', $search_title, 'both');
			}
			
			$this->db->order_by('event_time', 'asc');
			
			$result = $this->db->get($this->table);
			
			return $result->result_array();
		}

		/**
		 * 通过事件时间获取上一个事件
		 * @param string $event_time
		 * @return array|boolean
		 */
		private function _get_prev_event_by_time($event_time)
		{
			$this->db->where('event_time <', $event_time);
			$this->db->order_by('event_time', 'desc');
			$result = $this->db->get($this->table);
			if($result->num_rows() > 0)
			{
				return $result->row_array();
			}
				
			$this->set_error('通过事件时间获取上一条事件失败, sql:' . $this->db->last_query());
				
			return false;
		}
		
		/**
		 * 通过事件获取下一个事件
		 * @param string $event_time
		 * @return array|boolean
		 */
		private function _get_next_event_by_time($event_time)
		{
			$this->db->where('event_time >', $event_time);
			$this->db->order_by('event_time', 'asc');
			$result = $this->db->get($this->table);
			if($result->num_rows() > 0)
			{
				return $result->row_array();
			}
				
			$this->set_error('通过事件时间获取下一条事件失败, sql:' . $this->db->last_query());
				
			return false;
		}
		
		/**
		 * 设置错误消息
		 * @param string $err_msg
		 */
		public function set_error($err_msg)
		{
			$this->errmsg[] = $err_msg;
		}
		
		/**
		 * 返回错误消息
		 * @return string
		 */
		public function get_error()
		{
			$errmsg = '';
			foreach ($this->errmsg as $err)
			{
				$errmsg .= $err . "\n";
			}
			return $errmsg;
		}
		
	}