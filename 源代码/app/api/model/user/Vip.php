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

namespace app\api\model\user;

use app\common\model\user\Vip as VipModel;

/**
 * 用户VIP模型
 * Class Vip
 * @package app\api\model\user
 */
class Vip extends VipModel
{
    public function buyVip(array $userInfo,int $planId){
        //获取充值套餐信息
        $plan = Play::detail($planId);
        //插入购买信息
        $id = self::insertGetId(
            ['mobile'=>$userInfo['mobile'],'plan_id'=>$planId,'plan_name'=>$plan['']]
        );
    }
}
