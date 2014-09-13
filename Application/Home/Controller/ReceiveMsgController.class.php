<?php
namespace Home\Controller;
use Think\Controller;
    /**
     * 接收聊天信息的加载
     */
class ReceiveMsgController extends Controller {
	 /**
     *接收聊天信息
     *
     * @access public
     * @param  array  $condition  数据库中未接收的聊天信息条件
     *         string $info 接收信息对应的html  
     *         array  $data 未接收的聊天信息
     *         int    $isGet 是否接收         
     * @return mixed
     */
    public function index(){
    	$sender = I('sender');
    	$getterid = session('id');
        $getter = M('username')->where("id = '$getterid'")->getField('username');
        $condition = array(
            "sender" => $sender,
            "getter" => $getter,
            "isGet" => "0"
            ); 
        $data = M('chat')->where($condition)->order('num')->select();
        if ($data) {
        	$this->getter = $getter;
            $this->sender = $sender;
            for ($num=0;$num<count($data);$num++) {
                $data[$num]['content'] = $this->replace_express($data[$num]['content']);
            }
            $this->data = $data;
            $Info["chat"] = $Info["chat"].$this->fetch("ReceiveMsg");
            echo json_encode($Info);
            $isGet = M('chat')->where("sender = '$sender' AND getter = '$getter'")->setField('isGet','1');
        }
    }

     /**
     *把表情替换成URL路径
     *
     * @access protected
     * @param  array  $express  表情库
     *         string $content 表情相对的URL路径               
     * @return mixed
     */
    protected function replace_express($content){
        preg_match_all('/\[.*?\]/is', $content,$arr);
        $express = array("smile","tu","bs","angry","han","cry");
        if ($arr[0]) {
            foreach ($arr[0] as $key => $v) {
                foreach ($express as $key => $value) {
                    if($v == '['.$value.']'){
                        $content = str_replace($v, '<img src="'.__ROOT__.'/Public/img/'.$value.'.png">',$content);
                    }
                    continue;
                }
            }      
        }
        return $content;       
    }
    
}
?>