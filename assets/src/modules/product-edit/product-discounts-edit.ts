import { useFieldFlatpickr } from '@windwalker-io/unicorn-next';
import { createApp } from 'vue';
import ProductDiscountsEditApp from '~shopgo/modules/product-edit/ProductDiscountsEditApp.vue';
import { ShopGoPlugin } from '~shopgo/shopgo-plugin';

export function initApp(props: Record<string, any>) {
  const app = createApp(ProductDiscountsEditApp, props);

  useFieldFlatpickr();

  app.use(ShopGoPlugin);

  return app;
}

