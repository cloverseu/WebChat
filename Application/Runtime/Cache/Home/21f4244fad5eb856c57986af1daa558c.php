<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>聊天室</title>

  <!-- Bootstrap -->
  <link href="../../../Public/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../../Public/css/chat.css" rel="stylesheet" media="screen">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>

  <div id="backpic"></div>

  <!-- <div class="music">
  <audio controls="controls" autoplay="autoplay">
    <source src="song.ogg" type="audio/ogg" />
    <source src="../../../Public/music/song.mp3" type="audio/mpeg" />
    Your browser does not support the audio element.
  </audio>
</div>
-->
<div class="left_container" id="left_container">
  <div class="panel panel-default">
    <div class="panel-body">
      <span class="glyphicon glyphicon-remove" id="out"></span>
      <img class="media-object img-thumbnail" src="../../../Public/uploads/mini/<?php echo ($id); ?>.jpg" alt="头像" id="picture" data-toggle="modal" data-target="#myModal">
      <p class="text-center">Friend</p>
      <div class="col-lg-12">
        <div class="input-group">
          <input type="text" id="add_id" class="form-control">
          <span class="input-group-btn">
            <button id="add" class="btn btn-default" type="button">添加</button>
          </span>
        </div>
        <!-- /input-group --> </div>
      <!-- /col-lg--> </div>
    <!-- /panel-body--> </div>
  <!-- /panel panel-default-->

  <div class="list">
    <div class="panel-group" id="accordion">
      <?php if(is_array($friendList)): $i = 0; $__LIST__ = $friendList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo ($list["id"]); ?>" >
                  <?php
 if($list['status'] == "1"){?> <font color="blue"><?php }else{?> <font color="grey"><?php } echo ($list["username"]); ?></font>
                    <div>
                      <span class="badge" id="<?php echo ($list["id"]); ?>news"></span>
                    </div>
                  </a>
                </h4>
              </div>
              <div id="<?php echo ($list["id"]); ?>" class="panel-collapse collapse">
                <div class="panel-body">
                  <div class="media" getter="<?php echo ($list["username"]); ?>" getterid="<?php echo ($list["id"]); ?>">
                    <div class="pull-left">
                      <img class="media-object img-thumbnail" src="../../../Public/uploads/mini/<?php echo ($list["img"]); ?>.jpg" alt="..."></div>
                    <div class="media-body">
                      <h4 class="media-heading"></h4>
                      用户id:<?php echo ($list["id"]); ?>
                    </div>
                  </div>
                </div>
              </div>
            </div><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
      </div>

      <ul class="nav nav-pills" role="tablist">
        <div class="navbar-header">
          <a class="navbar-brand">
            <img alt="Brand"  id="notice" src="../../../Public/img/notice.png" data-toggle="modal" data-target="#noticeList">
            <span class="badge" id="noticeBadge"></span>
          </a>
        </div>
        <div class="navbar-header">
          <a class="navbar-brand" >
            <img alt="Brand" id="service" src="../../../Public/img/service.png" data-toggle="modal" data-target="#serviceList"></a>
        </div>
        <div class="navbar-header" >
          <a class="navbar-brand" >
            <img alt="Brand" id="eamil" src="../../../Public/img/email.png"  data-toggle="modal" data-target="#emailList"></a>
        </div>
      </ul>

    </div>
  </div>

  <div class="chat_panel" id="close_chat" style="display:none">
    <div class="panel panel-default">
      <div class="panel-heading">
        <span class="glyphicon glyphicon-remove" id="close"></span>
      </div>
      <div class="panel-body">
        <div id="chatRoom"></div>
        <div id="receiveMsg"></div>
      </div>
      <div class="panel-footer">
        <div id="sendMessage"></div>
      </div>
    </div>
  </div>
</div>

<!--Picture Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
          <span class="sr-only">Close</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">更换头像</h4>
      </div>
      <div class="modal-body">
        <form  method="post">
          <div style="width: 500px; height: auto; border: 1px solid #e1e1e1; font-size: 12px; padding: 10px;">
            <span id="spanButtonPlaceholder"></span>
            <div id="divFileProgressContainer"></div>
            <div id="thumbnails">
              <ul id="pic_list" style="margin: 5px;list-style-type:none"></ul>
              <div style="clear: both;"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary">确认</button>
      </div>
    </div>
  </div>
</div>

<!-- serviceList modal -->

<div class="modal fade" id="serviceList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
          <span class="sr-only">Close</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">常用功能</h4>
      </div>
      <div class="modal-body">
        <div class="weather">天气查询</div>
      </br>
      <div class="input-group col-lg-6">
        <input type="text" class="form-control"  id="city" placeholder="请输入城市">
        <span class="input-group-btn">
          <button class="btn btn-default" type="button" id="weather">Go!</button>
        </span>
      </div>
      <!-- /input-group -->
      <div id="showWeather"></div>
      <hr>
      <div>本周新片推荐</div>
    </br>
    <button type="button" class="btn btn-primary" id="movie">查询</button>
    <div id="showMovie"></div>
    <hr>
    <div>四六级成绩查询</div>
  </br>
  <div class="row">
    <div class="col-lg-6">
      <div class="input-group">
        <span class="input-group-addon">准考号</span>
        <input type="text" id="cet_id" class="form-control"></div>
      <!-- /input-group --> </div>
    <!-- /.col-lg-6 -->
    <div class="col-lg-6">
      <div class="input-group">
        <span class="input-group-addon">姓名</span>
        <input type="text" id="cet_name" class="form-control"></div>
      <!-- /input-group --> </div>
    <!-- /.col-lg-6 --> </div>
</br>
<!-- /.row -->
<button type="button" class="btn btn-primary" id="cet">查询</button>
<div id="showCet"></div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
<button type="button" class="btn btn-primary">确认</button>
</div>
</div>
</div>
</div>

<!-- noticeList modal -->

<div class="modal fade" id="noticeList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-sm">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">
  <span aria-hidden="true">&times;</span>
  <span class="sr-only">Close</span>
</button>
<h4 class="modal-title" id="myModalLabel">添加好友通知</h4>
</div>
<div class="modal-body">
<?php if(is_array($messageName)): $i = 0; $__LIST__ = $messageName;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$stg): $mod = ($i % 2 );++$i; if(is_array($stg)): $i = 0; $__LIST__ = $stg;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$others): $mod = ($i % 2 );++$i;?><div id = "<?php echo ($others["id"]); ?>deal">
      <span><?php echo ($others["username"]); ?>请求加您为好友</span>
      <button type="button" class="btn btn-primary deal" dealId = "<?php echo ($others["id"]); ?>" idType="agree">同意</button>
      <button type="button" class="btn btn-primary deal" dealId = "<?php echo ($others["id"]); ?>" idType="igone">忽略</button>
      <hr></div><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<div class="modal fade" id="emailList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-sm">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">
  <span aria-hidden="true">&times;</span>
  <span class="sr-only">Close</span>
</button>
<h4 class="modal-title" id="myModalLabel">意见服务</h4>
</div>
<div class="modal-body">
欢迎您为我们提出贵宝的意见！
<input type="textarea" class="form-control rol-10">
<!-- /.col-lg-6 -->
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
<button type="button" class="btn btn-primary">确认</button>
</div>
</div>
</div>
</div>

<div class="fixed">
<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert">
<span aria-hidden="true">&times;</span>
<span class="sr-only">Close</span>
</button> <strong>教务新闻</strong>
<?php echo ($news); ?>
</div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../../../Public/js/bootstrap.min.js"></script>
<script src="../../../Public/js/chat.js"></script>
<script src="../../../Public/js/handlers.js"></script>
<script src="../../../Public/js/swfupload.js"></script>
<script type="text/javascript">
        var path='../../../Public';
        var url='/Chat-room/index.php/Home/Chat';
        var Btn=document.getElementById("Button1");
        Btn.click();
    </script>
<script type="text/javascript">
    var swfu;
    window.onload = function () {
      swfu = new SWFUpload({
        upload_url: "/Chat-room/index.php/Home/Chat/uploadImg",
        post_params: {"PHPSESSID": "<?php echo session_id();?>"},
        file_size_limit : "2 MB",
        file_types : "*.jpg;*.png;*.gif;*.bmp",
        file_types_description : "JPG Images",
        file_upload_limit : "1",
        file_queue_error_handler : fileQueueError,
        file_dialog_complete_handler : fileDialogComplete,
        upload_progress_handler : uploadProgress,
        upload_error_handler : uploadError,
        upload_success_handler : uploadSuccess,
        upload_complete_handler : uploadComplete,
        button_image_url : "../../../Public/img/upload.png",
        button_placeholder_id : "spanButtonPlaceholder",
        button_width: 113,
        button_height: 33,
        button_text : '',
        button_text_style : '.spanButtonPlaceholder { font-family: Helvetica, Arial, sans-serif; font-size: 14pt;} ',
        button_text_top_padding: 0,
        button_text_left_padding: 0,
        button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
        button_cursor: SWFUpload.CURSOR.HAND,     
        flash_url : "../../../Public/swf/swfupload.swf",
        custom_settings : {
          upload_target : "divFileProgressContainer"
        },        
        debug: false
      });
    };
  </script>

</body>

</html>