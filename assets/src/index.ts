export * from './directives';
export * from './shopgo-plugin';
export * from './services';
export * from './utilities';

export async function useAdditionalPurchaseAttachmentEditApp(props: Record<string, any> = {}) {
  const { initApp } = await import('~shopgo/modules/additional-purchase/additional-purchase-attachment-edit');

  return initApp(props);
}

export async function useProductDiscountsEditApp(props: Record<string, any> = {}) {
  const { initApp } = await import('~shopgo/modules/product-edit/product-discounts-edit');

  return initApp(props);
}

export async function useProductVariantsEditApp(props: Record<string, any> = {}) {
  const { initApp } = await import('~shopgo/modules/product-edit/product-variants-edit');

  return initApp(props);
}

export async function useProductAttributeEditApp(props: Record<string, any> = {}) {
  const { initApp } = await import('~shopgo/modules/product-attribute/product-attribute-edit');

  return initApp(props);
}

export async function useProductFeatureEditApp(props: Record<string, any> = {}) {
  const { initApp } = await import('~shopgo/modules/product-feature/product-feature-edit');

  return initApp(props);
}
