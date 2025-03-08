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

namespace app\store\controller\user;

use app\store\controller\Controller;
use app\store\model\user\Play as PlayModel;

/**
 * 用户模板记录
 * Class Recharge
 * @package app\store\controller\user
 */
class Play extends Controller
{
    /**
     * 记录
     * @return mixed
     */
    public function list()
    {
        $model = new PlayModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 更新
     * @param int $id
     * @return array|mixed
     */
    public function edit(int $id)
    {
        // 详情
        $model = PlayModel::detail($id);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除
     * @param $id
     * @return array|mixed
     */
    public function delete(int $id)
    {
        // 详情
        $model = PlayModel::detail($id);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
    /**
     * 音乐详情
     * @param int $id
     * @return array|bool|string
     */
    public function detail(int $id)
    {
        $detail = PlayModel::detail($id);
        return $this->renderSuccess(compact('detail'));
    }
}
