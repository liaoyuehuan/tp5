<?php
namespace app\index\controller;
class Key {
    public function index(){
        var_dump(openssl_pkey_new());
    }
}