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
        static isSubCurrency() {
          return this.getCurrentCurrency().code !== this.getMainCurrency().code;
        }
        static getCurrentCurrency() {
          return u.data('currency').current;
        }
        static getMainCurrency() {
          return u.data('currency').main;
        }

        /**
         * Translate from PHP Currency::formatPrice() by ChatGPT
         *
         * @param {Number|String} num
         * @param {*} currency
         * @param {Boolean} addCode
         * @returns {string}
         */
        static format(num) {
          let currency = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : undefined;
          let addCode = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
          currency = currency || this.getCurrentCurrency();
          let negative = num < 0;
          num = Math.abs(num);
          num = this.exchange(num, currency);
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
        static formatMainCurrency(num) {
          let addCode = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
          return this.format(num, this.getMainCurrency(), addCode);
        }
        static exchange(num, currency) {
          return num * currency.exchangeRate;
        }
      });
      window.Currency = Currency;
    }
  };
});
//# sourceMappingURL=currency.js.map
