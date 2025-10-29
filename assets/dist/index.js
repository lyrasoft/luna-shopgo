import { useUniDirective as h, data as b, route as P, useFormAsync as $, delegate as C, useHttpClient as w, simpleAlert as E, useUnicorn as x, useTomSelect as A, useColorPicker as m, __ as L, injectCssToDocument as _, selectOne as D, useMacro as B } from "@windwalker-io/unicorn-next";
import { numberFormat as y } from "@lyrasoft/ts-toolkit/generic";
import F from "sweetalert";
import { Tooltip as p } from "bootstrap";
import { nextTick as U } from "vue";
function g(e = {}) {
  function t() {
    return b("currency").current;
  }
  function n() {
    return b("currency").main;
  }
  function r() {
    return t().code !== n().code;
  }
  function c(a, l) {
    return a * l.exchangeRate;
  }
  function o(a, l, f = {}) {
    let i = typeof a == "string" ? parseFloat(a) : a;
    Number.isNaN(i) && (i = 0);
    const s = l || t();
    f = Object.assign({}, e, f);
    const T = f?.code ?? !1, N = f?.sign ?? !0, q = f?.signPosition ?? s.signPosition, M = i < 0;
    i = Math.abs(i), i = c(i, s);
    let u = y(i, s.decimalPlace, s.decimalPoint);
    const v = s.space ? " " : "";
    return N && (q === "start" ? u = s.sign + v + u : u += v + s.sign), M ? "-" + u : (T && (u = s.code + " " + u), u);
  }
  function d(a, l = {}) {
    return o(a, n(), l);
  }
  return {
    isSubCurrency: r,
    getCurrentCurrency: t,
    getMainCurrency: n,
    format: o,
    formatMainCurrency: d,
    exchange: c
  };
}
function G() {
  let e = null;
  return h("currency-switch", {
    mounted(t, { value: n }) {
      e = async () => {
        const r = P("currency_switch");
        (await $()).post(r, { code: n });
      }, t.addEventListener("click", e);
    },
    unmounted(t) {
      e && (t.removeEventListener("click", e), e = null);
    }
  });
}
async function H() {
  C(document.body, "[data-task=add-to-cart]", "click", (e) => {
    I(e.currentTarget);
  }), C(document.body, "[data-task=buy]", "click", (e) => {
    k(e.currentTarget);
  }), await Promise.all([
    J(),
    R()
  ]);
}
function J() {
  const e = (t) => I(t.currentTarget);
  return h("add-to-cart", {
    mounted(t) {
      t.addEventListener("click", e);
    },
    unmounted(t) {
      t.removeEventListener("click", e);
    }
  });
}
function R() {
  const e = (t) => k(t.currentTarget);
  return h("buy", {
    mounted(t) {
      t.addEventListener("click", e);
    },
    unmounted(t) {
      t.removeEventListener("click", e);
    }
  });
}
async function S(e) {
  const t = e.dataset.id;
  if (!t)
    throw new Error("No product ID");
  const n = e.dataset.variantId;
  if (!n)
    throw new Error("No variant ID");
  const r = document.querySelector("[data-role=quantity]"), c = Number(r?.value || 1), o = V(), { post: d } = await w();
  try {
    const a = await d(
      "@cart_ajax/addToCart",
      {
        product_id: t,
        variant_id: n,
        quantity: c,
        attachments: o
      }
    );
    return Q(a.data.data), a.data;
  } catch (a) {
    throw console.error(a), a;
  }
}
async function I(e) {
  const { isAxiosError: t } = await w();
  try {
    await S(e);
  } catch (r) {
    t(r) && E(r.message, "", "warning");
    return;
  }
  await F({
    title: "已加入購物車",
    buttons: [
      "繼續購物",
      "前往結帳"
    ]
  }) && O();
}
async function k(e) {
  const { isAxiosError: t } = await w();
  try {
    await S(e);
  } catch (n) {
    t(n) && E(n.message, "", "warning");
    return;
  }
  O();
}
function O() {
  location.href = P("cart");
}
function Q(e) {
  const t = e.length;
  x().trigger("cart.update", e, t), document.dispatchEvent(
    new CustomEvent("cart.update", {
      detail: {
        data: e,
        count: t
      }
    })
  );
  const r = document.querySelectorAll("[data-role=cart-button]");
  for (const c of r) {
    const o = c.querySelector("[data-role=cart-quantity]");
    c.classList.toggle("h-has-items", t > 0), o && (o.textContent = t), c.dispatchEvent(
      new CustomEvent("cart.update", {
        detail: {
          data: e,
          count: t
        }
      })
    );
  }
}
function V() {
  const e = document.querySelectorAll("[data-role=attachment]"), t = {};
  for (const n of e) {
    const r = n.querySelector("[data-role=attachment_id]"), c = n.querySelector("[data-role=attachment_quantity]");
    r.checked && (t[r.value] = Number(c.value));
  }
  return t;
}
const tt = {
  async mounted(e, { value: t }) {
    p.getOrCreateInstance(e, t);
  },
  updated(e, { value: t }) {
    p.getOrCreateInstance(e, t).update();
  },
  beforeUnmount(e) {
    p.getOrCreateInstance(e).dispose();
  }
}, et = {
  async mounted(e, { value: t }) {
    await U(), await A(e, t);
  },
  async beforeUnmount(e) {
    (await A(e)).destroy();
  }
}, nt = {
  async mounted(e, { value: t }) {
    await m(e, Object.assign({}, t));
  },
  async updated(e, { value: t }) {
    await m();
    const n = Spectrum.getInstance(e);
    JSON.stringify(t) !== JSON.stringify(n.options) && n.rebuild(Object.assign({}, t));
  },
  async unmounted(e) {
    await m(), Spectrum.getInstance(e).destroy();
  }
};
function rt(e) {
  e.config.compilerOptions.whitespace = "preserve", e.config.compilerOptions.isCustomElement = (t) => [
    "uni-flatpickr",
    "uni-iframe-modal"
  ].includes(t), e.config.globalProperties.$lang = (t, ...n) => L(t, ...n), e.config.globalProperties.$numberFormat = (t, n = "") => {
    t = Number(t);
    const r = t < 0;
    let c = n + y(Math.abs(t));
    return r && (c = "-" + c), c;
  }, e.config.globalProperties.$offsetFormat = (t, n = "") => {
    t = Number(t);
    const r = t < 0;
    let c = n + y(Math.abs(t));
    return r ? c = "-" + c : c = "+" + c, c;
  }, e.config.globalProperties.$priceOffset = (t, n) => {
    t = Number(t);
    const r = t < 0, { format: c } = g({ sign: !1, code: !1 });
    return n === "fixed" ? "=" + c(Math.abs(t)) : n === "offsets" ? r ? "-" + c(Math.abs(t)) : "+" + c(Math.abs(t)) : n === "percentage" ? (t > 100 && (t = 100), t + "%") : String(t);
  }, e.config.globalProperties.$formatPrice = (t, n = {}) => g().format(t, void 0, n), e.config.globalProperties.$currency = g();
}
function z(e, t, n = [null, void 0, ""]) {
  for (let r in t)
    try {
      if (n.includes(t[r]))
        continue;
      t[r].constructor === Object ? e[r] = z(e[r], t[r]) : e[r] = t[r];
    } catch {
      e[r] = t[r];
    }
  return e;
}
let K = null;
async function ct(e, t = {}) {
  const [{ default: n }, { Navigation: r, Pagination: c }, { default: o }] = await Promise.all([
    import("swiper"),
    import("swiper/modules"),
    import("swiper/css/bundle?inline")
  ]);
  if (K ??= _(o), e) {
    const d = D(e);
    return t = Object.assign({}, {
      simulateTouch: !1,
      allowTouchMove: !1,
      autoHeight: !0,
      modules: [r, c]
    }, t), new n(d, t);
  }
  return n;
}
function at() {
  return B("$shopgo", {
    useProductCartButtons: H,
    useCurrencySwitcher: G
  });
}
async function ot(e = {}) {
  const { initApp: t } = await import("./chunks/additional-purchase-attachment-edit.js");
  return t(e);
}
async function it(e = {}) {
  const { initApp: t } = await import("./chunks/product-discounts-edit.js");
  return t(e);
}
async function st(e = {}) {
  const { initApp: t } = await import("./chunks/product-variants-edit.js");
  return t(e);
}
async function ut(e = {}) {
  const { initApp: t } = await import("./chunks/product-attribute-edit.js");
  return t(e);
}
async function dt(e = {}) {
  const { initApp: t } = await import("./chunks/product-feature-edit.js");
  return t(e);
}
async function lt(e = {}) {
  const { initApp: t } = await import("./chunks/cart.js");
  return t(e);
}
export {
  rt as ShopGoPlugin,
  z as mergeRecursive,
  ot as useAdditionalPurchaseAttachmentEditApp,
  lt as useCartApp,
  g as useCurrency,
  G as useCurrencySwitcher,
  ut as useProductAttributeEditApp,
  H as useProductCartButtons,
  it as useProductDiscountsEditApp,
  dt as useProductFeatureEditApp,
  st as useProductVariantsEditApp,
  at as useShopGoCatalog,
  ct as useSwiper,
  nt as vColorpicker,
  et as vTomSelect,
  tt as vTooltip
};
//# sourceMappingURL=index.js.map
