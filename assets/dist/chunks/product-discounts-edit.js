import { data as X, useFieldFlatpickr as $ } from "@windwalker-io/unicorn-next";
import { defineComponent as tt, ref as g, computed as I, watch as T, onMounted as et, createElementBlock as f, openBlock as m, createElementVNode as e, createTextVNode as i, createCommentVNode as y, toDisplayString as r, createVNode as A, withCtx as F, Fragment as ot, renderList as nt, normalizeClass as st, withDirectives as b, vModelCheckbox as it, vModelSelect as J, Transition as lt, vModelText as D, createApp as dt } from "vue";
import { uniqueItemList as ut } from "@lyrasoft/ts-toolkit/vue";
import { h as U } from "./index.es.js";
import { vTooltip as rt, ShopGoPlugin as at } from "../index.js";
import { VueDraggable as pt } from "vue-draggable-plus";
import { _ as ct } from "./_plugin-vue_export-helper.js";
const V = "Y-m-d H:i:S", ft = /* @__PURE__ */ tt({
  __name: "ProductDiscountsEditApp",
  props: {
    product: {},
    discounts: {}
  },
  setup(u, { expose: t }) {
    t();
    const k = u;
    function n(o) {
      return ut(o).map((l) => (l.checked = !1, l.unsave = !1, l));
    }
    const a = g(n(k.discounts || [])), d = g(), s = g(-1), v = g(""), p = g(null), Q = g(JSON.stringify({
      dateFormat: V,
      enableTime: !0,
      enableSeconds: !0,
      allowInput: !0,
      time_24hr: !0,
      monthSelect: !1
    })), H = g(X("input.step") || "0.0001"), P = I(() => JSON.stringify(a.value));
    let w = !1;
    const q = U.hashStr(JSON.stringify(a.value)), E = I(() => U.hashStr(P.value) !== q), O = I(() => a.value.filter((o) => o.checked));
    function _(o) {
      const l = { ...o };
      return delete l.checked, delete l.unsave, U.hashStr(JSON.stringify(l));
    }
    function x(o) {
      return !o || !flatpickr ? o : flatpickr.formatDate(flatpickr.parseDate(o), V);
    }
    function R(o) {
      const l = o.target;
      a.value.forEach((c) => {
        c.checked = l.checked;
      });
    }
    function B(o, l, c) {
      if (p.value === null) {
        p.value = c;
        return;
      }
      if (o.shiftKey) {
        let h = p.value;
        const M = o.target.checked;
        if (p.value < c)
          for (; h < c; h++)
            a.value[h].checked = M;
        else
          for (; h > c; h--)
            a.value[h].checked = M;
      }
      p.value = c;
    }
    function Z() {
      return O.value.length;
    }
    function j() {
      const o = {
        id: null,
        productId: k.product?.id,
        type: "product",
        subtype: "discount",
        minProductQuantity: 0,
        price: "",
        start_date: null,
        end_date: null,
        method: "offsets",
        state: 1
      };
      n([o]), a.value.push(o), N(o, a.value.length - 1);
    }
    T(() => d.value?.method, (o) => {
      !d.value || s.value === -1 || (["percentage", "fixed"].indexOf(o) !== -1 ? d.value.price = Math.abs(d.value.price) : d.value.price = -Math.abs(d.value.price));
    });
    async function N(o, l) {
      o.publishUp = x(o.publishUp), o.publishDown = x(o.publishDown), v.value = _(o), d.value = o, s.value = l;
    }
    T(
      () => d.value,
      () => {
        v.value !== "" && v.value !== _(d.value) && s.value >= 0 && s.value < a.value.length && (a.value[s.value].unsave = !0);
      },
      { deep: !0 }
    );
    function S() {
      v.value = "", d.value = void 0, s.value = -1;
    }
    function z(o) {
      o ? (o.uid === d.value?.uid && S(), a.value = a.value.filter((l) => l.uid !== o.uid)) : a.value = a.value.filter((l) => (l.checked && l.uid === d.value?.uid && S(), !l.checked));
    }
    function G() {
      a.value.forEach((o, l) => {
        o.ordering = l + 1;
      });
    }
    function K(o) {
      let l = "";
      return o.publishUp ? l += new Date(o.publishUp).toLocaleString(void 0, { timeZone: "UTC" }) : l += "現在", l += " 到 ", o.publishDown ? l += new Date(o.publishDown).toLocaleString(void 0, { timeZone: "UTC" }) : l += "不限期", l;
    }
    function Y() {
      d.value && (d.value.method === "fixed" ? d.value.price = Math.max(d.value.price, 0) : d.value.method === "offsets" ? d.value.price = Math.min(d.value.price, 0) : (d.value.price = Math.max(d.value.price, 0), d.value.price = Math.min(d.value.price, 100)));
    }
    function W(o) {
      d.value && (d.value.price = Number(o.target.value));
    }
    const C = g("");
    et(() => {
      const o = document.querySelector("#input-item-variant-price"), l = document.querySelector("#admin-form");
      C.value = parseFloat(o.value).toString(), o.addEventListener("change", () => {
        C.value = parseFloat(o.value).toString();
      }), l.addEventListener("submit", (c) => {
        w = !0;
      }), window.addEventListener("beforeunload", (c) => {
        if (E.value && !w)
          return c.preventDefault(), c.stopPropagation(), c.returnValue = "Save Required", "Save Required";
      });
    });
    const L = { props: k, dateFormat: V, prepareItems: n, items: a, current: d, currentIndex: s, currentHash: v, lastCheckItemIndex: p, flatpickrOptions: Q, inputStep: H, itemsJSON: P, get formSubmitting() {
      return w;
    }, set formSubmitting(o) {
      w = o;
    }, initialHash: q, saveRequired: E, checkedItems: O, hashItem: _, dateToSQLFormat: x, checkAll: R, multiCheck: B, countChecked: Z, newItem: j, editItem: N, cancelEdit: S, deleteItems: z, reorder: G, timeLimit: K, correctPriceInput: Y, onPriceInput: W, mainPrice: C, get vTooltip() {
      return rt;
    }, get VueDraggable() {
      return pt;
    } };
    return Object.defineProperty(L, "__isScriptSetup", { enumerable: !1, value: !0 }), L;
  }
}), mt = {
  class: "l-product-discount row",
  "data-novalidate": ""
}, vt = { class: "col-lg-6 l-product-discount__list" }, gt = { class: "card c-discount-list" }, bt = { class: "card-header c-discount-list__toolbar d-flex" }, ht = { class: "ms-auto" }, yt = { class: "c-discount-list__items list-group list-group-flush" }, kt = {
  class: "list-group-item c-discount-list__header d-flex gap-2",
  style: { "margin-bottom": "0" }
}, wt = { class: "" }, _t = [".indeterminate"], xt = {
  class: "flex-fill",
  style: {}
}, St = {
  class: "text-end",
  style: { width: "100px" }
}, Ct = {
  class: "text-end",
  style: { width: "100px" }
}, It = {
  class: "",
  style: { width: "75px" }
}, Dt = {
  class: "",
  style: { width: "75px" }
}, Ut = {
  class: "c-discount-list__scroll list-group list-group-flush",
  style: { "overflow-y": "scroll", height: "75vh", "min-height": "400px" }
}, Vt = ["data-id"], Pt = { class: "list-group-item__wrapper d-flex align-items-center gap-2" }, qt = { class: "c-discount-item__control d-flex flex-nowrap" }, Et = ["onUpdate:modelValue", "onClick"], Ot = { class: "c-discount-item__type flex-fill text-nowrap" }, Nt = { key: 0 }, Lt = { class: "badge bg-warning" }, Mt = {
  class: "c-discount-item__quantity text-end",
  style: { width: "100px" }
}, Tt = {
  class: "c-discount-item__price text-end flex-fill",
  style: { width: "100px" }
}, At = {
  class: "c-discount-item__time-limit text-center",
  style: { width: "75px" }
}, Ft = ["title"], Jt = { key: 1 }, Qt = {
  class: "c-discount-item__actions text-nowrap text-end",
  style: { width: "75px" }
}, Ht = ["onClick"], Rt = ["onClick"], Bt = { class: "col-lg-6 l-product-discount__manage" }, Zt = {
  key: 0,
  class: "c-discount-edit card"
}, jt = { class: "card-header d-flex" }, zt = { class: "c-discount-edit__title" }, Gt = { class: "card-body" }, Kt = { class: "d-flex gap-2" }, Yt = { class: "form-group mb-4" }, Wt = {
  for: "input-discount-subtype",
  class: "form-label"
}, Xt = { value: "discount" }, $t = { value: "special" }, te = {
  key: 0,
  class: "form-group mb-4",
  style: { "animation-duration": ".3s" }
}, ee = {
  for: "input-discount-quantity",
  class: "form-label"
}, oe = { class: "d-flex gap-2" }, ne = { class: "form-group mb-4" }, se = {
  for: "input-discount-start_date",
  class: "form-label"
}, ie = ["options"], le = {
  class: "input-group",
  "data-calendar": ""
}, de = { class: "form-group mb-4" }, ue = {
  for: "input-discount-end_date",
  class: "form-label"
}, re = ["options"], ae = {
  class: "input-group",
  "data-calendar": ""
}, pe = { class: "d-flex gap-2" }, ce = { class: "form-group mb-4" }, fe = {
  for: "input-discount-price",
  class: "form-label"
}, me = { class: "input-group" }, ve = ["value", "step"], ge = {
  key: 0,
  class: "input-group-text"
}, be = { class: "form-group mb-4" }, he = {
  for: "input-discount-method",
  class: "form-label"
}, ye = { value: "percentage" }, ke = { value: "offsets" }, we = { value: "fixed" }, _e = ["value"];
function xe(u, t, k, n, a, d) {
  return m(), f("div", mt, [
    e("div", vt, [
      e("div", gt, [
        e("div", bt, [
          e("div", ht, [
            n.countChecked() > 0 ? (m(), f("button", {
              key: 0,
              type: "button",
              class: "btn btn-sm btn-outline-danger",
              onClick: t[0] || (t[0] = (s) => n.deleteItems())
            }, [
              t[11] || (t[11] = e("span", { class: "fa fa-trash" }, null, -1)),
              i(" " + r(u.$lang("shopgo.product.button.delete")), 1)
            ])) : y("", !0),
            t[13] || (t[13] = i()),
            e("button", {
              type: "button",
              class: "btn btn-sm btn-primary",
              onClick: t[1] || (t[1] = (s) => n.newItem())
            }, [
              t[12] || (t[12] = e("span", { class: "fa fa-plus" }, null, -1)),
              i(" " + r(u.$lang("shopgo.product.discount.button.new")), 1)
            ])
          ])
        ]),
        t[32] || (t[32] = i()),
        e("div", yt, [
          e("div", kt, [
            e("div", wt, [
              t[14] || (t[14] = e("span", { class: "fa fa-arrows-alt-v fa-fw me-1" }, null, -1)),
              t[15] || (t[15] = i()),
              e("input", {
                type: "checkbox",
                onChange: t[2] || (t[2] = (s) => n.checkAll(s)),
                class: "form-check-input",
                ".indeterminate": n.countChecked() > 0 && n.countChecked() < n.items.length
              }, null, 40, _t)
            ]),
            t[16] || (t[16] = i()),
            e("div", xt, r(u.$lang("shopgo.product.discount.field.type")), 1),
            t[17] || (t[17] = i()),
            e("div", St, r(u.$lang("shopgo.discount.field.min.product.quantity")), 1),
            t[18] || (t[18] = i()),
            e("div", Ct, r(u.$lang("shopgo.product.discount.field.price.offsets")), 1),
            t[19] || (t[19] = i()),
            e("div", It, r(u.$lang("shopgo.product.discount.field.time")), 1),
            t[20] || (t[20] = i()),
            e("div", Dt, r(u.$lang("shopgo.product.discount.actions")), 1)
          ]),
          t[31] || (t[31] = i()),
          e("div", Ut, [
            A(n.VueDraggable, {
              modelValue: n.items,
              "onUpdate:modelValue": t[3] || (t[3] = (s) => n.items = s),
              onSort: n.reorder,
              animation: 300,
              handle: ".handle",
              "item-key": "uid"
            }, {
              default: F(() => [
                (m(!0), f(ot, null, nt(n.items, (s, v) => (m(), f("div", {
                  key: s.uid,
                  class: st(["list-group-item c-discount-item", { "text-bg-dark": n.current?.uid === s.uid }]),
                  "data-id": s.id
                }, [
                  e("div", Pt, [
                    e("div", qt, [
                      t[21] || (t[21] = e("span", {
                        class: "fa fa-fw fa-ellipsis-v handle",
                        style: { cursor: "move" }
                      }, null, -1)),
                      t[22] || (t[22] = i()),
                      b(e("input", {
                        type: "checkbox",
                        "onUpdate:modelValue": (p) => s.checked = p,
                        class: "form-check-input",
                        onClick: (p) => n.multiCheck(p, s, v)
                      }, null, 8, Et), [
                        [it, s.checked]
                      ])
                    ]),
                    t[26] || (t[26] = i()),
                    e("div", Ot, [
                      i(r(u.$lang("shopgo.discount.subtype." + s.subtype)) + " ", 1),
                      s.unsave ? (m(), f("div", Nt, [
                        e("span", Lt, r(u.$lang("shopgo.product.text.save.required")), 1)
                      ])) : y("", !0)
                    ]),
                    t[27] || (t[27] = i()),
                    e("div", Mt, r(s.subtype === "discount" ? s.minProductQuantity : "-"), 1),
                    t[28] || (t[28] = i()),
                    e("div", Tt, r(u.$priceOffset(s.price, s.method)), 1),
                    t[29] || (t[29] = i()),
                    e("div", At, [
                      s.publishUp || s.publishDown ? b((m(), f("span", {
                        key: 0,
                        class: "fa fa-clock has-tooltip",
                        title: n.timeLimit(s)
                      }, null, 8, Ft)), [
                        [n.vTooltip]
                      ]) : (m(), f("span", Jt, "-"))
                    ]),
                    t[30] || (t[30] = i()),
                    e("div", Qt, [
                      e("button", {
                        type: "button",
                        class: "btn btn-sm btn-light border-secondary",
                        onClick: (p) => n.editItem(s, v)
                      }, [...t[23] || (t[23] = [
                        e("span", { class: "fa fa-pencil-alt" }, null, -1)
                      ])], 8, Ht),
                      t[25] || (t[25] = i()),
                      e("button", {
                        type: "button",
                        class: "btn btn-sm btn-light border-secondary",
                        onClick: (p) => n.deleteItems(s)
                      }, [...t[24] || (t[24] = [
                        e("span", { class: "fa fa-trash text-danger" }, null, -1)
                      ])], 8, Rt)
                    ])
                  ])
                ], 10, Vt))), 128))
              ]),
              _: 1
            }, 8, ["modelValue"])
          ])
        ])
      ])
    ]),
    t[59] || (t[59] = i()),
    e("div", Bt, [
      n.current ? (m(), f("div", Zt, [
        e("div", jt, [
          e("div", zt, r(u.$lang("shopgo.product.discount.edit.title")), 1),
          t[33] || (t[33] = i()),
          t[34] || (t[34] = e("div", { class: "c-discount-edit__actions ms-auto" }, null, -1))
        ]),
        t[58] || (t[58] = i()),
        e("div", Gt, [
          e("div", Kt, [
            e("div", Yt, [
              e("label", Wt, r(u.$lang("shopgo.product.discount.field.mode")), 1),
              t[36] || (t[36] = i()),
              b(e("select", {
                id: "input-discount-subtype",
                class: "form-select",
                style: { "min-width": "100px" },
                "onUpdate:modelValue": t[4] || (t[4] = (s) => n.current.subtype = s)
              }, [
                e("option", Xt, r(u.$lang("shopgo.discount.subtype.discount")), 1),
                t[35] || (t[35] = i()),
                e("option", $t, r(u.$lang("shopgo.discount.subtype.special")), 1)
              ], 512), [
                [J, n.current.subtype]
              ])
            ]),
            t[38] || (t[38] = i()),
            A(lt, { name: "fade" }, {
              default: F(() => [
                n.current.subtype === "discount" ? (m(), f("div", te, [
                  e("label", ee, r(u.$lang("shopgo.discount.field.min.product.quantity")), 1),
                  t[37] || (t[37] = i()),
                  b(e("input", {
                    id: "input-discount-quantity",
                    type: "number",
                    class: "form-control",
                    "onUpdate:modelValue": t[5] || (t[5] = (s) => n.current.minProductQuantity = s),
                    min: "0"
                  }, null, 512), [
                    [D, n.current.minProductQuantity]
                  ])
                ])) : y("", !0)
              ]),
              _: 1
            })
          ]),
          t[56] || (t[56] = i()),
          e("div", oe, [
            e("div", ne, [
              e("label", se, r(u.$lang("shopgo.discount.field.publish.up")), 1),
              t[43] || (t[43] = i()),
              e("uni-flatpickr", { options: n.flatpickrOptions }, [
                e("div", le, [
                  b(e("input", {
                    id: "input-discount-start_date",
                    type: "text",
                    class: "form-control",
                    "onUpdate:modelValue": t[6] || (t[6] = (s) => n.current.publishUp = s),
                    "data-input": ""
                  }, null, 512), [
                    [D, n.current.publishUp]
                  ]),
                  t[40] || (t[40] = i()),
                  t[41] || (t[41] = e("button", {
                    type: "button",
                    class: "btn btn-secondary",
                    "data-toggle": ""
                  }, [
                    e("span", { class: "fa fa-calendar" })
                  ], -1)),
                  t[42] || (t[42] = i()),
                  e("button", {
                    type: "button",
                    class: "btn btn-secondary",
                    "data-clear": "",
                    onClick: t[7] || (t[7] = (s) => n.current.publishUp = "")
                  }, [...t[39] || (t[39] = [
                    e("span", { class: "fa fa-times" }, null, -1)
                  ])])
                ])
              ], 8, ie)
            ]),
            t[49] || (t[49] = i()),
            e("div", de, [
              e("label", ue, r(u.$lang("shopgo.discount.field.publish.down")), 1),
              t[48] || (t[48] = i()),
              e("uni-flatpickr", { options: n.flatpickrOptions }, [
                e("div", ae, [
                  b(e("input", {
                    id: "input-discount-end_date",
                    type: "text",
                    class: "form-control",
                    "onUpdate:modelValue": t[8] || (t[8] = (s) => n.current.publishDown = s),
                    "data-input": ""
                  }, null, 512), [
                    [D, n.current.publishDown]
                  ]),
                  t[45] || (t[45] = i()),
                  t[46] || (t[46] = e("button", {
                    type: "button",
                    class: "btn btn-secondary",
                    "data-toggle": ""
                  }, [
                    e("span", { class: "fa fa-calendar" })
                  ], -1)),
                  t[47] || (t[47] = i()),
                  e("button", {
                    type: "button",
                    class: "btn btn-secondary",
                    "data-clear": "",
                    onClick: t[9] || (t[9] = (s) => n.current.publishDown = "")
                  }, [...t[44] || (t[44] = [
                    e("span", { class: "fa fa-times" }, null, -1)
                  ])])
                ])
              ], 8, re)
            ])
          ]),
          t[57] || (t[57] = i()),
          e("div", pe, [
            e("div", ce, [
              e("label", fe, r(u.$lang("shopgo.product.discount.field.price.offsets")), 1),
              t[51] || (t[51] = i()),
              e("div", me, [
                e("input", {
                  id: "input-discount-price",
                  type: "number",
                  class: "form-control",
                  value: n.current.price,
                  onInput: n.onPriceInput,
                  onChange: n.correctPriceInput,
                  step: n.current.method === "percentage" ? 0.1 : n.inputStep
                }, null, 40, ve),
                t[50] || (t[50] = i()),
                n.current.method === "percentage" ? (m(), f("span", ge, `
                    %
                `)) : y("", !0)
              ])
            ]),
            t[55] || (t[55] = i()),
            e("div", be, [
              e("label", he, r(u.$lang("shopgo.discount.field.method")), 1),
              t[54] || (t[54] = i()),
              b(e("select", {
                id: "input-discount-method",
                class: "form-select",
                "onUpdate:modelValue": t[10] || (t[10] = (s) => n.current.method = s)
              }, [
                e("option", ye, r(u.$lang("shopgo.discount.method.percentage")), 1),
                t[52] || (t[52] = i()),
                e("option", ke, r(u.$lang("shopgo.discount.method.offsets")), 1),
                t[53] || (t[53] = i()),
                e("option", we, r(u.$lang("shopgo.discount.method.fixed")), 1)
              ], 512), [
                [J, n.current.method]
              ])
            ])
          ])
        ])
      ])) : y("", !0)
    ]),
    t[60] || (t[60] = i()),
    e("textarea", {
      name: "discounts",
      class: "d-none",
      value: n.itemsJSON
    }, null, 8, _e)
  ]);
}
const Se = /* @__PURE__ */ ct(ft, [["render", xe], ["__file", "ProductDiscountsEditApp.vue"]]);
function Ee(u) {
  const t = dt(Se, u);
  return $(), t.use(at), t;
}
export {
  Ee as initApp
};
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoicHJvZHVjdC1kaXNjb3VudHMtZWRpdC5qcyIsInNvdXJjZXMiOlsiLi4vLi4vc3JjL21vZHVsZXMvcHJvZHVjdC1lZGl0L1Byb2R1Y3REaXNjb3VudHNFZGl0QXBwLnZ1ZSIsIi4uLy4uL3NyYy9tb2R1bGVzL3Byb2R1Y3QtZWRpdC9wcm9kdWN0LWRpc2NvdW50cy1lZGl0LnRzIl0sInNvdXJjZXNDb250ZW50IjpbIjxzY3JpcHQgc2V0dXAgbGFuZz1cInRzXCI+XG5pbXBvcnQgeyB1bmlxdWVJdGVtTGlzdCB9IGZyb20gJ0BseXJhc29mdC90cy10b29sa2l0L3Z1ZSc7XG5pbXBvcnQgeyBkYXRhIH0gZnJvbSAnQHdpbmR3YWxrZXItaW8vdW5pY29ybi1uZXh0JztcbmltcG9ydCB7IE1kNSB9IGZyb20gJ3RzLW1kNSc7XG5pbXBvcnQgeyBjb21wdXRlZCwgb25Nb3VudGVkLCByZWYsIHdhdGNoIH0gZnJvbSAndnVlJztcbmltcG9ydCB7IHZUb29sdGlwIH0gZnJvbSAnfnNob3Bnby9kaXJlY3RpdmVzJztcbmltcG9ydCB7IERpc2NvdW50LCBQcm9kdWN0IH0gZnJvbSAnfnNob3Bnby90eXBlcyc7XG5pbXBvcnQgeyBWdWVEcmFnZ2FibGUgfSBmcm9tICd2dWUtZHJhZ2dhYmxlLXBsdXMnO1xuXG5jb25zdCBwcm9wcyA9IGRlZmluZVByb3BzPHtcbiAgcHJvZHVjdDogUHJvZHVjdDtcbiAgZGlzY291bnRzOiBEaXNjb3VudFtdO1xufT4oKTtcblxuY29uc3QgZGF0ZUZvcm1hdCA9ICdZLW0tZCBIOmk6Uyc7XG5cbmZ1bmN0aW9uIHByZXBhcmVJdGVtcyhpdGVtczogRGlzY291bnRbXSkge1xuICByZXR1cm4gdW5pcXVlSXRlbUxpc3QoaXRlbXMpLm1hcCgoaXRlbSkgPT4ge1xuICAgIGl0ZW0uY2hlY2tlZCA9IGZhbHNlO1xuICAgIGl0ZW0udW5zYXZlID0gZmFsc2U7XG5cbiAgICByZXR1cm4gaXRlbTtcbiAgfSk7XG59XG5cbi8vIHNwbGl0IHN0YXRlIGludG8gaW5kaXZpZHVhbCByZWZzXG5jb25zdCBpdGVtcyA9IHJlZjxEaXNjb3VudFtdPihwcmVwYXJlSXRlbXMocHJvcHMuZGlzY291bnRzIHx8IFtdKSk7XG5jb25zdCBjdXJyZW50ID0gcmVmPERpc2NvdW50PigpO1xuY29uc3QgY3VycmVudEluZGV4ID0gcmVmPG51bWJlcj4oLTEpO1xuY29uc3QgY3VycmVudEhhc2ggPSByZWY8c3RyaW5nPignJyk7XG5jb25zdCBsYXN0Q2hlY2tJdGVtSW5kZXggPSByZWY8bnVtYmVyIHwgbnVsbD4obnVsbCk7XG5jb25zdCBmbGF0cGlja3JPcHRpb25zID0gcmVmPHN0cmluZz4oSlNPTi5zdHJpbmdpZnkoe1xuICBkYXRlRm9ybWF0LFxuICBlbmFibGVUaW1lOiB0cnVlLFxuICBlbmFibGVTZWNvbmRzOiB0cnVlLFxuICBhbGxvd0lucHV0OiB0cnVlLFxuICB0aW1lXzI0aHI6IHRydWUsXG4gIG1vbnRoU2VsZWN0OiBmYWxzZSxcbn0pKTtcbmNvbnN0IGlucHV0U3RlcCA9IHJlZjxzdHJpbmc+KGRhdGEoJ2lucHV0LnN0ZXAnKSB8fCAnMC4wMDAxJyk7XG5cbmNvbnN0IGl0ZW1zSlNPTiA9IGNvbXB1dGVkKCgpID0+IEpTT04uc3RyaW5naWZ5KGl0ZW1zLnZhbHVlKSk7XG4vLyBjb25zdCBjdXJyZW50SXRlbXNIYXNoID0gY29tcHV0ZWQoKCkgPT4gTWQ1Lmhhc2hTdHIoaXRlbXNKU09OLnZhbHVlKSk7XG5cbmxldCBmb3JtU3VibWl0dGluZyA9IGZhbHNlO1xuY29uc3QgaW5pdGlhbEhhc2ggPSBNZDUuaGFzaFN0cihKU09OLnN0cmluZ2lmeShpdGVtcy52YWx1ZSkpO1xuY29uc3Qgc2F2ZVJlcXVpcmVkID0gY29tcHV0ZWQoKCkgPT4gTWQ1Lmhhc2hTdHIoaXRlbXNKU09OLnZhbHVlKSAhPT0gaW5pdGlhbEhhc2gpO1xuY29uc3QgY2hlY2tlZEl0ZW1zID0gY29tcHV0ZWQoKCkgPT4gaXRlbXMudmFsdWUuZmlsdGVyKChpdDogYW55KSA9PiBpdC5jaGVja2VkKSk7XG4vLyBjb25zdCBjdXJyZW50RWRpdFVuc2F2ZSA9IGNvbXB1dGVkKCgpID0+IGN1cnJlbnQudmFsdWUuY3VycmVudENvcHkgIT09IEpTT04uc3RyaW5naWZ5KGN1cnJlbnQudmFsdWUpKTtcblxuZnVuY3Rpb24gaGFzaEl0ZW0oaXRlbTogYW55KSB7XG4gIGNvbnN0IG5ld0l0ZW0gPSB7IC4uLml0ZW0gfTtcbiAgZGVsZXRlIG5ld0l0ZW0uY2hlY2tlZDtcbiAgZGVsZXRlIG5ld0l0ZW0udW5zYXZlO1xuICByZXR1cm4gTWQ1Lmhhc2hTdHIoSlNPTi5zdHJpbmdpZnkobmV3SXRlbSkpO1xufVxuXG5mdW5jdGlvbiBkYXRlVG9TUUxGb3JtYXQoZGF0ZVN0cjogc3RyaW5nIHwgbnVsbCkge1xuICBpZiAoIWRhdGVTdHIpIHtcbiAgICByZXR1cm4gZGF0ZVN0cjtcbiAgfVxuXG4gIGlmICghZmxhdHBpY2tyKSB7XG4gICAgcmV0dXJuIGRhdGVTdHI7XG4gIH1cblxuICByZXR1cm4gZmxhdHBpY2tyLmZvcm1hdERhdGUoZmxhdHBpY2tyLnBhcnNlRGF0ZShkYXRlU3RyKSwgZGF0ZUZvcm1hdCk7XG59XG5cbmZ1bmN0aW9uIGNoZWNrQWxsKGV2ZW50OiBFdmVudCkge1xuICBjb25zdCB0YXJnZXQgPSBldmVudC50YXJnZXQgYXMgSFRNTElucHV0RWxlbWVudDtcbiAgaXRlbXMudmFsdWUuZm9yRWFjaCgoaXRlbTogYW55KSA9PiB7XG4gICAgaXRlbS5jaGVja2VkID0gdGFyZ2V0LmNoZWNrZWQ7XG4gIH0pO1xufVxuXG5mdW5jdGlvbiBtdWx0aUNoZWNrKGV2ZW50OiBNb3VzZUV2ZW50IHwgS2V5Ym9hcmRFdmVudCwgaXRlbTogYW55LCBpOiBudW1iZXIpIHtcbiAgaWYgKGxhc3RDaGVja0l0ZW1JbmRleC52YWx1ZSA9PT0gbnVsbCkge1xuICAgIGxhc3RDaGVja0l0ZW1JbmRleC52YWx1ZSA9IGk7XG4gICAgcmV0dXJuO1xuICB9XG5cbiAgaWYgKChldmVudCBhcyBNb3VzZUV2ZW50KS5zaGlmdEtleSkge1xuICAgIGxldCBrID0gbGFzdENoZWNrSXRlbUluZGV4LnZhbHVlIGFzIG51bWJlcjtcbiAgICBjb25zdCBjaGVja2VkID0gKGV2ZW50LnRhcmdldCBhcyBIVE1MSW5wdXRFbGVtZW50KS5jaGVja2VkO1xuXG4gICAgaWYgKGxhc3RDaGVja0l0ZW1JbmRleC52YWx1ZSEgPCBpKSB7XG4gICAgICBmb3IgKDsgayA8IGk7IGsrKykge1xuICAgICAgICBpdGVtcy52YWx1ZVtrXS5jaGVja2VkID0gY2hlY2tlZDtcbiAgICAgIH1cbiAgICB9IGVsc2Uge1xuICAgICAgZm9yICg7IGsgPiBpOyBrLS0pIHtcbiAgICAgICAgaXRlbXMudmFsdWVba10uY2hlY2tlZCA9IGNoZWNrZWQ7XG4gICAgICB9XG4gICAgfVxuICB9XG5cbiAgbGFzdENoZWNrSXRlbUluZGV4LnZhbHVlID0gaTtcbn1cblxuZnVuY3Rpb24gY291bnRDaGVja2VkKCkge1xuICByZXR1cm4gY2hlY2tlZEl0ZW1zLnZhbHVlLmxlbmd0aDtcbn1cblxuZnVuY3Rpb24gbmV3SXRlbSgpIHtcbiAgY29uc3QgaXRlbTogYW55ID0ge1xuICAgIGlkOiBudWxsLFxuICAgIHByb2R1Y3RJZDogcHJvcHMucHJvZHVjdD8uaWQsXG4gICAgdHlwZTogJ3Byb2R1Y3QnLFxuICAgIHN1YnR5cGU6ICdkaXNjb3VudCcsXG4gICAgbWluUHJvZHVjdFF1YW50aXR5OiAwLFxuICAgIHByaWNlOiAnJyxcbiAgICBzdGFydF9kYXRlOiBudWxsLFxuICAgIGVuZF9kYXRlOiBudWxsLFxuICAgIG1ldGhvZDogJ29mZnNldHMnLFxuICAgIHN0YXRlOiAxLFxuICB9O1xuXG4gIHByZXBhcmVJdGVtcyhbaXRlbV0pO1xuICBpdGVtcy52YWx1ZS5wdXNoKGl0ZW0pO1xuICBlZGl0SXRlbShpdGVtLCBpdGVtcy52YWx1ZS5sZW5ndGggLSAxKTtcbn1cblxud2F0Y2goKCkgPT4gY3VycmVudC52YWx1ZT8ubWV0aG9kLCAobWV0aG9kOiBzdHJpbmcgfCB1bmRlZmluZWQpID0+IHtcbiAgaWYgKCFjdXJyZW50LnZhbHVlIHx8IGN1cnJlbnRJbmRleC52YWx1ZSA9PT0gLTEpIHtcbiAgICByZXR1cm47XG4gIH1cblxuICBpZiAoWydwZXJjZW50YWdlJywgJ2ZpeGVkJ10uaW5kZXhPZihtZXRob2QgYXMgc3RyaW5nKSAhPT0gLTEpIHtcbiAgICBjdXJyZW50LnZhbHVlLnByaWNlID0gTWF0aC5hYnMoY3VycmVudC52YWx1ZS5wcmljZSk7XG4gIH0gZWxzZSB7XG4gICAgY3VycmVudC52YWx1ZS5wcmljZSA9IC1NYXRoLmFicyhjdXJyZW50LnZhbHVlLnByaWNlKTtcbiAgfVxufSk7XG5cbmFzeW5jIGZ1bmN0aW9uIGVkaXRJdGVtKGl0ZW06IGFueSwgaTogbnVtYmVyKSB7XG4gIGl0ZW0ucHVibGlzaFVwID0gZGF0ZVRvU1FMRm9ybWF0KGl0ZW0ucHVibGlzaFVwKTtcbiAgaXRlbS5wdWJsaXNoRG93biA9IGRhdGVUb1NRTEZvcm1hdChpdGVtLnB1Ymxpc2hEb3duKTtcblxuICBjdXJyZW50SGFzaC52YWx1ZSA9IGhhc2hJdGVtKGl0ZW0pO1xuICBjdXJyZW50LnZhbHVlID0gaXRlbTtcbiAgY3VycmVudEluZGV4LnZhbHVlID0gaTtcbn1cblxud2F0Y2goXG4gICgpID0+IGN1cnJlbnQudmFsdWUsXG4gICgpID0+IHtcbiAgICBpZiAoY3VycmVudEhhc2gudmFsdWUgIT09ICcnICYmIGN1cnJlbnRIYXNoLnZhbHVlICE9PSBoYXNoSXRlbShjdXJyZW50LnZhbHVlKSkge1xuICAgICAgaWYgKGN1cnJlbnRJbmRleC52YWx1ZSA+PSAwICYmIGN1cnJlbnRJbmRleC52YWx1ZSA8IGl0ZW1zLnZhbHVlLmxlbmd0aCkge1xuICAgICAgICBpdGVtcy52YWx1ZVtjdXJyZW50SW5kZXgudmFsdWVdLnVuc2F2ZSA9IHRydWU7XG4gICAgICB9XG4gICAgfVxuICB9LFxuICB7IGRlZXA6IHRydWUgfVxuKTtcblxuZnVuY3Rpb24gY2FuY2VsRWRpdCgpIHtcbiAgY3VycmVudEhhc2gudmFsdWUgPSAnJztcbiAgY3VycmVudC52YWx1ZSA9IHVuZGVmaW5lZDtcbiAgY3VycmVudEluZGV4LnZhbHVlID0gLTE7XG59XG5cbmZ1bmN0aW9uIGRlbGV0ZUl0ZW1zKGl0ZW0/OiBEaXNjb3VudCkge1xuICBpZiAoIWl0ZW0pIHtcbiAgICBpdGVtcy52YWx1ZSA9IGl0ZW1zLnZhbHVlLmZpbHRlcigoaXQ6IGFueSkgPT4ge1xuICAgICAgaWYgKGl0LmNoZWNrZWQgJiYgaXQudWlkID09PSBjdXJyZW50LnZhbHVlPy51aWQpIHtcbiAgICAgICAgY2FuY2VsRWRpdCgpO1xuICAgICAgfVxuICAgICAgcmV0dXJuICFpdC5jaGVja2VkO1xuICAgIH0pO1xuICB9IGVsc2Uge1xuICAgIGlmIChpdGVtLnVpZCA9PT0gY3VycmVudC52YWx1ZT8udWlkKSB7XG4gICAgICBjYW5jZWxFZGl0KCk7XG4gICAgfVxuICAgIGl0ZW1zLnZhbHVlID0gaXRlbXMudmFsdWUuZmlsdGVyKChpdDogYW55KSA9PiBpdC51aWQgIT09IGl0ZW0udWlkKTtcbiAgfVxufVxuXG5mdW5jdGlvbiByZW9yZGVyKCkge1xuICBpdGVtcy52YWx1ZS5mb3JFYWNoKChpdGVtOiBhbnksIGk6IG51bWJlcikgPT4ge1xuICAgIGl0ZW0ub3JkZXJpbmcgPSBpICsgMTtcbiAgfSk7XG59XG5cbmZ1bmN0aW9uIHRpbWVMaW1pdChpdGVtOiBhbnkpIHtcbiAgbGV0IHRleHQgPSAnJztcbiAgaWYgKGl0ZW0ucHVibGlzaFVwKSB7XG4gICAgdGV4dCArPSBuZXcgRGF0ZShpdGVtLnB1Ymxpc2hVcCkudG9Mb2NhbGVTdHJpbmcodW5kZWZpbmVkLCB7IHRpbWVab25lOiAnVVRDJyB9KTtcbiAgfSBlbHNlIHtcbiAgICB0ZXh0ICs9ICfnj77lnKgnO1xuICB9XG4gIHRleHQgKz0gJyDliLAgJztcbiAgaWYgKGl0ZW0ucHVibGlzaERvd24pIHtcbiAgICB0ZXh0ICs9IG5ldyBEYXRlKGl0ZW0ucHVibGlzaERvd24pLnRvTG9jYWxlU3RyaW5nKHVuZGVmaW5lZCwgeyB0aW1lWm9uZTogJ1VUQycgfSk7XG4gIH0gZWxzZSB7XG4gICAgdGV4dCArPSAn5LiN6ZmQ5pyfJztcbiAgfVxuICByZXR1cm4gdGV4dDtcbn1cblxuZnVuY3Rpb24gY29ycmVjdFByaWNlSW5wdXQoKSB7XG4gIGlmICghY3VycmVudC52YWx1ZSkge1xuICAgIHJldHVybjtcbiAgfVxuXG4gIGlmIChjdXJyZW50LnZhbHVlLm1ldGhvZCA9PT0gJ2ZpeGVkJykge1xuICAgIGN1cnJlbnQudmFsdWUucHJpY2UgPSBNYXRoLm1heChjdXJyZW50LnZhbHVlLnByaWNlLCAwKTtcbiAgfSBlbHNlIGlmIChjdXJyZW50LnZhbHVlLm1ldGhvZCA9PT0gJ29mZnNldHMnKSB7XG4gICAgY3VycmVudC52YWx1ZS5wcmljZSA9IE1hdGgubWluKGN1cnJlbnQudmFsdWUucHJpY2UsIDApO1xuICB9IGVsc2Uge1xuICAgIGN1cnJlbnQudmFsdWUucHJpY2UgPSBNYXRoLm1heChjdXJyZW50LnZhbHVlLnByaWNlLCAwKTtcbiAgICBjdXJyZW50LnZhbHVlLnByaWNlID0gTWF0aC5taW4oY3VycmVudC52YWx1ZS5wcmljZSwgMTAwKTtcbiAgfVxufVxuXG5mdW5jdGlvbiBvblByaWNlSW5wdXQoZTogRXZlbnQpIHtcbiAgaWYgKCFjdXJyZW50LnZhbHVlKSB7XG4gICAgcmV0dXJuO1xuICB9XG5cbiAgY3VycmVudC52YWx1ZS5wcmljZSA9IE51bWJlcigoZS50YXJnZXQgYXMgSFRNTElucHV0RWxlbWVudCkudmFsdWUpO1xufVxuXG5jb25zdCBtYWluUHJpY2UgPSByZWYoJycpO1xuXG5vbk1vdW50ZWQoKCkgPT4ge1xuICBjb25zdCBwcmljZUlucHV0ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcjxIVE1MSW5wdXRFbGVtZW50PignI2lucHV0LWl0ZW0tdmFyaWFudC1wcmljZScpITtcbiAgY29uc3QgZm9ybUVsZW1lbnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yPEhUTUxGb3JtRWxlbWVudD4oJyNhZG1pbi1mb3JtJykhO1xuXG4gIG1haW5QcmljZS52YWx1ZSA9IHBhcnNlRmxvYXQocHJpY2VJbnB1dC52YWx1ZSkudG9TdHJpbmcoKTtcblxuICBwcmljZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsICgpID0+IHtcbiAgICBtYWluUHJpY2UudmFsdWUgPSBwYXJzZUZsb2F0KHByaWNlSW5wdXQudmFsdWUpLnRvU3RyaW5nKCk7XG4gIH0pO1xuXG4gIGZvcm1FbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3N1Ym1pdCcsIChlKSA9PiB7XG4gICAgZm9ybVN1Ym1pdHRpbmcgPSB0cnVlO1xuICB9KTtcblxuICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignYmVmb3JldW5sb2FkJywgKGUpID0+IHtcbiAgICBpZiAoc2F2ZVJlcXVpcmVkLnZhbHVlICYmICFmb3JtU3VibWl0dGluZykge1xuICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgZS5zdG9wUHJvcGFnYXRpb24oKTtcbiAgICAgIGUucmV0dXJuVmFsdWUgPSAnU2F2ZSBSZXF1aXJlZCc7XG5cbiAgICAgIHJldHVybiAnU2F2ZSBSZXF1aXJlZCc7XG4gICAgfVxuICB9KTtcbn0pO1xuPC9zY3JpcHQ+XG5cbjx0ZW1wbGF0ZT5cbiAgPGRpdiBjbGFzcz1cImwtcHJvZHVjdC1kaXNjb3VudCByb3dcIiBkYXRhLW5vdmFsaWRhdGU+XG4gICAgPGRpdiBjbGFzcz1cImNvbC1sZy02IGwtcHJvZHVjdC1kaXNjb3VudF9fbGlzdFwiPlxuICAgICAgPGRpdiBjbGFzcz1cImNhcmQgYy1kaXNjb3VudC1saXN0XCI+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkLWhlYWRlciBjLWRpc2NvdW50LWxpc3RfX3Rvb2xiYXIgZC1mbGV4XCI+XG4gICAgICAgICAgPGRpdiBjbGFzcz1cIm1zLWF1dG9cIj5cbiAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1zbSBidG4tb3V0bGluZS1kYW5nZXJcIlxuICAgICAgICAgICAgICB2LWlmPVwiY291bnRDaGVja2VkKCkgPiAwXCJcbiAgICAgICAgICAgICAgQGNsaWNrPVwiZGVsZXRlSXRlbXMoKVwiPlxuICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLXRyYXNoXCI+PC9zcGFuPlxuICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLnByb2R1Y3QuYnV0dG9uLmRlbGV0ZScpIH19XG4gICAgICAgICAgICA8L2J1dHRvbj5cblxuICAgICAgICAgICAgPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJidG4gYnRuLXNtIGJ0bi1wcmltYXJ5XCJcbiAgICAgICAgICAgICAgQGNsaWNrPVwibmV3SXRlbSgpXCI+XG4gICAgICAgICAgICAgIDxzcGFuIGNsYXNzPVwiZmEgZmEtcGx1c1wiPjwvc3Bhbj5cbiAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LmRpc2NvdW50LmJ1dHRvbi5uZXcnKSB9fVxuICAgICAgICAgICAgPC9idXR0b24+XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgIDwvZGl2PlxuXG4gICAgICAgIDxkaXYgY2xhc3M9XCJjLWRpc2NvdW50LWxpc3RfX2l0ZW1zIGxpc3QtZ3JvdXAgbGlzdC1ncm91cC1mbHVzaFwiPlxuICAgICAgICAgIDxkaXYgY2xhc3M9XCJsaXN0LWdyb3VwLWl0ZW0gYy1kaXNjb3VudC1saXN0X19oZWFkZXIgZC1mbGV4IGdhcC0yXCIgc3R5bGU9XCJtYXJnaW4tYm90dG9tOiAwO1wiPlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cIlwiPlxuICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLWFycm93cy1hbHQtdiBmYS1mdyBtZS0xXCI+PC9zcGFuPlxuICAgICAgICAgICAgICA8aW5wdXQgdHlwZT1cImNoZWNrYm94XCIgQGNoYW5nZT1cImNoZWNrQWxsKCRldmVudClcIlxuICAgICAgICAgICAgICAgIGNsYXNzPVwiZm9ybS1jaGVjay1pbnB1dFwiXG4gICAgICAgICAgICAgICAgOmluZGV0ZXJtaW5hdGUucHJvcD1cImNvdW50Q2hlY2tlZCgpID4gMCAmJiBjb3VudENoZWNrZWQoKSA8IGl0ZW1zLmxlbmd0aFwiIC8+XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmbGV4LWZpbGxcIiBzdHlsZT1cIlwiPlxuICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLnByb2R1Y3QuZGlzY291bnQuZmllbGQudHlwZScpIH19XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ0ZXh0LWVuZFwiIHN0eWxlPVwid2lkdGg6IDEwMHB4O1wiPlxuICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmRpc2NvdW50LmZpZWxkLm1pbi5wcm9kdWN0LnF1YW50aXR5JykgfX1cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cInRleHQtZW5kXCIgc3R5bGU9XCJ3aWR0aDogMTAwcHg7XCI+XG4gICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5kaXNjb3VudC5maWVsZC5wcmljZS5vZmZzZXRzJykgfX1cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cIlwiIHN0eWxlPVwid2lkdGg6IDc1cHg7XCI+XG4gICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5kaXNjb3VudC5maWVsZC50aW1lJykgfX1cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cIlwiIHN0eWxlPVwid2lkdGg6IDc1cHg7XCI+XG4gICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5kaXNjb3VudC5hY3Rpb25zJykgfX1cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgPCEtLSBMaXN0IC0tPlxuICAgICAgICAgIDxkaXYgY2xhc3M9XCJjLWRpc2NvdW50LWxpc3RfX3Njcm9sbCBsaXN0LWdyb3VwIGxpc3QtZ3JvdXAtZmx1c2hcIlxuICAgICAgICAgICAgc3R5bGU9XCJvdmVyZmxvdy15OiBzY3JvbGw7IGhlaWdodDogNzV2aDsgbWluLWhlaWdodDogNDAwcHhcIj5cbiAgICAgICAgICAgIDxWdWVEcmFnZ2FibGUgdi1tb2RlbD1cIml0ZW1zXCIgQHNvcnQ9XCJyZW9yZGVyXCJcbiAgICAgICAgICAgICAgOmFuaW1hdGlvbj1cIjMwMFwiXG4gICAgICAgICAgICAgIGhhbmRsZT1cIi5oYW5kbGVcIlxuICAgICAgICAgICAgICBpdGVtLWtleT1cInVpZFwiXG4gICAgICAgICAgICA+XG4gICAgICAgICAgICAgIDwhLS0gRGlzY291bnQgSXRlbS0tPlxuICAgICAgICAgICAgICA8dGVtcGxhdGUgdi1mb3I9XCIoaXRlbSwgaSkgaW4gaXRlbXNcIiA6a2V5PVwiaXRlbS51aWRcIj5cbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwibGlzdC1ncm91cC1pdGVtIGMtZGlzY291bnQtaXRlbVwiXG4gICAgICAgICAgICAgICAgICA6Y2xhc3M9XCJ7ICd0ZXh0LWJnLWRhcmsnOiBjdXJyZW50Py51aWQgPT09IGl0ZW0udWlkIH1cIlxuICAgICAgICAgICAgICAgICAgOmRhdGEtaWQ9XCJpdGVtLmlkXCJcbiAgICAgICAgICAgICAgICA+XG4gICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwibGlzdC1ncm91cC1pdGVtX193cmFwcGVyIGQtZmxleCBhbGlnbi1pdGVtcy1jZW50ZXIgZ2FwLTJcIj5cbiAgICAgICAgICAgICAgICAgICAgPCEtLSBDaGVja2JveCAtLT5cbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImMtZGlzY291bnQtaXRlbV9fY29udHJvbCBkLWZsZXggZmxleC1ub3dyYXBcIj5cbiAgICAgICAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLWZ3IGZhLWVsbGlwc2lzLXYgaGFuZGxlXCIgc3R5bGU9XCJjdXJzb3I6IG1vdmU7XCI+PC9zcGFuPlxuICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCB0eXBlPVwiY2hlY2tib3hcIiB2LW1vZGVsPVwiaXRlbS5jaGVja2VkXCJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNsYXNzPVwiZm9ybS1jaGVjay1pbnB1dFwiXG4gICAgICAgICAgICAgICAgICAgICAgICBAY2xpY2s9XCJtdWx0aUNoZWNrKCRldmVudCwgaXRlbSwgaSlcIiAvPlxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgICAgICAgICAgICA8IS0tIFR5cGUgLS0+XG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjLWRpc2NvdW50LWl0ZW1fX3R5cGUgZmxleC1maWxsIHRleHQtbm93cmFwXCI+XG4gICAgICAgICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5kaXNjb3VudC5zdWJ0eXBlLicgKyBpdGVtLnN1YnR5cGUpIH19XG4gICAgICAgICAgICAgICAgICAgICAgPGRpdiB2LWlmPVwiaXRlbS51bnNhdmVcIj5cbiAgICAgICAgICAgICAgICAgICAgICAgIDxzcGFuIGNsYXNzPVwiYmFkZ2UgYmctd2FybmluZ1wiPlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC50ZXh0LnNhdmUucmVxdWlyZWQnKSB9fVxuICAgICAgICAgICAgICAgICAgICAgICAgPC9zcGFuPlxuICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgICAgICAgICAgICA8IS0tIFN0YXJ0IFF0eSAtLT5cbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImMtZGlzY291bnQtaXRlbV9fcXVhbnRpdHkgdGV4dC1lbmRcIlxuICAgICAgICAgICAgICAgICAgICAgIHN0eWxlPVwid2lkdGg6IDEwMHB4O1wiPlxuICAgICAgICAgICAgICAgICAgICAgIHt7IGl0ZW0uc3VidHlwZSA9PT0gJ2Rpc2NvdW50JyA/IGl0ZW0ubWluUHJvZHVjdFF1YW50aXR5IDogJy0nIH19XG4gICAgICAgICAgICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgICAgICAgICAgIDwhLS0gUHJpY2luZyAtLT5cbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImMtZGlzY291bnQtaXRlbV9fcHJpY2UgdGV4dC1lbmQgZmxleC1maWxsXCJcbiAgICAgICAgICAgICAgICAgICAgICBzdHlsZT1cIndpZHRoOiAxMDBweFwiPlxuICAgICAgICAgICAgICAgICAgICAgIHt7ICRwcmljZU9mZnNldChpdGVtLnByaWNlLCBpdGVtLm1ldGhvZCkgfX1cbiAgICAgICAgICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgICAgICAgICAgPCEtLSBUaW1lIC0tPlxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiYy1kaXNjb3VudC1pdGVtX190aW1lLWxpbWl0IHRleHQtY2VudGVyXCJcbiAgICAgICAgICAgICAgICAgICAgICBzdHlsZT1cIndpZHRoOiA3NXB4O1wiPlxuICAgICAgICAgICAgICAgICAgICAgIDxzcGFuIHYtaWY9XCJpdGVtLnB1Ymxpc2hVcCB8fCBpdGVtLnB1Ymxpc2hEb3duXCJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNsYXNzPVwiZmEgZmEtY2xvY2sgaGFzLXRvb2x0aXBcIlxuICAgICAgICAgICAgICAgICAgICAgICAgdi10b29sdGlwXG4gICAgICAgICAgICAgICAgICAgICAgICA6dGl0bGU9XCJ0aW1lTGltaXQoaXRlbSlcIlxuICAgICAgICAgICAgICAgICAgICAgID48L3NwYW4+XG4gICAgICAgICAgICAgICAgICAgICAgPHNwYW4gdi1lbHNlPi08L3NwYW4+XG4gICAgICAgICAgICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgICAgICAgICAgIDwhLS0gQWN0aW9ucyAtLT5cbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImMtZGlzY291bnQtaXRlbV9fYWN0aW9ucyB0ZXh0LW5vd3JhcCB0ZXh0LWVuZFwiXG4gICAgICAgICAgICAgICAgICAgICAgc3R5bGU9XCJ3aWR0aDogNzVweFwiPlxuICAgICAgICAgICAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1zbSBidG4tbGlnaHQgYm9yZGVyLXNlY29uZGFyeVwiXG4gICAgICAgICAgICAgICAgICAgICAgICBAY2xpY2s9XCJlZGl0SXRlbShpdGVtLCBpKVwiPlxuICAgICAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJmYSBmYS1wZW5jaWwtYWx0XCI+PC9zcGFuPlxuICAgICAgICAgICAgICAgICAgICAgIDwvYnV0dG9uPlxuICAgICAgICAgICAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1zbSBidG4tbGlnaHQgYm9yZGVyLXNlY29uZGFyeVwiXG4gICAgICAgICAgICAgICAgICAgICAgICBAY2xpY2s9XCJkZWxldGVJdGVtcyhpdGVtKVwiPlxuICAgICAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJmYSBmYS10cmFzaCB0ZXh0LWRhbmdlclwiPjwvc3Bhbj5cbiAgICAgICAgICAgICAgICAgICAgICA8L2J1dHRvbj5cbiAgICAgICAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgPC90ZW1wbGF0ZT5cbiAgICAgICAgICAgIDwvVnVlRHJhZ2dhYmxlPlxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICA8L2Rpdj5cbiAgICAgIDwvZGl2PlxuICAgIDwvZGl2PlxuXG4gICAgPCEtLSBFZGl0IC0tPlxuICAgIDxkaXYgY2xhc3M9XCJjb2wtbGctNiBsLXByb2R1Y3QtZGlzY291bnRfX21hbmFnZVwiPlxuICAgICAgPGRpdiB2LWlmPVwiY3VycmVudFwiIGNsYXNzPVwiYy1kaXNjb3VudC1lZGl0IGNhcmRcIj5cbiAgICAgICAgPGRpdiBjbGFzcz1cImNhcmQtaGVhZGVyIGQtZmxleFwiPlxuICAgICAgICAgIDxkaXYgY2xhc3M9XCJjLWRpc2NvdW50LWVkaXRfX3RpdGxlXCI+XG4gICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLnByb2R1Y3QuZGlzY291bnQuZWRpdC50aXRsZScpIH19XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgPGRpdiBjbGFzcz1cImMtZGlzY291bnQtZWRpdF9fYWN0aW9ucyBtcy1hdXRvXCI+XG5cbiAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgPC9kaXY+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkLWJvZHlcIj5cbiAgICAgICAgICA8ZGl2IGNsYXNzPVwiZC1mbGV4IGdhcC0yXCI+XG4gICAgICAgICAgICA8IS0tIE1vZGUgLS0+XG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBtYi00XCI+XG4gICAgICAgICAgICAgIDxsYWJlbCBmb3I9XCJpbnB1dC1kaXNjb3VudC1zdWJ0eXBlXCIgY2xhc3M9XCJmb3JtLWxhYmVsXCI+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LmRpc2NvdW50LmZpZWxkLm1vZGUnKSB9fVxuICAgICAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgICAgICA8c2VsZWN0IGlkPVwiaW5wdXQtZGlzY291bnQtc3VidHlwZVwiIGNsYXNzPVwiZm9ybS1zZWxlY3RcIlxuICAgICAgICAgICAgICAgIHN0eWxlPVwibWluLXdpZHRoOiAxMDBweDtcIlxuICAgICAgICAgICAgICAgIHYtbW9kZWw9XCJjdXJyZW50LnN1YnR5cGVcIj5cbiAgICAgICAgICAgICAgICA8b3B0aW9uIHZhbHVlPVwiZGlzY291bnRcIj5cbiAgICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uZGlzY291bnQuc3VidHlwZS5kaXNjb3VudCcpIH19XG4gICAgICAgICAgICAgICAgPC9vcHRpb24+XG4gICAgICAgICAgICAgICAgPG9wdGlvbiB2YWx1ZT1cInNwZWNpYWxcIj5cbiAgICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uZGlzY291bnQuc3VidHlwZS5zcGVjaWFsJykgfX1cbiAgICAgICAgICAgICAgICA8L29wdGlvbj5cbiAgICAgICAgICAgICAgPC9zZWxlY3Q+XG4gICAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgICAgPCEtLSBTdGFydCBRdHkgLS0+XG4gICAgICAgICAgICA8dHJhbnNpdGlvbiBuYW1lPVwiZmFkZVwiPlxuICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBtYi00XCIgdi1pZj1cImN1cnJlbnQuc3VidHlwZSA9PT0gJ2Rpc2NvdW50J1wiXG4gICAgICAgICAgICAgICAgc3R5bGU9XCJhbmltYXRpb24tZHVyYXRpb246IC4zc1wiPlxuICAgICAgICAgICAgICAgIDxsYWJlbCBmb3I9XCJpbnB1dC1kaXNjb3VudC1xdWFudGl0eVwiIGNsYXNzPVwiZm9ybS1sYWJlbFwiPlxuICAgICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5kaXNjb3VudC5maWVsZC5taW4ucHJvZHVjdC5xdWFudGl0eScpIH19XG4gICAgICAgICAgICAgICAgPC9sYWJlbD5cbiAgICAgICAgICAgICAgICA8aW5wdXQgaWQ9XCJpbnB1dC1kaXNjb3VudC1xdWFudGl0eVwiIHR5cGU9XCJudW1iZXJcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiXG4gICAgICAgICAgICAgICAgICB2LW1vZGVsPVwiY3VycmVudC5taW5Qcm9kdWN0UXVhbnRpdHlcIiBtaW49XCIwXCIgLz5cbiAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICA8L3RyYW5zaXRpb24+XG4gICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICA8ZGl2IGNsYXNzPVwiZC1mbGV4IGdhcC0yXCI+XG4gICAgICAgICAgICA8IS0tIFB1Ymxpc2ggVXAgLS0+XG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBtYi00XCI+XG4gICAgICAgICAgICAgIDxsYWJlbCBmb3I9XCJpbnB1dC1kaXNjb3VudC1zdGFydF9kYXRlXCIgY2xhc3M9XCJmb3JtLWxhYmVsXCI+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5kaXNjb3VudC5maWVsZC5wdWJsaXNoLnVwJykgfX1cbiAgICAgICAgICAgICAgPC9sYWJlbD5cbiAgICAgICAgICAgICAgPHVuaS1mbGF0cGlja3IgOm9wdGlvbnM9XCJmbGF0cGlja3JPcHRpb25zXCI+XG4gICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImlucHV0LWdyb3VwXCIgZGF0YS1jYWxlbmRhcj5cbiAgICAgICAgICAgICAgICAgIDxpbnB1dCBpZD1cImlucHV0LWRpc2NvdW50LXN0YXJ0X2RhdGVcIiB0eXBlPVwidGV4dFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCJcbiAgICAgICAgICAgICAgICAgICAgdi1tb2RlbD1cImN1cnJlbnQucHVibGlzaFVwXCJcbiAgICAgICAgICAgICAgICAgICAgZGF0YS1pbnB1dFxuICAgICAgICAgICAgICAgICAgLz5cblxuICAgICAgICAgICAgICAgICAgPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCJcbiAgICAgICAgICAgICAgICAgICAgY2xhc3M9XCJidG4gYnRuLXNlY29uZGFyeVwiXG4gICAgICAgICAgICAgICAgICAgIGRhdGEtdG9nZ2xlXG4gICAgICAgICAgICAgICAgICA+XG4gICAgICAgICAgICAgICAgICAgIDxzcGFuIGNsYXNzPVwiZmEgZmEtY2FsZW5kYXJcIj48L3NwYW4+XG4gICAgICAgICAgICAgICAgICA8L2J1dHRvbj5cbiAgICAgICAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiXG4gICAgICAgICAgICAgICAgICAgIGNsYXNzPVwiYnRuIGJ0bi1zZWNvbmRhcnlcIlxuICAgICAgICAgICAgICAgICAgICBkYXRhLWNsZWFyXG4gICAgICAgICAgICAgICAgICAgIEBjbGljaz1cImN1cnJlbnQucHVibGlzaFVwID0gJydcIlxuICAgICAgICAgICAgICAgICAgPlxuICAgICAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLXRpbWVzXCI+PC9zcGFuPlxuICAgICAgICAgICAgICAgICAgPC9idXR0b24+XG4gICAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICAgIDwvdW5pLWZsYXRwaWNrcj5cbiAgICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgICA8IS0tIFB1Ymxpc2ggRG93biAtLT5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIG1iLTRcIj5cbiAgICAgICAgICAgICAgPGxhYmVsIGZvcj1cImlucHV0LWRpc2NvdW50LWVuZF9kYXRlXCIgY2xhc3M9XCJmb3JtLWxhYmVsXCI+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5kaXNjb3VudC5maWVsZC5wdWJsaXNoLmRvd24nKSB9fVxuICAgICAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgICAgICA8dW5pLWZsYXRwaWNrciA6b3B0aW9ucz1cImZsYXRwaWNrck9wdGlvbnNcIj5cbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiaW5wdXQtZ3JvdXBcIiBkYXRhLWNhbGVuZGFyPlxuICAgICAgICAgICAgICAgICAgPGlucHV0IGlkPVwiaW5wdXQtZGlzY291bnQtZW5kX2RhdGVcIiB0eXBlPVwidGV4dFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCJcbiAgICAgICAgICAgICAgICAgICAgdi1tb2RlbD1cImN1cnJlbnQucHVibGlzaERvd25cIlxuICAgICAgICAgICAgICAgICAgICBkYXRhLWlucHV0XG4gICAgICAgICAgICAgICAgICAvPlxuXG4gICAgICAgICAgICAgICAgICA8YnV0dG9uIHR5cGU9XCJidXR0b25cIlxuICAgICAgICAgICAgICAgICAgICBjbGFzcz1cImJ0biBidG4tc2Vjb25kYXJ5XCJcbiAgICAgICAgICAgICAgICAgICAgZGF0YS10b2dnbGVcbiAgICAgICAgICAgICAgICAgID5cbiAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJmYSBmYS1jYWxlbmRhclwiPjwvc3Bhbj5cbiAgICAgICAgICAgICAgICAgIDwvYnV0dG9uPlxuICAgICAgICAgICAgICAgICAgPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCJcbiAgICAgICAgICAgICAgICAgICAgY2xhc3M9XCJidG4gYnRuLXNlY29uZGFyeVwiXG4gICAgICAgICAgICAgICAgICAgIGRhdGEtY2xlYXJcbiAgICAgICAgICAgICAgICAgICAgQGNsaWNrPVwiY3VycmVudC5wdWJsaXNoRG93biA9ICcnXCJcbiAgICAgICAgICAgICAgICAgID5cbiAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJmYSBmYS10aW1lc1wiPjwvc3Bhbj5cbiAgICAgICAgICAgICAgICAgIDwvYnV0dG9uPlxuICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICA8L3VuaS1mbGF0cGlja3I+XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgIDxkaXYgY2xhc3M9XCJkLWZsZXggZ2FwLTJcIj5cbiAgICAgICAgICAgIDwhLS0gUHJpY2luZyAtLT5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIG1iLTRcIj5cbiAgICAgICAgICAgICAgPGxhYmVsIGZvcj1cImlucHV0LWRpc2NvdW50LXByaWNlXCIgY2xhc3M9XCJmb3JtLWxhYmVsXCI+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LmRpc2NvdW50LmZpZWxkLnByaWNlLm9mZnNldHMnKSB9fVxuICAgICAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiaW5wdXQtZ3JvdXBcIj5cbiAgICAgICAgICAgICAgICA8aW5wdXQgaWQ9XCJpbnB1dC1kaXNjb3VudC1wcmljZVwiIHR5cGU9XCJudW1iZXJcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiXG4gICAgICAgICAgICAgICAgICA6dmFsdWU9XCJjdXJyZW50LnByaWNlXCJcbiAgICAgICAgICAgICAgICAgIEBpbnB1dD1cIm9uUHJpY2VJbnB1dFwiXG4gICAgICAgICAgICAgICAgICBAY2hhbmdlPVwiY29ycmVjdFByaWNlSW5wdXRcIlxuICAgICAgICAgICAgICAgICAgOnN0ZXA9XCJjdXJyZW50Lm1ldGhvZCA9PT0gJ3BlcmNlbnRhZ2UnID8gMC4xIDogaW5wdXRTdGVwXCJcbiAgICAgICAgICAgICAgICAvPlxuICAgICAgICAgICAgICAgIDxzcGFuIHYtaWY9XCJjdXJyZW50Lm1ldGhvZCA9PT0gJ3BlcmNlbnRhZ2UnXCIgY2xhc3M9XCJpbnB1dC1ncm91cC10ZXh0XCI+XG4gICAgICAgICAgICAgICAgICAgICVcbiAgICAgICAgICAgICAgICA8L3NwYW4+XG4gICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgIDwhLS0gUHJpY2luZyBNZXRob2QgLS0+XG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBtYi00XCI+XG4gICAgICAgICAgICAgIDxsYWJlbCBmb3I9XCJpbnB1dC1kaXNjb3VudC1tZXRob2RcIiBjbGFzcz1cImZvcm0tbGFiZWxcIj5cbiAgICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmRpc2NvdW50LmZpZWxkLm1ldGhvZCcpIH19XG4gICAgICAgICAgICAgIDwvbGFiZWw+XG4gICAgICAgICAgICAgIDxzZWxlY3QgaWQ9XCJpbnB1dC1kaXNjb3VudC1tZXRob2RcIiBjbGFzcz1cImZvcm0tc2VsZWN0XCJcbiAgICAgICAgICAgICAgICB2LW1vZGVsPVwiY3VycmVudC5tZXRob2RcIj5cbiAgICAgICAgICAgICAgICA8b3B0aW9uIHZhbHVlPVwicGVyY2VudGFnZVwiPlxuICAgICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5kaXNjb3VudC5tZXRob2QucGVyY2VudGFnZScpIH19XG4gICAgICAgICAgICAgICAgPC9vcHRpb24+XG4gICAgICAgICAgICAgICAgPG9wdGlvbiB2YWx1ZT1cIm9mZnNldHNcIj5cbiAgICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uZGlzY291bnQubWV0aG9kLm9mZnNldHMnKSB9fVxuICAgICAgICAgICAgICAgIDwvb3B0aW9uPlxuICAgICAgICAgICAgICAgIDxvcHRpb24gdmFsdWU9XCJmaXhlZFwiPlxuICAgICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5kaXNjb3VudC5tZXRob2QuZml4ZWQnKSB9fVxuICAgICAgICAgICAgICAgIDwvb3B0aW9uPlxuICAgICAgICAgICAgICA8L3NlbGVjdD5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICA8L2Rpdj5cbiAgICAgIDwvZGl2PlxuICAgIDwvZGl2PlxuXG4gICAgPHRleHRhcmVhIG5hbWU9XCJkaXNjb3VudHNcIiBjbGFzcz1cImQtbm9uZVwiIDp2YWx1ZT1cIml0ZW1zSlNPTlwiPjwvdGV4dGFyZWE+XG4gIDwvZGl2PlxuPC90ZW1wbGF0ZT5cblxuPHN0eWxlIHNjb3BlZD5cblxuPC9zdHlsZT5cbiIsImltcG9ydCB7IHVzZUZpZWxkRmxhdHBpY2tyIH0gZnJvbSAnQHdpbmR3YWxrZXItaW8vdW5pY29ybi1uZXh0JztcbmltcG9ydCB7IGNyZWF0ZUFwcCB9IGZyb20gJ3Z1ZSc7XG5pbXBvcnQgUHJvZHVjdERpc2NvdW50c0VkaXRBcHAgZnJvbSAnfnNob3Bnby9tb2R1bGVzL3Byb2R1Y3QtZWRpdC9Qcm9kdWN0RGlzY291bnRzRWRpdEFwcC52dWUnO1xuaW1wb3J0IHsgU2hvcEdvUGx1Z2luIH0gZnJvbSAnfnNob3Bnby9zaG9wZ28tcGx1Z2luJztcblxuZXhwb3J0IGZ1bmN0aW9uIGluaXRBcHAocHJvcHM6IFJlY29yZDxzdHJpbmcsIGFueT4pIHtcbiAgY29uc3QgYXBwID0gY3JlYXRlQXBwKFByb2R1Y3REaXNjb3VudHNFZGl0QXBwLCBwcm9wcyk7XG5cbiAgdXNlRmllbGRGbGF0cGlja3IoKTtcblxuICBhcHAudXNlKFNob3BHb1BsdWdpbik7XG5cbiAgcmV0dXJuIGFwcDtcbn1cblxuIl0sIm5hbWVzIjpbImRhdGVGb3JtYXQiLCJwcm9wcyIsIl9fcHJvcHMiLCJwcmVwYXJlSXRlbXMiLCJpdGVtcyIsInVuaXF1ZUl0ZW1MaXN0IiwiaXRlbSIsInJlZiIsImN1cnJlbnQiLCJjdXJyZW50SW5kZXgiLCJjdXJyZW50SGFzaCIsImxhc3RDaGVja0l0ZW1JbmRleCIsImZsYXRwaWNrck9wdGlvbnMiLCJpbnB1dFN0ZXAiLCJkYXRhIiwiaXRlbXNKU09OIiwiY29tcHV0ZWQiLCJmb3JtU3VibWl0dGluZyIsImluaXRpYWxIYXNoIiwiTWQ1Iiwic2F2ZVJlcXVpcmVkIiwiY2hlY2tlZEl0ZW1zIiwiaXQiLCJoYXNoSXRlbSIsIm5ld0l0ZW0iLCJkYXRlVG9TUUxGb3JtYXQiLCJkYXRlU3RyIiwiY2hlY2tBbGwiLCJldmVudCIsInRhcmdldCIsIm11bHRpQ2hlY2siLCJpIiwiayIsImNoZWNrZWQiLCJjb3VudENoZWNrZWQiLCJlZGl0SXRlbSIsIndhdGNoIiwibWV0aG9kIiwiY2FuY2VsRWRpdCIsImRlbGV0ZUl0ZW1zIiwicmVvcmRlciIsInRpbWVMaW1pdCIsInRleHQiLCJjb3JyZWN0UHJpY2VJbnB1dCIsIm9uUHJpY2VJbnB1dCIsImUiLCJtYWluUHJpY2UiLCJvbk1vdW50ZWQiLCJwcmljZUlucHV0IiwiZm9ybUVsZW1lbnQiLCJfaG9pc3RlZF8yIiwiX2hvaXN0ZWRfMyIsIl9ob2lzdGVkXzQiLCJfaG9pc3RlZF81IiwiX2hvaXN0ZWRfNiIsIl9ob2lzdGVkXzgiLCJfaG9pc3RlZF8xNyIsIl9ob2lzdGVkXzE4IiwiX2hvaXN0ZWRfMjAiLCJfaG9pc3RlZF8yMiIsIl9ob2lzdGVkXzMxIiwiX2hvaXN0ZWRfMzMiLCJfaG9pc3RlZF8zNCIsIl9ob2lzdGVkXzM1IiwiX2hvaXN0ZWRfMzYiLCJfaG9pc3RlZF8zNyIsIl9ob2lzdGVkXzM5IiwiX2hvaXN0ZWRfNDAiLCJfaG9pc3RlZF80MyIsIl9ob2lzdGVkXzQ0IiwiX2hvaXN0ZWRfNDgiLCJfaG9pc3RlZF81MiIsIl9ob2lzdGVkXzUzIiwiX2hvaXN0ZWRfNTUiLCJfaG9pc3RlZF81OCIsIl9ob2lzdGVkXzYwIiwiX2hvaXN0ZWRfNjEiLCJfaG9pc3RlZF82MiIsIl9vcGVuQmxvY2siLCJfY3JlYXRlRWxlbWVudEJsb2NrIiwiX2hvaXN0ZWRfMSIsIl9jcmVhdGVFbGVtZW50Vk5vZGUiLCIkc2V0dXAiLCJfY2FjaGUiLCJfY3JlYXRlVGV4dFZOb2RlIiwiX3RvRGlzcGxheVN0cmluZyIsIl9jdHgiLCJfY3JlYXRlQ29tbWVudFZOb2RlIiwiX2hvaXN0ZWRfNyIsIiRldmVudCIsIl9ob2lzdGVkXzkiLCJfaG9pc3RlZF8xMCIsIl9ob2lzdGVkXzExIiwiX2hvaXN0ZWRfMTIiLCJfaG9pc3RlZF8xMyIsIl9ob2lzdGVkXzE0IiwiX2hvaXN0ZWRfMTUiLCJfY3JlYXRlVk5vZGUiLCJfRnJhZ21lbnQiLCJfcmVuZGVyTGlzdCIsIl9ub3JtYWxpemVDbGFzcyIsIl9ob2lzdGVkXzE5IiwiX3ZNb2RlbENoZWNrYm94IiwiX2hvaXN0ZWRfMjEiLCJfaG9pc3RlZF8yMyIsIl9ob2lzdGVkXzI0IiwiX2hvaXN0ZWRfMjUiLCJfd2l0aERpcmVjdGl2ZXMiLCJfaG9pc3RlZF8yNiIsIl9ob2lzdGVkXzI4IiwiX2hvaXN0ZWRfMjkiLCJfaG9pc3RlZF8zMCIsIl9ob2lzdGVkXzMyIiwiX2hvaXN0ZWRfMzgiLCJfdk1vZGVsU2VsZWN0IiwiX1RyYW5zaXRpb24iLCJfd2l0aEN0eCIsIl9ob2lzdGVkXzQxIiwiX2hvaXN0ZWRfNDIiLCJfdk1vZGVsVGV4dCIsIl9ob2lzdGVkXzQ1IiwiX2hvaXN0ZWRfNDciLCJfaG9pc3RlZF80OSIsIl9ob2lzdGVkXzUxIiwiX2hvaXN0ZWRfNTQiLCJfaG9pc3RlZF81NiIsIl9ob2lzdGVkXzU3IiwiX2hvaXN0ZWRfNTkiLCJfaG9pc3RlZF82MyIsImluaXRBcHAiLCJhcHAiLCJjcmVhdGVBcHAiLCJQcm9kdWN0RGlzY291bnRzRWRpdEFwcCIsInVzZUZpZWxkRmxhdHBpY2tyIiwiU2hvcEdvUGx1Z2luIl0sIm1hcHBpbmdzIjoiOzs7Ozs7O0FBY0EsTUFBTUEsSUFBYTs7Ozs7Ozs7QUFMbkIsVUFBTUMsSUFBUUM7QUFPZCxhQUFTQyxFQUFhQyxHQUFtQjtBQUN2QyxhQUFPQyxHQUFlRCxDQUFLLEVBQUUsSUFBSSxDQUFDRSxPQUNoQ0EsRUFBSyxVQUFVLElBQ2ZBLEVBQUssU0FBUyxJQUVQQSxFQUNSO0FBQUEsSUFDSDtBQUdBLFVBQU1GLElBQVFHLEVBQWdCSixFQUFhRixFQUFNLGFBQWEsQ0FBQSxDQUFFLENBQUMsR0FDM0RPLElBQVVELEVBQUEsR0FDVkUsSUFBZUYsRUFBWSxFQUFFLEdBQzdCRyxJQUFjSCxFQUFZLEVBQUUsR0FDNUJJLElBQXFCSixFQUFtQixJQUFJLEdBQzVDSyxJQUFtQkwsRUFBWSxLQUFLLFVBQVU7QUFBQSxNQUNsRCxZQUFBUDtBQUFBLE1BQ0EsWUFBWTtBQUFBLE1BQ1osZUFBZTtBQUFBLE1BQ2YsWUFBWTtBQUFBLE1BQ1osV0FBVztBQUFBLE1BQ1gsYUFBYTtBQUFBLElBQUEsQ0FDZCxDQUFDLEdBQ0lhLElBQVlOLEVBQVlPLEVBQUssWUFBWSxLQUFLLFFBQVEsR0FFdERDLElBQVlDLEVBQVMsTUFBTSxLQUFLLFVBQVVaLEVBQU0sS0FBSyxDQUFDO0FBRzVELFFBQUlhLElBQWlCO0FBQ3JCLFVBQU1DLElBQWNDLEVBQUksUUFBUSxLQUFLLFVBQVVmLEVBQU0sS0FBSyxDQUFDLEdBQ3JEZ0IsSUFBZUosRUFBUyxNQUFNRyxFQUFJLFFBQVFKLEVBQVUsS0FBSyxNQUFNRyxDQUFXLEdBQzFFRyxJQUFlTCxFQUFTLE1BQU1aLEVBQU0sTUFBTSxPQUFPLENBQUNrQixNQUFZQSxFQUFHLE9BQU8sQ0FBQztBQUcvRSxhQUFTQyxFQUFTakIsR0FB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
