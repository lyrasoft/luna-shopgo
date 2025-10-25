import { useProductAttributeEditApp } from '@lyrasoft/shopgo';
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

const app = await useProductAttributeEditApp({ options: data('options') });
app.mount('product-attribute-edit-app');
