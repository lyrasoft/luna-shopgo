System.register([], function (_export, _context) {
  "use strict";

  return {
    setters: [],
    execute: function () {
      /**
       * Part of shopgo project.
       *
       * @copyright  Copyright (C) 2023 __ORGANIZATION__.
       * @license    __LICENSE__
       */

      window.ShopgoVueUtilities = class ShopgoVueUtilities {
        /**
         * @template {[name: string]: any} T
         * @param {T} item
         * @param {(item: T) => T} callback
         * @returns {object}
         */
        static prepareVueItem(item) {
          let callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
          if (callback) {
            item = callback(item) || item;
          }
          item.uid = item.uid || u.tid();
          return item;
        }

        /**
         * @template {[name: string]: any} T
         * @param {T[]} items
         * @param {(item: T) => T} callback
         * @returns {object[]}
         */
        static prepareVueItemList(items) {
          let callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
          return items.map(item => this.prepareVueItem(item, callback));
        }

        /**
         * Merge recursive but ignore some values, Based on ChatGPT, modified by Simon.
         *
         * @template {[name: string]: any} T
         * @param {T} obj1
         * @param {object} obj2
         * @param {any[]} ignoreValues
         * @returns {T}
         */
        static mergeRecursive(obj1, obj2) {
          let ignoreValues = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [null, undefined, ''];
          for (let p in obj2) {
            try {
              if (ignoreValues.includes(obj2[p])) {
                continue;
              }

              // Property in destination object set; update its value.
              if (obj2[p].constructor === Object) {
                obj1[p] = this.mergeRecursive(obj1[p], obj2[p]);
              } else {
                obj1[p] = obj2[p];
              }
            } catch (e) {
              // Property in destination object not set; create it and set its value.
              obj1[p] = obj2[p];
            }
          }
          return obj1;
        }
      };
      window.ShopGoVuePlugin = function (app) {
        app.config.compilerOptions.whitespace = 'preserve';
        app.config.compilerOptions.isCustomElement = tag => {
          return ['uni-flatpickr'].includes(tag);
        };
        app.config.globalProperties.$lang = function (id) {
          for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
            args[_key - 1] = arguments[_key];
          }
          return u.__(id, ...args);
        };
        app.config.globalProperties.$numberFormat = function (num) {
          let prefix = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
          const negative = num < 0;
          let formatted = prefix + u.numberFormat(Math.abs(num));
          if (negative) {
            formatted = '-' + formatted;
          }
          return formatted;
        };
        app.config.globalProperties.$offsetFormat = function (num) {
          let prefix = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
          const negative = num < 0;
          let formatted = prefix + u.numberFormat(Math.abs(num));
          if (negative) {
            formatted = '-' + formatted;
          } else {
            formatted = '+' + formatted;
          }
          return formatted;
        };
        app.config.globalProperties.$priceOffset = (num, method) => {
          const negative = num < 0;
          if (method === 'fixed') {
            return '=' + u.numberFormat(Math.abs(num));
          }
          if (method === 'offsets') {
            if (negative) {
              return '-' + u.numberFormat(Math.abs(num));
            }
            return '+' + u.numberFormat(Math.abs(num));
          }
          if (method === 'percentage') {
            if (num > 100) {
              num = 100;
            }
            return num + '%';
          }
          return num;
        };
        app.config.globalProperties.$formatPrice = function (num) {
          let addCode = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
          return Currency.format(num, addCode);
        };
      };

      // Directive

      // Colorpicker
      window.ShopGoVuePlugin.Colorpicker = {
        async mounted(el, _ref) {
          let {
            value
          } = _ref;
          await u.$ui.colorPicker();
          Spectrum.getInstance(el, Object.assign({}, value));
        },
        updated(el, _ref2) {
          let {
            value
          } = _ref2;
          const sp = Spectrum.getInstance(el, options);
          if (JSON.stringify(value) !== JSON.stringify(sp.options)) {
            sp.rebuild(Object.assign({}, value));
          }
        },
        unmounted(el) {
          const sp = Spectrum.getInstance(el);
          sp.destroy();
        }
      };

      // Tooltip
      window.ShopGoVuePlugin.Tooltip = {
        async mounted(el, _ref3) {
          let {
            value
          } = _ref3;
          const inc = u.$ui.bootstrap.tooltip(el, value);
        },
        updated(el, _ref4) {
          let {
            value
          } = _ref4;
          const inc = u.$ui.bootstrap.tooltip(el, value);
          inc.update();
        },
        beforeUnmount(el) {
          const inc = u.$ui.bootstrap.tooltip(el, value);
          inc.dispose();
        }
      };

      // Tooltip
      window.ShopGoVuePlugin.TomSelect = {
        async mounted(el, _ref5) {
          let {
            value
          } = _ref5;
          const inc = u.$ui.tomSelect(el, value);
        },
        beforeUnmount(el) {
          const inc = u.$ui.tomSelect(el, value);
          inc.destroy();
        }
      };
    }
  };
});
//# sourceMappingURL=shopgo-vue-utilities.js.map
