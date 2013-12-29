/**
 * @fileoverview 个人主页
 * @authors yifei (flylee.bjfu@gmail.com)
 * @date    2013-12-28
 */

var elAvatarImg = $('#avatar img');
var elAvatarLink = $('#avatar a');
var elAvatarModal = $('#avatarModal');
var elUploadAvatarAlbum = $('#upload-from-album');

elAvatarLink.on('click', function(e){

	var imgUrl = 'http://dev.redbeanpie.com' + elAvatarImg.attr('src');
	// xiuxiu.setLaunchVars("preventBrowseDefault", 1);
	// xiuxiu.setLaunchVars("preventUploadDefault", 1);
	// 第1个参数是加载编辑器div容器，第2个参数是编辑器类型，第3个参数是div容器宽，第4个参数是div容器高
	xiuxiu.embedSWF("xiuxiuContent", 5, "100%", "100%");
	// 上传接收图片程序地址
	xiuxiu.setUploadURL("http://dev.redbeanpie.com/?c=api&a=upload_file");
	xiuxiu.onInit = function () {
		// 修改为要处理的图片url
		xiuxiu.loadPhoto(imgUrl);
		xiuxiu.setUploadType(1);

		// xiuxiu.setUploadType(2);
		// xiuxiu.setUploadDataFieldName("upload_file");

	};
	xiuxiu.onBeforeUpload = function(data, id) {
		var size = data.size;
		if(size > 2 * 1024 * 1024)
		{
		alert("图片不能超过2M");
		return false;
		}
		return true;
	};
	xiuxiu.onUploadResponse = function(data) {
		alert('上传成功');
		console.log('上传响应', data);
		//alert("上传响应" + data); 可以开启调试
	};
	xiuxiu.onDebug = function(data)
	{
		console.log('错误响应', data);
	};

	elAvatarModal.modal();
});

elUploadAvatarAlbum.on('click', function(e){

	var imgUrl = 'http://dev.redbeanpie.com' + elAvatarImg.attr('src');

		xiuxiu.loadPhoto(imgUrl);
	
});



