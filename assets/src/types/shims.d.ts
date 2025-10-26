// import { ComponentCustomProperties } from 'vue'
import { __ } from '@windwalker-io/unicorn-next';
import { CurrencyFormatOptions, useCurrency } from '~shopgo/services';

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $lang: typeof __;
    $priceOffset: (num: number | string, method: 'fixed' | 'offsets' | 'percentage' | string) => string;
    $offsetFormat: (num: number | string, prefix?: string) => string;
    $numberFormat: (num: number | string, prefix?: string) => string;
    $formatPrice: (value: number | string, options?: CurrencyFormatOptions) => string;
    $currency: ReturnType<typeof useCurrency>;
  }
}
