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

namespace app\common\service\message\passport;

use app\common\service\message\Basics;
use app\common\enum\setting\sms\Scene as SettingSmsScene;

/**
 * 消息通知服务 [短信验证码]
 * Class Captcha
 * @package app\common\service\message\passport
 */
class Captcha extends Basics
{
    /**
     * 发送消息通知
     * @param array $param
     * @return bool|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function send(array $param)
    {
        // 发送短信
        if (!$this->sendSms(SettingSmsScene::CAPTCHA, $param['mobile'], ['code' => $param['code']])) {
            throwError('短信发送失败：' . $this->getError());
        }
        return true;
    }
}