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

namespace app\common\library\express;

use think\facade\Cache;
use cores\traits\ErrorTrait;
use app\common\library\helper;

/**
 * 快递100API模块
 * Class Kuaidi100
 * @package app\common\library\express
 */
class Kuaidi100
{
    use ErrorTrait;

    // 物流跟踪查询API地址
    const QUERY_URL = 'http://poll.kuaidi100.com/poll/query.do';

    // 微信支付配置
    /* @var array $config */
    private $config;

    /**
     * 构造方法
     * WxPay constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 执行查询
     * @param string $code
     * @param string $expressNo
     * @return string|bool
     */
    public function query(string $code, string $expressNo)
    {
        // 缓存索引
        $cacheIndex = "express_{$code}_$expressNo";
        if ($cacheData = Cache::get($cacheIndex)) {
            return $cacheData;
        }
        // 参数设置
        $postData = [
            'customer' => $this->config['customer'],
            'param' => helper::jsonEncode([
                'resultv2' => '1',
                'com' => $code,
                'num' => $expressNo
            ])
        ];
        $postData['sign'] = strtoupper(md5($postData['param'] . $this->config['key'] . $postData['customer']));
        // 请求快递100 api
        $result = $this->curlGet(self::QUERY_URL, $postData);
        $express = helper::jsonDecode($result);
        // 记录错误信息
        if (isset($express['returnCode']) || !isset($express['data'])) {
            $this->error = $express['message'] ?? '查询失败';
            return false;
        }
        // 记录缓存, 时效30分钟
        Cache::set($cacheIndex, $express['data'], 3000);
        return $express['data'];
    }

    /**
     * curl请求指定url (post)
     * @param $url
     * @param array $data
     * @return bool|string
     */
    private function curlGet($url, array $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
