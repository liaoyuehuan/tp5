<?php
namespace Admin\Controller;
use Think\Controller;

class ApiController extends Controller {
    public function index(){
        $uid = $this->data['uid'];
        $lat = $this->data['lat'];
        $lng = $this->data['lng'];
        $range = $this->getRange();
        $where = array();
        $db=M("dustbin_map");
        if ($uid) {
            $admin = M("admin");
            $admin_info = $admin->find($uid);
            if($admin_info['is_admin'] == 1) {
                $username = $admin_info['username'];
            }
        }
        if (isset($username) && $username) {
            $where['to_admin'] = $username;
        } else {
            $range_sql = "sqrt(  
                    (  
                     (({$lng}-lng)*PI()*12656*cos((({$lat}+lat)/2)*PI()/180)/180)  
                     *  
                     (({$lng}-lng)*PI()*12656*cos ((({$lat}+lat)/2)*PI()/180)/180)  
                    )  
                    +  
                    (  
                     (({$lat}-lat)*PI()*12656/180)  
                     *  
                     (({$lat}-lat)*PI()*12656/180)  
                    )  
                )<{$range} ";
            $db->where($range_sql);
        }
        $where['status'] = 1;

        $list=$db->where($where)->order('id desc')->select();
        echo json_encode(array('status'=>1,'data'=> ($list ? $list : []),'msg'=>'success'));
    }

    private function getRange(){
        $range  = S('dustbin_range');
        if($range){
            return $range;
        } else {
            $config_name = 'range';
            $model_config = M('config');
            $value = $model_config->where(['name' => $config_name])->cache('dustbin_range',600)->getField('value');
            return $value;
        }
    }


    /**
     * 获取管理垃圾箱的信息
     * @param  uid  用户id
     * @return [status] [0 =>失败, 1 => 成功]
     *         [data]   ['total' => 管理的垃圾桶数量,'full_count' => '满的垃圾桶','full_percent' => '满百分比' , 'is_clear' => [0 => 可不清理 ,1 => 去清理]]
     *         ['msg']  系统提示
     */
    public function dustbin_manage_info(){


        $uid = $this->data['uid'];
        $where_array = [];
        $result = [];
        $where_array['status'] = 1 ;
        if ($uid) {
            $model_admin = M('admin');
            $username = $model_admin->where(['id' => $uid,'status' => 1 ,'is_admin' => 1])->getField('username');
            if  ($username) {
                $where_array['to_admin'] = $username;
            }
        }
        $model_dustbin_map = M('dustbin_map');
        //获取管理垃圾桶的总数量
        $dustbin_manage_total = $model_dustbin_map->field('count(1) as total')->where($where_array)->find()['total'];
        //获取管理满的总数量
        $where_array['full'] = 1;
        $dustbin_manage_full_count = $model_dustbin_map->field('count(1) as total')->where($where_array)->find()['total'];
        $percent = (int)($dustbin_manage_full_count / $dustbin_manage_total * 100);
        $is_clear = $percent >= C('clear_percent') ? 1 : 0;
        $result['status'] = 1;
        $result['data'] = ['total' => $dustbin_manage_total,'full_count' => $dustbin_manage_full_count ,'full_percent' => $percent ,'is_clear' => $is_clear];
        $result['msg'] = 'success';
        $this->ajaxReturn($result);
    }

    /**
     * 获取操作说明内容
     * @return [status] [0 =>失败, 1 => 成功]
     *         [data]   ['content' => '说明内容']
     *         ['msg']  系统提示
     */
    public function get_operate_state(){
        $return = [];
        $config_name = 'operate';
        $model_config = M('config');
        $content = $model_config->where(['name' => $config_name])->cache('operate_state',600)->getField('content');
        $return['status'] = 1;
        $return['data'] = ['content' => htmlspecialchars_decode($content)];
        $return['msg'] = 'success';
        $this->ajaxReturn($return);
    }

    /**
     * 获取关于我们
     * @return [status] [0 =>失败, 1 => 成功]
     *         [data]   ['content' => '关于我们内容']
     *         ['msg']  系统提示
     */
    public function get_about_us(){
        $return = [];
        $config_name = 'about_us';
        $model_config = M('config');
        $content = $model_config->where(['name' => $config_name])->cache('about_us',600)->getField('content');
        $return['status'] = 1;
        $return['data'] = ['content' => htmlspecialchars_decode($content)];
        $return['msg'] = 'success';
        $this->ajaxReturn($return);
    }

    public function user(){
        $db=M("admin");
        $list=$db->where('status=1')->order('id desc')->select();
        echo json_encode(array('status'=>1,'data'=>$list,'msg'=>'success'));
    }

    public function about(){
        $db=M("admin_guanyu");
        $list=$db->order('id desc')->limit(0,1)->select();
        // $list=json_encode($list);
        // var_dump($list[0]);die;

        echo json_encode(array('status'=>1,'data'=>$list[0],'msg'=>'success'));
    }

    public function suggestion(){
        $db=M("suggestion");
        $list=$db->where('status=1')->order('id desc')->select();
        echo json_encode(array('status'=>1,'data'=>$list,'msg'=>'success'));
    }

    public function login(){
        $phone=$this->data['phone'];
        $password=md5($this->data['password']);
        $biao = M('admin');
        $row = $biao->where("phone='$phone' AND password='$password' AND status=1")->find();
        $data['uid']=$row['id'];
        $data['nickname']=$row['nickname'];
        $data['sex']=$row['sex'];
        $data['is_admin'] =  $row['is_admin'];
        $data['head_ico']=$row['picture'];
        $data['sid']=time().rand(111,999);
        $data['logtime']=time();
        if($row){
            $biao->where("id=".$data['uid'])->save($data['sid']);
            $biao->where("id=".$data['uid'])->save($data['logtime']);
            // $json=array('status'=>1,'data'=>$data ,'msg'=>'登录成功');
            // foreach ( $json as $key => $value ) {
            // $json[$key] = urlencode ( $value );
            // }
            // echo urldecode ( json_encode ( $json ) );
            echo json_encode(array('status'=>1,'data'=>$data,'msg'=>'登录成功'));
        }else{
            // echo json_encode(array('status'=>0,'data'=>$data,'msg'=>'用户名或密码错误'));
            $json=array('status'=>0,'data'=>$data,'msg'=>'用户名或密码错误');
            foreach ( $json as $key => $value ) {
                $json[$key] = urlencode ( $value );
            }
            echo urldecode ( json_encode ( $json ) ); exit();
        }
    }


    public function register(){
        $db=M('admin');
        $data['username'] = uniqid(date('Yms'));
        $data['phone']=$this->data['phone'];
        $data['password']=md5($this->data['password']);
        if($this->data['password']==$this->data['repassword']){
            if(strlen($this->data['password'])<6 || strlen($this->data['password'])>20){
                $json=array('status'=>0,'msg'=>'密码长度在6到20位之间！');
                foreach ( $json as $key => $value ) {
                    $json[$key] = urlencode ( $value );
                }
                echo urldecode ( json_encode ( $json ) );exit();
                // echo json_encode(array('status'=>0,'msg'=>'密码长度在6到20位之间！'));exit();
            }
            if(!preg_match("/^1[34578]\d{9}$/", $data['phone'])){
                $json=array('status'=>0,'msg'=>'非法手机号码！');
                foreach ( $json as $key => $value ) {
                    $json[$key] = urlencode ( $value );
                }
                echo urldecode ( json_encode ( $json ) );
                exit();
                // echo json_encode(array('status'=>0,'msg'=>'非法手机号码！'));return false;
            }
            $row = $db->where("phone={$data['phone']} and status=1")->find();
            if($row){
                $json=array('status'=>0,'msg'=>'注册失败，手机号已注册！');
                foreach ( $json as $key => $value ) {
                    $json[$key] = urlencode ( $value );
                }
                echo urldecode ( json_encode ( $json ) ); exit();
                // echo json_encode(array('status'=>0,'msg'=>'注册失败，手机号已注册！'));
            }
            $time=time();
            $rand=rand(111,999);
            $data['sid']=$time.$rand;
            $data['regtime']=time();
            $res=$db->add($data);
            // $_SESSION['phone']=$phone;
            if($res){
                // $json=array('status'=>1,'data'=>$data,'msg'=>'注册成功');
                // foreach ( $json as $key => $value ) {
                // $json[$key] = urlencode ( $value );
                // }
                // echo urldecode ( json_encode ( $json ) );
                echo json_encode(array('status'=>1,'data'=>$data,'msg'=>'注册成功'));
            }else{
                $json=array('status'=>0,'data'=>$data,'msg'=>'注册失败');
                foreach ( $json as $key => $value ) {
                    $json[$key] = urlencode ( $value );
                }
                echo urldecode ( json_encode ( $json ) );exit();
                // echo json_encode(array('status'=>0,'data'=>$data,'msg'=>'注册失败'));
            }
        }else{
            $json=array('status'=>0,'msg'=>'注册失败,两次密码不一样');
            foreach ( $json as $key => $value ) {
                $json[$key] = urlencode ( $value );
            }
            echo urldecode ( json_encode ( $json ) );exit();
            // echo json_encode(array('status'=>0,'msg'=>'注册失败,两次密码不一样'));
        }

    }

    public function dologin($phone,$password){
        // header("Content-type: text/html; charset=gbk2312");
        $biao=M('admin');
        $row = $biao->where("phone='$phone' AND password='$password'")->find();
        if($row){
            // if($row['is_admin']==0){
            //   return json_encode(array('status'=>2,'msg'=>'only administrator enter'));
            // }
            $logintime = time();
            // $os=PHP_OS;//获取操作系统
            // $vs=PHP_VERSION;//获取PHP版本
            // $ql=mysql_get_server_info();//获取Mysql版本
            $loginip = $_SERVER['REMOTE_ADDR'];
            $nickname=$row['nickname'];
            $_SESSION['id']=$row['id'];
            $_SESSION['username']=$row['username'];
            $_SESSION['is_admin']=$row['is_admin'];
            $_SESSION['times']=$row['logtime'];
            $_SESSION['ip']=$row['logip'];
            // $_SESSION['osing']=$row['os'];
            // $_SESSION['vsing']=$row['vs'];
            // $_SESSION['qling']=$row['ql'];
            // $_SESSION['os']=PHP_OS;
            // $_SESSION['vs']=PHP_VERSION;
            // $_SESSION['ql']=mysql_get_server_info();
            $data['logtime']=$logintime;
            $data['logip']=$loginip;
            // $data['os']=$os;
            // $data['vs']=$vs;
            // $data['ql']=$ql;
            $res = $biao->where("phone='$phone'")->save($data);
            if($res){
                return json_encode(array('status'=>1,'msg'=>'login success'));
                // return $res['status']=1;
            }else{
                return json_encode(array('status'=>0,'msg'=>'username or password error'));
                // return $res['status']=0;
            }
        }else{
            return json_encode(array('status'=>0,'msg'=>'username or password error'));
            // return $res['status']=0;
        }
    }


    public function dustbinInfo(){
        $id=$_POST['id']?$_POST['id']:$_GET['id'];
        $lat=$_POST['lat']?$_POST['lat']:$_GET['lat'];
        $lng=$_POST['lng']?$_POST['lng']:$_GET['lng'];
        $type=$_POST['type']?$_POST['type']:$_GET['type'];
        $full=$_POST['full']?$_POST['full']:$_GET['full'];
        $warning=$_POST['warning']?$_POST['warning']:$_GET['warning'];

        #GPS坐标转百度坐标
        $urls="http://api.map.baidu.com/geoconv/v1/?coords=".$lng.",".$lat."&from=1&to=5&ak=GpYGPdkypUGiPqWPZRIZ6kPSSklFeWH1";
        $urlJson=file_get_contents($urls);

        $urlArr=json_decode($urlJson,true);
        $lat=$urlArr['result'][0]['y'];
        $lng=$urlArr['result'][0]['x'];
        #根据经纬度解析地址
        $url='http://api.map.baidu.com/geocoder/v2/?ak=GpYGPdkypUGiPqWPZRIZ6kPSSklFeWH1&location='.$lat.','.$lng.'&output=xml&pois=1';
        $urlData=file_get_contents($url);
        $obj=simplexml_load_string($urlData);
        $array=object_to_array($obj);
        $address=$array['result']['formatted_address'];
        $db=M("dustbin_map");
        #保存至数据库
        if($id){
            $data['phone']= $id;
            $data['lat']= $lat;
            $data['lng']= $lng;
            $data['full']= empty($full)?0:1;
            $data['warning']=$warning==null?0:1;
            $data['point']=$lng.','.$lat;
            $data['type'] = $type;
            $data['address']=$address;
            $data['addtime']=time();
            $exist=$db->where('phone ='.$data['phone'])->find();
            if(!$exist){
                $db->data($data)->add();
            }else{
                $db->where('phone='.$data['phone'])->save($data);
            }
            echo json_encode(array('status'=>1,'msg'=>'success'));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'error'));
        }
    }

    public function updateUserInfo(){
        $db=M('admin');
        $uid=$this->data['uid'];
        $sid=$this->data['sid'];
        $sex=$this->data['sex'];
        $nickname=$this->data['nickname'];
        $data['sex']=$sex;
        $data['nickname']=$nickname;
        if($sid< $sql['sid']){
            $json=array('status'=>0,'msg'=>'用户信息已过期');
            foreach ( $json as $key => $value ) {
                $json[$key] = urlencode ( $value );
            }
            echo urldecode ( json_encode ( $json ) );exit();
        }

        $sql=$db->where("id=".$uid)->save($data);

        if($sql){
            $json=array('status'=>1,'msg'=>'修改成功');
            foreach ( $json as $key => $value ) {
                $json[$key] = urlencode ( $value );
            }
            echo urldecode ( json_encode ( $json ) );
        }else{
            $json=array('status'=>0,'msg'=>'修改失败');
            foreach ( $json as $key => $value ) {
                $json[$key] = urlencode ( $value );
            }
            echo urldecode ( json_encode ( $json ) );
            exit();
        }
    }

    public function updatePwd(){
        $db=M('admin');
        $uid=$this->data['uid'];
        $sid=$this->data['sid'];
        $password=$this->data['password'];
        $repassword=$this->data['repassword'];
        $oldpassword=$this->data['oldpassword'];
        $pwd=$db->where('id='.$uid)->find();
        if(md5($oldpassword)!=$pwd['password']){
            // echo json_encode(array('status'=>0,'msg'=>'密码输入错误!'));exit();
            $json=array('status'=>0,'msg'=>'密码输入错误');
            foreach ( $json as $key => $value ) {
                $json[$key] = urlencode ( $value );
            }
            echo urldecode ( json_encode ( $json ) );exit();
        }elseif($sid<$pwd['sid']){
            $json=array('status'=>0,'msg'=>'用户信息已过期');
            foreach ( $json as $key => $value ) {
                $json[$key] = urlencode ( $value );
            }
            echo urldecode ( json_encode ( $json ) );exit();
        }
        if($password==$repassword){
            if(strlen($password)<6 || strlen($password)>20){
                $json=array('status'=>0,'msg'=>'密码长度在6到20位之间！');
                foreach ( $json as $key => $value ) {
                    $json[$key] = urlencode ( $value );
                }
                echo urldecode ( json_encode ( $json ) );exit();
                // echo json_encode(array('status'=>0,'msg'=>'密码长度在6到20位之间！'));exit();
            }
            $data['password']=md5($password);
            $sql=$db->where("id=".$uid)->save($data);
            if($sql){
                $json=array('status'=>1,'msg'=>'修改成功');
                foreach ( $json as $key => $value ) {
                    $json[$key] = urlencode ( $value );
                }
                echo urldecode ( json_encode ( $json ) );
            }else{
                $json=array('status'=>0,'msg'=>'修改失败');
                foreach ( $json as $key => $value ) {
                    $json[$key] = urlencode ( $value );
                }
                echo urldecode ( json_encode ( $json ) );exit();
            }
        }else{
            $json=array('status'=>0,'msg'=>'修改失败,两次密码不一样');
            foreach ( $json as $key => $value ) {
                $json[$key] = urlencode ( $value );
            }
            echo urldecode ( json_encode ( $json ) );exit();
        }

    }

    public function uploadImg(){
        $db=M('admin');
        $uid=$this->data['uid'];
        $head_ico=$this->data['head_ico'];
        $data['picture']=$head_ico;
        $sql=$db->where("id=".$uid)->save($data);
        if($sql){
            $json=array('status'=>1,'msg'=>'修改成功');
            foreach ( $json as $key => $value ) {
                $json[$key] = urlencode ( $value );
            }
            echo urldecode ( json_encode ( $json ) );
        }else{
            $json=array('status'=>0,'msg'=>'修改失败');
            foreach ( $json as $key => $value ) {
                $json[$key] = urlencode ( $value );
            }
            echo urldecode ( json_encode ( $json ) );
        }

    }

    public function uploadPic(){
        // $db=M('admin');
        // $uid=$_POST['uid'];
        $head_ico=$this->data['file'];
        if($_FILES['file']['error']==0){
            $upload = new \Think\Upload();                                    // 实例化上传类
            $upload->maxSize   =     3145728 ;                               // 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');    // 设置附件上传类型
            $upload->rootPath  =     'Uploads/';                           // 设置附件上传目录
            $upload->savePath  = '';                                      // 设置附件上传（子）目录
            $info   =   $upload->uploadOne($_FILES['file']);             // 单文件上传方法引用
            if(!$info) {                                                // 判断上传错误提示错误信息
                $data=$this->error($upload->getError());
                // $json=array('status'=>0,'data'=>$data,'msg'=>'上传失败');
                //     foreach ( $json as $key => $value ) {
                //     $json[$key] = urlencode ( $value );
                //     }
                //     echo urldecode ( json_encode ( $json ) );
                echo json_encode(array('status'=>0,'data'=>$data,'msg'=>'上传失败'));exit();
            }else{                                                    // 上传成功 获取上传文件信息
                $file='/Uploads/'.$info['savepath'].$info['savename'];
                // $json=array('status'=>1,'data'=>$data['picture'],'msg'=>'上传成功');
                //    foreach ( $json as $key => $value ) {
                //    $json[$key] = urlencode ( $value );
                //    }
                //    echo urldecode ( json_encode ( $json ) );
                echo json_encode(array('status'=>1,'data'=>$file,'msg'=>'上传成功'));
            }
        }else{
            $file=$head_ico;
            echo json_encode(array('status'=>0,'data'=>$file,'msg'=>'上传失败'));exit();
        }
    }

    public function addSuggestion(){
        $uid=$this->data['uid'];
        $content=$this->data['content'];
        // $nickname=$this->data['nickname'];
        $db=M('suggestion');
        $user=M('admin');
        $row=$user->where('id='.$uid)->find();
        if(isset($content)){
            $data['content']=$content;
            $data['addtime']=time();
            $data['nickname']=$row['nickname'];
            // $data['id']=$uid;
            $res=$db->add($data);
            if($res){
                $json=array('status'=>1,'msg'=>'反馈成功');
                foreach ( $json as $key => $value ) {
                    $json[$key] = urlencode ( $value );
                }
                echo urldecode ( json_encode ( $json ) );
            }else{
                $json=array('status'=>0,'data'=>$db->getLastSql(),'msg'=>'反馈失败');
                foreach ( $json as $key => $value ) {
                    $json[$key] = urlencode ( $value );
                }
                echo urldecode ( json_encode ( $json ) );
            }
        }
    }

    public function getAndroidVersion(){
        $db=M('version');
        $data=$db->order('id desc')->limit(0,1)->select();
        echo json_encode(array('status'=>1,'data'=>$data[0],'msg'=>'获取成功'));
        // $json=array('status'=>1,'data'=>$data[0],'msg'=>'获取成功');
        //          foreach ( $json as $key => $value ) {
        //          $json[$key] = urlencode ( $value );
        //          }
        //          echo urldecode ( json_encode ( $json ) );
    }

}
