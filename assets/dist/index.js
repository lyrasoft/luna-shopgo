import { Tooltip as g } from "bootstrap";
import { useTomSelect as b, useColorPicker as m, data as v, __ as k, delegate as C, useHttpClient as h, simpleAlert as P, route as x, useUnicorn as N } from "@windwalker-io/unicorn-next";
import { nextTick as T } from "vue";
import { numberFormat as y } from "@lyrasoft/ts-toolkit/generic";
import $ from "sweetalert";
const L = {
  async mounted(e, { value: t }) {
    g.getOrCreateInstance(e, t);
  },
  updated(e, { value: t }) {
    g.getOrCreateInstance(e, t).update();
  },
  beforeUnmount(e) {
    g.getOrCreateInstance(e).dispose();
  }
}, Q = {
  async mounted(e, { value: t }) {
    await T(), await b(e, t);
  },
  async beforeUnmount(e) {
    (await b(e)).destroy();
  }
}, V = {
  async mounted(e, { value: t }) {
    await m(e, Object.assign({}, t));
  },
  async updated(e, { value: t }) {
    const n = await m(e);
    JSON.stringify(t) !== JSON.stringify(n.options) && n.rebuild(Object.assign({}, t));
  },
  async unmounted(e) {
    (await m(e)).destroy();
  }
};
function p(e = {}) {
  function t() {
    return v("currency").current;
  }
  function n() {
    return v("currency").main;
  }
  function r() {
    return t().code !== n().code;
  }
  function a(o, d) {
    return o * d.exchangeRate;
  }
  function s(o, d, f = {}) {
    let c = typeof o == "string" ? parseFloat(o) : o;
    Number.isNaN(c) && (c = 0);
    const i = d || t();
    f = Object.assign({}, e, f);
    const O = f?.code ?? !1, q = f?.sign ?? !0, E = f?.signPosition ?? i.signPosition, I = c < 0;
    c = Math.abs(c), c = a(c, i);
    let u = y(c, i.decimalPlace, i.decimalPoint);
    const w = i.space ? " " : "";
    return q && (E === "start" ? u = i.sign + w + u : u += w + i.sign), I ? "-" + u : (O && (u = i.code + " " + u), u);
  }
  function l(o, d = {}) {
    return s(o, n(), d);
  }
  return {
    isSubCurrency: r,
    getCurrentCurrency: t,
    getMainCurrency: n,
    format: s,
    formatMainCurrency: l,
    exchange: a
  };
}
function z(e) {
  e.config.compilerOptions.whitespace = "preserve", e.config.compilerOptions.isCustomElement = (t) => [
    "uni-flatpickr",
    "uni-iframe-modal"
  ].includes(t), e.config.globalProperties.$lang = (t, ...n) => k(t, ...n), e.config.globalProperties.$numberFormat = (t, n = "") => {
    const r = t < 0;
    let a = n + y(Math.abs(t));
    return r && (a = "-" + a), a;
  }, e.config.globalProperties.$offsetFormat = (t, n = "") => {
    const r = t < 0;
    let a = n + y(Math.abs(t));
    return r ? a = "-" + a : a = "+" + a, a;
  }, e.config.globalProperties.$priceOffset = (t, n) => {
    const r = t < 0, { format: a } = p({ sign: !1, code: !1 });
    return n === "fixed" ? "=" + a(Math.abs(t)) : n === "offsets" ? r ? "-" + a(Math.abs(t)) : "+" + a(Math.abs(t)) : n === "percentage" ? (t > 100 && (t = 100), t + "%") : String(t);
  }, e.config.globalProperties.$formatPrice = (t, n = !1) => p().format(t, void 0, n), e.config.globalProperties.$currency = p();
}
function K() {
  C(document.body, "[data-task=add-to-cart]", "click", (e) => {
    M(e.currentTarget);
  }), C(document.body, "[data-task=buy]", "click", (e) => {
    _(e.currentTarget);
  });
}
async function A(e) {
  const t = e.dataset.id;
  if (!t)
    throw new Error("No product ID");
  const n = e.dataset.variantId;
  if (!n)
    throw new Error("No variant ID");
  const r = document.querySelector("[data-role=quantity]"), a = Number(r?.value || 1), s = B(), { post: l } = await h();
  try {
    const o = await l(
      "@cart_ajax/addToCart",
      {
        product_id: t,
        variant_id: n,
        quantity: a,
        attachments: s
      }
    );
    return F(o.data.data), o.data;
  } catch (o) {
    throw console.error(o), o;
  }
}
async function M(e) {
  const { isAxiosError: t } = await h();
  try {
    await A(e);
  } catch (r) {
    t(r) && P(r.message, "", "warning");
    return;
  }
  await $({
    title: "已加入購物車",
    buttons: [
      "繼續購物",
      "前往結帳"
    ]
  }) && S();
}
async function _(e) {
  const { isAxiosError: t } = await h();
  try {
    await A(e);
  } catch (n) {
    t(n) && P(n.message, "", "warning");
    return;
  }
  S();
}
function S() {
  location.href = x("cart");
}
function F(e) {
  const t = e.length;
  N().trigger("cart.update", e, t), document.dispatchEvent(
    new CustomEvent("cart.update", {
      detail: {
        data: e,
        count: t
      }
    })
  );
  const r = document.querySelectorAll("[data-role=cart-button]");
  for (const a of r) {
    const s = a.querySelector("[data-role=cart-quantity]");
    a.classList.toggle("h-has-items", t > 0), s && (s.textContent = t), a.dispatchEvent(
      new CustomEvent("cart.update", {
        detail: {
          data: e,
          count: t
        }
      })
    );
  }
}
function B() {
  const e = document.querySelectorAll("[data-role=attachment]"), t = {};
  for (const n of e) {
    const r = n.querySelector("[data-role=attachment_id]"), a = n.querySelector("[data-role=attachment_quantity]");
    r.checked && (t[r.value] = Number(a.value));
  }
  return t;
}
function D(e, t, n = [null, void 0, ""]) {
  for (let r in t)
    try {
      if (n.includes(t[r]))
        continue;
      t[r].constructor === Object ? e[r] = D(e[r], t[r]) : e[r] = t[r];
    } catch {
      e[r] = t[r];
    }
  return e;
}
async function W(e = {}) {
  const { initApp: t } = await import("./chunks/additional-purchase-attachment-edit.js");
  return t(e);
}
async function X(e = {}) {
  const { initApp: t } = await import("./chunks/product-discounts-edit.js");
  return t(e);
}
async function Y(e = {}) {
  const { initApp: t } = await import("./chunks/product-variants-edit.js");
  return t(e);
}
export {
  z as ShopGoPlugin,
  D as mergeRecursive,
  W as useAdditionalPurchaseAttachmentEditApp,
  p as useCurrency,
  K as useProductCart,
  X as useProductDiscountsEditApp,
  Y as useProductVariantsEditApp,
  V as vColorpicker,
  Q as vTomSelect,
  L as vTooltip
};
//# sourceMappingURL=index.js.map
