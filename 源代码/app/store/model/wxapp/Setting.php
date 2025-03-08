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

namespace app\store\model\wxapp;

use think\facade\Cache;
use app\common\model\wxapp\Setting as SettingModel;

/**
 * 微信小程序设置模型
 * Class Setting
 * @package app\store\model\wxapp
 */
class Setting extends SettingModel
{
    /**
     * 设置项描述
     * @var array
     */
    private $describe = [
        'basic' => '基础设置',
    ];

    /**
     * 更新系统设置
     * @param string $key
     * @param array $values
     * @return bool
     */
    public function edit(string $key, array $values): bool
    {
        $model = self::detail($key) ?: $this;
        // 删除小程序设置缓存
        Cache::delete('wxapp_setting_' . self::$storeId);
        // 写入cert证书文件
        $this->writeCertPemFiles($values['cert_pem'], $values['key_pem']);
        // 保存设置
        return $model->save([
                'key' => $key,
                'describe' => $this->describe[$key],
                'values' => $values,
                'update_time' => time(),
                'store_id' => self::$storeId,
            ]) !== false;
    }

    /**
     * 写入cert证书文件
     * @param string $certPem
     * @param string $keyPem
     * @return void
     */
    private function writeCertPemFiles(string $certPem, string $keyPem): void
    {
        if (empty($certPem) && empty($keyPem)) {
            return;
        }
        // 证书目录
        $filePath = base_path() . 'common/library/wechat/cert/' . self::$storeId . '/';
        // 目录不存在则自动创建
        if (!is_dir($filePath)) {
            mkdir($filePath, 0755, true);
        }
        // 写入cert.pem文件
        if (!empty($certPem)) {
            file_put_contents($filePath . 'cert.pem', $certPem);
        }
        // 写入key.pem文件
        if (!empty($keyPem)) {
            file_put_contents($filePath . 'key.pem', $keyPem);
        }
    }
}