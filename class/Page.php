<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/24
 * Time: 11:31
 */

namespace DataEditer;

use DataEditer\Pagination;

class Page
{
    private $data = null;

    /**
     * Page constructor.
     * @param RedisService $redis
     * @param int $current_page
     */
    public function __construct($redis, $current_page)
    {
        $current_page = $current_page ?: 1;
        $per_page = $redis->get_one('perpage');
        $per_page = $per_page ?: 10;
        $this->data['perpage'] = $per_page;
        $database = Database::getInstance($redis);
        if ($database instanceof Database) {
            $this->data['status'] = '1';
            $query = $redis->get(['filed', 'table', 'where', 'groupby', 'having', 'orderby']);
            $limit = (($current_page - 1) * $per_page) . ', ' . $per_page;
            $redis->set(['page', $current_page]);
            $this->data['data'] = $database->query($query, $limit);

            $query['filed'] = 'count(*) as nums';
            $this->data['nums'] = $database->query($query, false)['0']['nums'];

            $total_page_num = ceil($this->data['nums'] / 10);
            //页码[start]
            $pagination = new Pagination($total_page_num, $current_page);
            $page_num['prev']['num'] = $pagination->prevPage();
            $page_num['prev']['class'] = 'page-item';
            if ($page_num['prev']['num'] == $current_page) {
                $page_num['prev']['class'] .= ' active';
            }
            if (empty($page_num['prev']['num'])) {
                $page_num['prev']['class'] .= ' disabled';
            }
            $page_num['next']['num'] = $pagination->nextPage();
            $page_num['next']['class'] = 'page-item';
            if ($page_num['next']['num'] == $current_page) {
                $page_num['next']['class'] .= ' active';
            }
            if (empty($page_num['next']['num'])) {
                $page_num['next']['class'] .= ' disabled';
            }
            $nums = $pagination->getPageNum(5, true);
            foreach ($nums as $index => $num) {
                $num = $num > 0 ? $num : '...';
                $p[$index]['num'] = $num;
                $p[$index]['class'] .= 'page-item';
                if ($num == $current_page) {
                    $p[$index]['class'] .= ' active';
                }
                if (empty($num)) {
                    $p[$index]['class'] .= ' disabled';
                }
                $page_num['num'] = $p;
            }
            $this->data['pagination'] = $page_num;
            //页码[end]
        } else {
            $this->data['exception'] = $database;
        }
    }

    public function getData()
    {
        return $this->data;
    }

}