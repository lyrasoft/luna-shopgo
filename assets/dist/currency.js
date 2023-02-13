System.register([], function (_export, _context) {
  "use strict";

  var Currency;
  _export("Currency", void 0);
  return {
    setters: [],
    execute: function () {
      /**
       * Part of shopgo project.
       *
       * @copyright  Copyright (C) 2023 __ORGANIZATION__.
       * @license    __LICENSE__
       */
      _export("Currency", Currency = class Currency {
        /**
         * Translate from PHP Currency::formatPrice() by ChatGPT
         *
         * @param {Number|String} num
         * @param {Boolean} addCode
         * @returns {string}
         */
        static format(num) {
          let addCode = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
          const currency = u.data('currency');
          let negative = num < 0;
          num = Math.abs(num);
          let formatted = u.numberFormat(num, currency.decimalPlace, currency.decimalPoint);
          let space = currency.space ? ' ' : '';
          if (currency.signPosition === 'start') {
            formatted = currency.sign + space + formatted;
          } else {
            formatted += space + currency.sign;
          }
          if (negative) {
            return '-' + formatted;
          }
          if (addCode) {
            formatted = currency.code + " " + formatted;
          }
          return formatted;
        }
      });
      window.Currency = Currency;
    }
  };
});
//# sourceMappingURL=currency.js.map
