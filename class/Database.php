<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/23
 * Time: 14:47
 */
namespace DataEditer;
use DataEditer\Pagination;

class Database
{
    private static $instance = null;
    private static $redis = null;
    public static $db = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance($redis)
    {
        if (null === static::$instance) {
            static::$instance = new static();
            static::$redis = $redis;
        }

        if (null === static::$db) {
            $connect = static::$redis->get(['host','dbname','user','password']);
            $dsn = 'mysql:host=' . $connect['host'] . ';dbname=' . $connect['dbname'];
            try {
                static::$db = new \PDO($dsn, $connect['user'], $connect['password']);
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }

        return static::$instance;
    }

    public function getTable(){
        $data = static::$db->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
        return $data;
    }

    /**
     * 拼接SQL语句
     * @param array $params
     * @param string $limit
     * @return string
     */
    private function getSQL($params,$limit){
        $sql = 'select '. ($params['filed'] ? $params['filed'] : '*') . ' from ' . $params['table'] . ($params['where'] ? ' where ' . $params['where'] : '');
        $sql .= ($params['group'] ? ' group by ' . $params['group'] : '') . ($params['having'] ? ' having ' . $params['having'] : '') . ($params['orderby'] ? ' order by ' . $params['orderby'] : '');
        if (false != $limit){
            $sql .= ' limit ' . $limit;
        }
        return $sql;
    }

    /**
     * 查询数据
     * @param array $params
     * @param string $limit
     * @return array
     */
    public function query($params, $limit){
        $sql = $this->getSQL($params,$limit);
        $data = static::$db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        return $data;
    }

}