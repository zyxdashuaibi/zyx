<?php

// [ 应用入口文件 ]
namespace think;

// 检测PHP环境
if (version_compare(PHP_VERSION, '7.1.0', '<')) die('require PHP > 7.1.0 !');

// 加载核心文件
require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;

// 特殊路由
$_amain = 'index';
$_aother = 'admin|api|store|common|console'; // 这里是除了index以外的所有其他应用
if (preg_match('/^\/('.$_aother.')\/?/', $_SERVER['REQUEST_URI'])) {
    $response = $http->run();
} else {
    if(strpos($_SERVER['REQUEST_URI'],'/admin/') !== false || strpos($_SERVER['REQUEST_URI'],'/api/') !== false || strpos($_SERVER['REQUEST_URI'],'/store/') !== false || strpos($_SERVER['REQUEST_URI'],'/console/') !== false || strpos($_SERVER['REQUEST_URI'],'/common/') !== false ){
        $response = $http->run();
    }else{
        $response = $http->name($_amain)->run();
    }

}
//$response = $http->run();

$response->send();

$http->end($response);

