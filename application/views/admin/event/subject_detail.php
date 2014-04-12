
<div class="main">
	<div class="event_filter">
		<form id="filter_form" action="<?php echo base_url('admin/event/subject_detail/'.$subject_id)?>" method="post">
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
					/ <a href="javascript:void(0);" class="del_event"
					rel_id="<?php echo $event['id']?>">删除</a></td>
			</tr>
		<?php endforeach;?>
	</table>
	</div>

	<hr />
	<h3>继续添加事件</h3>
	<form action="<?php echo site_url('admin/event/ajax_track_subject');?>"
		method="post" enctype="application/x-www-form-urlencoded" id="track_subject">
		<input type="hidden" name="subject_id" value="<?php echo $events[0]['subject_id'];?>" />
		<input type="hidden" name="type" value="<?php echo $events[0]['type'];?>" />
		<p>
			<label>事件标题：<input type="text" name="title"
				value="" /></label> <label class="error"></label>
		</p>
		<p>
			<label>事件摘要：<textarea name="summary"></textarea>
			</label> <label class="error"></label>
		</p>
		<p>
			<label>事件发生时间：<input type="text" name="event_time"
				value="" /></label> <label class="error"></label>
		</p>
		<p>
			<input type="submit" value="添加事件" />
		</p>

	</form>
	
</div>


<script>
	$('.del_event').click(function(){
		id = $(this).attr('rel_id');
		$.ajax({
			url : '<?php echo base_url('admin/event/ajax_del_event')?>',
			data : {
				id : id
			},
			dataType : 'json',
			success : function(response){

				if(response.status == 'succ')
				{
					alert('删除成功！');
					location.reload();
				}
				else
				{
					alert('删除成功！');
				}
			}
		});
	});
	
	$('#track_subject').submit(function(){
		$.ajax({
			url : '<?php echo base_url('admin/event/ajax_track_subject');?>',
			data : $('#track_subject').serialize(),
			type : 'post',
			dataType : 'json',
			success : function(response){
				if(response.status == 'succ')
				{
					location.reload();
				}
				else
				{
					alert('追加失败！');
				}
			}
		});
		return false;
	});
</script>

