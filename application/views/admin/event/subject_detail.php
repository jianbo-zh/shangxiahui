
<div class="main">
	<div class="event_filter">
		<form id="filter_form" action="<?php echo base_url('admin/event/event_list/'.$subject_id)?>" method="post">
			<label>事件名称： <input type="text" name="search_title" value="<?=$search_title;?>" />
			</label> <label>开始时间： <input type="text" name="start_time" value="<?=$start_time;?>" />
			</label> <label>结束时间： <input type="text" name="end_time" value="<?=$end_time;?>" />
			</label> <label>每页显示 <input type="text" name="limit" value="<?=$limit;?>" /> 条
			</label> <input type="submit" value="搜索" />
		</form>
	</div>
	<div class="event_list">
		<table>
			<tr>
				<th>事件名称</th>
				<th>事件摘要</th>
				<th>事件时间</th>
				<th>操作</th>
			</tr>
		<?php foreach ($events as $event):?>
		<tr>
				<td><?=$event['title'];?></td>
				<td><?=$event['summary'];?></td>
				<td><?=date('Y-m-d H:i:s', $event['event_time']);?></td>
				<td><a
					href="<?php echo base_url('admin/event/mod_event/'.$event['id']);?>">详细</a>
					/ <a
					href="<?php echo base_url('admin/event/track_event/'.$event['subject_id']);?>">追加</a>
					/ <a href="javascript:void(0);" class="del_event"
					rel_id="<?php echo $event['id']?>">删除</a></td>
			</tr>
		<?php endforeach;?>
	</table>
	</div>
	<div class="track_event">
		<a href="javascript:void(0);" class="track_event">跟进事件</a>
	</div>
</div>

<script>
	$('.del_event').click(function(){
		id = $(this).attr('rel_id');
		$.ajax({
			url : <?php echo base_url('admin/event/ajax_del_event')?>,
			data : {
				id : id
			}
			success : function(response){
				alert(response.message);
			}
		});
	});
</script>

