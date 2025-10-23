

import '@main';

u.$ui.bootstrap.tooltip();

const formSelector = '#admin-form';

// Validation
u.formValidation().then(() => {
  u.$ui.disableOnSubmit(formSelector);
  u.$ui.disableOnSubmit('#state-form');
});

// Init form
u.form(formSelector).initComponent();

// Disable if uploading
u.$ui.disableIfStackNotEmpty();

// Keep Alive
u.$ui.keepAlive(location.href);
