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

namespace app\index\controller;

use app\api\model\QrStyle;
use app\api\model\user\Play;
use cores\BaseController;
use dh2y\qrcode\QRcode;
use app\api\service\User as UserService;
use think\Exception;

/**
 * 帮助中心
 * Class help
 */
class Help extends BaseController
{
    /**
     * 获取列表记录
     */
    public function list($id)
    {
        print_r($id);die();
    }

    /**
     * @param $i 二维码的宽高
     * 生成二维码
     */
    public function qr($i){
        $code = new QRcode();
        $j = ($i/37*100)/100 + 0.01;
        $res = $code->png('https://www.baidu.com/',false, $j)->entry();
        print_r($res);
    }

    /**
     * 生成合并图片
     */
    public function pic(){
        header("Content-Type: image/jpeg");
        ob_clean();
        //原始图像
        $dst = "http://wechat-small-app.oss-cn-hangzhou.aliyuncs.com/10001/20221026/cf89f4a2604f434a35fb0684bb4e788c.jpg";
//得到原始图片信息
        $dst_im = imagecreatefromjpeg($dst);
        $dst_info = getimagesize($dst);
//水印图像
        $url =
        $src = "http://wechat-small-app.oss-cn-hangzhou.aliyuncs.com/10001/20221023/07f21c8b8ac1ec44b28167d1da3fbf54.png";
        $src_im = imagecreatefrompng($src);
        $src_info = getimagesize($src);
//水印透明度
        $alpha = 50;
//合并水印图片
        imagecopymerge($dst_im,$src_im,280,0,0,0,$src_info[0], $src_info[1],$alpha);
//输出合并后水印图片
        imagejpeg($dst_im);

        imagedestroy($dst_im);
        imagedestroy($src_im);
        exit;
    }

    /**
     * 生成合并图片
     */
    /**
     * @param int $qrId //选择的二维码图片id
     * @param int $id //用户创建的模板i内容d
     * @throws \cores\exception\BaseException
     */
    public function picQr(int $qrId,int $id){

        header("Content-Type: image/jpeg");
        ob_clean();

        $qrInfo = QrStyle::detail($qrId,['image']);
        $play = Play::detail($id);
        $link = $play['link'];

        $code = new QRcode();
        $j = ($qrInfo['width']/37*100)/100 + 0.01;
        $qrImage = $code->png($link,false, $j)->entry();

        //原始图像
        $dst = $qrInfo['image_url'];
//得到原始图片信息
        try{
            $dst_im = imagecreatefromjpeg($dst);
        }catch (Exception $exception){
            $dst_im = imagecreatefrompng($dst);
        }

        $dst_info = getimagesize($dst);
//水印图像

        $src = $qrImage;
        $src_im = imagecreatefrompng($src);
        $src_info = getimagesize($src);
//水印透明度
        $alpha = 100;
//合并水印图片
        imagecopymerge($dst_im,$src_im,$qrInfo['x_value'],$qrInfo['y_value'],0,0,$src_info[0], $src_info[1],$alpha);
//输出合并后水印图片
        imagejpeg($dst_im);

        imagedestroy($dst_im);
        imagedestroy($src_im);
        exit;
    }
}
