import { useProductDiscountsEditApp, useProductVariantsEditApp } from '@lyrasoft/shopgo';
import {
  data,
  useBs5Tooltip,
  useDisableIfStackNotEmpty,
  useDisableOnSubmit,
  useFormComponent,
  useFormValidation,
  useKeepAlive,
  useTomSelect,
} from '@windwalker-io/unicorn-next';

const formSelector = '#admin-form';

useBs5Tooltip();

useFormComponent(formSelector);

useFormValidation().then(() => useDisableOnSubmit(formSelector));

useDisableIfStackNotEmpty();

useKeepAlive(location.href);

useTomSelect('.has-tom-select');

// Tags
useTomSelect('#input-item-tags', {
  create: (input: string) => {
    return {
      value: `new#${input}`,
      text: input,
    };
  },
});

// Discount
useProductDiscountsEditApp(data('product.discounts.props')).then((app) => {
  app.mount('product-discounts-edit-app');
});

// Variants
useProductVariantsEditApp(data('product.variants.props')).then((app) => {
  app.mount('product-variants-edit-app');
});
