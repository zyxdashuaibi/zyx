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

namespace app\api\controller;

use app\api\model\Store;
use app\api\model\User as UserModel;
use app\api\model\user\Play;
use app\api\model\user\Vip;
use app\api\model\user\VipLog;
use app\common\exception\BaseException;
use app\api\model\UserCoupon as UserCouponModel;
use app\api\service\User as UserService;
use think\response\Json;
use app\api\service\passport\Login as LoginService;

/**
 * 用户管理
 * Class User
 * @package app\api
 */
class User extends Controller
{
    /**
     * 当前用户详情
     * @return Json
     * @throws BaseException
     */
    public function info(): Json
    {
        // 当前用户信息
        $userInfo = UserService::getCurrentLoginUser(true);
        // 获取用户头像
        $userInfo['avatar'];
        // 获取会员VIP信息
        $userInfo['vip'];
        $store = Store::detail($this->getStoreId());
        $userInfo['setting'] = ['ckjc'=>$store['ckjc_link'],
            'wyly'=>$store['wyly_link'],
            'kfwx'=>$store['kfwx'],
            'nick_name'=>env('default.nickname','表白派'),
            'avatar_url'=>env('default.avatarurl','http://wechat-small-app.oss-cn-hangzhou.aliyuncs.com/10001/20221114/72f4fd3da1ba8e790c8c3fd60c7ab8ea.jpg')
        ];
        return $this->renderSuccess(compact('userInfo'));
    }

    /**
     * 账户资产
     * @return Json
     * @throws BaseException
     */
    public function assets(): Json
    {
        // 当前用户信息
        $userInfo = UserService::getCurrentLoginUser(true);
        // 用户优惠券模型
        $model = new UserCouponModel;
        // 返回数据
        return $this->renderSuccess([
            'assets' => [
                'balance' => $userInfo['balance'],  // 账户余额
                'points' => $userInfo['points'],    // 会员积分
                'coupon' => $model->getCount($userInfo['user_id']),    // 优惠券数量(可用)
            ]
        ]);
    }

    /**
     * 短信手机号绑定
     * @return Json
     * @throws \cores\exception\BaseException
     */
    public function bindMobile(): Json
    {
        $model = new UserModel;
        if (!$model->bindMobile($this->postForm())) {
            return $this->renderSuccess($model->getError() ?: '操作失败');
        }
        return $this->renderSuccess('恭喜您，手机号绑定成功');
    }

    /**
     * 微信小程序快捷登录 (需提交wx.login接口返回的code、微信用户公开信息)
     * 业务流程：判断openid是否存在 -> 存在:  更新用户登录信息 -> 返回userId和token
     *                          -> 不存在: 返回false, 跳转到注册页面
     * @return array|\think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function loginMpWx()
    {
        // 微信小程序一键登录
        $LoginService = new LoginService;
        if (!$LoginService->loginMpWx($this->postForm())) {
            return $this->renderError($LoginService->getError());
        }
        // 获取登录成功后的用户信息
        $userInfo = $LoginService->getUserInfo();
        return $this->renderSuccess([
            'userId' => (int)$userInfo['user_id'],
            'token' => $LoginService->getToken((int)$userInfo['user_id'])
        ], '登录成功');
    }

    /**
     * 快捷登录: 微信小程序授权手机号绑定
     * @return array|\think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function mobile()
    {
        // 微信小程序一键登录
        $LoginService = new LoginService;
        if (!$LoginService->loginMpWxMobile($this->postForm())) {
            return $this->renderError($LoginService->getError());
        }
        // 获取登录成功后的用户信息
        $userInfo = $LoginService->getUserInfo();
        return $this->renderSuccess([
            'userId' => (int)$userInfo['user_id'],
            'token' => $LoginService->getToken((int)$userInfo['user_id'])
        ], '添加手机成功');
    }

    /**
     * 我的制作列表
     * @return Json
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DbException
     */
    public function playList(){
        // 当前用户信息
        $userId = UserService::getCurrentLoginUserId();
        $play = new Play();
        $list = $play->getList(['userId'=>$userId]);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * @return Json
     * 修改用户昵称和头像
     */
    public function userSetting(){
        $user = new \app\api\model\User();
        if(!$user->userSetting($this->postForm())){
            return $this->renderError($user->getError());
        }
        return $this->renderSuccess([], '修改成功');
    }

    public function uploadPic(){
        // 当前用户信息
        $userInfo = UserService::getCurrentLoginUser(true);
        // 获取会员VIP信息
        $userInfo['vip'];
    }

    /**
     * @param int $planId
     * @return Json
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function buyVip(int $planId){
        // 当前用户信息
        $userInfo = UserService::getCurrentLoginUser(true);
        if($userInfo['vip_id'] > 0){
            return $this->renderError('你当前是VIP状态，失效后才可购买');
        }
        $model = new VipLog();
        $result = $model->buyVip($userInfo,$planId);
        if(!$result){
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess($result, '下单成功');
    }
}
