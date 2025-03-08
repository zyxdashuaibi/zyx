(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["statistics"],{29543:function(t,a,e){"use strict";e.r(a);var s={};e.r(s),e.d(s,"overview",(function(){return D})),e.d(s,"statistics",(function(){return I}));var n=function(){var t=this,a=t._self._c;return a("a-spin",{attrs:{spinning:t.isLoading}},[a("div",{staticClass:"container"},[a("a-card",{staticClass:"overview",attrs:{bordered:!1}},[a("div",{staticClass:"card-title"},[a("span",[t._v("数据概况")])]),a("div",{staticClass:"screen flex flex-x-center"},[a("a-range-picker",{attrs:{format:"YYYY-MM-DD"},on:{change:t.onPickerChange},model:{value:t.dateValue,callback:function(a){t.dateValue=a},expression:"dateValue"}}),a("div",{staticClass:"shortcut"},[a("div",{staticClass:"shortcut-days"},[a("a",{attrs:{href:"javascript:void(0);"},on:{click:function(a){return t.handleFastDate(7)}}},[t._v("7天")]),a("a",{attrs:{href:"javascript:void(0);"},on:{click:function(a){return t.handleFastDate(30)}}},[t._v("30天")]),a("a",{attrs:{href:"javascript:void(0);"},on:{click:function(a){return t.handleFastDate(0)}}},[t._v("清空")])])])],1),a("a-row",{staticClass:"mt-20",attrs:{gutter:32}},[a("a-col",{attrs:{span:12}},[a("div",{staticClass:"item flex"},[a("div",{staticClass:"col-left"},[a("div",{staticClass:"icon-body flex flex-x-center flex-y-center"},[a("a-icon",{attrs:{component:t.Icons.overview.sale}})],1)]),a("div",{staticClass:"col-right"},[a("p",{staticClass:"name"},[t._v("销售额 (元)")]),a("p",{staticClass:"value"},[t._v(t._s(t.data.overview.vipTotalMoney))])])])]),a("a-col",{attrs:{span:12}},[a("div",{staticClass:"item flex"},[a("div",{staticClass:"col-left small"},[a("div",{staticClass:"icon-body flex flex-x-center flex-y-center"},[a("a-icon",{attrs:{component:t.Icons.statistics.order}})],1)]),a("div",{staticClass:"col-right"},[a("p",{staticClass:"name"},[t._v("支付订单数 (笔)")]),a("p",{staticClass:"value"},[t._v(t._s(t.data.overview.vipTotal))])])])])],1),a("a-row",{staticClass:"mt-20",attrs:{gutter:32}},[a("a-col",{attrs:{span:12}},[a("div",{staticClass:"item flex"},[a("div",{staticClass:"col-left"},[a("div",{staticClass:"icon-body flex flex-x-center flex-y-center"},[a("a-icon",{attrs:{component:t.Icons.statistics.user}})],1)]),a("div",{staticClass:"col-right"},[a("p",{staticClass:"name"},[t._v("会员数量")]),a("p",{staticClass:"value"},[t._v(t._s(t.data.overview.userTotal))])])])]),a("a-col",{attrs:{span:12}},[a("div",{staticClass:"item flex"},[a("div",{staticClass:"col-left"},[a("div",{staticClass:"icon-body flex flex-x-center flex-y-center"},[a("a-icon",{attrs:{component:t.Icons.statistics.consume}})],1)]),a("div",{staticClass:"col-right"},[a("p",{staticClass:"name"},[t._v("消费人数")]),a("p",{staticClass:"value"},[t._v(t._s(t.data.overview.consumeUsers))])])])])],1)],1),a("a-card",{staticClass:"trade-trend mt-20",attrs:{bordered:!1}},[a("div",{staticClass:"card-title"},[a("span",[t._v("近七日交易走势")])]),a("div",{staticClass:"echarts-body"},[a("div",{staticStyle:{width:"100%",height:"400px"},attrs:{id:"main"}})])]),a("a-row",{staticClass:"ranking mt-20",attrs:{gutter:32}},[a("a-col",{staticClass:"ranking-item",attrs:{span:24}},[a("a-card",{attrs:{bordered:!1}},[a("div",{staticClass:"card-title"},[a("span",[t._v("用户消费榜")])]),a("a-table",{attrs:{rowKey:"user_id",columns:t.userRankingColumns,dataSource:t.data.userExpendRanking,pagination:!1},scopedSlots:t._u([{key:"index",fn:function(e,s,n){return[n<3&&s.pay_money>0?a("div",{staticClass:"ranking-img"},[a("img",{attrs:{src:"static/img/statistics/ranking/0".concat(n+1,".png"),alt:""}})]):a("span",[t._v(t._s(n+1))])]}}])})],1)],1)],1)],1)])},i=[],c=(e("d3b7"),e("313e")),r=e.n(c),o=(e("a59f"),e("b775")),l={data:"/statistics.data/data",survey:"/statistics.data/survey"};function d(t){return Object(o["b"])({url:l.data,method:"get",params:t})}function v(t){return Object(o["b"])({url:l.survey,method:"get",params:t})}var u=e("193c"),f=e.n(u),g=e("ba7a"),p=e.n(g),m=e("19c2"),h=e.n(m),x=e("5ef6"),C=e.n(x),y=e("f4f6"),_=e.n(y),w=e("f08d"),b=e.n(w),k=e("8a6c"),T=e.n(k),D={sale:f.a,increase:p.a},I={goods:h.a,order:C.a,user:_.a,consume:b.a,recharge:T.a},M=e("ca00"),Y=e("c1df"),R=e.n(Y),S=[{title:"排名",dataIndex:"index",align:"center",scopedSlots:{customRender:"index"}},{title:"商品名称",dataIndex:"goods_name",scopedSlots:{customRender:"goods_name"}},{title:"销量 (件)",align:"center",dataIndex:"total_sales_num"},{title:"销售额 (元)",align:"center",dataIndex:"sales_volume"}],L=[{title:"排名",dataIndex:"index",align:"center",scopedSlots:{customRender:"index"}},{title:"会员昵称",dataIndex:"nick_name",scopedSlots:{customRender:"nick_name"}},{title:"实际消费金额 (元)",align:"center",dataIndex:"expend_money"}],j={overview:{userTotal:"0",consumeUsers:"0",orderTotal:"0",orderTotalPrice:"0.00",goodsTotal:"0",rechargeTotalMoney:"0"},tradeTrend:{date:[],orderTotal:[],orderTotalPrice:[]},goodsRanking:[],userExpendRanking:[]},H={name:"Index",data:function(){return{Icons:s,isLoading:!1,data:j,dateValue:[],goodsRankingColumns:S,userRankingColumns:L}},created:function(){this.getData()},methods:{onPickerChange:function(){this.getSurvey()},handleFastDate:function(t){this.dateValue=0===t?[]:[R()(Object(M["c"])(-t)),R()(Object(M["c"])(0))],this.getSurvey()},getSurvey:function(){var t=this;this.isLoading=!0;var a=this.dateValue,e={startDate:null,endDate:null};a.length&&(e.startDate=a[0].format("YYYY-MM-DD"),e.endDate=a[1].format("YYYY-MM-DD")),v(e).then((function(a){t.data.overview=a.data})).finally((function(){t.isLoading=!1}))},getData:function(){var t=this;this.isLoading=!0,d().then((function(a){t.data=a.data.data,t.$nextTick((function(){t.myEcharts()}))})).finally((function(){t.isLoading=!1}))},myEcharts:function(){var t=this.data,a=r.a.init(document.getElementById("main"),"fresh-cut"),e={tooltip:{trigger:"axis"},legend:{data:["成交量","成交额"]},toolbox:{show:!0,showTitle:!1,feature:{mark:{show:!0},magicType:{show:!0,type:["line","bar"]}}},calculable:!0,xAxis:{type:"category",boundaryGap:!1,data:t.tradeTrend.date},yAxis:{type:"value"},series:[{name:"成交额",type:"line",data:t.tradeTrend.orderTotalPrice},{name:"成交量",type:"line",data:t.tradeTrend.orderTotal}]};a.setOption(e)}}},O=H,V=(e("e17c"),e("2877")),A=Object(V["a"])(O,n,i,!1,null,"34b11c22",null);a["default"]=A.exports},"8a6c":function(t,a,e){var s=e("b2b7");t.exports={__esModule:!0,default:s.svgComponent({tag:"svg",attrsMap:{t:"1600598056712",class:"icon",viewBox:"0 0 1024 1024",version:"1.1",xmlns:"http://www.w3.org/2000/svg","p-id":"8246",width:"200",height:"200"},children:[{tag:"path",attrsMap:{d:"M829.797514 641.926675c19.058897 0 31.786147 12.72725 31.786147 31.786147v127.080633h127.080632c19.122853 0 31.786147 12.791206 31.786147 31.850103s-19.058897 31.786147-31.786147 31.786147l-127.080632-0.063956v127.144588c0 12.72725-19.058897 31.786147-31.786147 31.786147-19.058897 0-31.786147-12.72725-31.786147-31.786147v-127.144588h-127.144588c-19.058897 0-31.722191-12.663294-31.722191-31.722191 0-19.122853 12.663294-31.786147 31.722191-31.786147l127.144588-0.063956v-127.080633c0-19.058897 12.72725-31.786147 31.786147-31.786147zM512 0c279.67972 0 508.45044 228.834676 508.45044 508.45044 0 25.4545 0 50.845044-6.395603 82.631191 0 19.058897-18.994941 31.786147-38.053838 25.4545-19.058897 0-31.786147-19.058897-25.4545-38.18175 6.395603-25.390544 6.395603-44.449441 6.395603-69.903941A442.191993 442.191993 0 0 0 512 63.572294c-247.893573 0-444.942102 203.380176-444.942102 451.273749A442.191993 442.191993 0 0 0 512 959.72419c25.4545 0 57.176691 0 82.631191-6.395603 19.058897-6.331647 31.786147 6.395603 38.117794 25.4545 6.395603 19.058897-6.395603 31.786147-25.390544 38.18175H512C232.32028 1016.900881 3.54956 788.130161 3.54956 508.45044 3.54956 228.77072 232.32028 0 512 0z m92.416464 262.859284a30.059334 30.059334 0 0 1 39.908563-14.454063 30.059334 30.059334 0 0 1 14.454062 39.908563L574.67691 468.797702h131.173818a30.059334 30.059334 0 0 1 0 60.054712H542.059334v63.95603h92.416464c20.593842 0 37.478234 13.430766 37.478234 29.995378 0 16.4367-16.884392 29.931422-37.478234 29.931423H542.059334v94.974704a30.059334 30.059334 0 0 1-29.995378 29.931423 30.059334 30.059334 0 0 1-29.995378-29.931423v-94.974704H389.588158c-20.593842 0-37.478234-13.430766-37.478234-29.931423s16.884392-30.059334 37.478234-30.059334H481.940666v-63.95603H318.213228A30.059334 30.059334 0 0 1 288.153894 498.857036a30.059334 30.059334 0 0 1 29.995378-29.995378H449.32309L365.220911 288.441696a30.059334 30.059334 0 0 1 54.362625-25.390544L512 461.25089z","p-id":"8247"}}]})}},9507:function(t,a,e){},e17c:function(t,a,e){"use strict";e("9507")}}]);