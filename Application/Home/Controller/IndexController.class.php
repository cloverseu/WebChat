<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 注册登录、登录页面的类
 * 完成注册、登录、验证码验证的功能
 */
class IndexController extends Controller {
    public function index() {
        $this->display();
    }

    /**
     *完成注册功能
     *
     *接收页面传来的用户名和密码，对密码使用MD5加密，
     *添加成功返回1，否则返回0
     * 
     * Ajax方式返回数据到客户端
     * @access public
     * @param mixed $data 要添加的数据
     * @param mixed $result 要返回的数据
     * @param int $id 添加成功返回当前id
     * @return mixed
     */
    public function register() {
        $data = array(
           'username' => I('username'),
           'password' => MD5(I('password'))
            );
        $id =  M("username")->data($data)->add();  
        if ($id) { // 添加成功返回数字1和用户ID，否则返回0
            $result['status'] = 1;
            $result['id'] = $id;
            $this->AjaxReturn($result,'json');
        } else {
            $result['status'] = 0;
            $this->AjaxReturn($result,'json');
        }
    }

     /**
     *检查用户名是否存在
     * 
     * Ajax方式返回数据到客户端
     * @access public
     * @param string $username 用户名
     * @param string $user 当前用户名是否存在
     * @param mixed $result 要返回的数据
     * @return mixed
     */
    public function check() {
    	$username = I('username');
    	$user = M("username")->where("username = '$username'")->find();
    	if ($user) {
	    	$result['status'] = 0;
	        $this->AjaxReturn($result,'json');
	    } else {
	        $result['status'] = 1;
	        $this->AjaxReturn($result,'json');
	    }
    }

    /**
     *用户登录
     *
     *验证用户名密码是否正确，正确则保存session，用户状态为1，反之则为0
     * 
     * Ajax方式返回数据到客户端
     * @access public
     * @param string $username 用户名
     *        string $password 输入的密码
     *        string $pwd 数据库的密码
     *        int    $id 用户id
     *        mixed  $result 要返回的数据
     * @return mixed
     */
    public function login() {
        $username = I('username');
        $password = MD5(I('password'));
		$pwd = M("username")->where("username = '$username'")->getField('password');
        if ($password == $pwd) {
            $id = M("username")->where("username = '$username'")->getField('id');
			$result['status'] = 1;
            session('id',$id);
            $status = M("username")->where("id = '$id'")->setField('status','1');
	        $this->AjaxReturn($result,'json');
		} else {
	        $result['status'] = 0;
	        $this->AjaxReturn($result,'json');
	    }
    }

    /**
     * 生成验证码图片
     */
    public function Verify() {
        $Verify = new \Think\Verify();
        $Verify->fontSize = 20;
        $Verify->entry();
    }
    
    /**
     * 检查验证码输入是否正确
     *
     * Ajax方式返回数据到客户端
     * @param string $code 用户输入的验证码
     * @return mixed
     */
    public function check_verify() {
        $code = I('verify');
        $verify = new \Think\Verify();
        if($verify->check($code)) {
          $result['status'] = 1;
          $this->AjaxReturn($result,'json');
        }else{
          $result['status'] = 0;
          $this->AjaxReturn($result,'json');
        }
    }

    /**
     * 检查session是否存在，验证当前是否有用户存在
     *
     * Ajax方式返回数据到客户端
     * @return mixed
     */
    public function checkSession() {
        if(session('?id')) {
            $result['status']=1;
            $this->AjaxReturn($result,'json');
        };
    }    

}