System.register(["@main"], function (_export, _context) {
  "use strict";

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
    const quantity = Number((qtyInput === null || qtyInput === void 0 ? void 0 : qtyInput.value) || 1);

    // Find additional purchases
    const attachments = findAttachments();
    try {
      const res = await u.$http.post('@cart_ajax/addToCart', {
        product_id: productId,
        variant_id: variantId,
        quantity,
        attachments
      });
      updateCartButton(res.data.data);
      return res.data;
    } catch (e) {
      console.error(e);
      throw e;
    }
  }
  async function addToCart(el) {
    try {
      await sendAddAction(el);
    } catch (e) {
      u.alert(e.message, '', 'warning');
      return;
    }
    const v = await swal({
      title: '已加入購物車',
      buttons: ['繼續購物', '前往結帳']
    });
    if (!v) {
      return;
    }
    toCartPage();
  }
  async function buy(el) {
    try {
      await sendAddAction(el);
    } catch (e) {
      u.alert(e.message, '', 'warning');
      return;
    }
    toCartPage();
  }
  function toCartPage() {
    location.href = u.route('cart');
  }
  function updateCartButton(data) {
    const count = data.length;
    u.trigger('cart.update', data, count);
    document.dispatchEvent(new CustomEvent('cart.update', {
      detail: {
        data,
        count
      }
    }));
    const $cartButtons = document.querySelectorAll('[data-role=cart-button]');
    for (const $cartButton of $cartButtons) {
      const $cartQuantity = $cartButton.querySelector('[data-role=cart-quantity]');
      $cartButton.classList.toggle('h-has-items', count > 0);
      $cartQuantity.textContent = count;
      $cartButton.dispatchEvent(new CustomEvent('cart.update', {
        detail: {
          data,
          count
        }
      }));
    }
  }
  function findAttachments() {
    const attachments = u.selectAll('[data-role=attachment]');
    const attachItems = {};
    for (const attachment of attachments) {
      const idInput = attachment.querySelector('[data-role=attachment_id]');
      const qtyInput = attachment.querySelector('[data-role=attachment_quantity]');
      if (idInput.checked) {
        attachItems[idInput.value] = Number(qtyInput.value);
      }
    }
    return attachItems;
  }
  return {
    setters: [function (_main) {}],
    execute: function () {
      /**
       * Part of shopgo project.
       *
       * @copyright  Copyright (C) 2023 __ORGANIZATION__.
       * @license    __LICENSE__
       */

      u.delegate(document, '[data-task=add-to-cart]', 'click', e => {
        addToCart(e.currentTarget);
      });
      u.delegate(document, '[data-task=buy]', 'click', e => {
        buy(e.currentTarget);
      });
    }
  };
});
//# sourceMappingURL=product-cart.js.map
