import { createApp } from 'vue';
import AdditionalPurchaseAttachmentEditApp
  from '~shopgo/modules/additional-purchase/AdditionalPurchaseAttachmentEditApp.vue';
import { ShopGoPlugin } from '~shopgo/shopgo-plugin';

export function initApp(props: Record<string, any>) {
  const app = createApp(AdditionalPurchaseAttachmentEditApp, props);

  app.use(ShopGoPlugin);

  return app;
}
