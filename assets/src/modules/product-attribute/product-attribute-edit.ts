import { createApp } from 'vue';
import ProductAttributeEditApp from '~shopgo/modules/product-attribute/ProductAttributeEditApp.vue';
import { ShopGoPlugin } from '~shopgo/shopgo-plugin';

export function initApp(props: Record<string, any>) {
  const app = createApp(ProductAttributeEditApp, props);

  app.use(ShopGoPlugin);

  return app;
}


