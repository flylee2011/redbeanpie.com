/**
 * @fileoverview coming soon页面
 * @authors yifei (flylee.bjfu@gmail.com)
 * @date    2013-11-10 11:17:43
 */

var formNode = $('#bookForm');
var submitBtn = $('#submitBtn');
var applyBtn = $('#applyBtn');
// 公司select
var elComSelect = $('#comselect');
// 默认公司group区域
var elDefaultComEmail = $('#default-comemail-group');
// 其他公司group区域
var elOtherComEmail = $('#other-comemail-group');
// 默认公司后缀
var elComEmailSuffix = $('#comemailSuffix');
// 其他公司名input
var elOtherComName = $('#other-comname-row');

// 申请按钮弹层
applyBtn.fancybox({
	scrolling : 'hidden',
	padding : [30, 10, 30, 10],
	afterClose : function() {
		resetBookForm();
	}
});

// 预定表单验证
var bookForm = formNode.Validform({
	ajaxPost : true,
	tiptype : function(msg,o,cssctl){
		var objtip = o.obj.parent().siblings().children('.Validform_checktip');
		cssctl(objtip,o.type);
		objtip.text(msg);
	},
	callback : function(data) {
		var nickname = $('#nickname').val();
		var useremail = $('#useremail').val();
		var gender = $('#gender').val();
		var com_email_prefix = $('#default-comemail-input').val();
		var com_email_id = elComSelect.val();
		var com_name = elComSelect.find('option:selected').text();
		var com_email_suffix = elComEmailSuffix.html().replace('@', '');

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
			var email_split = $('#other-comemail-input').val().split('@');
			reqData.com_email_prefix = email_split[0];
			reqData.com_email_suffix = email_split[1];
			reqData.com_name = $('#other-comname').val();
		}
		console.log(reqData);
		$.ajax({
			type: 'POST',
			url: '?c=api&a=user_presign',
			data: reqData,
			dataType: 'json',
			success: function(res) {
				var code = res.code;
				if(code === '0') {
					alert('预定成功！');
					resetBookForm();
				}
			},
			complete: function(res) {
			}
		});
		
		return false;
	}
});
bookForm.ignore('#other-comname,#other-comemail-input');

// 公司切换
elComSelect.on('change', function(){
	var comid = $(this).val();
	console.log(comid);
	resetBookForm();

	if(comid === '0') {
		elComSelect.find('option').attr('selected', false);
		elComSelect.find('option[value=0]').attr('selected', 'selected');
		elDefaultComEmail.hide();
		elOtherComEmail.show();
		elOtherComName.show();

		bookForm.unignore('#other-comname,#other-comemail-input');
		bookForm.ignore('#default-comemail-input');
	}else {
		elComSelect.find('option').attr('selected', false);
		elComSelect.find('option[value='+comid+']').attr('selected', 'selected');
		var com_email_suffix = $(this).find('option:selected').attr('suffix');
		elComEmailSuffix.html('@' + com_email_suffix);
		$('#default-comemail-input').attr('ajaxurl', '?c=api&a=check_presign&com_email_suffix=' + com_email_suffix);

		elDefaultComEmail.show();
		elOtherComEmail.hide();
		elOtherComName.hide();
	}

	// if(comid !== '0') {
	// 	var com_email_suffix = $(this).find('option:selected').attr('suffix');
	// 	elComEmailSuffix.html('@' + com_email_suffix);
	// 	$('#default-comemail-input').attr('ajaxurl', '?c=api&a=check_presign&com_email_suffix=' + com_email_suffix);

	// 	elDefaultComEmail.show();
	// 	elOtherComEmail.hide();
	// 	elOtherComName.hide();

	// 	bookForm.unignore('#default-comemail-input');
	// 	bookForm.ignore('#other-comname,#other-comemail-input');
	// }else {
	// 	elDefaultComEmail.hide();
	// 	elOtherComEmail.show();
	// 	elOtherComName.show();

	// 	bookForm.unignore('#other-comname,#other-comemail-input');
	// 	bookForm.ignore('#default-comemail-input');
	// }

});

// 重置表单
function resetBookForm() {

	elOtherComName.hide();
	elOtherComEmail.hide();

	bookForm.resetForm();
	elDefaultComEmail.show();
	elComSelect.find('option').attr('selected', false);
	elComSelect.find('option').selectedIndex = 0;
	var com_email_suffix = elComSelect.find('option:selected').attr('suffix');
	$('.Validform_checktip').html('');
	elComEmailSuffix.html('@' + com_email_suffix);
	$('#default-comemail-input').attr('ajaxurl', '?c=api&a=check_presign&com_email_suffix=' + com_email_suffix);
	bookForm.unignore('#default-comemail-input');
	bookForm.ignore('#other-comname,#other-comemail-input');

}

