System.register([],(function(e,t){return{setters:[],execute:function(){window.ShopgoVueUtilities=class{static prepareVueItem(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;return t&&(e=t(e)||e),e.uid=e.uid||u.tid(),e}static prepareVueItemList(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;return e.map((e=>this.prepareVueItem(e,t)))}static mergeRecursive(e,t){let o=arguments.length>2&&void 0!==arguments[2]?arguments[2]:[null,void 0,""];for(let n in t)try{if(o.includes(t[n]))continue;t[n].constructor===Object?e[n]=this.mergeRecursive(e[n],t[n]):e[n]=t[n]}catch(o){e[n]=t[n]}return e}},window.ShopGoVuePlugin=function(e){e.config.compilerOptions.whitespace="preserve",e.config.compilerOptions.isCustomElement=e=>["uni-flatpickr"].includes(e),e.config.globalProperties.$lang=function(e){for(var t=arguments.length,o=new Array(t>1?t-1:0),n=1;n<t;n++)o[n-1]=arguments[n];return u.__(e,...o)},e.config.globalProperties.$numberFormat=function(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"";const o=e<0;let n=t+u.numberFormat(Math.abs(e));return o&&(n="-"+n),n},e.config.globalProperties.$offsetFormat=function(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"";const o=e<0;let n=t+u.numberFormat(Math.abs(e));return n=o?"-"+n:"+"+n,n},e.config.globalProperties.$priceOffset=(e,t)=>{const o=e<0;return"fixed"===t?"="+u.numberFormat(Math.abs(e)):"offsets"===t?o?"-"+u.numberFormat(Math.abs(e)):"+"+u.numberFormat(Math.abs(e)):"percentage"===t?(e>100&&(e=100),e+"%"):e},e.config.globalProperties.$formatPrice=function(e){let t=arguments.length>1&&void 0!==arguments[1]&&arguments[1];return Currency.format(e,t)}},window.ShopGoVuePlugin.Colorpicker={async mounted(e,t){let{value:o}=t;await u.$ui.colorPicker(),Spectrum.getInstance(e,Object.assign({},o))},updated(e,t){let{value:o}=t;const n=Spectrum.getInstance(e,options);JSON.stringify(o)!==JSON.stringify(n.options)&&n.rebuild(Object.assign({},o))},unmounted(e){Spectrum.getInstance(e).destroy()}},window.ShopGoVuePlugin.Tooltip={async mounted(e,t){let{value:o}=t;u.$ui.bootstrap.tooltip(e,o)},updated(e,t){let{value:o}=t;u.$ui.bootstrap.tooltip(e,o).update()},beforeUnmount(e){u.$ui.bootstrap.tooltip(e,value).dispose()}},window.ShopGoVuePlugin.TomSelect={async mounted(e,t){let{value:o}=t;u.$ui.tomSelect(e,o)},beforeUnmount(e){u.$ui.tomSelect(e,value).destroy()}}}}}));
//# sourceMappingURL=shopgo-vue-utilities.js.map