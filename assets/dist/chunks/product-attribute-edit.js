import { defineComponent as E, ref as v, createElementBlock as r, createCommentVNode as y, openBlock as p, createElementVNode as o, createTextVNode as l, toDisplayString as u, createVNode as V, withCtx as I, TransitionGroup as D, Fragment as P, renderList as M, normalizeStyle as O, withDirectives as m, withModifiers as c, vModelCheckbox as q, vModelRadio as L, vModelText as h, createApp as T } from "vue";
import { uniqueItemList as B, uniqueItem as G } from "@lyrasoft/ts-toolkit/vue";
import { useCssImport as J } from "@windwalker-io/unicorn-next";
import { VueDraggable as j } from "vue-draggable-plus";
import { _ as z } from "./_plugin-vue_export-helper.js";
import { ShopGoPlugin as F } from "../index.js";
const R = /* @__PURE__ */ E({
  __name: "ProductAttributeEditApp",
  props: {
    options: {}
  },
  setup(i, { expose: t }) {
    t(), J("@vue-animate");
    const b = i, n = document.querySelector("#input-item-type"), a = v(
      B(b.options || []).map((s) => ({
        data: s,
        uid: s.uid,
        selected: !1
      }))
    ), g = v(n.value), e = v(null), d = v([]), w = v(a.value.find((s) => s.data.is_default)?.uid);
    n.addEventListener("change", () => {
      g.value = n.value;
    });
    function _(s) {
      e.value = s;
    }
    function U(s = null) {
      const f = s ? a.value.indexOf(s) + 1 : a.value.length, x = G({
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
    function A(s) {
      const f = a.value.indexOf(s);
      f !== -1 && a.value.splice(f, 1), e.value === s && (e.value = null);
    }
    function N() {
      a.value = a.value.filter((s) => !d.value.includes(s.uid)), d.value.includes(e.value?.uid || "") && (e.value = null), d.value = [];
    }
    function S(s) {
      return JSON.stringify(s);
    }
    const k = { props: b, $typeSelect: n, items: a, type: g, current: e, selected: d, defaultUid: w, selectItem: _, addNewItem: U, removeItem: A, removeItems: N, toJson: S, get VueDraggable() {
      return j;
    } };
    return Object.defineProperty(k, "__isScriptSetup", { enumerable: !1, value: !0 }), k;
  }
}), H = {
  key: 0,
  class: "row"
}, K = { class: "col-lg-6" }, Q = { class: "card c-feature-option-list" }, W = { class: "card-header d-flex align-items-center" }, X = { class: "m-0" }, Y = { class: "c-list-top-toolbar ms-auto" }, Z = ["disabled"], $ = { class: "c-option-list list-group list-group-flush" }, tt = ["onClick"], ot = { class: "d-flex align-items-center gap-2" }, et = { class: "c-option-item__control" }, nt = ["value"], lt = { class: "c-option-control__title flex-grow-1" }, st = { class: "h5 m-0" }, it = {
  key: 0,
  style: { opacity: ".5" }
}, dt = { class: "c-option-control__actions d-flex align-items-center gap-1" }, at = ["value", "id"], ut = ["for"], rt = ["onClick"], pt = ["onClick"], vt = { class: "d-none" }, ft = ["name", "value"], mt = ["name", "value"], ct = ["name", "value"], bt = ["name", "checked", "value"], gt = { class: "col-lg-6 l-feature-option-item" }, yt = { class: "card c-option-edit" }, _t = { class: "card-header" }, kt = { class: "card-body" }, xt = {
  key: 0,
  class: "c-option-edit__form"
}, Ct = { class: "form-group mb-4" }, Vt = {
  for: "input-option-text",
  class: "form-label"
}, It = {
  key: 0,
  class: "form-group mb-4"
}, ht = {
  for: "input-option-value",
  class: "form-label"
}, wt = { key: 1 }, Ut = { class: "card bg-light" }, At = { class: "card-body text-center" };
function Nt(i, t, b, n, a, g) {
  return n.type !== "bool" ? (p(), r("div", H, [
    o("div", K, [
      o("div", Q, [
        o("div", W, [
          o("h3", X, u(i.$lang("shopgo.product.attribute.options.title")), 1),
          t[12] || (t[12] = l()),
          o("div", Y, [
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
            ], 8, Z)
          ])
        ]),
        t[27] || (t[27] = l()),
        o("div", $, [
          V(n.VueDraggable, {
            modelValue: n.items,
            "onUpdate:modelValue": t[6] || (t[6] = (e) => n.items = e),
            handle: ".handle",
            "item-key": "uid",
            animation: 150
          }, {
            default: I(() => [
              V(D, { name: "fade" }, {
                default: I(() => [
                  (p(!0), r(P, null, M(n.items, (e) => (p(), r("div", {
                    key: e.uid,
                    class: "list-group-item c-option-item",
                    style: O([[n.current === e ? "background: rgba(var(--bs-primary-rgb), .3)" : "", "animation-duration: .3s;"], { cursor: "pointer" }]),
                    onClick: (d) => n.selectItem(e)
                  }, [
                    o("div", ot, [
                      o("div", et, [
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
                        }, null, 8, nt), [
                          [q, n.selected]
                        ])
                      ]),
                      t[21] || (t[21] = l()),
                      o("div", lt, [
                        o("span", st, u(e.data.text || i.$lang("shopgo.product.attribute.text.unnameed")), 1),
                        t[15] || (t[15] = l()),
                        n.type === "select" ? (p(), r("span", it, `
                        (` + u(e.data.value) + `)
                    `, 1)) : y("", !0)
                      ]),
                      t[22] || (t[22] = l()),
                      o("div", dt, [
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
                          }, null, 8, at), [
                            [L, n.defaultUid]
                          ]),
                          t[16] || (t[16] = l()),
                          o("label", {
                            for: "default-radio-" + e.uid,
                            class: "form-check-label"
                          }, u(i.$lang("shopgo.product.attribute.text.default")), 9, ut)
                        ]),
                        t[19] || (t[19] = l()),
                        o("button", {
                          type: "button",
                          class: "btn btn-sm btn-outline-dark",
                          onClick: c((d) => n.addNewItem(e), ["stop"])
                        }, [...t[17] || (t[17] = [
                          o("span", { class: "fa fa-plus" }, null, -1)
                        ])], 8, rt),
                        t[20] || (t[20] = l()),
                        o("button", {
                          type: "button",
                          class: "btn btn-sm btn-outline-danger",
                          onClick: c((d) => n.removeItem(e), ["stop"])
                        }, [...t[18] || (t[18] = [
                          o("span", { class: "fa fa-trash" }, null, -1)
                        ])], 8, pt)
                      ])
                    ]),
                    t[26] || (t[26] = l()),
                    o("div", vt, [
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][text]`,
                        value: e.data.text
                      }, null, 8, ft),
                      t[23] || (t[23] = l()),
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][value]`,
                        value: e.data.value
                      }, null, 8, mt),
                      t[24] || (t[24] = l()),
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][color]`,
                        value: e.data.color
                      }, null, 8, ct),
                      t[25] || (t[25] = l()),
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][is_default]`,
                        checked: e.uid === n.defaultUid,
                        value: e.uid === n.defaultUid ? 1 : ""
                      }, null, 8, bt)
                    ])
                  ], 12, tt))), 128))
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
    o("div", gt, [
      o("div", yt, [
        o("div", _t, u(i.$lang("shopgo.product.attribute.option.data.title")), 1),
        t[31] || (t[31] = l()),
        o("div", kt, [
          n.current ? (p(), r("div", xt, [
            o("div", Ct, [
              o("label", Vt, u(i.$lang("shopgo.product.attribute.option.text")), 1),
              t[28] || (t[28] = l()),
              m(o("input", {
                id: "input-option-text",
                type: "text",
                class: "form-control",
                "onUpdate:modelValue": t[7] || (t[7] = (e) => n.current.data.text = e)
              }, null, 512), [
                [h, n.current.data.text]
              ])
            ]),
            t[30] || (t[30] = l()),
            n.type === "select" ? (p(), r("div", It, [
              o("label", ht, u(i.$lang("shopgo.product.attribute.option.value")), 1),
              t[29] || (t[29] = l()),
              m(o("input", {
                id: "input-option-value",
                type: "text",
                class: "form-control",
                "onUpdate:modelValue": t[8] || (t[8] = (e) => n.current.data.value = e)
              }, null, 512), [
                [h, n.current.data.value]
              ])
            ])) : y("", !0)
          ])) : (p(), r("div", wt, [
            o("div", Ut, [
              o("div", At, u(i.$lang("shopgo.product.attribute.option.no.select")), 1)
            ])
          ]))
        ])
      ])
    ])
  ])) : y("", !0);
}
const St = /* @__PURE__ */ z(R, [["render", Nt], ["__file", "ProductAttributeEditApp.vue"]]);
function Lt(i) {
  const t = T(St, i);
  return t.use(F), t;
}
export {
  Lt as initApp
};
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoicHJvZHVjdC1hdHRyaWJ1dGUtZWRpdC5qcyIsInNvdXJjZXMiOlsiLi4vLi4vc3JjL21vZHVsZXMvcHJvZHVjdC1hdHRyaWJ1dGUvUHJvZHVjdEF0dHJpYnV0ZUVkaXRBcHAudnVlIiwiLi4vLi4vc3JjL21vZHVsZXMvcHJvZHVjdC1hdHRyaWJ1dGUvcHJvZHVjdC1hdHRyaWJ1dGUtZWRpdC50cyJdLCJzb3VyY2VzQ29udGVudCI6WyI8c2NyaXB0IHNldHVwIGxhbmc9XCJ0c1wiPlxuaW1wb3J0IHsgdW5pcXVlSXRlbSwgdW5pcXVlSXRlbUxpc3QgfSBmcm9tICdAbHlyYXNvZnQvdHMtdG9vbGtpdC92dWUnO1xuaW1wb3J0IHsgZGF0YSwgdXNlQ3NzSW1wb3J0IH0gZnJvbSAnQHdpbmR3YWxrZXItaW8vdW5pY29ybi1uZXh0JztcbmltcG9ydCB7IHJlZiB9IGZyb20gJ3Z1ZSc7XG5pbXBvcnQgeyBWdWVEcmFnZ2FibGUgfSBmcm9tICd2dWUtZHJhZ2dhYmxlLXBsdXMnO1xuaW1wb3J0IHsgTGlzdE9wdGlvbiB9IGZyb20gJ35zaG9wZ28vdHlwZXMnO1xuXG51c2VDc3NJbXBvcnQoJ0B2dWUtYW5pbWF0ZScpO1xuXG50eXBlIEF0dHJpYnV0ZU9wdGlvbkl0ZW0gPSB7XG4gIGRhdGE6IExpc3RPcHRpb247XG4gIHVpZDogc3RyaW5nO1xuICBzZWxlY3RlZDogYm9vbGVhbjtcbn07XG5cbmNvbnN0IHByb3BzID0gZGVmaW5lUHJvcHM8e1xuICBvcHRpb25zOiBMaXN0T3B0aW9uW107XG59PigpO1xuXG5jb25zdCAkdHlwZVNlbGVjdCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3I8SFRNTFNlbGVjdEVsZW1lbnQ+KCcjaW5wdXQtaXRlbS10eXBlJykhO1xuXG5jb25zdCBpdGVtcyA9IHJlZjxBdHRyaWJ1dGVPcHRpb25JdGVtW10+KFxuICB1bmlxdWVJdGVtTGlzdChwcm9wcy5vcHRpb25zIHx8IFtdKS5tYXAoKGl0ZW0pID0+IHtcbiAgICByZXR1cm4ge1xuICAgICAgZGF0YTogaXRlbSxcbiAgICAgIHVpZDogaXRlbS51aWQsXG4gICAgICBzZWxlY3RlZDogZmFsc2VcbiAgICB9O1xuICB9KVxuKTtcbmNvbnN0IHR5cGUgPSByZWYoJHR5cGVTZWxlY3QudmFsdWUpO1xuY29uc3QgY3VycmVudCA9IHJlZjxBdHRyaWJ1dGVPcHRpb25JdGVtIHwgbnVsbD4obnVsbCk7XG5jb25zdCBzZWxlY3RlZCA9IHJlZjxzdHJpbmdbXT4oW10pO1xuY29uc3QgZGVmYXVsdFVpZCA9IHJlZjxzdHJpbmcgfCB1bmRlZmluZWQ+KGl0ZW1zLnZhbHVlLmZpbmQoKGl0ZW0pID0+IGl0ZW0uZGF0YS5pc19kZWZhdWx0KT8udWlkKTtcblxuJHR5cGVTZWxlY3QuYWRkRXZlbnRMaXN0ZW5lcignY2hhbmdlJywgKCkgPT4ge1xuICB0eXBlLnZhbHVlID0gJHR5cGVTZWxlY3QudmFsdWU7XG59KTtcblxuZnVuY3Rpb24gc2VsZWN0SXRlbShpdGVtOiBBdHRyaWJ1dGVPcHRpb25JdGVtKSB7XG4gIGN1cnJlbnQudmFsdWUgPSBpdGVtO1xufVxuXG5mdW5jdGlvbiBhZGROZXdJdGVtKGl0ZW06IEF0dHJpYnV0ZU9wdGlvbkl0ZW0gfCBudWxsID0gbnVsbCkge1xuICBjb25zdCBpID0gaXRlbSA/IGl0ZW1zLnZhbHVlLmluZGV4T2YoaXRlbSkgKyAxIDogaXRlbXMudmFsdWUubGVuZ3RoO1xuXG4gIGNvbnN0IGRhdGEgPSB1bmlxdWVJdGVtKHtcbiAgICB2YWx1ZTogJycsXG4gICAgdGV4dDogJycsXG4gICAgaXNfZGVmYXVsdDogZmFsc2VcbiAgfSk7XG4gIGNvbnN0IG5ld0l0ZW0gPSB7XG4gICAgZGF0YSxcbiAgICB1aWQ6IGRhdGEudWlkLFxuICAgIHNlbGVjdGVkOiBmYWxzZVxuICB9O1xuICBpdGVtcy52YWx1ZS5zcGxpY2UoaSwgMCwgbmV3SXRlbSk7XG5cbiAgc2VsZWN0SXRlbShuZXdJdGVtKTtcbn1cblxuZnVuY3Rpb24gcmVtb3ZlSXRlbShpdGVtOiBBdHRyaWJ1dGVPcHRpb25JdGVtKSB7XG4gIGNvbnN0IGkgPSBpdGVtcy52YWx1ZS5pbmRleE9mKGl0ZW0pO1xuXG4gIGlmIChpICE9PSAtMSkge1xuICAgIGl0ZW1zLnZhbHVlLnNwbGljZShpLCAxKTtcbiAgfVxuXG4gIGlmIChjdXJyZW50LnZhbHVlID09PSBpdGVtKSB7XG4gICAgY3VycmVudC52YWx1ZSA9IG51bGw7XG4gIH1cbn1cblxuZnVuY3Rpb24gcmVtb3ZlSXRlbXMoKSB7XG4gIGl0ZW1zLnZhbHVlID0gaXRlbXMudmFsdWUuZmlsdGVyKChpdCkgPT4gIXNlbGVjdGVkLnZhbHVlLmluY2x1ZGVzKGl0LnVpZCkpO1xuXG4gIGlmIChzZWxlY3RlZC52YWx1ZS5pbmNsdWRlcyhjdXJyZW50LnZhbHVlPy51aWQgfHwgJycpKSB7XG4gICAgY3VycmVudC52YWx1ZSA9IG51bGw7XG4gIH1cblxuICBzZWxlY3RlZC52YWx1ZSA9IFtdO1xufVxuXG5mdW5jdGlvbiB0b0pzb24oZGF0YTogYW55KSB7XG4gIHJldHVybiBKU09OLnN0cmluZ2lmeShkYXRhKTtcbn1cbjwvc2NyaXB0PlxuXG48dGVtcGxhdGU+XG4gIDxkaXYgdi1pZj1cInR5cGUgIT09ICdib29sJ1wiIGNsYXNzPVwicm93XCI+XG4gICAgPGRpdiBjbGFzcz1cImNvbC1sZy02XCI+XG4gICAgICA8ZGl2IGNsYXNzPVwiY2FyZCBjLWZlYXR1cmUtb3B0aW9uLWxpc3RcIj5cbiAgICAgICAgPGRpdiBjbGFzcz1cImNhcmQtaGVhZGVyIGQtZmxleCBhbGlnbi1pdGVtcy1jZW50ZXJcIj5cbiAgICAgICAgICA8aDMgY2xhc3M9XCJtLTBcIj5cbiAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5hdHRyaWJ1dGUub3B0aW9ucy50aXRsZScpIH19XG4gICAgICAgICAgPC9oMz5cbiAgICAgICAgICA8ZGl2IGNsYXNzPVwiYy1saXN0LXRvcC10b29sYmFyIG1zLWF1dG9cIj5cbiAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1zbSBidG4tcHJpbWFyeVwiXG4gICAgICAgICAgICAgIEBjbGljaz1cImFkZE5ld0l0ZW0oKVwiPlxuICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLXBsdXNcIj48L3NwYW4+XG4gICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5hdHRyaWJ1dGUuYnV0dG9uLm5ldycpIH19XG4gICAgICAgICAgICA8L2J1dHRvbj5cbiAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1zbSBidG4tb3V0bGluZS1kYW5nZXJcIlxuICAgICAgICAgICAgICBAY2xpY2s9XCJyZW1vdmVJdGVtcygpXCIgOmRpc2FibGVkPVwic2VsZWN0ZWQubGVuZ3RoID09PSAwXCI+XG4gICAgICAgICAgICAgIDxzcGFuIGNsYXNzPVwiZmEgZmEtdHJhc2hcIj48L3NwYW4+XG4gICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5hdHRyaWJ1dGUuYnV0dG9uLmRlbGV0ZScpIH19XG4gICAgICAgICAgICA8L2J1dHRvbj5cbiAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgPGRpdiBjbGFzcz1cImMtb3B0aW9uLWxpc3QgbGlzdC1ncm91cCBsaXN0LWdyb3VwLWZsdXNoXCI+XG4gICAgICAgICAgPFZ1ZURyYWdnYWJsZSB2LW1vZGVsPVwiaXRlbXNcIiBoYW5kbGU9XCIuaGFuZGxlXCIgaXRlbS1rZXk9XCJ1aWRcIlxuICAgICAgICAgICAgOmFuaW1hdGlvbj1cIjE1MFwiXG4gICAgICAgICAgPlxuICAgICAgICAgICAgPFRyYW5zaXRpb25Hcm91cCBuYW1lPVwiZmFkZVwiPlxuICAgICAgICAgICAgICA8dGVtcGxhdGUgdi1mb3I9XCJpdGVtIG9mIGl0ZW1zXCIgOmtleT1cIml0ZW0udWlkXCI+XG4gICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImxpc3QtZ3JvdXAtaXRlbSBjLW9wdGlvbi1pdGVtXCJcbiAgICAgICAgICAgICAgICAgIDpzdHlsZT1cIlsgY3VycmVudCA9PT0gaXRlbSA/ICdiYWNrZ3JvdW5kOiByZ2JhKHZhcigtLWJzLXByaW1hcnktcmdiKSwgLjMpJyA6ICcnLCAnYW5pbWF0aW9uLWR1cmF0aW9uOiAuM3M7JyBdXCJcbiAgICAgICAgICAgICAgICAgIEBjbGljaz1cInNlbGVjdEl0ZW0oaXRlbSlcIlxuICAgICAgICAgICAgICAgICAgc3R5bGU9XCJjdXJzb3I6IHBvaW50ZXI7XCJcbiAgICAgICAgICAgICAgICA+XG4gICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZC1mbGV4IGFsaWduLWl0ZW1zLWNlbnRlciBnYXAtMlwiPlxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiYy1vcHRpb24taXRlbV9fY29udHJvbFwiPlxuICAgICAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLWZ3IGZhLWVsbGlwc2lzLXYgaGFuZGxlXCJcbiAgICAgICAgICAgICAgICAgICAgICBzdHlsZT1cImN1cnNvcjogbW92ZVwiXG4gICAgICAgICAgICAgICAgICAgID48L3NwYW4+XG4gICAgICAgICAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJjaGVja2JveFwiIG5hbWU9XCJzZWxlY3RlZFtdXCIgdi1tb2RlbD1cInNlbGVjdGVkXCJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNsYXNzPVwiZm9ybS1jaGVjay1pbnB1dFwiXG4gICAgICAgICAgICAgICAgICAgICAgICA6dmFsdWU9XCJpdGVtLnVpZFwiXG4gICAgICAgICAgICAgICAgICAgICAgICBAY2xpY2suc3RvcD1cIlwiIC8+XG4gICAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiYy1vcHRpb24tY29udHJvbF9fdGl0bGUgZmxleC1ncm93LTFcIj5cbiAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJoNSBtLTBcIj5cbiAgICAgICAgICAgICAgICAgICAgICAgIHt7IGl0ZW0uZGF0YS50ZXh0IHx8ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5hdHRyaWJ1dGUudGV4dC51bm5hbWVlZCcpIH19XG4gICAgICAgICAgICAgICAgICAgIDwvc3Bhbj5cbiAgICAgICAgICAgICAgICAgICAgICA8c3BhbiB2LWlmPVwidHlwZSA9PT0gJ3NlbGVjdCdcIiBzdHlsZT1cIm9wYWNpdHk6IC41XCI+XG4gICAgICAgICAgICAgICAgICAgICAgICAoe3sgaXRlbS5kYXRhLnZhbHVlIH19KVxuICAgICAgICAgICAgICAgICAgICA8L3NwYW4+XG4gICAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgICAgICA8ZGl2XG4gICAgICAgICAgICAgICAgICAgICAgY2xhc3M9XCJjLW9wdGlvbi1jb250cm9sX19hY3Rpb25zIGQtZmxleCBhbGlnbi1pdGVtcy1jZW50ZXIgZ2FwLTFcIj5cbiAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1jaGVjayBtYi0wIG1lLTJcIiBAY2xpY2suc3RvcD1cIlwiPlxuICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJyYWRpb1wiXG4gICAgICAgICAgICAgICAgICAgICAgICAgIDp2YWx1ZT1cIml0ZW0udWlkXCJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgY2xhc3M9XCJmb3JtLWNoZWNrLWlucHV0XCJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgOmlkPVwiJ2RlZmF1bHQtcmFkaW8tJyArIGl0ZW0udWlkXCJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgdi1tb2RlbD1cImRlZmF1bHRVaWRcIlxuICAgICAgICAgICAgICAgICAgICAgICAgLz5cbiAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbCA6Zm9yPVwiJ2RlZmF1bHQtcmFkaW8tJyArIGl0ZW0udWlkXCJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgY2xhc3M9XCJmb3JtLWNoZWNrLWxhYmVsXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5hdHRyaWJ1dGUudGV4dC5kZWZhdWx0JykgfX1cbiAgICAgICAgICAgICAgICAgICAgICAgIDwvbGFiZWw+XG4gICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICAgICAgICAgICAgPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNsYXNzPVwiYnRuIGJ0bi1zbSBidG4tb3V0bGluZS1kYXJrXCJcbiAgICAgICAgICAgICAgICAgICAgICAgIEBjbGljay5zdG9wPVwiYWRkTmV3SXRlbShpdGVtKVwiPlxuICAgICAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJmYSBmYS1wbHVzXCI+PC9zcGFuPlxuICAgICAgICAgICAgICAgICAgICAgIDwvYnV0dG9uPlxuICAgICAgICAgICAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1zbSBidG4tb3V0bGluZS1kYW5nZXJcIlxuICAgICAgICAgICAgICAgICAgICAgICAgQGNsaWNrLnN0b3A9XCJyZW1vdmVJdGVtKGl0ZW0pXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLXRyYXNoXCI+PC9zcGFuPlxuICAgICAgICAgICAgICAgICAgICAgIDwvYnV0dG9uPlxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImQtbm9uZVwiPlxuICAgICAgICAgICAgICAgICAgICA8aW5wdXQgdHlwZT1cImhpZGRlblwiIDpuYW1lPVwiYG9wdGlvbnNbJHtpdGVtLnVpZH1dW3RleHRdYFwiXG4gICAgICAgICAgICAgICAgICAgICAgOnZhbHVlPVwiaXRlbS5kYXRhLnRleHRcIiAvPlxuICAgICAgICAgICAgICAgICAgICA8aW5wdXQgdHlwZT1cImhpZGRlblwiIDpuYW1lPVwiYG9wdGlvbnNbJHtpdGVtLnVpZH1dW3ZhbHVlXWBcIlxuICAgICAgICAgICAgICAgICAgICAgIDp2YWx1ZT1cIml0ZW0uZGF0YS52YWx1ZVwiIC8+XG4gICAgICAgICAgICAgICAgICAgIDxpbnB1dCB0eXBlPVwiaGlkZGVuXCIgOm5hbWU9XCJgb3B0aW9uc1ske2l0ZW0udWlkfV1bY29sb3JdYFwiXG4gICAgICAgICAgICAgICAgICAgICAgOnZhbHVlPVwiaXRlbS5kYXRhLmNvbG9yXCIgLz5cbiAgICAgICAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJoaWRkZW5cIiA6bmFtZT1cImBvcHRpb25zWyR7aXRlbS51aWR9XVtpc19kZWZhdWx0XWBcIlxuICAgICAgICAgICAgICAgICAgICAgIDpjaGVja2VkPVwiaXRlbS51aWQgPT09IGRlZmF1bHRVaWRcIlxuICAgICAgICAgICAgICAgICAgICAgIDp2YWx1ZT1cIml0ZW0udWlkID09PSBkZWZhdWx0VWlkID8gMSA6ICcnXCIgLz5cbiAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICA8L3RlbXBsYXRlPlxuICAgICAgICAgICAgPC9UcmFuc2l0aW9uR3JvdXA+XG4gICAgICAgICAgPC9WdWVEcmFnZ2FibGU+XG4gICAgICAgIDwvZGl2PlxuICAgICAgPC9kaXY+XG4gICAgPC9kaXY+XG4gICAgPGRpdiBjbGFzcz1cImNvbC1sZy02IGwtZmVhdHVyZS1vcHRpb24taXRlbVwiPlxuICAgICAgPGRpdiBjbGFzcz1cImNhcmQgYy1vcHRpb24tZWRpdFwiPlxuICAgICAgICA8ZGl2IGNsYXNzPVwiY2FyZC1oZWFkZXJcIj5cbiAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLnByb2R1Y3QuYXR0cmlidXRlLm9wdGlvbi5kYXRhLnRpdGxlJykgfX1cbiAgICAgICAgPC9kaXY+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkLWJvZHlcIj5cbiAgICAgICAgICA8ZGl2IHYtaWY9XCJjdXJyZW50XCIgY2xhc3M9XCJjLW9wdGlvbi1lZGl0X19mb3JtXCI+XG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBtYi00XCI+XG4gICAgICAgICAgICAgIDxsYWJlbCBmb3I9XCJpbnB1dC1vcHRpb24tdGV4dFwiIGNsYXNzPVwiZm9ybS1sYWJlbFwiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5hdHRyaWJ1dGUub3B0aW9uLnRleHQnKSB9fVxuICAgICAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgICAgICA8aW5wdXQgaWQ9XCJpbnB1dC1vcHRpb24tdGV4dFwiIHR5cGU9XCJ0ZXh0XCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIlxuICAgICAgICAgICAgICAgIHYtbW9kZWw9XCJjdXJyZW50LmRhdGEudGV4dFwiIC8+XG4gICAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgICAgPGRpdiB2LWlmPVwidHlwZSA9PT0gJ3NlbGVjdCdcIiBjbGFzcz1cImZvcm0tZ3JvdXAgbWItNFwiPlxuICAgICAgICAgICAgICA8bGFiZWwgZm9yPVwiaW5wdXQtb3B0aW9uLXZhbHVlXCIgY2xhc3M9XCJmb3JtLWxhYmVsXCI+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LmF0dHJpYnV0ZS5vcHRpb24udmFsdWUnKSB9fVxuICAgICAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgICAgICA8aW5wdXQgaWQ9XCJpbnB1dC1vcHRpb24tdmFsdWVcIiB0eXBlPVwidGV4dFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCJcbiAgICAgICAgICAgICAgICB2LW1vZGVsPVwiY3VycmVudC5kYXRhLnZhbHVlXCIgLz5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDxkaXYgdi1lbHNlPlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImNhcmQgYmctbGlnaHRcIj5cbiAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImNhcmQtYm9keSB0ZXh0LWNlbnRlclwiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5hdHRyaWJ1dGUub3B0aW9uLm5vLnNlbGVjdCcpIH19XG4gICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgIDwvZGl2PlxuICAgICAgPC9kaXY+XG4gICAgPC9kaXY+XG4gIDwvZGl2PlxuPC90ZW1wbGF0ZT5cblxuPHN0eWxlIHNjb3BlZD5cblxuPC9zdHlsZT5cbiIsImltcG9ydCB7IGNyZWF0ZUFwcCB9IGZyb20gJ3Z1ZSc7XG5pbXBvcnQgUHJvZHVjdEF0dHJpYnV0ZUVkaXRBcHAgZnJvbSAnfnNob3Bnby9tb2R1bGVzL3Byb2R1Y3QtYXR0cmlidXRlL1Byb2R1Y3RBdHRyaWJ1dGVFZGl0QXBwLnZ1ZSc7XG5pbXBvcnQgeyBTaG9wR29QbHVnaW4gfSBmcm9tICd+c2hvcGdvL3Nob3Bnby1wbHVnaW4nO1xuXG5leHBvcnQgZnVuY3Rpb24gaW5pdEFwcChwcm9wczogUmVjb3JkPHN0cmluZywgYW55Pikge1xuICBjb25zdCBhcHAgPSBjcmVhdGVBcHAoUHJvZHVjdEF0dHJpYnV0ZUVkaXRBcHAsIHByb3BzKTtcblxuICBhcHAudXNlKFNob3BHb1BsdWdpbik7XG5cbiAgcmV0dXJuIGFwcDtcbn1cblxuXG4iXSwibmFtZXMiOlsidXNlQ3NzSW1wb3J0IiwicHJvcHMiLCJfX3Byb3BzIiwiJHR5cGVTZWxlY3QiLCJpdGVtcyIsInJlZiIsInVuaXF1ZUl0ZW1MaXN0IiwiaXRlbSIsInR5cGUiLCJjdXJyZW50Iiwic2VsZWN0ZWQiLCJkZWZhdWx0VWlkIiwic2VsZWN0SXRlbSIsImFkZE5ld0l0ZW0iLCJpIiwiZGF0YSIsInVuaXF1ZUl0ZW0iLCJuZXdJdGVtIiwicmVtb3ZlSXRlbSIsInJlbW92ZUl0ZW1zIiwiaXQiLCJ0b0pzb24iLCJfaG9pc3RlZF8yIiwiX2hvaXN0ZWRfMyIsIl9ob2lzdGVkXzQiLCJfaG9pc3RlZF81IiwiX2hvaXN0ZWRfNiIsIl9ob2lzdGVkXzgiLCJfaG9pc3RlZF8xMCIsIl9ob2lzdGVkXzExIiwiX2hvaXN0ZWRfMTMiLCJfaG9pc3RlZF8xNCIsIl9ob2lzdGVkXzE2IiwiX2hvaXN0ZWRfMjEiLCJfaG9pc3RlZF8yNiIsIl9ob2lzdGVkXzI3IiwiX2hvaXN0ZWRfMjgiLCJfaG9pc3RlZF8yOSIsIl9ob2lzdGVkXzMxIiwiX2hvaXN0ZWRfMzYiLCJfaG9pc3RlZF8zNyIsIiRzZXR1cCIsIl9vcGVuQmxvY2siLCJfY3JlYXRlRWxlbWVudEJsb2NrIiwiX2hvaXN0ZWRfMSIsIl9jcmVhdGVFbGVtZW50Vk5vZGUiLCJfdG9EaXNwbGF5U3RyaW5nIiwiX2N0eCIsIl9jYWNoZSIsIl9jcmVhdGVUZXh0Vk5vZGUiLCJfaG9pc3RlZF83IiwiX2NyZWF0ZVZOb2RlIiwiJGV2ZW50IiwiX1RyYW5zaXRpb25Hcm91cCIsIl93aXRoQ3R4IiwiX0ZyYWdtZW50IiwiX3JlbmRlckxpc3QiLCJfbm9ybWFsaXplU3R5bGUiLCJfd2l0aE1vZGlmaWVycyIsIl9ob2lzdGVkXzEyIiwiX2hvaXN0ZWRfMTUiLCJfY3JlYXRlQ29tbWVudFZOb2RlIiwiX2hvaXN0ZWRfMTciLCJfaG9pc3RlZF8xOCIsIl9ob2lzdGVkXzE5IiwiX2hvaXN0ZWRfMjAiLCJfaG9pc3RlZF8yMiIsIl9ob2lzdGVkXzIzIiwiX2hvaXN0ZWRfMjQiLCJfaG9pc3RlZF8yNSIsIl9ob2lzdGVkXzMwIiwiX2hvaXN0ZWRfMzIiLCJfdk1vZGVsVGV4dCIsIl9ob2lzdGVkXzMzIiwiX2hvaXN0ZWRfMzQiLCJfaG9pc3RlZF8zNSIsImluaXRBcHAiLCJhcHAiLCJjcmVhdGVBcHAiLCJQcm9kdWN0QXR0cmlidXRlRWRpdEFwcCIsIlNob3BHb1BsdWdpbiJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7O1NBT0FBLEVBQWEsY0FBYztBQVEzQixVQUFNQyxJQUFRQyxHQUlSQyxJ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
