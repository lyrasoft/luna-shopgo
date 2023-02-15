System.register([], function (_export, _context) {
  "use strict";

  var VariantsFlatList;
  function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
  function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return typeof key === "symbol" ? key : String(key); }
  function _toPrimitive(input, hint) { if (typeof input !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (typeof res !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
  return {
    setters: [],
    execute: function () {
      /**
       * Part of shopgo project.
       *
       * @copyright  Copyright (C) 2023 __ORGANIZATION__.
       * @license    __LICENSE__
       */

      u.directive('variants-flat-list', {
        mounted(el, _ref) {
          let {
            value
          } = _ref;
          value = JSON.parse(value || '[]') || [];
          u.module(el, 'variants.flat.list', () => new VariantsFlatList(el, value));
        }
      });
      VariantsFlatList = class VariantsFlatList {
        constructor(el, value) {
          _defineProperty(this, "el", void 0);
          _defineProperty(this, "value", []);
          this.value = value;
          this.el = el;
          this.productInput = u.selectOne(this.el.dataset.productSelector);

          // this.productInput.addEventListener('change', () => {
          //   this.updateVariantsList();
          // });

          this.updateVariantsList();
        }
        updateVariantsList() {}
      };
    }
  };
});
//# sourceMappingURL=variants-flat-list.js.map
