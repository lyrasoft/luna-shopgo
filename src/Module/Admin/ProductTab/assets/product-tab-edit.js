

import '@main';

u.$ui.bootstrap.tooltip();

const formSelector = '#admin-form';

u.$ui.bootstrap.keepTab(formSelector);

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
