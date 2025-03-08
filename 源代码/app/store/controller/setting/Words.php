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

namespace app\store\controller\setting;

use app\store\controller\Controller;
use app\store\model\Words as WordsModel;

/**
 * 敏感字控制器
 * Class article
 * @package app\store\controller\setting
 */
class Words extends Controller
{
    /**
     * 敏感字列表
     * @return array|mixed
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new WordsModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 敏感字详情
     * @param int $id
     * @return array|bool|string
     */
    public function detail(int $id)
    {
        $detail = WordsModel::detail($id);
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 添加敏感字
     * @return array|mixed
     */
    public function add()
    {
        // 新增记录
        $model = new WordsModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 更新敏感字
     * @param int $id
     * @return array|mixed
     */
    public function edit(int $id)
    {
        // 敏感字详情
        $model = WordsModel::detail($id);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除敏感字
     * @param $id
     * @return array|mixed
     */
    public function delete(int $id)
    {
        // 敏感字详情
        $model = WordsModel::detail($id);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
