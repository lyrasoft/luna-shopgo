import { numberFormat } from '@lyrasoft/ts-toolkit/generic';
import { data } from '@windwalker-io/unicorn-next';
import { Currency } from '~shopgo/types';

interface CurrencyInfo {
  main: Currency;
  current: Currency;
}

export function useCurrency() {
  function getCurrentCurrency(): Currency {
    return data<CurrencyInfo>('currency')!.current;
  }

  function getMainCurrency(): Currency {
    return data<CurrencyInfo>('currency')!.main;
  }

  function isSubCurrency(): boolean {
    return getCurrentCurrency().code !== getMainCurrency().code;
  }

  function exchange(num: number, currency: any): number {
    return num * currency.exchangeRate;
  }

  function format(num: number | string, currency?: Currency, addCode: boolean = false): string {
    // normalize number
    let n = typeof num === 'string' ? parseFloat(num) : (num as number);
    if (Number.isNaN(n)) {
      n = 0;
    }

    const currencyObj = currency || getCurrentCurrency();

    const negative = n < 0;
    n = Math.abs(n);

    n = exchange(n, currencyObj);

    let formatted = numberFormat(n, currencyObj.decimalPlace, currencyObj.decimalPoint);

    const space = currencyObj.space ? ' ' : '';

    if (currencyObj.signPosition === 'start') {
      formatted = currencyObj.sign + space + formatted;
    } else {
      formatted += space + currencyObj.sign;
    }

    if (negative) {
      return '-' + formatted;
    }

    if (addCode) {
      formatted = currencyObj.code + " " + formatted;
    }

    return formatted;
  }

  function formatMainCurrency(num: number | string, addCode: boolean = false): string {
    return format(num, getMainCurrency(), addCode);
  }

  return {
    isSubCurrency,
    getCurrentCurrency,
    getMainCurrency,
    format,
    formatMainCurrency,
    exchange,
  };
}
