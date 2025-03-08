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

use app\common\model\user\VipLog as VipLogModel;
use app\common\enum\order\PayStatus as PayStatusEnum;

/**
 * 用户会员等级变更记录模型
 * Class VipLog
 * @package app\store\model\user
 */
class VipLog extends VipLogModel
{
    /**
     * 新增变更记录
     * @param $data
     * @return int
     */
    public function record($data)
    {
        return $this->records([$data]);
    }

    /**
     * 获取订单列表
     * @param array $param
     * @return \think\Paginator
     */
    public function getList(array $param = [])
    {
        // 设置查询条件
        $filter = $this->getFilter($param);
        // 获取列表数据
        return $this->with(['user.avatar'])
            ->where($filter)
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
            'user_id' => 0,          // 用户ID
            'search' => '',          // 查询内容
            'pay_status' => 0,       // 支付状态
            'source' => 0,       // 来源
            'betweenTime' => []     // 起止时间
        ]);
        // 检索查询条件
        $filter = [];
        $filter[] = ['is_delete', '=', 0];
        // 用户ID
        $params['user_id'] > 0 && $filter[] = ['user_id', '=', $params['user_id']];
        // 用户昵称/订单号
        !empty($params['search']) && $filter[] = ['order_no', 'like', "%{$params['search']}%"];
        // 支付状态
        $params['pay_status'] > 0 && $filter[] = ['pay_status', '=', (int)$params['pay_status']];
        $params['source'] > 0 && $filter[] = ['source', '=', (int)$params['source']];
        // 起止时间
        if (!empty($params['betweenTime'])) {
            $times = between_time($params['betweenTime']);
            $filter[] = ['pay_time', '>=', $times['start_time']];
            $filter[] = ['pay_time', '<', $times['end_time'] + 86400];
        }
        return $filter;
    }


    /**
     * 获取未付款订单数量
     * @return int
     */
    public function getNotPayOrderTotal(): int
    {
        $filter = [
            ['pay_status', '=', PayStatusEnum::PENDING]
        ];
        return $this->getOrderTotal($filter);
    }

    /**
     * 获取订单总数
     * @param array $filter
     * @return int
     */
    private function getOrderTotal(array $filter = []): int
    {
        // 获取订单总数量
        return $this->where($filter)
            ->where('is_delete', '=', 0)
            ->where('source','=','10')
            ->count();
    }

    /**
     * 获取某天的总销售额
     * @param null $startDate
     * @param null $endDate
     * @return float
     */
    public function getVipLogTotalPrice($startDate = null, $endDate = null): float
    {
        // 查询对象
        $query = $this->getNewQuery();
        // 设置查询条件
        if (!is_null($startDate) && !is_null($endDate)) {
            $query->where('pay_time', '>=', strtotime($startDate))
                ->where('pay_time', '<', strtotime($endDate) + 86400);
        }
        // 总销售额
        return $query->where('pay_status', '=', PayStatusEnum::SUCCESS)
            ->where('is_delete', '=', 0)
            ->where('source','=','10')
            ->sum('money');
    }

    /**
     * 获取某天的下单用户数
     * @param string $day
     * @return float|int
     */
    public function getPayVipLogUserTotal(string $day)
    {
        $startTime = strtotime($day);
        return $this->field('user_id')
            ->where('pay_time', '>=', $startTime)
            ->where('pay_time', '<', $startTime + 86400)
            ->where('pay_status', '=', PayStatusEnum::SUCCESS)
            ->where('is_delete', '=', '0')
            ->where('source','=','10')
            ->group('user_id')
            ->count();
    }

    /**
     * 获取已付款订单总数 (可指定某天)
     * @param null $startDate
     * @param null $endDate
     * @return int
     */
    public function getPayOrderTotal($startDate = null, $endDate = null): int
    {
        $filter = [
            ['pay_status', '=', PayStatusEnum::SUCCESS]
        ];
        if (!is_null($startDate) && !is_null($endDate)) {
            $filter[] = ['pay_time', '>=', strtotime($startDate)];
            $filter[] = ['pay_time', '<', strtotime($endDate) + 86400];
        }
        return $this->getOrderTotal($filter);
    }

}
