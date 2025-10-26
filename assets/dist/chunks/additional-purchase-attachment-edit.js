import { defineComponent as Q, ref as _, computed as D, watch as S, createElementBlock as f, openBlock as m, createElementVNode as e, createTextVNode as s, toDisplayString as u, withDirectives as k, vModelCheckbox as P, normalizeClass as C, Fragment as N, renderList as E, vModelSelect as L, createCommentVNode as q, vModelText as M, onMounted as R, createBlock as $, TransitionGroup as z, withCtx as B, createApp as I } from "vue";
import { slideDown as F, slideUp as G, data as O, useIframeModal as Y, simpleAlert as V, __ as U, route as H, useHttpClient as J } from "@windwalker-io/unicorn-next";
import { uniqueItemList as T, uniqueItem as K } from "@lyrasoft/ts-toolkit/vue";
import { _ as j } from "./_plugin-vue_export-helper.js";
import { ShopGoPlugin as W } from "../index.js";
const X = /* @__PURE__ */ Q({
  __name: "AttachmentProduct",
  props: {
    product: {},
    variants: {},
    open: { type: Boolean }
  },
  setup(l, { expose: t }) {
    t();
    const i = l, o = _(
      T(i.variants).map((a) => (a.attachment = a.attachment || {
        method: "offsets",
        price: 0,
        maxQuantity: "",
        state: 1
      }, a))
    ), h = _(!1), v = _(i.open);
    function n(a) {
      for (const p of o.value)
        p.attachment.state = a.checked ? 1 : 0;
    }
    const d = D(() => o.value.filter((a) => Number(a.attachment.state) === 1).length);
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
    function b(a) {
      return a.attachment.method === "percentage" ? "1" : O("price.step") || "0.0001";
    }
    const w = _();
    S(v, (a) => {
      setTimeout(() => {
        a ? F(w.value) : G(w.value);
      }, 0);
    }, { immediate: !0 }), S(() => i.open, (a) => {
      v.value = a;
    });
    const A = { props: i, items: o, syncAll: h, open: v, toggleAll: n, checks: d, onMethodChange: y, onMaxQuantityChange: r, normalizePricing: c, syncAllFields: g, getPriceStep: b, variantList: w };
    return Object.defineProperty(A, "__isScriptSetup", { enumerable: !1, value: !0 }), A;
  }
}), Z = { class: "card c-attachment" }, tt = { class: "c-attachment__product card-header border-bottom d-flex gap-3" }, et = {
  class: "ratio ratio-1x1",
  style: { width: "55px" }
}, nt = ["src"], ot = { class: "w-100" }, at = { class: "d-flex align-items-center gap-2 mb-2" }, st = { class: "m-0" }, lt = { class: "ms-auto" }, it = { class: "d-flex align-items-center gap-2" }, dt = { class: "badge" }, rt = { class: "badge bg-secondary" }, ut = { class: "d-flex gap-3" }, ct = { class: "form-check" }, pt = ["id"], mt = ["for"], ht = { class: "ms-auto" }, ft = {
  class: "c-attachment__variants",
  ref: "variantList",
  style: { overflow: "hidden", display: "none" }
}, vt = { class: "table" }, gt = { style: { width: "1%" } }, yt = ["checked", ".indeterminate"], bt = {
  class: "text-nowrap",
  style: { width: "23%" }
}, _t = {
  class: "text-nowrap",
  style: { width: "15%" }
}, xt = {
  class: "text-nowrap",
  style: { width: "10%" }
}, kt = ["id", "onUpdate:modelValue"], wt = ["for"], At = { class: "d-none" }, St = ["name", "value"], Pt = ["name", "value"], Ct = ["name", "value"], Mt = ["name", "value"], $t = ["name", "value"], Vt = ["onUpdate:modelValue", "onChange"], Ut = { value: "percentage" }, Qt = { value: "offsets" }, Nt = { value: "fixed" }, Et = { class: "input-group input-group-sm flex-nowrap" }, qt = ["step", "onUpdate:modelValue", "onChange"], Tt = {
  key: 0,
  class: "input-group-text"
}, jt = ["onUpdate:modelValue", "onChange"];
function Dt(l, t, i, o, h, v) {
  return m(), f("div", Z, [
    e("div", tt, [
      e("div", null, [
        e("div", et, [
          e("img", {
            class: "object-fit-cover",
            src: i.product.variant.cover,
            alt: "cover"
          }, null, 8, nt)
        ])
      ]),
      t[13] || (t[13] = s()),
      e("div", ot, [
        e("div", at, [
          e("h4", st, u(i.product.title), 1),
          t[7] || (t[7] = s()),
          t[8] || (t[8] = e("div", null, null, -1)),
          t[9] || (t[9] = s()),
          e("div", lt, [
            e("div", it, [
              e("span", dt, `
                            #` + u(i.product.id), 1),
              t[5] || (t[5] = s()),
              e("span", rt, u(l.$lang("shopgo.additional.purchase.text.selected.count", o.checks)), 1),
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
        e("div", ut, [
          e("div", ct, [
            k(e("input", {
              id: `input-sync-all-${i.product.id}`,
              type: "checkbox",
              class: "form-check-input",
              "onUpdate:modelValue": t[1] || (t[1] = (n) => o.syncAll = n)
            }, null, 8, pt), [
              [P, o.syncAll]
            ]),
            t[10] || (t[10] = s()),
            e("label", {
              for: `input-sync-all-${i.product.id}`
            }, [
              e("i", {
                class: C(["fa", [o.syncAll ? "fa-lock" : "fa-unlock"]])
              }, null, 2),
              s(" " + u(l.$lang("shopgo.additional.purchase.text.sync.all")), 1)
            ], 8, mt)
          ]),
          t[11] || (t[11] = s()),
          e("div", ht, [
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
    e("div", ft, [
      e("table", vt, [
        e("thead", null, [
          e("tr", null, [
            e("th", gt, [
              e("input", {
                type: "checkbox",
                class: "form-check-input",
                checked: o.checks === o.items.length,
                ".indeterminate": o.checks !== 0 && o.checks < o.items.length,
                onClick: t[3] || (t[3] = (n) => o.toggleAll(n.target))
              }, null, 40, yt)
            ]),
            t[14] || (t[14] = s()),
            e("th", null, u(l.$lang("unicorn.field.title")), 1),
            t[15] || (t[15] = s()),
            e("th", bt, u(l.$lang("shopgo.additional.purchase.field.method")), 1),
            t[16] || (t[16] = s()),
            e("th", _t, u(l.$lang("shopgo.additional.purchase.field.pricing")), 1),
            t[17] || (t[17] = s()),
            e("th", xt, u(l.$lang("shopgo.additional.purchase.field.max.quantity")), 1)
          ])
        ]),
        t[30] || (t[30] = s()),
        e("tbody", null, [
          (m(!0), f(N, null, E(o.items, (n) => (m(), f("tr", {
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
              }, null, 8, kt), [
                [P, n.attachment.state]
              ])
            ]),
            t[26] || (t[26] = s()),
            e("td", null, [
              e("label", {
                for: `input-variant-${n.id}`
              }, u(n.title), 9, wt),
              t[22] || (t[22] = s()),
              e("div", At, [
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
                }, null, 8, Pt),
                t[19] || (t[19] = s()),
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][price]`,
                  value: n.attachment.price,
                  type: "hidden"
                }, null, 8, Ct),
                t[20] || (t[20] = s()),
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][max_quantity]`,
                  value: n.attachment.maxQuantity,
                  type: "hidden"
                }, null, 8, Mt),
                t[21] || (t[21] = s()),
                e("input", {
                  name: `attachments[${i.product.id}][${n.id}][state]`,
                  value: n.attachment.state,
                  type: "hidden"
                }, null, 8, $t)
              ])
            ]),
            t[27] || (t[27] = s()),
            e("td", null, [
              k(e("select", {
                class: "form-select form-select-sm",
                "onUpdate:modelValue": (d) => n.attachment.method = d,
                onChange: (d) => o.onMethodChange(n)
              }, [
                e("option", Ut, u(l.$lang("shopgo.discount.method.percentage")), 1),
                t[23] || (t[23] = s()),
                e("option", Qt, u(l.$lang("shopgo.discount.method.offsets")), 1),
                t[24] || (t[24] = s()),
                e("option", Nt, u(l.$lang("shopgo.discount.method.fixed")), 1)
              ], 40, Vt), [
                [L, n.attachment.method]
              ])
            ]),
            t[28] || (t[28] = s()),
            e("td", null, [
              e("div", Et, [
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
                n.attachment.method === "percentage" ? (m(), f("span", Tt, `
                                %
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
              }, null, 40, jt), [
                [M, n.attachment.maxQuantity]
              ])
            ])
          ]))), 128))
        ])
      ])
    ], 512)
  ]);
}
const Lt = /* @__PURE__ */ j(X, [["render", Dt], ["__file", "AttachmentProduct.vue"]]), Rt = /* @__PURE__ */ Q({
  __name: "AdditionalPurchaseAttachmentEditApp",
  props: {
    attachmentData: {}
  },
  setup(l, { expose: t }) {
    t();
    const i = l, o = _(
      T(i.attachmentData).map((r) => (r.open = !1, r))
    );
    o.value.length === 1 && (o.value[0].open = !0), Y(), R(() => {
      setTimeout(() => {
        const r = window.targetSelected;
        window.targetSelected = function(c) {
          const g = c.value;
          try {
            n(g);
          } catch (b) {
            V(b.message);
            return;
          }
          r(c);
        };
      }, 500);
    });
    const h = _(null);
    function v() {
      const r = "productSelected", c = new URL(H("product_modal"));
      c.searchParams.set("callback", r), window[r] = async function({ title: g, value: b, image: w }) {
        try {
          n(b);
        } catch (x) {
          V(x.message, "", "warning");
          return;
        }
        const { get: A } = await J(), a = await A(`@additional_purchase_ajax/getProductInfo?id=${b}`);
        for (const x of o.value)
          x.open = !1;
        const p = K(a.data.data);
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
    const y = { props: i, attachmentSet: o, productSelector: h, openProductSelector: v, checkAvailable: n, removeProduct: d, AttachmentProduct: Lt };
    return Object.defineProperty(y, "__isScriptSetup", { enumerable: !1, value: !0 }), y;
  }
}), zt = {
  class: "l-ap-attachments",
  "data-novalidate": ""
}, Bt = { class: "mb-3" }, It = {
  key: 1,
  class: "card bg-light"
}, Ft = { class: "card-body text-center py-5" }, Gt = { ref: "productSelector" };
function Ot(l, t, i, o, h, v) {
  return m(), f("div", zt, [
    t[2] || (t[2] = e("input", {
      name: "attachments",
      type: "hidden",
      value: "__EMPTY_ARRAY__"
    }, null, -1)),
    t[3] || (t[3] = s()),
    e("div", Bt, [
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
    o.attachmentSet.length > 0 ? (m(), $(z, {
      key: 0,
      name: "fade"
    }, {
      default: B(() => [
        (m(!0), f(N, null, E(o.attachmentSet, ({ product: n, variants: d, open: y }, r) => (m(), $(o.AttachmentProduct, {
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
    })) : (m(), f("div", It, [
      e("div", Ft, [
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
    e("uni-iframe-modal", Gt, null, 512)
  ]);
}
const Yt = /* @__PURE__ */ j(Rt, [["render", Ot], ["__file", "AdditionalPurchaseAttachmentEditApp.vue"]]);
function Zt(l) {
  const t = I(Yt, l);
  return t.use(W), t;
}
export {
  Zt as initApp
};
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYWRkaXRpb25hbC1wdXJjaGFzZS1hdHRhY2htZW50LWVkaXQuanMiLCJzb3VyY2VzIjpbIi4uLy4uL3NyYy9tb2R1bGVzL2FkZGl0aW9uYWwtcHVyY2hhc2UvQXR0YWNobWVudFByb2R1Y3QudnVlIiwiLi4vLi4vc3JjL21vZHVsZXMvYWRkaXRpb25hbC1wdXJjaGFzZS9BZGRpdGlvbmFsUHVyY2hhc2VBdHRhY2htZW50RWRpdEFwcC52dWUiLCIuLi8uLi9zcmMvbW9kdWxlcy9hZGRpdGlvbmFsLXB1cmNoYXNlL2FkZGl0aW9uYWwtcHVyY2hhc2UtYXR0YWNobWVudC1lZGl0LnRzIl0sInNvdXJjZXNDb250ZW50IjpbIjxzY3JpcHQgc2V0dXAgbGFuZz1cInRzXCI+XG5pbXBvcnQgeyB1bmlxdWVJdGVtTGlzdCB9IGZyb20gJ0BseXJhc29mdC90cy10b29sa2l0L3Z1ZSc7XG5pbXBvcnQgeyBkYXRhLCBzbGlkZURvd24sIHNsaWRlVXAgfSBmcm9tICdAd2luZHdhbGtlci1pby91bmljb3JuLW5leHQnO1xuaW1wb3J0IHsgd2F0Y2gsIHJlZiwgY29tcHV0ZWQgfSBmcm9tICd2dWUnO1xuaW1wb3J0IHsgUHJvZHVjdCwgUHJvZHVjdFZhcmlhbnQgfSBmcm9tICd+c2hvcGdvL3R5cGVzJztcblxuY29uc3QgcHJvcHMgPSBkZWZpbmVQcm9wczx7XG4gIHByb2R1Y3Q6IFByb2R1Y3Q7XG4gIHZhcmlhbnRzOiBQcm9kdWN0VmFyaWFudFtdO1xuICBvcGVuPzogYm9vbGVhbjtcbn0+KCk7XG5cbmNvbnN0IGl0ZW1zID0gcmVmKFxuICB1bmlxdWVJdGVtTGlzdChwcm9wcy52YXJpYW50cykubWFwKChpdGVtKSA9PiB7XG4gICAgaXRlbS5hdHRhY2htZW50ID1cbiAgICAgIGl0ZW0uYXR0YWNobWVudCB8fCB7XG4gICAgICAgIG1ldGhvZDogJ29mZnNldHMnLFxuICAgICAgICBwcmljZTogMCxcbiAgICAgICAgbWF4UXVhbnRpdHk6ICcnLFxuICAgICAgICBzdGF0ZTogMSxcbiAgICAgIH07XG5cbiAgICByZXR1cm4gaXRlbTtcbiAgfSlcbik7XG5cbmNvbnN0IHN5bmNBbGwgPSByZWYoZmFsc2UpO1xuY29uc3Qgb3BlbiA9IHJlZihwcm9wcy5vcGVuKTtcblxuZnVuY3Rpb24gdG9nZ2xlQWxsKGVsOiBIVE1MSW5wdXRFbGVtZW50KSB7XG4gIGZvciAoY29uc3QgaXRlbSBvZiBpdGVtcy52YWx1ZSkge1xuICAgIGl0ZW0uYXR0YWNobWVudC5zdGF0ZSA9IGVsLmNoZWNrZWQgPyAxIDogMDtcbiAgfVxufVxuXG5jb25zdCBjaGVja3MgPSBjb21wdXRlZCgoKSA9PiB7XG4gIHJldHVybiBpdGVtcy52YWx1ZS5maWx0ZXIoKGl0ZW0pID0+IE51bWJlcihpdGVtLmF0dGFjaG1lbnQuc3RhdGUpID09PSAxKS5sZW5ndGg7XG59KTtcblxuZnVuY3Rpb24gb25NZXRob2RDaGFuZ2UoaXRlbTogUHJvZHVjdFZhcmlhbnQpIHtcbiAgc3luY0FsbEZpZWxkcyhpdGVtLmF0dGFjaG1lbnQubWV0aG9kLCAnbWV0aG9kJyk7XG5cbiAgbm9ybWFsaXplUHJpY2luZyhpdGVtKTtcbn1cblxuZnVuY3Rpb24gb25NYXhRdWFudGl0eUNoYW5nZShpdGVtOiBQcm9kdWN0VmFyaWFudCkge1xuICBsZXQgcXR5ID0gaXRlbS5hdHRhY2htZW50Lm1heFF1YW50aXR5O1xuXG4gIHF0eSA9IE1hdGgubWF4KHF0eSwgMCk7XG4gIHF0eSA9IE1hdGgubWluKHF0eSwgMzApO1xuXG4gIGl0ZW0uYXR0YWNobWVudC5tYXhRdWFudGl0eSA9IHF0eTtcblxuICBzeW5jQWxsRmllbGRzKGl0ZW0uYXR0YWNobWVudC5tYXhRdWFudGl0eSwgJ21heFF1YW50aXR5Jyk7XG59XG5cbmZ1bmN0aW9uIG5vcm1hbGl6ZVByaWNpbmcoaXRlbTogUHJvZHVjdFZhcmlhbnQpIHtcbiAgaWYgKFxuICAgIGl0ZW0uYXR0YWNobWVudC5tZXRob2QgPT09ICdwZXJjZW50YWdlJ1xuICAgICYmIChpdGVtLmF0dGFjaG1lbnQucHJpY2UgPCAwIHx8IGl0ZW0uYXR0YWNobWVudC5wcmljZSA+IDEwMClcbiAgKSB7XG4gICAgaXRlbS5hdHRhY2htZW50LnByaWNlID0gTWF0aC5taW4oXG4gICAgICBNYXRoLmFicyhpdGVtLmF0dGFjaG1lbnQucHJpY2UpLFxuICAgICAgMTAwXG4gICAgKTtcbiAgfVxuXG4gIGlmIChcbiAgICBpdGVtLmF0dGFjaG1lbnQubWV0aG9kID09PSAnb2Zmc2V0cydcbiAgICAmJiBpdGVtLmF0dGFjaG1lbnQucHJpY2UgPiAwXG4gICkge1xuICAgIGl0ZW0uYXR0YWNobWVudC5wcmljZSA9IC1pdGVtLmF0dGFjaG1lbnQucHJpY2U7XG4gIH1cblxuICBpZiAoXG4gICAgaXRlbS5hdHRhY2htZW50Lm1ldGhvZCA9PT0gJ2ZpeGVkJ1xuICAgICYmIGl0ZW0uYXR0YWNobWVudC5wcmljZSA8IDBcbiAgKSB7XG4gICAgaXRlbS5hdHRhY2htZW50LnByaWNlID0gLWl0ZW0uYXR0YWNobWVudC5wcmljZTtcbiAgfVxuXG4gIHN5bmNBbGxGaWVsZHMoaXRlbS5hdHRhY2htZW50LnByaWNlLCAncHJpY2UnKTtcbn1cblxuZnVuY3Rpb24gc3luY0FsbEZpZWxkcyh2YWx1ZSwgZmllbGQpIHtcbiAgaWYgKCFzeW5jQWxsLnZhbHVlKSB7XG4gICAgcmV0dXJuO1xuICB9XG5cbiAgZm9yIChjb25zdCBpdGVtIG9mIGl0ZW1zLnZhbHVlKSB7XG4gICAgaXRlbS5hdHRhY2htZW50W2ZpZWxkXSA9IHZhbHVlO1xuICB9XG59XG5cbmZ1bmN0aW9uIGdldFByaWNlU3RlcChpdGVtOiBQcm9kdWN0VmFyaWFudCkge1xuICBpZiAoaXRlbS5hdHRhY2htZW50Lm1ldGhvZCA9PT0gJ3BlcmNlbnRhZ2UnKSB7XG4gICAgcmV0dXJuICcxJztcbiAgfVxuXG4gIHJldHVybiBkYXRhKCdwcmljZS5zdGVwJykgfHwgJzAuMDAwMSc7XG59XG5cbi8vIE9wZW4gLyBDbG9zZVxuY29uc3QgdmFyaWFudExpc3QgPSByZWY8SFRNTERpdkVsZW1lbnQ+KCk7XG5cbndhdGNoKG9wZW4sICh2KSA9PiB7XG4gIHNldFRpbWVvdXQoKCkgPT4ge1xuICAgIGlmICh2KSB7XG4gICAgICBzbGlkZURvd24odmFyaWFudExpc3QudmFsdWUhKTtcbiAgICB9IGVsc2Uge1xuICAgICAgc2xpZGVVcCh2YXJpYW50TGlzdC52YWx1ZSEpO1xuICAgIH1cbiAgfSwgMCk7XG59LCB7IGltbWVkaWF0ZTogdHJ1ZSB9KTtcblxud2F0Y2goKCkgPT4gcHJvcHMub3BlbiwgKHYpID0+IHtcbiAgb3Blbi52YWx1ZSA9IHY7XG59KTtcbjwvc2NyaXB0PlxuXG48dGVtcGxhdGU+XG4gIDxkaXYgY2xhc3M9XCJjYXJkIGMtYXR0YWNobWVudFwiPlxuICAgIDxkaXYgY2xhc3M9XCJjLWF0dGFjaG1lbnRfX3Byb2R1Y3QgY2FyZC1oZWFkZXIgYm9yZGVyLWJvdHRvbSBkLWZsZXggZ2FwLTNcIj5cbiAgICAgIDxkaXY+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJyYXRpbyByYXRpby0xeDFcIiBzdHlsZT1cIndpZHRoOiA1NXB4XCI+XG4gICAgICAgICAgPGltZyBjbGFzcz1cIm9iamVjdC1maXQtY292ZXJcIiA6c3JjPVwicHJvZHVjdC52YXJpYW50LmNvdmVyXCIgYWx0PVwiY292ZXJcIj5cbiAgICAgICAgPC9kaXY+XG4gICAgICA8L2Rpdj5cbiAgICAgIDxkaXYgY2xhc3M9XCJ3LTEwMFwiPlxuICAgICAgICA8ZGl2IGNsYXNzPVwiZC1mbGV4IGFsaWduLWl0ZW1zLWNlbnRlciBnYXAtMiBtYi0yXCI+XG4gICAgICAgICAgPGg0IGNsYXNzPVwibS0wXCI+e3sgcHJvZHVjdC50aXRsZSB9fTwvaDQ+XG4gICAgICAgICAgPGRpdj5cbiAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgIDxkaXYgY2xhc3M9XCJtcy1hdXRvXCI+XG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZC1mbGV4IGFsaWduLWl0ZW1zLWNlbnRlciBnYXAtMlwiPlxuICAgICAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJiYWRnZVwiPlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICN7eyBwcm9kdWN0LmlkIH19XG4gICAgICAgICAgICAgICAgICAgICAgICA8L3NwYW4+XG4gICAgICAgICAgICAgIDxzcGFuIGNsYXNzPVwiYmFkZ2UgYmctc2Vjb25kYXJ5XCI+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5hZGRpdGlvbmFsLnB1cmNoYXNlLnRleHQuc2VsZWN0ZWQuY291bnQnLCBjaGVja3MpIH19XG4gICAgICAgICAgICAgICAgICAgICAgICA8L3NwYW4+XG4gICAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1vdXRsaW5lLXNlY29uZGFyeSBidG4tc21cIlxuICAgICAgICAgICAgICAgIEBjbGljaz1cIiRlbWl0KCdyZW1vdmUnKVwiPlxuICAgICAgICAgICAgICAgIDxpIGNsYXNzPVwiZmEgZmEtdHJhc2hcIj48L2k+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5hZGRpdGlvbmFsLnB1cmNoYXNlLmJ1dHRvbi5kZWxldGUnKSB9fVxuICAgICAgICAgICAgICA8L2J1dHRvbj5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICA8L2Rpdj5cbiAgICAgICAgPGRpdiBjbGFzcz1cImQtZmxleCBnYXAtM1wiPlxuICAgICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWNoZWNrXCI+XG4gICAgICAgICAgICA8aW5wdXQgOmlkPVwiYGlucHV0LXN5bmMtYWxsLSR7cHJvZHVjdC5pZH1gXCIgdHlwZT1cImNoZWNrYm94XCIgY2xhc3M9XCJmb3JtLWNoZWNrLWlucHV0XCJcbiAgICAgICAgICAgICAgdi1tb2RlbD1cInN5bmNBbGxcIlxuICAgICAgICAgICAgLz5cbiAgICAgICAgICAgIDxsYWJlbCA6Zm9yPVwiYGlucHV0LXN5bmMtYWxsLSR7cHJvZHVjdC5pZH1gXCI+XG4gICAgICAgICAgICAgIDxpIGNsYXNzPVwiZmFcIiA6Y2xhc3M9XCJbIHN5bmNBbGwgPyAnZmEtbG9jaycgOiAnZmEtdW5sb2NrJyBdXCI+PC9pPlxuICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmFkZGl0aW9uYWwucHVyY2hhc2UudGV4dC5zeW5jLmFsbCcpIH19XG4gICAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgPGRpdiBjbGFzcz1cIm1zLWF1dG9cIj5cbiAgICAgICAgICAgIDxhIGhyZWY9XCJqYXZhc2NyaXB0Oi8vXCIgY2xhc3M9XCJweC0yIHB5LTJcIlxuICAgICAgICAgICAgICBAY2xpY2s9XCJvcGVuID0gIW9wZW5cIj5cbiAgICAgICAgICAgICAgPGkgY2xhc3M9XCJmYVwiIDpjbGFzcz1cIlsgb3BlbiA/ICdmYS1jaGV2cm9uLWRvd24nIDogJ2ZhLWNoZXZyb24tdXAnIF1cIj48L2k+XG4gICAgICAgICAgICA8L2E+XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgIDwvZGl2PlxuICAgICAgPC9kaXY+XG4gICAgPC9kaXY+XG4gICAgPGRpdiBjbGFzcz1cImMtYXR0YWNobWVudF9fdmFyaWFudHNcIiByZWY9XCJ2YXJpYW50TGlzdFwiXG4gICAgICBzdHlsZT1cIm92ZXJmbG93OiBoaWRkZW47IGRpc3BsYXk6IG5vbmU7XCI+XG4gICAgICA8dGFibGUgY2xhc3M9XCJ0YWJsZVwiPlxuICAgICAgICA8dGhlYWQ+XG4gICAgICAgIDx0cj5cbiAgICAgICAgICA8dGggc3R5bGU9XCJ3aWR0aDogMSU7XCI+XG4gICAgICAgICAgICA8aW5wdXQgdHlwZT1cImNoZWNrYm94XCJcbiAgICAgICAgICAgICAgY2xhc3M9XCJmb3JtLWNoZWNrLWlucHV0XCJcbiAgICAgICAgICAgICAgOmNoZWNrZWQ9XCJjaGVja3MgPT09IGl0ZW1zLmxlbmd0aFwiXG4gICAgICAgICAgICAgIDppbmRldGVybWluYXRlLnByb3A9XCJjaGVja3MgIT09IDAgJiYgY2hlY2tzIDwgaXRlbXMubGVuZ3RoXCJcbiAgICAgICAgICAgICAgQGNsaWNrPVwidG9nZ2xlQWxsKCRldmVudC50YXJnZXQpXCJcbiAgICAgICAgICAgIC8+XG4gICAgICAgICAgPC90aD5cbiAgICAgICAgICA8dGg+XG4gICAgICAgICAgICB7eyAkbGFuZygndW5pY29ybi5maWVsZC50aXRsZScpIH19XG4gICAgICAgICAgPC90aD5cbiAgICAgICAgICA8dGggY2xhc3M9XCJ0ZXh0LW5vd3JhcFwiIHN0eWxlPVwid2lkdGg6IDIzJTtcIj5cbiAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uYWRkaXRpb25hbC5wdXJjaGFzZS5maWVsZC5tZXRob2QnKSB9fVxuICAgICAgICAgIDwvdGg+XG4gICAgICAgICAgPHRoIGNsYXNzPVwidGV4dC1ub3dyYXBcIiBzdHlsZT1cIndpZHRoOiAxNSU7XCI+XG4gICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmFkZGl0aW9uYWwucHVyY2hhc2UuZmllbGQucHJpY2luZycpIH19XG4gICAgICAgICAgPC90aD5cbiAgICAgICAgICA8dGggY2xhc3M9XCJ0ZXh0LW5vd3JhcFwiIHN0eWxlPVwid2lkdGg6IDEwJTtcIj5cbiAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uYWRkaXRpb25hbC5wdXJjaGFzZS5maWVsZC5tYXgucXVhbnRpdHknKSB9fVxuICAgICAgICAgIDwvdGg+XG4gICAgICAgIDwvdHI+XG4gICAgICAgIDwvdGhlYWQ+XG4gICAgICAgIDx0Ym9keT5cbiAgICAgICAgPHRyIHYtZm9yPVwiaXRlbSBvZiBpdGVtc1wiIDprZXk9XCJpdGVtLmlkXCJcbiAgICAgICAgICBjbGFzcz1cIlwiPlxuICAgICAgICAgIDx0ZD5cbiAgICAgICAgICAgIDxpbnB1dCB0eXBlPVwiY2hlY2tib3hcIlxuICAgICAgICAgICAgICA6aWQ9XCJgaW5wdXQtdmFyaWFudC0ke2l0ZW0uaWR9YFwiXG4gICAgICAgICAgICAgIGNsYXNzPVwiZm9ybS1jaGVjay1pbnB1dFwiXG4gICAgICAgICAgICAgIHYtbW9kZWw9XCJpdGVtLmF0dGFjaG1lbnQuc3RhdGVcIlxuICAgICAgICAgICAgICA6dHJ1ZS12YWx1ZT1cIjFcIlxuICAgICAgICAgICAgICA6ZmFsc2UtdmFsdWU9XCIwXCJcbiAgICAgICAgICAgIC8+XG4gICAgICAgICAgPC90ZD5cbiAgICAgICAgICA8dGQ+XG4gICAgICAgICAgICA8bGFiZWwgOmZvcj1cImBpbnB1dC12YXJpYW50LSR7aXRlbS5pZH1gXCI+XG4gICAgICAgICAgICAgIHt7IGl0ZW0udGl0bGUgfX1cbiAgICAgICAgICAgIDwvbGFiZWw+XG5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJkLW5vbmVcIj5cbiAgICAgICAgICAgICAgPGlucHV0IDpuYW1lPVwiYGF0dGFjaG1lbnRzWyR7cHJvZHVjdC5pZH1dWyR7aXRlbS5pZH1dW2lkXWBcIlxuICAgICAgICAgICAgICAgIDp2YWx1ZT1cIml0ZW0uYXR0YWNobWVudD8uaWRcIlxuICAgICAgICAgICAgICAgIHR5cGU9XCJoaWRkZW5cIiAvPlxuICAgICAgICAgICAgICA8aW5wdXQgOm5hbWU9XCJgYXR0YWNobWVudHNbJHtwcm9kdWN0LmlkfV1bJHtpdGVtLmlkfV1bbWV0aG9kXWBcIlxuICAgICAgICAgICAgICAgIDp2YWx1ZT1cIml0ZW0uYXR0YWNobWVudC5tZXRob2RcIlxuICAgICAgICAgICAgICAgIHR5cGU9XCJoaWRkZW5cIiAvPlxuICAgICAgICAgICAgICA8aW5wdXQgOm5hbWU9XCJgYXR0YWNobWVudHNbJHtwcm9kdWN0LmlkfV1bJHtpdGVtLmlkfV1bcHJpY2VdYFwiXG4gICAgICAgICAgICAgICAgOnZhbHVlPVwiaXRlbS5hdHRhY2htZW50LnByaWNlXCJcbiAgICAgICAgICAgICAgICB0eXBlPVwiaGlkZGVuXCIgLz5cbiAgICAgICAgICAgICAgPGlucHV0IDpuYW1lPVwiYGF0dGFjaG1lbnRzWyR7cHJvZHVjdC5pZH1dWyR7aXRlbS5pZH1dW21heF9xdWFudGl0eV1gXCJcbiAgICAgICAgICAgICAgICA6dmFsdWU9XCJpdGVtLmF0dGFjaG1lbnQubWF4UXVhbnRpdHlcIlxuICAgICAgICAgICAgICAgIHR5cGU9XCJoaWRkZW5cIiAvPlxuICAgICAgICAgICAgICA8aW5wdXQgOm5hbWU9XCJgYXR0YWNobWVudHNbJHtwcm9kdWN0LmlkfV1bJHtpdGVtLmlkfV1bc3RhdGVdYFwiXG4gICAgICAgICAgICAgICAgOnZhbHVlPVwiaXRlbS5hdHRhY2htZW50LnN0YXRlXCJcbiAgICAgICAgICAgICAgICB0eXBlPVwiaGlkZGVuXCIgLz5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvdGQ+XG4gICAgICAgICAgPHRkPlxuICAgICAgICAgICAgPHNlbGVjdCBjbGFzcz1cImZvcm0tc2VsZWN0IGZvcm0tc2VsZWN0LXNtXCJcbiAgICAgICAgICAgICAgdi1tb2RlbD1cIml0ZW0uYXR0YWNobWVudC5tZXRob2RcIlxuICAgICAgICAgICAgICBAY2hhbmdlPVwib25NZXRob2RDaGFuZ2UoaXRlbSlcIlxuICAgICAgICAgICAgPlxuICAgICAgICAgICAgICA8b3B0aW9uIHZhbHVlPVwicGVyY2VudGFnZVwiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uZGlzY291bnQubWV0aG9kLnBlcmNlbnRhZ2UnKSB9fVxuICAgICAgICAgICAgICA8L29wdGlvbj5cbiAgICAgICAgICAgICAgPG9wdGlvbiB2YWx1ZT1cIm9mZnNldHNcIj5cbiAgICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmRpc2NvdW50Lm1ldGhvZC5vZmZzZXRzJykgfX1cbiAgICAgICAgICAgICAgPC9vcHRpb24+XG4gICAgICAgICAgICAgIDxvcHRpb24gdmFsdWU9XCJmaXhlZFwiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uZGlzY291bnQubWV0aG9kLmZpeGVkJykgfX1cbiAgICAgICAgICAgICAgPC9vcHRpb24+XG4gICAgICAgICAgICA8L3NlbGVjdD5cbiAgICAgICAgICA8L3RkPlxuICAgICAgICAgIDx0ZD5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJpbnB1dC1ncm91cCBpbnB1dC1ncm91cC1zbSBmbGV4LW5vd3JhcFwiPlxuICAgICAgICAgICAgICA8aW5wdXQgdHlwZT1cIm51bWJlclwiXG4gICAgICAgICAgICAgICAgY2xhc3M9XCJmb3JtLWNvbnRyb2wgZm9ybS1jb250cm9sLXNtXCJcbiAgICAgICAgICAgICAgICA6c3RlcD1cImdldFByaWNlU3RlcChpdGVtKVwiXG4gICAgICAgICAgICAgICAgdi1tb2RlbC5udW1iZXI9XCJpdGVtLmF0dGFjaG1lbnQucHJpY2VcIlxuICAgICAgICAgICAgICAgIEBjaGFuZ2U9XCJub3JtYWxpemVQcmljaW5nKGl0ZW0pXCJcbiAgICAgICAgICAgICAgICBzdHlsZT1cIm1pbi13aWR0aDogODBweFwiXG4gICAgICAgICAgICAgIC8+XG4gICAgICAgICAgICAgIDxzcGFuIHYtaWY9XCJpdGVtLmF0dGFjaG1lbnQubWV0aG9kID09PSAncGVyY2VudGFnZSdcIiBjbGFzcz1cImlucHV0LWdyb3VwLXRleHRcIj5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvc3Bhbj5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvdGQ+XG4gICAgICAgICAgPHRkPlxuICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJudW1iZXJcIlxuICAgICAgICAgICAgICBjbGFzcz1cImZvcm0tY29udHJvbCBmb3JtLWNvbnRyb2wtc21cIlxuICAgICAgICAgICAgICB2LW1vZGVsPVwiaXRlbS5hdHRhY2htZW50Lm1heFF1YW50aXR5XCJcbiAgICAgICAgICAgICAgQGNoYW5nZT1cIm9uTWF4UXVhbnRpdHlDaGFuZ2UoaXRlbSlcIlxuICAgICAgICAgICAgICBtaW49XCIwXCJcbiAgICAgICAgICAgICAgbWF4PVwiMzBcIlxuICAgICAgICAgICAgLz5cbiAgICAgICAgICA8L3RkPlxuICAgICAgICA8L3RyPlxuICAgICAgICA8L3Rib2R5PlxuICAgICAgPC90YWJsZT5cbiAgICA8L2Rpdj5cbiAgPC9kaXY+XG48L3RlbXBsYXRlPlxuXG48c3R5bGUgc2NvcGVkPlxuXG48L3N0eWxlPlxuIiwiPHNjcmlwdCBzZXR1cCBsYW5nPVwidHNcIj5cbmltcG9ydCB7IF9fLCByb3V0ZSwgc2ltcGxlQWxlcnQsIHVzZUh0dHBDbGllbnQsIHVzZUlmcmFtZU1vZGFsIH0gZnJvbSAnQHdpbmR3YWxrZXItaW8vdW5pY29ybi1uZXh0JztcbmltcG9ydCB7IG9uTW91bnRlZCwgcmVhY3RpdmUsIHJlZiwgdG9SZWZzIH0gZnJvbSAndnVlJztcbmltcG9ydCB7IHVuaXF1ZUl0ZW1MaXN0LCB1bmlxdWVJdGVtIH0gZnJvbSAnQGx5cmFzb2Z0L3RzLXRvb2xraXQvdnVlJztcbmltcG9ydCBBdHRhY2htZW50UHJvZHVjdCBmcm9tICd+c2hvcGdvL21vZHVsZXMvYWRkaXRpb25hbC1wdXJjaGFzZS9BdHRhY2htZW50UHJvZHVjdC52dWUnO1xuaW1wb3J0IHsgUHJvZHVjdCwgUHJvZHVjdFZhcmlhbnQgfSBmcm9tICd+c2hvcGdvL3R5cGVzJztcblxuaW50ZXJmYWNlIEF0dGFjaG1lbnREYXRhSXRlbSB7XG4gIHByb2R1Y3Q6IFByb2R1Y3Q7XG4gIHZhcmlhbnRzOiBBcnJheTxQcm9kdWN0VmFyaWFudD47XG4gIG9wZW4/OiBib29sZWFuO1xufVxuXG5jb25zdCBwcm9wcyA9IGRlZmluZVByb3BzPHsgYXR0YWNobWVudERhdGE6IEF0dGFjaG1lbnREYXRhSXRlbVtdIH0+KCk7XG5cbmNvbnN0IGF0dGFjaG1lbnRTZXQgPSByZWY8QXR0YWNobWVudERhdGFJdGVtW10+KFxuICB1bmlxdWVJdGVtTGlzdChwcm9wcy5hdHRhY2htZW50RGF0YSkubWFwKChpdGVtKSA9PiB7XG4gICAgaXRlbS5vcGVuID0gZmFsc2U7XG4gICAgcmV0dXJuIGl0ZW07XG4gIH0pXG4pO1xuXG5pZiAoYXR0YWNobWVudFNldC52YWx1ZS5sZW5ndGggPT09IDEpIHtcbiAgYXR0YWNobWVudFNldC52YWx1ZVswXS5vcGVuID0gdHJ1ZTtcbn1cblxudXNlSWZyYW1lTW9kYWwoKTtcblxub25Nb3VudGVkKCgpID0+IHtcbiAgc2V0VGltZW91dCgoKSA9PiB7XG4gICAgY29uc3QgdGFyZ2V0U2VsZWN0ZWQgPSAod2luZG93IGFzIGFueSkudGFyZ2V0U2VsZWN0ZWQ7XG5cbiAgICAod2luZG93IGFzIGFueSkudGFyZ2V0U2VsZWN0ZWQgPSBmdW5jdGlvbiAodmFsdWU6IGFueSkge1xuICAgICAgY29uc3QgaWQgPSB2YWx1ZS52YWx1ZTtcblxuICAgICAgdHJ5IHtcbiAgICAgICAgY2hlY2tBdmFpbGFibGUoaWQpXG4gICAgICB9IGNhdGNoIChlKSB7XG4gICAgICAgIHNpbXBsZUFsZXJ0KChlIGFzIEVycm9yKS5tZXNzYWdlKTtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuXG4gICAgICB0YXJnZXRTZWxlY3RlZCh2YWx1ZSk7XG4gICAgfVxuICB9LCA1MDApO1xufSk7XG5cbmNvbnN0IHByb2R1Y3RTZWxlY3RvciA9IHJlZjxhbnk+KG51bGwpO1xuXG5mdW5jdGlvbiBvcGVuUHJvZHVjdFNlbGVjdG9yKCkge1xuICBjb25zdCBjYWxsYmFja05hbWUgPSAncHJvZHVjdFNlbGVjdGVkJztcbiAgY29uc3QgdXJsID0gbmV3IFVSTChyb3V0ZSgncHJvZHVjdF9tb2RhbCcpKTtcbiAgdXJsLnNlYXJjaFBhcmFtcy5zZXQoJ2NhbGxiYWNrJywgY2FsbGJhY2tOYW1lKTtcblxuICAod2luZG93IGFzIGFueSlbY2FsbGJhY2tOYW1lXSA9IGFzeW5jIGZ1bmN0aW9uICh7IHRpdGxlLCB2YWx1ZTogaWQsIGltYWdlOiBjb3ZlciB9OiBhbnkpIHtcbiAgICB0cnkge1xuICAgICAgY2hlY2tBdmFpbGFibGUoaWQpO1xuICAgIH0gY2F0Y2ggKGUpIHtcbiAgICAgIHNpbXBsZUFsZXJ0KChlIGFzIEVycm9yKS5tZXNzYWdlLCAnJywgJ3dhcm5pbmcnKTtcbiAgICAgIHJldHVybjtcbiAgICB9XG5cbiAgICBjb25zdCB7IGdldCB9ID0gYXdhaXQgdXNlSHR0cENsaWVudCgpO1xuXG4gICAgY29uc3QgcmVzID0gYXdhaXQgZ2V0KGBAYWRkaXRpb25hbF9wdXJjaGFzZV9hamF4L2dldFByb2R1Y3RJbmZvP2lkPSR7aWR9YCk7XG5cbiAgICBmb3IgKGNvbnN0IGF0dGFjaG1lbnQgb2YgYXR0YWNobWVudFNldC52YWx1ZSkge1xuICAgICAgYXR0YWNobWVudC5vcGVuID0gZmFsc2U7XG4gICAgfVxuXG4gICAgY29uc3QgaXRlbSA9IHVuaXF1ZUl0ZW0ocmVzLmRhdGEuZGF0YSk7XG4gICAgaXRlbS5vcGVuID0gdHJ1ZTtcblxuICAgIGF0dGFjaG1lbnRTZXQudmFsdWUudW5zaGlmdChpdGVtKTtcblxuICAgIHByb2R1Y3RTZWxlY3Rvci52YWx1ZS5jbG9zZSgpO1xuICB9XG5cbiAgcHJvZHVjdFNlbGVjdG9yLnZhbHVlLm9wZW4odXJsLCB7IHNpemU6ICdtb2RhbC14bCcgfSk7XG59XG5cbmZ1bmN0aW9uIGNoZWNrQXZhaWxhYmxlKGlkOiBzdHJpbmcgfCBudW1iZXIpIHtcbiAgLy8gQ2hlY2sgaXMgaW4gYXR0YWNobWVudHNcbiAgZm9yIChjb25zdCB7IHByb2R1Y3QgfSBvZiBhdHRhY2htZW50U2V0LnZhbHVlKSB7XG4gICAgaWYgKE51bWJlcihwcm9kdWN0LmlkKSA9PT0gTnVtYmVyKGlkKSkge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKF9fKCdzaG9wZ28uYWRkaXRpb25hbC5wdXJjaGFzZS5tZXNzYWdlLmFscmVhZHkuc2VsZWN0ZWQnKSk7XG4gICAgfVxuICB9XG5cbiAgLy8gQ2hlY2sgaXMgaW4gdGFyZ2V0c1xuICBmb3IgKGNvbnN0IHRhcmdldCBvZiBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsPEhUTUxFbGVtZW50PignI2lucHV0LWl0ZW0tcHJvZHVjdHMtd3JhcCAubGlzdC1ncm91cC1pdGVtJykpIHtcbiAgICBpZiAoTnVtYmVyKHRhcmdldC5kYXRhc2V0LnZhbHVlKSA9PT0gTnVtYmVyKGlkKSkge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKF9fKCdzaG9wZ28uYWRkaXRpb25hbC5wdXJjaGFzZS5tZXNzYWdlLmFscmVhZHkuaW4udGFyZ2V0cycpKTtcbiAgICB9XG4gIH1cbn1cblxuZnVuY3Rpb24gcmVtb3ZlUHJvZHVjdChpOiBudW1iZXIpIHtcbiAgYXR0YWNobWVudFNldC52YWx1ZS5zcGxpY2UoaSwgMSk7XG59XG5cbjwvc2NyaXB0PlxuXG48dGVtcGxhdGU+XG4gIDxkaXYgY2xhc3M9XCJsLWFwLWF0dGFjaG1lbnRzXCIgZGF0YS1ub3ZhbGlkYXRlPlxuXG4gICAgPGlucHV0IG5hbWU9XCJhdHRhY2htZW50c1wiIHR5cGU9XCJoaWRkZW5cIiB2YWx1ZT1cIl9fRU1QVFlfQVJSQVlfX1wiIC8+XG5cbiAgICA8ZGl2IGNsYXNzPVwibWItM1wiPlxuICAgICAgPGJ1dHRvbiB2LWlmPVwiYXR0YWNobWVudFNldC5sZW5ndGggPiAwXCIgdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1wcmltYXJ5IGJ0bi1zbVwiXG4gICAgICAgIHN0eWxlPVwibWluLXdpZHRoOiAxMDBweFwiXG4gICAgICAgIEBjbGljaz1cIm9wZW5Qcm9kdWN0U2VsZWN0b3JcIlxuICAgICAgPlxuICAgICAgICA8aSBjbGFzcz1cImZhIGZhLXBsdXNcIj48L2k+XG4gICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uYWRkaXRpb25hbC5wdXJjaGFzZS5idXR0b24uYWRkLnByb2R1Y3QnKSB9fVxuICAgICAgPC9idXR0b24+XG4gICAgPC9kaXY+XG5cbiAgICA8dHJhbnNpdGlvbi1ncm91cCB2LWlmPVwiYXR0YWNobWVudFNldC5sZW5ndGggPiAwXCIgbmFtZT1cImZhZGVcIj5cbiAgICAgIDxBdHRhY2htZW50UHJvZHVjdFxuICAgICAgICB2LWZvcj1cIih7IHByb2R1Y3QsIHZhcmlhbnRzLCBvcGVuIH0sIGkpIG9mIGF0dGFjaG1lbnRTZXRcIiA6a2V5PVwicHJvZHVjdC5pZFwiXG4gICAgICAgIDpwcm9kdWN0PVwicHJvZHVjdFwiXG4gICAgICAgIDp2YXJpYW50cz1cInZhcmlhbnRzXCJcbiAgICAgICAgOm9wZW49XCJvcGVuXCJcbiAgICAgICAgQHJlbW92ZT1cInJlbW92ZVByb2R1Y3QoaSlcIlxuICAgICAgICBjbGFzcz1cIm1iLTRcIlxuICAgICAgICBzdHlsZT1cImFuaW1hdGlvbi1kdXJhdGlvbjogLjNzXCJcbiAgICAgID48L0F0dGFjaG1lbnRQcm9kdWN0PlxuICAgIDwvdHJhbnNpdGlvbi1ncm91cD5cblxuICAgIDxkaXYgdi1lbHNlIGNsYXNzPVwiY2FyZCBiZy1saWdodFwiPlxuICAgICAgPGRpdiBjbGFzcz1cImNhcmQtYm9keSB0ZXh0LWNlbnRlciBweS01XCI+XG4gICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1wcmltYXJ5XCJcbiAgICAgICAgICBzdHlsZT1cIm1pbi13aWR0aDogMTAwcHhcIlxuICAgICAgICAgIEBjbGljaz1cIm9wZW5Qcm9kdWN0U2VsZWN0b3JcIlxuICAgICAgICA+XG4gICAgICAgICAgPGkgY2xhc3M9XCJmYSBmYS1wbHVzXCI+PC9pPlxuICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uYWRkaXRpb25hbC5wdXJjaGFzZS5idXR0b24uYWRkLnByb2R1Y3QnKSB9fVxuICAgICAgICA8L2J1dHRvbj5cbiAgICAgIDwvZGl2PlxuICAgIDwvZGl2PlxuXG4gICAgPHVuaS1pZnJhbWUtbW9kYWwgcmVmPVwicHJvZHVjdFNlbGVjdG9yXCI+PC91bmktaWZyYW1lLW1vZGFsPlxuICA8L2Rpdj5cbjwvdGVtcGxhdGU+XG5cbjxzdHlsZSBzY29wZWQ+XG5cbjwvc3R5bGU+XG4iLCJpbXBvcnQgeyBjcmVhdGVBcHAgfSBmcm9tICd2dWUnO1xuaW1wb3J0IEFkZGl0aW9uYWxQdXJjaGFzZUF0dGFjaG1lbnRFZGl0QXBwXG4gIGZyb20gJ35zaG9wZ28vbW9kdWxlcy9hZGRpdGlvbmFsLXB1cmNoYXNlL0FkZGl0aW9uYWxQdXJjaGFzZUF0dGFjaG1lbnRFZGl0QXBwLnZ1ZSc7XG5pbXBvcnQgeyBTaG9wR29QbHVnaW4gfSBmcm9tICd+c2hvcGdvL3Nob3Bnby1wbHVnaW4nO1xuXG5leHBvcnQgZnVuY3Rpb24gaW5pdEFwcChwcm9wczogUmVjb3JkPHN0cmluZywgYW55Pikge1xuICBjb25zdCBhcHAgPSBjcmVhdGVBcHAoQWRkaXRpb25hbFB1cmNoYXNlQXR0YWNobWVudEVkaXRBcHAsIHByb3BzKTtcblxuICBhcHAudXNlKFNob3BHb1BsdWdpbik7XG5cbiAgcmV0dXJuIGFwcDtcbn1cbiJdLCJuYW1lcyI6WyJwcm9wcyIsIl9fcHJvcHMiLCJpdGVtcyIsInJlZiIsInVuaXF1ZUl0ZW1MaXN0IiwiaXRlbSIsInN5bmNBbGwiLCJvcGVuIiwidG9nZ2xlQWxsIiwiZWwiLCJjaGVja3MiLCJjb21wdXRlZCIsIm9uTWV0aG9kQ2hhbmdlIiwic3luY0FsbEZpZWxkcyIsIm5vcm1hbGl6ZVByaWNpbmciLCJvbk1heFF1YW50aXR5Q2hhbmdlIiwicXR5IiwidmFsdWUiLCJmaWVsZCIsImdldFByaWNlU3RlcCIsImRhdGEiLCJ2YXJpYW50TGlzdCIsIndhdGNoIiwidiIsInNsaWRlRG93biIsInNsaWRlVXAiLCJfaG9pc3RlZF8xIiwiX2hvaXN0ZWRfMiIsIl9ob2lzdGVkXzUiLCJfaG9pc3RlZF82IiwiX2hvaXN0ZWRfNyIsIl9ob2lzdGVkXzgiLCJfaG9pc3RlZF85IiwiX2hvaXN0ZWRfMTAiLCJfaG9pc3RlZF8xMSIsIl9ob2lzdGVkXzEyIiwiX2hvaXN0ZWRfMTMiLCJfaG9pc3RlZF8xNiIsIl9ob2lzdGVkXzE4IiwiX2hvaXN0ZWRfMjYiLCJfaG9pc3RlZF8zMyIsIl9ob2lzdGVkXzM0IiwiX2hvaXN0ZWRfMzUiLCJfaG9pc3RlZF8zNiIsIl9vcGVuQmxvY2siLCJfY3JlYXRlRWxlbWVudEJsb2NrIiwiX2NyZWF0ZUVsZW1lbnRWTm9kZSIsIl9ob2lzdGVkXzMiLCIkcHJvcHMiLCJfaG9pc3RlZF80IiwiX3RvRGlzcGxheVN0cmluZyIsIl9jYWNoZSIsIl9jcmVhdGVUZXh0Vk5vZGUiLCJfY3R4IiwiJHNldHVwIiwiX3dpdGhEaXJlY3RpdmVzIiwiJGV2ZW50IiwiX2hvaXN0ZWRfMTQiLCJfbm9ybWFsaXplQ2xhc3MiLCJfaG9pc3RlZF8xNSIsIl9ob2lzdGVkXzE3IiwiX2hvaXN0ZWRfMTkiLCJfaG9pc3RlZF8yMCIsIl9ob2lzdGVkXzIxIiwiX2hvaXN0ZWRfMjIiLCJfaG9pc3RlZF8yMyIsIl9GcmFnbWVudCIsIl9yZW5kZXJMaXN0IiwiX2hvaXN0ZWRfMjQiLCJfdk1vZGVsQ2hlY2tib3giLCJfaG9pc3RlZF8yNSIsIl9ob2lzdGVkXzI3IiwiX2hvaXN0ZWRfMjgiLCJfaG9pc3RlZF8yOSIsIl9ob2lzdGVkXzMwIiwiX2hvaXN0ZWRfMzEiLCJfaG9pc3RlZF8zMiIsIl92TW9kZWxTZWxlY3QiLCJfaG9pc3RlZF8zNyIsIl9ob2lzdGVkXzM4IiwiX2NyZWF0ZUNvbW1lbnRWTm9kZSIsIl9ob2lzdGVkXzM5IiwiX3ZNb2RlbFRleHQiLCJhdHRhY2htZW50U2V0IiwidXNlSWZyYW1lTW9kYWwiLCJvbk1vdW50ZWQiLCJ0YXJnZXRTZWxlY3RlZCIsImlkIiwiY2hlY2tBdmFpbGFibGUiLCJlIiwic2ltcGxlQWxlcnQiLCJwcm9kdWN0U2VsZWN0b3IiLCJvcGVuUHJvZHVjdFNlbGVjdG9yIiwiY2FsbGJhY2tOYW1lIiwidXJsIiwicm91dGUiLCJ0aXRsZSIsImNvdmVyIiwiZ2V0IiwidXNlSHR0cENsaWVudCIsInJlcyIsImF0dGFjaG1lbnQiLCJ1bmlxdWVJdGVtIiwicHJvZHVjdCIsIl9fIiwidGFyZ2V0IiwicmVtb3ZlUHJvZHVjdCIsImkiLCJfY3JlYXRlQmxvY2siLCJfVHJhbnNpdGlvbkdyb3VwIiwidmFyaWFudHMiLCJpbml0QXBwIiwiYXBwIiwiY3JlYXRlQXBwIiwiQWRkaXRpb25hbFB1cmNoYXNlQXR0YWNobWVudEVkaXRBcHAiLCJTaG9wR29QbHVnaW4iXSwibWFwcGlu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
