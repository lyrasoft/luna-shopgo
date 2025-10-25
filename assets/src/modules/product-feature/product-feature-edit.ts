import { createApp } from 'vue';
import ProductFeatureEditApp from '~shopgo/modules/product-feature/ProductFeatureEditApp.vue';
import { ShopGoPlugin } from '~shopgo/shopgo-plugin';

export function initApp(props: Record<string, any>) {
  const app = createApp(ProductFeatureEditApp, props);

  app.use(ShopGoPlugin);

  return app;
}




