(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-home-wbv"],{"199c":function(t,e,n){"use strict";n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return a}));var a={uNavbar:n("7efc").default},i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("u-navbar",t._g(t._b({staticClass:"navbar",attrs:{"back-icon-size":36,"back-icon-name":t.backIcon,"back-icon-color":t.backColor,"back-text":"",title:t.title,"title-color":t.titleColor,"border-bottom":t.borderBottom,"title-bold":!0,"custom-back":t.back,backgroundColor:!0,background:t.backgroundObj,isBack:t.isBack,titleWidth:"540"}},"u-navbar",t.$attrs,!1),t.$listeners),[t._t("center"),t._t("right")],2)},r=[]},"3cd8":function(t,e,n){var a=n("24fb");e=a(!1),e.push([t.i,".webview[data-v-bfafc836]{top:%?108?%;height:calc(100vh - %?108?%)}",""]),t.exports=e},"45d7":function(t,e,n){var a=n("24fb");e=a(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */\r\n/* 主题配置 */.u-navbar[data-v-6fefbe5a]{width:100%}.u-navbar-fixed[data-v-6fefbe5a]{position:fixed;left:0;right:0;top:0;z-index:991}.u-status-bar[data-v-6fefbe5a]{width:100%}.u-navbar-inner[data-v-6fefbe5a]{display:flex;flex-direction:row;justify-content:space-between;position:relative;align-items:center}.u-back-wrap[data-v-6fefbe5a]{display:flex;flex-direction:row;align-items:center;flex:1;flex-grow:0;padding:%?14?% %?14?% %?14?% %?24?%}.u-back-text[data-v-6fefbe5a]{padding-left:%?4?%;font-size:%?30?%}.u-navbar-content-title[data-v-6fefbe5a]{display:flex;flex-direction:row;align-items:center;justify-content:center;flex:1;position:absolute;left:0;right:0;height:%?60?%;text-align:center;flex-shrink:0}.u-navbar-centent-slot[data-v-6fefbe5a]{flex:1}.u-title[data-v-6fefbe5a]{line-height:%?60?%;font-size:%?32?%;flex:1}.u-navbar-right[data-v-6fefbe5a]{flex:1;display:flex;flex-direction:row;align-items:center;justify-content:flex-end}.u-slot-content[data-v-6fefbe5a]{flex:1;display:flex;flex-direction:row;align-items:center}',""]),t.exports=e},"4bc1":function(t,e,n){"use strict";n.r(e);var a=n("e743"),i=n.n(a);for(var r in a)"default"!==r&&function(t){n.d(e,t,(function(){return a[t]}))}(r);e["default"]=i.a},"4f45":function(t,e,n){"use strict";n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return a}));var a={uIcon:n("2c2b").default},i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{},[n("v-uni-view",{staticClass:"u-navbar",class:{"u-navbar-fixed":t.isFixed,"u-border-bottom":t.borderBottom},style:[t.navbarStyle]},[n("v-uni-view",{staticClass:"u-status-bar",style:{height:t.statusBarHeight+"px"}}),n("v-uni-view",{staticClass:"u-navbar-inner",style:[t.navbarInnerStyle]},[t.isBack?n("v-uni-view",{staticClass:"u-back-wrap",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.goBack.apply(void 0,arguments)}}},[n("v-uni-view",{staticClass:"u-icon-wrap"},[n("u-icon",{attrs:{name:t.backIconName,color:t.backIconColor,size:t.backIconSize}})],1),t.backText?n("v-uni-view",{staticClass:"u-icon-wrap u-back-text u-line-1",style:[t.backTextStyle]},[t._v(t._s(t.backText))]):t._e()],1):t._e(),t.title?n("v-uni-view",{staticClass:"u-navbar-content-title",style:[t.titleStyle]},[n("v-uni-view",{staticClass:"u-title u-line-1",style:{color:t.titleColor,fontSize:t.titleSize+"rpx",fontWeight:t.titleBold?"bold":"normal"}},[t._v(t._s(t.title))])],1):t._e(),n("v-uni-view",{staticClass:"u-slot-content"},[t._t("default")],2),n("v-uni-view",{staticClass:"u-slot-right"},[t._t("right")],2)],1)],1),t.isFixed&&!t.immersive?n("v-uni-view",{staticClass:"u-navbar-placeholder",style:{width:"100%",height:Number(t.navbarHeight)+t.statusBarHeight+"px"}}):t._e()],1)},r=[]},6416:function(t,e,n){"use strict";n.r(e);var a=n("79df"),i=n.n(a);for(var r in a)"default"!==r&&function(t){n.d(e,t,(function(){return a[t]}))}(r);e["default"]=i.a},"76bd":function(t,e,n){"use strict";var a=n("b940"),i=n.n(a);i.a},"79df":function(t,e,n){"use strict";n("c975"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={data:function(){return{webviewStyles:{progress:{color:"#FED104"}},url:""}},onLoad:function(t){if(t.url){var e=decodeURIComponent(t.url);e.indexOf("group-api.runcopys.com")>-1&&(e+="?token=".concat(this.$store.state.token)),this.url=e}},methods:{handleBack:function(){console.log(123),uni.navigateTo({url:"../account/account"})}}};e.default=a},"7efc":function(t,e,n){"use strict";n.r(e);var a=n("4f45"),i=n("8661");for(var r in i)"default"!==r&&function(t){n.d(e,t,(function(){return i[t]}))}(r);n("76bd");var u,o=n("f0c5"),c=Object(o["a"])(i["default"],a["b"],a["c"],!1,null,"6fefbe5a",null,!1,a["a"],u);e["default"]=c.exports},8661:function(t,e,n){"use strict";n.r(e);var a=n("9139"),i=n.n(a);for(var r in a)"default"!==r&&function(t){n.d(e,t,(function(){return a[t]}))}(r);e["default"]=i.a},9139:function(t,e,n){"use strict";n("a9e3"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=uni.getSystemInfoSync(),i={},r={name:"u-navbar",props:{height:{type:[String,Number],default:""},backIconColor:{type:String,default:"#606266"},backIconName:{type:String,default:"nav-back"},backIconSize:{type:[String,Number],default:"44"},backText:{type:String,default:""},backTextStyle:{type:Object,default:function(){return{color:"#606266"}}},title:{type:String,default:""},titleWidth:{type:[String,Number],default:"250"},titleColor:{type:String,default:"#606266"},titleBold:{type:Boolean,default:!1},titleSize:{type:[String,Number],default:32},isBack:{type:[Boolean,String],default:!0},background:{type:Object,default:function(){return{background:"#ffffff"}}},isFixed:{type:Boolean,default:!0},immersive:{type:Boolean,default:!1},borderBottom:{type:Boolean,default:!0},zIndex:{type:[String,Number],default:""},customBack:{type:Function,default:null}},data:function(){return{menuButtonInfo:i,statusBarHeight:a.statusBarHeight}},computed:{navbarInnerStyle:function(){var t={};return t.height=this.navbarHeight+"px",t},navbarStyle:function(){var t={};return t.zIndex=this.zIndex?this.zIndex:this.$u.zIndex.navbar,Object.assign(t,this.background),t},titleStyle:function(){var t={};return t.left=(a.windowWidth-uni.upx2px(this.titleWidth))/2+"px",t.right=(a.windowWidth-uni.upx2px(this.titleWidth))/2+"px",t.width=uni.upx2px(this.titleWidth)+"px",t},navbarHeight:function(){return this.height?this.height:44}},created:function(){},methods:{goBack:function(){"function"===typeof this.customBack?this.customBack.bind(this.$u.$parent.call(this))():uni.navigateBack()}}};e.default=r},9179:function(t,e,n){"use strict";n.r(e);var a=n("99a3"),i=n("6416");for(var r in i)"default"!==r&&function(t){n.d(e,t,(function(){return i[t]}))}(r);n("97bd");var u,o=n("f0c5"),c=Object(o["a"])(i["default"],a["b"],a["c"],!1,null,"bfafc836",null,!1,a["a"],u);e["default"]=c.exports},"97bd":function(t,e,n){"use strict";var a=n("a1f2"),i=n.n(a);i.a},"99a3":function(t,e,n){"use strict";n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return a}));var a={navbar:n("b50e").default},i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticStyle:{"padding-top":"108rpx"}},[n("navbar",{attrs:{title:"",background:"#ffffff"}}),n("v-uni-web-view",{staticClass:"webview",attrs:{"webview-styles":t.webviewStyles,src:t.url}})],1)},r=[]},a1f2:function(t,e,n){var a=n("3cd8");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var i=n("4f06").default;i("effd866a",a,!0,{sourceMap:!1,shadowMode:!1})},b50e:function(t,e,n){"use strict";n.r(e);var a=n("199c"),i=n("4bc1");for(var r in i)"default"!==r&&function(t){n.d(e,t,(function(){return i[t]}))}(r);var u,o=n("f0c5"),c=Object(o["a"])(i["default"],a["b"],a["c"],!1,null,"1f02e089",null,!1,a["a"],u);e["default"]=c.exports},b940:function(t,e,n){var a=n("45d7");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var i=n("4f06").default;i("25712023",a,!0,{sourceMap:!1,shadowMode:!1})},e743:function(t,e,n){"use strict";n("a9e3"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={inheritAttrs:!1,props:{title:{type:String,default:""},titleColor:{type:String,default:"#17273a"},titleSize:{type:[String,Number],default:32},backIcon:{type:String,default:"arrow-left"},backColor:{type:String,default:"#919191"},isBack:{type:Boolean,default:!0},background:{type:String,default:"transparent"},isComfirm:{type:Boolean,default:!1},borderBottom:{type:Boolean,default:!1}},computed:{backgroundObj:function(){return"transparent"==this.background?{background:"rgba(0,0,0,0)"}:{background:this.background}}},data:function(){return{}},methods:{back:function(){if(this.isComfirm)this.$emit("beforeBack");else{var t=getCurrentPages();1==t.length?uni.navigateTo({url:"/pages/home/home"}):uni.navigateBack({})}}}};e.default=a}}]);