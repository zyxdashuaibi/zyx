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

/**
 * 商家记录表模型
 * Class Store
 * @package app\common\model
 */
class Store extends BaseModel
{
    // 定义表名
    protected $name = 'store';

    // 定义主键
    protected $pk = 'store_id';

    /**
     * 关联logo图片
     * @return \think\model\relation\HasOne
     */
    public function logoImage()
    {
        return $this->hasOne('UploadFile', 'file_id', 'logo_image_id');
    }

    /**
     * 详情信息
     * @param int $storeId
     * @return array|null|static
     */
    public static function detail(int $storeId)
    {
        return self::get($storeId, ['logoImage']);
    }
}
