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

namespace app\common\model\wxapp;

use cores\BaseModel;
use think\facade\Cache;
use app\common\model\Wxapp as WxappModel;
use app\common\library\helper;

/**
 * 微信小程序设置模型
 * Class Setting
 * @package app\common\model\wxapp
 */
class Setting extends BaseModel
{
    // 定义表名
    protected $name = 'wxapp_setting';

    protected $createTime = false;

    /**
     * 获取器: 转义数组格式
     * @param $value
     * @return array
     */
    public function getValuesAttr($value): array
    {
        return helper::jsonDecode($value);
    }

    /**
     * 修改器: 转义成json格式
     * @param $value
     * @return string
     */
    public function setValuesAttr($value): string
    {
        return helper::jsonEncode($value);
    }

    /**
     * 获取指定项设置
     * @param string $key
     * @param int|null $storeId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getItem(string $key, int $storeId = null): array
    {
        $data = static::getAll($storeId);
        return isset($data[$key]) ? $data[$key]['values'] : [];
    }

    /**
     * 获取微信小程序基础配置
     * @param int|null $storeId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getWxappConfig(?int $storeId = null): array
    {
        return static::getItem('basic', $storeId);
    }

    /**
     * 获取全部设置
     * @param int|null $storeId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAll(?int $storeId = null): array
    {
        $model = new static;
        is_null($storeId) && $storeId = static::$storeId;
        if (!$data = Cache::get("wxapp_setting_{$storeId}")) {
            // 获取全部设置
            $data = $model->getList($storeId);
            // 写入缓存中
            Cache::tag('cache')->set("wxapp_setting_{$storeId}", $data);
        }
        // 合并默认设置
        return array_merge_multiple($model->defaultData(), $data);
    }

    /**
     * 获取商城设置列表
     * @param int $storeId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getList(int $storeId): array
    {
        // 获取所有设置项
        $data = $this->where('store_id', '=', $storeId)->select();
        $setting = $data->isEmpty() ? [] : helper::arrayColumn2Key($data->toArray(), 'key');
        // 兼容旧数据
        return static::compatibleOld($setting, $storeId);
    }

    /**
     * 兼容老版本数据 (v2.0.4之前)
     * @param array $newSetting
     * @param int|null $storeId
     * @return array
     */
    private static function compatibleOld(array $newSetting, ?int $storeId = null): array
    {
        if (empty($newSetting) || !isset($newSetting['basic'])) {
            $basic = WxappModel::getOldData($storeId);
            $newSetting['basic']['values'] = $basic;
            (new static)->save([
                'key' => 'basic',
                'describe' => '基础设置',
                'values' => $basic,
                'store_id' => $storeId,
                'update_time' => time(),
            ]);
        }
        return $newSetting;
    }

    /**
     * 获取设置项信息
     * @param string $key
     * @return array|\think\Model|null
     */
    public static function detail(string $key)
    {
        return static::get(compact('key'));
    }

    /**
     * 默认配置
     * @return array
     */
    public function defaultData(): array
    {
        return [
            'basic' => [
                'key' => 'basic',
                'describe' => '基础设置',
                'values' => [
                    // 小程序AppID
                    'app_id' => '',
                    // 小程序AppSecret
                    'app_secret' => '',
                    // 微信支付商户号ID
                    'mchid' => '',
                    // 微信支付密钥
                    'apikey' => '',
                    // 证书文件cert
                    'cert_pem' => '',
                    // 证书文件key
                    'key_pem' => '',
                ]
            ]
        ];
    }
}