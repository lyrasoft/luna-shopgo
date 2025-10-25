import { useFieldFlatpickr } from '@windwalker-io/unicorn-next';
import { createApp } from 'vue';
import ProductVariantsEditApp from '~shopgo/modules/product-edit/ProductVariantsEditApp.vue';
import { ShopGoPlugin } from '~shopgo/shopgo-plugin';

export function initApp(props: Record<string, any>) {
  const app = createApp(ProductVariantsEditApp, props);

  useFieldFlatpickr();

  app.use(ShopGoPlugin);

  return app;
}

