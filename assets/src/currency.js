/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

export class Currency {
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
  static format(num, currency = undefined, addCode = false) {
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

  static formatMainCurrency(num, addCode = false) {
    return this.format(num, this.getMainCurrency(), addCode);
  }

  static exchange(num, currency) {
    return num * currency.exchangeRate;
  }
}

window.Currency = Currency;
