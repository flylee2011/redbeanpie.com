/**
 * @fileoverview 后台管理
 * @authors yifei (flylee.bjfu@gmail.com)
 * @date    2013-12-08
 */

var elAddIndustryForm = $('#addIndustryForm');
var elEditIndustryForm = $('#editIndustryForm');
var elAddComForm = $('#addComForm');
var elEditComForm = $('#editComForm');
var elUpdateComLink = $('#cominfo-table').find('[data-role="updatelink"]');

// 添加行业信息
if(elAddIndustryForm.length) {
	var addIndustryValid = elAddIndustryForm.Validform({
		tiptype : 2,
		ajaxPost : true
	});
	addIndustryValid.config({
		ajaxpost : {
			url : '?c=admin&a=add_industryinfo_api',
			timeout : 5000,
			success : function(res) {
				var status = res.status;
				var msg = res.info;
				if(res.status === 'y') {
					$.Showmsg("添加成功");
				}else {
					$.Showmsg("系统繁忙");
				}
			},
			error : function(res) {
				console.log(res);
				$.Showmsg("系统繁忙");
			},
			complete : function() {
				addIndustryValid.resetForm();
				window.setTimeout(function() {
					$.Hidemsg();
				}, 1000);
			}
		}
	});
	addIndustryValid.tipmsg.r = 'ok';
}
// 编辑行业信息
if(elEditIndustryForm.length) {
	var editIndustryValid = elEditIndustryForm.Validform({
		tiptype : 2,
		ajaxPost : true
	});
	editIndustryValid.config({
		ajaxpost : {
			url : '?c=admin&a=edit_industryinfo_api',
			timeout : 5000,
			success : function(res) {
				var status = res.status;
				var msg = res.info;
				if(res.status === 'y') {
					$.Showmsg("修改成功");
					window.setTimeout(function() {
						window.location.href = 'http://redbeanpie.com/admin.php?c=admin&a=industryinfo';
					}, 1000);
				}else {
					$.Showmsg("系统繁忙");
				}
			},
			error : function(res) {
				console.log(res);
				$.Showmsg("系统繁忙");
			},
			complete : function() {
				window.setTimeout(function() {
					$.Hidemsg();
				}, 1000);
			}
		}
	});
	editIndustryValid.tipmsg.r = 'ok';
}

// 添加公司信息
if(elAddComForm.length) {
	var addComValid = elAddComForm.Validform({
		tiptype : 2,
		ajaxPost : true
	});
	addComValid.config({
		ajaxpost : {
			url : '?c=admin&a=add_cominfo_api',
			timeout : 5000,
			success : function(res) {
				var status = res.status;
				var msg = res.info;
				if(status === 'y') {
					$.Showmsg("添加成功");
				}else {
					$.Showmsg("系统繁忙");
				}
			},
			error : function(res) {
				console.log(res);
				$.Showmsg("系统繁忙");
			},
			complete : function() {
				addComValid.resetForm();
				window.setTimeout(function() {
					$.Hidemsg();
				}, 1000);
			}
		}
	});
	addComValid.tipmsg.r = 'ok';
}

// 编辑公司信息
if(elEditComForm.length) {
	var editComValid = elEditComForm.Validform({
		tiptype : 2,
		ajaxPost : true
	});
	editComValid.config({
		tiptype : 2,
		ajaxpost : {
			url : '?c=admin&a=edit_cominfo_api',
			timeout : 5000,
			success : function(res) {
				var status = res.status;
				var msg = res.info;
				if(status === 'y') {
					$.Showmsg("修改成功");
					window.setTimeout(function() {
						$.Hidemsg();
						window.location.href = 'http://redbeanpie.com/admin.php?c=admin&a=cominfo';
					}, 1000);
				}else {
					$.Showmsg("系统繁忙");
				}
			},
			error : function(res) {
				console.log(res);
				$.Showmsg("系统繁忙");
			}
		}
	});
	editComValid.tipmsg.r = 'ok';
}

// 更新公司状态，开放或关闭
if(elUpdateComLink.length) {
	elUpdateComLink.on('click', function(e){
		var reqdata = {
			com_id : $(this).attr('comid'),
			is_active : $(this).attr('isactive')
		};
		
		$.ajax({
			url : '?c=admin&a=update_comactive_api',
			data : reqdata,
			dataType : 'json',
			success : function(res) {
				var status = res.status;
				var msg = res.info;
				if(status === 'y') {
					alert(msg);
					window.location.href = 'http://redbeanpie.com/admin.php?c=admin&a=cominfo';
				}else {
					alert('系统繁忙');
				}
			},
			error : function(res) {
				console.log('error');
				alert('系统繁忙');
			}
		});
	});
}