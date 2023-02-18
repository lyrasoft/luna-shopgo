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

// Gen Code
u.selectOne('[data-task=gencode]')?.addEventListener('click', async (e) => {
  const res = await u.$http.get('@discount_ajax/gencode');

  /** @type HTMLButtonElement */
  const button = e.target;
  button.previousElementSibling.value = res.data.data;
});
