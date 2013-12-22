/**
 * @fileoverview guest登录注册，未登录主页
 * @authors yifei (flylee.bjfu@gmail.com)
 * @date    2013-12-16
 */

var elSigninForm = $('#signin-form');
var elSignupStaffForm = $('#signup-staff-form');
var elSignupCodeForm = $('#signup-code-form');

// 登录表单
if(elSigninForm.length) {
	var signinValid = elSigninForm.Validform({
		tiptype : 2,
		ajaxPost : true
	});

	signinValid.config({
		ajaxpost : {
			url : '?c=guest&a=login',
			timeout : 5000,
			success : function(res) {
				var code = res.code;
				var msg = res.msg;
				switch(code) {
					case '0' :
						window.location.href = '?c=dashboard';
						break;
					case '1' :
						$.Showmsg(msg);
						break;
					case '2' :
						$.Showmsg(msg);
						break;
					default :
						$.Showmsg('系统繁忙');
				}
			},
			error : function(res) {
				console.log(res);
				
			},
			complete : function(res) {
			}
		}
	});

	signinValid.tipmsg.r = ' ';
}

// 注册
// 公司邮箱注册
if(elSignupStaffForm.length) {
	var staffFormValid = elSignupStaffForm.Validform({
		tiptype : 2,
		ajaxPost : true
	});

	staffFormValid.config({
		ajaxpost : {
			url : '?c=guest&a=signup_staff',
			timeout : 5000,
			success : function(res) {
				var code = res.code;
				var msg = res.msg;
				if(code === '0') {
					$.Showmsg('');
					window.location.href = '?c=guest&a=signup_checkemail';
				}
			},
			error : function(res) {
				console.log(res);
			},
			complete : function(res) {
			}
		}
	});

	staffFormValid.tipmsg.r = ' ';

	var elComVisible = elSignupStaffForm.find('[name="company_visible"]');
	var elComSelect = elSignupStaffForm.find('[name="company_select"]');
	var elComNameInput = elSignupStaffForm.find('input[name="company_name"]');
	var elComSuffix = elSignupStaffForm.find('[data-role="com_email_suffix"]');
	var elComSuffixInput = elSignupStaffForm.find('input[name="com_email_suffix"]');

	// 公司是否隐藏checkbox
	elComVisible.on('click', function(){
		if($(this).val() === '1') {
			$(this).val('0');
		}else {
			$(this).val('1');
		}
	});

	// 公司select列表
	elComSelect.on('change', function(){
		var suffix = $(this).find('option:selected').attr('suffix');
		var comname = $(this).find('option:selected').text().trim();
		elComSuffix.html(suffix);
		elComSuffixInput.val(suffix);
		elComNameInput.val(comname);
	});
}
// 邀请码注册
if(elSignupCodeForm.length) {
	var codeFormValid = elSignupCodeForm.Validform({
		tiptype : 2,
		ajaxPost : true
	});

	codeFormValid.config({
		ajaxpost : {
			url : '?c=guest&a=signup_code',
			timeout : 5000,
			success : function(res) {
				var code = res.code;
				var msg = res.msg;
				if(code === '0') {
					$.Showmsg('');
					window.location.href = '?c=guest&a=signup_checkemail';
				}
			},
			error : function(res) {
				console.log(res);
			},
			complete : function(res) {
			}
		}
	});

	codeFormValid.tipmsg.r = ' ';
}

// jcarousel
var jcarousel = $('.jcarousel');

jcarousel
    .on('jcarousel:reload jcarousel:create', function () {
        var width = jcarousel.innerWidth();

        if (width >= 600) {
            width = width / 5;
        } else if (width >= 350) {
            width = width / 3;
        }

        jcarousel.jcarousel('items').css('width', width + 'px');
    })
    .jcarousel({
        wrap: 'circular'
    });

$('.jcarousel-control-prev')
    .jcarouselControl({
        target: '-=3'
    });

$('.jcarousel-control-next')
    .jcarouselControl({
        target: '+=3'
    });

$('.jcarousel-pagination')
    .on('jcarouselpagination:active', 'a', function() {
        $(this).addClass('active');
    })
    .on('jcarouselpagination:inactive', 'a', function() {
        $(this).removeClass('active');
    })
    .on('click', function(e) {
        e.preventDefault();
    })
    .jcarouselPagination({
        perPage: 1,
        item: function(page) {
            return '<a href="#' + page + '">' + page + '</a>';
        }
    });


	



