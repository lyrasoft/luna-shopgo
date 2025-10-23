import { defineComponent as N, ref as _, computed as D, watch as S, createElementBlock as v, openBlock as h, createElementVNode as e, createTextVNode as s, toDisplayString as u, withDirectives as k, vModelCheckbox as C, normalizeClass as P, Fragment as Q, renderList as E, vModelSelect as L, createCommentVNode as q, vModelText as M, onMounted as R, resolveComponent as z, createBlock as V, createVNode as B, TransitionGroup as I, withCtx as O, createApp as F } from "vue";
import { slideDown as G, slideUp as Y, data as H, useIframeModal as J, simpleAlert as $, __ as U, route as K, useHttpClient as W } from "@windwalker-io/unicorn-next";
import { uniqueItemList as T, uniqueItem as X } from "@lyrasoft/ts-toolkit/vue";
import { ShopGoPlugin as Z } from "../index.js";
const tt = /* @__PURE__ */ N({
  __name: "AttachmentProduct",
  props: {
    product: {},
    variants: {},
    open: { type: Boolean }
  },
  setup(i, { expose: t }) {
    t();
    const l = i, n = _(
      T(l.variants).map((a) => (a.attachment = a.attachment || {
        method: "offsets",
        price: 0,
        maxQuantity: "",
        state: 1
      }, a))
    ), m = _(!1), g = _(l.open);
    function o(a) {
      for (const p of n.value)
        p.attachment.state = a.checked ? 1 : 0;
    }
    const d = D(() => n.value.filter((a) => Number(a.attachment.state) === 1).length);
    function y(a) {
      f(a.attachment.method, "method"), c(a);
    }
    function r(a) {
      let p = a.attachment.maxQuantity;
      p = Math.max(p, 0), p = Math.min(p, 30), a.attachment.maxQuantity = p, f(a.attachment.maxQuantity, "maxQuantity");
    }
    function c(a) {
      a.attachment.method === "percentage" && (a.attachment.price < 0 || a.attachment.price > 100) && (a.attachment.price = Math.min(
        Math.abs(a.attachment.price),
        100
      )), a.attachment.method === "offsets" && a.attachment.price > 0 && (a.attachment.price = -a.attachment.price), a.attachment.method === "fixed" && a.attachment.price < 0 && (a.attachment.price = -a.attachment.price), f(a.attachment.price, "price");
    }
    function f(a, p) {
      if (m.value)
        for (const x of n.value)
          x.attachment[p] = a;
    }
    function b(a) {
      return a.attachment.method === "percentage" ? "1" : H("price.step") || "0.0001";
    }
    const w = _();
    S(g, (a) => {
      setTimeout(() => {
        a ? G(w.value) : Y(w.value);
      }, 0);
    }, { immediate: !0 }), S(() => l.open, (a) => {
      g.value = a;
    });
    const A = { props: l, items: n, syncAll: m, open: g, toggleAll: o, checks: d, onMethodChange: y, onMaxQuantityChange: r, normalizePricing: c, syncAllFields: f, getPriceStep: b, variantList: w };
    return Object.defineProperty(A, "__isScriptSetup", { enumerable: !1, value: !0 }), A;
  }
}), j = (i, t) => {
  const l = i.__vccOpts || i;
  for (const [n, m] of t)
    l[n] = m;
  return l;
}, et = { class: "card c-attachment" }, nt = { class: "c-attachment__product card-header border-bottom d-flex gap-3" }, ot = {
  class: "ratio ratio-1x1",
  style: { width: "55px" }
}, at = ["src"], st = { class: "w-100" }, lt = { class: "d-flex align-items-center gap-2 mb-2" }, it = { class: "m-0" }, dt = { class: "ms-auto" }, rt = { class: "d-flex align-items-center gap-2" }, ut = { class: "badge" }, ct = { class: "badge bg-secondary" }, pt = { class: "d-flex gap-3" }, mt = { class: "form-check" }, ht = ["id"], ft = ["for"], vt = { class: "ms-auto" }, gt = {
  class: "c-attachment__variants",
  ref: "variantList",
  style: { overflow: "hidden", display: "none" }
}, yt = { class: "table" }, bt = { style: { width: "1%" } }, _t = ["checked", ".indeterminate"], xt = {
  class: "text-nowrap",
  style: { width: "23%" }
}, kt = {
  class: "text-nowrap",
  style: { width: "15%" }
}, wt = {
  class: "text-nowrap",
  style: { width: "10%" }
}, At = ["id", "onUpdate:modelValue"], St = ["for"], Ct = { class: "d-none" }, Pt = ["name", "value"], Mt = ["name", "value"], Vt = ["name", "value"], $t = ["name", "value"], Ut = ["name", "value"], Nt = ["onUpdate:modelValue", "onChange"], Qt = { value: "percentage" }, Et = { value: "offsets" }, qt = { value: "fixed" }, Tt = { class: "input-group input-group-sm flex-nowrap" }, jt = ["step", "onUpdate:modelValue", "onChange"], Dt = {
  key: 0,
  class: "input-group-text"
}, Lt = ["onUpdate:modelValue", "onChange"];
function Rt(i, t, l, n, m, g) {
  return h(), v("div", et, [
    e("div", nt, [
      e("div", null, [
        e("div", ot, [
          e("img", {
            class: "object-fit-cover",
            src: l.product.variant.cover,
            alt: "cover"
          }, null, 8, at)
        ])
      ]),
      t[13] || (t[13] = s()),
      e("div", st, [
        e("div", lt, [
          e("h4", it, u(l.product.title), 1),
          t[7] || (t[7] = s()),
          t[8] || (t[8] = e("div", null, null, -1)),
          t[9] || (t[9] = s()),
          e("div", dt, [
            e("div", rt, [
              e("span", ut, `
                            #` + u(l.product.id), 1),
              t[5] || (t[5] = s()),
              e("span", ct, u(i.$lang("shopgo.additional.purchase.text.selected.count", n.checks)), 1),
              t[6] || (t[6] = s()),
              e("button", {
                type: "button",
                class: "btn btn-outline-secondary btn-sm",
                onClick: t[0] || (t[0] = (o) => i.$emit("remove"))
              }, [
                t[4] || (t[4] = e("i", { class: "fa fa-trash" }, null, -1)),
                s(" " + u(i.$lang("shopgo.additional.purchase.button.delete")), 1)
              ])
            ])
          ])
        ]),
        t[12] || (t[12] = s()),
        e("div", pt, [
          e("div", mt, [
            k(e("input", {
              id: `input-sync-all-${l.product.id}`,
              type: "checkbox",
              class: "form-check-input",
              "onUpdate:modelValue": t[1] || (t[1] = (o) => n.syncAll = o)
            }, null, 8, ht), [
              [C, n.syncAll]
            ]),
            t[10] || (t[10] = s()),
            e("label", {
              for: `input-sync-all-${l.product.id}`
            }, [
              e("i", {
                class: P(["fa", [n.syncAll ? "fa-lock" : "fa-unlock"]])
              }, null, 2),
              s(" " + u(i.$lang("shopgo.additional.purchase.text.sync.all")), 1)
            ], 8, ft)
          ]),
          t[11] || (t[11] = s()),
          e("div", vt, [
            e("a", {
              href: "javascript://",
              class: "px-2 py-2",
              onClick: t[2] || (t[2] = (o) => n.open = !n.open)
            }, [
              e("i", {
                class: P(["fa", [n.open ? "fa-chevron-down" : "fa-chevron-up"]])
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
            e("th", bt, [
              e("input", {
                type: "checkbox",
                class: "form-check-input",
                checked: n.checks === n.items.length,
                ".indeterminate": n.checks !== 0 && n.checks < n.items.length,
                onClick: t[3] || (t[3] = (o) => n.toggleAll(o.target))
              }, null, 40, _t)
            ]),
            t[14] || (t[14] = s()),
            e("th", null, u(i.$lang("unicorn.field.title")), 1),
            t[15] || (t[15] = s()),
            e("th", xt, u(i.$lang("shopgo.additional.purchase.field.method")), 1),
            t[16] || (t[16] = s()),
            e("th", kt, u(i.$lang("shopgo.additional.purchase.field.pricing")), 1),
            t[17] || (t[17] = s()),
            e("th", wt, u(i.$lang("shopgo.additional.purchase.field.max.quantity")), 1)
          ])
        ]),
        t[30] || (t[30] = s()),
        e("tbody", null, [
          (h(!0), v(Q, null, E(n.items, (o) => (h(), v("tr", {
            key: o.id,
            class: ""
          }, [
            e("td", null, [
              k(e("input", {
                type: "checkbox",
                id: `input-variant-${o.id}`,
                class: "form-check-input",
                "onUpdate:modelValue": (d) => o.attachment.state = d,
                "true-value": 1,
                "false-value": 0
              }, null, 8, At), [
                [C, o.attachment.state]
              ])
            ]),
            t[26] || (t[26] = s()),
            e("td", null, [
              e("label", {
                for: `input-variant-${o.id}`
              }, u(o.title), 9, St),
              t[22] || (t[22] = s()),
              e("div", Ct, [
                e("input", {
                  name: `attachments[${l.product.id}][${o.id}][id]`,
                  value: o.attachment?.id,
                  type: "hidden"
                }, null, 8, Pt),
                t[18] || (t[18] = s()),
                e("input", {
                  name: `attachments[${l.product.id}][${o.id}][method]`,
                  value: o.attachment.method,
                  type: "hidden"
                }, null, 8, Mt),
                t[19] || (t[19] = s()),
                e("input", {
                  name: `attachments[${l.product.id}][${o.id}][price]`,
                  value: o.attachment.price,
                  type: "hidden"
                }, null, 8, Vt),
                t[20] || (t[20] = s()),
                e("input", {
                  name: `attachments[${l.product.id}][${o.id}][max_quantity]`,
                  value: o.attachment.maxQuantity,
                  type: "hidden"
                }, null, 8, $t),
                t[21] || (t[21] = s()),
                e("input", {
                  name: `attachments[${l.product.id}][${o.id}][state]`,
                  value: o.attachment.state,
                  type: "hidden"
                }, null, 8, Ut)
              ])
            ]),
            t[27] || (t[27] = s()),
            e("td", null, [
              k(e("select", {
                class: "form-select form-select-sm",
                "onUpdate:modelValue": (d) => o.attachment.method = d,
                onChange: (d) => n.onMethodChange(o)
              }, [
                e("option", Qt, u(i.$lang("shopgo.discount.method.percentage")), 1),
                t[23] || (t[23] = s()),
                e("option", Et, u(i.$lang("shopgo.discount.method.offsets")), 1),
                t[24] || (t[24] = s()),
                e("option", qt, u(i.$lang("shopgo.discount.method.fixed")), 1)
              ], 40, Nt), [
                [L, o.attachment.method]
              ])
            ]),
            t[28] || (t[28] = s()),
            e("td", null, [
              e("div", Tt, [
                k(e("input", {
                  type: "number",
                  class: "form-control form-control-sm",
                  step: n.getPriceStep(o),
                  "onUpdate:modelValue": (d) => o.attachment.price = d,
                  onChange: (d) => n.normalizePricing(o),
                  style: { "min-width": "80px" }
                }, null, 40, jt), [
                  [
                    M,
                    o.attachment.price,
                    void 0,
                    { number: !0 }
                  ]
                ]),
                t[25] || (t[25] = s()),
                o.attachment.method === "percentage" ? (h(), v("span", Dt, `
                                %
                            `)) : q("", !0)
              ])
            ]),
            t[29] || (t[29] = s()),
            e("td", null, [
              k(e("input", {
                type: "number",
                class: "form-control form-control-sm",
                "onUpdate:modelValue": (d) => o.attachment.maxQuantity = d,
                onChange: (d) => n.onMaxQuantityChange(o),
                min: "0",
                max: "30"
              }, null, 40, Lt), [
                [M, o.attachment.maxQuantity]
              ])
            ])
          ]))), 128))
        ])
      ])
    ], 512)
  ]);
}
const zt = /* @__PURE__ */ j(tt, [["render", Rt], ["__file", "AttachmentProduct.vue"]]), Bt = /* @__PURE__ */ N({
  __name: "AdditionalPurchaseAttachmentEditApp",
  props: {
    attachmentData: {}
  },
  setup(i, { expose: t }) {
    t();
    const l = i, n = _(
      T(l.attachmentData).map((r) => (r.open = !1, r))
    );
    n.value.length === 1 && (n.value[0].open = !0), J(), R(() => {
      setTimeout(() => {
        const r = window.targetSelected;
        window.targetSelected = function(c) {
          const f = c.value;
          try {
            o(f);
          } catch (b) {
            $(b.message);
            return;
          }
          r(c);
        };
      }, 500);
    });
    const m = _(null);
    function g() {
      const r = "productSelected", c = new URL(K("product_modal"));
      c.searchParams.set("callback", r), window[r] = async function({ title: f, value: b, image: w }) {
        try {
          o(b);
        } catch (x) {
          $(x.message, "", "warning");
          return;
        }
        const { get: A } = await W(), a = await A(`@additional_purchase_ajax/getProductInfo?id=${b}`);
        for (const x of n.value)
          x.open = !1;
        const p = X(a.data.data);
        p.open = !0, n.value.unshift(p), m.value.close();
      }, m.value.open(c, { size: "modal-xl" });
    }
    function o(r) {
      for (const { product: c } of n.value)
        if (Number(c.id) === Number(r))
          throw new Error(U("shopgo.additional.purchase.message.already.selected"));
      for (const c of document.querySelectorAll("#input-item-products-wrap .list-group-item"))
        if (Number(c.dataset.value) === Number(r))
          throw new Error(U("shopgo.additional.purchase.message.already.in.targets"));
    }
    function d(r) {
      n.value.splice(r, 1);
    }
    const y = { props: l, attachmentSet: n, productSelector: m, openProductSelector: g, checkAvailable: o, removeProduct: d, AttachmentProduct: zt };
    return Object.defineProperty(y, "__isScriptSetup", { enumerable: !1, value: !0 }), y;
  }
}), It = {
  class: "l-ap-attachments",
  "data-novalidate": ""
}, Ot = { class: "mb-3" }, Ft = {
  key: 1,
  class: "card bg-light"
}, Gt = { class: "card-body text-center py-5" };
function Yt(i, t, l, n, m, g) {
  const o = z("uni-iframe-modal");
  return h(), v("div", It, [
    t[2] || (t[2] = e("input", {
      name: "attachments",
      type: "hidden",
      value: "__EMPTY_ARRAY__"
    }, null, -1)),
    t[3] || (t[3] = s()),
    e("div", Ot, [
      n.attachmentSet.length > 0 ? (h(), v("button", {
        key: 0,
        type: "button",
        class: "btn btn-primary btn-sm",
        style: { "min-width": "100px" },
        onClick: n.openProductSelector
      }, [
        t[0] || (t[0] = e("i", { class: "fa fa-plus" }, null, -1)),
        s(" " + u(i.$lang("shopgo.additional.purchase.button.add.product")), 1)
      ])) : q("", !0)
    ]),
    t[4] || (t[4] = s()),
    n.attachmentSet.length > 0 ? (h(), V(I, {
      key: 0,
      name: "fade"
    }, {
      default: O(() => [
        (h(!0), v(Q, null, E(n.attachmentSet, ({ product: d, variants: y, open: r }, c) => (h(), V(n.AttachmentProduct, {
          key: d.id,
          product: d,
          variants: y,
          open: r,
          onRemove: (f) => n.removeProduct(c),
          class: "mb-4",
          style: { "animation-duration": ".3s" }
        }, null, 8, ["product", "variants", "open", "onRemove"]))), 128))
      ]),
      _: 1
    })) : (h(), v("div", Ft, [
      e("div", Gt, [
        e("button", {
          type: "button",
          class: "btn btn-primary",
          style: { "min-width": "100px" },
          onClick: n.openProductSelector
        }, [
          t[1] || (t[1] = e("i", { class: "fa fa-plus" }, null, -1)),
          s(" " + u(i.$lang("shopgo.additional.purchase.button.add.product")), 1)
        ])
      ])
    ])),
    t[5] || (t[5] = s()),
    B(o, { ref: "productSelector" }, null, 512)
  ]);
}
const Ht = /* @__PURE__ */ j(Bt, [["render", Yt], ["__file", "AdditionalPurchaseAttachmentEditApp.vue"]]);
function Zt(i, t) {
  const l = F(Ht, t);
  return l.use(Z), l.mount(i);
}
export {
  Zt as init
};
//# sourceMappingURL=additional-purchase-attachment-edit.js.map
