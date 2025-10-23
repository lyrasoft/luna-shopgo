// import { ComponentCustomProperties } from 'vue'
import { __ } from '@windwalker-io/unicorn-next';

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $lang: typeof __;
  }
}
