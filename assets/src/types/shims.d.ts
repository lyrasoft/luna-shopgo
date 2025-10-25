// import { ComponentCustomProperties } from 'vue'
import { __ } from '@windwalker-io/unicorn-next';

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $lang: typeof __;
    $priceOffset: (num: number, method: 'fixed' | 'offsets' | 'percentage' | string) => string;
    $offsetFormat: (num: number, prefix?: string) => string;
    $numberFormat: (num: number, prefix?: string) => string;
  }
}
