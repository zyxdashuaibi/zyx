(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-order-detail"],{"00b6":function(e,t,a){var i=a("44cc2");"string"===typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);var n=a("4f06").default;n("217928cc",i,!0,{sourceMap:!1,shadowMode:!1})},"0893":function(e,t,a){"use strict";var i=a("dbce");a("d3b7"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n=a("3a11"),s=i(a("5933")),o=a("c824"),r={data:function(){return{DeliveryStatusEnum:n.DeliveryStatusEnum,DeliveryTypeEnum:n.DeliveryTypeEnum,OrderStatusEnum:n.OrderStatusEnum,PayStatusEnum:n.PayStatusEnum,PayTypeEnum:n.PayTypeEnum,ReceiptStatusEnum:n.ReceiptStatusEnum,orderId:null,isLoading:!0,order:{},setting:{},showPayPopup:!1}},onLoad:function(e){var t=e.orderId;this.orderId=t,this.getOrderDetail()},methods:{getOrderDetail:function(){var e=this;e.isLoading=!0,s.detail(e.orderId).then((function(t){e.order=t.data.order,e.setting=t.data.setting,e.isLoading=!1}))},handleCopy:function(e){var t=this;uni.setClipboardData({data:e,success:function(){t.$toast("复制成功")}})},handleTargetExpress:function(){this.$navTo("pages/order/express/index",{orderId:this.orderId})},handleTargetGoods:function(e){this.$navTo("pages/goods/detail",{goodsId:e})},handleApplyRefund:function(e){this.$navTo("pages/refund/apply",{orderGoodsId:e})},onCancel:function(e){var t=this;uni.showModal({title:"友情提示",content:"确认要取消该订单吗？",success:function(a){a.confirm&&s.cancel(e).then((function(e){t.$toast(e.message),t.getOrderDetail()}))}})},onReceipt:function(e){var t=this;uni.showModal({title:"友情提示",content:"确认收到商品了吗？",success:function(a){a.confirm&&s.receipt(e).then((function(e){t.$success(e.message),t.getOrderDetail()}))}})},onPay:function(){this.showPayPopup=!0},onSelectPayType:function(e){var t=this;this.showPayPopup=!1,s.pay(t.orderId,e).then((function(e){return t.onSubmitCallback(e)})).catch((function(e){return e}))},onSubmitCallback:function(e){var t=this;e.data.pay_type==n.PayTypeEnum.WECHAT.value&&(0,o.wxPayment)(e.data.payment).then((function(){t.$success("支付成功"),setTimeout((function(){t.getOrderDetail()}),1500)})).catch((function(e){t.$error("订单未支付")})).finally((function(){t.disabled=!1})),e.data.pay_type==n.PayTypeEnum.BALANCE.value&&(t.$success("支付成功"),t.disabled=!1,setTimeout((function(){t.getOrderDetail()}),1500))},handleTargetComment:function(e){this.$navTo("pages/order/comment/index",{orderId:e})}}};t.default=r},"24e0":function(e,t,a){"use strict";var i=a("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n=i(a("43dc")),s=new n.default([{key:"EXPRESS",name:"快递配送",value:10}]);t.default=s},"3a11":function(e,t,a){"use strict";var i=a("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),Object.defineProperty(t,"DeliveryStatusEnum",{enumerable:!0,get:function(){return n.default}}),Object.defineProperty(t,"DeliveryTypeEnum",{enumerable:!0,get:function(){return s.default}}),Object.defineProperty(t,"OrderSourceEnum",{enumerable:!0,get:function(){return o.default}}),Object.defineProperty(t,"OrderStatusEnum",{enumerable:!0,get:function(){return r.default}}),Object.defineProperty(t,"PayStatusEnum",{enumerable:!0,get:function(){return d.default}}),Object.defineProperty(t,"PayTypeEnum",{enumerable:!0,get:function(){return u.default}}),Object.defineProperty(t,"ReceiptStatusEnum",{enumerable:!0,get:function(){return l.default}});var n=i(a("c21f")),s=i(a("24e0")),o=i(a("e985")),r=i(a("9dc9")),d=i(a("b0db")),u=i(a("e685")),l=i(a("d223"))},"409a2":function(e,t,a){"use strict";a.d(t,"b",(function(){return n})),a.d(t,"c",(function(){return s})),a.d(t,"a",(function(){return i}));var i={uPopup:a("43de").default},n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return e.isLoading?e._e():a("v-uni-view",{staticClass:"container"},[a("v-uni-view",{staticClass:"header"},[a("v-uni-view",{staticClass:"order-status"},[a("v-uni-view",{staticClass:"status-icon"},[e.order.order_status==e.OrderStatusEnum.NORMAL.value?[e.order.pay_status==e.PayStatusEnum.PENDING.value?[a("v-uni-image",{staticClass:"image",attrs:{src:"/static/order/status/wait_pay.png",mode:"aspectFit"}})]:e.order.delivery_status==e.DeliveryStatusEnum.NOT_DELIVERED.value?[a("v-uni-image",{staticClass:"image",attrs:{src:"/static/order/status/wait_deliver.png",mode:"aspectFit"}})]:e.order.receipt_status==e.ReceiptStatusEnum.NOT_RECEIVED.value?[a("v-uni-image",{staticClass:"image",attrs:{src:"/static/order/status/wait_receipt.png",mode:"aspectFit"}})]:e._e()]:e._e(),e.order.order_status==e.OrderStatusEnum.COMPLETED.value?[a("v-uni-image",{staticClass:"image",attrs:{src:"/static/order/status/received.png",mode:"aspectFit"}})]:e._e(),e.order.order_status==e.OrderStatusEnum.CANCELLED.value||e.order.order_status==e.OrderStatusEnum.APPLY_CANCEL.value?[a("v-uni-image",{staticClass:"image",attrs:{src:"/static/order/status/close.png",mode:"aspectFit"}})]:e._e()],2),a("v-uni-view",{staticClass:"status-text"},[a("v-uni-text",[e._v(e._s(e.order.state_text))])],1)],1),e.order.order_status==e.OrderStatusEnum.NORMAL.value?a("v-uni-view",{staticClass:"next-action"},[e.order.pay_status==e.PayStatusEnum.PENDING.value?a("v-uni-view",{staticClass:"action-btn",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.onPay()}}},[e._v("去支付")]):e._e(),e.order.delivery_status==e.DeliveryStatusEnum.DELIVERED.value&&e.order.receipt_status==e.ReceiptStatusEnum.NOT_RECEIVED.value?a("v-uni-view",{staticClass:"action-btn",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.onReceipt(e.order.order_id)}}},[e._v("确认收货")]):e._e()],1):e._e()],1),a("v-uni-view",{staticClass:"delivery-address i-card"},[a("v-uni-view",{staticClass:"link-man"},[a("v-uni-text",{staticClass:"name"},[e._v(e._s(e.order.address.name))]),a("v-uni-text",{staticClass:"phone"},[e._v(e._s(e.order.address.phone))])],1),a("v-uni-view",{staticClass:"address"},[e._l(e.order.address.region,(function(t,i){return a("v-uni-text",{key:i,staticClass:"region"},[e._v(e._s(t))])})),a("v-uni-text",{staticClass:"detail"},[e._v(e._s(e.order.address.detail))])],2)],1),e.order.delivery_type==e.DeliveryTypeEnum.EXPRESS.value&&e.order.delivery_status==e.DeliveryStatusEnum.DELIVERED.value&&e.order.express?a("v-uni-view",{staticClass:"express i-card",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.handleTargetExpress()}}},[a("v-uni-view",{staticClass:"main"},[a("v-uni-view",{staticClass:"info-item"},[a("v-uni-view",{staticClass:"item-lable"},[e._v("物流公司")]),a("v-uni-view",{staticClass:"item-content"},[a("v-uni-text",[e._v(e._s(e.order.express.express_name))])],1)],1),a("v-uni-view",{staticClass:"info-item"},[a("v-uni-view",{staticClass:"item-lable"},[e._v("物流单号")]),a("v-uni-view",{staticClass:"item-content"},[a("v-uni-text",[e._v(e._s(e.order.express_no))]),a("v-uni-view",{staticClass:"act-copy",on:{click:function(t){t.stopPropagation(),arguments[0]=t=e.$handleEvent(t),e.handleCopy(e.order.express_no)}}},[a("v-uni-text",[e._v("复制")])],1)],1)],1)],1),a("v-uni-view",{staticClass:"right-arrow"},[a("v-uni-text",{staticClass:"iconfont icon-arrow-right"})],1)],1):e._e(),a("v-uni-view",{staticClass:"goods-list i-card"},e._l(e.order.goods,(function(t,i){return a("v-uni-view",{key:i,staticClass:"goods-item"},[a("v-uni-view",{staticClass:"goods-main",on:{click:function(a){arguments[0]=a=e.$handleEvent(a),e.handleTargetGoods(t.goods_id)}}},[a("v-uni-view",{staticClass:"goods-image"},[a("v-uni-image",{staticClass:"image",attrs:{src:t.goods_image,mode:"scaleToFill"}})],1),a("v-uni-view",{staticClass:"goods-content"},[a("v-uni-view",{staticClass:"goods-title"},[a("v-uni-text",{staticClass:"twoline-hide"},[e._v(e._s(t.goods_name))])],1),a("v-uni-view",{staticClass:"goods-props clearfix"},e._l(t.goods_props,(function(t,i){return a("v-uni-view",{key:i,staticClass:"goods-props-item"},[a("v-uni-text",[e._v(e._s(t.value.name))])],1)})),1)],1),a("v-uni-view",{staticClass:"goods-trade"},[a("v-uni-view",{staticClass:"goods-price"},[a("v-uni-text",{staticClass:"unit"},[e._v("￥")]),a("v-uni-text",{staticClass:"value"},[e._v(e._s(t.is_user_grade?t.grade_goods_price:t.goods_price))])],1),a("v-uni-view",{staticClass:"goods-num"},[a("v-uni-text",[e._v("×"+e._s(t.total_num))])],1)],1)],1),a("v-uni-view",{staticClass:"goods-refund"},[t.refund?a("v-uni-text",{staticClass:"stata-text"},[e._v("已申请售后")]):e.order.isAllowRefund?a("v-uni-view",{staticClass:"action-btn",on:{click:function(a){a.stopPropagation(),arguments[0]=a=e.$handleEvent(a),e.handleApplyRefund(t.order_goods_id)}}},[e._v("申请售后")]):e._e()],1)],1)})),1),a("v-uni-view",{staticClass:"order-info i-card"},[a("v-uni-view",{staticClass:"info-item"},[a("v-uni-view",{staticClass:"item-lable"},[e._v("订单编号")]),a("v-uni-view",{staticClass:"item-content"},[a("v-uni-text",[e._v(e._s(e.order.order_no))]),a("v-uni-view",{staticClass:"act-copy",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.handleCopy(e.order.order_no)}}},[a("v-uni-text",[e._v("复制")])],1)],1)],1),a("v-uni-view",{staticClass:"info-item"},[a("v-uni-view",{staticClass:"item-lable"},[e._v("下单时间")]),a("v-uni-view",{staticClass:"item-content"},[a("v-uni-text",[e._v(e._s(e.order.create_time))])],1)],1),a("v-uni-view",{staticClass:"info-item"},[a("v-uni-view",{staticClass:"item-lable"},[e._v("买家留言")]),a("v-uni-view",{staticClass:"item-content"},[a("v-uni-text",[e._v(e._s(e.order.buyer_remark?e.order.buyer_remark:"--"))])],1)],1)],1),a("v-uni-view",{staticClass:"trade-info i-card"},[a("v-uni-view",{staticClass:"info-item"},[a("v-uni-view",{staticClass:"item-lable"},[e._v("订单金额")]),a("v-uni-view",{staticClass:"item-content"},[a("v-uni-text",[e._v("￥"+e._s(e.order.total_price))])],1)],1),e.order.coupon_money>0?a("v-uni-view",{staticClass:"info-item"},[a("v-uni-view",{staticClass:"item-lable"},[e._v("优惠券抵扣")]),a("v-uni-view",{staticClass:"item-content"},[a("v-uni-text",[e._v("-￥"+e._s(e.order.coupon_money))])],1)],1):e._e(),e.order.points_money>0?a("v-uni-view",{staticClass:"info-item"},[a("v-uni-view",{staticClass:"item-lable"},[e._v(e._s(e.setting.points_name)+"抵扣")]),a("v-uni-view",{staticClass:"item-content"},[a("v-uni-text",[e._v("-￥"+e._s(e.order.points_money))])],1)],1):e._e(),a("v-uni-view",{staticClass:"info-item"},[a("v-uni-view",{staticClass:"item-lable"},[e._v("运费")]),a("v-uni-view",{staticClass:"item-content"},[a("v-uni-text",[e._v("+￥"+e._s(e.order.express_price))])],1)],1),"0.00"!=e.order.update_price.value?a("v-uni-view",{staticClass:"info-item"},[a("v-uni-view",{staticClass:"item-lable"},[e._v("后台改价")]),a("v-uni-view",{staticClass:"item-content"},[a("v-uni-text",[e._v(e._s(e.order.update_price.symbol))]),a("v-uni-text",[e._v("￥"+e._s(e.order.update_price.value))])],1)],1):e._e(),a("v-uni-view",{staticClass:"divider"}),a("v-uni-view",{staticClass:"trade-total"},[a("v-uni-text",{staticClass:"lable"},[e._v("实付款")]),a("v-uni-view",{staticClass:"goods-price"},[a("v-uni-text",{staticClass:"unit"},[e._v("￥")]),a("v-uni-text",{staticClass:"value"},[e._v(e._s(e.order.pay_price))])],1)],1)],1),e.order.order_status!=e.OrderStatusEnum.CANCELLED.value?a("v-uni-view",{staticClass:"footer-fixed"},[a("v-uni-view",{staticClass:"btn-wrapper"},[e.order.pay_status==e.PayStatusEnum.PENDING.value?[a("v-uni-view",{staticClass:"btn-item",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.onCancel(e.order.order_id)}}},[e._v("取消")])]:e._e(),e.order.order_status!=e.OrderStatusEnum.APPLY_CANCEL.value?[e.order.pay_status==e.PayStatusEnum.SUCCESS.value&&e.order.delivery_status==e.DeliveryStatusEnum.NOT_DELIVERED.value?[a("v-uni-view",{staticClass:"btn-item",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.onCancel(e.order.order_id)}}},[e._v("申请取消")])]:e._e()]:a("v-uni-view",{staticClass:"f-28 col-8"},[e._v("取消申请中")]),e.order.pay_status==e.PayStatusEnum.PENDING.value?[a("v-uni-view",{staticClass:"btn-item active",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.onPay()}}},[e._v("去支付")])]:e._e(),e.order.delivery_status==e.DeliveryStatusEnum.DELIVERED.value&&e.order.receipt_status==e.ReceiptStatusEnum.NOT_RECEIVED.value?[a("v-uni-view",{staticClass:"btn-item active",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.onReceipt(e.order.order_id)}}},[e._v("确认收货")])]:e._e(),e.order.order_status==e.OrderStatusEnum.COMPLETED.value&&0==e.order.is_comment?[a("v-uni-view",{staticClass:"btn-item",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.handleTargetComment(e.order.order_id)}}},[e._v("评价")])]:e._e()],2)],1):e._e(),a("u-popup",{attrs:{mode:"bottom","border-radius":"26",closeable:!0},model:{value:e.showPayPopup,callback:function(t){e.showPayPopup=t},expression:"showPayPopup"}},[a("v-uni-view",{staticClass:"pay-popup"},[a("v-uni-view",{staticClass:"title"},[e._v("请选择支付方式")]),a("v-uni-view",{staticClass:"pop-content"},[a("v-uni-view",{staticClass:"pay-item dis-flex flex-x-between",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.onSelectPayType(e.PayTypeEnum.BALANCE.value)}}},[a("v-uni-view",{staticClass:"item-left dis-flex flex-y-center"},[a("v-uni-view",{staticClass:"item-left_icon balance"},[a("v-uni-text",{staticClass:"iconfont icon-balance-pay"})],1),a("v-uni-view",{staticClass:"item-left_text"},[a("v-uni-text",[e._v(e._s(e.PayTypeEnum.BALANCE.name))])],1)],1)],1)],1)],1)],1)],1)},s=[]},"43dc":function(e,t,a){"use strict";var i=a("4ea4");a("c975"),a("d81d"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n=i(a("d4ec")),s=i(a("bee2")),o=function(){function e(t){var a=this;(0,n.default)(this,e);var i=[],s=[];if(!Array.isArray(t))throw new Error("param is not an array!");t.map((function(e){e.key&&e.name&&(i.push(e.key),s.push(e.value),a[e.key]=e,e.key!==e.value&&(a[e.value]=e))})),this.data=t,this.keyArr=i,this.valueArr=s}return(0,s.default)(e,[{key:"keyOf",value:function(e){return this.data[this.keyArr.indexOf(e)]}},{key:"valueOf",value:function(e){return this.data[this.valueArr.indexOf(e)]}},{key:"getNameByKey",value:function(e){var t=this.keyOf(e);if(!t)throw new Error("No enum constant"+e);return t.name}},{key:"getNameByValue",value:function(e){var t=this.valueOf(e);if(!t)throw new Error("No enum constant"+e);return t.name}},{key:"getValueByKey",value:function(e){var t=this.keyOf(e);if(!t)throw new Error("No enum constant"+e);return t.key}},{key:"getData",value:function(){return this.data}}]),e}(),r=o;t.default=r},"44cc2":function(e,t,a){var i=a("24fb");t=i(!1),t.push([e.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */\r\n/* 引入uView全局scss变量文件 */.container[data-v-566d8aba]{padding-bottom:constant(env(safe-area-inset-bottom)106rpx6rpx);padding-bottom:calc(env(safe-area-inset-bottom) + %?106?% + %?6?%)}.header[data-v-566d8aba]{display:flex;justify-content:space-between;background-color:#e8c269;height:%?280?%;padding:%?56?% %?30?% 0 %?30?%}.header .order-status[data-v-566d8aba]{display:flex;align-items:center;height:%?128?%}.header .order-status .status-icon[data-v-566d8aba]{width:%?128?%;height:%?128?%}.header .order-status .status-icon .image[data-v-566d8aba]{display:block;width:100%;height:100%}.header .order-status .status-text[data-v-566d8aba]{padding-left:%?20?%;color:#fff;font-size:%?38?%;font-weight:700}.header .next-action[data-v-566d8aba]{display:flex;align-items:center;height:%?128?%}.header .next-action .action-btn[data-v-566d8aba]{min-width:%?152?%;height:%?56?%;padding:0 %?30?%;line-height:%?56?%;background-color:#fff;text-align:center;border-radius:%?28?%;border-color:#666;cursor:pointer;-webkit-user-select:none;user-select:none;color:#c7a157}.i-card[data-v-566d8aba]{background:#fff;padding:%?24?% %?24?%;width:94%;box-shadow:0 %?1?% %?5?% 0 rgba(0,0,0,.05);margin:0 auto %?20?% auto;border-radius:%?20?%}.delivery-address[data-v-566d8aba]{margin-top:%?-50?%}.delivery-address .link-man[data-v-566d8aba]{line-height:%?46?%;color:#333}.delivery-address .link-man .name[data-v-566d8aba]{margin-right:%?10?%}.delivery-address .address[data-v-566d8aba]{margin-top:%?12?%;color:#999;font-size:%?24?%}.delivery-address .address .detail[data-v-566d8aba]{margin-left:%?6?%}.express[data-v-566d8aba]{display:flex;align-items:center}.express .main[data-v-566d8aba]{flex:1}.express .info-item[data-v-566d8aba]{display:flex;margin-bottom:%?24?%}.express .info-item[data-v-566d8aba]:last-child{margin-bottom:0}.express .info-item .item-lable[data-v-566d8aba]{display:flex;align-items:center;font-size:%?24?%;color:#999;margin-right:%?30?%}.express .info-item .item-content[data-v-566d8aba]{flex:1;display:flex;align-items:center;font-size:%?26?%;color:#333}.express .info-item .item-content .act-copy[data-v-566d8aba]{margin-left:%?20?%;padding:%?2?% %?20?%;font-size:%?22?%;color:#666;border:%?1?% solid #c1c1c1;border-radius:%?16?%}.express .right-arrow[data-v-566d8aba]{margin-left:%?16?%;font-size:%?26?%}.goods-list .goods-item[data-v-566d8aba]{margin-bottom:%?40?%}.goods-list .goods-item[data-v-566d8aba]:last-child{margin-bottom:0}.goods-list .goods-item .goods-main[data-v-566d8aba]{display:flex}.goods-list .goods-item .goods-image[data-v-566d8aba]{width:%?180?%;height:%?180?%}.goods-list .goods-item .goods-image .image[data-v-566d8aba]{display:block;width:100%;height:100%;border-radius:%?8?%}.goods-list .goods-item .goods-content[data-v-566d8aba]{flex:1;padding-left:%?16?%;padding-top:%?16?%}.goods-list .goods-item .goods-content .goods-title[data-v-566d8aba]{font-size:%?26?%;max-height:%?76?%}.goods-list .goods-item .goods-content .goods-props[data-v-566d8aba]{margin-top:%?14?%;height:%?40?%;color:#ababab;font-size:%?24?%;overflow:hidden}.goods-list .goods-item .goods-content .goods-props .goods-props-item[data-v-566d8aba]{display:inline-block;margin-right:%?14?%;padding:%?4?% %?16?%;border-radius:%?12?%;background-color:#f5f5f5;width:auto}.goods-list .goods-item .goods-trade[data-v-566d8aba]{padding-top:%?16?%;width:%?150?%;text-align:right;color:#999;font-size:%?26?%}.goods-list .goods-item .goods-trade .goods-price[data-v-566d8aba]{vertical-align:bottom;margin-bottom:%?16?%}.goods-list .goods-item .goods-trade .goods-price .unit[data-v-566d8aba]{margin-right:%?-2?%;font-size:%?24?%}.goods-list .goods-item .goods-refund[data-v-566d8aba]{display:flex;justify-content:flex-end}.goods-list .goods-item .goods-refund .stata-text[data-v-566d8aba]{font-size:%?24?%;color:#999}.goods-list .goods-item .goods-refund .action-btn[data-v-566d8aba]{border-radius:%?28?%;padding:%?8?% %?26?%;font-size:%?24?%;color:#383838;border:%?1?% solid #a8a8a8}.order-info .info-item[data-v-566d8aba]{display:flex;margin-bottom:%?24?%}.order-info .info-item[data-v-566d8aba]:last-child{margin-bottom:0}.order-info .info-item .item-lable[data-v-566d8aba]{display:flex;align-items:center;font-size:%?24?%;color:#999;margin-right:%?30?%}.order-info .info-item .item-content[data-v-566d8aba]{flex:1;display:flex;align-items:center;font-size:%?26?%;color:#333}.order-info .info-item .item-content .act-copy[data-v-566d8aba]{margin-left:%?20?%;padding:%?2?% %?20?%;font-size:%?22?%;color:#666;border:%?1?% solid #c1c1c1;border-radius:%?16?%}.trade-info .info-item[data-v-566d8aba]{display:flex;margin-bottom:%?24?%}.trade-info .info-item .item-lable[data-v-566d8aba]{font-size:%?24?%;color:#999;margin-right:%?24?%}.trade-info .info-item .item-content[data-v-566d8aba]{flex:1;font-size:%?26?%;color:#333;text-align:right}.trade-info .divider[data-v-566d8aba]{height:%?1?%;background:#f1f1f1;margin-bottom:%?24?%}.trade-info .trade-total[data-v-566d8aba]{display:flex;justify-content:flex-end}.trade-info .trade-total .goods-price[data-v-566d8aba]{margin-left:%?12?%;vertical-align:bottom;color:#fa2209}.trade-info .trade-total .goods-price .unit[data-v-566d8aba]{margin-right:%?-2?%;font-size:%?24?%}\r\n/* 底部操作栏 */.footer-fixed[data-v-566d8aba]{position:fixed;bottom:var(--window-bottom);left:0;right:0;z-index:11;box-shadow:0 %?-4?% %?40?% 0 hsla(0,0%,59.2%,.24);background:#fff;padding-bottom:constant(safe-area-inset-bottom);padding-bottom:env(safe-area-inset-bottom)}.footer-fixed .btn-wrapper[data-v-566d8aba]{height:%?106?%;display:flex;align-items:center;justify-content:flex-end;padding:0 %?30?%}.footer-fixed .btn-item[data-v-566d8aba]{min-width:%?164?%;border-radius:%?28?%;padding:%?10?% %?24?%;font-size:%?28?%;color:#383838;text-align:center;border:%?1?% solid #a8a8a8;margin-left:%?24?%}.footer-fixed .btn-item.active[data-v-566d8aba]{color:#fff;border:none;background:linear-gradient(90deg,#f9211c,#ff6335)}.pay-popup[data-v-566d8aba]{padding:%?24?%}.pay-popup .title[data-v-566d8aba]{font-size:%?30?%;margin-bottom:%?50?%;font-weight:700;text-align:center}.pay-popup .pop-content[data-v-566d8aba]{min-height:%?260?%;padding:0 %?10?%}.pay-popup .pop-content .pay-item[data-v-566d8aba]{padding:%?20?% %?35?%;font-size:%?28?%;border-bottom:%?1?% solid #f1f1f1}.pay-popup .pop-content .pay-item[data-v-566d8aba]:last-child{border-bottom:none}.pay-popup .pop-content .pay-item .item-left_icon[data-v-566d8aba]{margin-right:%?20?%;font-size:%?32?%}.pay-popup .pop-content .pay-item .item-left_icon.wechat[data-v-566d8aba]{color:#00c800}.pay-popup .pop-content .pay-item .item-left_icon.balance[data-v-566d8aba]{color:#ff9700}',""]),e.exports=t},5933:function(e,t,a){"use strict";var i=a("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.todoCounts=r,t.list=d,t.detail=u,t.express=l,t.cancel=c,t.receipt=v,t.pay=f;var n=i(a("5530")),s=i(a("c05a")),o={todoCounts:"order/todoCounts",list:"order/list",detail:"order/detail",express:"order/express",cancel:"order/cancel",receipt:"order/receipt",pay:"order/pay"};function r(e,t){return s.default.get(o.todoCounts,e,t)}function d(e,t){return s.default.get(o.list,e,t)}function u(e,t){return s.default.get(o.detail,(0,n.default)({orderId:e},t))}function l(e,t){return s.default.get(o.express,(0,n.default)({orderId:e},t))}function c(e,t){return s.default.post(o.cancel,(0,n.default)({orderId:e},t))}function v(e,t){return s.default.post(o.receipt,(0,n.default)({orderId:e},t))}function f(e,t,a){return s.default.get(o.pay,(0,n.default)({orderId:e,payType:t},a))}},"77e4":function(e,t,a){"use strict";var i=a("9719"),n=a.n(i);n.a},9123:function(e,t,a){"use strict";var i=a("00b6"),n=a.n(i);n.a},"93b0":function(e,t,a){var i=a("24fb");t=i(!1),t.push([e.i,"uni-page-body[data-v-566d8aba]{background:#f4f4f4}body.?%PAGE?%[data-v-566d8aba]{background:#f4f4f4}",""]),e.exports=t},9719:function(e,t,a){var i=a("93b0");"string"===typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);var n=a("4f06").default;n("3ee6de9a",i,!0,{sourceMap:!1,shadowMode:!1})},"9dc9":function(e,t,a){"use strict";var i=a("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n=i(a("43dc")),s=new n.default([{key:"NORMAL",name:"进行中",value:10},{key:"CANCELLED",name:"已取消",value:20},{key:"APPLY_CANCEL",name:"待取消",value:21},{key:"COMPLETED",name:"已完成",value:30}]);t.default=s},b0db:function(e,t,a){"use strict";var i=a("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n=i(a("43dc")),s=new n.default([{key:"PENDING",name:"待支付",value:10},{key:"SUCCESS",name:"已支付",value:20}]);t.default=s},c21f:function(e,t,a){"use strict";var i=a("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n=i(a("43dc")),s=new n.default([{key:"NOT_DELIVERED",name:"未发货",value:10},{key:"DELIVERED",name:"已发货",value:20}]);t.default=s},d223:function(e,t,a){"use strict";var i=a("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n=i(a("43dc")),s=new n.default([{key:"NOT_RECEIVED",name:"未收货",value:10},{key:"RECEIVED",name:"已收货",value:20}]);t.default=s},e45b:function(e,t,a){"use strict";a.r(t);var i=a("409a2"),n=a("ee1b");for(var s in n)"default"!==s&&function(e){a.d(t,e,(function(){return n[e]}))}(s);a("77e4"),a("9123");var o,r=a("f0c5"),d=Object(r["a"])(n["default"],i["b"],i["c"],!1,null,"566d8aba",null,!1,i["a"],o);t["default"]=d.exports},e685:function(e,t,a){"use strict";var i=a("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n=i(a("43dc")),s=new n.default([{key:"BALANCE",name:"余额支付",value:10},{key:"WECHAT",name:"微信支付",value:20}]);t.default=s},e985:function(e,t,a){"use strict";var i=a("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n=i(a("43dc")),s=new n.default([{key:"MASTER",name:"普通订单",value:10},{key:"BARGAIN",name:"砍价订单",value:20},{key:"SHARP",name:"秒杀订单",value:30}]);t.default=s},ee1b:function(e,t,a){"use strict";a.r(t);var i=a("0893"),n=a.n(i);for(var s in i)"default"!==s&&function(e){a.d(t,e,(function(){return i[e]}))}(s);t["default"]=n.a}}]);