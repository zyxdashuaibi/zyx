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

use cores\BaseModel;

/**
 * 模型类：第三方用户信息
 * Class UserOauth
 * @package app\common\model
 */
class UserOauth extends BaseModel
{
    // 定义表名
    protected $name = 'bbp_user_oauth';

    // 定义主键
    protected $pk = 'id';

    /**
     * 根据openid获取用户ID
     * @param string $oauthId 第三方用户唯一标识 (openid)
     * @param string $oauthType 第三方登陆类型
     * @return mixed
     */
    public static function getUserIdByOauthId(string $oauthId, string $oauthType)
    {
        return (new static)->where('oauth_id', '=', $oauthId)
            ->where('oauth_type', '=', $oauthType)
            ->where('is_delete', '=', 0)
            ->value('user_id');
    }

    /**
     * 根据用户ID获取OpenID
     * @param int $userId 用户ID
     * @param string $oauthType 第三方登陆类型
     * @return mixed
     */
    public static function getOauthIdByUserId(int $userId, string $oauthType)
    {
        return (new static)->where('user_id', '=', $userId)
            ->where('oauth_type', '=', $oauthType)
            ->where('is_delete', '=', 0)
            ->value('oauth_id');
    }

    /**
     * 根据用户ID获取微信小程序OpenID
     * @param int $userId 用户ID
     * @return mixed
     */
    public static function getMpWeiXinOpenId(int $userId)
    {
        return static::getOauthIdByUserId($userId, 'MP-WEIXIN');
    }

    /**
     * 获取第三方用户信息
     * @param int $userId 用户ID
     * @param string $oauthType 第三方登陆类型
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getOauth(int $userId, string $oauthType)
    {
        return (new static)->where('user_id', '=', $userId)
            ->where('oauth_type', '=', $oauthType)
            ->find();
    }

}
