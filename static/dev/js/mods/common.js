// 站点域名
var siteDomain = 'http://dev.redbeanpie.com/';
// 发起浮层模板
var launchTPL = [
	'<div class="list-group list-group-launch text-center">',
		'<a href="/?c=dating&a=launch" class="list-group-item">',
			'<span class="list-group-icon"><img src="/static/dev/images/icon-launch-dating.png"></span>',
			'<span>约会</span>',
		'</a>',
		'<a href="/?c=party&a=launch" class="list-group-item">',
			'<span class="list-group-icon"><img src="/static/dev/images/icon-launch-party.png"></span>',
			'<span>派对</span>',
		'</a>',
	'</div>'
];

// 发起按钮
if($('#launch-btn').length) {
	$('#launch-btn').popover({
		container : 'body',
		html : true,
		placement : 'bottom',
		animation : false,
		trigger : 'click',
		content : launchTPL.join('')
	});
}
