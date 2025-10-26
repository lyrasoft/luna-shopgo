import {
  __, data,
  route,
  simpleAlert,
  useBs5Tooltip,
  useDisableIfStackNotEmpty,
  useDisableOnSubmit,
  useFormComponent,
  useFormValidation, useHttpClient, useIframeModal,
  useKeepAlive, useTomSelect,
} from '@windwalker-io/unicorn-next';
import { useAdditionalPurchaseAttachmentEditApp } from '@lyrasoft/shopgo';
import Test from '~/src/Test.vue';

const formSelector = '#admin-form';

useBs5Tooltip();

useFormComponent(formSelector);

useFormValidation().then(() => useDisableOnSubmit(formSelector));

useDisableIfStackNotEmpty();

useKeepAlive(location.href);

useTomSelect('.has-tom-select');

// Attachment Edit
const app = await useAdditionalPurchaseAttachmentEditApp(data('ap.attachments.props'));
// app.component('AttachmentProduct', Test)
app.mount('additional-purchase-attachments-app');
