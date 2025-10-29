import { useAdditionalPurchaseAttachmentEditApp } from '@lyrasoft/shopgo';
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

// Attachment Edit
const app = await useAdditionalPurchaseAttachmentEditApp(data('ap.attachments.props'));
app.mount('additional-purchase-attachments-app');
