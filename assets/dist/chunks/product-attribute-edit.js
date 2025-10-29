import { _ as S, r as P } from "./_plugin-vue_export-helper.js";
import { defineComponent as D, ref as v, createElementBlock as r, createCommentVNode as y, openBlock as p, createElementVNode as o, createTextVNode as l, toDisplayString as u, createVNode as V, withCtx as I, TransitionGroup as M, Fragment as O, renderList as T, normalizeStyle as q, withDirectives as m, withModifiers as c, vModelCheckbox as L, vModelRadio as B, vModelText as A, createApp as G } from "vue";
import { uniqueItemList as J, uniqueItem as j } from "@lyrasoft/ts-toolkit/vue";
import { useCssImport as z } from "@windwalker-io/unicorn-next";
import { VueDraggable as F } from "vue-draggable-plus";
import { ShopGoPlugin as R } from "../index.js";
const H = /* @__PURE__ */ D({
  __name: "ProductAttributeEditApp",
  props: {
    options: {}
  },
  setup(i, { expose: t }) {
    t(), z("@vue-animate");
    const b = i, n = document.querySelector("#input-item-type"), a = v(
      J(b.options || []).map((s) => ({
        data: s,
        uid: s.uid,
        selected: !1
      }))
    ), g = v(n.value), e = v(null), d = v([]), h = v(a.value.find((s) => s.data.is_default)?.uid);
    n.addEventListener("change", () => {
      g.value = n.value;
    });
    function _(s) {
      e.value = s;
    }
    function w(s = null) {
      const f = s ? a.value.indexOf(s) + 1 : a.value.length, x = j({
        value: "",
        text: "",
        is_default: !1
      }), C = {
        data: x,
        uid: x.uid,
        selected: !1
      };
      a.value.splice(f, 0, C), _(C);
    }
    function U(s) {
      const f = a.value.indexOf(s);
      f !== -1 && a.value.splice(f, 1), e.value === s && (e.value = null);
    }
    function E() {
      a.value = a.value.filter((s) => !d.value.includes(s.uid)), d.value.includes(e.value?.uid || "") && (e.value = null), d.value = [];
    }
    function N(s) {
      return JSON.stringify(s);
    }
    const k = { props: b, $typeSelect: n, items: a, type: g, current: e, selected: d, defaultUid: h, selectItem: _, addNewItem: w, removeItem: U, removeItems: E, toJson: N, get VueDraggable() {
      return F;
    } };
    return Object.defineProperty(k, "__isScriptSetup", { enumerable: !1, value: !0 }), k;
  }
}), K = {
  key: 0,
  class: "row"
}, Q = { class: "col-lg-6" }, W = { class: "card c-feature-option-list" }, X = { class: "card-header d-flex align-items-center" }, Y = { class: "m-0" }, Z = { class: "c-list-top-toolbar ms-auto" }, $ = ["disabled"], tt = { class: "c-option-list list-group list-group-flush" }, ot = ["onClick"], et = { class: "d-flex align-items-center gap-2" }, nt = { class: "c-option-item__control" }, lt = ["value"], st = { class: "c-option-control__title flex-grow-1" }, it = { class: "h5 m-0" }, dt = {
  key: 0,
  style: { opacity: ".5" }
}, at = { class: "c-option-control__actions d-flex align-items-center gap-1" }, ut = ["value", "id"], rt = ["for"], pt = ["onClick"], vt = ["onClick"], ft = { class: "d-none" }, mt = ["name", "value"], ct = ["name", "value"], bt = ["name", "value"], gt = ["name", "checked", "value"], yt = { class: "col-lg-6 l-feature-option-item" }, _t = { class: "card c-option-edit" }, kt = { class: "card-header" }, xt = { class: "card-body" }, Ct = {
  key: 0,
  class: "c-option-edit__form"
}, Vt = { class: "form-group mb-4" }, It = {
  for: "input-option-text",
  class: "form-label"
}, At = {
  key: 0,
  class: "form-group mb-4"
}, ht = {
  for: "input-option-value",
  class: "form-label"
}, wt = { key: 1 }, Ut = { class: "card bg-light" }, Et = { class: "card-body text-center" };
function Nt(i, t, b, n, a, g) {
  return n.type !== "bool" ? (p(), r("div", K, [
    o("div", Q, [
      o("div", W, [
        o("div", X, [
          o("h3", Y, u(i.$lang("shopgo.product.attribute.options.title")), 1),
          t[12] || (t[12] = l()),
          o("div", Z, [
            o("button", {
              type: "button",
              class: "btn btn-sm btn-primary",
              onClick: t[0] || (t[0] = (e) => n.addNewItem())
            }, [
              t[9] || (t[9] = o("span", { class: "fa fa-plus" }, null, -1)),
              l(" " + u(i.$lang("shopgo.product.attribute.button.new")), 1)
            ]),
            t[11] || (t[11] = l()),
            o("button", {
              type: "button",
              class: "btn btn-sm btn-outline-danger",
              onClick: t[1] || (t[1] = (e) => n.removeItems()),
              disabled: n.selected.length === 0
            }, [
              t[10] || (t[10] = o("span", { class: "fa fa-trash" }, null, -1)),
              l(" " + u(i.$lang("shopgo.product.attribute.button.delete")), 1)
            ], 8, $)
          ])
        ]),
        t[27] || (t[27] = l()),
        o("div", tt, [
          V(n.VueDraggable, {
            modelValue: n.items,
            "onUpdate:modelValue": t[6] || (t[6] = (e) => n.items = e),
            handle: ".handle",
            "item-key": "uid",
            animation: 150
          }, {
            default: I(() => [
              V(M, { name: "fade" }, {
                default: I(() => [
                  (p(!0), r(O, null, T(n.items, (e) => (p(), r("div", {
                    key: e.uid,
                    class: "list-group-item c-option-item",
                    style: q([[n.current === e ? "background: rgba(var(--bs-primary-rgb), .3)" : "", "animation-duration: .3s;"], { cursor: "pointer" }]),
                    onClick: (d) => n.selectItem(e)
                  }, [
                    o("div", et, [
                      o("div", nt, [
                        t[13] || (t[13] = o("span", {
                          class: "fa fa-fw fa-ellipsis-v handle",
                          style: { cursor: "move" }
                        }, null, -1)),
                        t[14] || (t[14] = l()),
                        m(o("input", {
                          type: "checkbox",
                          name: "selected[]",
                          "onUpdate:modelValue": t[2] || (t[2] = (d) => n.selected = d),
                          class: "form-check-input",
                          value: e.uid,
                          onClick: t[3] || (t[3] = c(() => {
                          }, ["stop"]))
                        }, null, 8, lt), [
                          [L, n.selected]
                        ])
                      ]),
                      t[21] || (t[21] = l()),
                      o("div", st, [
                        o("span", it, u(e.data.text || i.$lang("shopgo.product.attribute.text.unnameed")), 1),
                        t[15] || (t[15] = l()),
                        n.type === "select" ? (p(), r("span", dt, `
                        (` + u(e.data.value) + `)
                    `, 1)) : y("", !0)
                      ]),
                      t[22] || (t[22] = l()),
                      o("div", at, [
                        o("div", {
                          class: "form-check mb-0 me-2",
                          onClick: t[5] || (t[5] = c(() => {
                          }, ["stop"]))
                        }, [
                          m(o("input", {
                            type: "radio",
                            value: e.uid,
                            class: "form-check-input",
                            id: "default-radio-" + e.uid,
                            "onUpdate:modelValue": t[4] || (t[4] = (d) => n.defaultUid = d)
                          }, null, 8, ut), [
                            [B, n.defaultUid]
                          ]),
                          t[16] || (t[16] = l()),
                          o("label", {
                            for: "default-radio-" + e.uid,
                            class: "form-check-label"
                          }, u(i.$lang("shopgo.product.attribute.text.default")), 9, rt)
                        ]),
                        t[19] || (t[19] = l()),
                        o("button", {
                          type: "button",
                          class: "btn btn-sm btn-outline-dark",
                          onClick: c((d) => n.addNewItem(e), ["stop"])
                        }, [...t[17] || (t[17] = [
                          o("span", { class: "fa fa-plus" }, null, -1)
                        ])], 8, pt),
                        t[20] || (t[20] = l()),
                        o("button", {
                          type: "button",
                          class: "btn btn-sm btn-outline-danger",
                          onClick: c((d) => n.removeItem(e), ["stop"])
                        }, [...t[18] || (t[18] = [
                          o("span", { class: "fa fa-trash" }, null, -1)
                        ])], 8, vt)
                      ])
                    ]),
                    t[26] || (t[26] = l()),
                    o("div", ft, [
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][text]`,
                        value: e.data.text
                      }, null, 8, mt),
                      t[23] || (t[23] = l()),
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][value]`,
                        value: e.data.value
                      }, null, 8, ct),
                      t[24] || (t[24] = l()),
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][color]`,
                        value: e.data.color
                      }, null, 8, bt),
                      t[25] || (t[25] = l()),
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][is_default]`,
                        checked: e.uid === n.defaultUid,
                        value: e.uid === n.defaultUid ? 1 : ""
                      }, null, 8, gt)
                    ])
                  ], 12, ot))), 128))
                ]),
                _: 1
              })
            ]),
            _: 1
          }, 8, ["modelValue"])
        ])
      ])
    ]),
    t[32] || (t[32] = l()),
    o("div", yt, [
      o("div", _t, [
        o("div", kt, u(i.$lang("shopgo.product.attribute.option.data.title")), 1),
        t[31] || (t[31] = l()),
        o("div", xt, [
          n.current ? (p(), r("div", Ct, [
            o("div", Vt, [
              o("label", It, u(i.$lang("shopgo.product.attribute.option.text")), 1),
              t[28] || (t[28] = l()),
              m(o("input", {
                id: "input-option-text",
                type: "text",
                class: "form-control",
                "onUpdate:modelValue": t[7] || (t[7] = (e) => n.current.data.text = e)
              }, null, 512), [
                [A, n.current.data.text]
              ])
            ]),
            t[30] || (t[30] = l()),
            n.type === "select" ? (p(), r("div", At, [
              o("label", ht, u(i.$lang("shopgo.product.attribute.option.value")), 1),
              t[29] || (t[29] = l()),
              m(o("input", {
                id: "input-option-value",
                type: "text",
                class: "form-control",
                "onUpdate:modelValue": t[8] || (t[8] = (e) => n.current.data.value = e)
              }, null, 512), [
                [A, n.current.data.value]
              ])
            ])) : y("", !0)
          ])) : (p(), r("div", wt, [
            o("div", Ut, [
              o("div", Et, u(i.$lang("shopgo.product.attribute.option.no.select")), 1)
            ])
          ]))
        ])
      ])
    ])
  ])) : y("", !0);
}
const St = /* @__PURE__ */ S(H, [["render", Nt], ["__file", "ProductAttributeEditApp.vue"]]), Pt = P("ProductAttributeEditApp", St);
function Bt(i) {
  const t = G(Pt, i);
  return t.use(R), t;
}
export {
  Bt as initApp
};
//# sourceMappingURL=product-attribute-edit.js.map
