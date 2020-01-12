<?php
/**
 * 地图地址获取相关方法
 *
 * @date: 2019/10/13
 */

namespace App\Service\Utils;

class MapPlace
{
    /**
     * 地点检索服务
     *
     * @date: 2019/10/14
     * @author: jason
     *
     * @param string $query 查询关键字
     * @param string $location 坐标
     * @param int $radius 限制半径
     * @return mix
     */
    public static function search($query, $location, $region=null, $cityLimit=true, $radius=1000)
    {
        $params = [
            'output' => 'json',
            'radius' => $radius,
            'radius_limit' => $cityLimit ? 'true' : 'false',
            'ak' => config('liuxuekw.baidu.map.ak'),
            'page_size' => 20,
            'page_num' => 1,
            // 'coord_type' => 'wgs84ll',
            // 'scope' => 2,
            // 'filter' => 'sort_name:distance|sort_rule:1',

            'location' => $location,
            'query' => $query,
            'city_limit' => $cityLimit ? 'true' : 'false',
        ];

        if (!empty($region)) $params['region'] = $region;
        
        $url = 'http://api.map.baidu.com/place/v2/search?' . http_build_query($params);
        
        $response = \App\Service\Utils\Http::get($url);
        $result = $response->getBody();

        $temp = json_decode($result, true);

        if ($temp['status'] == 0) {
            return $temp['results'];
        } else {
            return null;
        }
    }
    
    public static function searchAbroad($query, $location, $region=null, $cityLimit=true, $radius=1000 * 50)
    {
        $params = [
            'output' => 'json',
            'radius' => $radius,
            'ak' => config('liuxuekw.baidu.map.ak'),
            'page_size' => 20,
            'page_num' => 1,
            //'coord_type' => 'wgs84ll',

            'location' => $location,
            'query' => $query,
        ];

        if (!empty($region)) $params['region'] = $region;
        
        $url = 'http://api.map.baidu.com/place_abroad/v1/search?' . http_build_query($params);
        
        $response = \App\Service\Utils\Http::get($url);
        $result = $response->getBody();

        $temp = json_decode($result, true);

        if ($temp['status'] == 0) {
            return $temp['results'];
        } else {
            return null;
        }
    }


    /**
     * 地点输入提示服务
     *
     * @date: 2019/10/14
     * @author: jason
     *
     * @param string $query 查询关键字
     * @param string $location 坐标
     * @param int $radius 限制半径
     * @return mix
     */
    public static function suggestion($query, $location, $region=null, $cityLimit=false)
    {
        $params = [
            'output' => 'json',
            'ak' => config('liuxuekw.baidu.map.ak'),

            'location' => $location,
            'query' => $query,
            'city_limit' => $cityLimit ? 'true' : 'false',
        ];

        if (!empty($region)) $params['region'] = $region;
        
        $url = 'http://api.map.baidu.com/place/v2/suggestion?' . http_build_query($params);
        
        $response = \App\Service\Utils\Http::get($url);
        $result = $response->getBody();

        $temp = json_decode($result, true);

        if ($temp['status'] == 0) {
            foreach ($temp['result'] as $index => $item) {
                $temp['result'][$index]['address'] = $item['province'] . $item['city'] . $item['district'];
            }
            
            return $temp['result'];
        } else {
            return null;
        }
    }

    public static function suggestionAbroad($query, $location, $region=null, $cityLimit=false)
    {
        $params = [
            'output' => 'json',
            'ak' => config('liuxuekw.baidu.map.ak'),

            'location' => $location,
            'query' => $query,
        ];

        if (!empty($region)) $params['region'] = $region;
        
        $url = 'http://api.map.baidu.com/place_abroad/v1/suggestion?' . http_build_query($params);
        
        $response = \App\Service\Utils\Http::get($url);
        $result = $response->getBody();

        $temp = json_decode($result, true);

        if ($temp['status'] == 0) {
            foreach ($temp['result'] as $index => $item) {
                $temp['result'][$index]['address'] = $item['province'] . $item['city'] . $item['district'];
            }
            
            return $temp['result'];
        } else {
            return null;
        }
    }

    
    /**
     * 逆地理编码
     *
     * @date: 2019/10/22
     * @author: jason
     *
     * @param string $location lat<纬度>,lng<经度> 
     * @param string $coordtype 坐标的类型，目前支持的坐标类型包括：bd09ll（百度经纬度坐标）、bd09mc（百度米制坐标）、gcj02ll（国测局经纬度坐标，仅限中国）、wgs84ll（ GPS经纬度）
     * @return mix 
     */
    public static function reverseGeocoding($location, $coordtype='bd09ll')
    {
        $params = [
            'output' => 'json',
            'ak' => config('liuxuekw.baidu.map.ak'),

            'location' => $location,
            'coordtype' => $coordtype,
        ];

        $url = 'http://api.map.baidu.com/reverse_geocoding/v3/?' . http_build_query($params);

        $response = \App\Service\Utils\Http::get($url);
        $result = $response->getBody();

        $temp = json_decode($result, true);

        if ($temp['status'] == 0) {
            return $temp['result'];
        } else {
            return null;
        }
    }

    public static function geoConvert($location, $from, $to)
    {
        $params = [
            'output' => 'json',
            'ak' => config('liuxuekw.baidu.map.ak'),

            'coords' => $location,
            'from' => $from,
            'to' => $to,
        ];

        $url = 'http://api.map.baidu.com/geoconv/v1/?' . http_build_query($params);

        $response = \App\Service\Utils\Http::get($url);
        $result = $response->getBody();

        $temp = json_decode($result, true);

        if ($temp['status'] == 0) {
            return $temp['result'];
        } else {
            return null;
        }
    }
}
