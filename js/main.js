var main = {
	dateOptions : {
		changeMonth : true,
		changeYear : true,
		currentText : "Today",
		dateFormat : "dd-M-yy",
		duration : "", // So disables display animation
		gotoCurrent : true,
		hideIfNoPrevNext:true,
//		minDate : new Date(minYear, 0, 1),
//		maxDate : new Date(maxYear, 0, 1),
		showButtonPanel : false,
		showOtherMonths : false,
		yearRange: "1900:2020"
	},
	clrMsgs : function() {
		$('#msg').html('');
	},
	setMsg : function(msg) {
		$('#msg').html(msg);
	},
	onDepChange : function(el) {
		var o = this;
		var val = $(el).val();
		var numSelect = $('.dep').length;
		var numOpt = el.options.length - 1; // Exclude blank option
		var name = 'dep' + (numSelect+1);
		if ( val.length > 0 && numSelect != numOpt ) {
			var l = $('<label class="dep"></label>').insertAfter(el.parentNode);
			var s = $('<select id="dep' + name + '" name="' + name + '"></select>').appendTo(l)
			.change(function(evt) {o.onDepChange(this);});
			
			for(var i = 0; i<=el.options.length-1; i++ ) {
				s.append(new Option(el.options[i].text,el.options[i].value));
			}
		}

	},
	show : function(html) {
		this.clrMsgs();
		$('#f').html('').append(html)
		.css({top: $('html').scrollTop() + 'px'})
		.show();

		if($.datepicker) {
			$('#due').datepicker(this.dateOptions);
		}

		var o = this;
		$('.dep select').change(function(evt) {
			o.onDepChange(this);
		});

		$('#desc').focus();

		//$('#tasks, #msg, #addt').hide();
	},
	cancel : function() {
		$('#f').html('').hide();
		//$('#tasks, #msg, #addt').show();
	},
	load : function(url, qs) {
		if ( qs ) {
			var s = [];
			for ( var i in qs ) {
				s.push(i + '=' + qs[i]);
			}
			url += '?' + s.join('&');
		}
		var o = this;
		$.ajax({
			url:url,
			type:'POST'
		})
		.done(function(data) {
			try {
				o.show(data);
			}
			catch(e) {
				//alert(e.message);
				alert(data);
			}
		});
	},
	save : function(url) {
		var o = this;

		$.ajax({
			url:url,
			type:'POST',
			data : $('#f').serialize()
		})
		.done(function(data) {
			var json;
			try {
				json = JSON.parse(data);
				o.cancel();
				o.setMsg(json.msg);
				Page.reloadList();
			}
			catch(e) {
				//throw e;
				//alert(e.message);
				alert(data);
			}
		});
	},
	del : function(url, qs) {
		var data;
		if ( qs ) {
			var s = [];
			for ( var i in qs ) {
				s.push(i + '=' + qs[i]);
			}
			data = s.join('&');
		}
		var o = this;
		$.ajax({
			url:url,
			data:data,
			type:'POST'
		})
		.done(function(data) {
			var json;
			try {
				json = JSON.parse(data);
				o.cancel();
				o.setMsg(json.msg);
				Page.reloadList();
			}
			catch(e) {
				//throw e;
				//alert(e.message);
				alert(data);
			}
		});
	}
}
