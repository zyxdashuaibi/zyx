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
declare (strict_types=1);

namespace app\common\model;

use app\common\library\helper;
use cores\BaseModel;
use think\facade\Cache;
use app\common\exception\BaseException;

/**
 * 微信小程序模型 (当前类已废弃，请勿使用)
 * Class Wxapp
 * @package app\common\model
 */
class Wxapp extends BaseModel
{
    // 定义表名
    protected $name = 'wxapp';

    // 定义主键名
    protected $pk = 'id';

    /**
     * 获取微信小程序配置 (即将废弃, 用于兼容v2.0.4之前)
     * @param int|null $storeId
     * @return array
     */
    public static function getOldData(?int $storeId = null): array
    {
        empty($storeId) && $storeId = static::$storeId;
        $detail = static::get(['store_id' => $storeId]);
        if (empty($detail)) {
            return [];
        }
        return helper::pick($detail->toArray(), ['app_id', 'app_secret', 'mchid', 'apikey', 'cert_pem', 'key_pem']);
    }
}
