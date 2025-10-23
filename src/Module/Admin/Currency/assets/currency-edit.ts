import { numberFormat } from '@lyrasoft/ts-toolkit/generic';
import {
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

const currencyContainer = document.querySelector('.j-currency-container')!;
const inputs = currencyContainer.querySelectorAll('input, select');

for (const input of inputs) {
  input.addEventListener('change', (e) => {
    toggleCurrencyExample();
  });
}

toggleCurrencyExample();

function toggleCurrencyExample() {
  const example = document.querySelector('.j-currency-example');

  if (!example) {
    return;
  }

  const currencyData = {
    code: document.querySelector<HTMLInputElement>('#input-item-code')!.value,
    sign: document.querySelector<HTMLInputElement>('#input-item-sign')!.value,
    signPosition: document.querySelector<HTMLInputElement>('#input-item-sign_position')!.value,
    decimalPlace: document.querySelector<HTMLInputElement>('#input-item-decimal_place')!.value,
    decimalPoint: document.querySelector<HTMLInputElement>('#input-item-decimal_point')!.value,
    numSeparator: document.querySelector<HTMLInputElement>('#input-item-num_separator')!.value,
    space: document.querySelector<HTMLInputElement>('#input-item-space')!.checked,
  };

  const space = currencyData.space ? ' ' : '';

  let n = numberFormat(
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
