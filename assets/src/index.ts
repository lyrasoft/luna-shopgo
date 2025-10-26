import { useFormAsync, useMacro } from '@windwalker-io/unicorn-next';
import { useCurrencySwitcher, useProductCartButtons } from '~shopgo/services';

export * from './directives';
export * from './shopgo-plugin';
export * from './services';
export * from './utilities';
export * from './types';

export function useShopGoCatalog() {
  return useMacro('$shopgo', {
    useProductCartButtons,
    useCurrencySwitcher,
  });
}

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

export async function useCartApp(props: Record<string, any> = {}) {
  const { initApp } = await import('~shopgo/modules/cart/cart');

  return initApp(props);
}
