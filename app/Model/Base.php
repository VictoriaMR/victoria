<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 封装一些常用的ORM方法，所有Model以此为基类
 *
 */
class Base extends Model
{
    /**
     * 方法名称映射缓存
     *
     * @var array
     */
    protected static $underscoreCache = [];

    /**
     * 通过主键获取数据
     *
     * @param mix $id 主键值
     * @return App\Model\Base
     */
    public function loadData($id)
    {
        $id = (int) $id;

        if (empty($id)) {
            return $this;
        }

        $data = $this->find($id);

        if (empty($data)) return $this->reset();

        $this->copyToAttribute($data);

        return $this;
    }

    /**
     * 通过属性值获取数据，注意此属性一般为unique
     *
     * @param string $filed
     * @param string/int $value
     * @return App\Model\Base
     */
    public function loadByAttribute($field, $value)
    {
        $field = trim($field);
        $value = trim($value);

        if (empty($field)) {
            return $this;
        }

        $data = $this->where($field, $value)->first();

        if (empty($data)) return $this->reset();

        $this->copyToAttribute($data);

        return $this;
    }

    public function copyToAttribute($obj)
    {
        if (empty($obj)) return $this;

        foreach ($obj->toArray() as $attr => $value) {
            $this->setAttribute($attr, $value);
        }

        return $this;
    }

    public function getId()
    {
        return $this->getKey();
    }

    /**
     * 获取属性值
     *
     * @param string $key 属性名称
     * @return mix $key为null时以数组形式返回所有属性
     */
    public function getData($key = null)
    {
        if ($key === null) {
            return $this->toArray();
        } else {
            return $this->__get($key);
        }
    }

    /**
     * 设置属性值
     *
     * @param string $key 属性名
     * @param $value 属性值
     * @return App\Model\Base
     */
    public function setData($key, $value = null)
    {
        if ($value !== null) {
            $this->__set($key, $value);
        } else {
            if (is_array($key)) {
                foreach ($key as $k => $v) {
                    $this->__set($k, $v);
                }
            }
        }

        return $this;
    }

    /**
     * 移除属性
     * 
     * @param string $key 属性名 为空是移除所有属性
     * @return App\Model\Base
     */
    public function unsetData($key = null)
    {
        $this->__unset($key);

        return $this;
    }

    /**
     * 判断属性是否存在
     *
     * @param string $key 属性名
     * @return bool
     */
    public function hasData($key = '')
    {
        return $this->__isset($key);
    }

    /**
     * 删除当前Model数据
     *
     * @return bool
     */
    public function deleteData($id)
    {
        return $this->where($this->getKeyName(), $id)->delete();
    }

    /**
     * 重置当前Model
     *
     * @return App\Model\Base
     */
    public function reset()
    {
        foreach ($this->getData() as $attr => $value) {
            $this->unsetData($attr);
        }

        return $this;
    }

    /**
     * 通过主键更新数据
     *
     * @param mix $id
     * @param array $data
     * @return bool
     */
    public function updateDataById($id, $data)
    {
        return $this->where($this->getKeyName(), $id)->update($data);
    }

    /**
     * 通过属性值更新数据
     * 
     * @param string $attribute
     * @param mix $value
     * @param array $data
     * @return bool
     */
    public function updateDataByAttribute($attribute, $value, $data)
    {
        return $this->where($attribute, $value)->update($data);
    }

    /**
     * 通过filter更新数据
     *
     * @param array $filter 更新条件 [["name", "张三"]]
     * @param array $data 更新数据
     */
    public function updateDataByFilter($filter, $data)
    {
        if (empty($filter)) return false;

        return $this->parseFilter($filter)->update($data);
    }

    /**
     * 获取当前业务模型列表数据
     *
     * @param bool $isCount 是否返回记录条数
     * @param array $filter 条件列表
     * @param array $limit 分页数据 ['pagesize' => 10, 'page' => 1]
     * @param array $orderBy 排序数据 [["field", "dir"]]
     * @param array $joins 联表 [['leftJoin' = " table c ON e.xx=c.xx"]]
     * @param array $fields 字段列表，默认为取所有字段
     * @param string $groupBy 
     * @param string $having
     * @return array
     */
    public function getDataList($isCount = false, $filter = [], $limit = [], $orderBy = [], $fields = [], $groupBy = null, $having = null)
    {
        if ($isCount == true) {
            if (!empty($groupBy)) {
                $query = $this->parseFilter($filter)->groupBy($groupBy);

                if (!empty($fields)) $query = $query->select($fields);

                return (int)$query->get()->count();
            } else {
                return (int)$this->parseFilter($filter)->count();
            }
        }

        $query = $this->parseFilter($filter);
        if (!empty($limit)) {
            $query = $query->offset($limit["pagesize"] * ($limit["page"] - 1))->limit($limit["pagesize"]);
        }

        if (!empty($orderBy)) {
            if (is_array($orderBy)) {
                foreach ($orderBy as $item) {
                    $query = $query->orderBy($item[0], $item[1]);
                }
            } else {
                $query = $query->orderByRaw($orderBy);
            }
        }

        if (!empty($fields)) $query = $query->select($fields);
        else $query = $query->select('*');

        if (!empty($groupBy)) $query = $query->groupBy($groupBy);

        if (!empty($having)) $query = $query->havingRaw($having);

        return $query->get();
    }

    protected function parseFilter($filter = [], $query = null)
    {
        $type = $this->detectFilterType($filter);

        if ($query === null) $query = $this;

        if ($type == 1) return $query->where($filter);

        foreach ($filter as $item) {
            if (isset($item[0]) && is_array($item[0])) { //or join
                $query = $query->where(function ($cQuery) use ($item) {
                    foreach ($item as $cIndex => $cItem) {
                        if (isset($cItem[0]) && is_array($cItem[0])) {
                            $cQuery = $cQuery->Orwhere(function ($ccQuery) use ($cItem) {
                                foreach ($cItem as $ccIndex => $ccItem) {
                                    if ($ccIndex == 0) $ccQuery = $this->parseWhere($ccQuery, $ccItem, 'or');
                                    else $ccQuery = $this->parseWhere($ccQuery, $ccItem);
                                }

                                return $ccQuery;
                            });
                        } else {
                            if ($cIndex == 0) $cQuery = $this->parseWhere($cQuery, $cItem);
                            else $cQuery = $this->parseWhere($cQuery, $cItem, 'or');
                        }
                    }

                    return $cQuery;
                });
            } else { //and join
                $query = $this->parseWhere($query, $item);
            }
        }

        return $query;
    }

    protected function parseWhere($query, $where, $type = 'and')
    {
        $type = strtolower($type);

        if (isset($where[0]) && strpos($where[0], ' ') !== false) { //Raw 模式
            $query = call_user_func_array([$query, $type == 'or' ? 'orWhereRaw' : 'whereRaw'], $where);
        } else if (isset($where[1]) && strtolower($where[1]) == 'in') { //in
            if (!isset($where[2]) || !is_array($where[2])) return $query; //不合法格式直接舍弃

            $query = call_user_func_array([$query, $type == 'or' ? 'orWhereIn' : 'whereIn'], [$where[0], $where[2]]);
        } else if (isset($where[1]) && strtolower($where[1]) == 'not in') { // not in
            if (!isset($where[2]) || !is_array($where[2])) return $query;

            $query = call_user_func_array([$query, $type == 'or' ? 'orWhereNotIn' : 'whereNotIn'], [$where[0], $where[2]]);
        } else if (isset($where[1]) && strtolower($where[1]) == 'between') { // between
            if (!isset($where[2]) || !is_array($where[2])) return $query;

            $query = call_user_func_array([$query, $type == 'or' ? 'orWhereBetween' : 'whereBetween'], [$where[0], $where[2]]);
        } else if (isset($where[1]) && strtolower($where[1]) == 'not between') { // between
            if (!isset($where[2]) || !is_array($where[2])) return $query;

            $query = call_user_func_array([$query, $type == 'or' ? 'orWhereNotBetween' : 'whereNotBetween'], [$where[0], $where[2]]);
        } else {
            $query = call_user_func_array([$query, $type == 'or' ? 'orWhere' : 'where'], $where);
        }

        return $query;
    }

    protected function detectFilterType($filter = [])
    {
        //type 1 ['field' => $value]
        //type 2 [['field', $value], ['field2', $value2]]
        //type 3 [[['field', $value], ['field2', $value2]]]
        //type 4 [[[['field', $value], ['field1', $value1]], ['field2', $value2]]]

        if (empty($filter)) return 1;

        $keys = array_keys($filter);
        if (!is_numeric($keys[0]) && $keys[0] !== 0) return 1;

        return 2;
    }

    protected function parsePagination($page = [], $orderBy = [], $query = null)
    {
        if ($query === null) $query = $this;

        if (!empty($orderBy)) {
            foreach ($orderBy as $item) {
                $query = $query->orderBy($item[0], $item[1]);
            }
        }

        if (!empty($page)) {
            $query = $query->offset($page["pagesize"] * ($page["page"] - 1))->limit($page["pagesize"]);
        }

        return $query;
    }

    public function getPaginationList($list, $total, $page = 1, $pagesize = 10)
    {
        return [
            "pagination" => [
                "total" => $total,
                "pagesize" => $pagesize,
                "page" => $page
            ],

            "list" => $list
        ];
    }

    protected function underscore($name)
    {
        if (isset(self::$underscoreCache[$name])) {
            return self::$underscoreCache[$name];
        }
        $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
        self::$underscoreCache[$name] = $result;
        return $result;
    }

    protected function camelize($name)
    {
        return uc_words($name, '');
    }

    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
            case 'get':
                $key = $this->underscore(substr($method, 3));
                $data = $this->getData($key, isset($args[0]) ? $args[0] : null);

                return $data;
            case 'set':
                $key = $this->underscore(substr($method, 3));
                $result = $this->setData($key, isset($args[0]) ? $args[0] : null);

                return $result;
            case 'uns':
                $key = $this->underscore(substr($method, 3));
                $result = $this->unsetData($key);

                return $result;
            case 'has':
                $key = $this->underscore(substr($method, 3));

                return $this->hasData($key);
            default:;
        }

        return parent::__call($method, $args);
    }
}