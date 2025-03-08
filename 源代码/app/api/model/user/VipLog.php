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

use app\api\model\recharge\Plan;
use app\common\library\wechat\WxPay;
use app\common\model\user\VipLog as VipLogModel;
use app\api\model\wxapp\Setting as WxappSettingModel;
use app\api\service\User as UserService;

/**
 * 用户VIP模型
 * Class Vip
 * @package app\api\model\user
 */
class VipLog extends VipLogModel
{
    /**
     * @param $userInfo
     * @param $planId
     * @return array|bool
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function buyVip($userInfo,$planId){
        //获取充值套餐信息
        $plan = Plan::detail($planId);
        //插入购买信息
        $vipLog = new VipLogModel();
        $storeId = getStoreId();
        $orderNo =$vipLog->createOrderNo();
        $id = self::insertGetId(
            [
                'order_no'=>$orderNo,
                'user_id' => $userInfo['user_id'],
                'plan_id' => $planId,
                'plan_name' => $plan['plan_name'],
                'money' => $plan['money']*100,
                'type' => $plan['type'],
                'number' => $plan['number'],
                'pay_status'=>10,
                'pay_time'=>time(),
                'source' => 10,
                'store_id' => $storeId,
                'create_time'=>time()
            ]
        );
        if(empty($id)){
             throwError('创建订单失败');
            return false;
        }
        $WxPay = new WxPay(static::getWxConfig(),$storeId);
        // 获取第三方用户信息(微信)
        $oauth = UserService::getOauth($userInfo['user_id'], 'MP-WEIXIN');
        return $WxPay->unifiedVip($orderNo, $oauth['oauth_id'], $plan['money']*100, 10);
    }


    /**
     * 获取微信支付配置
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private static function getWxConfig(): array
    {
        $storeId = getStoreId();
        return WxappSettingModel::getWxappConfig($storeId);
    }

    /**
     * 待支付订单详情
     * @param string $orderNo 订单号
     * @return null|static
     */
    public static function getPayDetail(string $orderNo): ?VipLog
    {
        return self::detail(['order_no' => $orderNo, 'pay_status' => 10, 'is_delete' => 0], ['user']);
    }
}
