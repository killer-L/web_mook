<?php
namespace app\index\controller;

class User extends \think\Controller
{
	//显示注册页面
	public function reg(){		
		return $this->fetch();
	}

    public function insert2(){
    	session(null); 
    	$data['user_name']=\think\Request::instance()->post('username'); // 获取某个post变量username
		$data['user_pwd']=input('post.password');
		$data['repass']=input('post.repass');
		$data['user_sex']=input('post.gender'); //性别
		$data['user_email']=input('post.email');
		$data['user_question']=input('post.question');
		$data['user_answer']=input('post.answer');
		$data['user_truepwd']=input('post.password');
		$data['user_qq']=input('post.qq');
		$data['user_tel']=input('post.phone');
		$data['user_address']=input('post.address');
		
		$validate = \think\Loader::validate('User');
		if(!$validate->check($data)){
			$this->error($validate->getError());
		}

		$u=new \app\index\model\User();
		$u->user_name=\think\Request::instance()->post('username');
		$u->user_pwd=md5(input('post.password'));
		$u->user_sex=input('post.gender'); //性别
		$u->user_email=input('post.email');
		$u->user_question=input('post.question');
		$u->user_answer=input('post.answer');
		$u->user_truepwd=input('post.password');
		$u->user_qq=input('post.qq');
		$u->user_tel=input('post.phone');
		$u->user_address=input('post.address');

		$u->save();
		$this->success("<h1>注册成功</h1>","index/user/login");
    }

//登录

	public function login(){		
		return $this->fetch();
	}

    public function logindo(){
    	if(!isset($username)){
	    	$data['user_name']=\think\Request::instance()->post('username'); // 获取某个post变量username
			$data['user_pwd']=md5(input('post.password'));
			$u=new \app\index\model\User();
			$result = $u->where('user_name',$data['user_name'])->where('user_pwd',$data['user_pwd'])->find();

			if($result){
				session_start();
				$username=$data['user_name'];
				session('username',$username);
				$this->assign('user_name',$data['user_name']);	
				$this->success("<h1>登录成功</h1>","index/index/index");
			}else{
				$this->error("登录失败");
			}

		}else{
			echo "您已经登录";
		}
    }

//退出登录

    public function loginout()
    {
        session(null);
        $this->success('退出成功', url("index/index/index"));
    }

//忘记密码

    public function forgetpsw(){		
		return $this->fetch();
	}

    public function forgetdo(){

    	$data['user_name']=\think\Request::instance()->post('username'); // 获取某个post变量username
		$data['user_email']=input('post.email');
		$data['user_question']=input('post.question');
		$data['user_answer']=input('post.answer');
		$password=input('post.password');
		$password1=input('post.repass');

		$u=new \app\index\model\User();
		$result = $u->where('user_name',$data['user_name'])->where('user_email',$data['user_email'])->where('user_question',$data['user_question'])->where('user_answer',$data['user_answer'])->find();

		if($result){
			$username=$data['user_name'];
			$useremail=$data['user_email'];
			if (md5($password)==md5($password1)){
				$data['user_pwd']=md5($password);
				$data['user_truepwd']=$password;
				$u->where('user_name',$username)->update($data); // 更新数据库
				$this->assign('username',$username);	
				$this->success("<h1>修改成功，请登录</h1>","index/user/login");
				
			}else{
				$this->error("两次密码不同");
			}	
		}else{
			$this->error("身份验证失败");
		}
    }


    //个人信息
   	public function message(){

   		$u=new \app\index\model\User();
   		$username= session('username');
   		if(!isset($username)){
			$this->error("未登录");

   		}else{
   		$r = $u->where('user_name',$username)->select();
   		}
   		$this->assign('r',$r);	
		$this->assign('username',$username);	
   		return $this->fetch();
   	}

   	public function editdo(){

   		$u=new \app\index\model\User();
   		$username= session('username');
   		$qq=input('post.qq');
   		$phone=input('post.phone');
   		$address=input('post.address');

		$data['user_qq']=$qq;
		$data['user_tel']=$phone;
		$data['user_address']=$address;

		$u->where('user_name',$username)->update($data); 
		$this->success("<h1>修改成功</h1>","index/index/index");

   	}

//用户修改密码
   	public function newpsw(){		
		return $this->fetch();
	}

	public function newpswdo(){

		$u=new \app\index\model\User();
   		$username= session('username');
   		$r = $u->where('user_name',$username)->select();
   		$oldpwd = $r['0']['user_truepwd'];
   		$oldpassword = input("post.oldpassword");
   		$newpassword = input("post.newpassword");
		$password1=input('post.repass');
		
		if($oldpwd==$oldpassword){

			if (md5($newpassword)==md5($password1)){
					$data['user_pwd']=md5($newpassword);
					$data['user_truepwd']=$newpassword;
					$u->where('user_name',$username)->update($data); 
					$this->assign('username',$username);	
					session(null); 
					$this->success("<h1>修改成功，请登录</h1>","index/user/login");
				
				}else{
					$this->error("两次密码不同");
				}
		}else{
			$this->error("旧密码错误");

		}

	}

}