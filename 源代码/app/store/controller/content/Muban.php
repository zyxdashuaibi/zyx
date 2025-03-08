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
use app\store\model\Muban as MubanModel;

/**
 * 模板管理控制器
 * Class Muban
 * @package app\store\controller\content
 */
class Muban extends Controller
{
    /**
     * 文章列表
     * @return array|mixed
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new MubanModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 文章详情
     * @param int $mubanId
     * @return array|bool|string
     */
    public function detail(int $mubanId)
    {
        $detail = MubanModel::detail($mubanId);
        // 获取image (这里不能用with因为编辑页需要image对象)
        !empty($detail) && $detail['image'];
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 添加文章
     * @return array|mixed
     */
    public function add()
    {
        // 新增记录
        $model = new MubanModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 更新文章
     * @param int $mubanId
     * @return array|mixed
     */
    public function edit(int $mubanId)
    {
        // 文章详情
        $model = MubanModel::detail($mubanId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除文章
     * @param $mubanId
     * @return array|mixed
     */
    public function delete(int $mubanId)
    {
        // 文章详情
        $model = MubanModel::detail($mubanId);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
