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

namespace app\store\controller\content\muban;

use app\store\controller\Controller;
use app\store\model\muban\Category as CategoryModel;

/**
 * 模板分类
 * Class Category
 * @package app\store\controller\content
 */
class Category extends Controller
{
    /**
     * 模板分类列表
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function list()
    {
        $model = new CategoryModel;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 添加模板分类
     * @return array|mixed
     */
    public function add()
    {
        // 新增记录
        $model = new CategoryModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑模板分类
     * @param int $categoryId
     * @return array|mixed
     */
    public function edit(int $categoryId)
    {
        // 分类详情
        $model = CategoryModel::detail($categoryId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除模板分类
     * @param int $categoryId
     * @return array|mixed
     * @throws \Exception
     */
    public function delete(int $categoryId)
    {
        $model = CategoryModel::detail($categoryId);
        if (!$model->remove($categoryId)) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
