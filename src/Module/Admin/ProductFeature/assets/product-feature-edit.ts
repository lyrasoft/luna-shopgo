
import { useProductFeatureEditApp } from '@lyrasoft/shopgo';
import {
  data,
  useBs5Tooltip,
  useDisableIfStackNotEmpty,
  useDisableOnSubmit,
  useFormComponent,
  useFormValidation,
  useKeepAlive,
} from '@windwalker-io/unicorn-next';

const formSelector = '#admin-form';

useBs5Tooltip();

useFormComponent(formSelector);

useFormValidation().then(() => useDisableOnSubmit(formSelector));

useDisableIfStackNotEmpty();

useKeepAlive(location.href);

const app = await useProductFeatureEditApp({ options: data('options') });

app.mount('product-feature-edit-app');
