<div class="main">
	<h3>新增事件</h3>
	<form action="<?php echo site_url('admin/event/ajax_mod_event');?>"
		method="post" enctype="application/x-www-form-urlencoded" id="mod_event">
		<input type="hidden" name="id" value="<?php echo $event['id'];?>" />
		<input type="hidden" name="type" value="<?php echo $event['type'];?>" />
		<p>
			<label>事件标题：<input type="text" name="title" id="title"
				value="<?=$event['title']?>" /></label> <label for="title" class="error"></label>
		</p>
		<p>
			<label>事件摘要：<textarea name="summary" id="summary"><?=$event['summary']?></textarea>
			</label> <label for="summary" class="error"></label>
		</p>
		<p>
			<label>事件发生时间：<input type="text" name="event_time" id="event_time"
				value="<?=date('Y-m-d H:i:s', $event['event_time'])?>" /></label> <label for="event_time" class="error"></label>
		</p>
		<p>
			<input type="submit" value="确认修改" />
		</p>

	</form>
</div>
<script>
	$("#mod_event").submit(function(){
		$.ajax({
			url : '<?php echo base_url('admin/event/ajax_mod_event')?>',
			dataType : 'json',
			type : 'POST',
			data : $("#mod_event").serialize(),
			success : function(response){
				if(response.status == 'succ')
				{
					alert("修改成功！");
				}
				else
				{
					alert("修改失败！");
				}
			}
		});
		return false;
	});
</script>