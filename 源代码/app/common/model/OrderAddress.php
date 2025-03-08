<?php
// +----------------------------------------------------------------------
// | 萤火商城系统 [ 致力于通过产品和服务，帮助商家高效化开拓市场 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://www.yiovo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 这不是一个自由软件，不允许对程序代码以任何形式任何目的的再发行
// +----------------------------------------------------------------------
// | Author: 萤火科技 <admin@yiovo.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\common\model;

use cores\BaseModel;
use app\common\model\Region as RegionModel;

/**
 * 订单收货地址模型
 * Class OrderAddress
 * @package app\common\model
 */
class OrderAddress extends BaseModel
{
    // 定义表名
    protected $name = 'order_address';

    // 定义主键
    protected $pk = 'order_address_id';

    protected $updateTime = false;

    /**
     * 追加字段
     * @var array
     */
    protected $append = ['region'];

    /**
     * 获取器：地区名称
     * @param $value
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRegionAttr($value, $data)
    {
        return [
            'province' => RegionModel::getNameById($data['province_id']),
            'city' => RegionModel::getNameById($data['city_id']),
            'region' => RegionModel::getNameById($data['region_id'])
        ];
    }

    /**
     * 获取完整地址
     * @return string
     */
    public function getFullAddress()
    {
        return $this['region']['province'] . $this['region']['city'] . $this['region']['region'] . $this['detail'];
    }

    /**
     * 获取完整地址
     * @param $detail
     * @return string
     */
    public static function fullAddress($detail)
    {
        return $detail['region']['province'] . $detail['region']['city'] . $detail['region']['region'] . $detail['detail'];
    }
}
