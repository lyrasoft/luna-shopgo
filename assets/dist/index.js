import { Tooltip as m } from "bootstrap";
import { useTomSelect as b, useColorPicker as p, data as v, __ as T, delegate as C, useHttpClient as h, simpleAlert as P, route as N, useUnicorn as k, injectCssToDocument as x, selectOne as M } from "@windwalker-io/unicorn-next";
import { nextTick as $ } from "vue";
import { numberFormat as y } from "@lyrasoft/ts-toolkit/generic";
import _ from "sweetalert";
const z = {
  async mounted(e, { value: t }) {
    m.getOrCreateInstance(e, t);
  },
  updated(e, { value: t }) {
    m.getOrCreateInstance(e, t).update();
  },
  beforeUnmount(e) {
    m.getOrCreateInstance(e).dispose();
  }
}, K = {
  async mounted(e, { value: t }) {
    await $(), await b(e, t);
  },
  async beforeUnmount(e) {
    (await b(e)).destroy();
  }
}, W = {
  async mounted(e, { value: t }) {
    await p(e, Object.assign({}, t));
  },
  async updated(e, { value: t }) {
    await p();
    const n = Spectrum.getInstance(e);
    JSON.stringify(t) !== JSON.stringify(n.options) && n.rebuild(Object.assign({}, t));
  },
  async unmounted(e) {
    await p(), Spectrum.getInstance(e).destroy();
  }
};
function g(e = {}) {
  function t() {
    return v("currency").current;
  }
  function n() {
    return v("currency").main;
  }
  function r() {
    return t().code !== n().code;
  }
  function a(o, f) {
    return o * f.exchangeRate;
  }
  function c(o, f, l = {}) {
    let i = typeof o == "string" ? parseFloat(o) : o;
    Number.isNaN(i) && (i = 0);
    const s = f || t();
    l = Object.assign({}, e, l);
    const I = l?.code ?? !1, O = l?.sign ?? !0, E = l?.signPosition ?? s.signPosition, q = i < 0;
    i = Math.abs(i), i = a(i, s);
    let u = y(i, s.decimalPlace, s.decimalPoint);
    const w = s.space ? " " : "";
    return O && (E === "start" ? u = s.sign + w + u : u += w + s.sign), q ? "-" + u : (I && (u = s.code + " " + u), u);
  }
  function d(o, f = {}) {
    return c(o, n(), f);
  }
  return {
    isSubCurrency: r,
    getCurrentCurrency: t,
    getMainCurrency: n,
    format: c,
    formatMainCurrency: d,
    exchange: a
  };
}
function X(e) {
  e.config.compilerOptions.whitespace = "preserve", e.config.compilerOptions.isCustomElement = (t) => [
    "uni-flatpickr",
    "uni-iframe-modal"
  ].includes(t), e.config.globalProperties.$lang = (t, ...n) => T(t, ...n), e.config.globalProperties.$numberFormat = (t, n = "") => {
    const r = t < 0;
    let a = n + y(Math.abs(t));
    return r && (a = "-" + a), a;
  }, e.config.globalProperties.$offsetFormat = (t, n = "") => {
    const r = t < 0;
    let a = n + y(Math.abs(t));
    return r ? a = "-" + a : a = "+" + a, a;
  }, e.config.globalProperties.$priceOffset = (t, n) => {
    const r = t < 0, { format: a } = g({ sign: !1, code: !1 });
    return n === "fixed" ? "=" + a(Math.abs(t)) : n === "offsets" ? r ? "-" + a(Math.abs(t)) : "+" + a(Math.abs(t)) : n === "percentage" ? (t > 100 && (t = 100), t + "%") : String(t);
  }, e.config.globalProperties.$formatPrice = (t, n = !1) => g().format(t, void 0, n), e.config.globalProperties.$currency = g();
}
function Y() {
  C(document.body, "[data-task=add-to-cart]", "click", (e) => {
    F(e.currentTarget);
  }), C(document.body, "[data-task=buy]", "click", (e) => {
    D(e.currentTarget);
  });
}
async function A(e) {
  const t = e.dataset.id;
  if (!t)
    throw new Error("No product ID");
  const n = e.dataset.variantId;
  if (!n)
    throw new Error("No variant ID");
  const r = document.querySelector("[data-role=quantity]"), a = Number(r?.value || 1), c = U(), { post: d } = await h();
  try {
    const o = await d(
      "@cart_ajax/addToCart",
      {
        product_id: t,
        variant_id: n,
        quantity: a,
        attachments: c
      }
    );
    return B(o.data.data), o.data;
  } catch (o) {
    throw console.error(o), o;
  }
}
async function F(e) {
  const { isAxiosError: t } = await h();
  try {
    await A(e);
  } catch (r) {
    t(r) && P(r.message, "", "warning");
    return;
  }
  await _({
    title: "已加入購物車",
    buttons: [
      "繼續購物",
      "前往結帳"
    ]
  }) && S();
}
async function D(e) {
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
  location.href = N("cart");
}
function B(e) {
  const t = e.length;
  k().trigger("cart.update", e, t), document.dispatchEvent(
    new CustomEvent("cart.update", {
      detail: {
        data: e,
        count: t
      }
    })
  );
  const r = document.querySelectorAll("[data-role=cart-button]");
  for (const a of r) {
    const c = a.querySelector("[data-role=cart-quantity]");
    a.classList.toggle("h-has-items", t > 0), c && (c.textContent = t), a.dispatchEvent(
      new CustomEvent("cart.update", {
        detail: {
          data: e,
          count: t
        }
      })
    );
  }
}
function U() {
  const e = document.querySelectorAll("[data-role=attachment]"), t = {};
  for (const n of e) {
    const r = n.querySelector("[data-role=attachment_id]"), a = n.querySelector("[data-role=attachment_quantity]");
    r.checked && (t[r.value] = Number(a.value));
  }
  return t;
}
function H(e, t, n = [null, void 0, ""]) {
  for (let r in t)
    try {
      if (n.includes(t[r]))
        continue;
      t[r].constructor === Object ? e[r] = H(e[r], t[r]) : e[r] = t[r];
    } catch {
      e[r] = t[r];
    }
  return e;
}
let J = null;
async function Z(e, t = {}) {
  const [{ default: n }, { Navigation: r, Pagination: a }, { default: c }] = await Promise.all([
    import("swiper"),
    import("swiper/modules"),
    import("swiper/css/bundle?inline")
  ]);
  if (J ??= x(c), e) {
    const d = M(e);
    return t = Object.assign({}, {
      simulateTouch: !1,
      allowTouchMove: !1,
      autoHeight: !0,
      modules: [r, a]
    }, t), new n(d, t);
  }
  return n;
}
async function j(e = {}) {
  const { initApp: t } = await import("./chunks/additional-purchase-attachment-edit.js");
  return t(e);
}
async function tt(e = {}) {
  const { initApp: t } = await import("./chunks/product-discounts-edit.js");
  return t(e);
}
async function et(e = {}) {
  const { initApp: t } = await import("./chunks/product-variants-edit.js");
  return t(e);
}
async function nt(e = {}) {
  const { initApp: t } = await import("./chunks/product-attribute-edit.js");
  return t(e);
}
async function rt(e = {}) {
  const { initApp: t } = await import("./chunks/product-feature-edit.js");
  return t(e);
}
export {
  X as ShopGoPlugin,
  H as mergeRecursive,
  j as useAdditionalPurchaseAttachmentEditApp,
  g as useCurrency,
  nt as useProductAttributeEditApp,
  Y as useProductCart,
  tt as useProductDiscountsEditApp,
  rt as useProductFeatureEditApp,
  et as useProductVariantsEditApp,
  Z as useSwiper,
  W as vColorpicker,
  K as vTomSelect,
  z as vTooltip
};
//# sourceMappingURL=index.js.map
