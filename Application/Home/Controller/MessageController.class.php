<?php
namespace Home\Controller;
use Think\Controller;
    /*
     *聊天信息发送处理,主要包括表情图片的替换
     */
class MessageController extends Controller {
	/**
     *将用户发送的信息保存在数据库
     *
     * @access public
     * @param  int    $sendId 发送信息者的id
     *         string $sender 发送者的名称
     *         array  $data  存储在数据库的信息
     *         int    $num   添加成功后返回当前数量
     *         string $info  相对应的html页面            
     * @return mixed
     */
    public function index(){
        $sendId = session('id');
        $sender = M("username")->where("id = '$sendId'")->getField('username');
        $data = array(
            'sender' => $sender,
            'getter' => I('getter'),
            'content' => I('content'),
            'sendTime' =>date('Y-m-d H:i:s')
            );
        $num = M("chat")->data($data)->add();
        $data['content'] = $this->replace_express($data['content']);
        if ($num) {
            $data['status']=1;
            $this->data = $data;
            $this->sendid = $sendid;
            $Info["content"]=$Info["content"].$this->fetch("Message");
            echo json_encode($Info);
        } else {
            $result['status']=0;
            $this->AjaxReturn($result,'json');
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