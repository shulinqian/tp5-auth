<?php
namespace think;
if(!defined('IS_TEST')){
    exit('hack!');
}

// ���ؿ�������ļ�
$env = 'dev';
// [ Ӧ������ļ� ]
define('TEST_PATH', __DIR__ . '/');
define('TEST_ROOT_PATH', __DIR__ . '/../../../../../');

// ����Ӧ��Ŀ¼
defined('APP_PATH') || define('APP_PATH', TEST_ROOT_PATH . 'application/');
if($env == 'prodcut'){
    define('CONF_PATH', TEST_ROOT_PATH . 'config/');
} else {
    define('CONF_PATH', TEST_ROOT_PATH.'config_dev/');
}

// ���ؿ�������ļ�
require TEST_ROOT_PATH . 'thinkphp/base.php';
// ִ��Ӧ��
$response =  App::run();

$trace_show = function ()use($response){
    // Trace����ע��
    if (\think\Env::get('app_trace', \think\Config::get('app_trace'))) {
        $data = '';
        \think\Debug::inject($response, $data);
        echo $data;
    }
};