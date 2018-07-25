<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/24
 * Time: 17:10
 */

namespace DataEditer;

/**
 * 页码类
 * Class Pagination
 * @package DataEditer
 */
class Pagination
{
    private $total = null;
    private $current_page = null;

    public function __construct($total, $current_page = null)
    {
        $this->setTotal($total);
        $this->setCurrentPage($current_page);
    }

    private function setCurrentPage($current_page)
    {
        $this->current_page = $current_page;
    }

    private function setTotal($total)
    {
        $this->total = $total;
    }

    public function nextPage()
    {
        if ($this->total > $this->current_page) {
            return $this->current_page + 1;
        }
        return '';
    }

    public function prevPage()
    {
        if ($this->current_page > 1) {
            return $this->current_page - 1;
        }
        return '';
    }

    /**
     * 根据页码数、是否显示省略，生成返回页码数组
     * 数组元素的值为int则为页码，值为''空字符串，则为省略
     *
     * @param int $nums
     * @param bool $omit
     * @return array
     */
    public function getPageNum($nums, $omit)
    {
        if ($nums >= $this->total) {//总页数小于要显示的页码数
            for ($i = 1; $i <= $nums; $i++) {
                $page[] = $i;
            }
        } else if ($this->current_page <= floor($nums / 2) + 1) {//当前页码小于等于中间的页码
            for ($i = 1; $i <= $nums; $i++) {
                $page[] = $i;
            }
            if ($omit) {
                $page[] = '';
            }
        } else if ($this->total - $this->current_page < floor($nums / 2) + 1) {//当前页码大于中间的页码
            if ($omit) {
                $page[] = '';
            }
            for ($i = 1; $i <= $nums; $i++) {
                $p = $this->current_page - 3 + $i;
                if ($p <= $this->total) {
                    $page[] = $p;
                }
            }
        } else {//显示的页码两端都有隐藏
            if ($omit) {
                $page[] = '';
            }
            for ($i = 1; $i <= $nums; $i++) {
                $page[] = $this->current_page - 3 + $i;
            }
            if ($omit) {
                $page[] = '';
            }
        }

        return $page;
    }

}