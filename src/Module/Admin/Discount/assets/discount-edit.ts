
import {
  useBs5Tooltip,
  useDisableIfStackNotEmpty,
  useDisableOnSubmit,
  useFormComponent,
  useFormValidation, useHttpClient,
  useKeepAlive, useTomSelect,
} from '@windwalker-io/unicorn-next';

const formSelector = '#admin-form';

useBs5Tooltip();

useFormComponent(formSelector);

useFormValidation().then(() => useDisableOnSubmit(formSelector));

useDisableIfStackNotEmpty();

useKeepAlive(location.href);

useTomSelect('.has-tom-select');

// Gen Code
document.querySelector('[data-task=gencode]')?.addEventListener('click', async (e) => {
  const { get } = await useHttpClient();

  const res = await get('@discount_ajax/gencode');

  const button = e.target as HTMLButtonElement;

  const input = button.previousElementSibling as HTMLInputElement | null;

  if (input) {
    input.value = res.data.data;
  }
});
