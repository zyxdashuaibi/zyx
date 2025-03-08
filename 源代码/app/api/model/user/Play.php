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

namespace app\api\model\user;

use app\common\model\user\Play as PlayModel;
use app\api\service\User as UserService;
use app\common\model\Words;
use app\common\library\wechat\WxPay;
use app\api\model\wxapp\Setting as WxappSettingModel;

/**
 * 用户VIP模型
 * Class Play
 * @package app\api\model\user
 */
class Play extends PlayModel
{
    /**
     * 获取模板数据列表
     * @param array $param
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList($param = [])
    {

        // 设置查询条件
        $filter = $this->getFilter($param);
        // 获取列表数据
        return $this->with(['muban.image'])
            ->field(['id','link','create_time','muban_id','is_jump','see_jump_num','jump_link','status'])
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->where('status', '=', 1)
            ->order(['create_time' => 'desc'])
            ->paginate(15);
    }

    /**
     * 设置查询条件
     * @param array $param
     * @return array
     */
    private function getFilter(array $param): array
    {
        // 设置默认的检索数据
        $params = $this->setQueryDefaultValue($param, [
            'userId' => 0,          // 会员ID
        ]);
        // 检索查询条件
        $filter = [];
        // 用户ID
        $params['userId'] > 0 && $filter[] = ['user_id', '=', $params['userId']];

        return $filter;
    }

    /**
     * @param array $form
     * @return array|bool|string
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 添加用户创建的模板
     */
    public function addPlay(array $form)
    {
        // 当前登录的用户信息
        $userInfo = UserService::getCurrentLoginUser(true);
//        $userInfo = ['user_id'=>10009,'domain'=>null];
        $data['user_id'] = $userInfo['user_id'];
        if(empty($userInfo['is_inside']) && empty($userInfo['vip_id'])){
            $play = new Play();
            $today = date('Y-m-d');
            $count = $play->where('user_id','=',$userInfo['user_id'])
                ->where('create_time', '>=', strtotime($today))
                ->where('create_time', '<', strtotime($today) + 86400)
                ->count();
            if($count > 15){
                throwError('普通用户每天生成的表白代码不成超过15个！');
            }
        }

        $data['title'] = 'test';
        $data['muban_id'] = $form['muban_id']; //模板
        $data['category_id'] = $form['category_id']; //模板id
        $dataJson = $form['data_json'];
        $json = [];
        foreach ($dataJson as $key=>$item) {
            if(is_array($item)){
                foreach ($item as $k => $ite) {
                    $ite = preg_replace('/\r|\n/','\n',$ite);
                    $json[$key][$k] = urlencode($ite);
                }
            }else{
                $item = preg_replace('/\r|\n/','\n',$item);
                $json[$key] = urlencode($item);
            }
        }
        $data['data_json'] = urldecode(json_encode($json)); //模板内容

        //调用微信的内容安全接口security.msgSecCheck
        $dataJson = json_decode($data['data_json'],true);
        $blessing = isset($dataJson['blessing'])?$dataJson['blessing']:'';
        $big_title = isset($dataJson['big_title'])?$dataJson['big_title']:'';
        $small_title = isset($dataJson['small_title'])?$dataJson['small_title']:'';
        $name = isset($dataJson['name'])?$dataJson['name']:'';
        $gb_title = isset($dataJson['gb_title'])?$dataJson['gb_title']:'';
        $yy_title = isset($dataJson['yy_title'])?$dataJson['yy_title']:'';
        $blessing_title = isset($dataJson['blessing_title'])?$dataJson['blessing_title']:'';
        $button_title = isset($dataJson['button_title'])?$dataJson['button_title']:'';
        $title = isset($dataJson['title'])?$dataJson['title']:'';
        $bottom = isset($dataJson['bottom'])?$dataJson['bottom']:'';
        $next_text = isset($dataJson['next_text'])?$dataJson['next_text']:'';
        $date = isset($dataJson['date'])?$dataJson['date']:'';
        $web_title = isset($dataJson['web_title'])?$dataJson['web_title']:'';
        $title2 = isset($dataJson['title2'])?implode($dataJson['title2']):'';
        $content =$blessing.$big_title.$small_title.$name.$gb_title.$yy_title.$blessing_title.$button_title.$title.$bottom.$next_text.$date.$web_title.$title2;
        $wxConfig = WxappSettingModel::getWxappConfig();
        $WxPay = new WxPay($wxConfig);
        $result = $WxPay->msgSecCheck($userInfo['currentOauth']['oauth_id'],$content);
        $result = json_decode($result,true);
        if($result['errcode'] != 0){
            throwError('调用微信内容检测错误，错误提示：'.$result['errmsg']);
        }else{
            if($result['result']['label'] != 100 || $result['result']['suggest'] != 'pass'){
                throwError('微信提示提交的有违法违规内容');
            }
        }
        //过滤敏感字
        $worlds = new \app\api\model\Words();
        $wordsList = $worlds->getAll()->toArray();
        if($wordsList != null){
            foreach ($wordsList as $item) {
                if($item['content']!= null && strpos($data['data_json'],trim($item['content']))!==false){ // 如果检测到关键字，则返回匹配的关键字,并终止运行
                    //$i=$k;
                    throwError('你发布的内容存在敏感字【'.$item['content'].'】');
                }
            }
        }

        $qrId = isset($form['qr_id'])?$form['qr_id']:16; //使用的二维码图片id
        $data['is_jump']=$userInfo['is_jump'];
        $data['see_jump_num']=$userInfo['see_jump_num'];
        $data['jump_link']=$userInfo['jump_link'];
        $data['create_time']=time();
        if(empty($userInfo['domain'])){
            $domain = env('domain.host','');
        }else{
            $domain = $userInfo['domain'];
        }
        $data['domain'] = $domain;
        $num = self::where('domain','=',$domain)->count();
        $data['num'] = $num +1 ;
        $id = self::insertGetId($data);
        $link = $domain.'/'.$data['num'];
        self::update(['link'=>$link],['id'=>$id]);

        //生成二维码图片
        if(count(explode('http',$domain)) > 1){ //包含http
            $image_url= $domain.'/picQr?qrId='.$qrId.'&id='.$id;
        }else{
            $image_url= 'http://'.$domain.'/picQr?qrId='.$qrId.'&id='.$id;
        }
        return ['image_url'=>$image_url,'link'=>$link];
    }

    /**
     * @param array $form
     * @return array
     * @throws \cores\exception\BaseException
     */
    public function jumpSetting(array $form){
        UserService::getCurrentLoginUser(true);
        $data = ['is_jump'=>$form['is_jump'],'see_jump_num'=>$form['see_jump_num'],'jump_link'=>$form['jump_link'],'update_time'=>time()];
        $this->where('id','=',$form['id'])->save($data);
        return [];
    }
}
