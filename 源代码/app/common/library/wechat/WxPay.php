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
namespace app\common\library\wechat;

use app\common\model\wxapp\Setting as WxappSettingModel;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use app\common\library\helper;
use app\common\exception\BaseException;

/**
 * 微信支付
 * Class WxPay
 * @package app\common\library\wechat
 */
class WxPay extends WxBase
{
    // 微信支付配置
    private $config;

    // 当前商城ID
    private $storeId;

    // 订单模型
    private $modelClass = [
        OrderTypeEnum::ORDER => \app\api\service\order\PaySuccess::class,
        OrderTypeEnum::RECHARGE => \app\api\service\recharge\PaySuccess::class,
    ];

    /**
     * 构造函数
     * @param array $config
     * @param int|null $storeId
     */
    public function __construct(array $config = [], ?int $storeId = null)
    {
        parent::__construct();
        $this->config = $config;
        $this->storeId = $storeId;
        if (!empty($this->config)) {
            $this->setConfig($this->config['app_id'], $this->config['app_secret']);
        }
    }

    /**
     * 检查一段文本是否含有违法违规内容
     * @param $openid
     * @param $content
     * @return bool|string
     * @throws \cores\exception\BaseException
     */
    public function msgSecCheck($openid,$content){
        $accessToken = $this->getAccessToken();
        $posturl="https://api.weixin.qq.com/wxa/msg_sec_check?access_token=".$accessToken;
        $postdata = json_encode([
            'version'=>2, //接口版本号，2.0版本为固定值2
            'openid' =>$openid, //用户的openid（用户需在近两小时访问过小程序）
            'scene'=>4, //场景枚举值（1 资料；2 评论；3 论坛；4 社交日志）
            'content' => $content //需检测的文本内容，文本字数的上限为2500字，需使用UTF-8编码
        ],JSON_UNESCAPED_UNICODE);
        //提交
        $data= $this->post($posturl, $postdata);
        return $data;
    }

    /**
     * @param $openid
     * @param $url
     * @param $media_type
     * @return bool|mixed|string
     * @throws \cores\exception\BaseException
     * 验证图片和视频
     */
    public function mediaCheckAsync($openid,$url,$media_type){
        $accessToken = $this->getAccessToken();
        $posturl="https://api.weixin.qq.com/wxa/media_check_async?access_token=".$accessToken;
        $postdata = json_encode([
            'media_url'=>$url, //要检测的图片或音频的url，支持图片格式包括 jpg , jepg, png, bmp, gif（取首帧），支持的音频格式包括mp3, aac, ac3, wma, flac, vorbis, opus, wav
            'media_type'=>$media_type, //1:音频;2:图片
            'version'=>2, //接口版本号，2.0版本为固定值2
            'openid' =>$openid, //用户的openid（用户需在近两小时访问过小程序）
            'scene'=>4, //场景枚举值（1 资料；2 评论；3 论坛；4 社交日志）
        ],JSON_UNESCAPED_UNICODE);
        //提交
        $data= $this->post($posturl, $postdata);
        return $data;
    }

    /**
     * 统一下单API
     * @param $orderNo
     * @param $openid
     * @param $totalFee
     * @param int $orderType 订单类型
     * @return array
     * @throws \cores\exception\BaseException
     */
    public function unifiedorder($orderNo, $openid, $totalFee, int $orderType = OrderTypeEnum::ORDER): array
    {
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time . $openid);
        // API参数
        $params = [
            'appid' => $this->appId,
            'attach' => helper::jsonEncode(['order_type' => $orderType]),
            'body' => $orderNo,
            'mch_id' => $this->config['mchid'],
            'nonce_str' => $nonceStr,
            'notify_url' => base_url() . 'notice.php',  // 异步通知地址
            'openid' => $openid,
            'out_trade_no' => $orderNo,
            'spbill_create_ip' => \request()->ip(),
            'total_fee' => (int)helper::bcmul($totalFee, 100), // 价格:单位分
            'trade_type' => 'JSAPI',
        ];
        // 生成签名
        $params['sign'] = $this->makeSign($params);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $result = $this->post($url, $this->toXml($params));
        $prepay = $this->fromXml($result);
        // 请求失败
        if ($prepay['return_code'] === 'FAIL') {
            $errMsg = "微信支付api：{$prepay['return_msg']}";
            throwError($errMsg, null, ['errorCode' => 'WECHAT_PAY', 'isCreated' => true]);
        }
        if ($prepay['result_code'] === 'FAIL') {
            $errMsg = "微信支付api：{$prepay['err_code_des']}";
            throwError($errMsg, null, ['errorCode' => 'WECHAT_PAY', 'isCreated' => true]);
        }
        // 生成 nonce_str 供前端使用
        $paySign = $this->makePaySign($params['nonce_str'], $prepay['prepay_id'], $time);
        return [
            'prepay_id' => $prepay['prepay_id'],
            'nonceStr' => $nonceStr,
            'timeStamp' => (string)$time,
            'paySign' => $paySign
        ];
    }

    /**
     * vip统一下单API
     * @param $orderNo
     * @param $openid
     * @param $totalFee
     * @param int $orderType 订单类型
     * @return array
     * @throws \cores\exception\BaseException
     */
    public function unifiedVip($orderNo, $openid, $totalFee, int $orderType = OrderTypeEnum::ORDER): array
    {
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time . $openid);
        // API参数
        $params = [
            'appid' => $this->appId,
            'attach' => helper::jsonEncode(['order_type' => $orderType]),
            'body' => 'VIP充值',
            'mch_id' => $this->config['mchid'],
            'nonce_str' => $nonceStr,
            'notify_url' => base_url() . 'notice.php',  // 异步通知地址
            'openid' => $openid,
            'out_trade_no' => $orderNo,
            'spbill_create_ip' => \request()->ip(),
            'total_fee' => $totalFee, // 价格:单位分
            'trade_type' => 'JSAPI'
        ];
        // 生成签名
        $params['sign'] = $this->makeSign($params);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $result = $this->post($url, $this->toXml($params));
        $prepay = $this->fromXml($result);
        // 请求失败
        if ($prepay['return_code'] === 'FAIL') {
            $errMsg = "微信支付api：{$prepay['return_msg']}";
            throwError($errMsg, null, ['errorCode' => 'WECHAT_PAY', 'isCreated' => true]);
        }
        if ($prepay['result_code'] === 'FAIL') {
            $errMsg = "微信支付api：{$prepay['err_code_des']}";
            throwError($errMsg, null, ['errorCode' => 'WECHAT_PAY', 'isCreated' => true]);
        }
        // 生成 nonce_str 供前端使用
        $paySign = $this->makePaySign($params['nonce_str'], $prepay['prepay_id'], $time);
        return [
            'prepay_id' => $prepay['prepay_id'],
            'nonceStr' => $nonceStr,
            'timeStamp' => (string)$time,
            'paySign' => $paySign
        ];
    }

    /**
     * 支付成功异步通知
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function notify()
    {
        if (!$xml = file_get_contents('php://input')) {
            $this->returnCode(false, 'Not found DATA');
        }
        // 将服务器返回的XML数据转化为数组
        $data = $this->fromXml($xml);
        // 记录日志
        log_record($xml);
        log_record($data);
        // 实例化订单模型
        $model = $this->getOrderModel($data['out_trade_no'], $data['attach']);
        // 订单信息
        $order = $model->getOrderInfo();
        empty($order) && $this->returnCode(false, '订单不存在');
        // 小程序配置信息
        $wxConfig = WxappSettingModel::getWxappConfig($order['store_id']);
        // 设置支付秘钥
        $this->config['apikey'] = $wxConfig['apikey'];
        // 保存微信服务器返回的签名sign
        $dataSign = $data['sign'];
        // sign不参与签名算法
        unset($data['sign']);
        // 生成签名
        $sign = $this->makeSign($data);
        // 判断签名是否正确 判断支付状态
        if (
            ($sign !== $dataSign)
            || ($data['return_code'] !== 'SUCCESS')
            || ($data['result_code'] !== 'SUCCESS')
        ) {
            $this->returnCode(false, '签名失败');
        }
        // 订单支付成功业务处理
        $status = $model->onPaySuccess(OrderPayTypeEnum::WECHAT, $data);
        if ($status == false) {
            $this->returnCode(false, $model->getError());
        }
        // 返回状态
        $this->returnCode(true, 'OK');
    }

    /**
     * 申请退款API
     * @param string $transactionId 微信支付交易流水号
     * @param string $totalFee 订单总金额
     * @param string $refundFee 退款金额
     * @return bool
     * @throws BaseException
     * @throws \cores\exception\BaseException
     */
    public function refund(string $transactionId, string $totalFee, string $refundFee): bool
    {
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time . $transactionId . $totalFee . $refundFee);
        // API参数
        $params = [
            'appid' => $this->appId,
            'mch_id' => $this->config['mchid'],
            'nonce_str' => $nonceStr,
            'transaction_id' => $transactionId,
            'out_refund_no' => $time,
            'total_fee' => (int)helper::bcmul($totalFee,100),
            'refund_fee' => (int)helper::bcmul($refundFee,100),
        ];
        // 生成签名
        $params['sign'] = $this->makeSign($params);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $result = $this->post($url, $this->toXml($params), true, $this->getCertPem());
        // 请求失败
        if (empty($result)) {
            throwError('微信退款api请求失败');
        }
        // 格式化返回结果
        $prepay = $this->fromXml($result);
        // 记录日志
        log_record(['name' => '微信退款API', [
            'params' => $params,
            'result' => $result,
            'prepay' => $prepay
        ]]);
        // 请求失败
        if ($prepay['return_code'] === 'FAIL') {
            throwError("return_msg: {$prepay['return_msg']}");
        }
        if ($prepay['result_code'] === 'FAIL') {
            throwError("err_code_des: {$prepay['err_code_des']}");
        }
        return true;
    }

    /**
     * 获取cert证书文件
     * @return string[]
     * @throws \cores\exception\BaseException
     */
    private function getCertPem(): array
    {
        if (empty($this->config['cert_pem']) || empty($this->config['key_pem'])) {
            throwError('请先到后台小程序设置填写微信支付证书文件');
        }
        // cert目录
        $filePath = __DIR__ . "/cert/{$this->storeId}/";
        return [
            'certPem' => $filePath . 'cert.pem',
            'keyPem' => $filePath . 'key.pem'
        ];
    }

    /**
     * 实例化订单模型 (根据attach判断)
     * @param $orderNo
     * @param null $attach
     * @return mixed
     */
    private function getOrderModel($orderNo, $attach = null)
    {
        $attach = helper::jsonDecode($attach);
        // 判断订单类型返回对应的订单模型
        $model = $this->modelClass[$attach['order_type']];
        return new $model($orderNo);
    }

    /**
     * 返回状态给微信服务器
     * @param boolean $returnCode
     * @param string|null $msg
     */
    private function returnCode(bool $returnCode = true, string $msg = null)
    {
        // 返回状态
        $return = [
            'return_code' => $returnCode ? 'SUCCESS' : 'FAIL',
            'return_msg' => $msg ?: 'OK',
        ];
        // 记录日志
        log_record([
            'name' => '返回微信支付状态',
            'data' => $return
        ]);
        die($this->toXml($return));
    }

    /**
     * 生成paySign
     * @param string $nonceStr
     * @param string $prepayId
     * @param int $timeStamp
     * @return string
     */
    private function makePaySign(string $nonceStr, string $prepayId, int $timeStamp): string
    {
        $data = [
            'appId' => $this->appId,
            'nonceStr' => $nonceStr,
            'package' => 'prepay_id=' . $prepayId,
            'signType' => 'MD5',
            'timeStamp' => $timeStamp,
        ];
        // 签名步骤一：按字典序排序参数
        ksort($data);
        $string = $this->toUrlParams($data);
        // 签名步骤二：在string后加入KEY
        $string = $string . '&key=' . $this->config['apikey'];
        // 签名步骤三：MD5加密
        $string = md5($string);
        // 签名步骤四：所有字符转为大写
        return strtoupper($string);
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @return mixed
     */
    private function fromXml(string $xml)
    {
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return helper::jsonDecode(helper::jsonEncode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)));
    }

    /**
     * 生成签名
     * @param array $values
     * @return string 本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    private function makeSign(array $values): string
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = $this->toUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' . $this->config['apikey'];
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        return strtoupper($string);
    }

    /**
     * 格式化参数格式化成url参数
     * @param array $values
     * @return string
     */
    private function toUrlParams(array $values): string
    {
        $buff = '';
        foreach ($values as $k => $v) {
            if ($k != 'sign' && $v != '' && !is_array($v)) {
                $buff .= $k . '=' . $v . '&';
            }
        }
        return trim($buff, '&');
    }

    /**
     * 输出xml字符
     * @param array $values
     * @return bool|string
     */
    private function toXml(array $values)
    {
        if (!is_array($values)
            || count($values) <= 0
        ) {
            return false;
        }
        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}
