<?php

namespace App\Service;

/**
 * 业务模型基类.
 */
class Base
{
    /**
     * 关联数据Model.
     *
     * var App\Model\Base
     */
    protected $baseModel = null;

    /**
     * 常量映射关系表.
     */
    protected static $constantMap = [];

    /**
     * 通过主键获取资料.
     *
     * @param mix $id 主键值
     *
     * @return array
     */
    public function loadData($id)
    {
        return $this->baseModel->reset()->loadData($id)->getData();
    }

    /**
     * 新增数据.
     *
     * @param array $data 新增数据
     */
    public function addData($data)
    {
        return $this->baseModel->insertGetId($data);
    }

    /**
     * 通过主键更新数据.
     *
     * @param mix   $id
     * @param array $data
     *
     * @return bool
     */
    public function updateData($id, $data)
    {
        return $this->baseModel->updateDataById($id, $data);
    }

    /**
     * 通过属性获取资料.
     *
     * @param string $attribute 属性名
     * @param mix    $value     属性值
     *
     * @return array
     */
    public function loadDataByAttribute($attribute, $value)
    {
        return $this->baseModel->loadDataByAttribute($attribute, $value); }

    /**
     * 通过属性值更新数据.
     *
     * @param mix   $id
     * @param array $data
     *
     * @return bool
     */
    public function updateDataByAttribute($attribute, $value, $data)
    {
        return $this->baseModel->updateDataByAttribute($attribute, $value, $data);
    }

    /**
     * 通过filter更新数据.
     *
     * @param array $filter 更新条件 [["name", "张三"]]
     * @param array $data   更新数据
     */
    public function updateDataByFilter($filter, $data)
    {
        return $this->baseModel->updateDataByFilter($filter, $data);
    }

    /**
     * 通过主键进行删除.
     *
     * @param $id 主键值
     */
    public function deleteData($id)
    {
        return $this->baseModel->deleteData($id);
    }

    /**
     *  获取Model.
     */
    public function getBaseModel()
    {
        return $this->baseModel;
    }

    /**
     * 获取当前业务模型列表数据.
     *
     * @param bool   $isCount 是否返回记录条数
     * @param array  $filter  条件列表
     * @param array  $limit   分页数据 ['pagesize' => 10, 'page' => 1]
     * @param array  $orderBy 排序数据 [["field", "dir"]]
     * @param array  $joins   联表 [['leftJoin' = " table c ON e.xx=c.xx"]]
     * @param array  $fields  字段列表，默认为取所有字段
     * @param string $groupBy
     * @param string $having
     *
     * @return array
     */
    public function getDataList($isCount = false, $filter = [], $limit = [], $orderBy = [], $fields = [], $groupBy = '', $having = '')
    {
        return $this->baseModel->getDataList($isCount, $filter, $limit, $orderBy, $fields, $groupBy, $having);
    }

    /**
     * @method 获取页码类型
     * @author Victoria
     * @date   2020-01-12
     * @param  [type]            $list    
     * @param  [type]            $total   
     * @param  integer           $page    
     * @param  integer           $pagesize
     * @return array
     */
    public function getPaginationList($list, $total, $page = 1, $pagesize = 10)
    {
        return $this->baseModel->getPaginationList($list, $total, $page, $pagesize);
    }

    /**
     * 获取常量继承方法
     * @author   Mingrong
     * @DateTime 2020-01-10
     * @param    [type]     $const [description]
     * @param    string     $model [description]
     * @return   
     */
    public static function constant($const, $model = 'base')
    {
        $namespace = 'static';

        if (isset(static::$constantMap[$model])) {
            $namespace = static::$constantMap[$model];
        }

        return constant($namespace.'::'.$const);
    }
}
