/**
 * @fileoverview coming soon页面
 * @authors yifei (flylee.bjfu@gmail.com)
 * @date    2013-11-10 11:17:43
 */

var formNode = $('#bookForm');
var submitBtn = $('#submitBtn');
var applyBtn = $('#applyBtn');

formNode.Validform({
	tiptype:function(msg,o,cssctl){
		var objtip = o.obj.parent().siblings().children('.Validform_checktip');
		cssctl(objtip,o.type);
		objtip.text(msg);
	},
	callback : function(data) {
		var nickname = $('#nickname').val();
		var useremail = $('#useremail').val();
		var com_email_prefix = $('#comemail').val();
		var com_email_id = $('#comselect').val();
		var com_email_name = $('#comselect').text();
		var com_email_suffix = $('#comemailSuffix').html();

		var reqData = {
			username : useremail,
			nickname : nickname,
			email : useremail,
			com_email_suffix : com_email_suffix,
			com_email_prefix : com_email_prefix,
			com_email_id : com_email_id
		};
		console.log(reqData);

		// $.ajax({
		// 	type: "POST",
		// 	url: "?c=api&a=user_presign",
		// 	data: reqData,
		// 	dataType: 'json',
		// 	success: function(res) {
		// 		var code = res.code;
		// 		if(code === '0') {
		// 			alert('success');
		// 		}
		// 	},
		// 	complete: function(res) {
		// 	}
		// });
		
		return false;
	}
});
applyBtn.fancybox({
	scrolling : 'hidden'
});

