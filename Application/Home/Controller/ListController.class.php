<?php
namespace Home\Controller;
use Think\Controller;
    /**
     * 聊天页面的加载
     */
class ListController extends Controller {
    /**
     * 加载聊天面板
     *
     * @access public
     * @param  stirng $getter 信息接收用户               
     * @return mixed
     */
    public function index(){
    	$getter = I('getter');
    	$this->getter = $getter;
        $returnInfo["content"]=$returnInfo["content"].$this->fetch("List");
        echo json_encode($returnInfo);
    }

}	   