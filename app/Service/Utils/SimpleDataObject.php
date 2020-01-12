<?php

namespace App\Service\Utils;

/**
 * 简单数据对象
 *
 */
class SimpleDataObject
{
    /**
     * 基础数据
     *
     * @var array 
     */
    protected $data = null;

    /**
     * 方法名称映射缓存
     *
     * @var array
     */
    protected static $underscoreCache = [];
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function setData($key, $value=null)
    {
        if ($value !== NULL) {
            $this->data[$key] = $value;
        } else {
            if (is_array($key)) {
                foreach ($key as $k => $v) {
                    $this->data[$k] = $v;
                }
            }
        }

        return $this;
    }

    /**
     * 获取属性值
     *
     * @param string $key 属性名称
     * @return mix $key为null时以数组形式返回所有属性
     */
    public function getData($key=null)
    {
        if ($key === NULL) {
            return $this->data;
        } else {
            return isset($this->data[$key])?$this->data[$key]:null;
        }
    }

    /**
     * 移除属性
     * 
     * @param string $key 属性名 为空是移除所有属性
     * @return App\Utils\SimpleDataObject
     */
    public function unsetData($key=null)
    {
        if ($this->hasData($key)) unset($this->data[$key]);
        
        return $this;
    }

    /**
     * 判断属性是否存在
     *
     * @param string $key 属性名
     * @return bool
     */
    public function hasData($key='')
    {
        return isset($this->data[$key]);
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
            case 'get' :
                $key = $this->underscore(substr($method, 3));
                $data = $this->getData($key, isset($args[0]) ? $args[0] : null);
                
                return $data;
            case 'set' :
                $key = $this->underscore(substr($method, 3));
                $result = $this->setData($key, isset($args[0]) ? $args[0] : null);
                
                return $result;
            case 'uns' :
                $key = $this->underscore(substr($method, 3));
                $result = $this->unsetData($key);
                
                return $result;
            case 'has' :
                $key = $this->underscore(substr($method, 3));
                
                return $this->hasData($key);
            default:;
        }
    }
}
