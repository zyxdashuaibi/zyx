(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-order-express-index"],{1195:function(t,e,i){"use strict";i.r(e);var n=i("5808"),s=i.n(n);for(var a in n)"default"!==a&&function(t){i.d(e,t,(function(){return n[t]}))}(a);e["default"]=s.a},"18dc":function(t,e,i){"use strict";var n=i("c0d9"),s=i.n(n);s.a},4617:function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return s})),i.d(e,"c",(function(){return a})),i.d(e,"a",(function(){return n}));var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return t.isLoading?t._e():i("v-uni-view",{staticClass:"container"},[i("v-uni-view",{staticClass:"express i-card"},[i("v-uni-view",{staticClass:"info-item"},[i("v-uni-view",{staticClass:"item-lable"},[t._v("物流公司")]),i("v-uni-view",{staticClass:"item-content"},[i("v-uni-text",[t._v(t._s(t.express.express_name))])],1)],1),i("v-uni-view",{staticClass:"info-item"},[i("v-uni-view",{staticClass:"item-lable"},[t._v("物流单号")]),i("v-uni-view",{staticClass:"item-content"},[i("v-uni-text",[t._v(t._s(t.express.express_no))]),i("v-uni-view",{staticClass:"act-copy",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.handleCopy(t.express.express_no)}}},[i("v-uni-text",[t._v("复制")])],1)],1)],1)],1),i("v-uni-view",{staticClass:"logis-detail"},t._l(t.express.list,(function(e,n){return i("v-uni-view",{key:n,staticClass:"logis-item",class:{first:0===n}},[i("v-uni-view",{staticClass:"logis-item-content"},[i("v-uni-view",{staticClass:"logis-item-content__describe"},[i("v-uni-text",{staticClass:"f-26"},[t._v(t._s(e.context))])],1),i("v-uni-view",{staticClass:"logis-item-content__time"},[i("v-uni-text",{staticClass:"f-22"},[t._v(t._s(e.ftime))])],1)],1)],1)})),1)],1)},a=[]},5808:function(t,e,i){"use strict";var n=i("dbce");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var s=n(i("5933")),a={data:function(){return{isLoading:!0,orderId:null,express:{}}},onLoad:function(t){var e=t.orderId;this.orderId=e,this.getExpress()},methods:{getExpress:function(){var t=this;t.isLoading=!0,s.express(t.orderId).then((function(e){t.express=e.data.express,t.isLoading=!1}))},handleCopy:function(t){var e=this;uni.setClipboardData({data:t,success:function(){e.$toast("复制成功")}})}}};e.default=a},5933:function(t,e,i){"use strict";var n=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.todoCounts=o,e.list=d,e.detail=c,e.express=l,e.cancel=u,e.receipt=f,e.pay=p;var s=n(i("5530")),a=n(i("c05a")),r={todoCounts:"order/todoCounts",list:"order/list",detail:"order/detail",express:"order/express",cancel:"order/cancel",receipt:"order/receipt",pay:"order/pay"};function o(t,e){return a.default.get(r.todoCounts,t,e)}function d(t,e){return a.default.get(r.list,t,e)}function c(t,e){return a.default.get(r.detail,(0,s.default)({orderId:t},e))}function l(t,e){return a.default.get(r.express,(0,s.default)({orderId:t},e))}function u(t,e){return a.default.post(r.cancel,(0,s.default)({orderId:t},e))}function f(t,e){return a.default.post(r.receipt,(0,s.default)({orderId:t},e))}function p(t,e,i){return a.default.get(r.pay,(0,s.default)({orderId:t,payType:e},i))}},"5ad9":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */\r\n/* 引入uView全局scss变量文件 */.i-card[data-v-4c05000a]{background:#fff;padding:%?24?% %?24?%;box-shadow:0 %?1?% %?5?% 0 rgba(0,0,0,.05)}.express .info-item[data-v-4c05000a]{display:flex;margin-bottom:%?24?%}.express .info-item[data-v-4c05000a]:last-child{margin-bottom:0}.express .info-item .item-lable[data-v-4c05000a]{display:flex;align-items:center;font-size:%?24?%;color:#999;margin-right:%?30?%}.express .info-item .item-content[data-v-4c05000a]{flex:1;display:flex;align-items:center;font-size:%?26?%;color:#333}.express .info-item .item-content .act-copy[data-v-4c05000a]{margin-left:%?20?%;padding:%?2?% %?20?%;font-size:%?22?%;color:#666;border:%?1?% solid #c1c1c1;border-radius:%?16?%}.logis-detail[data-v-4c05000a]{padding:%?30?%;background-color:#fff}.logis-detail .logis-item[data-v-4c05000a]{position:relative;padding:10px 0 10px 25px;box-sizing:border-box;border-left:2px solid #ccc}.logis-detail .logis-item.first[data-v-4c05000a]{border-left:2px solid #f40}.logis-detail .logis-item.first[data-v-4c05000a]:after{background:#f40}.logis-detail .logis-item.first .logis-item-content[data-v-4c05000a]{background:#ff6e39;color:#fff}.logis-detail .logis-item.first .logis-item-content[data-v-4c05000a]:after{border-bottom-color:#ff6e39}.logis-detail .logis-item[data-v-4c05000a]:after{content:" ";display:inline-block;position:absolute;left:-6px;top:30px;width:6px;height:6px;border-radius:10px;background:#bdbdbd;border:2px solid #fff}.logis-detail .logis-item .logis-item-content[data-v-4c05000a]{position:relative;background:#f9f9f9;padding:%?10?% %?20?%;box-sizing:border-box;color:#666}.logis-detail .logis-item .logis-item-content[data-v-4c05000a]:after{content:"";display:inline-block;position:absolute;left:-10px;top:18px;border-left:10px solid #fff;border-bottom:10px solid #f3f3f3}',""]),t.exports=e},7425:function(t,e,i){"use strict";i.r(e);var n=i("4617"),s=i("1195");for(var a in s)"default"!==a&&function(t){i.d(e,t,(function(){return s[t]}))}(a);i("18dc");var r,o=i("f0c5"),d=Object(o["a"])(s["default"],n["b"],n["c"],!1,null,"4c05000a",null,!1,n["a"],r);e["default"]=d.exports},c0d9:function(t,e,i){var n=i("5ad9");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var s=i("4f06").default;s("3cbad180",n,!0,{sourceMap:!1,shadowMode:!1})}}]);