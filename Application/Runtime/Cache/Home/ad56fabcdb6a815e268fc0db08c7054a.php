<?php if (!defined('THINK_PATH')) exit();?> <div class="row" > 
        <div class="col-lg" >
          <div class="input-group">
            <input type="text" id="content" class="form-control">
            <span class="input-group-btn">
              <button class="btn btn-default send" type="button" getter="<?php echo ($getter); ?>">发送</button>
		 </span>
          </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
        <div class="express">
       <img src="../../../Public/img/cry.png" alt="哭" value="[cry]">
        <img src="../../../Public/img/smile.png" alt="开心" value="[smile]">
        <img src="../../../Public/img/han.png" alt="汗" value="[han]">
        <img src="../../../Public/img/angry.png" alt="愤怒" value="[angry]">
        <img src="../../../Public/img/bs.png" alt="鄙视" value="[bs]">
        <img src="../../../Public/img/tu.png" alt="吐" value="[tu]">
    </div>

      </div><!-- /.row -->
</div>

<script src="../../../Public/js/chat.js"></script>