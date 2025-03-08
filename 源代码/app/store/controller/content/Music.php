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

namespace app\store\controller\content;

use app\store\controller\Controller;
use app\store\model\Music as MusicModel;

/**
 * 音乐管理控制器
 * Class Music
 * @package app\store\controller\content
 */
class Music extends Controller
{
    /**
     * 音乐列表
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new MusicModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 音乐详情
     * @param int $MusicId
     * @return array|bool|string
     */
    public function detail(int $MusicId)
    {
        $detail = MusicModel::detail($MusicId);
        // 获取image (这里不能用with因为编辑页需要image对象)
        !empty($detail) && $detail['image'];
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 添加音乐
     * @return array|mixed
     */
    public function add()
    {
        // 新增记录
        $model = new MusicModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 更新音乐
     * @param int $MusicId
     * @return array|mixed
     */
    public function edit(int $MusicId)
    {
        // 音乐详情
        $model = MusicModel::detail($MusicId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除音乐
     * @param $MusicId
     * @return array
     */
    public function delete(int $MusicId)
    {
        // 音乐详情
        $model = MusicModel::detail($MusicId);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 解析音乐
     * * @param string $url
     * @return \think\response\Json
     */
    public function analysis($url)
    {
//        $url = empty($_GET['url'])?"https://v.douyin.com/JeRfPdL/":$_GET['url'];
//        echo $url;exit;
        header('Content-type: application/json; charset=utf-8');
        $body = $this->get_curl($url,0,"https://v.douyin.com",0,1,0,1);

        preg_match("/Location: (.*?)\r\n/iU", $body, $urls);
        if(!$urls[1]){
            return $this->renderError('error');
        }
        $dyurl = $urls[1];
        preg_match("/video\/(.*?)\//s",$dyurl,$item_ids);
        if(!$item_ids[1]){
            return $this->renderError('解析链接失败');
//            exit(json_encode(['code'=>-1,'msg'=>'解析链接失败'],320));
        }
        $item_ids = $item_ids[1];
        $api = "https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids=".$item_ids;
        $boby = $this->get_curl($api);
        $json = json_decode($boby);
        $item_list = $json->item_list[0];
        if(!$item_list){
            return $this->renderError('获取详细信息失败');
//            exit(json_encode(['code'=>-1,'msg'=>'获取详细信息失败'],320));
        }

        //获取作者信息
        $author = $item_list->author;
        $nickname = $author->nickname;//获取抖音昵称
        $unique_id = $author->unique_id;//获取抖音号
        $author_tx = $author->avatar_larger->url_list[0];//获取作者高清头像

        $desc = $item_list->desc; ////获取视频介绍
        //获取视频背景音乐
        $music = $item_list->music;
        $music_url = $music->play_url->uri;
        $author = $music->author; //真实作者
        $title = $music->title; //真实歌曲名称
//        $musicTitle = explode("一",$title);
//        $title = $musicTitle[0];
//        $author_tx = $music->cover_hd->url_list[0]; //作者高清头像
        $author_tx = $music->cover_medium->url_list[0]; //作者头像
        $duration = $music->duration;//音乐时长

        $return=[
            'code'=>1,
            'nickname'=>$nickname,
            'unique_id'=>$unique_id,
            'desc'=>$desc,
            'author_tx'=>$author_tx,
            'music_url'=>$music_url,
            'author'=>$author,
            'title'=>$title,
            'duration'=>$duration
        ];
//        return $this->renderSuccess(['a'=>$item_list]);
        return $this->renderSuccess($return);
    }


    function get_curl($url,$post=0,$referer=0,$cookie=0,$header=0,$ua=0,$nobaody=0,$split=0){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $httpheader[] = "Accept:*/*";
        $httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
        $httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
        $httpheader[] = "Connection:close";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        if($post){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if($header){
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
        }
        if($cookie){
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        if($referer){
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
        if($ua){
            curl_setopt($ch, CURLOPT_USERAGENT,$ua);
        }else{
            curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.152 Safari/537.36');
        }
        if($nobaody){
            curl_setopt($ch, CURLOPT_NOBODY,1);

        }
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        $ret = curl_exec($ch);
        if ($split) {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($ret, 0, $headerSize);
            $body = substr($ret, $headerSize);
            $ret=array();
            $ret['header']=$header;
            $ret['body']=$body;
        }
        curl_close($ch);
        return $ret;
    }


}
