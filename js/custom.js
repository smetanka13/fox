/*! modernizr 3.5.0 (Custom Build) | MIT *
 * https://modernizr.com/download/?-backgroundsize-bgpositionshorthand-bgpositionxy-bgrepeatspace_bgrepeatround-bgsizecover-borderradius-boxshadow-boxsizing-cssanimations-csscalc-csstransforms-csstransitions-cubicbezierrange-hiddenscroll-inputtypes-multiplebgs-opacity-overflowscrolling-queryselector-rgba-search-svg-textshadow-video-setclasses !*/
!function(e,t,n){function r(e,t){return typeof e===t}function o(){var e,t,n,o,s,i,a;for(var l in x)if(x.hasOwnProperty(l)){if(e=[],t=x[l],t.name&&(e.push(t.name.toLowerCase()),t.options&&t.options.aliases&&t.options.aliases.length))for(n=0;n<t.options.aliases.length;n++)e.push(t.options.aliases[n].toLowerCase());for(o=r(t.fn,"function")?t.fn():t.fn,s=0;s<e.length;s++)i=e[s],a=i.split("."),1===a.length?Modernizr[a[0]]=o:(!Modernizr[a[0]]||Modernizr[a[0]]instanceof Boolean||(Modernizr[a[0]]=new Boolean(Modernizr[a[0]])),Modernizr[a[0]][a[1]]=o),b.push((o?"":"no-")+a.join("-"))}}function s(e){var t=w.className,n=Modernizr._config.classPrefix||"";if(S&&(t=t.baseVal),Modernizr._config.enableJSClass){var r=new RegExp("(^|\\s)"+n+"no-js(\\s|$)");t=t.replace(r,"$1"+n+"js$2")}Modernizr._config.enableClasses&&(t+=" "+n+e.join(" "+n),S?w.className.baseVal=t:w.className=t)}function i(){return"function"!=typeof t.createElement?t.createElement(arguments[0]):S?t.createElementNS.call(t,"http://www.w3.org/2000/svg",arguments[0]):t.createElement.apply(t,arguments)}function a(){var e=t.body;return e||(e=i(S?"svg":"body"),e.fake=!0),e}function l(e,n,r,o){var s,l,c,d,u="modernizr",p=i("div"),f=a();if(parseInt(r,10))for(;r--;)c=i("div"),c.id=o?o[r]:u+(r+1),p.appendChild(c);return s=i("style"),s.type="text/css",s.id="s"+u,(f.fake?f:p).appendChild(s),f.appendChild(p),s.styleSheet?s.styleSheet.cssText=e:s.appendChild(t.createTextNode(e)),p.id=u,f.fake&&(f.style.background="",f.style.overflow="hidden",d=w.style.overflow,w.style.overflow="hidden",w.appendChild(f)),l=n(p,e),f.fake?(f.parentNode.removeChild(f),w.style.overflow=d,w.offsetHeight):p.parentNode.removeChild(p),!!l}function c(e,t){return!!~(""+e).indexOf(t)}function d(e){return e.replace(/([a-z])-([a-z])/g,function(e,t,n){return t+n.toUpperCase()}).replace(/^-/,"")}function u(e,t){return function(){return e.apply(t,arguments)}}function p(e,t,n){var o;for(var s in e)if(e[s]in t)return n===!1?e[s]:(o=t[e[s]],r(o,"function")?u(o,n||t):o);return!1}function f(e){return e.replace(/([A-Z])/g,function(e,t){return"-"+t.toLowerCase()}).replace(/^ms-/,"-ms-")}function g(t,n,r){var o;if("getComputedStyle"in e){o=getComputedStyle.call(e,t,n);var s=e.console;if(null!==o)r&&(o=o.getPropertyValue(r));else if(s){var i=s.error?"error":"log";s[i].call(s,"getComputedStyle returning null, its possible modernizr test results are inaccurate")}}else o=!n&&t.currentStyle&&t.currentStyle[r];return o}function v(t,r){var o=t.length;if("CSS"in e&&"supports"in e.CSS){for(;o--;)if(e.CSS.supports(f(t[o]),r))return!0;return!1}if("CSSSupportsRule"in e){for(var s=[];o--;)s.push("("+f(t[o])+":"+r+")");return s=s.join(" or "),l("@supports ("+s+") { #modernizr { position: absolute; } }",function(e){return"absolute"==g(e,null,"position")})}return n}function m(e,t,o,s){function a(){u&&(delete q.style,delete q.modElem)}if(s=r(s,"undefined")?!1:s,!r(o,"undefined")){var l=v(e,o);if(!r(l,"undefined"))return l}for(var u,p,f,g,m,y=["modernizr","tspan","samp"];!q.style&&y.length;)u=!0,q.modElem=i(y.shift()),q.style=q.modElem.style;for(f=e.length,p=0;f>p;p++)if(g=e[p],m=q.style[g],c(g,"-")&&(g=d(g)),q.style[g]!==n){if(s||r(o,"undefined"))return a(),"pfx"==t?g:!0;try{q.style[g]=o}catch(h){}if(q.style[g]!=m)return a(),"pfx"==t?g:!0}return a(),!1}function y(e,t,n,o,s){var i=e.charAt(0).toUpperCase()+e.slice(1),a=(e+" "+N.join(i+" ")+i).split(" ");return r(t,"string")||r(t,"undefined")?m(a,t,o,s):(a=(e+" "+$.join(i+" ")+i).split(" "),p(a,t,n))}function h(e,t,r){return y(e,n,n,t,r)}var b=[],x=[],T={_version:"3.5.0",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,t){var n=this;setTimeout(function(){t(n[e])},0)},addTest:function(e,t,n){x.push({name:e,fn:t,options:n})},addAsyncTest:function(e){x.push({name:null,fn:e})}},Modernizr=function(){};Modernizr.prototype=T,Modernizr=new Modernizr,Modernizr.addTest("queryselector","querySelector"in t&&"querySelectorAll"in t),Modernizr.addTest("svg",!!t.createElementNS&&!!t.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect);var w=t.documentElement,S="svg"===w.nodeName.toLowerCase();Modernizr.addTest("video",function(){var e=i("video"),t=!1;try{t=!!e.canPlayType,t&&(t=new Boolean(t),t.ogg=e.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),t.h264=e.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),t.webm=e.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,""),t.vp9=e.canPlayType('video/webm; codecs="vp9"').replace(/^no$/,""),t.hls=e.canPlayType('application/x-mpegURL; codecs="avc1.42E01E"').replace(/^no$/,""))}catch(n){}return t}),Modernizr.addTest("multiplebgs",function(){var e=i("a").style;return e.cssText="background:url(https://),url(https://),red url(https://)",/(url\s*\(.*?){3}/.test(e.background)}),Modernizr.addTest("rgba",function(){var e=i("a").style;return e.cssText="background-color:rgba(150,255,150,.5)",(""+e.backgroundColor).indexOf("rgba")>-1}),Modernizr.addTest("bgpositionshorthand",function(){var e=i("a"),t=e.style,n="right 10px bottom 10px";return t.cssText="background-position: "+n+";",t.backgroundPosition===n});var C=function(){function e(e,t){var o;return e?(t&&"string"!=typeof t||(t=i(t||"div")),e="on"+e,o=e in t,!o&&r&&(t.setAttribute||(t=i("div")),t.setAttribute(e,""),o="function"==typeof t[e],t[e]!==n&&(t[e]=n),t.removeAttribute(e)),o):!1}var r=!("onblur"in t.documentElement);return e}();T.hasEvent=C,Modernizr.addTest("inputsearchevent",C("search"));var k=i("input"),P="search tel url email datetime date month week time datetime-local number range color".split(" "),z={};Modernizr.inputtypes=function(e){for(var r,o,s,i=e.length,a="1)",l=0;i>l;l++)k.setAttribute("type",r=e[l]),s="text"!==k.type&&"style"in k,s&&(k.value=a,k.style.cssText="position:absolute;visibility:hidden;",/^range$/.test(r)&&k.style.WebkitAppearance!==n?(w.appendChild(k),o=t.defaultView,s=o.getComputedStyle&&"textfield"!==o.getComputedStyle(k,null).WebkitAppearance&&0!==k.offsetHeight,w.removeChild(k)):/^(search|tel)$/.test(r)||(s=/^(url|email)$/.test(r)?k.checkValidity&&k.checkValidity()===!1:k.value!=a)),z[e[l]]=!!s;return z}(P);var _=T._config.usePrefixes?" -webkit- -moz- -o- -ms- ".split(" "):["",""];T._prefixes=_,Modernizr.addTest("csscalc",function(){var e="width:",t="calc(10px);",n=i("a");return n.style.cssText=e+_.join(t+e),!!n.style.length}),Modernizr.addTest("cubicbezierrange",function(){var e=i("a");return e.style.cssText=_.join("transition-timing-function:cubic-bezier(1,0,0,1.1); "),!!e.style.length}),Modernizr.addTest("opacity",function(){var e=i("a").style;return e.cssText=_.join("opacity:.55;"),/^0.55$/.test(e.opacity)});var E=T.testStyles=l;Modernizr.addTest("hiddenscroll",function(){return E("#modernizr {width:100px;height:100px;overflow:scroll}",function(e){return e.offsetWidth===e.clientWidth})});var A="Moz O ms Webkit",N=T._config.usePrefixes?A.split(" "):[];T._cssomPrefixes=N;var $=T._config.usePrefixes?A.toLowerCase().split(" "):[];T._domPrefixes=$;var j={elem:i("modernizr")};Modernizr._q.push(function(){delete j.elem});var q={style:j.elem.style};Modernizr._q.unshift(function(){delete q.style});var R=T.testProp=function(e,t,r){return m([e],n,t,r)};Modernizr.addTest("textshadow",R("textShadow","1px 1px")),T.testAllProps=y,T.testAllProps=h,Modernizr.addTest("cssanimations",h("animationName","a",!0)),Modernizr.addTest("bgpositionxy",function(){return h("backgroundPositionX","3px",!0)&&h("backgroundPositionY","5px",!0)}),Modernizr.addTest("bgrepeatround",h("backgroundRepeat","round")),Modernizr.addTest("bgrepeatspace",h("backgroundRepeat","space")),Modernizr.addTest("backgroundsize",h("backgroundSize","100%",!0)),Modernizr.addTest("bgsizecover",h("backgroundSize","cover")),Modernizr.addTest("borderradius",h("borderRadius","0px",!0)),Modernizr.addTest("boxshadow",h("boxShadow","1px 1px",!0)),Modernizr.addTest("boxsizing",h("boxSizing","border-box",!0)&&(t.documentMode===n||t.documentMode>7)),Modernizr.addTest("overflowscrolling",h("overflowScrolling","touch",!0)),Modernizr.addTest("csstransforms",function(){return-1===navigator.userAgent.indexOf("Android 2.")&&h("transform","scale(1)",!0)}),Modernizr.addTest("csstransitions",h("transition","all",!0)),o(),s(b),delete T.addTest,delete T.addAsyncTest;for(var V=0;V<Modernizr._q.length;V++)Modernizr._q[V]();e.Modernizr=Modernizr}(window,document);