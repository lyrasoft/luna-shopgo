import {
  addQuery,
  useBs5Tooltip,
  useCheckboxesMultiSelect,
  useDisableOnSubmit, useGrid,
  useGridComponent,
} from '@windwalker-io/unicorn-next';

const formSelector = '#admin-form';

useBs5Tooltip();

useGridComponent(formSelector);

useDisableOnSubmit(formSelector);

useCheckboxesMultiSelect(formSelector);

// Print
document.querySelector('[data-task=print_list]')?.addEventListener('click', (e) => {
  const button = e.currentTarget as HTMLButtonElement;
  let uri = button.dataset.uri || '';
  const ids = useGrid(formSelector)!.getCheckedValues();
  
  if (ids.length) {
    uri = addQuery(uri, { id: ids });
  }

  window.open(uri);
});
