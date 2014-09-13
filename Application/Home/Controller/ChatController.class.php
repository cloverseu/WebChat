<?php
namespace Home\Controller;
use Think\Controller;
    /**
     * 聊天页面的整体布局
     * 包括添加好友、退出登录、更改头像图片、
     * 教务新闻面板的加载、基础功能的查询
     * 
     */
class ChatController extends Controller {
    /**
     * 完成页面布局
     *
     * 获取好友列表，新好友的添加，教务处新闻
     *
     * @access public
     * @param int   $id 用户的id
     *        int   $friendId 好友的id
     *        int   $acceptId 接收有发送信息的id
     *        mixed $messageName 有发送信息的用户         
     * @return mixed
     */
    public function index(){
        //获取用户好友列表
        $id = session('id'); 
        $friendId = M("friend_list")->where("id = '$id' AND accept = '1'")->getField('friend_id',true);        
        for ($i=0;$i<count($friendId);$i++) { //获取好友的id，用户名，登录状态，图片（默认）
            $listId = $friendId[$i];
            $friendList[$i] = M('username')->where("id = '$listId'")->getField('id,username,status,img');
        }
        $this->friendList = $friendList;
        if (file_exists("Public/uploads/mini/$id.jpg")) { //获取用户的头像文件，如果不存在则使用默认图片
            $this->id = $id;
        } else {
            $this->id = "tx";
        }
        //获取好友发送的信息
        $acceptId = M('friend_list')->where("friend_id = '$id'AND accept = '0'")->getField('id',true);
        for($i=0;$i<count($acceptId);$i++){
            $messageId = $acceptId[$i];
            $messageName[$i] = M("username")->where("id = '$messageId'")->getField('id,username,img');
        }
        $this->messageName = $messageName;
        //教务处新闻
        $url = "http://jwc.seu.edu.cn";
        $fp = @fopen($url, "r") or die("超时");
        $fcontents = file_get_contents($url);
        eregi("<td><table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"font1\">(.*)<td><table id=\"__01\" width=\"1004\" height=\"75\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">", $fcontents,$regs);
        $regs[0] = str_replace("<img src=\"/page/main2128/images/1_02.gif\" width=\"20\" height=\"30\">","",$regs[0]);
        $this->news = $regs[0];
        $this->display();
        
    }

    /**
     * 添加好友
     *
     * 先检查该好友是否已经添加或者是否已经注册
     *
     *@access public
     *@param  array  $data 用户的id和好友的id
     *         int   $checkId 获取当前好友列表的id，用于检查
     *         int   $existId 检查当前id是否存在
     *         mixed $addinfo 检查当前好友是否被添加
     *@return  mixed                
     */
    public function add(){
        $data = array(
            'id' => session('id'),
            'friend_id' => I('add_id')
            );
        $checkId = M('friend_list')->where("id = '$data[id]'")->getField('friend_id');
        $existId = M('username')->where("id = '$data[friend_id]'")->find();
        if ($existId) {
            if ($checkId == $data['friend_id']) {
                $addinto = null;
            } else {
                $addinto = M('friend_list')->data($data)->add(); 
            }
        } else {
            $addinto = null;
        }
        if ($addinto) {
            $data['status']=1;
            $this->AjaxReturn($data,'json');
        } else {
            $data['status']=0;
            $this->AjaxReturn($data,'json');
        }
    }

    /**
     * 接受新的好友添加提醒
     *
     * @param int $notice 获取未通过添加的好友请求
     *        int $num 请求的用户数目            
     * @return int
     */
    public function receiveNotices(){
        $id = session('id');
        $notice = M('friend_list')->where("friend_id = '$id' AND accept = '0'")->getField('friend_id',true);
        $num = count($notice);
        if ($num) {
            $this->AjaxReturn($num,'json');
        }    
    }

    /**
     * 处理用户的好友请求
     *
     * @access public
     * @param int    $requestId 请求加为好友的Id
     *        string $type 接受的类型，拒绝或者同意
     *        bool   $otherDeal 同意则修改accept的值
     * @return mixed
     */
    public function dealId(){
        $id = session('id');
        $requestId = I('id');
        $type = I('type');
        if ($type = "agree") {
            $otherDeal = M('friend_list')->where("id = '$requestId' AND friend_id = '$id'")->setField('accept','1');
            $data = array(
                    'id'=>$id,
                    'friend_id'=>$requestId,
                    'accept'=>"1"
                );
            $myDeal =M('friend_list')->data($data)->add();
        } else {
            $myDeal = M('friend_list')->where("id = '$requestId' AND friend_id = '$id'")->delete();
        }
        $result['status']=1;
        $this->AjaxReturn($result,'json');
     }

    /**
     * 未接收的聊天信息提醒
     * 
     * @access public
     * @param string $getter 接收用户，即当前用户
     *        string $sender 获取发送用户
     *        int    $sendId 发送用户的id提醒              
     * @return int
     */
    public function receiveNews(){
        $id = session('id');
        $getter = M('username')->where("id = '$id'")->getField('username');
        $sender = M('chat')->where("getter = '$getter' && isGet = '0'")->getField('sender',true);
         for($num = 0;$num<count($sender);$num++){
            $username = $sender[$num];
            $senderId[$num] = M('username')->where("username = '$username'")->getField('id');   
         };
         $this->AjaxReturn($senderId,'json');
     }
    /**
     * 用户退出时，状态设为0，清除session
     *
     * @access public
     * @param  int $status 用户的登录状态
     *         mixed $data 返回ajax数据            
     * @return mixed
     */
    public function out(){
        if (session('?id')) {
            $id = session('id');
            $status = M('username')->where("id = '$id'")->setField('status','0');
            session('id',null);
            $data['status']=1;
            $this->AjaxReturn($data,'json');
        } else {
            $data['status']=0;
            $this->AjaxReturn($data,'json');
        };
    }
    /**
     * 引用Tp内置的上传类，图像处理类
     * 实现用户更改头像的上传
     *
     * @access public
     * @param  class $upload 上传类
     *         class $image  图像处理类            
     * @return path 
     */
    public function uploadImg() {
        $imgName = session('id');
        $upload =  new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 31457280 ;// 设置附件上传大小
        $upload->Exts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->replace = true;
        $upload->saveName = $imgName.'tmp';//保存的名称
        $upload->saveExts = 'jpg';//保存的后缀名
        $upload->autoSub = false;
        $savepath='../Public/uploads/tmp/';
        $upload->savePath =  $savepath;// 设置附件上传目录
        $info = $upload->upload();
        $img = M('username')->where("id ='$imgName'")->setField('img',$imgName);
        $image = new \Think\Image(); 
        $image->open("Public/uploads/tmp/$upload->saveName.jpg");
              // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
        $image->thumb(75, 75,\Think\Image::IMAGE_THUMB_FIXED)->save("Public/uploads/mini/$imgName.jpg");
        echo ("../../../Public/uploads/tmp/$upload->saveName.jpg");
  }

  /**
   * 引用外部的API（百度API）CURL实现天气查询，查看影片
   *
   * @access public
   * @param string $city 查询的城市名称，电影则默认查询南京
   *        string $type 查询的类型，影片或者电影
   *        string $url  引用外部接口的网址
   *        string $output 获取的html文件
   *        obj    $weather 获取的天气对象
   *        obj    $movie 获取的影片对象
   *@return   mixed    
   */
    public function basicFunction(){
        $city = I('city');
        $type = I('type');
        if ($type == "weather") {
            $url = "http://api.map.baidu.com/telematics/v3/".$type."?location=".$city."&output=json&ak=B3b101fc9cc6e67fe9e348cff061bc37";
        } elseif ($type == "movie") {
            $url = "http://api.map.baidu.com/telematics/v3/".$type."?location=".$city."&qt=hot_movie&output=json&ak=B3b101fc9cc6e67fe9e348cff061bc37";
        };    
        $ch = curl_init();//设置选项
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);//执行并获取HTML文档
        $output = curl_exec($ch);//释放CURL句柄
        curl_close($ch);
        $data = json_decode($output);
        if ($type == "weather") {
            $weather = $data->results[0]->weather_data[0];
            $this->AjaxReturn($weather,'json');
        } elseif ($type == "movie"){
            $movie = $data->result->movie;
            foreach ($movie as $key => $value) {
                if ($value->movie_score > 8){
                    $movieArr[] = $value;
                }
            }
            $this->AjaxReturn($movieArr,'json');
        };
   }
    /**
     * 引用外部网址，模拟post传递数据实现四六级成绩查询
     *
     * @access public
     * @param  string $cetName 查询的名称
     *         string $cetId   查询的准考证号
     *         string $postFiles 模拟post传递数据
     *         string $match 匹配所需的内容
     * @return mixed
     */
    public function cet(){
        $cetName = I('cetName');
        $cetId = I('cetId');
        $url = "http://apix.sinaapp.com/cet/submit.php";
        $post_files = "name=".$cetName."&number=".$cetId."&appkey=CET46";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_files);
        $output = curl_exec($ch);
        curl_close($ch);
        $match = "#<td>(.*)</td>#";
        preg_match_all($match, $output,$data);
        $this->AjaxReturn($data,'json');
        }     

}