import { _ as A, r as F } from "./_plugin-vue_export-helper.js";
import { defineComponent as O, ref as m, createElementBlock as u, openBlock as p, createElementVNode as o, createTextVNode as l, toDisplayString as a, createVNode as V, withCtx as I, TransitionGroup as D, Fragment as U, renderList as T, normalizeClass as q, createCommentVNode as w, withDirectives as v, withModifiers as b, vModelCheckbox as z, normalizeStyle as L, vModelText as y, createApp as M } from "vue";
import { uniqueItemList as B, uniqueItem as G } from "@lyrasoft/ts-toolkit/vue";
import { useCssImport as J } from "@windwalker-io/unicorn-next";
import { VueDraggable as j } from "vue-draggable-plus";
import "bootstrap";
import { vColorpicker as H, ShopGoPlugin as K } from "../index.js";
const Q = /* @__PURE__ */ O({
  __name: "ProductFeatureEditApp",
  props: {
    options: {}
  },
  setup(s, { expose: t }) {
    t(), J("@vue-animate");
    const c = document.querySelector("#input-item-type"), n = s, f = m(c.value), r = m(
      B(n.options || []).map((i) => ({
        data: i,
        uid: i.uid,
        selected: !1
      }))
    ), e = m(null), d = m([]), h = m({});
    c.addEventListener("change", () => {
      f.value = c.value;
    });
    function _(i) {
      e.value = i;
    }
    function P(i) {
      const g = i ? r.value.indexOf(i) + 1 : r.value.length, x = G({
        value: "",
        text: "",
        color: ""
      }), C = {
        data: x,
        uid: x.uid,
        selected: !1
      };
      r.value.splice(g, 0, C), _(C);
    }
    function E(i) {
      const g = r.value.indexOf(i);
      r.value.splice(g, 1);
    }
    function N() {
      r.value = r.value.filter((i) => !d.value.includes(i.uid)), d.value.includes(e.value?.uid || "") && (e.value = null), d.value = [];
    }
    function S(i) {
      return JSON.stringify(i);
    }
    const k = { $typeSelect: c, props: n, type: f, items: r, current: e, selected: d, colorPickerOptions: h, selectItem: _, addNewItem: P, removeItem: E, removeItems: N, toJson: S, get VueDraggable() {
      return j;
    }, get vColorpicker() {
      return H;
    } };
    return Object.defineProperty(k, "__isScriptSetup", { enumerable: !1, value: !0 }), k;
  }
}), R = { class: "row" }, W = { class: "col-lg-6" }, X = { class: "card c-feature-option-list" }, Y = { class: "card-header d-flex align-items-center" }, Z = { class: "m-0" }, $ = { class: "c-list-top-toolbar ms-auto" }, tt = ["disabled"], ot = { class: "c-option-list list-group list-group-flush" }, et = ["onClick"], nt = { class: "d-flex align-items-center gap-2" }, lt = { class: "c-option-item__control" }, st = ["value"], it = {
  key: 0,
  class: "c-option-item__color"
}, rt = { class: "c-option-control__title flex-grow-1" }, dt = { class: "h5 m-0" }, at = { class: "c-option-control__actions d-flex align-items-center gap-1" }, ut = ["onClick"], pt = ["onClick"], ct = { class: "d-none" }, mt = ["name", "value"], vt = ["name", "value"], ft = ["name", "value"], gt = ["name", "value"], bt = { class: "col-lg-6 l-feature-option-item" }, yt = { class: "card-header" }, _t = { class: "card-body" }, kt = {
  key: 0,
  class: "c-option-edit__form"
}, xt = { class: "form-group mb-4" }, Ct = {
  for: "input-option-text",
  class: "form-label"
}, Vt = { class: "form-group mb-4" }, It = {
  for: "input-option-value",
  class: "form-label"
}, wt = {
  key: 0,
  class: "form-group mb-4"
}, ht = {
  for: "input-option-value",
  class: "form-label"
}, Pt = { key: 1 }, Et = { class: "card bg-light" }, Nt = { class: "card-body text-center" };
function St(s, t, c, n, f, r) {
  return p(), u("div", R, [
    o("div", W, [
      o("div", X, [
        o("div", Y, [
          o("h3", Z, a(s.$lang("shopgo.product.feature.options.title")), 1),
          t[11] || (t[11] = l()),
          o("div", $, [
            o("button", {
              type: "button",
              class: "btn btn-sm btn-primary",
              onClick: t[0] || (t[0] = (e) => n.addNewItem())
            }, [
              t[8] || (t[8] = o("span", { class: "fa fa-plus" }, null, -1)),
              l(" " + a(s.$lang("shopgo.product.feature.button.new")), 1)
            ]),
            t[10] || (t[10] = l()),
            o("button", {
              type: "button",
              class: "btn btn-sm btn-outline-danger",
              onClick: t[1] || (t[1] = (e) => n.removeItems()),
              disabled: n.selected.length === 0
            }, [
              t[9] || (t[9] = o("span", { class: "fa fa-trash" }, null, -1)),
              l(" " + a(s.$lang("shopgo.product.feature.button.delete")), 1)
            ], 8, tt)
          ])
        ]),
        t[24] || (t[24] = l()),
        o("div", ot, [
          V(n.VueDraggable, {
            modelValue: n.items,
            "onUpdate:modelValue": t[4] || (t[4] = (e) => n.items = e),
            handle: ".handle",
            animation: 150
          }, {
            default: I(() => [
              V(D, { name: "fade" }, {
                default: I(() => [
                  (p(!0), u(U, null, T(n.items, (e) => (p(), u("div", {
                    key: e.uid,
                    class: q(["list-group-item c-option-item", [{ active: n.current === e }]]),
                    onClick: (d) => n.selectItem(e),
                    style: { cursor: "pointer", "animation-duration": ".3s" }
                  }, [
                    o("div", nt, [
                      o("div", lt, [
                        t[12] || (t[12] = o("span", {
                          class: "fa fa-fw fa-ellipsis-v handle",
                          style: { cursor: "move" }
                        }, null, -1)),
                        t[13] || (t[13] = l()),
                        v(o("input", {
                          type: "checkbox",
                          name: "selected[]",
                          "onUpdate:modelValue": t[2] || (t[2] = (d) => n.selected = d),
                          class: "form-check-input",
                          value: e.uid,
                          onClick: t[3] || (t[3] = b(() => {
                          }, ["stop"]))
                        }, null, 8, st), [
                          [z, n.selected]
                        ])
                      ]),
                      t[17] || (t[17] = l()),
                      n.type === "color" ? (p(), u("div", it, [
                        o("div", {
                          class: "c-option-item__color-box rounded",
                          style: L([{ width: "25px", height: "25px" }, { "background-color": e.data.color || "#eee" }])
                        }, null, 4)
                      ])) : w("", !0),
                      t[18] || (t[18] = l()),
                      o("div", rt, [
                        o("div", dt, a(e.data.text || s.$lang("shopgo.product.feature.text.unnamed")), 1)
                      ]),
                      t[19] || (t[19] = l()),
                      o("div", at, [
                        o("button", {
                          type: "button",
                          class: "btn btn-sm btn-light border-secondary",
                          onClick: b((d) => n.addNewItem(e), ["stop"])
                        }, [...t[14] || (t[14] = [
                          o("span", { class: "fa fa-plus" }, null, -1)
                        ])], 8, ut),
                        t[16] || (t[16] = l()),
                        o("button", {
                          type: "button",
                          class: "btn btn-sm btn-outline-danger",
                          onClick: b((d) => n.removeItem(e), ["stop"])
                        }, [...t[15] || (t[15] = [
                          o("span", { class: "fa fa-trash" }, null, -1)
                        ])], 8, pt)
                      ])
                    ]),
                    t[23] || (t[23] = l()),
                    o("div", ct, [
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][uid]`,
                        value: e.data.uid
                      }, null, 8, mt),
                      t[20] || (t[20] = l()),
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][text]`,
                        value: e.data.text
                      }, null, 8, vt),
                      t[21] || (t[21] = l()),
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][value]`,
                        value: e.data.value
                      }, null, 8, ft),
                      t[22] || (t[22] = l()),
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][color]`,
                        value: e.data.color
                      }, null, 8, gt)
                    ])
                  ], 10, et))), 128))
                ]),
                _: 1
              })
            ]),
            _: 1
          }, 8, ["modelValue"])
        ])
      ])
    ]),
    t[31] || (t[31] = l()),
    o("div", bt, [
      (p(), u("div", {
        class: "card c-option-edit",
        key: n.current?.uid
      }, [
        o("div", yt, a(s.$lang("shopgo.product.feature.option.data.title")), 1),
        t[30] || (t[30] = l()),
        o("div", _t, [
          n.current ? (p(), u("div", kt, [
            o("div", xt, [
              o("label", Ct, a(s.$lang("shopgo.product.feature.option.text")), 1),
              t[25] || (t[25] = l()),
              v(o("input", {
                id: "input-option-text",
                type: "text",
                class: "form-control",
                "onUpdate:modelValue": t[5] || (t[5] = (e) => n.current.data.text = e)
              }, null, 512), [
                [y, n.current.data.text]
              ])
            ]),
            t[28] || (t[28] = l()),
            o("div", Vt, [
              o("label", It, a(s.$lang("shopgo.product.feature.option.value")), 1),
              t[26] || (t[26] = l()),
              v(o("input", {
                id: "input-option-value",
                type: "text",
                class: "form-control",
                "onUpdate:modelValue": t[6] || (t[6] = (e) => n.current.data.value = e)
              }, null, 512), [
                [y, n.current.data.value]
              ])
            ]),
            t[29] || (t[29] = l()),
            n.type === "color" ? (p(), u("div", wt, [
              o("label", ht, a(s.$lang("shopgo.product.feature.option.color")), 1),
              t[27] || (t[27] = l()),
              o("div", null, [
                v(o("input", {
                  id: "input-option-color",
                  type: "text",
                  class: "form-control",
                  "onUpdate:modelValue": t[7] || (t[7] = (e) => n.current.data.color = e)
                }, null, 512), [
                  [n.vColorpicker, n.colorPickerOptions],
                  [
                    y,
                    n.current.data.color,
                    void 0,
                    { lazy: !0 }
                  ]
                ])
              ])
            ])) : w("", !0)
          ])) : (p(), u("div", Pt, [
            o("div", Et, [
              o("div", Nt, a(s.$lang("shopgo.product.feature.option.no.select")), 1)
            ])
          ]))
        ])
      ]))
    ])
  ]);
}
const At = /* @__PURE__ */ A(Q, [["render", St], ["__file", "ProductFeatureEditApp.vue"]]), Ft = F("ProductFeatureEditApp", At);
function Mt(s) {
  const t = M(Ft, s);
  return t.use(K), t;
}
export {
  Mt as initApp
};
//# sourceMappingURL=product-feature-edit.js.map
