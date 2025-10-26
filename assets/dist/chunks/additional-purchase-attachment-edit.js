import { _ as Q, r as N } from "./_plugin-vue_export-helper.js";
import { defineComponent as T, ref as _, computed as R, watch as S, createElementBlock as g, openBlock as h, createElementVNode as e, createTextVNode as s, toDisplayString as c, withDirectives as x, vModelCheckbox as C, normalizeClass as M, Fragment as q, renderList as j, vModelSelect as z, createCommentVNode as D, vModelText as $, onMounted as B, createBlock as V, TransitionGroup as I, withCtx as F, createApp as G } from "vue";
import { slideDown as O, slideUp as Y, data as H, useIframeModal as J, simpleAlert as U, __ as E, route as K, useHttpClient as W } from "@windwalker-io/unicorn-next";
import { uniqueItemList as L, uniqueItem as X } from "@lyrasoft/ts-toolkit/vue";
import { ShopGoPlugin as Z } from "../index.js";
const tt = /* @__PURE__ */ T({
  __name: "AttachmentProduct",
  props: {
    product: {},
    variants: {},
    open: { type: Boolean }
  },
  setup(l, { expose: t }) {
    t();
    const i = l, a = _(
      L(i.variants).map((o) => (o.attachment = o.attachment || {
        method: "offsets",
        price: 0,
        maxQuantity: "",
        state: 1
      }, o))
    ), p = _(!1), f = _(i.open);
    function n(o) {
      for (const m of a.value)
        m.attachment.state = o.checked ? 1 : 0;
    }
    const d = R(() => a.value.filter((o) => Number(o.attachment.state) === 1).length);
    function b(o) {
      u(o.attachment.method, "method"), r(o);
    }
    function y(o) {
      let m = o.attachment.maxQuantity;
      m = Math.max(m, 0), m = Math.min(m, 30), o.attachment.maxQuantity = m, u(o.attachment.maxQuantity, "maxQuantity");
    }
    function r(o) {
      o.attachment.method === "percentage" && (o.attachment.price < 0 || o.attachment.price > 100) && (o.attachment.price = Math.min(
        Math.abs(o.attachment.price),
        100
      )), o.attachment.method === "offsets" && o.attachment.price > 0 && (o.attachment.price = -o.attachment.price), o.attachment.method === "fixed" && o.attachment.price < 0 && (o.attachment.price = -o.attachment.price), u(o.attachment.price, "price");
    }
    function u(o, m) {
      if (p.value)
        for (const A of a.value)
          A.attachment[m] = o;
    }
    function k(o) {
      return o.attachment.method === "percentage" ? "1" : H("price.step") || "0.0001";
    }
    const v = _();
    S(f, (o) => {
      setTimeout(() => {
        o ? O(v.value) : Y(v.value);
      }, 0);
    }, { immediate: !0 }), S(() => i.open, (o) => {
      f.value = o;
    });
    const w = { props: i, items: a, syncAll: p, open: f, toggleAll: n, checks: d, onMethodChange: b, onMaxQuantityChange: y, normalizePricing: r, syncAllFields: u, getPriceStep: k, variantList: v };
    return Object.defineProperty(w, "__isScriptSetup", { enumerable: !1, value: !0 }), w;
  }
}), et = { class: "card c-attachment" }, nt = { class: "c-attachment__product card-header border-bottom d-flex gap-3" }, ot = {
  class: "ratio ratio-1x1",
  style: { width: "55px" }
}, at = ["src"], st = { class: "w-100" }, lt = { class: "d-flex align-items-center gap-2 mb-2" }, it = { class: "m-0" }, dt = { class: "ms-auto" }, rt = { class: "d-flex align-items-center gap-2" }, ct = { class: "badge" }, ut = { class: "badge bg-secondary" }, pt = { class: "d-flex gap-3" }, mt = { class: "form-check" }, ht = ["id"], ft = ["for"], vt = { class: "ms-auto" }, gt = {
  class: "c-attachment__variants",
  ref: "variantList",
  style: { overflow: "hidden", display: "none" }
}, yt = { class: "table" }, _t = { style: { width: "1%" } }, bt = ["checked", ".indeterminate"], xt = {
  class: "text-nowrap",
  style: { width: "23%" }
}, kt = {
  class: "text-nowrap",
  style: { width: "15%" }
}, At = {
  class: "text-nowrap",
  style: { width: "10%" }
}, wt = ["id", "onUpdate:modelValue"], Pt = ["for"], St = { class: "d-none" }, Ct = ["name", "value"], Mt = ["name", "value"], $t = ["name", "value"], Vt = ["name", "value"], Ut = ["name", "value"], Et = ["onUpdate:modelValue", "onChange"], Qt = { value: "percentage" }, Nt = { value: "offsets" }, Tt = { value: "fixed" }, qt = { class: "input-group input-group-sm flex-nowrap" }, jt = ["step", "onUpdate:modelValue", "onChange"], Dt = {
  key: 0,
  class: "input-group-text"
}, Lt = ["onUpdate:modelValue", "onChange"];
function Rt(l, t, i, a, p, f) {
  return h(), g("div", et, [
    e("div", nt, [
      e("div", null, [
        e("div", ot, [
          e("img", {
            class: "object-fit-cover",
            src: i.product.variant.cover,
            alt: "cover"
          }, null, 8, at)
        ])
      ]),
      t[13] || (t[13] = s()),
      e("div", st, [
        e("div", lt, [
          e("h4", it, c(i.product.title), 1),
          t[7] || (t[7] = s()),
          t[8] || (t[8] = e("div", null, null, -1)),
          t[9] || (t[9] = s()),
          e("div", dt, [
            e("div", rt, [
              e("span", ct, `
                            #` + c(i.product.id), 1),
              t[5] || (t[5] = s()),
              e("span", ut, c(l.$lang("shopgo.additional.purchase.text.selected.count", a.checks)), 1),
              t[6] || (t[6] = s()),
              e("button", {
                type: "button",
                class: "btn btn-outline-secondary btn-sm",
                onClick: t[0] || (t[0] = (n) => l.$emit("remove"))
              }, [
                t[4] || (t[4] = e("i", { class: "fa fa-trash" }, null, -1)),
                s(" " + c(l.$lang("shopgo.additional.purchase.button.delete")), 1)
              ])
            ])
          ])
        ]),
        t[12] || (t[12] = s()),
        e("div", pt, [
          e("div", mt, [
            x(e("input", {
              id: `input-sync-all-${i.product.id}`,
              type: "checkbox",
              class: "form-check-input",
              "onUpdate:modelValue": t[1] || (t[1] = (n) => a.syncAll = n)
            }, null, 8, ht), [
              [C, a.syncAll]
            ]),
            t[10] || (t[10] = s()),
            e("label", {
              for: `input-sync-all-${i.product.id}`
            }, [
              e("i", {
                class: M(["fa", [a.syncAll ? "fa-lock" : "fa-unlock"]])
              }, null, 2),
              s(" " + c(l.$lang("shopgo.additional.purchase.text.sync.all")), 1)
            ], 8, ft)
          ]),
          t[11] || (t[11] = s()),
          e("div", vt, [
            e("a", {
              href: "javascript://",
              class: "px-2 py-2",
              onClick: t[2] || (t[2] = (n) => a.open = !a.open)
            }, [
              e("i", {
                class: M(["fa", [a.open ? "fa-chevron-down" : "fa-chevron-up"]])
              }, null, 2)
            ])
          ])
        ])
      ])
    ]),
    t[31] || (t[31] = s()),
    e("div", gt, [
      e("table", yt, [
        e("thead", null, [
          e("tr", null, [
            e("th", _t, [
              e("input", {
                type: "checkbox",
                class: "form-check-input",
                checked: a.checks === a.items.length,
                ".indeterminate": a.checks !== 0 && a.checks < a.items.length,
                onClick: t[3] || (t[3] = (n) => a.toggleAll(n.target))
              }, null, 40, bt)
            ]),
            t[14] || (t[14] = s()),
            e("th", null, c(l.$lang("unicorn.field.title")), 1),
            t[15] || (t[15] = s()),
            e("th", xt, c(l.$lang("shopgo.additional.purchase.field.method")), 1),
            t[16] || (t[16] = s()),
            e("th", kt, c(l.$lang("shopgo.additional.purchase.field.pricing")), 1),
            t[17] || (t[17] = s()),
            e("th", At, c(l.$lang("shopgo.additional.purchase.field.max.quantity")), 1)
          ])
        ]),
        t[30] || (t[30] = s()),
        e("tbody", null, [
          (h(!0), g(q, null, j(a.items, (n) => (h(), g("tr", {
            key: n.id,
            class: ""
          }, [
            e("td", null, [
              x(e("input", {
                type: "checkbox",
                id: `input-variant-${n.id}`,
                class: "form-check-input",
                "onUpdate:modelValue": (d) => n.attachment.state = d,
                "true-value": 1,
                "false-value": 0
              }, null, 8, wt), [
                [C, n.attachment.state]
              ])
            ]),
            t[26] || (t[26] = s()),
            e("td", null, [
              e("label", {
                for: `input-variant-${n.id}`
              }, c(n.title), 9, Pt),
              t[22] || (t[22] = s()),
              e("div", St, [
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][id]`,
                  value: n.attachment?.id,
                  type: "hidden"
                }, null, 8, Ct),
                t[18] || (t[18] = s()),
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][method]`,
                  value: n.attachment.method,
                  type: "hidden"
                }, null, 8, Mt),
                t[19] || (t[19] = s()),
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][price]`,
                  value: n.attachment.price,
                  type: "hidden"
                }, null, 8, $t),
                t[20] || (t[20] = s()),
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][max_quantity]`,
                  value: n.attachment.maxQuantity,
                  type: "hidden"
                }, null, 8, Vt),
                t[21] || (t[21] = s()),
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][state]`,
                  value: n.attachment.state,
                  type: "hidden"
                }, null, 8, Ut)
              ])
            ]),
            t[27] || (t[27] = s()),
            e("td", null, [
              x(e("select", {
                class: "form-select form-select-sm",
                "onUpdate:modelValue": (d) => n.attachment.method = d,
                onChange: (d) => a.onMethodChange(n)
              }, [
                e("option", Qt, c(l.$lang("shopgo.discount.method.percentage")), 1),
                t[23] || (t[23] = s()),
                e("option", Nt, c(l.$lang("shopgo.discount.method.offsets")), 1),
                t[24] || (t[24] = s()),
                e("option", Tt, c(l.$lang("shopgo.discount.method.fixed")), 1)
              ], 40, Et), [
                [z, n.attachment.method]
              ])
            ]),
            t[28] || (t[28] = s()),
            e("td", null, [
              e("div", qt, [
                x(e("input", {
                  type: "number",
                  class: "form-control form-control-sm",
                  step: a.getPriceStep(n),
                  "onUpdate:modelValue": (d) => n.attachment.price = d,
                  onChange: (d) => a.normalizePricing(n),
                  style: { "min-width": "80px" }
                }, null, 40, jt), [
                  [
                    $,
                    n.attachment.price,
                    void 0,
                    { number: !0 }
                  ]
                ]),
                t[25] || (t[25] = s()),
                n.attachment.method === "percentage" ? (h(), g("span", Dt, `
                                %
                            `)) : D("", !0)
              ])
            ]),
            t[29] || (t[29] = s()),
            e("td", null, [
              x(e("input", {
                type: "number",
                class: "form-control form-control-sm",
                "onUpdate:modelValue": (d) => n.attachment.maxQuantity = d,
                onChange: (d) => a.onMaxQuantityChange(n),
                min: "0",
                max: "30"
              }, null, 40, Lt), [
                [$, n.attachment.maxQuantity]
              ])
            ])
          ]))), 128))
        ])
      ])
    ], 512)
  ]);
}
const zt = /* @__PURE__ */ Q(tt, [["render", Rt], ["__file", "AttachmentProduct.vue"]]), Bt = /* @__PURE__ */ T({
  __name: "AdditionalPurchaseAttachmentEditApp",
  props: {
    attachmentData: {}
  },
  setup(l, { expose: t }) {
    t();
    const i = N("AttachmentProduct", zt), a = l, p = _(
      L(a.attachmentData).map((r) => (r.open = !1, r))
    );
    p.value.length === 1 && (p.value[0].open = !0), J(), B(() => {
      setTimeout(() => {
        const r = window.targetSelected;
        window.targetSelected = function(u) {
          const k = u.value;
          try {
            d(k);
          } catch (v) {
            U(v.message);
            return;
          }
          r(u);
        };
      }, 500);
    });
    const f = _(null);
    function n() {
      const r = "productSelected", u = new URL(K("product_modal"));
      u.searchParams.set("callback", r), window[r] = async function({ title: k, value: v, image: w }) {
        try {
          d(v);
        } catch (P) {
          U(P.message, "", "warning");
          return;
        }
        const { get: o } = await W(), m = await o(`@additional_purchase_ajax/getProductInfo?id=${v}`);
        for (const P of p.value)
          P.open = !1;
        const A = X(m.data.data);
        A.open = !0, p.value.unshift(A), f.value.close();
      }, f.value.open(u, { size: "modal-xl" });
    }
    function d(r) {
      for (const { product: u } of p.value)
        if (Number(u.id) === Number(r))
          throw new Error(E("shopgo.additional.purchase.message.already.selected"));
      for (const u of document.querySelectorAll("#input-item-products-wrap .list-group-item"))
        if (Number(u.dataset.value) === Number(r))
          throw new Error(E("shopgo.additional.purchase.message.already.in.targets"));
    }
    function b(r) {
      p.value.splice(r, 1);
    }
    const y = { AttachmentProduct: i, props: a, attachmentSet: p, productSelector: f, openProductSelector: n, checkAvailable: d, removeProduct: b };
    return Object.defineProperty(y, "__isScriptSetup", { enumerable: !1, value: !0 }), y;
  }
}), It = {
  class: "l-ap-attachments",
  "data-novalidate": ""
}, Ft = { class: "mb-3" }, Gt = {
  key: 1,
  class: "card bg-light"
}, Ot = { class: "card-body text-center py-5" }, Yt = { ref: "productSelector" };
function Ht(l, t, i, a, p, f) {
  return h(), g("div", It, [
    t[2] || (t[2] = e("input", {
      name: "attachments",
      type: "hidden",
      value: "__EMPTY_ARRAY__"
    }, null, -1)),
    t[3] || (t[3] = s()),
    e("div", Ft, [
      a.attachmentSet.length > 0 ? (h(), g("button", {
        key: 0,
        type: "button",
        class: "btn btn-primary btn-sm",
        style: { "min-width": "100px" },
        onClick: a.openProductSelector
      }, [
        t[0] || (t[0] = e("i", { class: "fa fa-plus" }, null, -1)),
        s(" " + c(l.$lang("shopgo.additional.purchase.button.add.product")), 1)
      ])) : D("", !0)
    ]),
    t[4] || (t[4] = s()),
    a.attachmentSet.length > 0 ? (h(), V(I, {
      key: 0,
      name: "fade"
    }, {
      default: F(() => [
        (h(!0), g(q, null, j(a.attachmentSet, ({ product: n, variants: d, open: b }, y) => (h(), V(a.AttachmentProduct, {
          key: n.id,
          product: n,
          variants: d,
          open: b,
          onRemove: (r) => a.removeProduct(y),
          class: "mb-4",
          style: { "animation-duration": ".3s" }
        }, null, 8, ["product", "variants", "open", "onRemove"]))), 128))
      ]),
      _: 1
    })) : (h(), g("div", Gt, [
      e("div", Ot, [
        e("button", {
          type: "button",
          class: "btn btn-primary",
          style: { "min-width": "100px" },
          onClick: a.openProductSelector
        }, [
          t[1] || (t[1] = e("i", { class: "fa fa-plus" }, null, -1)),
          s(" " + c(l.$lang("shopgo.additional.purchase.button.add.product")), 1)
        ])
      ])
    ])),
    t[5] || (t[5] = s()),
    e("uni-iframe-modal", Yt, null, 512)
  ]);
}
const Jt = /* @__PURE__ */ Q(Bt, [["render", Ht], ["__file", "AdditionalPurchaseAttachmentEditApp.vue"]]), Kt = N("AdditionalPurchaseAttachmentEditApp", Jt);
function ne(l) {
  const t = G(Kt, l);
  return t.use(Z), t;
}
export {
  ne as initApp
};
