<?php

namespace app\api\controller;

use app\api\model\Muban;
use app\api\model\muban\CategoryName;
use app\api\model\Music;
use app\api\model\Pic;
use app\api\model\QrStyle;
use app\api\model\user\Play;
use app\api\model\user\PlayPreview;
use app\common\model\Blessing;
use app\api\service\User as UserService;
use app\api\model\Setting as SettingModel;
use app\common\enum\Setting as SettingEnum;
use app\api\model\recharge\Plan as PlanModel;
use app\common\library\wechat\WxPay;
use app\api\model\wxapp\Setting as WxappSettingModel;


/**
 * 默认控制器
 * Class Index
 * @package app\api\controller
 */
class Index extends Controller
{
    public function index()
    {
        echo '当前访问的index.php，请将index.html设为默认站点入口';
    }

    /**
     * 模板分类
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function mubanCategory(){
        $model = new CategoryName();
        $list = $model->getShowList()->toArray();
        $res = [['category_name_id'=>0,'name'=>'全部','jump_url'=>null]];
        $list = array_merge($res,$list);
        return $this->renderSuccess($list);
    }

    /**
     * 获取模板列表
     * @param int $categoryNameId 模板分类
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function muban(int $categoryNameId = 0){
        $model = new Muban();
        $list = $model->getList($categoryNameId);
        $data = [
            'list' =>$list,
            'share'=>[
                'title'=>'可自由定制的表白代码来啦♪(^∇^*)，一定要学废哦',
                'imageUrl'=>'http://wechat-small-app.oss-cn-hangzhou.aliyuncs.com/10001/20221028/b18201cbf9be1b786f143b6d2f7ee504.jpg'
            ]
        ];
        return $this->renderSuccess($data);
    }

    /**
     * 获取二维码图片列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function qrPic(){
        $model = new QrStyle();
        $list = $model->getList()->toArray();
        return $this->renderSuccess($list);
    }

    /**
     * 音乐分类
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function musicCategory(){
        $model = new \app\api\model\music\Category();
        $list = $model->getList([['status','=',1]])->toArray();
        $res = [['category_id'=>0,'name'=>'全部']];
        $list = array_merge($res,$list);
        return $this->renderSuccess($list);
    }

    /**
     * 音乐列表
     * @param int $categoryId 音乐分类
     * @param string $title 音乐名称 搜索
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function music(int $categoryId = 0,$title=''){
        $model = new Music();
        $list = $model->getList(['categoryId'=>$categoryId,'status'=>1,'title'=>$title]);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 获取祝福语列表
     */
    public function blessing(){
        $model = new Blessing();
        $list = $model->getList()->toArray();
        return $this->renderSuccess($list);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 获取背景图分类
     */
    public function bigPicCategory(){
        $model = new \app\common\model\pic\Category();
        $list = $model->getList([['status','=',1],['type','=',2]])->toArray();
        $res = [['category_id'=>0,'name'=>'我的']];
        $list = array_merge($res,$list);
        return $this->renderSuccess($list);
    }
    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 获取背景图分类
     */
    public function smallPicCategory(){
        $model = new \app\common\model\pic\Category();
        $list = $model->getList([['status','=',1],['type','=',1]])->toArray();
        $res = [['category_id'=>0,'name'=>'我的']];
        $list = array_merge($res,$list);
        return $this->renderSuccess($list);
    }

    /**
     * @param int $categoryId
     * @return \think\response\Json
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DbException
     */
    public function pic(int $categoryId = 0){
        // 当前用户信息
        $userId = UserService::getCurrentLoginUserId();;
        $model = new Pic();
        $list = $model->getList(['categoryId'=>$categoryId,'status'=>1,'userId'=>$userId]);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * @return \think\response\Json
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 购买vip页显示内容
     */
    public function vipSetting(){
        // 当前用户信息
        UserService::getCurrentLoginUserId();
        $recharge = new PlanModel();
        $result['recharge']=$recharge->getList();
        $result['setting'] = SettingModel::getItem(SettingEnum::RECHARGE);
        return $this->renderSuccess($result);
    }

    /**
     * @return \think\response\Json
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 用户制作模板
     */
    public function addPlay(){
        $data = json_decode(file_get_contents("php://input"),true);
        $model = new Play();
        $result = $model->addPlay($data['form']);
        return $this->renderSuccess($result);
    }

    /**
     * @return \think\response\Json
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 用户制作模板预览
     */
    public function addPlayPreview(){
        $data = json_decode(file_get_contents("php://input"),true);
        $model = new PlayPreview();
        $result = $model->addPlayPreview($data['form']);
        return $this->renderSuccess($result);
    }

    /**
     * @return \think\response\Json
     * @throws \cores\exception\BaseException
     * 内部员工分享链接跳转设置
     */
    public function jumpSetting(){
        $model = new Play();
        $result = $model->jumpSetting($this->postForm());
        return $this->renderSuccess($result);
    }

    /**
     * @param int $start
     * @param int $end
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function msgSecCheck(int $start,int $end){
        //设置执行时间不限时
        set_time_limit(0);
        //清除并关闭缓冲，输出到浏览器之前使用这个函数。
        ob_end_clean();
        // 控制隐式缓冲泻出，默认off，打开时，对每个 print/echo 或者输出命令的结果都发送到浏览器。
        ob_implicit_flush(1);
        $play = new Play();
        $res = $play->where('id','>=',$start)
            ->where('id','<=',$end)
            ->field('data_json')
            ->select();
        if($res != null){
            foreach ($res as $key=>$item) {
                echo $item['id'].'---'.$item['data_json'].'</br>';
                $wxConfig = WxappSettingModel::getWxappConfig();
                $WxPay = new WxPay($wxConfig);
                $result = $WxPay->msgSecCheck('oW-835JKVNkfmQRG0ih0GPP20J2c',$item['data_json']);
                $result = json_decode($result,true);
                if($result['errcode'] != 0){
                    log_record([
                        'result' => json_encode($result)
                    ],'error');
                    echo'error -'.$item['id'].'--'. json_encode($result).'</br>';
                }else{
                    echo'result -'.$item['id'].'--label:'. $result['result']['label'].'--suggest:'.$result['result']['suggest'].'<br/>';
                    if($result['result']['label'] != 100 || $result['result']['suggest'] != 'pass'){
                        log_record([
                            'result' => json_encode($result)
                        ],'warning');
                        echo'warning -'.$item['id'].'--'. json_encode($result).'</br>';
                    }
                }
                echo $item['id'].'-----success';
            }
        }
        echo 'success';
    }


    /**
     * @param int $start
     * @param int $end
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function msgSecCheckPlayPreview(int $start,int $end){
        header( 'Content-Type: text/event-stream' );
        header( 'Cache-Control: no-cache' );
        header( 'X-Accel-Buffering: no' );
        //设置执行时间不限时
        set_time_limit(0);
        //清除并关闭缓冲，输出到浏览器之前使用这个函数。
        ob_end_clean();
        // 控制隐式缓冲泻出，默认off，打开时，对每个 print/echo 或者输出命令的结果都发送到浏览器。
        ob_implicit_flush(1);
        echo '开始时间：'.date("Y-m-d H:i:s");
        echo PHP_EOL;
        $play = new PlayPreview();
        $res = $play->where('id','>=',$start)
            ->where('id','<=',$end)
            ->select();
        if($res != null){
            foreach ($res as $key=>$item) {
                $wxConfig = WxappSettingModel::getWxappConfig();
                $WxPay = new WxPay($wxConfig);
                $result = $WxPay->msgSecCheck('oW-835AtxgbEIqe_P2z9zuwbQ86A',$item['data_json']);
                $result = json_decode($result,true);

                if($result['errcode'] != 0){
                    log_record([
                        'result' => json_encode($result)
                    ],'wxerror');
                    echo'error -'.$item['id'].'--'. json_encode($result).'<br/>';
                }else{
                    echo'result -'.$item['id'].'--label:'. $result['result']['label'].'--suggest:'.$result['result']['suggest'];
                    //  echo PHP_EOL;
                    if($result['result']['label'] != 100 || $result['result']['suggest'] != 'pass'){
                        log_record([
                            'result' => json_encode($result)
                        ],'warning');
                        echo'warning -'.$item['id'].'--'. json_encode($result);
                        echo PHP_EOL;
                    }
                }
                echo '---检测完成---success ';
                echo PHP_EOL;
            }
        }
        echo '全部检测完成';
        echo PHP_EOL;
        echo '结束时间：'.date("Y-m-d H:i:s");
    }

}
