/**
 * @fileoverview coming soon页面
 * @authors yifei (flylee.bjfu@gmail.com)
 * @date    2013-11-10 11:17:43
 */

var formNode = $('#bookForm');
var submitBtn = $('#submitBtn');
var applyBtn = $('#applyBtn');
var comselect = $('#comselect');
var defaultComemail = $('#default-comemail-group');
var otherComemail = $('#other-comemail-group');
var comemailSuffix = $('#comemailSuffix');
var otherComname = $('#other-comname');

// 预定表单验证
var bookForm = formNode.Validform({
	tiptype:function(msg,o,cssctl){
		var objtip = o.obj.parent().siblings().children('.Validform_checktip');
		cssctl(objtip,o.type);
		objtip.text(msg);
	},
	callback : function(data) {
		var nickname = $('#nickname').val();
		var useremail = $('#useremail').val();
		var gender = $('#gender').val();
		var com_email_prefix = $('#comemail').val();
		var com_email_id = $('#comselect').val();
		var com_name = $('#comselect').text();
		var com_email_suffix = $('#comemailSuffix').html().replace('@', '');

		var reqData = {
			username : useremail,
			nickname : nickname,
			gender : gender,
			email : useremail,
			com_email_suffix : com_email_suffix,
			com_email_prefix : com_email_prefix,
			com_email_id : com_email_id,
			com_name : com_name
		};
		// 其他企业
		if(com_email_id === '0') {
			var email_split = com_email_prefix.spilt('@');
			reqData.com_email_prefix = email_split[0];
			reqData.com_email_suffix = email_split[1];
			reqData.com_name = otherComname.val();
		}

		$.ajax({
			type: "POST",
			url: "?c=api&a=user_presign",
			data: reqData,
			dataType: 'json',
			success: function(res) {
				var code = res.code;
				if(code === '0') {
					alert('预定成功！');
					bookForm.resetForm();
				}
			},
			complete: function(res) {
			}
		});
		
		return false;
	}
});
bookForm.ignore('#other-comname,#other-comemail-group input');

// 申请按钮弹层
applyBtn.fancybox({
	scrolling : 'hidden',
	padding : [30, 10, 30, 10]
});
// 公司切换
comselect.on('change', function(){
	var comid = $(this).val();

	if(comid !== '0') {
		var com_email_suffix = $(this).find('option:selected').attr('suffix');
		comemailSuffix.html('@' + com_email_suffix);
		defaultComemail.show();
		otherComemail.hide();
		otherComname.hide();

		bookForm.unignore('#default-comemail-group input');
		bookForm.ignore('#other-comname,#other-comemail-group input');
	}else {
		defaultComemail.hide();
		otherComemail.show();
		otherComname.show();

		bookForm.unignore('#other-comname,#other-comemail-group input');
		bookForm.ignore('#default-comemail-group input');
	}

	// bookForm.resetForm();

});

