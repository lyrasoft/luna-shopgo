import { resolveVueComponent as E } from "vite-plugin-vue-component-override";
import { defineComponent as Q, ref as b, computed as L, watch as P, createElementBlock as f, openBlock as m, createElementVNode as e, createTextVNode as s, toDisplayString as u, withDirectives as k, vModelCheckbox as S, normalizeClass as C, Fragment as N, renderList as T, vModelSelect as R, createCommentVNode as q, vModelText as M, onMounted as z, createBlock as $, TransitionGroup as B, withCtx as I, createApp as F } from "vue";
import { slideDown as G, slideUp as O, data as Y, useIframeModal as H, simpleAlert as V, __ as U, route as J, useHttpClient as K } from "@windwalker-io/unicorn-next";
import { uniqueItemList as j, uniqueItem as W } from "@lyrasoft/ts-toolkit/vue";
import { _ as D } from "./_plugin-vue_export-helper.js";
import { ShopGoPlugin as X } from "../index.js";
const Z = /* @__PURE__ */ Q({
  __name: "AttachmentProduct",
  props: {
    product: {},
    variants: {},
    open: { type: Boolean }
  },
  setup(l, { expose: t }) {
    t();
    const i = l, o = b(
      j(i.variants).map((a) => (a.attachment = a.attachment || {
        method: "offsets",
        price: 0,
        maxQuantity: "",
        state: 1
      }, a))
    ), h = b(!1), v = b(i.open);
    function n(a) {
      for (const p of o.value)
        p.attachment.state = a.checked ? 1 : 0;
    }
    const d = L(() => o.value.filter((a) => Number(a.attachment.state) === 1).length);
    function y(a) {
      g(a.attachment.method, "method"), c(a);
    }
    function r(a) {
      let p = a.attachment.maxQuantity;
      p = Math.max(p, 0), p = Math.min(p, 30), a.attachment.maxQuantity = p, g(a.attachment.maxQuantity, "maxQuantity");
    }
    function c(a) {
      a.attachment.method === "percentage" && (a.attachment.price < 0 || a.attachment.price > 100) && (a.attachment.price = Math.min(
        Math.abs(a.attachment.price),
        100
      )), a.attachment.method === "offsets" && a.attachment.price > 0 && (a.attachment.price = -a.attachment.price), a.attachment.method === "fixed" && a.attachment.price < 0 && (a.attachment.price = -a.attachment.price), g(a.attachment.price, "price");
    }
    function g(a, p) {
      if (h.value)
        for (const x of o.value)
          x.attachment[p] = a;
    }
    function _(a) {
      return a.attachment.method === "percentage" ? "1" : Y("price.step") || "0.0001";
    }
    const A = b();
    P(v, (a) => {
      setTimeout(() => {
        a ? G(A.value) : O(A.value);
      }, 0);
    }, { immediate: !0 }), P(() => i.open, (a) => {
      v.value = a;
    });
    const w = { props: i, items: o, syncAll: h, open: v, toggleAll: n, checks: d, onMethodChange: y, onMaxQuantityChange: r, normalizePricing: c, syncAllFields: g, getPriceStep: _, variantList: A };
    return Object.defineProperty(w, "__isScriptSetup", { enumerable: !1, value: !0 }), w;
  }
}), tt = { class: "card c-attachment" }, et = { class: "c-attachment__product card-header border-bottom d-flex gap-3" }, nt = {
  class: "ratio ratio-1x1",
  style: { width: "55px" }
}, ot = ["src"], at = { class: "w-100" }, st = { class: "d-flex align-items-center gap-2 mb-2" }, lt = { class: "m-0" }, it = { class: "ms-auto" }, dt = { class: "d-flex align-items-center gap-2" }, rt = { class: "badge" }, ut = { class: "badge bg-secondary" }, ct = { class: "d-flex gap-3" }, pt = { class: "form-check" }, mt = ["id"], ht = ["for"], ft = { class: "ms-auto" }, vt = {
  class: "c-attachment__variants",
  ref: "variantList",
  style: { overflow: "hidden", display: "none" }
}, gt = { class: "table" }, yt = { style: { width: "1%" } }, _t = ["checked", ".indeterminate"], bt = {
  class: "text-nowrap",
  style: { width: "23%" }
}, xt = {
  class: "text-nowrap",
  style: { width: "15%" }
}, kt = {
  class: "text-nowrap",
  style: { width: "10%" }
}, At = ["id", "onUpdate:modelValue"], wt = ["for"], Pt = { class: "d-none" }, St = ["name", "value"], Ct = ["name", "value"], Mt = ["name", "value"], $t = ["name", "value"], Vt = ["name", "value"], Ut = ["onUpdate:modelValue", "onChange"], Et = { value: "percentage" }, Qt = { value: "offsets" }, Nt = { value: "fixed" }, Tt = { class: "input-group input-group-sm flex-nowrap" }, qt = ["step", "onUpdate:modelValue", "onChange"], jt = {
  key: 0,
  class: "input-group-text"
}, Dt = ["onUpdate:modelValue", "onChange"];
function Lt(l, t, i, o, h, v) {
  return m(), f("div", tt, [
    e("div", et, [
      e("div", null, [
        e("div", nt, [
          e("img", {
            class: "object-fit-cover",
            src: i.product.variant.cover,
            alt: "cover"
          }, null, 8, ot)
        ])
      ]),
      t[13] || (t[13] = s()),
      e("div", at, [
        e("div", st, [
          e("h4", lt, u(i.product.title), 1),
          t[7] || (t[7] = s()),
          t[8] || (t[8] = e("div", null, null, -1)),
          t[9] || (t[9] = s()),
          e("div", it, [
            e("div", dt, [
              e("span", rt, `\r
                            #` + u(i.product.id), 1),
              t[5] || (t[5] = s()),
              e("span", ut, u(l.$lang("shopgo.additional.purchase.text.selected.count", o.checks)), 1),
              t[6] || (t[6] = s()),
              e("button", {
                type: "button",
                class: "btn btn-outline-secondary btn-sm",
                onClick: t[0] || (t[0] = (n) => l.$emit("remove"))
              }, [
                t[4] || (t[4] = e("i", { class: "fa fa-trash" }, null, -1)),
                s(" " + u(l.$lang("shopgo.additional.purchase.button.delete")), 1)
              ])
            ])
          ])
        ]),
        t[12] || (t[12] = s()),
        e("div", ct, [
          e("div", pt, [
            k(e("input", {
              id: `input-sync-all-${i.product.id}`,
              type: "checkbox",
              class: "form-check-input",
              "onUpdate:modelValue": t[1] || (t[1] = (n) => o.syncAll = n)
            }, null, 8, mt), [
              [S, o.syncAll]
            ]),
            t[10] || (t[10] = s()),
            e("label", {
              for: `input-sync-all-${i.product.id}`
            }, [
              e("i", {
                class: C(["fa", [o.syncAll ? "fa-lock" : "fa-unlock"]])
              }, null, 2),
              s(" " + u(l.$lang("shopgo.additional.purchase.text.sync.all")), 1)
            ], 8, ht)
          ]),
          t[11] || (t[11] = s()),
          e("div", ft, [
            e("a", {
              href: "javascript://",
              class: "px-2 py-2",
              onClick: t[2] || (t[2] = (n) => o.open = !o.open)
            }, [
              e("i", {
                class: C(["fa", [o.open ? "fa-chevron-down" : "fa-chevron-up"]])
              }, null, 2)
            ])
          ])
        ])
      ])
    ]),
    t[31] || (t[31] = s()),
    e("div", vt, [
      e("table", gt, [
        e("thead", null, [
          e("tr", null, [
            e("th", yt, [
              e("input", {
                type: "checkbox",
                class: "form-check-input",
                checked: o.checks === o.items.length,
                ".indeterminate": o.checks !== 0 && o.checks < o.items.length,
                onClick: t[3] || (t[3] = (n) => o.toggleAll(n.target))
              }, null, 40, _t)
            ]),
            t[14] || (t[14] = s()),
            e("th", null, u(l.$lang("unicorn.field.title")), 1),
            t[15] || (t[15] = s()),
            e("th", bt, u(l.$lang("shopgo.additional.purchase.field.method")), 1),
            t[16] || (t[16] = s()),
            e("th", xt, u(l.$lang("shopgo.additional.purchase.field.pricing")), 1),
            t[17] || (t[17] = s()),
            e("th", kt, u(l.$lang("shopgo.additional.purchase.field.max.quantity")), 1)
          ])
        ]),
        t[30] || (t[30] = s()),
        e("tbody", null, [
          (m(!0), f(N, null, T(o.items, (n) => (m(), f("tr", {
            key: n.id,
            class: ""
          }, [
            e("td", null, [
              k(e("input", {
                type: "checkbox",
                id: `input-variant-${n.id}`,
                class: "form-check-input",
                "onUpdate:modelValue": (d) => n.attachment.state = d,
                "true-value": 1,
                "false-value": 0
              }, null, 8, At), [
                [S, n.attachment.state]
              ])
            ]),
            t[26] || (t[26] = s()),
            e("td", null, [
              e("label", {
                for: `input-variant-${n.id}`
              }, u(n.title), 9, wt),
              t[22] || (t[22] = s()),
              e("div", Pt, [
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][id]`,
                  value: n.attachment?.id,
                  type: "hidden"
                }, null, 8, St),
                t[18] || (t[18] = s()),
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][method]`,
                  value: n.attachment.method,
                  type: "hidden"
                }, null, 8, Ct),
                t[19] || (t[19] = s()),
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][price]`,
                  value: n.attachment.price,
                  type: "hidden"
                }, null, 8, Mt),
                t[20] || (t[20] = s()),
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][max_quantity]`,
                  value: n.attachment.maxQuantity,
                  type: "hidden"
                }, null, 8, $t),
                t[21] || (t[21] = s()),
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][state]`,
                  value: n.attachment.state,
                  type: "hidden"
                }, null, 8, Vt)
              ])
            ]),
            t[27] || (t[27] = s()),
            e("td", null, [
              k(e("select", {
                class: "form-select form-select-sm",
                "onUpdate:modelValue": (d) => n.attachment.method = d,
                onChange: (d) => o.onMethodChange(n)
              }, [
                e("option", Et, u(l.$lang("shopgo.discount.method.percentage")), 1),
                t[23] || (t[23] = s()),
                e("option", Qt, u(l.$lang("shopgo.discount.method.offsets")), 1),
                t[24] || (t[24] = s()),
                e("option", Nt, u(l.$lang("shopgo.discount.method.fixed")), 1)
              ], 40, Ut), [
                [R, n.attachment.method]
              ])
            ]),
            t[28] || (t[28] = s()),
            e("td", null, [
              e("div", Tt, [
                k(e("input", {
                  type: "number",
                  class: "form-control form-control-sm",
                  step: o.getPriceStep(n),
                  "onUpdate:modelValue": (d) => n.attachment.price = d,
                  onChange: (d) => o.normalizePricing(n),
                  style: { "min-width": "80px" }
                }, null, 40, qt), [
                  [
                    M,
                    n.attachment.price,
                    void 0,
                    { number: !0 }
                  ]
                ]),
                t[25] || (t[25] = s()),
                n.attachment.method === "percentage" ? (m(), f("span", jt, `\r
                                %\r
                            `)) : q("", !0)
              ])
            ]),
            t[29] || (t[29] = s()),
            e("td", null, [
              k(e("input", {
                type: "number",
                class: "form-control form-control-sm",
                "onUpdate:modelValue": (d) => n.attachment.maxQuantity = d,
                onChange: (d) => o.onMaxQuantityChange(n),
                min: "0",
                max: "30"
              }, null, 40, Dt), [
                [M, n.attachment.maxQuantity]
              ])
            ])
          ]))), 128))
        ])
      ])
    ], 512)
  ]);
}
const Rt = /* @__PURE__ */ D(Z, [["render", Lt], ["__file", "AttachmentProduct.vue"]]), zt = E("~shopgo/modules/additional-purchase/AttachmentProduct.vue", Rt), Bt = /* @__PURE__ */ Q({
  __name: "AdditionalPurchaseAttachmentEditApp",
  props: {
    attachmentData: {}
  },
  setup(l, { expose: t }) {
    t();
    const i = l, o = b(
      j(i.attachmentData).map((r) => (r.open = !1, r))
    );
    o.value.length === 1 && (o.value[0].open = !0), H(), z(() => {
      setTimeout(() => {
        const r = window.targetSelected;
        window.targetSelected = function(c) {
          const g = c.value;
          try {
            n(g);
          } catch (_) {
            V(_.message);
            return;
          }
          r(c);
        };
      }, 500);
    });
    const h = b(null);
    function v() {
      const r = "productSelected", c = new URL(J("product_modal"));
      c.searchParams.set("callback", r), window[r] = async function({ title: g, value: _, image: A }) {
        try {
          n(_);
        } catch (x) {
          V(x.message, "", "warning");
          return;
        }
        const { get: w } = await K(), a = await w(`@additional_purchase_ajax/getProductInfo?id=${_}`);
        for (const x of o.value)
          x.open = !1;
        const p = W(a.data.data);
        p.open = !0, o.value.unshift(p), h.value.close();
      }, h.value.open(c, { size: "modal-xl" });
    }
    function n(r) {
      for (const { product: c } of o.value)
        if (Number(c.id) === Number(r))
          throw new Error(U("shopgo.additional.purchase.message.already.selected"));
      for (const c of document.querySelectorAll("#input-item-products-wrap .list-group-item"))
        if (Number(c.dataset.value) === Number(r))
          throw new Error(U("shopgo.additional.purchase.message.already.in.targets"));
    }
    function d(r) {
      o.value.splice(r, 1);
    }
    const y = { props: i, attachmentSet: o, productSelector: h, openProductSelector: v, checkAvailable: n, removeProduct: d, AttachmentProduct: zt };
    return Object.defineProperty(y, "__isScriptSetup", { enumerable: !1, value: !0 }), y;
  }
}), It = {
  class: "l-ap-attachments",
  "data-novalidate": ""
}, Ft = { class: "mb-3" }, Gt = {
  key: 1,
  class: "card bg-light"
}, Ot = { class: "card-body text-center py-5" }, Yt = { ref: "productSelector" };
function Ht(l, t, i, o, h, v) {
  return m(), f("div", It, [
    t[2] || (t[2] = e("input", {
      name: "attachments",
      type: "hidden",
      value: "__EMPTY_ARRAY__"
    }, null, -1)),
    t[3] || (t[3] = s()),
    e("div", Ft, [
      o.attachmentSet.length > 0 ? (m(), f("button", {
        key: 0,
        type: "button",
        class: "btn btn-primary btn-sm",
        style: { "min-width": "100px" },
        onClick: o.openProductSelector
      }, [
        t[0] || (t[0] = e("i", { class: "fa fa-plus" }, null, -1)),
        s(" " + u(l.$lang("shopgo.additional.purchase.button.add.product")), 1)
      ])) : q("", !0)
    ]),
    t[4] || (t[4] = s()),
    o.attachmentSet.length > 0 ? (m(), $(B, {
      key: 0,
      name: "fade"
    }, {
      default: I(() => [
        (m(!0), f(N, null, T(o.attachmentSet, ({ product: n, variants: d, open: y }, r) => (m(), $(o.AttachmentProduct, {
          key: n.id,
          product: n,
          variants: d,
          open: y,
          onRemove: (c) => o.removeProduct(r),
          class: "mb-4",
          style: { "animation-duration": ".3s" }
        }, null, 8, ["product", "variants", "open", "onRemove"]))), 128))
      ]),
      _: 1
    })) : (m(), f("div", Gt, [
      e("div", Ot, [
        e("button", {
          type: "button",
          class: "btn btn-primary",
          style: { "min-width": "100px" },
          onClick: o.openProductSelector
        }, [
          t[1] || (t[1] = e("i", { class: "fa fa-plus" }, null, -1)),
          s(" " + u(l.$lang("shopgo.additional.purchase.button.add.product")), 1)
        ])
      ])
    ])),
    t[5] || (t[5] = s()),
    e("uni-iframe-modal", Yt, null, 512)
  ]);
}
const Jt = /* @__PURE__ */ D(Bt, [["render", Ht], ["__file", "AdditionalPurchaseAttachmentEditApp.vue"]]), Kt = E("~shopgo/modules/additional-purchase/AdditionalPurchaseAttachmentEditApp.vue", Jt);
function oe(l) {
  const t = F(Kt, l);
  return t.use(X), t;
}
export {
  oe as initApp
};
//# sourceMappingURL=additional-purchase-attachment-edit.js.map
