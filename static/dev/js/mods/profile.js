/**
 * @fileoverview 个人主页
 * @authors yifei (flylee.bjfu@gmail.com)
 * @date    2013-12-28
 */

// 头像节点
var elAvatarBox = $('#profile-avatar-box');
var elAvatarImg = elAvatarBox.find('img');
var elAvatarLink = elAvatarBox.find('a');
// 相册列表
var elAlbumList = $('#profile-album-ul');
var elAddImg = elAlbumList.find('li.add-photo');
var elUploadImgBtn = $('#upload-img-btn');
// 上传头像浮层
var elAvatarModal = $('#modal-avatar');
// 上传相册照片浮层
var elAlbumModal = $('#modal-album');
var elPreviewImg = elAlbumModal.find('.img-preview-box img');
var elDesText = elAlbumModal.find('textarea');
var elSubmitAlbumBtn = elAlbumModal.find('.btn-submit');

// 相册列表设置宽度
if(elAlbumList.length) {
	var itemLen = elAlbumList.find('li').length;
	elAlbumList.css('width', (100 * itemLen) + 'px');
}

// 相册浮层
if(elAddImg.length) {
	elAddImg.on('click', function(e){
		elAlbumModal.modal();
	});
}
// 相册图片上传
if(elUploadImgBtn.length) {
	elUploadImgBtn.uploadify({
		swf : '/static/dev/js/lib/uploadify.swf',
		uploader : '/?c=api&a=upload_img',
		buttonText : '本地上传',
		fileTypeExts : '*.gif; *.jpg; *.jpge; *.png',
		fileSizeLimit : '5MB',
		multi : false,
		onUploadSuccess : function(file, data, response) {
			var res = $.parseJSON(data);
			var imgPath = res.data.img_path;
			
			elPreviewImg.attr({
				'src' : imgPath
			});
			elPreviewImg.parent('a').attr({
				'href' : imgPath
			});

		},
		onUploadError : function(file, errorCode, errorMsg, errorString) {
			console.log(errorCode);
		}
	});
}
// 添加图片信息到数据库
if(elSubmitAlbumBtn.length) {
	elSubmitAlbumBtn.on('click', function(e){
		var self = $(this);
		var reqdata = {
			img_path : elPreviewImg.attr('src'),
			description : elDesText.val().trim()
		};
		self.attr('disabled', true).html('提交中...');

		$.ajax({
			type : 'POST',
			dataType : 'json',
			url : '/?c=api&a=add_img_info',
			data : reqdata,
			success : function(res) {
				var code = res.code;
				self.attr('disabled', false).html('确定');
				switch(code) {
					case 'S00001' :
						alert('上传成功');
						window.location.reload();
						break;
					case 'E10002' :
						alert('上传失败');
						break;
					default :
						alert('系统繁忙');
						break;
				}
			},
			error : function(res) {

			}
		});
	});
}

// 头像上传
if(elAvatarLink.length) {
	elAvatarLink.on('click', function(e){
		var imgUrl = 'http://dev.redbeanpie.com' + elAvatarImg.attr('src');
		// 第1个参数是加载编辑器div容器，第2个参数是编辑器类型，第3个参数是div容器宽，第4个参数是div容器高
		xiuxiu.embedSWF("xiuxiuContent", 5, "100%", "100%");
		
		xiuxiu.setUploadURL("http://dev.redbeanpie.com/?c=api&a=upload_avatar");
		// 初始化
		xiuxiu.onInit = function () {
			// 修改为要处理的图片url
			xiuxiu.loadPhoto(imgUrl);
			xiuxiu.setUploadType(1);
		};
		xiuxiu.onBeforeUpload = function(data, id) {
			var size = data.size;
			if(size > 5 * 1024 * 1024) {
				alert("图片不能超过5M");
				return false;
			}
			return true;
		};
		xiuxiu.onUploadResponse = function(data) {
			var res = $.parseJSON(data);

			switch(res.code) {
				case 'S00001' :
					alert('上传成功');
					window.location.reload();
					break;
				case 'E10002' :
					alert('上传失败');
					break;
				case 'E10002' :
					alert('请选择图片');
					break;
				default :
					alert('系统繁忙');
					break;
			}
		};
		xiuxiu.onDebug = function(data) {
			alert('系统繁忙，上传失败');
			console.log('错误响应', data);
		};

		elAvatarModal.modal();
	});
}


