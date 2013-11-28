var Page = {
	showCompleted : 0,
	toggleShow : function(link) {
		if ( this.showCompleted == 0 ) {
			this.showCompleted = 1;
			$(link).text('Hide completed tasks');
		}
		else if ( this.showCompleted == 1 ) {
			this.showCompleted = 0;
			$(link).text('Show completed tasks');
		}
	},
	reloadList : function() {
		var o = this;
		$.ajax({
			url:'getTaskList.php?showall=' + o.showCompleted,
			type:'POST'
		})
		.done(function(data) {
			var json;
			try {
//				alert(data);
				json = JSON.parse(data);
//				$('#tasks').html(data);
				if ( json.data.tasks ) {
					$('#tasks').html('');
					for(var i=0; i<=json.data.taskorder.length-1; i++ ) {
						var tid = json.data.taskorder[i];
						o.add(json.data.tasks[tid], $('#tasks'));
					}
				}
			}
			catch(e) {
				//throw e;
				alert(data);
			}
		});
	},
	taskLinks : function(el, id) {
		var o = this;
		if ($(el).data('init') == undefined ) {
			o.resetLinks();
			
			var div = $('<span class="links"></span>').appendTo(el);
			$('<a href="#" onclick="return false;">Edit</a>').click(function(evt) {
				main.t.load({loadid:id});
			}).appendTo(div);
			$('<a href="#" onclick="return false;">Add task dependency</a>').click(function(evt) {
				main.t.load({depid:id});
			}).appendTo(div);
			$('<a href="#" onclick="return false;">Add task version</a>').click(function(evt) {
				main.t.load({revid:id});
			}).appendTo(div);
			$('<a href="#" onclick="return false;">Delete</a>').click(function(evt) {
				main.t.del({deleteid:id});
			}).appendTo(div);
			$(el).data('init', '1');
			$(el).data('links', div);

			this.activeSpan = el;
		}
		else {
			this.clearDelay();
		}
	},
	activeSpan : null,
	resetTimeout : null,
	delayReset : function() {
		if ( this.activeSpan ) {
			var o = this;
//			alert(o.resetLinks);
			this.resetTimeout = setTimeout(function() {o.resetLinks()},1000);

		}
	},
	clearDelay : function() {
		clearTimeout(this.resetTimeout);
		this.resetTimeout = null;
	},
	resetLinks : function() {
		this.clearDelay();
		
		$(this.activeSpan).data('init', null);
		$('.links').remove();
		this.activeSpan = null;
	},
	checkTask : function(el,tid) {
		var url = 'checkTask.php'
		var checked = (el.checked)? 'Y' : 'N';
		var data = 'id=' + tid + '&checked=' + checked;
		var o = this;
		$.ajax({
			url:url,
			type:'POST',
			data : data
		})
		.done(function(data) {
			var json;
			try {
				json = JSON.parse(data);
				main.setMsg(json.msg);

				if ( el.checked ) {
					$('.chk-' + tid).parent().addClass('hldone');
				}
				else {
					$('.chk-' + tid).parent().removeClass('hldone');
				}
			}
			catch(e) {
				//throw e;
				//alert(e.message);
				alert(data);
			}
		});
	},
	add : function(item, parent) {
		var newParent;
		var o = this;
		if ( item.task ) {
			var text = '<span class="tasktxt">' + item.task + '</span>';
			if (item.due) {
				text += '<span class="due"> Due ' + item.due + '</span>';
			}
			if (item.assignedTo) {
				text += '<span class="due"> ' + item.assignedTo + '</span>';
			}

			var hlClass = 'hl';
			if ( item.complete == 'Y' ) {hlClass += ' hldone';}

			var checked = '';
			if ( item.complete == 'Y' ) {checked = ' checked="checked"';}

			newParent = $(
				'<li><div class="task task-' + item.id + '"><span class="' + hlClass + '"><input value="' + item.id + '" class="chk-' + item.id + '" type="checkbox"' + checked + '/>' + text + '</span><ul></ul></div></li>'
			)
				.appendTo(parent)
				.find('ul');

			parent.find('.task-' + item.id + ' .hl')
			.mouseover(function(evt) {
				o.taskLinks(this,item.id);
			})
			.mouseout(function(evt) {
				//o.resetLinks();
				o.delayReset();
			});

			parent.find('.chk-' + item.id).click(function(evt) {
				o.checkTask(this, item.id);
			});
		}

		if ( newParent && item.tasks && item.taskorder) {
//			alert(item.taskorder);
			for( var i=0; i<=item.taskorder.length-1; i++ ) {
				var tid = item.taskorder[i];
				this.add(item.tasks[tid], newParent);
			}
//			for ( var id in item.tasks ) {
//				this.add(item.tasks[id], newParent);
//				
//			}
		}
	}

}

main.u = {
	load : function() {
		main.load('getUserData.php');
	},
	save : function() {
		main.save('saveUser.php');
	}
}
main.l = {
	load : function() {
		main.load('getTaskListData.php');
	},
	save : function() {
		main.save('saveTaskList.php');
	}
}
main.t = {
	load : function(qs) {
		main.load('getTaskData.php', qs);
	},
	save : function() {
		main.save('saveTask.php');
	},
	del : function(qs) {
		if ( confirm('Are you sure?') ) {
			main.del('deleteTask.php', qs);
		}
	}
}


$(function() {
//	$('html').mouseup(
//		function( evt ) {
//			Page.delayReset();
//		}
//	);

	$('#addt').click(function(evt) {
		main.t.load();
	});

	$('#showt').click(function(evt) {
		Page.toggleShow(this);
		Page.reloadList();
	});

	if ( $('#changelist').length == 0 ) {
		main.l.load();
	}
	else {
		$('#changelist').change(function(evt) {
			var text = $(this).find(':selected').text();

			if ( text == 'Add new list...') {
				main.l.load();
			}
			else if ( text != 'Change list...' && text != '' ) {
				window.location = 'changeList.php?listid=' + $(this).val();
			}
			else {
				$(this).val("-1");
			}
		});
	}
	Page.reloadList();
})