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

const currencyContainer = document.querySelector('.j-currency-container');
const inputs = currencyContainer.querySelectorAll('input, select');

inputs.forEach((e) => {
  e.addEventListener('change', (e) => {
    toggleCurrencyExample();
  });
});

toggleCurrencyExample();

function toggleCurrencyExample() {
  const example = document.querySelector('.j-currency-example');

  const currencyData = {
    code: document.querySelector('#input-item-code').value,
    sign: document.querySelector('#input-item-sign').value,
    signPosition: document.querySelector('#input-item-sign_position').value,
    decimalPlace: document.querySelector('#input-item-decimal_place').value,
    decimalPoint: document.querySelector('#input-item-decimal_point').value,
    numSeparator: document.querySelector('#input-item-num_separator').value,
    space: document.querySelector('#input-item-space').checked,
  };

  const space = currencyData.space ? ' ' : '';

  let n = u.numberFormat(
    123456.7898,
    Number(currencyData.decimalPlace),
    currencyData.decimalPoint,
    currencyData.numSeparator
  );

  if (currencyData.signPosition === 'start') {
    n = currencyData.sign + space + n;
  } else {
    n += space + currencyData.sign;
  }

  n = currencyData.code + ' ' + n;

  example.textContent = n;
}
