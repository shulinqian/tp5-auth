<?php
define('IS_TEST', true);
include (__DIR__ . '/init.php');

//
$admin = new \thinkweb\auth\examples\Admin();
$post = [
    'username' => 'admin',
    'password' => 'admin',
];

if($user = $admin->loginByUsername($post)){
    echo "登录成功<br/>";
}
$user = $admin->getLoginUser();


$prefix = '';
$config = [
    'auth_on' => true,
    'auth_type' => 3,

    'auth_group' => $prefix . 'admin_auth_group',
    'auth_group_access' => $prefix . 'admin_auth_group_access',
    'auth_rule' => $prefix . 'admin_auth_rule',
    'auth_user' => $prefix . 'admin',
];
$auth = thinkweb\auth\Auth::instance($config);

$rs = $auth->check('news.add', $user['id'], 1, 'module');
$rs = $auth->check('goods.add, news.add1', $user['id'], 1, 'module');
$rs = $auth->check('goods.add, news.add1', $user['id'], 1, 'module');
if($rs){
    echo "验证成功<br/>";
}

$trace_show();