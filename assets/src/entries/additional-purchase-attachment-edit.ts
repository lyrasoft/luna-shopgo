import { createApp } from 'vue';
import AdditionalPurchaseAttachmentEditApp from '~shopgo/app/AdditionalPurchaseAttachmentEditApp.vue';
import { ShopGoPlugin } from '~shopgo/shopgo-plugin';

export function init(el: Element | string, props: Record<string, any>) {
  const app = createApp(AdditionalPurchaseAttachmentEditApp, props);

  app.use(ShopGoPlugin);

  return app.mount(el);
}
