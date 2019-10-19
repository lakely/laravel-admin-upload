(function(){
  var ringChart = function(canvas_id, curr) {
    var canvas = document.getElementById(canvas_id);
    var total = 100;
    var constrast = parseFloat(curr / total).toFixed(2); //比例
    if(constrast > 1) {return;}
    canvas.height=canvas.height + 0;
    var context = null;
    if (!canvas.getContext) { return;}
    // 定义开始点的大小
    var startArc = Math.PI * 1.5;
    // 根据占的比例画圆弧
    var endArc = (Math.PI * 2) * constrast;
    context = canvas.getContext("2d");
    // 圆心文字
    context.font = "16px Arial";
    context.fillStyle = '#ff801a';
    context.textBaseline = 'middle';
    var text = (Number(curr / total) * 100).toFixed(0) + "%";
    var tw = context.measureText(text).width;
    context.fillText(text, 45 - tw / 2, 45);
    // 绘制背景圆
    context.save();
    context.beginPath();
    context.strokeStyle = "#e7e7e7";
    context.lineWidth = "4";
    context.arc(45, 45, 30, 0, Math.PI * 2, false);
    context.closePath();
    context.stroke();
    context.restore();
    // 若为百分零则不必再绘制比例圆
    if (curr / total === 0) { return;}
    // 绘制比例圆
    context.save();
    context.beginPath();
    context.strokeStyle = "#ff801a";
    context.lineWidth = "4";
    context.arc(45, 45, 30, startArc, (curr % total === 0 ? startArc : (endArc + startArc)), false);
    context.stroke();
    context.restore();

    // 绘制边框
    context.save();
    context.beginPath();
    context.strokeStyle = "#ff801a";
    context.lineWidth = "2";
    context.strokeRect(0,0,90,90);
    context.stroke();
    context.restore();
  };

  var filename_new = '', file_ext = '';

  //指定长度的随机字符串
  function random_string(len) {
    len = len || 32;
    var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var maxPos = chars.length;
    var pwd = '';
    for (i = 0; i < len; i++) {
      pwd += chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
  }
  //获取文件的后缀名
  function get_suffix(filename) {
    var pos = filename.lastIndexOf('.');
    var suffix = '';
    if (pos !== -1) {
      suffix = filename.substring(pos)
    }
    return suffix.toLowerCase();
  }

  // 删除事件
  window.del_pic = function(obj, multi) {
    obj = $(obj);
    if(multi) {
      var upload_warp = obj.parents('div.upload_warp');
      obj.parents('div.upload_item').remove();
      if(upload_warp.find('.upload_item').length === 0) {
        upload_warp.hide();
      }
    }else{
      var warp = obj.parent();
      warp.find('img').remove();
      warp.find('.upload_add_img').show();
      warp.find('input.Js_upload_input').val('');
      warp.find('input.Js_upload_input_id').remove();
    }
  }

  // 图片上传
  window.init_upload = function(id, multi, token){
    var element = $('#'+id);
    var upload_warp = multi ? $(element.attr('data-warp')) : element.parents('.Js_upload_warp');
    var container = $('<div style="height:0px;width:0px;display:none"></div>').appendTo(upload_warp);
    var uploader = new plupload.Uploader({
      runtimes : 'html5,flash,silverlight,html4',
      browse_button : id,//'pickfiles',
      container: container.get(0),//document.getElementById('container'),
      url : '/admin/file-upload',
      flash_swf_url : './plupload-2.1.2/Moxie.swf',
      silverlight_xap_url : './plupload-2.1.2/Moxie.xap',
      multi_selection: multi,//false单选，true多选
      multipart_params: { '_token' : token },
      //过滤
      filters : {
        max_file_size : '10mb',
        mime_types: [
          {title : "Image files", extensions : "jpg,jpeg,gif,png"}
        ]
      },

      init: {
        FilesAdded: function(up, files) {
          plupload.each(files, function(file) {
            if(multi) {
              // 多图
              upload_warp.find('input.Js_upload_input_default').remove();
              upload_warp.css('opacity',1).append('<div class="upload_item" id="'+file.id+'"><canvas id="'+file.id+'_canvas" width="90px" height="90px"></canvas></div>').show();
              Sortable.create(upload_warp.get(0), {
                group: {
                  pull: false,
                  put: false
                },
                handle: 'img',
                ghostClass: 'upload_ghost',
                chosenClass: 'upload_chose',
              });
            }else{
              // 单图
              element.hide();
              upload_warp.prepend('<canvas id="'+file.id+'_canvas" width="90px" height="90px" style="margin-top: 5px;"></canvas>')
            }
            ringChart(file.id+'_canvas', 0);
          });
          uploader.start();//选择文件后立即上传
        },
        BeforeUpload: function(up, file) {
          //设置新文件名
        },
        UploadProgress: function(up, file) {
          ringChart(file.id+'_canvas', file.percent);
        },
        FileUploaded: function(up, file, info) {
          let result = JSON.parse(info.response);
          if (result.code === 30000) {
            alert(result.message);
          } else {
            for(let upload_id in result.data) {
              let path = result.data[upload_id];
              let all_path = result.data[upload_id];
              let collumn_name = id.slice(0, -7);

              if (multi) {
                $('#' + file.id).html('<span class="upload_del_btn" data-filename="' + path + '" onclick="' + "del_pic(this,true)" + '">删除</span><img src="' + all_path + '?x-oss-process=image/resize,m_fill,w_100,h_100"><input type="hidden" class="Js_upload_input" name="'+ collumn_name + '[' + upload_id + ']" value="' + all_path +'">');
              } else {
                $('#' + file.id + '_canvas').remove();
                upload_warp.prepend('<img data-filename="' + path + '" src="' + all_path + '?x-oss-process=image/resize,m_fill,w_100,h_100">');
                upload_warp.prepend('<input type="hidden" class="Js_upload_input_id" name="' + collumn_name +'_id" value="'+ upload_id +'">').find('input.Js_upload_input').val(path);
              }
            }
          }
        },
        Error: function(up, err) {
          alert("抱歉！出错了：" + err.message);
        }
      }
    });
    //初始化上传
    uploader.init();
  }
})();
