$(function() {
	$('#changeuser').change(function(evt) {
		window.location.href = window.location.pathname + '?id=' + $(this).val();
	});
});

main.c = {
	load : function(tid) {
		main.load('getComments.php', {loadid:tid});
	},
	save : function(tid) {
		main.save('saveComments.php');
		Page.lastTid = $('#id').val();
		Page.lastComments = $('#comments').val();
	}
}

var Page = {
	lastTid : 0,
	lastComments : '',
	reloadList : function() {
		var c = this.lastComments;
		c = c.replace(/\n/g, "<BR/>");
		c = c.replace(/\r/g, "<BR/>");
		$('#comments-' + this.lastTid).html(c);
	}
}