
<div class="main">
	<div class="event_filter">
		<form id="filter_form" action="<?php echo base_url('admin/event/subject_list')?>" method="post">
			<label>事件类型： <select name="type">
					<option value="">全部</option>
					<option value="1" <?php echo $type==1 ? 'selected' : '';?>>军事</option>
					<option value="2" <?php echo $type==2 ? 'selected' : '';?>>社会</option>
					<option value="3" <?php echo $type==3 ? 'selected' : '';?>>娱乐</option>
			</select>
			</label> <label>事件名称： <input type="text" name="search_title" value="<?=$search_title;?>" />
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
					href="<?php echo base_url('admin/event/subject_detail/'.$event['subject_id']);?>">详细</a>
					/ <a href="javascript:void(0);" class="del_event"
					rel_id="<?php echo $event['subject_id']?>">删除</a></td>
			</tr>
		<?php endforeach;?>
	</table>
	</div>
</div>

<script>
	$('.del_event').click(function(){
		id = $(this).attr('rel_id');
		$.ajax({
			url : '<?php echo base_url('admin/event/ajax_del_subject')?>',
			data : {
				id : id
			},
			dataType : 'json',
			success : function(response){
				if(response.status == 'succ')
				{
					alert("删除成功！");
				}
				else
				{
					alert("删除失败");
				}
			}
		});
	});
</script>

