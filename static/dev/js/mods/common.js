// 站点域名
var siteDomain = 'http://dev.redbeanpie.com/';
// 发起浮层模板
var launchTPL = [
	'<div class="list-group list-group-launch text-center">',
		'<a href="/?c=party&a=launch" class="list-group-item">',
			'派对',
		'</a>',
		'<a href="/?c=dating&a=launch" class="list-group-item">',
			'约会',
		'</a>',
	'</div>'
];
// 发起按钮
$('#launch-btn').popover({
	container : 'body',
	html : true,
	placement : 'bottom',
	content : launchTPL.join('')
});