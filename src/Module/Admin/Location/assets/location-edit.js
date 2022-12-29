/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

import '@main';

u.$ui.bootstrap.tooltip();

const formSelector = '#admin-form';

// Validation
u.formValidation().then(() => {
  u.$ui.disableOnSubmit(formSelector);
});

// Init form
u.form(formSelector).initComponent();

// Disable if uploading
u.$ui.disableIfStackNotEmpty();

// Keep Alive
u.$ui.keepAlive(location.href);

// Parent Selector
/** @type HTMLSelectElement */
const typeSelect = document.querySelector('#input-item-type');
const cascade = document.querySelector('#input-item-parent_id-wrapper .c-cascade-select');
cascade.addEventListener('change', (e) => {
  forceUpdateType();
});

document.querySelector('#input-item-type').addEventListener('change', () => {
  forceUpdateType();
});

function forceUpdateType() {
  /** @type HTMLSelectElement[] */
  let selects = u.selectAll(cascade.querySelectorAll('select.form-select'));
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
