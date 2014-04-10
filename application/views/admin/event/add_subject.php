<div class="main">
	<h3>新增事件</h3>
	<form action="<?php echo site_url('admin/event/add_subject');?>"
		method="post" enctype="application/x-www-form-urlencoded">
		<p>
			<label>事件类型： <select name="type">
					<option value="1" <?=set_select('type', '1');?>>军事</option>
					<option value="2" <?=set_select('type', '2');?>>社会</option>
					<option value="3" <?=set_select('type', '3');?>>娱乐</option>
			</select>
			</label> <label class="error"><?=form_error('type');?></label>
		</p>
		<p>
			<label>事件标题：<input type="text" name="title"
				value="<?=set_value('title');?>" /></label> <label class="error"><?=form_error('title');?></label>
		</p>
		<p>
			<label>事件摘要：<textarea name="summary"><?=set_value('summary');?></textarea>
			</label> <label class="error"><?=form_error('summary');?></label>
		</p>
		<p>
			<label>事件发生时间：<input type="text" name="event_time"
				value="<?=set_value('event_time');?>" /></label> <label class="error"><?=form_error('event_time');?></label>
		</p>
		<p>
			<input type="submit" value="添加" />
		</p>

	</form>
</div>