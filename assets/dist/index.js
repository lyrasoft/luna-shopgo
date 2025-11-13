import { useUniDirective as I, data as A, route as y, useFormAsync as $, delegate as k, useHttpClient as O, simpleAlert as M, useUnicorn as D, useTomSelect as T, useColorPicker as b, __ as B, injectCssToDocument as F, selectOne as U, useIframeModal as J, useMacro as R } from "@windwalker-io/unicorn-next";
import { numberFormat as E } from "@lyrasoft/ts-toolkit/generic";
import z from "sweetalert";
import { Tooltip as P, Modal as G } from "bootstrap";
import { nextTick as H, defineComponent as Q, ref as g, computed as V, createApp as j } from "vue";
import { uniqueItem as K } from "@lyrasoft/ts-toolkit/vue";
function S(e = {}) {
  function t() {
    return A("currency").current;
  }
  function n() {
    return A("currency").main;
  }
  function r() {
    return t().code !== n().code;
  }
  function o(a, f) {
    return a * f.exchangeRate;
  }
  function c(a, f, p = {}) {
    let u = typeof a == "string" ? parseFloat(a) : a;
    Number.isNaN(u) && (u = 0);
    const s = f || t();
    p = Object.assign({}, e, p);
    const m = p?.code ?? !1, h = p?.sign ?? !0, v = p?.signPosition ?? s.signPosition, w = u < 0;
    u = Math.abs(u), u = o(u, s);
    let i = E(u, s.decimalPlace, s.decimalPoint);
    const l = s.space ? " " : "";
    return h && (v === "start" ? i = s.sign + l + i : i += l + s.sign), w ? "-" + i : (m && (i = s.code + " " + i), i);
  }
  function d(a, f = {}) {
    return c(a, n(), f);
  }
  return {
    isSubCurrency: r,
    getCurrentCurrency: t,
    getMainCurrency: n,
    format: c,
    formatMainCurrency: d,
    exchange: o
  };
}
function W() {
  let e = null;
  return I("currency-switch", {
    mounted(t, { value: n }) {
      e = async () => {
        const r = y("currency_switch");
        (await $()).post(r, { code: n });
      }, t.addEventListener("click", e);
    },
    unmounted(t) {
      e && (t.removeEventListener("click", e), e = null);
    }
  });
}
async function X() {
  k(document.body, "[data-task=add-to-cart]", "click", (e) => {
    q(e.currentTarget);
  }), k(document.body, "[data-task=buy]", "click", (e) => {
    x(e.currentTarget);
  }), await Promise.all([
    Y(),
    Z()
  ]);
}
function Y() {
  const e = (t) => q(t.currentTarget);
  return I("add-to-cart", {
    mounted(t) {
      t.addEventListener("click", e);
    },
    unmounted(t) {
      t.removeEventListener("click", e);
    }
  });
}
function Z() {
  const e = (t) => x(t.currentTarget);
  return I("buy", {
    mounted(t) {
      t.addEventListener("click", e);
    },
    unmounted(t) {
      t.removeEventListener("click", e);
    }
  });
}
async function N(e) {
  const t = e.dataset.id;
  if (!t)
    throw new Error("No product ID");
  const n = e.dataset.variantId;
  if (!n)
    throw new Error("No variant ID");
  const r = document.querySelector("[data-role=quantity]"), o = Number(r?.value || 1), c = et(), { post: d } = await O();
  try {
    const a = await d(
      "@cart_ajax/addToCart",
      {
        product_id: t,
        variant_id: n,
        quantity: o,
        attachments: c
      }
    );
    return tt(a.data.data), a.data;
  } catch (a) {
    throw console.error(a), a;
  }
}
async function q(e) {
  const { isAxiosError: t } = await O();
  try {
    await N(e);
  } catch (r) {
    t(r) && M(r.message, "", "warning");
    return;
  }
  await z({
    title: "已加入購物車",
    buttons: [
      "繼續購物",
      "前往結帳"
    ]
  }) && L();
}
async function x(e) {
  const { isAxiosError: t } = await O();
  try {
    await N(e);
  } catch (n) {
    t(n) && M(n.message, "", "warning");
    return;
  }
  L();
}
function L() {
  location.href = y("cart");
}
function tt(e) {
  const t = e.length;
  D().trigger("cart.update", e, t), document.dispatchEvent(
    new CustomEvent("cart.update", {
      detail: {
        data: e,
        count: t
      }
    })
  );
  const r = document.querySelectorAll("[data-role=cart-button]");
  for (const o of r) {
    const c = o.querySelector("[data-role=cart-quantity]");
    o.classList.toggle("h-has-items", t > 0), c && (c.textContent = t), o.dispatchEvent(
      new CustomEvent("cart.update", {
        detail: {
          data: e,
          count: t
        }
      })
    );
  }
}
function et() {
  const e = document.querySelectorAll("[data-role=attachment]"), t = {};
  for (const n of e) {
    const r = n.querySelector("[data-role=attachment_id]"), o = n.querySelector("[data-role=attachment_quantity]");
    r.checked && (t[r.value] = Number(o.value));
  }
  return t;
}
const ft = {
  async mounted(e, { value: t }) {
    P.getOrCreateInstance(e, t);
  },
  updated(e, { value: t }) {
    P.getOrCreateInstance(e, t).update();
  },
  beforeUnmount(e) {
    P.getOrCreateInstance(e).dispose();
  }
}, pt = {
  async mounted(e, { value: t }) {
    await H(), await T(e, t);
  },
  async beforeUnmount(e) {
    (await T(e)).destroy();
  }
}, gt = {
  async mounted(e, { value: t }) {
    await b(e, Object.assign({}, t));
  },
  async updated(e, { value: t }) {
    await b();
    const n = Spectrum.getInstance(e);
    JSON.stringify(t) !== JSON.stringify(n.options) && n.rebuild(Object.assign({}, t));
  },
  async unmounted(e) {
    await b(), Spectrum.getInstance(e).destroy();
  }
};
function nt(e) {
  e.config.compilerOptions.whitespace = "preserve", e.config.compilerOptions.isCustomElement = (t) => [
    "uni-flatpickr",
    "uni-iframe-modal"
  ].includes(t), e.config.globalProperties.$lang = (t, ...n) => B(t, ...n), e.config.globalProperties.$numberFormat = (t, n = "") => {
    t = Number(t);
    const r = t < 0;
    let o = n + E(Math.abs(t));
    return r && (o = "-" + o), o;
  }, e.config.globalProperties.$offsetFormat = (t, n = "") => {
    t = Number(t);
    const r = t < 0;
    let o = n + E(Math.abs(t));
    return r ? o = "-" + o : o = "+" + o, o;
  }, e.config.globalProperties.$priceOffset = (t, n) => {
    t = Number(t);
    const r = t < 0, { format: o } = S({ sign: !1, code: !1 });
    return n === "fixed" ? "=" + o(Math.abs(t)) : n === "offsets" ? r ? "-" + o(Math.abs(t)) : "+" + o(Math.abs(t)) : n === "percentage" ? (t > 100 && (t = 100), t + "%") : String(t);
  }, e.config.globalProperties.$formatPrice = (t, n = {}) => S().format(t, void 0, n), e.config.globalProperties.$currency = S();
}
function rt(e, t, n = [null, void 0, ""]) {
  for (let r in t)
    try {
      if (n.includes(t[r]))
        continue;
      t[r].constructor === Object ? e[r] = rt(e[r], t[r]) : e[r] = t[r];
    } catch {
      e[r] = t[r];
    }
  return e;
}
let ot = null;
async function mt(e, t = {}) {
  const [{ default: n }, { Navigation: r, Pagination: o }, { default: c }] = await Promise.all([
    import("swiper"),
    import("swiper/modules"),
    import("swiper/css/bundle?inline")
  ]);
  if (ot ??= F(c), e) {
    const d = U(e);
    return t = Object.assign({}, {
      simulateTouch: !1,
      allowTouchMove: !1,
      autoHeight: !0,
      modules: [r, o]
    }, t), new n(d, t);
  }
  return n;
}
J();
const it = Q({
  name: "ShippingPricingEditApp",
  props: {
    pricing: Object
  },
  setup(e) {
    const t = g(e.pricing?.global || {
      free: !1,
      pricing: s()
    }), n = g(e.pricing?.locationCategories || []), r = g(e.pricing?.locations || []), o = g(null), c = g(), d = g();
    function a() {
      const i = y("category_modal", { callback: "locationCategorySelected" });
      window.locationCategorySelected = function({ value: l, title: C }) {
        n.value.push({
          id: l,
          title: C,
          free: !1,
          pricing: s()
        }), c.value?.close();
      }, c.value?.open(i, { size: "modal-xl" });
    }
    function f() {
      const i = y("location_modal", { callback: "locationSelected" });
      window.locationSelected = function({ value: l, title: C, path: _ }) {
        r.value.push({
          id: l,
          title: C,
          path: _,
          free: !1,
          pricing: s()
        }), c.value?.close();
      }, c.value?.open(i, { size: "modal-xl" });
    }
    function p(i) {
      return i.pricing.filter((l) => l.fee !== "" && l.threshold !== "").length;
    }
    function u(i) {
      o.value = i, G.getOrCreateInstance(d.value).show();
    }
    function s() {
      const i = m();
      return i.threshold = 0, [i];
    }
    function m() {
      return K(
        {
          threshold: "",
          fee: ""
        }
      );
    }
    function h(i = 0) {
      o.value?.pricing.splice(i + 1, 0, m());
    }
    function v(i) {
      o.value?.pricing.splice(i, 1);
    }
    const w = V(() => JSON.stringify(
      {
        global: t.value,
        locationCategories: n.value,
        locations: r.value
      }
    ));
    return {
      global: t,
      locationCategories: n,
      locations: r,
      currentItem: o,
      finalResult: w,
      selectModal: c,
      pricingModal: d,
      openLocationSelector: f,
      openLocationCategorySelector: a,
      calcLocationPricingCount: p,
      configurePricing: u,
      addPricingSegment: h,
      removePricingSegment: v
    };
  }
});
function yt(e) {
  const t = e.getAttribute("id"), n = j(
    it,
    A(t + ".props")
  );
  n.use(nt), n.mount(e);
}
function ht() {
  return R("$shopgo", {
    useProductCartButtons: X,
    useCurrencySwitcher: W
  });
}
async function vt(e = {}) {
  const { initApp: t } = await import("./chunks/additional-purchase-attachment-edit.js");
  return t(e);
}
async function wt(e = {}) {
  const { initApp: t } = await import("./chunks/product-discounts-edit.js");
  return t(e);
}
async function Ct(e = {}) {
  const { initApp: t } = await import("./chunks/product-variants-edit.js");
  return t(e);
}
async function bt(e = {}) {
  const { initApp: t } = await import("./chunks/product-attribute-edit.js");
  return t(e);
}
async function Pt(e = {}) {
  const { initApp: t } = await import("./chunks/product-feature-edit.js");
  return t(e);
}
async function St(e = {}) {
  const { initApp: t } = await import("./chunks/cart.js");
  return t(e);
}
export {
  nt as ShopGoPlugin,
  rt as mergeRecursive,
  vt as useAdditionalPurchaseAttachmentEditApp,
  St as useCartApp,
  S as useCurrency,
  W as useCurrencySwitcher,
  bt as useProductAttributeEditApp,
  X as useProductCartButtons,
  wt as useProductDiscountsEditApp,
  Pt as useProductFeatureEditApp,
  Ct as useProductVariantsEditApp,
  yt as useShippingPricingEditApp,
  ht as useShopGoCatalog,
  mt as useSwiper,
  gt as vColorpicker,
  pt as vTomSelect,
  ft as vTooltip
};
//# sourceMappingURL=index.js.map
