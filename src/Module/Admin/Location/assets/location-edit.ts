
import {
  selectAll,
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

// Parent Selector
/** @type HTMLSelectElement */
const typeSelect = document.querySelector<HTMLInputElement>('#input-item-type')!;
const cascade = document.querySelector<HTMLElement>('#input-item-parent_id-wrapper .c-cascade-select');
cascade?.addEventListener('change', (e) => {
  forceUpdateType();
});

document.querySelector('#input-item-type')?.addEventListener('change', () => {
  forceUpdateType();
});

function forceUpdateType() {
  let selects = selectAll(cascade!.querySelectorAll<HTMLSelectElement>('select.form-select'));
  selects = selects.filter((select) => select.value !== '');
  const level = selects.length;

  if (level === 0) {
    typeSelect.value = 'continent';
  } else if (level === 1) {
    typeSelect.value = 'country';
  } else if (level === 2) {
    typeSelect.value = 'state';
  } else if (level === 3) {
    typeSelect.value = 'city';
  }
}
