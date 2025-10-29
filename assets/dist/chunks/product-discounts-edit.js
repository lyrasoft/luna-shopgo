import { _ as X, r as $ } from "./_plugin-vue_export-helper.js";
import { data as tt, useFieldFlatpickr as et } from "@windwalker-io/unicorn-next";
import { defineComponent as ot, ref as g, computed as D, watch as M, onMounted as nt, createElementBlock as f, openBlock as m, createElementVNode as e, createTextVNode as i, createCommentVNode as y, toDisplayString as r, createVNode as A, withCtx as F, Fragment as st, renderList as it, normalizeClass as lt, withDirectives as b, vModelCheckbox as dt, vModelSelect as J, Transition as ut, vModelText as I, createApp as rt } from "vue";
import { uniqueItemList as at } from "@lyrasoft/ts-toolkit/vue";
import { h as V } from "./index.es.js";
import { vTooltip as pt, ShopGoPlugin as ct } from "../index.js";
import { VueDraggable as ft } from "vue-draggable-plus";
const U = "Y-m-d H:i:S", mt = /* @__PURE__ */ ot({
  __name: "ProductDiscountsEditApp",
  props: {
    product: {},
    discounts: {}
  },
  setup(u, { expose: t }) {
    t();
    const k = u;
    function n(o) {
      return at(o).map((l) => (l.checked = !1, l.unsave = !1, l));
    }
    const a = g(n(k.discounts || [])), d = g(), s = g(-1), v = g(""), p = g(null), Q = g(JSON.stringify({
      dateFormat: U,
      enableTime: !0,
      enableSeconds: !0,
      allowInput: !0,
      time_24hr: !0,
      monthSelect: !1
    })), H = g(tt("input.step") || "0.0001"), P = D(() => JSON.stringify(a.value));
    let _ = !1;
    const E = V.hashStr(JSON.stringify(a.value)), q = D(() => V.hashStr(P.value) !== E), O = D(() => a.value.filter((o) => o.checked));
    function w(o) {
      const l = { ...o };
      return delete l.checked, delete l.unsave, V.hashStr(JSON.stringify(l));
    }
    function x(o) {
      return !o || !flatpickr ? o : flatpickr.formatDate(flatpickr.parseDate(o), U);
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
        const L = o.target.checked;
        if (p.value < c)
          for (; h < c; h++)
            a.value[h].checked = L;
        else
          for (; h > c; h--)
            a.value[h].checked = L;
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
    M(() => d.value?.method, (o) => {
      !d.value || s.value === -1 || (["percentage", "fixed"].indexOf(o) !== -1 ? d.value.price = Math.abs(d.value.price) : d.value.price = -Math.abs(d.value.price));
    });
    async function N(o, l) {
      o.publishUp = x(o.publishUp), o.publishDown = x(o.publishDown), v.value = w(o), d.value = o, s.value = l;
    }
    M(
      () => d.value,
      () => {
        v.value !== "" && v.value !== w(d.value) && s.value >= 0 && s.value < a.value.length && (a.value[s.value].unsave = !0);
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
    nt(() => {
      const o = document.querySelector("#input-item-variant-price"), l = document.querySelector("#admin-form");
      C.value = parseFloat(o.value).toString(), o.addEventListener("change", () => {
        C.value = parseFloat(o.value).toString();
      }), l.addEventListener("submit", (c) => {
        _ = !0;
      }), window.addEventListener("beforeunload", (c) => {
        if (q.value && !_)
          return c.preventDefault(), c.stopPropagation(), c.returnValue = "Save Required", "Save Required";
      });
    });
    const T = { props: k, dateFormat: U, prepareItems: n, items: a, current: d, currentIndex: s, currentHash: v, lastCheckItemIndex: p, flatpickrOptions: Q, inputStep: H, itemsJSON: P, get formSubmitting() {
      return _;
    }, set formSubmitting(o) {
      _ = o;
    }, initialHash: E, saveRequired: q, checkedItems: O, hashItem: w, dateToSQLFormat: x, checkAll: R, multiCheck: B, countChecked: Z, newItem: j, editItem: N, cancelEdit: S, deleteItems: z, reorder: G, timeLimit: K, correctPriceInput: Y, onPriceInput: W, mainPrice: C, get vTooltip() {
      return pt;
    }, get VueDraggable() {
      return ft;
    } };
    return Object.defineProperty(T, "__isScriptSetup", { enumerable: !1, value: !0 }), T;
  }
}), vt = {
  class: "l-product-discount row",
  "data-novalidate": ""
}, gt = { class: "col-lg-6 l-product-discount__list" }, bt = { class: "card c-discount-list" }, ht = { class: "card-header c-discount-list__toolbar d-flex" }, yt = { class: "ms-auto" }, kt = { class: "c-discount-list__items list-group list-group-flush" }, _t = {
  class: "list-group-item c-discount-list__header d-flex gap-2",
  style: { "margin-bottom": "0" }
}, wt = { class: "" }, xt = [".indeterminate"], St = {
  class: "flex-fill",
  style: {}
}, Ct = {
  class: "text-end",
  style: { width: "100px" }
}, Dt = {
  class: "text-end",
  style: { width: "100px" }
}, It = {
  class: "",
  style: { width: "75px" }
}, Vt = {
  class: "",
  style: { width: "75px" }
}, Ut = {
  class: "c-discount-list__scroll list-group list-group-flush",
  style: { "overflow-y": "scroll", height: "75vh", "min-height": "400px" }
}, Pt = ["data-id"], Et = { class: "list-group-item__wrapper d-flex align-items-center gap-2" }, qt = { class: "c-discount-item__control d-flex flex-nowrap" }, Ot = ["onUpdate:modelValue", "onClick"], Nt = { class: "c-discount-item__type flex-fill text-nowrap" }, Tt = { key: 0 }, Lt = { class: "badge bg-warning" }, Mt = {
  class: "c-discount-item__quantity text-end",
  style: { width: "100px" }
}, At = {
  class: "c-discount-item__price text-end flex-fill",
  style: { width: "100px" }
}, Ft = {
  class: "c-discount-item__time-limit text-center",
  style: { width: "75px" }
}, Jt = ["title"], Qt = { key: 1 }, Ht = {
  class: "c-discount-item__actions text-nowrap text-end",
  style: { width: "75px" }
}, Rt = ["onClick"], Bt = ["onClick"], Zt = { class: "col-lg-6 l-product-discount__manage" }, jt = {
  key: 0,
  class: "c-discount-edit card"
}, zt = { class: "card-header d-flex" }, Gt = { class: "c-discount-edit__title" }, Kt = { class: "card-body" }, Yt = { class: "d-flex gap-2" }, Wt = { class: "form-group mb-4" }, Xt = {
  for: "input-discount-subtype",
  class: "form-label"
}, $t = { value: "discount" }, te = { value: "special" }, ee = {
  key: 0,
  class: "form-group mb-4",
  style: { "animation-duration": ".3s" }
}, oe = {
  for: "input-discount-quantity",
  class: "form-label"
}, ne = { class: "d-flex gap-2" }, se = { class: "form-group mb-4" }, ie = {
  for: "input-discount-start_date",
  class: "form-label"
}, le = ["options"], de = {
  class: "input-group",
  "data-calendar": ""
}, ue = { class: "form-group mb-4" }, re = {
  for: "input-discount-end_date",
  class: "form-label"
}, ae = ["options"], pe = {
  class: "input-group",
  "data-calendar": ""
}, ce = { class: "d-flex gap-2" }, fe = { class: "form-group mb-4" }, me = {
  for: "input-discount-price",
  class: "form-label"
}, ve = { class: "input-group" }, ge = ["value", "step"], be = {
  key: 0,
  class: "input-group-text"
}, he = { class: "form-group mb-4" }, ye = {
  for: "input-discount-method",
  class: "form-label"
}, ke = { value: "percentage" }, _e = { value: "offsets" }, we = { value: "fixed" }, xe = ["value"];
function Se(u, t, k, n, a, d) {
  return m(), f("div", vt, [
    e("div", gt, [
      e("div", bt, [
        e("div", ht, [
          e("div", yt, [
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
        e("div", kt, [
          e("div", _t, [
            e("div", wt, [
              t[14] || (t[14] = e("span", { class: "fa fa-arrows-alt-v fa-fw me-1" }, null, -1)),
              t[15] || (t[15] = i()),
              e("input", {
                type: "checkbox",
                onChange: t[2] || (t[2] = (s) => n.checkAll(s)),
                class: "form-check-input",
                ".indeterminate": n.countChecked() > 0 && n.countChecked() < n.items.length
              }, null, 40, xt)
            ]),
            t[16] || (t[16] = i()),
            e("div", St, r(u.$lang("shopgo.product.discount.field.type")), 1),
            t[17] || (t[17] = i()),
            e("div", Ct, r(u.$lang("shopgo.discount.field.min.product.quantity")), 1),
            t[18] || (t[18] = i()),
            e("div", Dt, r(u.$lang("shopgo.product.discount.field.price.offsets")), 1),
            t[19] || (t[19] = i()),
            e("div", It, r(u.$lang("shopgo.product.discount.field.time")), 1),
            t[20] || (t[20] = i()),
            e("div", Vt, r(u.$lang("shopgo.product.discount.actions")), 1)
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
                (m(!0), f(st, null, it(n.items, (s, v) => (m(), f("div", {
                  key: s.uid,
                  class: lt(["list-group-item c-discount-item", { "text-bg-dark": n.current?.uid === s.uid }]),
                  "data-id": s.id
                }, [
                  e("div", Et, [
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
                      }, null, 8, Ot), [
                        [dt, s.checked]
                      ])
                    ]),
                    t[26] || (t[26] = i()),
                    e("div", Nt, [
                      i(r(u.$lang("shopgo.discount.subtype." + s.subtype)) + " ", 1),
                      s.unsave ? (m(), f("div", Tt, [
                        e("span", Lt, r(u.$lang("shopgo.product.text.save.required")), 1)
                      ])) : y("", !0)
                    ]),
                    t[27] || (t[27] = i()),
                    e("div", Mt, r(s.subtype === "discount" ? s.minProductQuantity : "-"), 1),
                    t[28] || (t[28] = i()),
                    e("div", At, r(u.$priceOffset(s.price, s.method)), 1),
                    t[29] || (t[29] = i()),
                    e("div", Ft, [
                      s.publishUp || s.publishDown ? b((m(), f("span", {
                        key: 0,
                        class: "fa fa-clock has-tooltip",
                        title: n.timeLimit(s)
                      }, null, 8, Jt)), [
                        [n.vTooltip]
                      ]) : (m(), f("span", Qt, "-"))
                    ]),
                    t[30] || (t[30] = i()),
                    e("div", Ht, [
                      e("button", {
                        type: "button",
                        class: "btn btn-sm btn-light border-secondary",
                        onClick: (p) => n.editItem(s, v)
                      }, [...t[23] || (t[23] = [
                        e("span", { class: "fa fa-pencil-alt" }, null, -1)
                      ])], 8, Rt),
                      t[25] || (t[25] = i()),
                      e("button", {
                        type: "button",
                        class: "btn btn-sm btn-light border-secondary",
                        onClick: (p) => n.deleteItems(s)
                      }, [...t[24] || (t[24] = [
                        e("span", { class: "fa fa-trash text-danger" }, null, -1)
                      ])], 8, Bt)
                    ])
                  ])
                ], 10, Pt))), 128))
              ]),
              _: 1
            }, 8, ["modelValue"])
          ])
        ])
      ])
    ]),
    t[59] || (t[59] = i()),
    e("div", Zt, [
      n.current ? (m(), f("div", jt, [
        e("div", zt, [
          e("div", Gt, r(u.$lang("shopgo.product.discount.edit.title")), 1),
          t[33] || (t[33] = i()),
          t[34] || (t[34] = e("div", { class: "c-discount-edit__actions ms-auto" }, null, -1))
        ]),
        t[58] || (t[58] = i()),
        e("div", Kt, [
          e("div", Yt, [
            e("div", Wt, [
              e("label", Xt, r(u.$lang("shopgo.product.discount.field.mode")), 1),
              t[36] || (t[36] = i()),
              b(e("select", {
                id: "input-discount-subtype",
                class: "form-select",
                style: { "min-width": "100px" },
                "onUpdate:modelValue": t[4] || (t[4] = (s) => n.current.subtype = s)
              }, [
                e("option", $t, r(u.$lang("shopgo.discount.subtype.discount")), 1),
                t[35] || (t[35] = i()),
                e("option", te, r(u.$lang("shopgo.discount.subtype.special")), 1)
              ], 512), [
                [J, n.current.subtype]
              ])
            ]),
            t[38] || (t[38] = i()),
            A(ut, { name: "fade" }, {
              default: F(() => [
                n.current.subtype === "discount" ? (m(), f("div", ee, [
                  e("label", oe, r(u.$lang("shopgo.discount.field.min.product.quantity")), 1),
                  t[37] || (t[37] = i()),
                  b(e("input", {
                    id: "input-discount-quantity",
                    type: "number",
                    class: "form-control",
                    "onUpdate:modelValue": t[5] || (t[5] = (s) => n.current.minProductQuantity = s),
                    min: "0"
                  }, null, 512), [
                    [I, n.current.minProductQuantity]
                  ])
                ])) : y("", !0)
              ]),
              _: 1
            })
          ]),
          t[56] || (t[56] = i()),
          e("div", ne, [
            e("div", se, [
              e("label", ie, r(u.$lang("shopgo.discount.field.publish.up")), 1),
              t[43] || (t[43] = i()),
              e("uni-flatpickr", { options: n.flatpickrOptions }, [
                e("div", de, [
                  b(e("input", {
                    id: "input-discount-start_date",
                    type: "text",
                    class: "form-control",
                    "onUpdate:modelValue": t[6] || (t[6] = (s) => n.current.publishUp = s),
                    "data-input": ""
                  }, null, 512), [
                    [I, n.current.publishUp]
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
              ], 8, le)
            ]),
            t[49] || (t[49] = i()),
            e("div", ue, [
              e("label", re, r(u.$lang("shopgo.discount.field.publish.down")), 1),
              t[48] || (t[48] = i()),
              e("uni-flatpickr", { options: n.flatpickrOptions }, [
                e("div", pe, [
                  b(e("input", {
                    id: "input-discount-end_date",
                    type: "text",
                    class: "form-control",
                    "onUpdate:modelValue": t[8] || (t[8] = (s) => n.current.publishDown = s),
                    "data-input": ""
                  }, null, 512), [
                    [I, n.current.publishDown]
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
              ], 8, ae)
            ])
          ]),
          t[57] || (t[57] = i()),
          e("div", ce, [
            e("div", fe, [
              e("label", me, r(u.$lang("shopgo.product.discount.field.price.offsets")), 1),
              t[51] || (t[51] = i()),
              e("div", ve, [
                e("input", {
                  id: "input-discount-price",
                  type: "number",
                  class: "form-control",
                  value: n.current.price,
                  onInput: n.onPriceInput,
                  onChange: n.correctPriceInput,
                  step: n.current.method === "percentage" ? 0.1 : n.inputStep
                }, null, 40, ge),
                t[50] || (t[50] = i()),
                n.current.method === "percentage" ? (m(), f("span", be, `
                    %
                `)) : y("", !0)
              ])
            ]),
            t[55] || (t[55] = i()),
            e("div", he, [
              e("label", ye, r(u.$lang("shopgo.discount.field.method")), 1),
              t[54] || (t[54] = i()),
              b(e("select", {
                id: "input-discount-method",
                class: "form-select",
                "onUpdate:modelValue": t[10] || (t[10] = (s) => n.current.method = s)
              }, [
                e("option", ke, r(u.$lang("shopgo.discount.method.percentage")), 1),
                t[52] || (t[52] = i()),
                e("option", _e, r(u.$lang("shopgo.discount.method.offsets")), 1),
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
    }, null, 8, xe)
  ]);
}
const Ce = /* @__PURE__ */ X(mt, [["render", Se], ["__file", "ProductDiscountsEditApp.vue"]]), De = $("ProductDiscountsEditApp", Ce);
function Ne(u) {
  const t = rt(De, u);
  return et(), t.use(ct), t;
}
export {
  Ne as initApp
};
//# sourceMappingURL=product-discounts-edit.js.map
