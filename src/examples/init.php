<?php
namespace think;
if(!defined('IS_TEST')){
    exit('hack!');
}

// 加载框架引导文件
$env = 'dev';
// [ 应用入口文件 ]
define('TEST_PATH', __DIR__ . '/');
define('TEST_ROOT_PATH', __DIR__ . '/../../../../../');

// 定义应用目录
defined('APP_PATH') || define('APP_PATH', TEST_ROOT_PATH . 'application/');
if($env == 'prodcut'){
    define('CONF_PATH', TEST_ROOT_PATH . 'config/');
} else {
    define('CONF_PATH', TEST_ROOT_PATH.'config_dev/');
}

// 加载框架引导文件
require TEST_ROOT_PATH . 'thinkphp/base.php';
// 执行应用
$response =  App::run();

$trace_show = function ()use($response){
    // Trace调试注入
    if (\think\Env::get('app_trace', \think\Config::get('app_trace'))) {
        $data = '';
        \think\Debug::inject($response, $data);
        echo $data;
    }
};