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

namespace app\api\controller;

use app\api\controller\points\Log;
use app\api\model\Pic;

/**
 * 文件库管理
 * Class WxResponse
 * @package app\api\controller
 */
class WxResponse extends Controller
{
    public $error = null;
    const TOKEN = "lovebbp123456789";

    public function checkServer(){ // 校验服务器地址URL
        if (isset($_GET['echostr'])) {
            $this->valid();
        } else {
            $this->responseMsg();
        }
    }

    public function valid(){
        $echoStr = $_GET["echostr"];

        if ($this->checkSignature()) {
            header('content-type:text');
            echo $echoStr;
            exit();
        } else {
            echo $echoStr . '+++' . self::TOKEN;
            log_record([
                '$echoStr' => $echoStr . '+++' . self::TOKEN
            ]);
            exit();
        }
    }

    private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = self::TOKEN;
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce
        );
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function responseMsg(){
        $postStr =  file_get_contents('php://input');
        if(empty($postStr)){
            return false;
        }

        log_record([
            'postStr' => $postStr
        ]);
        $postStr = json_decode($postStr,true);
        if(!empty($postStr['trace_id'])){
            if($postStr['result']['label'] != 100 || $postStr['result']['suggest'] != 'pass'){
                // 记录日志
                log_record([
                    'postStr' => json_encode($postStr)
                ],'error');
                $pic = new Pic();
                $info = $pic->where('trace_id','=',$postStr['trace_id'])->find();
                if($info){
                    $info->save(['is_delete'=>1,'update_time'=>time()]);
                }
            }
        }
        return true;
    }
}
