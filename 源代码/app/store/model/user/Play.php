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

namespace app\store\model\user;

use app\common\model\user\Play as PlayModel;

/**
 * 用户模板数据模型
 * Class Play
 * @package app\store\model\user
 */
class Play extends PlayModel
{
    /**
     * 获取模板数据列表
     * @param array $param
     * @return \think\Paginator
     */
    public function getList($param = [])
    {
        // 设置查询条件
        $filter = $this->getFilter($param);
        // 获取列表数据
        return $this->with(['user','category'])
            ->alias('m')
            ->where($filter)
            ->where('m.is_delete', '=', 0)
            ->order(['m.create_time' => 'desc', $this->getPk()])
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
            'search' => '',         // 搜索内容
            'betweenTime' => [],    // 起止时间
            'status' => -1,      //状态
            'text' => [],    // 内容
            'mobile' => '',    // 手机号
            'id' => 0,//编号
        ]);
        // 检索查询条件
        $filter = [];
        // 用户ID
        $params['userId'] > 0 && $filter[] = ['m.user_id', '=', $params['userId']];
        $params['status'] > -1 && $filter[] = ['m.status', '=', $params['status']];
        $params['id'] > 0 && $filter[] = ['m.id', '=', $params['id']];
        // 搜索内容: 用户昵称
        !empty($params['search']) && $filter[] = ['user.nick_name', 'like', "%{$params['search']}%"];
        !empty($params['mobile']) && $filter[] = ['user.mobile', 'like', "%{$params['mobile']}%"];
        !empty($params['text']) && $filter[] = ['m.data_json', 'like', "%{$params['text']}%"];
        // 起止时间
        if (!empty($params['betweenTime'])) {
            $times = between_time($params['betweenTime']);
            $filter[] = ['m.create_time', '>=', $times['start_time']];
            $filter[] = ['m.create_time', '<', $times['end_time'] + 86400];
        }
        return $filter;
    }

    /**
     * 更新记录
     * @param array $data
     * @return bool|int
     */
    public function edit(array $data)
    {
        return $this->save($data) !== false;
    }

    /**
     * 软删除
     * @return false|int
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

}
