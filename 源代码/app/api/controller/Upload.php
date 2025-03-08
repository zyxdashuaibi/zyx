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

use app\api\model\Pic;
use app\api\model\Setting as SettingModel;
use app\api\model\UploadFile as UploadFileModel;
use app\api\model\user\Vip;
use app\api\service\User as UserService;
use app\common\enum\Setting as SettingEnum;
use app\common\enum\file\FileType as FileTypeEnum;
use app\common\library\storage\Driver as StorageDriver;
use app\common\exception\BaseException;
use app\common\library\wechat\WxPay;
use app\api\model\wxapp\Setting as WxappSettingModel;

/**
 * 文件库管理
 * Class Upload
 * @package app\api\controller
 */
class Upload extends Controller
{
    // 当前商城的上传设置
    private $config;

    /**
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function initialize()
    {
        parent::initialize();
        // 验证登录
        UserService::isLogin(true);
        // 存储配置信息
        $this->config = SettingModel::getItem(SettingEnum::STORAGE);
    }

    /**
     * 图片上传接口
     * @return array|\think\response\Json
     * @throws BaseException
     * @throws \think\Exception
     */
    public function image()
    {
        // 当前用户ID
        $userInfo = UserService::getCurrentLoginUser();
        $userId = $userInfo['user_id'];
        if(empty($userInfo['vip_id'])){
            return $this->renderError('没有上传权限');
        }
        // 实例化存储驱动
        $storage = new StorageDriver($this->config);
        // 设置上传文件的信息
        $storage->setUploadFile('file')
            ->setRootDirName((string)$this->getStoreId())
            ->setValidationScene('image');
        // 执行文件上传
        if (!$storage->upload()) {
            return $this->renderError('图片上传失败：' . $storage->getError());
        }
        // 文件信息
        $fileInfo = $storage->getSaveFileInfo();

        //调用微信异步校验图片/音频是否含有违法违规内容security.mediaCheckAsync
        $imageUrl = $fileInfo['domain'].'/'.$fileInfo['file_path'];

        $wxConfig = WxappSettingModel::getWxappConfig();
        $WxPay = new WxPay($wxConfig);
        $result = $WxPay->mediaCheckAsync($userInfo['currentOauth']['oauth_id'],$imageUrl,2);
        $result = json_decode($result,true);
        $trace_id = isset($result['trace_id'])?$result['trace_id']:'';
        // 添加文件库记录
        $model = new UploadFileModel;
        $image_id = $model->add($fileInfo, FileTypeEnum::IMAGE, $userId);
        //添加到用户上传的表中
        $pic = new Pic();
        $pic->add(['user_id'=>$userId,'image_id'=>$image_id,'title'=>'默认','trace_id'=>$trace_id]);
        $count= 0;
        //vip信息更新
        if($userInfo['vip_id'] > 0){
            $vipInfo = Vip::detail($userInfo['vip_id']);
            if($vipInfo['type'] == 1){ //类型（1单次套餐、2天数套餐、3永久套餐）
                $pic = new Pic();
                //获取购买vip后上传的图片数量
                $count = $pic->where('user_id','=',$userId)
                    ->where('create_time','>',$vipInfo['create_time'])
                    ->where('is_delete', '=', 0)
                    ->count();
//                $count = Pic::getArticleTotal([['user_id','=',$userId],['create_time','>',$vipInfo['create_time']]]);
                if($count >= $vipInfo['number']){
                    $vipInfo->delete();
                    $userInfo->save(['vip_id'=>0]);
                }
            }
            if($vipInfo['type'] == 2){ //类型（1单次套餐、2天数套餐、3永久套餐）
                $time = strtotime("+".$vipInfo['number']."days",$vipInfo['create_time']);
                if(time() >= $time){
                    $vipInfo->delete();
                    $userInfo->save(['vip_id'=>0]);
                }
            }
        }
        // 图片上传成功
        return $this->renderSuccess(['count'=>$count], '图片上传成功');
    }
}
