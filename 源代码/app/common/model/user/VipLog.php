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

namespace app\common\model\user;

use cores\BaseModel;

/**
 * 用户vip购买记录模型
 * Class VipLog
 * @package app\common\model\user
 */
class VipLog extends BaseModel
{
    // 定义表名
    protected $name = 'bbp_user_vip_log';

    // 定义主键
    protected $pk = 'id';

    protected $updateTime = false;

    /**
     * 关联会员记录表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        $module = self::getCalledModule();
        return $this->belongsTo("app\\{$module}\\model\\User",'user_id');
    }


    /**
     * 付款时间
     * @param $value
     * @return array
     */
    public function getPayTimeAttr($value)
    {
        return format_time($value);
    }

    /**
     * 新增记录
     * @param $data
     */
    public static function add($data)
    {
        $static = new static;
        $static->save(array_merge(['store_id' => $static::$storeId], $data));
    }

    /**
     * 新增记录 (批量)
     * @param $saveData
     * @return bool
     */
    public function onBatchAdd($saveData)
    {
        return $this->addAll($saveData) !== false;
    }

    /**
     * 新增变更记录 (批量)
     * @param $data
     * @return int
     */
    public function records($data)
    {
        $saveData = [];
        foreach ($data as $item) {
            $saveData[] = array_merge([
                'store_id' => static::$storeId
            ], $item);
        }
        return $this->addAll($saveData) !== false;
    }

    /**
     * 生成订单号`
     * @return string
     */
    public function createOrderNo(): string
    {
        return date('Ymdhms') . substr(implode('', array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 订单详情
     * @param $where
     * @param array $with
     * @return array|null|static
     */
    public static function detail($where, array $with = [])
    {
        return self::get($where, $with);
    }
}
