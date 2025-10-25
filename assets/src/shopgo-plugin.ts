import { __ } from '@windwalker-io/unicorn-next';
import type { App } from 'vue';
import { numberFormat } from '@lyrasoft/ts-toolkit/generic';
import { useCurrency } from '~shopgo/services/currency';

export function ShopGoPlugin(app: App) {
  app.config.compilerOptions.whitespace = 'preserve';

  app.config.compilerOptions.isCustomElement = (tag: string) => {
    return [
      'uni-flatpickr',
      'uni-iframe-modal'
    ].includes(tag);
  };

  app.config.globalProperties.$lang = (id, ...args) => {
    return __(id, ...args);
  };

  app.config.globalProperties.$numberFormat = (num: number, prefix = '') => {
    const negative = num < 0;
    let formatted = prefix + numberFormat(Math.abs(num));

    if (negative) {
      formatted = '-' + formatted;
    }

    return formatted;
  };

  app.config.globalProperties.$offsetFormat = (num: number, prefix = '') => {
    const negative = num < 0;
    let formatted = prefix + numberFormat(Math.abs(num));

    if (negative) {
      formatted = '-' + formatted;
    } else {
      formatted = '+' + formatted;
    }

    return formatted;
  };

  app.config.globalProperties.$priceOffset = (num: number, method: 'fixed' | 'offsets' | 'percentage' | string): string => {
    const negative = num < 0;

    const { format } = useCurrency({ sign: false, code: false });

    if (method === 'fixed') {
      return '=' + format(Math.abs(num));
    }

    if (method === 'offsets') {
      if (negative) {
        return '-' + format(Math.abs(num));
      }

      return '+' + format(Math.abs(num));
    }

    if (method === 'percentage') {
      if (num > 100) {
        num = 100;
      }

      return num + '%';
    }

    return String(num);
  };

  app.config.globalProperties.$formatPrice = (num: number, addCode = false) => {
    return useCurrency().format(num, undefined, addCode);
  };

  app.config.globalProperties.$currency = useCurrency();
}

