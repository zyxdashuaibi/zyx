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
use app\store\model\Pic as PicModel;

/**
 * 图片管理控制器
 * Class article
 * @package app\store\controller\content
 */
class pic extends Controller
{
    /**
     * 文章列表
     * @return array|mixed
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new PicModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 文章详情
     * @param int $picId
     * @return array|bool|string
     */
    public function detail(int $picId)
    {
        $detail = PicModel::detail($picId);
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
        $model = new PicModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 更新文章
     * @param int $picId
     * @return array|mixed
     */
    public function edit(int $picId)
    {
        // 文章详情
        $model = PicModel::detail($picId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除文章
     * @param $picId
     * @return array|mixed
     */
    public function delete(int $picId)
    {
        // 文章详情
        $model = PicModel::detail($picId);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
