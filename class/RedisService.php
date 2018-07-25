<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 9:53
 */

namespace DataEditer;

class RedisService
{
    private static $instance = null;
    private static $redis = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
            ////连接本地的 Redis 服务
            static::$redis = new \Redis();
            static::$redis->connect('127.0.0.1', 6379);
        }

        return static::$instance;
    }

    /**
     * 保存数据
     * @param array $data
     */
    public function set($data)
    {
        foreach ($data as $index => $value) {
            static::$redis->set('de_' . $index, $value);
        }
    }

    /**
     * 读取数据
     * @param array $data
     * @return array
     */
    public function get($data)
    {
        foreach ($data as $value) {
            $data[$value] = static::$redis->get('de_' . $value);
        }

        return $data;
    }

    /**
     * 读取一个数据
     * @param string $key
     * @return mixed
     */
    public function get_one($key)
    {
        $data = static::$redis->get('de_' . $key);

        return $data;
    }
}
