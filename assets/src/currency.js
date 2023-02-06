/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

export class Currency {
  /**
   * Translate from PHP Currency::formatPrice() by ChatGPT
   *
   * @param {Number|String} num
   * @param {Boolean} addCode
   * @returns {string}
   */
  static format(num, addCode = false) {
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
}

window.Currency = Currency;
