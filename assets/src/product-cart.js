/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

import '@main';

u.delegate(document, '[data-task=add-to-cart]', 'click', (e) => {
  addToCart(e.target);
});

u.delegate(document, '[data-task=buy]', 'click', (e) => {
  buy(e.target);
});

u.delegate(document, '[data-task=addon]', 'click', (e) => {
  addon(e.target);
});

async function sendAddAction(el) {
  const productId = el.dataset.id;

  if (!productId) {
    throw new Error('No product ID');
  }

  const variantId = el.dataset.variantId;

  if (!variantId) {
    throw new Error('No variant ID');
  }

  const qtyInput = document.querySelector('[data-role=quantity]');

  try {
    const res = await u.$http.post(
      '@cart_ajax/addToCart',
      {
        product_id: productId,
        variant_id: variantId,
        quantity: Number(qtyInput.value)
      }
    );

    updateCartButton(res.data.data);

    return res.data;
  } catch (e) {
    console.error(e);
    u.alert(e.message, '', 'warning');
  }
}

async function addToCart(el) {
  sendAddAction(el);

  const v = await swal({
    title: '已加入購物車',
    buttons: [
      '繼續購物',
      '前往結帳'
    ]
  });

  if (!v) {
    return;
  }

  toCartPage();
}

async function addon(el) {
  const apMapId = el.dataset.apMapId;

  try {
    const res = await u.$http.post(
      '@cart_ajax/addon',
      {
        apMapId
      }
    );

    updateCartButton(res.data.data);

    swal({ title: '已加購' });

    return res.data;
  } catch (e) {
    console.error(e);
    u.alert(e.message, '', 'warning');
  }
}

function buy(el) {
  sendAddAction(el);

  toCartPage();
}

function toCartPage() {
  location.href = u.route('cart');
}

function updateCartButton(data) {
  u.trigger('cart.update', data);

  const count = data.length;

  const $cartButtons = document.querySelectorAll('[data-role=cart-button]');

  for (const $cartButton of $cartButtons) {
    const $cartQuantity = $cartButton.querySelector('[data-role=cart-quantity]');

    $cartButton.classList.toggle('h-has-items', count > 0);
    $cartQuantity.textContent = count;
  }
}
