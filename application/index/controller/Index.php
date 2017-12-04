<?php
namespace app\index\controller;
use think\Db;

class Index  extends \think\Controller
{
    public function index(){
                return $this->fetch();

    }
    public function index2()
    {
    	
 		$username= session('username');
        $book = new \app\index\model\Book();
		$data= $book->field('book_id, book_name, book_newprice ,book_img')->where('book_issepprice=1')->find();
		$this->assign('tejiabook',$data);	
		$this->assign('username',$username);	
		return $this->fetch();	
	
    }

    public function callus(){       
        return $this->fetch();
    } 
    public function help(){       
        return $this->fetch();
    } 
    public function notice(){       
        return $this->fetch();
    }
     public function message(){   
            $m=new \app\index\model\Message();
            $r = $m->where('status',1)->
            paginate(2);
            $page = $r->render();
            $this->assign('r',$r);  
            $this->assign('page',$page);  
            return $this->fetch();
    }

    public function insertmessagedo(){
        $m=new \app\index\model\Message();
        $username= session('username');
        $data['user_name']=$username;
        $data['user_message']=input('post.message');
        $data['status']="1";
        $m->user_name=$data['user_name'];
        $m->user_message=$data['user_message'];
        $m->status=$data['status'];
        $m->save();
        $this->success("<h1>留言成功</h1>","index/index/message");



    }
}
