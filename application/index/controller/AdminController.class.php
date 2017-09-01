<?php
namespace Admin\Controller;
use Think\Controller;

class AdminController extends Controller {
    public function _initialize(){
        //初始化方法，访问控制器就会先运行此方法
        if(!session('?username')){
            $this->error("请登录!", U("Login/login"),1);
        }

    }

    public function index(){
        $key=I('get.key');
        $db=M('admin');
        if($key<>''){
            $where="status=1 and is_admin=1 and phone like '%".$key."%'";
        }else{
            $where="status=1 and is_admin=1";
        }
        $db=M('admin');
        $count = $db->where($where)->count();
        $Page = new \Think\Page($count,10);
        $show = $Page->show();
        $user=$db->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('list',$user);
        if(IS_POST){
            $idarr = implode(',',I('post.idarr'));
            $shouye=M('admin');
            $row = $shouye->delete($idarr);
            if($row){
                alert('删除成功',U('Admin/index'));
            }else{
                alert('删除失败');
            }
        }
        // if ($this->isAjax()) {//判断ajax请求
        //         layout(false);
        //         exit($this->fetch());
        //     }
        $this->display();
    }

    public function edit(){
        $id=I('get.id');
        $db=M('admin');
        $user = $db -> where("id=".$id)->find();
        $this->assign('user',$user);
        $region=M('region');
        $province = $region->where ( array('pid'=>1) )->select ();
        $this->assign('province',$province);
        if(isset($_POST['dosubmit'])){
            $data['nickname']=I('post.nickname');
            // $data['password']=md5(I('post.password'));
            $data['sex']=I('post.sex');
            $data['phone']=I('post.phone');
            $picture=I('post.picture');
            if($_FILES['picture']['error']==0){
                $upload = new \Think\Upload();                                    // 实例化上传类
                $upload->maxSize   =     3145728 ;                               // 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');    // 设置附件上传类型
                $upload->rootPath  =     'Uploads/';                           // 设置附件上传目录
                $upload->savePath  = '';                                      // 设置附件上传（子）目录
                $info   =   $upload->uploadOne($_FILES['picture']);             // 单文件上传方法引用
                if(!$info) {                                                // 判断上传错误提示错误信息
                    $this->error($upload->getError());
                }else{                                                    // 上传成功 获取上传文件信息
                    $file=$info['savepath'].$info['savename'];
                }
            }else{
                $file=$picture;
            }
            $data['picture']=$file;
            $pro_id=I('post.province');
            $city_id=I('post.city');
            $town_id=I('post.town');
            $result1=$region->where('id='.$pro_id)->find();
            $pro=$result1['name'];
            $result2=$region->where('id='.$city_id)->find();
            $city=$result2['name'];
            $result3=$region->where('id='.$town_id)->find();
            $town=$result3['name'];
            $data['region']=$pro.$city.$town;
            if (!empty(I('post.password'))) {
                if(I('post.password') == I('post.confirm_password')){
                    $data['password']= md5(I('post.password'));
                } else {
                    $this->error("密码和确认密码不一致！");
                }
            }
            $sql=$db->where("id=".$id)->save($data);
            if($sql){
                alert('修改成功',U('Admin/index'));
            }else{
                $this->error("修改失败！");
            }
        }

        $this->display();
    }

    public function del(){
        $id=I('get.id');
        $db=M('admin');
        $data['status']=0;
        $sql=$db->where("id='$id'")->save($data); //软删除
        if($sql){
            alert('删除成功',U('Admin/index'));
        }else{
            $this->error("删除失败！");
        }
    }

    public function add(){
        if(isset($_POST["dosubmit"])){
            $nickname = I("post.nickname");
            $phone = I("post.phone");
            $password = I("post.password");
            $sex=I('post.sex');
            if($_FILES['picture']['error']==0){
                $upload = new \Think\Upload();                                    // 实例化上传类
                $upload->maxSize   =     3145728 ;                               // 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');    // 设置附件上传类型
                $upload->rootPath  =     'Uploads/';                           // 设置附件上传目录
                $upload->savePath  = '';                                      // 设置附件上传（子）目录
                $info   =   $upload->uploadOne($_FILES['picture']);             // 单文件上传方法引用
                if(!$info) {                                                // 判断上传错误提示错误信息
                    $this->error($upload->getError());
                }else{                                                    // 上传成功 获取上传文件信息
                    $file=$info['savepath'].$info['savename'];
                }}else{
                $file="";
            }
            $user = M("admin");
            $data['nickname']=$nickname;
            $data['username'] = uniqid(date('Yms'));
            $data['phone']=$phone;
            $data['picture']=$file;
            $data['password']=md5($password);
            $data['sex']=$sex;
            $data['is_admin']=1;
            $row = $user -> add($data);
            if($row){
                alert('添加成功',U('Admin/index'));
            }else{
                $this->error("添加失败！");
            }
        }
        $this->display();
    }

    public function getRegion(){
        $region=M("region");
        $map['pid']=$_REQUEST["pid"];
        $map['type']=$_REQUEST["type"];
        $list=$region->where($map)->select();
        echo json_encode($list);
    }
}