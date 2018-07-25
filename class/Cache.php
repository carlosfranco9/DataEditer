<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 9:43
 */
namespace DataEditer;

class Cache
{
    private static $dir = '/cache/';

    /**
     * 传入文件名和数据，写入缓存文件
     * @param string $file
     * @param array $data
     */
    public static function write($file, $data)
    {
        //缓存
        $text = '<?php $cache=' . var_export($data, true) . ';';
        if (false !== fopen(self::$dir . $file, 'w+')) {
            file_put_contents(self::$dir . $file, $text);
        } else {
            echo '创建失败';
        }
    }

    /**
     * 读取指定文件的缓存数据
     * @param string $file
     * @return array
     */
    public static function read($file)
    {
        require_once self::$dir . $file;

        return $cache;
    }

    /**
     * 删除指定文件
     * @param string $file
     */
    public static function delete($file)
    {
        unlink(self::$dir . $file);
    }
}