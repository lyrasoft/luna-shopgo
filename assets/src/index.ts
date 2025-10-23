export * from './directives';
export * from './shopgo-plugin';
export * from './services';
export * from './utilities';

export async function useAdditionalPurchaseAttachmentEdit(el: Element | string, props: Record<string, any> = {}) {
  const { init } = await import('~shopgo/entries/additional-purchase-attachment-edit');

  return init(el, props);
}
