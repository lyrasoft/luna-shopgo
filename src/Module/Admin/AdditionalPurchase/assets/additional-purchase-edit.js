/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

import '@main';

u.$ui.bootstrap.tooltip();
u.$ui.tomSelect('.has-tom-select');

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

// Switch products
const productInput = document.querySelector('#input-item-attach_product_id');

productInput.addEventListener('change', () => {
  u.form(formSelector).submit(
    null,
    {
      task: 'switch_product'
    }
  );
});
