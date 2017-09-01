<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/19 0019
 * Time: 21:27
 */

namespace app\index\controller;


use app\index\model\News;
use app\index\model\Test;
use think\Db;

class Mongodb
{
    private $config = [
        'type' => '\think\mongo\Connection',

        'database'        => 'demo',
        // 用户名
        'username'        => 'test',
        // 密码
        'password'        => 'test',
        // 端口
        'hostport'        => '27017',

        'charset'         => 'utf8',
    ];

    public function buildBillionData(){
        $mongodb = $this->connect();
        $article = $mongodb->selectCollection('article');
        if (!$article) {
            $mongodb->createCollection('article');
            $article = $mongodb->selectCollection('article');
        }
        for ($i = 1 ; $i < 100000000; ++$i) {
            if($article->insert(['id'=>$i,'title' => 'title_'.$i,'content'=>'content_'.$i])){

            } else {

            };
        }
    }

    public function testConnect(){
        Db::connect($this->config);
        $article = Db::name('User');
        if ($article) {
            die( 'connect success');
        } else {
            die( 'connect failed');
        }
    }

    public function testFind(){
        $mc = new \MongoClient('mongodb://test:test@localhost:27017');
        $mg = new \MongoDB($mc,'demo');
        //$mg->authenticate('test','test');  #duplicate
        $data = $mg->ta_news->find([],['id']);

       # 1、
       /* while (($data->hasNext())) {
            var_dump($data->next()['_id']);
            echo '<br>';
        }*/
       # 2、等效1
        foreach ($data as $key => $value) {
            var_dump($data->next()['_id']);
            echo '<br>';
       }

       echo 'one<br>';
       $one =  $mg->ta_news->findOne();
       echo get_object_vars($one['_id'])['$id'];
    }

    public function connect(){
        $mongocolient = new \MongoClient('mongodb://test:test@localhost:27017');
        $mongodb = new \MongoDB($mongocolient,'demo');
        return $mongodb;
    }

    public function getObjectId($mongoId){
        if ( $mongoId && $mongoId instanceof \MongoId)
        return get_object_vars($mongoId)['$id'];
    }

}