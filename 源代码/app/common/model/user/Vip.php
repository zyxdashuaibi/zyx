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
 * 用户Vip模型
 * Class Vip
 * @package app\common\model\user
 */
class Vip extends BaseModel
{
    // 定义表名
    protected $name = 'bbp_user_vip';

    // 定义主键
    protected $pk = 'vip_id';

    /**
     * 关联音乐分类表
     * @return \think\model\relation\BelongsTo
     */
    public function plan()
    {
        $module = self::getCalledModule();
        return $this->BelongsTo("app\\{$module}\\model\\recharge\\Plan", 'plan_id');
    }

    /**
     * vip套餐详情
     * @param
     * @param array $with
     * @return null|static
     */
    public static function detail($vip_id,array $with = [])
    {
        return self::get($vip_id,$with);
    }

}
