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

namespace app\common\service\order;

use app\common\exception\BaseException;
use app\common\model\User as UserModel;
use app\common\model\wxapp\Setting as WxappSettingModel;
use app\common\model\user\BalanceLog as BalanceLogModel;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use app\common\library\wechat\WxPay;
use app\common\service\BaseService;

/**
 * 订单退款服务类
 * Class Refund
 * @package app\common\service\order
 */
class Refund extends BaseService
{
    /**
     * 执行订单退款
     * @param $order
     * @param null $money
     * @return bool
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function execute($order, $money = null): bool
    {
        // 退款金额，如不指定则默认为订单实付款金额
        is_null($money) && $money = $order['pay_price'];
        // 1.微信支付退款
        if ($order['pay_type'] == OrderPayTypeEnum::WECHAT) {
            return $this->wxpay($order, (string)$money);
        }
        // 2.余额支付退款
        if ($order['pay_type'] == OrderPayTypeEnum::BALANCE) {
            return $this->balance($order, (string)$money);
        }
        return false;
    }

    /**
     * 余额支付退款
     * @param $order
     * @param $money
     * @return bool
     */
    private function balance($order, $money): bool
    {
        // 回退用户余额
        UserModel::setIncBalance((int)$order['user_id'], (float)$money);
        // 记录余额明细
        BalanceLogModel::add(SceneEnum::REFUND, [
            'user_id' => $order['user_id'],
            'money' => $money,
        ], ['order_no' => $order['order_no']]);
        return true;
    }

    /**
     * 微信支付退款
     * @param $order
     * @param string $money
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \cores\exception\BaseException
     */
    private function wxpay($order, string $money): bool
    {
        $wxConfig = WxappSettingModel::getWxappConfig($order['store_id']);
        $WxPay = new WxPay($wxConfig, $order['store_id']);
        return $WxPay->refund($order['transaction_id'], $order['pay_price'], $money);
    }
}