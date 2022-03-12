!function(e){var t={};function r(s){if(t[s])return t[s].exports;var n=t[s]={i:s,l:!1,exports:{}};return e[s].call(n.exports,n,n.exports,r),n.l=!0,n.exports}r.m=e,r.c=t,r.d=function(e,t,s){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:s})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var s=Object.create(null);if(r.r(s),Object.defineProperty(s,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)r.d(s,n,function(t){return e[t]}.bind(null,n));return s},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="",r(r.s=6)}([function(e,t,r){"use strict";e.exports=r(2)},function(e,t,r){e.exports=r(4)()},function(e,t,r){"use strict";
/** @license React v16.14.0
 * react.production.min.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */var s=r(3),n="function"==typeof Symbol&&Symbol.for,o=n?Symbol.for("react.element"):60103,a=n?Symbol.for("react.portal"):60106,u=n?Symbol.for("react.fragment"):60107,c=n?Symbol.for("react.strict_mode"):60108,i=n?Symbol.for("react.profiler"):60114,l=n?Symbol.for("react.provider"):60109,p=n?Symbol.for("react.context"):60110,f=n?Symbol.for("react.forward_ref"):60112,d=n?Symbol.for("react.suspense"):60113,m=n?Symbol.for("react.memo"):60115,h=n?Symbol.for("react.lazy"):60116,y="function"==typeof Symbol&&Symbol.iterator;function _(e){for(var t="https://reactjs.org/docs/error-decoder.html?invariant="+e,r=1;r<arguments.length;r++)t+="&args[]="+encodeURIComponent(arguments[r]);return"Minified React error #"+e+"; visit "+t+" for the full message or use the non-minified dev environment for full errors and additional helpful warnings."}var g={isMounted:function(){return!1},enqueueForceUpdate:function(){},enqueueReplaceState:function(){},enqueueSetState:function(){}},S={};function b(e,t,r){this.props=e,this.context=t,this.refs=S,this.updater=r||g}function v(){}function w(e,t,r){this.props=e,this.context=t,this.refs=S,this.updater=r||g}b.prototype.isReactComponent={},b.prototype.setState=function(e,t){if("object"!=typeof e&&"function"!=typeof e&&null!=e)throw Error(_(85));this.updater.enqueueSetState(this,e,t,"setState")},b.prototype.forceUpdate=function(e){this.updater.enqueueForceUpdate(this,e,"forceUpdate")},v.prototype=b.prototype;var O=w.prototype=new v;O.constructor=w,s(O,b.prototype),O.isPureReactComponent=!0;var C={current:null},j=Object.prototype.hasOwnProperty,E={key:!0,ref:!0,__self:!0,__source:!0};function x(e,t,r){var s,n={},a=null,u=null;if(null!=t)for(s in void 0!==t.ref&&(u=t.ref),void 0!==t.key&&(a=""+t.key),t)j.call(t,s)&&!E.hasOwnProperty(s)&&(n[s]=t[s]);var c=arguments.length-2;if(1===c)n.children=r;else if(1<c){for(var i=Array(c),l=0;l<c;l++)i[l]=arguments[l+2];n.children=i}if(e&&e.defaultProps)for(s in c=e.defaultProps)void 0===n[s]&&(n[s]=c[s]);return{$$typeof:o,type:e,key:a,ref:u,props:n,_owner:C.current}}function P(e){return"object"==typeof e&&null!==e&&e.$$typeof===o}var R=/\/+/g,k=[];function U(e,t,r,s){if(k.length){var n=k.pop();return n.result=e,n.keyPrefix=t,n.func=r,n.context=s,n.count=0,n}return{result:e,keyPrefix:t,func:r,context:s,count:0}}function $(e){e.result=null,e.keyPrefix=null,e.func=null,e.context=null,e.count=0,10>k.length&&k.push(e)}function N(e,t,r){return null==e?0:function e(t,r,s,n){var u=typeof t;"undefined"!==u&&"boolean"!==u||(t=null);var c=!1;if(null===t)c=!0;else switch(u){case"string":case"number":c=!0;break;case"object":switch(t.$$typeof){case o:case a:c=!0}}if(c)return s(n,t,""===r?"."+M(t,0):r),1;if(c=0,r=""===r?".":r+":",Array.isArray(t))for(var i=0;i<t.length;i++){var l=r+M(u=t[i],i);c+=e(u,l,s,n)}else if(null===t||"object"!=typeof t?l=null:l="function"==typeof(l=y&&t[y]||t["@@iterator"])?l:null,"function"==typeof l)for(t=l.call(t),i=0;!(u=t.next()).done;)c+=e(u=u.value,l=r+M(u,i++),s,n);else if("object"===u)throw s=""+t,Error(_(31,"[object Object]"===s?"object with keys {"+Object.keys(t).join(", ")+"}":s,""));return c}(e,"",t,r)}function M(e,t){return"object"==typeof e&&null!==e&&null!=e.key?function(e){var t={"=":"=0",":":"=2"};return"$"+(""+e).replace(/[=:]/g,(function(e){return t[e]}))}(e.key):t.toString(36)}function T(e,t){e.func.call(e.context,t,e.count++)}function I(e,t,r){var s=e.result,n=e.keyPrefix;e=e.func.call(e.context,t,e.count++),Array.isArray(e)?A(e,s,r,(function(e){return e})):null!=e&&(P(e)&&(e=function(e,t){return{$$typeof:o,type:e.type,key:t,ref:e.ref,props:e.props,_owner:e._owner}}(e,n+(!e.key||t&&t.key===e.key?"":(""+e.key).replace(R,"$&/")+"/")+r)),s.push(e))}function A(e,t,r,s,n){var o="";null!=r&&(o=(""+r).replace(R,"$&/")+"/"),N(e,I,t=U(t,o,s,n)),$(t)}var L={current:null};function z(){var e=L.current;if(null===e)throw Error(_(321));return e}var D={ReactCurrentDispatcher:L,ReactCurrentBatchConfig:{suspense:null},ReactCurrentOwner:C,IsSomeRendererActing:{current:!1},assign:s};t.Children={map:function(e,t,r){if(null==e)return e;var s=[];return A(e,s,null,t,r),s},forEach:function(e,t,r){if(null==e)return e;N(e,T,t=U(null,null,t,r)),$(t)},count:function(e){return N(e,(function(){return null}),null)},toArray:function(e){var t=[];return A(e,t,null,(function(e){return e})),t},only:function(e){if(!P(e))throw Error(_(143));return e}},t.Component=b,t.Fragment=u,t.Profiler=i,t.PureComponent=w,t.StrictMode=c,t.Suspense=d,t.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED=D,t.cloneElement=function(e,t,r){if(null==e)throw Error(_(267,e));var n=s({},e.props),a=e.key,u=e.ref,c=e._owner;if(null!=t){if(void 0!==t.ref&&(u=t.ref,c=C.current),void 0!==t.key&&(a=""+t.key),e.type&&e.type.defaultProps)var i=e.type.defaultProps;for(l in t)j.call(t,l)&&!E.hasOwnProperty(l)&&(n[l]=void 0===t[l]&&void 0!==i?i[l]:t[l])}var l=arguments.length-2;if(1===l)n.children=r;else if(1<l){i=Array(l);for(var p=0;p<l;p++)i[p]=arguments[p+2];n.children=i}return{$$typeof:o,type:e.type,key:a,ref:u,props:n,_owner:c}},t.createContext=function(e,t){return void 0===t&&(t=null),(e={$$typeof:p,_calculateChangedBits:t,_currentValue:e,_currentValue2:e,_threadCount:0,Provider:null,Consumer:null}).Provider={$$typeof:l,_context:e},e.Consumer=e},t.createElement=x,t.createFactory=function(e){var t=x.bind(null,e);return t.type=e,t},t.createRef=function(){return{current:null}},t.forwardRef=function(e){return{$$typeof:f,render:e}},t.isValidElement=P,t.lazy=function(e){return{$$typeof:h,_ctor:e,_status:-1,_result:null}},t.memo=function(e,t){return{$$typeof:m,type:e,compare:void 0===t?null:t}},t.useCallback=function(e,t){return z().useCallback(e,t)},t.useContext=function(e,t){return z().useContext(e,t)},t.useDebugValue=function(){},t.useEffect=function(e,t){return z().useEffect(e,t)},t.useImperativeHandle=function(e,t,r){return z().useImperativeHandle(e,t,r)},t.useLayoutEffect=function(e,t){return z().useLayoutEffect(e,t)},t.useMemo=function(e,t){return z().useMemo(e,t)},t.useReducer=function(e,t,r){return z().useReducer(e,t,r)},t.useRef=function(e){return z().useRef(e)},t.useState=function(e){return z().useState(e)},t.version="16.14.0"},function(e,t,r){"use strict";
/*
object-assign
(c) Sindre Sorhus
@license MIT
*/var s=Object.getOwnPropertySymbols,n=Object.prototype.hasOwnProperty,o=Object.prototype.propertyIsEnumerable;function a(e){if(null==e)throw new TypeError("Object.assign cannot be called with null or undefined");return Object(e)}e.exports=function(){try{if(!Object.assign)return!1;var e=new String("abc");if(e[5]="de","5"===Object.getOwnPropertyNames(e)[0])return!1;for(var t={},r=0;r<10;r++)t["_"+String.fromCharCode(r)]=r;if("0123456789"!==Object.getOwnPropertyNames(t).map((function(e){return t[e]})).join(""))return!1;var s={};return"abcdefghijklmnopqrst".split("").forEach((function(e){s[e]=e})),"abcdefghijklmnopqrst"===Object.keys(Object.assign({},s)).join("")}catch(e){return!1}}()?Object.assign:function(e,t){for(var r,u,c=a(e),i=1;i<arguments.length;i++){for(var l in r=Object(arguments[i]))n.call(r,l)&&(c[l]=r[l]);if(s){u=s(r);for(var p=0;p<u.length;p++)o.call(r,u[p])&&(c[u[p]]=r[u[p]])}}return c}},function(e,t,r){"use strict";var s=r(5);function n(){}function o(){}o.resetWarningCache=n,e.exports=function(){function e(e,t,r,n,o,a){if(a!==s){var u=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw u.name="Invariant Violation",u}}function t(){return e}e.isRequired=e;var r={array:e,bool:e,func:e,number:e,object:e,string:e,symbol:e,any:e,arrayOf:t,element:e,elementType:e,instanceOf:t,node:e,objectOf:t,oneOf:t,oneOfType:t,shape:t,exact:t,checkPropTypes:o,resetWarningCache:n};return r.PropTypes=r,r}},function(e,t,r){"use strict";e.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"},function(e,t,r){"use strict";r.r(t);var s=r(0),n=r.n(s),o=r(1),a=r.n(o);class u extends n.a.Component{constructor(e){super(e),this.state={value:0,max:100}}render(){return n.a.createElement("progress",{value:this.props.value,max:this.props.max})}}class c extends s.Component{constructor(e){super(e),this.state={progress1a:0,progress1b:0,max1:0,progress2:0,max2:0,progress:0,max:0,scan_status:{scanned:0,fetched:0,total_pages:0,completed:!1,duration:0},warmup_status:{total:0,warmed_count:0,notwarmed_resources:[],completed:!1,duration:0},allow_optimization:this.props.wpRUCSSObject.api_allow_optimization,error_message:"",code:0,success:!0}}getStatus(){wp.apiFetch({url:this.props.wpRUCSSObject.api_url,method:"POST"}).then(e=>this.setState({scan_status:void 0!==e.data.scan_status?e.data.scan_status:this.state.scan_status,warmup_status:void 0!==e.data.warmup_status?e.data.warmup_status:this.state.warmup_status,code:void 0!==e.code?e.code:this.state.code,success:void 0!==e.success?e.success:this.state.success,error_message:void 0!==e.message?e.message:this.state.error_message,allow_optimization:void 0!==e.data.allow_optimization?e.data.allow_optimization:this.state.allow_optimization}))}computeProgress(){if("rest_forbidden"==this.state.code||0==this.state.success)return clearInterval(this.timeout),void this.setState({error_message:this.state.error_message});let e=this.step1ProgressA(),t=this.step1ProgressB(),r=this.step1MaxProgress(),s=this.step2Progress(),n=this.step2MaxProgress(),o=(r>0?Math.ceil(50*e/r):0)+(r>0?Math.ceil(50*t/r):0);o>100&&(o=100);let a=o+(n>0?Math.ceil(100*s/n):0);a>200&&(a=200);this.step1Completed()&&this.step2Completed()&&(clearInterval(this.timeout),a=200),this.setState({progress1a:e,progress1b:t,max1:r,progress2:s,max2:n,progress:a,max:200})}componentDidMount(){this.getStatus(),this.computeProgress(),this.timeout=setInterval(()=>{this.getStatus(),this.computeProgress(),this.state.progress>=200&&clearInterval(this.timeout)},3e3)}step1Completed(){return this.state.scan_status.completed}step1ProgressA(){return this.state.scan_status.scanned}step1ProgressB(){return this.state.scan_status.fetched}step1MaxProgress(){return this.state.scan_status.total_pages}step2Completed(){return this.state.warmup_status.completed}step2Progress(){return this.state.warmup_status.warmed_count}step2MaxProgress(){return this.state.warmup_status.total}renderError(){let e;return this.state.success||""==this.state.error_message||(e=n.a.createElement("div",{className:"rucss-progress-error wpr-fieldWarning-title wpr-icon-important"},this.state.error_message)),e}renderScanStep(){let e;if(this.state.success&&this.props.wpRUCSSObject.api_debug){let t=this.props.wpRUCSSObject.wpr_rucss_translations.step1_txt;t=t.replace("{count}",this.state.scan_status.fetched),t=t.replace("{total}",this.state.scan_status.total_pages);let r=this.step1Completed()?"rucss-progress-step completed step1  wpr-icon-check":"rucss-progress-step step1";e=n.a.createElement("div",{className:r},n.a.createElement("div",{className:"spinner"}),t)}return e}renderWarmupStep(){let e;if(this.state.success&&this.props.wpRUCSSObject.api_debug&&this.step1Completed()){let t=this.props.wpRUCSSObject.wpr_rucss_translations.step2_txt;t=t.replace("{count}",this.state.warmup_status.warmed_count),t=t.replace("{total}",this.state.warmup_status.total);let r=this.step2Completed()?"rucss-progress-step completed step2  wpr-icon-check":"rucss-progress-step  step2";e=n.a.createElement("div",{className:r},n.a.createElement("div",{className:"spinner"}),t)}return e}renderRUCSSEnabled(){let e;return this.state.allow_optimization&&(e=n.a.createElement("div",{className:"rucss-progress-step completed wpr-icon-check"},this.props.wpRUCSSObject.wpr_rucss_translations.rucss_working)),e}renderNotWarmedResourcesList(){let e;return this.state.success&&this.props.wpRUCSSObject.api_debug&&this.step1Completed()&&this.state.warmup_status.notwarmed_resources.length>0&&(e=n.a.createElement("div",{className:"rucss-progress-step wpr-icon-important rucss-progress-step2-list"},this.props.wpRUCSSObject.wpr_rucss_translations.warmed_list,n.a.createElement("ul",{className:"rucss-notwarmed-resources"},this.state.warmup_status.notwarmed_resources.map(e=>n.a.createElement("li",{key:e,className:"list-group-item list-group-item-primary"},e))))),e}renderRUCSSProgress(){let e;return this.state.allow_optimization||(e=n.a.createElement("div",null,n.a.createElement("div",{className:"rucss-progress-bar"},n.a.createElement(u,{value:this.state.progress,max:this.state.max})))),e}renderRUCSSSingleStep(){let e;if(this.state.success&&!this.props.wpRUCSSObject.api_debug&&!this.state.allow_optimization){let t=this.step2Completed()?"rucss-progress-step completed step2  wpr-icon-check":"rucss-progress-step  step2";e=n.a.createElement("div",{className:t},n.a.createElement("div",{className:"spinner"}),n.a.createElement("span",{dangerouslySetInnerHTML:{__html:this.props.wpRUCSSObject.wpr_rucss_translations.rucss_info_txt}}))}return e}render(){return n.a.createElement("div",{className:"rucss-status wpr-field-description"},this.renderRUCSSProgress(),this.renderError(),this.renderRUCSSSingleStep(),this.renderScanStep(),this.renderWarmupStep(),this.renderNotWarmedResourcesList(),this.renderRUCSSEnabled())}}c.propTypes={wpRUCSSObject:a.a.object},document.addEventListener("DOMContentLoaded",(function(){ReactDOM.render(React.createElement(c,{wpRUCSSObject:window.rocket_rucss_ajax_data}),document.getElementById("rucss-progressbar"))}))}]);