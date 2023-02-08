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

async function sendAddAction(el) {
  const productId = el.dataset.id;

  if (!productId) {
    throw new Error('No product ID');
  }

  const hash = el.dataset.hash;

  if (!hash) {
    throw new Error('No variant hash');
  }

  const qtyInput = document.querySelector('[data-role=quantity]');

  const res = await u.$http.post(
    '@cart_ajax/addToCart',
    {
      product_id: productId,
      hash,
      quantity: Number(qtyInput.value)
    }
  );

  return res.data;
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

function buy(el) {
  sendAddAction(el);

  toCartPage();
}

function toCartPage() {
  location.href = u.route('cart');
}
