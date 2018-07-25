<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/23
 * Time: 14:46
 */

use DataEditer\Cache;
use DataEditer\Database;
use DataEditer\RedisService;
use DataEditer\Page;

require 'vendor/autoload.php';
require_once 'common.php';

$a = $_GET['a'];
$redis = RedisService::getInstance();

//连接数据库，获取表名
if ('connect' == $a) {
    $connect = post(['host', 'dbname', 'user', 'password']);
    $redis->set($connect);
    $database = Database::getInstance($redis);
    if ($database instanceof Database) {
        $data['status'] = '1';
        $data['table'] = $database->getTable();
    } else {
        $data['exception'] = $database;
    }
    echo json_encode($data);
    exit;
}

//查询表中的数据
if ('query' == $a) {
    $query = post(['filed', 'table', 'where', 'groupby', 'having', 'orderby']);
    $redis->set($query);

    $page = new Page($redis, '');
    $data = $page->getData();
    echo json_encode($data);
    exit;
}
if ('page' == $a) {
    $page_num = post(['page']);

    $page = new Page($redis, $page_num);
    $data = $page->getData();
    echo json_encode($data);
    exit;
}

if('perpage' == $a){
    $per_page = post(['perpage']);
    $redis->set(['perpage'=>$per_page]);

    $page = new Page($redis, '');
    $data = $page->getData();
    echo json_encode($data);
    exit;
}

header("HTTP/1.1 404 Not Found", true, 404);
exit;