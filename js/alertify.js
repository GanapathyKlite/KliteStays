!function(e){"use strict";function t(e,t){e.className+=" "+t}function n(e,t){for(var n=e.className.split(" "),i=t.split(" "),s=0;s<i.length;s+=1){var o=n.indexOf(i[s]);o>-1&&n.splice(o,1)}e.className=n.join(" ")}function i(){return"rtl"===e.getComputedStyle(document.body).direction}function s(){return document.documentElement&&document.documentElement.scrollTop||document.body.scrollTop}function o(){return document.documentElement&&document.documentElement.scrollLeft||document.body.scrollLeft}function a(e){for(;e.lastChild;)e.removeChild(e.lastChild)}function l(e){if(null===e)return e;var t;if(Array.isArray(e)){t=[];for(var n=0;n<e.length;n+=1)t.push(l(e[n]));return t}if(e instanceof Date)return new Date(e.getTime());if(e instanceof RegExp)return t=new RegExp(e.source),t.global=e.global,t.ignoreCase=e.ignoreCase,t.multiline=e.multiline,t.lastIndex=e.lastIndex,t;if("object"==typeof e){t={};for(var i in e)e.hasOwnProperty(i)&&(t[i]=l(e[i]));return t}return e}function r(e,t){var n=e.elements.root;n.parentNode.removeChild(n),delete e.elements,e.settings=l(e.__settings),e.__init=t,delete e.__internal}function d(e,t){return function(){if(arguments.length>0){for(var n=[],i=0;i<arguments.length;i+=1)n.push(arguments[i]);return n.push(e),t.apply(e,n)}return t.apply(e,[null,e])}}function u(e,t){return{index:e,button:t,cancel:!1}}function c(){function e(e,t){for(var n in t)t.hasOwnProperty(n)&&(e[n]=t[n]);return e}function t(e){var t=i[e].dialog;return t&&"function"==typeof t.__init&&t.__init(t),t}function n(t,n,s,o){var a={dialog:null,factory:n};return void 0!==o&&(a.factory=function(){return e(new i[o].factory,new n)}),s||(a.dialog=e(new a.factory,v)),i[t]=a}var i={};return{defaults:f,dialog:function(i,s,o,a){if("function"!=typeof s)return t(i);if(this.hasOwnProperty(i))throw new Error("alertify.dialog: name already exists");var l=n(i,s,o,a);o?this[i]=function(){if(0===arguments.length)return l.dialog;var t=e(new l.factory,v);return t&&"function"==typeof t.__init&&t.__init(t),t.main.apply(t,arguments),t.show.apply(t)}:this[i]=function(){if(l.dialog&&"function"==typeof l.dialog.__init&&l.dialog.__init(l.dialog),0===arguments.length)return l.dialog;var e=l.dialog;return e.main.apply(l.dialog,arguments),e.show.apply(l.dialog)}},closeAll:function(e){for(var t=h.slice(0),n=0;n<t.length;n+=1){var i=t[n];(void 0===e||e!==i)&&i.close()}},setting:function(e,n,i){if("notifier"===e)return y.setting(n,i);var s=t(e);return s?s.setting(n,i):void 0},set:function(e,t,n){return this.setting(e,t,n)},get:function(e,t){return this.setting(e,t)},notify:function(e,t,n,i){return y.create(t,i).push(e,n)},message:function(e,t,n){return y.create(null,n).push(e,t)},success:function(e,t,n){return y.create("success",n).push(e,t)},error:function(e,t,n){return y.create("error",n).push(e,t)},warning:function(e,t,n){return y.create("warning",n).push(e,t)},dismissAll:function(){y.dismissAll()}}}var m={ENTER:13,ESC:27,F1:112,F12:123,LEFT:37,RIGHT:39},f={modal:!0,basic:!1,frameless:!1,movable:!0,moveBounded:!1,resizable:!0,closable:!0,closableByDimmer:!0,maximizable:!0,startMaximized:!1,pinnable:!0,pinned:!0,padding:!0,overflow:!0,maintainFocus:!0,transition:"pulse",autoReset:!0,notifier:{delay:5,position:"bottom-right"},glossary:{title:"AlertifyJS",ok:"OK",cancel:"Cancel",acccpt:"Accept",deny:"Deny",confirm:"Confirm",decline:"Decline",close:"Close",maximize:"Maximize",restore:"Restore"},theme:{input:"ajs-input",ok:"ajs-ok",cancel:"ajs-cancel"}},h=[],p=function(){return document.addEventListener?function(e,t,n,i){e.addEventListener(t,n,i===!0)}:document.attachEvent?function(e,t,n){e.attachEvent("on"+t,n)}:void 0}(),b=function(){return document.removeEventListener?function(e,t,n,i){e.removeEventListener(t,n,i===!0)}:document.detachEvent?function(e,t,n){e.detachEvent("on"+t,n)}:void 0}(),g=function(){var e,t,n=!1,i={animation:"animationend",OAnimation:"oAnimationEnd oanimationend",msAnimation:"MSAnimationEnd",MozAnimation:"animationend",WebkitAnimation:"webkitAnimationEnd"};for(e in i)if(void 0!==document.documentElement.style[e]){t=i[e],n=!0;break}return{type:t,supported:n}}(),v=function(){function c(e){if(!e.__internal){delete e.__init,e.__settings||(e.__settings=l(e.settings)),null===He&&document.body.setAttribute("tabindex","0");var n;"function"==typeof e.setup?(n=e.setup(),n.options=n.options||{},n.focus=n.focus||{}):n={buttons:[],focus:{element:null,select:!1},options:{}},"object"!=typeof e.hooks&&(e.hooks={});var i=[];if(Array.isArray(n.buttons))for(var s=0;s<n.buttons.length;s+=1){var o=n.buttons[s],a={};for(var r in o)o.hasOwnProperty(r)&&(a[r]=o[r]);i.push(a)}var u=e.__internal={isOpen:!1,activeElement:document.body,timerIn:void 0,timerOut:void 0,buttons:i,focus:n.focus,options:{title:void 0,modal:void 0,basic:void 0,frameless:void 0,pinned:void 0,movable:void 0,moveBounded:void 0,resizable:void 0,autoReset:void 0,closable:void 0,closableByDimmer:void 0,maximizable:void 0,startMaximized:void 0,pinnable:void 0,transition:void 0,padding:void 0,overflow:void 0,onshow:void 0,onclose:void 0,onfocus:void 0},resetHandler:void 0,beginMoveHandler:void 0,beginResizeHandler:void 0,bringToFrontHandler:void 0,modalClickHandler:void 0,buttonsClickHandler:void 0,commandsClickHandler:void 0,transitionInHandler:void 0,transitionOutHandler:void 0,destroy:void 0},c={};c.root=document.createElement("div"),c.root.className=Te.base+" "+Te.hidden+" ",c.root.innerHTML=we.dimmer+we.modal,c.dimmer=c.root.firstChild,c.modal=c.root.lastChild,c.modal.innerHTML=we.dialog,c.dialog=c.modal.firstChild,c.dialog.innerHTML=we.reset+we.commands+we.header+we.body+we.footer+we.resizeHandle+we.reset,c.reset=[],c.reset.push(c.dialog.firstChild),c.reset.push(c.dialog.lastChild),c.commands={},c.commands.container=c.reset[0].nextSibling,c.commands.pin=c.commands.container.firstChild,c.commands.maximize=c.commands.pin.nextSibling,c.commands.close=c.commands.maximize.nextSibling,c.header=c.commands.container.nextSibling,c.body=c.header.nextSibling,c.body.innerHTML=we.content,c.content=c.body.firstChild,c.footer=c.body.nextSibling,c.footer.innerHTML=we.buttons.auxiliary+we.buttons.primary,c.resizeHandle=c.footer.nextSibling,c.buttons={},c.buttons.auxiliary=c.footer.firstChild,c.buttons.primary=c.buttons.auxiliary.nextSibling,c.buttons.primary.innerHTML=we.button,c.buttonTemplate=c.buttons.primary.firstChild,c.buttons.primary.removeChild(c.buttonTemplate);for(var m=0;m<e.__internal.buttons.length;m+=1){var f=e.__internal.buttons[m];ke.indexOf(f.key)<0&&ke.push(f.key),f.element=c.buttonTemplate.cloneNode(),f.element.innerHTML=f.text,"string"==typeof f.className&&""!==f.className&&t(f.element,f.className);for(var h in f.attrs)"className"!==h&&f.attrs.hasOwnProperty(h)&&f.element.setAttribute(h,f.attrs[h]);"auxiliary"===f.scope?c.buttons.auxiliary.appendChild(f.element):c.buttons.primary.appendChild(f.element)}e.elements=c,u.resetHandler=d(e,q),u.beginMoveHandler=d(e,Z),u.beginResizeHandler=d(e,se),u.bringToFrontHandler=d(e,w),u.modalClickHandler=d(e,F),u.buttonsClickHandler=d(e,U),u.commandsClickHandler=d(e,E),u.transitionInHandler=d(e,J),u.transitionOutHandler=d(e,K),e.set("title",void 0===n.options.title?_.defaults.glossary.title:n.options.title),e.set("modal",void 0===n.options.modal?_.defaults.modal:n.options.modal),e.set("basic",void 0===n.options.basic?_.defaults.basic:n.options.basic),e.set("frameless",void 0===n.options.frameless?_.defaults.frameless:n.options.frameless),e.set("movable",void 0===n.options.movable?_.defaults.movable:n.options.movable),e.set("moveBounded",void 0===n.options.moveBounded?_.defaults.moveBounded:n.options.moveBounded),e.set("resizable",void 0===n.options.resizable?_.defaults.resizable:n.options.resizable),e.set("autoReset",void 0===n.options.autoReset?_.defaults.autoReset:n.options.autoReset),e.set("closable",void 0===n.options.closable?_.defaults.closable:n.options.closable),e.set("closableByDimmer",void 0===n.options.closableByDimmer?_.defaults.closableByDimmer:n.options.closableByDimmer),e.set("maximizable",void 0===n.options.maximizable?_.defaults.maximizable:n.options.maximizable),e.set("startMaximized",void 0===n.options.startMaximized?_.defaults.startMaximized:n.options.startMaximized),e.set("pinnable",void 0===n.options.pinnable?_.defaults.pinnable:n.options.pinnable),e.set("pinned",void 0===n.options.pinned?_.defaults.pinned:n.options.pinned),e.set("transition",void 0===n.options.transition?_.defaults.transition:n.options.transition),e.set("padding",void 0===n.options.padding?_.defaults.padding:n.options.padding),e.set("overflow",void 0===n.options.overflow?_.defaults.overflow:n.options.overflow),"function"==typeof e.build&&e.build()}document.body.appendChild(e.elements.root)}function f(){_e=o(),xe=s()}function v(){e.scrollTo(_e,xe)}function y(){for(var e=0,i=0;i<h.length;i+=1){var s=h[i];(s.isModal()||s.isMaximized())&&(e+=1)}0===e?n(document.body,Te.noOverflow):e>0&&document.body.className.indexOf(Te.noOverflow)<0&&t(document.body,Te.noOverflow)}function x(e,i,s){"string"==typeof s&&n(e.elements.root,Te.prefix+s),t(e.elements.root,Te.prefix+i),He=e.elements.root.offsetWidth}function k(e){e.get("modal")?(n(e.elements.root,Te.modeless),e.isOpen()&&(fe(e),R(e),y())):(t(e.elements.root,Te.modeless),e.isOpen()&&(me(e),R(e),y()))}function H(e){e.get("basic")?t(e.elements.root,Te.basic):n(e.elements.root,Te.basic)}function z(e){e.get("frameless")?t(e.elements.root,Te.frameless):n(e.elements.root,Te.frameless)}function w(e,t){for(var n=h.indexOf(t),i=n+1;i<h.length;i+=1)if(h[i].isModal())return;return document.body.lastChild!==t.elements.root&&(document.body.appendChild(t.elements.root),h.splice(h.indexOf(t),1),h.push(t),G(t)),!1}function T(e,i,s,o){switch(i){case"title":e.setHeader(o);break;case"modal":k(e);break;case"basic":H(e);break;case"frameless":z(e);break;case"pinned":W(e);break;case"closable":B(e);break;case"maximizable":S(e);break;case"pinnable":I(e);break;case"movable":ne(e);break;case"resizable":re(e);break;case"transition":x(e,o,s);break;case"padding":o?n(e.elements.root,Te.noPadding):e.elements.root.className.indexOf(Te.noPadding)<0&&t(e.elements.root,Te.noPadding);break;case"overflow":o?n(e.elements.root,Te.noOverflow):e.elements.root.className.indexOf(Te.noOverflow)<0&&t(e.elements.root,Te.noOverflow);break;case"transition":x(e,o,s)}"function"==typeof e.hooks.onupdate&&e.hooks.onupdate.call(e,i,s,o)}function C(e,t,n,i,s){var o={op:void 0,items:[]};if("undefined"==typeof s&&"string"==typeof i)o.op="get",t.hasOwnProperty(i)?(o.found=!0,o.value=t[i]):(o.found=!1,o.value=void 0);else{var a;if(o.op="set","object"==typeof i){var l=i;for(var r in l)t.hasOwnProperty(r)?(t[r]!==l[r]&&(a=t[r],t[r]=l[r],n.call(e,r,a,l[r])),o.items.push({key:r,value:l[r],found:!0})):o.items.push({key:r,value:l[r],found:!1})}else{if("string"!=typeof i)throw new Error("args must be a string or object");t.hasOwnProperty(i)?(t[i]!==s&&(a=t[i],t[i]=s,n.call(e,i,a,s)),o.items.push({key:i,value:s,found:!0})):o.items.push({key:i,value:s,found:!1})}}return o}function O(e){var t;D(e,function(e){return t=e.invokeOnClose===!0}),!t&&e.isOpen()&&e.close()}function E(e,t){var n=e.srcElement||e.target;switch(n){case t.elements.commands.pin:t.isPinned()?j(t):M(t);break;case t.elements.commands.maximize:t.isMaximized()?L(t):N(t);break;case t.elements.commands.close:O(t)}return!1}function M(e){e.set("pinned",!0)}function j(e){e.set("pinned",!1)}function N(e){t(e.elements.root,Te.maximized),e.isOpen()&&y()}function L(e){n(e.elements.root,Te.maximized),e.isOpen()&&y()}function I(e){e.get("pinnable")?t(e.elements.root,Te.pinnable):n(e.elements.root,Te.pinnable)}function A(e){var t=o();e.elements.modal.style.marginTop=s()+"px",e.elements.modal.style.marginLeft=t+"px",e.elements.modal.style.marginRight=-t+"px"}function P(e){var t=parseInt(e.elements.modal.style.marginTop,10),n=parseInt(e.elements.modal.style.marginLeft,10);if(e.elements.modal.style.marginTop="",e.elements.modal.style.marginLeft="",e.elements.modal.style.marginRight="",e.isOpen()){var i=0,a=0;""!==e.elements.dialog.style.top&&(i=parseInt(e.elements.dialog.style.top,10)),e.elements.dialog.style.top=i+(t-s())+"px",""!==e.elements.dialog.style.left&&(a=parseInt(e.elements.dialog.style.left,10)),e.elements.dialog.style.left=a+(n-o())+"px"}}function R(e){e.get("modal")||e.get("pinned")?P(e):A(e)}function W(e){e.get("pinned")?(n(e.elements.root,Te.unpinned),e.isOpen()&&P(e)):(t(e.elements.root,Te.unpinned),e.isOpen()&&!e.isModal()&&A(e))}function S(e){e.get("maximizable")?t(e.elements.root,Te.maximizable):n(e.elements.root,Te.maximizable)}function B(e){e.get("closable")?(t(e.elements.root,Te.closable),ve(e)):(n(e.elements.root,Te.closable),ye(e))}function F(e,t){var n=e.srcElement||e.target;return Ce||n!==t.elements.modal||t.get("closableByDimmer")!==!0||O(t),Ce=!1,!1}function D(e,t){for(var n=0;n<e.__internal.buttons.length;n+=1){var i=e.__internal.buttons[n];if(!i.element.disabled&&t(i)){var s=u(n,i);"function"==typeof e.callback&&e.callback.apply(e,[s]),s.cancel===!1&&e.close();break}}}function U(e,t){var n=e.srcElement||e.target;D(t,function(e){return e.element===n&&(Oe=!0)})}function X(e){if(Oe)return void(Oe=!1);var t=h[h.length-1],n=e.keyCode;return 0===t.__internal.buttons.length&&n===m.ESC&&t.get("closable")===!0?(O(t),!1):ke.indexOf(n)>-1?(D(t,function(e){return e.key===n}),!1):void 0}function Y(e){var t=h[h.length-1],n=e.keyCode;if(n===m.LEFT||n===m.RIGHT){for(var i=t.__internal.buttons,s=0;s<i.length;s+=1)if(document.activeElement===i[s].element)switch(n){case m.LEFT:return void i[(s||i.length)-1].element.focus();case m.RIGHT:return void i[(s+1)%i.length].element.focus()}}else if(n<m.F12+1&&n>m.F1-1&&ke.indexOf(n)>-1)return e.preventDefault(),e.stopPropagation(),D(t,function(e){return e.key===n}),!1}function G(e,t){if(t)t.focus();else{var n=e.__internal.focus,i=n.element;switch(typeof n.element){case"number":e.__internal.buttons.length>n.element&&(i=e.get("basic")===!0?e.elements.reset[0]:e.__internal.buttons[n.element].element);break;case"string":i=e.elements.body.querySelector(n.element);break;case"function":i=n.element.call(e)}"undefined"!=typeof i&&null!==i||0!==e.__internal.buttons.length||(i=e.elements.reset[0]),i&&i.focus&&(i.focus(),n.select&&i.select&&i.select())}}function q(e,t){if(!t)for(var n=h.length-1;n>-1;n-=1)if(h[n].isModal()){t=h[n];break}if(t&&t.isModal()){var i,s=e.srcElement||e.target,o=s===t.elements.reset[1]||0===t.__internal.buttons.length&&s===document.body;o&&(t.get("maximizable")?i=t.elements.commands.maximize:t.get("closable")&&(i=t.elements.commands.close)),void 0===i&&("number"==typeof t.__internal.focus.element?s===t.elements.reset[0]?i=t.elements.buttons.auxiliary.firstChild||t.elements.buttons.primary.firstChild:o&&(i=t.elements.reset[0]):s===t.elements.reset[0]&&(i=t.elements.buttons.primary.lastChild||t.elements.buttons.auxiliary.lastChild)),G(t,i)}}function J(e,t){clearTimeout(t.__internal.timerIn),G(t),v(),Oe=!1,"function"==typeof t.get("onfocus")&&t.get("onfocus").call(t),b(t.elements.dialog,g.type,t.__internal.transitionInHandler),n(t.elements.root,Te.animationIn)}function K(e,t){clearTimeout(t.__internal.timerOut),b(t.elements.dialog,g.type,t.__internal.transitionOutHandler),te(t),le(t),t.isMaximized()&&!t.get("startMaximized")&&L(t),_.defaults.maintainFocus&&t.__internal.activeElement&&(t.__internal.activeElement.focus(),t.__internal.activeElement=null),"function"==typeof t.__internal.destroy&&t.__internal.destroy.apply(t)}function V(e,t){var n=e[Ne]-Me,i=e[Le]-je;Ae&&(i-=document.body.scrollTop),t.style.left=n+"px",t.style.top=i+"px"}function Q(e,t){var n=e[Ne]-Me,i=e[Le]-je;Ae&&(i-=document.body.scrollTop),t.style.left=Math.min(Ie.maxLeft,Math.max(Ie.minLeft,n))+"px",Ae?t.style.top=Math.min(Ie.maxTop,Math.max(Ie.minTop,i))+"px":t.style.top=Math.max(Ie.minTop,i)+"px"}function Z(e,n){if(null===Re&&!n.isMaximized()&&n.get("movable")){var i,s=0,o=0;if("touchstart"===e.type?(e.preventDefault(),i=e.targetTouches[0],Ne="clientX",Le="clientY"):0===e.button&&(i=e),i){var a=n.elements.dialog;if(t(a,Te.capture),a.style.left&&(s=parseInt(a.style.left,10)),a.style.top&&(o=parseInt(a.style.top,10)),Me=i[Ne]-s,je=i[Le]-o,n.isModal()?je+=n.elements.modal.scrollTop:n.isPinned()&&(je-=document.body.scrollTop),n.get("moveBounded")){var l=a,r=-s,d=-o;do r+=l.offsetLeft,d+=l.offsetTop;while(l=l.offsetParent);Ie={maxLeft:r,minLeft:-r,maxTop:document.documentElement.clientHeight-a.clientHeight-d,minTop:-d},Pe=Q}else Ie=null,Pe=V;return Ae=!n.isModal()&&n.isPinned(),Ee=n,Pe(i,a),t(document.body,Te.noSelection),!1}}}function $(e){if(Ee){var t;"touchmove"===e.type?(e.preventDefault(),t=e.targetTouches[0]):0===e.button&&(t=e),t&&Pe(t,Ee.elements.dialog)}}function ee(){if(Ee){var e=Ee.elements.dialog;Ee=Ie=null,n(document.body,Te.noSelection),n(e,Te.capture)}}function te(e){Ee=null;var t=e.elements.dialog;t.style.left=t.style.top=""}function ne(e){e.get("movable")?(t(e.elements.root,Te.movable),e.isOpen()&&he(e)):(te(e),n(e.elements.root,Te.movable),e.isOpen()&&pe(e))}function ie(e,t,n){var s=t,o=0,a=0;do o+=s.offsetLeft,a+=s.offsetTop;while(s=s.offsetParent);var l,r;n===!0?(l=e.pageX,r=e.pageY):(l=e.clientX,r=e.clientY);var d=i();if(d&&(l=document.body.offsetWidth-l,isNaN(We)||(o=document.body.offsetWidth-o-t.offsetWidth)),t.style.height=r-a+Fe+"px",t.style.width=l-o+Fe+"px",!isNaN(We)){var u=.5*Math.abs(t.offsetWidth-Se);d&&(u*=-1),t.offsetWidth>Se?t.style.left=We+u+"px":t.offsetWidth>=Be&&(t.style.left=We-u+"px")}}function se(e,n){if(!n.isMaximized()){var i;if("touchstart"===e.type?(e.preventDefault(),i=e.targetTouches[0]):0===e.button&&(i=e),i){Re=n,Fe=n.elements.resizeHandle.offsetHeight/2;var s=n.elements.dialog;return t(s,Te.capture),We=parseInt(s.style.left,10),s.style.height=s.offsetHeight+"px",s.style.minHeight=n.elements.header.offsetHeight+n.elements.footer.offsetHeight+"px",s.style.width=(Se=s.offsetWidth)+"px","none"!==s.style.maxWidth&&(s.style.minWidth=(Be=s.offsetWidth)+"px"),s.style.maxWidth="none",t(document.body,Te.noSelection),!1}}}function oe(e){if(Re){var t;"touchmove"===e.type?(e.preventDefault(),t=e.targetTouches[0]):0===e.button&&(t=e),t&&ie(t,Re.elements.dialog,!Re.get("modal")&&!Re.get("pinned"))}}function ae(){if(Re){var e=Re.elements.dialog;Re=null,n(document.body,Te.noSelection),n(e,Te.capture),Ce=!0}}function le(e){Re=null;var t=e.elements.dialog;"none"===t.style.maxWidth&&(t.style.maxWidth=t.style.minWidth=t.style.width=t.style.height=t.style.minHeight=t.style.left="",We=Number.Nan,Se=Be=Fe=0)}function re(e){e.get("resizable")?(t(e.elements.root,Te.resizable),e.isOpen()&&be(e)):(le(e),n(e.elements.root,Te.resizable),e.isOpen()&&ge(e))}function de(){for(var e=0;e<h.length;e+=1){var t=h[e];t.get("autoReset")&&(te(t),le(t))}}function ue(t){1===h.length&&(p(e,"resize",de),p(document.body,"keyup",X),p(document.body,"keydown",Y),p(document.body,"focus",q),p(document.documentElement,"mousemove",$),p(document.documentElement,"touchmove",$),p(document.documentElement,"mouseup",ee),p(document.documentElement,"touchend",ee),p(document.documentElement,"mousemove",oe),p(document.documentElement,"touchmove",oe),p(document.documentElement,"mouseup",ae),p(document.documentElement,"touchend",ae)),p(t.elements.commands.container,"click",t.__internal.commandsClickHandler),p(t.elements.footer,"click",t.__internal.buttonsClickHandler),p(t.elements.reset[0],"focus",t.__internal.resetHandler),p(t.elements.reset[1],"focus",t.__internal.resetHandler),Oe=!0,p(t.elements.dialog,g.type,t.__internal.transitionInHandler),t.get("modal")||me(t),t.get("resizable")&&be(t),t.get("movable")&&he(t)}function ce(t){1===h.length&&(b(e,"resize",de),b(document.body,"keyup",X),b(document.body,"keydown",Y),b(document.body,"focus",q),b(document.documentElement,"mousemove",$),b(document.documentElement,"mouseup",ee),b(document.documentElement,"mousemove",oe),b(document.documentElement,"mouseup",ae)),b(t.elements.commands.container,"click",t.__internal.commandsClickHandler),b(t.elements.footer,"click",t.__internal.buttonsClickHandler),b(t.elements.reset[0],"focus",t.__internal.resetHandler),b(t.elements.reset[1],"focus",t.__internal.resetHandler),p(t.elements.dialog,g.type,t.__internal.transitionOutHandler),t.get("modal")||fe(t),t.get("movable")&&pe(t),t.get("resizable")&&ge(t)}function me(e){p(e.elements.dialog,"focus",e.__internal.bringToFrontHandler,!0)}function fe(e){b(e.elements.dialog,"focus",e.__internal.bringToFrontHandler,!0)}function he(e){p(e.elements.header,"mousedown",e.__internal.beginMoveHandler),p(e.elements.header,"touchstart",e.__internal.beginMoveHandler)}function pe(e){b(e.elements.header,"mousedown",e.__internal.beginMoveHandler),b(e.elements.header,"touchstart",e.__internal.beginMoveHandler)}function be(e){p(e.elements.resizeHandle,"mousedown",e.__internal.beginResizeHandler),p(e.elements.resizeHandle,"touchstart",e.__internal.beginResizeHandler)}function ge(e){b(e.elements.resizeHandle,"mousedown",e.__internal.beginResizeHandler),b(e.elements.resizeHandle,"touchstart",e.__internal.beginResizeHandler)}function ve(e){p(e.elements.modal,"click",e.__internal.modalClickHandler)}function ye(e){b(e.elements.modal,"click",e.__internal.modalClickHandler)}var _e,xe,ke=[],He=null,ze=e.navigator.userAgent.indexOf("Safari")>-1&&e.navigator.userAgent.indexOf("Chrome")<0,we={dimmer:'<div class="ajs-dimmer"></div>',modal:'<div class="ajs-modal" tabindex="0"></div>',dialog:'<div class="ajs-dialog" tabindex="0"></div>',reset:'<button class="ajs-reset"></button>',commands:'<div class="ajs-commands"><button class="ajs-pin"></button><button class="ajs-maximize"></button><button class="ajs-close"></button></div>',header:'<div class="ajs-header"></div>',body:'<div class="ajs-body"></div>',content:'<div class="ajs-content"></div>',footer:'<div class="ajs-footer"></div>',buttons:{primary:'<div class="ajs-primary ajs-buttons"></div>',auxiliary:'<div class="ajs-auxiliary ajs-buttons"></div>'},button:'<button class="ajs-button"></button>',resizeHandle:'<div class="ajs-handle"></div>'},Te={base:"alertify",prefix:"ajs-",hidden:"ajs-hidden",noSelection:"ajs-no-selection",noOverflow:"ajs-no-overflow",noPadding:"ajs-no-padding",modeless:"ajs-modeless",movable:"ajs-movable",resizable:"ajs-resizable",capture:"ajs-capture",fixed:"ajs-fixed",closable:"ajs-closable",maximizable:"ajs-maximizable",maximize:"ajs-maximize",restore:"ajs-restore",pinnable:"ajs-pinnable",unpinned:"ajs-unpinned",pin:"ajs-pin",maximized:"ajs-maximized",animationIn:"ajs-in",animationOut:"ajs-out",shake:"ajs-shake",basic:"ajs-basic",frameless:"ajs-frameless"},Ce=!1,Oe=!1,Ee=null,Me=0,je=0,Ne="pageX",Le="pageY",Ie=null,Ae=!1,Pe=null,Re=null,We=Number.Nan,Se=0,Be=0,Fe=0;return{__init:c,isOpen:function(){return this.__internal.isOpen},isModal:function(){return this.elements.root.className.indexOf(Te.modeless)<0},isMaximized:function(){return this.elements.root.className.indexOf(Te.maximized)>-1},isPinned:function(){return this.elements.root.className.indexOf(Te.unpinned)<0},maximize:function(){return this.isMaximized()||N(this),this},restore:function(){return this.isMaximized()&&L(this),this},pin:function(){return this.isPinned()||M(this),this},unpin:function(){return this.isPinned()&&j(this),this},bringToFront:function(){return w(null,this),this},moveTo:function(e,t){if(!isNaN(e)&&!isNaN(t)){var n=this.elements.dialog,s=n,o=0,a=0;n.style.left&&(o-=parseInt(n.style.left,10)),n.style.top&&(a-=parseInt(n.style.top,10));do o+=s.offsetLeft,a+=s.offsetTop;while(s=s.offsetParent);var l=e-o,r=t-a;i()&&(l*=-1),n.style.left=l+"px",n.style.top=r+"px"}return this},resizeTo:function(e,t){var n=parseFloat(e),i=parseFloat(t),s=/(\d*\.\d+|\d+)%/;if(!isNaN(n)&&!isNaN(i)&&this.get("resizable")===!0){(""+e).match(s)&&(n=n/100*document.documentElement.clientWidth),(""+t).match(s)&&(i=i/100*document.documentElement.clientHeight);var o=this.elements.dialog;"none"!==o.style.maxWidth&&(o.style.minWidth=(Be=o.offsetWidth)+"px"),o.style.maxWidth="none",o.style.minHeight=this.elements.header.offsetHeight+this.elements.footer.offsetHeight+"px",o.style.width=n+"px",o.style.height=i+"px"}return this},setting:function(e,t){var n=this,i=C(this,this.__internal.options,function(e,t,i){T(n,e,t,i)},e,t);if("get"===i.op)return i.found?i.value:"undefined"!=typeof this.settings?C(this,this.settings,this.settingUpdated||function(){},e,t).value:void 0;if("set"===i.op){if(i.items.length>0)for(var s=this.settingUpdated||function(){},o=0;o<i.items.length;o+=1){var a=i.items[o];a.found||"undefined"==typeof this.settings||C(this,this.settings,s,a.key,a.value)}return this}},set:function(e,t){return this.setting(e,t),this},get:function(e){return this.setting(e)},setHeader:function(t){return"string"==typeof t?(a(this.elements.header),this.elements.header.innerHTML=t):t instanceof e.HTMLElement&&this.elements.header.firstChild!==t&&(a(this.elements.header),this.elements.header.appendChild(t)),this},setContent:function(t){return"string"==typeof t?(a(this.elements.content),this.elements.content.innerHTML=t):t instanceof e.HTMLElement&&this.elements.content.firstChild!==t&&(a(this.elements.content),this.elements.content.appendChild(t)),this},showModal:function(e){return this.show(!0,e)},show:function(e,i){if(c(this),this.__internal.isOpen){te(this),le(this),t(this.elements.dialog,Te.shake);var s=this;setTimeout(function(){n(s.elements.dialog,Te.shake)},200)}else{if(this.__internal.isOpen=!0,h.push(this),_.defaults.maintainFocus&&(this.__internal.activeElement=document.activeElement),"function"==typeof this.prepare&&this.prepare(),ue(this),void 0!==e&&this.set("modal",e),f(),y(),"string"==typeof i&&""!==i&&(this.__internal.className=i,t(this.elements.root,i)),this.get("startMaximized")?this.maximize():this.isMaximized()&&L(this),R(this),n(this.elements.root,Te.animationOut),t(this.elements.root,Te.animationIn),clearTimeout(this.__internal.timerIn),this.__internal.timerIn=setTimeout(this.__internal.transitionInHandler,g.supported?1e3:100),ze){var o=this.elements.root;o.style.display="none",setTimeout(function(){o.style.display="block"},0)}He=this.elements.root.offsetWidth,n(this.elements.root,Te.hidden),"function"==typeof this.hooks.onshow&&this.hooks.onshow.call(this),"function"==typeof this.get("onshow")&&this.get("onshow").call(this)}return this},close:function(){return this.__internal.isOpen&&(ce(this),n(this.elements.root,Te.animationIn),t(this.elements.root,Te.animationOut),clearTimeout(this.__internal.timerOut),this.__internal.timerOut=setTimeout(this.__internal.transitionOutHandler,g.supported?1e3:100),t(this.elements.root,Te.hidden),He=this.elements.modal.offsetWidth,"undefined"!=typeof this.__internal.className&&""!==this.__internal.className&&n(this.elements.root,this.__internal.className),"function"==typeof this.hooks.onclose&&this.hooks.onclose.call(this),"function"==typeof this.get("onclose")&&this.get("onclose").call(this),h.splice(h.indexOf(this),1),this.__internal.isOpen=!1,y()),this},closeOthers:function(){return _.closeAll(this),this},destroy:function(){return this.__internal.isOpen?(this.__internal.destroy=function(){r(this,c)},this.close()):r(this,c),this}}}(),y=function(){function i(e){e.__internal||(e.__internal={position:_.defaults.notifier.position,delay:_.defaults.notifier.delay},c=document.createElement("DIV"),l(e)),c.parentNode!==document.body&&document.body.appendChild(c)}function s(e){e.__internal.pushed=!0,m.push(e)}function o(e){m.splice(m.indexOf(e),1),e.__internal.pushed=!1}function l(e){switch(c.className=f.base,e.__internal.position){case"top-right":t(c,f.top+" "+f.right);break;case"top-left":t(c,f.top+" "+f.left);break;case"bottom-left":t(c,f.bottom+" "+f.left);break;default:case"bottom-right":t(c,f.bottom+" "+f.right)}}function r(i,l){function r(e,t){t.dismiss(!0)}function m(e,t){b(t.element,g.type,m),c.removeChild(t.element)}function h(e){return e.__internal||(e.__internal={pushed:!1,delay:void 0,timer:void 0,clickHandler:void 0,transitionEndHandler:void 0,transitionTimeout:void 0},e.__internal.clickHandler=d(e,r),e.__internal.transitionEndHandler=d(e,m)),e}function v(e){clearTimeout(e.__internal.timer),clearTimeout(e.__internal.transitionTimeout)}return h({element:i,push:function(e,n){if(!this.__internal.pushed){s(this),v(this);var i,o;switch(arguments.length){case 0:o=this.__internal.delay;break;case 1:"number"==typeof e?o=e:(i=e,o=this.__internal.delay);break;case 2:i=e,o=n}return"undefined"!=typeof i&&this.setContent(i),y.__internal.position.indexOf("top")<0?c.appendChild(this.element):c.insertBefore(this.element,c.firstChild),u=this.element.offsetWidth,t(this.element,f.visible),p(this.element,"click",this.__internal.clickHandler),this.delay(o)}return this},ondismiss:function(){},callback:l,dismiss:function(e){return this.__internal.pushed&&(v(this),("function"!=typeof this.ondismiss||this.ondismiss.call(this)!==!1)&&(b(this.element,"click",this.__internal.clickHandler),"undefined"!=typeof this.element&&this.element.parentNode===c&&(this.__internal.transitionTimeout=setTimeout(this.__internal.transitionEndHandler,g.supported?1e3:100),n(this.element,f.visible),"function"==typeof this.callback&&this.callback.call(this,e)),o(this))),this},delay:function(e){if(v(this),this.__internal.delay="undefined"==typeof e||isNaN(+e)?y.__internal.delay:+e,this.__internal.delay>0){var t=this;this.__internal.timer=setTimeout(function(){t.dismiss()},1e3*this.__internal.delay)}return this},setContent:function(t){return"string"==typeof t?(a(this.element),this.element.innerHTML=t):t instanceof e.HTMLElement&&this.element.firstChild!==t&&(a(this.element),this.element.appendChild(t)),this},dismissOthers:function(){return y.dismissAll(this),this}})}var u,c,m=[],f={base:"alertify-notifier",message:"ajs-message",top:"ajs-top",right:"ajs-right",bottom:"ajs-bottom",left:"ajs-left",visible:"ajs-visible",hidden:"ajs-hidden"};return{setting:function(e,t){if(i(this),"undefined"==typeof t)return this.__internal[e];switch(e){case"position":this.__internal.position=t,l(this);break;case"delay":this.__internal.delay=t}return this},set:function(e,t){return this.setting(e,t),this},get:function(e){return this.setting(e)},create:function(e,t){i(this);var n=document.createElement("div");return n.className=f.message+("string"==typeof e&&""!==e?" ajs-"+e:""),r(n,t)},dismissAll:function(e){for(var t=m.slice(0),n=0;n<t.length;n+=1){var i=t[n];(void 0===e||e!==i)&&i.dismiss()}}}}(),_=new c;_.dialog("alert",function(){return{main:function(e,t,n){var i,s,o;switch(arguments.length){case 1:s=e;break;case 2:"function"==typeof t?(s=e,o=t):(i=e,s=t);break;case 3:i=e,s=t,o=n}return this.set("title",i),this.set("message",s),this.set("onok",o),this},setup:function(){return{buttons:[{text:_.defaults.glossary.ok,key:m.ESC,invokeOnClose:!0,className:_.defaults.theme.ok}],focus:{element:0,select:!1},options:{maximizable:!1,resizable:!1}}},build:function(){},prepare:function(){},setMessage:function(e){this.setContent(e)},settings:{message:void 0,onok:void 0,label:void 0},settingUpdated:function(e,t,n){switch(e){case"message":this.setMessage(n);break;case"label":this.__internal.buttons[0].element&&(this.__internal.buttons[0].element.innerHTML=n)}},callback:function(e){if("function"==typeof this.get("onok")){var t=this.get("onok").call(this,e);"undefined"!=typeof t&&(e.cancel=!t)}}}}),_.dialog("confirm",function(){function e(e){null!==n.timer&&(clearInterval(n.timer),n.timer=null,e.__internal.buttons[n.index].element.innerHTML=n.text)}function t(t,i,s){e(t),n.duration=s,n.index=i,n.text=t.__internal.buttons[i].element.innerHTML,n.timer=setInterval(d(t,n.task),1e3),n.task(null,t)}var n={timer:null,index:null,text:null,duration:null,task:function(t,i){if(i.isOpen()){if(i.__internal.buttons[n.index].element.innerHTML=n.text+" (&#8207;"+n.duration+"&#8207;) ",n.duration-=1,-1===n.duration){e(i);var s=i.__internal.buttons[n.index],o=u(n.index,s);"function"==typeof i.callback&&i.callback.apply(i,[o]),o.close!==!1&&i.close()}}else e(i)}};return{main:function(e,t,n,i){var s,o,a,l;switch(arguments.length){case 1:o=e;break;
case 2:o=e,a=t;break;case 3:o=e,a=t,l=n;break;case 4:s=e,o=t,a=n,l=i}return this.set("title",s),this.set("message",o),this.set("onok",a),this.set("oncancel",l),this},setup:function(){return{buttons:[{text:_.defaults.glossary.ok,key:m.ENTER,className:_.defaults.theme.ok},{text:_.defaults.glossary.cancel,key:m.ESC,invokeOnClose:!0,className:_.defaults.theme.cancel}],focus:{element:0,select:!1},options:{maximizable:!1,resizable:!1}}},build:function(){},prepare:function(){},setMessage:function(e){this.setContent(e)},settings:{message:null,labels:null,onok:null,oncancel:null,defaultFocus:null,reverseButtons:null},settingUpdated:function(e,t,n){switch(e){case"message":this.setMessage(n);break;case"labels":"ok"in n&&this.__internal.buttons[0].element&&(this.__internal.buttons[0].text=n.ok,this.__internal.buttons[0].element.innerHTML=n.ok),"cancel"in n&&this.__internal.buttons[1].element&&(this.__internal.buttons[1].text=n.cancel,this.__internal.buttons[1].element.innerHTML=n.cancel);break;case"reverseButtons":n===!0?this.elements.buttons.primary.appendChild(this.__internal.buttons[0].element):this.elements.buttons.primary.appendChild(this.__internal.buttons[1].element);break;case"defaultFocus":this.__internal.focus.element="ok"===n?0:1}},callback:function(t){e(this);var n;switch(t.index){case 0:"function"==typeof this.get("onok")&&(n=this.get("onok").call(this,t),"undefined"!=typeof n&&(t.cancel=!n));break;case 1:"function"==typeof this.get("oncancel")&&(n=this.get("oncancel").call(this,t),"undefined"!=typeof n&&(t.cancel=!n))}},autoOk:function(e){return t(this,0,e),this},autoCancel:function(e){return t(this,1,e),this}}}),_.dialog("prompt",function(){var t=document.createElement("INPUT"),n=document.createElement("P");return{main:function(e,t,n,i,s){var o,a,l,r,d;switch(arguments.length){case 1:a=e;break;case 2:a=e,l=t;break;case 3:a=e,l=t,r=n;break;case 4:a=e,l=t,r=n,d=i;break;case 5:o=e,a=t,l=n,r=i,d=s}return this.set("title",o),this.set("message",a),this.set("value",l),this.set("onok",r),this.set("oncancel",d),this},setup:function(){return{buttons:[{text:_.defaults.glossary.ok,key:m.ENTER,className:_.defaults.theme.ok},{text:_.defaults.glossary.cancel,key:m.ESC,invokeOnClose:!0,className:_.defaults.theme.cancel}],focus:{element:t,select:!0},options:{maximizable:!1,resizable:!1}}},build:function(){t.className=_.defaults.theme.input,t.setAttribute("type","text"),t.value=this.get("value"),this.elements.content.appendChild(n),this.elements.content.appendChild(t)},prepare:function(){},setMessage:function(t){"string"==typeof t?(a(n),n.innerHTML=t):t instanceof e.HTMLElement&&n.firstChild!==t&&(a(n),n.appendChild(t))},settings:{message:void 0,labels:void 0,onok:void 0,oncancel:void 0,value:"",type:"text",reverseButtons:void 0},settingUpdated:function(e,n,i){switch(e){case"message":this.setMessage(i);break;case"value":t.value=i;break;case"type":switch(i){case"text":case"color":case"date":case"datetime-local":case"email":case"month":case"number":case"password":case"search":case"tel":case"time":case"week":t.type=i;break;default:t.type="text"}break;case"labels":i.ok&&this.__internal.buttons[0].element&&(this.__internal.buttons[0].element.innerHTML=i.ok),i.cancel&&this.__internal.buttons[1].element&&(this.__internal.buttons[1].element.innerHTML=i.cancel);break;case"reverseButtons":i===!0?this.elements.buttons.primary.appendChild(this.__internal.buttons[0].element):this.elements.buttons.primary.appendChild(this.__internal.buttons[1].element)}},callback:function(e){var n;switch(e.index){case 0:this.settings.value=t.value,"function"==typeof this.get("onok")&&(n=this.get("onok").call(this,e,this.settings.value),"undefined"!=typeof n&&(e.cancel=!n));break;case 1:"function"==typeof this.get("oncancel")&&(n=this.get("oncancel").call(this,e),"undefined"!=typeof n&&(e.cancel=!n))}}}}),"object"==typeof module&&"object"==typeof module.exports?module.exports=_:"function"==typeof define&&define.amd?define([],function(){return _}):e.alertify||(e.alertify=_)}("undefined"!=typeof window?window:this);