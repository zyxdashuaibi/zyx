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

namespace app\common\library\wechat;

use think\facade\Cache;
use cores\traits\ErrorTrait;
use app\common\library\helper;
use app\common\exception\BaseException;

/**
 * 微信api基类
 * Class wechat
 * @package app\library
 */
class WxBase
{
    use ErrorTrait;

    protected $appId;
    protected $appSecret;

    /**
     * 构造函数
     * WxBase constructor.
     * @param $appId
     * @param $appSecret
     */
    public function __construct($appId = null, $appSecret = null)
    {
        $this->setConfig($appId, $appSecret);
    }

    protected function setConfig($appId = null, $appSecret = null)
    {
        !empty($appId) && $this->appId = $appId;
        !empty($appSecret) && $this->appSecret = $appSecret;
    }

    /**
     * 获取access_token
     * @return mixed
     * @throws \cores\exception\BaseException
     */
    protected function getAccessToken()
    {
        $cacheKey = $this->appId . '@access_token';
        if (!Cache::get($cacheKey)) {
            // 请求API获取 access_token
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appId}&secret={$this->appSecret}";
            $result = $this->get($url);
            $response = $this->jsonDecode($result);
            if (array_key_exists('errcode', $response)) {
                throwError("access_token获取失败，错误信息：{$result}，URL地址：{$url}");
            }
            // 记录日志
            log_record([
                'name' => '获取access_token',
                'url' => $url,
                'appId' => $this->appId,
                'result' => $result
            ]);
            // 写入缓存
            Cache::set($cacheKey, $response['access_token'], 6000);
        }
        return Cache::get($cacheKey);
    }

    /**
     * 模拟GET请求 HTTPS的页面
     * @param string $url 请求地址
     * @param array $data
     * @return string $result
     * @throws \cores\exception\BaseException
     */
    protected function get(string $url, array $data = [])
    {
        // 处理query参数
        if (!empty($data)) {
            $url = $url . '?' . http_build_query($data);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        $result = curl_exec($curl);
        if ($result === false) {
            throwError(curl_error($curl));
        }
        curl_close($curl);
        return $result;
    }

    /**
     * 模拟POST请求
     * @param string $url 请求地址
     * @param mixed $data 请求数据
     * @param false $useCert 是否引入微信支付证书
     * @param array $sslCert 证书路径
     * @return mixed|bool|string
     * @throws \cores\exception\BaseException
     */
    protected function post(string $url, $data = [], bool $useCert = false, array $sslCert = [])
    {
        $header = [
            'Content-type: application/json;'
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        if ($useCert == true) {
            // 设置证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLCERT, $sslCert['certPem']);
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLKEY, $sslCert['keyPem']);
        }
        $result = curl_exec($curl);
        if ($result === false) {
            throwError(curl_error($curl));
        }
        curl_close($curl);
        return $result;
    }

    /**
     * 模拟POST请求 [第二种方式, 用于兼容微信api]
     * @param $url
     * @param array $data
     * @return mixed
     * @throws \cores\exception\BaseException
     */
    protected function post2($url, array $data = [])
    {
        $header = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
        $result = curl_exec($ch);
        if ($result === false) {
            throwError(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    /**
     * 数组转json
     * @param $data
     * @return string
     */
    protected function jsonEncode($data): string
    {
        return helper::jsonEncode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * json转数组
     * @param $json
     * @return mixed
     */
    protected function jsonDecode($json)
    {
        return helper::jsonDecode($json);
    }

    //post函数
    function request_by_curl($remote_server, $post_string) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "CURL Example beta");
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
