import { defineComponent as O, ref as v, createElementBlock as u, openBlock as p, createElementVNode as o, createTextVNode as l, toDisplayString as a, createVNode as V, withCtx as I, TransitionGroup as A, Fragment as D, renderList as U, normalizeClass as F, createCommentVNode as w, withDirectives as m, withModifiers as b, vModelCheckbox as q, normalizeStyle as z, vModelText as y, createApp as L } from "vue";
import { uniqueItemList as M, uniqueItem as T } from "@lyrasoft/ts-toolkit/vue";
import { useCssImport as B } from "@windwalker-io/unicorn-next";
import { VueDraggable as G } from "vue-draggable-plus";
import "bootstrap";
import { vColorpicker as J, ShopGoPlugin as j } from "../index.js";
import { _ as H } from "./_plugin-vue_export-helper.js";
const K = /* @__PURE__ */ O({
  __name: "ProductFeatureEditApp",
  props: {
    options: {}
  },
  setup(s, { expose: t }) {
    t(), B("@vue-animate");
    const c = document.querySelector("#input-item-type"), n = s, f = v(c.value), r = v(
      M(n.options || []).map((i) => ({
        data: i,
        uid: i.uid,
        selected: !1
      }))
    ), e = v(null), d = v([]), h = v({});
    c.addEventListener("change", () => {
      f.value = c.value;
    });
    function _(i) {
      e.value = i;
    }
    function N(i) {
      const g = i ? r.value.indexOf(i) + 1 : r.value.length, x = T({
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
    function S(i) {
      const g = r.value.indexOf(i);
      r.value.splice(g, 1);
    }
    function P() {
      r.value = r.value.filter((i) => !d.value.includes(i.uid)), d.value.includes(e.value?.uid || "") && (e.value = null), d.value = [];
    }
    function E(i) {
      return JSON.stringify(i);
    }
    const k = { $typeSelect: c, props: n, type: f, items: r, current: e, selected: d, colorPickerOptions: h, selectItem: _, addNewItem: N, removeItem: S, removeItems: P, toJson: E, get VueDraggable() {
      return G;
    }, get vColorpicker() {
      return J;
    } };
    return Object.defineProperty(k, "__isScriptSetup", { enumerable: !1, value: !0 }), k;
  }
}), Q = { class: "row" }, R = { class: "col-lg-6" }, W = { class: "card c-feature-option-list" }, X = { class: "card-header d-flex align-items-center" }, Y = { class: "m-0" }, Z = { class: "c-list-top-toolbar ms-auto" }, $ = ["disabled"], tt = { class: "c-option-list list-group list-group-flush" }, ot = ["onClick"], et = { class: "d-flex align-items-center gap-2" }, nt = { class: "c-option-item__control" }, lt = ["value"], st = {
  key: 0,
  class: "c-option-item__color"
}, it = { class: "c-option-control__title flex-grow-1" }, rt = { class: "h5 m-0" }, dt = { class: "c-option-control__actions d-flex align-items-center gap-1" }, at = ["onClick"], ut = ["onClick"], pt = { class: "d-none" }, ct = ["name", "value"], vt = ["name", "value"], mt = ["name", "value"], ft = ["name", "value"], gt = { class: "col-lg-6 l-feature-option-item" }, bt = { class: "card-header" }, yt = { class: "card-body" }, _t = {
  key: 0,
  class: "c-option-edit__form"
}, kt = { class: "form-group mb-4" }, xt = {
  for: "input-option-text",
  class: "form-label"
}, Ct = { class: "form-group mb-4" }, Vt = {
  for: "input-option-value",
  class: "form-label"
}, It = {
  key: 0,
  class: "form-group mb-4"
}, wt = {
  for: "input-option-value",
  class: "form-label"
}, ht = { key: 1 }, Nt = { class: "card bg-light" }, St = { class: "card-body text-center" };
function Pt(s, t, c, n, f, r) {
  return p(), u("div", Q, [
    o("div", R, [
      o("div", W, [
        o("div", X, [
          o("h3", Y, a(s.$lang("shopgo.product.feature.options.title")), 1),
          t[11] || (t[11] = l()),
          o("div", Z, [
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
            ], 8, $)
          ])
        ]),
        t[24] || (t[24] = l()),
        o("div", tt, [
          V(n.VueDraggable, {
            modelValue: n.items,
            "onUpdate:modelValue": t[4] || (t[4] = (e) => n.items = e),
            handle: ".handle",
            animation: 150
          }, {
            default: I(() => [
              V(A, { name: "fade" }, {
                default: I(() => [
                  (p(!0), u(D, null, U(n.items, (e) => (p(), u("div", {
                    key: e.uid,
                    class: F(["list-group-item c-option-item", [{ active: n.current === e }]]),
                    onClick: (d) => n.selectItem(e),
                    style: { cursor: "pointer", "animation-duration": ".3s" }
                  }, [
                    o("div", et, [
                      o("div", nt, [
                        t[12] || (t[12] = o("span", {
                          class: "fa fa-fw fa-ellipsis-v handle",
                          style: { cursor: "move" }
                        }, null, -1)),
                        t[13] || (t[13] = l()),
                        m(o("input", {
                          type: "checkbox",
                          name: "selected[]",
                          "onUpdate:modelValue": t[2] || (t[2] = (d) => n.selected = d),
                          class: "form-check-input",
                          value: e.uid,
                          onClick: t[3] || (t[3] = b(() => {
                          }, ["stop"]))
                        }, null, 8, lt), [
                          [q, n.selected]
                        ])
                      ]),
                      t[17] || (t[17] = l()),
                      n.type === "color" ? (p(), u("div", st, [
                        o("div", {
                          class: "c-option-item__color-box rounded",
                          style: z([{ width: "25px", height: "25px" }, { "background-color": e.data.color || "#eee" }])
                        }, null, 4)
                      ])) : w("", !0),
                      t[18] || (t[18] = l()),
                      o("div", it, [
                        o("div", rt, a(e.data.text || s.$lang("shopgo.product.feature.text.unnamed")), 1)
                      ]),
                      t[19] || (t[19] = l()),
                      o("div", dt, [
                        o("button", {
                          type: "button",
                          class: "btn btn-sm btn-light border-secondary",
                          onClick: b((d) => n.addNewItem(e), ["stop"])
                        }, [...t[14] || (t[14] = [
                          o("span", { class: "fa fa-plus" }, null, -1)
                        ])], 8, at),
                        t[16] || (t[16] = l()),
                        o("button", {
                          type: "button",
                          class: "btn btn-sm btn-outline-danger",
                          onClick: b((d) => n.removeItem(e), ["stop"])
                        }, [...t[15] || (t[15] = [
                          o("span", { class: "fa fa-trash" }, null, -1)
                        ])], 8, ut)
                      ])
                    ]),
                    t[23] || (t[23] = l()),
                    o("div", pt, [
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][uid]`,
                        value: e.data.uid
                      }, null, 8, ct),
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
                      }, null, 8, mt),
                      t[22] || (t[22] = l()),
                      o("input", {
                        type: "hidden",
                        name: `options[${e.uid}][color]`,
                        value: e.data.color
                      }, null, 8, ft)
                    ])
                  ], 10, ot))), 128))
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
    o("div", gt, [
      (p(), u("div", {
        class: "card c-option-edit",
        key: n.current?.uid
      }, [
        o("div", bt, a(s.$lang("shopgo.product.feature.option.data.title")), 1),
        t[30] || (t[30] = l()),
        o("div", yt, [
          n.current ? (p(), u("div", _t, [
            o("div", kt, [
              o("label", xt, a(s.$lang("shopgo.product.feature.option.text")), 1),
              t[25] || (t[25] = l()),
              m(o("input", {
                id: "input-option-text",
                type: "text",
                class: "form-control",
                "onUpdate:modelValue": t[5] || (t[5] = (e) => n.current.data.text = e)
              }, null, 512), [
                [y, n.current.data.text]
              ])
            ]),
            t[28] || (t[28] = l()),
            o("div", Ct, [
              o("label", Vt, a(s.$lang("shopgo.product.feature.option.value")), 1),
              t[26] || (t[26] = l()),
              m(o("input", {
                id: "input-option-value",
                type: "text",
                class: "form-control",
                "onUpdate:modelValue": t[6] || (t[6] = (e) => n.current.data.value = e)
              }, null, 512), [
                [y, n.current.data.value]
              ])
            ]),
            t[29] || (t[29] = l()),
            n.type === "color" ? (p(), u("div", It, [
              o("label", wt, a(s.$lang("shopgo.product.feature.option.color")), 1),
              t[27] || (t[27] = l()),
              o("div", null, [
                m(o("input", {
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
          ])) : (p(), u("div", ht, [
            o("div", Nt, [
              o("div", St, a(s.$lang("shopgo.product.feature.option.no.select")), 1)
            ])
          ]))
        ])
      ]))
    ])
  ]);
}
const Et = /* @__PURE__ */ H(K, [["render", Pt], ["__file", "ProductFeatureEditApp.vue"]]);
function Lt(s) {
  const t = L(Et, s);
  return t.use(j), t;
}
export {
  Lt as initApp
};
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoicHJvZHVjdC1mZWF0dXJlLWVkaXQuanMiLCJzb3VyY2VzIjpbIi4uLy4uL3NyYy9tb2R1bGVzL3Byb2R1Y3QtZmVhdHVyZS9Qcm9kdWN0RmVhdHVyZUVkaXRBcHAudnVlIiwiLi4vLi4vc3JjL21vZHVsZXMvcHJvZHVjdC1mZWF0dXJlL3Byb2R1Y3QtZmVhdHVyZS1lZGl0LnRzIl0sInNvdXJjZXNDb250ZW50IjpbIjxzY3JpcHQgc2V0dXAgbGFuZz1cInRzXCI+XG5pbXBvcnQgeyB1bmlxdWVJdGVtLCB1bmlxdWVJdGVtTGlzdCB9IGZyb20gJ0BseXJhc29mdC90cy10b29sa2l0L3Z1ZSc7XG5pbXBvcnQgeyB1c2VDc3NJbXBvcnQgfSBmcm9tICdAd2luZHdhbGtlci1pby91bmljb3JuLW5leHQnO1xuaW1wb3J0IHsgcmVmIH0gZnJvbSAndnVlJztcbmltcG9ydCB7IFZ1ZURyYWdnYWJsZSB9IGZyb20gJ3Z1ZS1kcmFnZ2FibGUtcGx1cyc7XG5pbXBvcnQgeyB2Q29sb3JwaWNrZXIgfSBmcm9tICd+c2hvcGdvL2RpcmVjdGl2ZXMnO1xuaW1wb3J0IHsgTGlzdE9wdGlvbiB9IGZyb20gJ35zaG9wZ28vdHlwZXMnO1xuXG51c2VDc3NJbXBvcnQoJ0B2dWUtYW5pbWF0ZScpO1xuXG50eXBlIFByb2R1Y3RGZWF0dXJlT3B0aW9uSXRlbSA9IHtcbiAgZGF0YTogTGlzdE9wdGlvbjtcbiAgdWlkOiBzdHJpbmc7XG4gIHNlbGVjdGVkOiBib29sZWFuO1xufTtcblxuY29uc3QgJHR5cGVTZWxlY3QgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yPEhUTUxTZWxlY3RFbGVtZW50PignI2lucHV0LWl0ZW0tdHlwZScpITtcblxuY29uc3QgcHJvcHMgPSBkZWZpbmVQcm9wczx7XG4gIG9wdGlvbnM6IExpc3RPcHRpb25bXTtcbn0+KCk7XG5cbmNvbnN0IHR5cGUgPSByZWYoJHR5cGVTZWxlY3QudmFsdWUpO1xuY29uc3QgaXRlbXMgPSByZWY8UHJvZHVjdEZlYXR1cmVPcHRpb25JdGVtW10+KFxuICB1bmlxdWVJdGVtTGlzdChwcm9wcy5vcHRpb25zIHx8IFtdKS5tYXAoKGl0ZW0pID0+ICh7XG4gICAgZGF0YTogaXRlbSxcbiAgICB1aWQ6IGl0ZW0udWlkLFxuICAgIHNlbGVjdGVkOiBmYWxzZVxuICB9KSlcbik7XG5cbmNvbnN0IGN1cnJlbnQgPSByZWY8UHJvZHVjdEZlYXR1cmVPcHRpb25JdGVtIHwgbnVsbD4obnVsbCk7XG5jb25zdCBzZWxlY3RlZCA9IHJlZjxzdHJpbmdbXT4oW10pO1xuY29uc3QgY29sb3JQaWNrZXJPcHRpb25zID0gcmVmPFJlY29yZDxzdHJpbmcsIHVua25vd24+Pih7fSk7XG5cbiR0eXBlU2VsZWN0LmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsICgpID0+IHtcbiAgdHlwZS52YWx1ZSA9ICR0eXBlU2VsZWN0LnZhbHVlO1xufSk7XG5cbmZ1bmN0aW9uIHNlbGVjdEl0ZW0oaXRlbTogUHJvZHVjdEZlYXR1cmVPcHRpb25JdGVtKSB7XG4gIGN1cnJlbnQudmFsdWUgPSBpdGVtO1xufVxuXG5mdW5jdGlvbiBhZGROZXdJdGVtKGl0ZW0/OiBQcm9kdWN0RmVhdHVyZU9wdGlvbkl0ZW0pIHtcbiAgY29uc3QgaSA9IGl0ZW0gPyBpdGVtcy52YWx1ZS5pbmRleE9mKGl0ZW0pICsgMSA6IGl0ZW1zLnZhbHVlLmxlbmd0aDtcblxuICBjb25zdCBkYXRhID0gdW5pcXVlSXRlbSh7XG4gICAgdmFsdWU6ICcnLFxuICAgIHRleHQ6ICcnLFxuICAgIGNvbG9yOiAnJ1xuICB9KTtcbiAgY29uc3QgbmV3SXRlbSA9IHtcbiAgICBkYXRhLFxuICAgIHVpZDogZGF0YS51aWQsXG4gICAgc2VsZWN0ZWQ6IGZhbHNlXG4gIH07XG5cbiAgaXRlbXMudmFsdWUuc3BsaWNlKGksIDAsIG5ld0l0ZW0pO1xuXG4gIHNlbGVjdEl0ZW0obmV3SXRlbSk7XG59XG5cbmZ1bmN0aW9uIHJlbW92ZUl0ZW0oaXRlbTogUHJvZHVjdEZlYXR1cmVPcHRpb25JdGVtKSB7XG4gIGNvbnN0IGkgPSBpdGVtcy52YWx1ZS5pbmRleE9mKGl0ZW0pO1xuXG4gIGl0ZW1zLnZhbHVlLnNwbGljZShpLCAxKTtcbn1cblxuZnVuY3Rpb24gcmVtb3ZlSXRlbXMoKSB7XG4gIGl0ZW1zLnZhbHVlID0gaXRlbXMudmFsdWUuZmlsdGVyKChpdGVtKSA9PiAhc2VsZWN0ZWQudmFsdWUuaW5jbHVkZXMoaXRlbS51aWQpKTtcblxuICBpZiAoc2VsZWN0ZWQudmFsdWUuaW5jbHVkZXMoY3VycmVudC52YWx1ZT8udWlkIHx8ICcnKSkge1xuICAgIGN1cnJlbnQudmFsdWUgPSBudWxsO1xuICB9XG5cbiAgc2VsZWN0ZWQudmFsdWUgPSBbXTtcbn1cblxuZnVuY3Rpb24gdG9Kc29uKGRhdGE6IFByb2R1Y3RGZWF0dXJlT3B0aW9uSXRlbSkge1xuICByZXR1cm4gSlNPTi5zdHJpbmdpZnkoZGF0YSk7XG59XG48L3NjcmlwdD5cblxuPHRlbXBsYXRlPlxuICA8ZGl2IGNsYXNzPVwicm93XCI+XG4gICAgPGRpdiBjbGFzcz1cImNvbC1sZy02XCI+XG4gICAgICA8ZGl2IGNsYXNzPVwiY2FyZCBjLWZlYXR1cmUtb3B0aW9uLWxpc3RcIj5cbiAgICAgICAgPGRpdiBjbGFzcz1cImNhcmQtaGVhZGVyIGQtZmxleCBhbGlnbi1pdGVtcy1jZW50ZXJcIj5cbiAgICAgICAgICA8aDMgY2xhc3M9XCJtLTBcIj5cbiAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5mZWF0dXJlLm9wdGlvbnMudGl0bGUnKSB9fVxuICAgICAgICAgIDwvaDM+XG4gICAgICAgICAgPGRpdiBjbGFzcz1cImMtbGlzdC10b3AtdG9vbGJhciBtcy1hdXRvXCI+XG4gICAgICAgICAgICA8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImJ0biBidG4tc20gYnRuLXByaW1hcnlcIlxuICAgICAgICAgICAgICBAY2xpY2s9XCJhZGROZXdJdGVtKClcIj5cbiAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJmYSBmYS1wbHVzXCI+PC9zcGFuPlxuICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLnByb2R1Y3QuZmVhdHVyZS5idXR0b24ubmV3JykgfX1cbiAgICAgICAgICAgIDwvYnV0dG9uPlxuICAgICAgICAgICAgPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJidG4gYnRuLXNtIGJ0bi1vdXRsaW5lLWRhbmdlclwiXG4gICAgICAgICAgICAgIEBjbGljaz1cInJlbW92ZUl0ZW1zKClcIiA6ZGlzYWJsZWQ9XCJzZWxlY3RlZC5sZW5ndGggPT09IDBcIj5cbiAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJmYSBmYS10cmFzaFwiPjwvc3Bhbj5cbiAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LmZlYXR1cmUuYnV0dG9uLmRlbGV0ZScpIH19XG4gICAgICAgICAgICA8L2J1dHRvbj5cbiAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgPGRpdiBjbGFzcz1cImMtb3B0aW9uLWxpc3QgbGlzdC1ncm91cCBsaXN0LWdyb3VwLWZsdXNoXCI+XG4gICAgICAgICAgPFZ1ZURyYWdnYWJsZSB2LW1vZGVsPVwiaXRlbXNcIiBoYW5kbGU9XCIuaGFuZGxlXCIgOmFuaW1hdGlvbj1cIjE1MFwiPlxuICAgICAgICAgICAgPFRyYW5zaXRpb25Hcm91cCBuYW1lPVwiZmFkZVwiPlxuICAgICAgICAgICAgICA8dGVtcGxhdGUgdi1mb3I9XCJpdGVtIG9mIGl0ZW1zXCIgOmtleT1cIml0ZW0udWlkXCI+XG4gICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImxpc3QtZ3JvdXAtaXRlbSBjLW9wdGlvbi1pdGVtXCJcbiAgICAgICAgICAgICAgICAgIDpjbGFzcz1cIlt7YWN0aXZlOiBjdXJyZW50ID09PSBpdGVtfV1cIlxuICAgICAgICAgICAgICAgICAgQGNsaWNrPVwic2VsZWN0SXRlbShpdGVtKVwiXG4gICAgICAgICAgICAgICAgICBzdHlsZT1cImN1cnNvcjogcG9pbnRlcjsgYW5pbWF0aW9uLWR1cmF0aW9uOiAuM3M7XCJcbiAgICAgICAgICAgICAgICA+XG4gICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZC1mbGV4IGFsaWduLWl0ZW1zLWNlbnRlciBnYXAtMlwiPlxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiYy1vcHRpb24taXRlbV9fY29udHJvbFwiPlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLWZ3IGZhLWVsbGlwc2lzLXYgaGFuZGxlXCJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzdHlsZT1cImN1cnNvcjogbW92ZVwiXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgID48L3NwYW4+XG4gICAgICAgICAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJjaGVja2JveFwiIG5hbWU9XCJzZWxlY3RlZFtdXCIgdi1tb2RlbD1cInNlbGVjdGVkXCJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNsYXNzPVwiZm9ybS1jaGVjay1pbnB1dFwiXG4gICAgICAgICAgICAgICAgICAgICAgICA6dmFsdWU9XCJpdGVtLnVpZFwiXG4gICAgICAgICAgICAgICAgICAgICAgICBAY2xpY2suc3RvcD1cIlwiIC8+XG4gICAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgICAgICA8ZGl2IHYtaWY9XCJ0eXBlID09PSAnY29sb3InXCIgY2xhc3M9XCJjLW9wdGlvbi1pdGVtX19jb2xvclwiPlxuICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjLW9wdGlvbi1pdGVtX19jb2xvci1ib3ggcm91bmRlZFwiXG4gICAgICAgICAgICAgICAgICAgICAgICBzdHlsZT1cIndpZHRoOiAyNXB4OyBoZWlnaHQ6IDI1cHg7XCJcbiAgICAgICAgICAgICAgICAgICAgICAgIDpzdHlsZT1cInsnYmFja2dyb3VuZC1jb2xvcic6IGl0ZW0uZGF0YS5jb2xvciB8fCAnI2VlZSd9XCI+PC9kaXY+XG4gICAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiYy1vcHRpb24tY29udHJvbF9fdGl0bGUgZmxleC1ncm93LTFcIj5cbiAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiaDUgbS0wXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICB7eyBpdGVtLmRhdGEudGV4dCB8fCAkbGFuZygnc2hvcGdvLnByb2R1Y3QuZmVhdHVyZS50ZXh0LnVubmFtZWQnKSB9fVxuICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgICAgICAgPGRpdlxuICAgICAgICAgICAgICAgICAgICAgIGNsYXNzPVwiYy1vcHRpb24tY29udHJvbF9fYWN0aW9ucyBkLWZsZXggYWxpZ24taXRlbXMtY2VudGVyIGdhcC0xXCI+XG4gICAgICAgICAgICAgICAgICAgICAgPCEtLTxkaXYgY2xhc3M9XCJtci0yXCIgQGNsaWNrLnN0b3A9XCJcIj4tLT5cbiAgICAgICAgICAgICAgICAgICAgICA8IS0tPGxhYmVsIDpmb3I9XCInZGVmYXVsdC1yYWRpby0nICsgaXRlbS51aWRcIj7poJDoqK08L2xhYmVsPi0tPlxuICAgICAgICAgICAgICAgICAgICAgIDwhLS08aW5wdXQgdHlwZT1cInJhZGlvXCIgbmFtZT1cIml0ZW1bZGVmYXVsdF1cIiA6dmFsdWU9XCJpdGVtLnVpZFwiLS0+XG4gICAgICAgICAgICAgICAgICAgICAgPCEtLTppZD1cIidkZWZhdWx0LXJhZGlvLScgKyBpdGVtLnVpZFwiLS0+XG4gICAgICAgICAgICAgICAgICAgICAgPCEtLUBjbGljaz1cInNldERlZmF1bHQoaSlcIiAvPi0tPlxuICAgICAgICAgICAgICAgICAgICAgIDwhLS08L2Rpdj4tLT5cbiAgICAgICAgICAgICAgICAgICAgICA8YnV0dG9uIHR5cGU9XCJidXR0b25cIlxuICAgICAgICAgICAgICAgICAgICAgICAgY2xhc3M9XCJidG4gYnRuLXNtIGJ0bi1saWdodCBib3JkZXItc2Vjb25kYXJ5XCJcbiAgICAgICAgICAgICAgICAgICAgICAgIEBjbGljay5zdG9wPVwiYWRkTmV3SXRlbShpdGVtKVwiPlxuICAgICAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJmYSBmYS1wbHVzXCI+PC9zcGFuPlxuICAgICAgICAgICAgICAgICAgICAgIDwvYnV0dG9uPlxuICAgICAgICAgICAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1zbSBidG4tb3V0bGluZS1kYW5nZXJcIlxuICAgICAgICAgICAgICAgICAgICAgICAgQGNsaWNrLnN0b3A9XCJyZW1vdmVJdGVtKGl0ZW0pXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLXRyYXNoXCI+PC9zcGFuPlxuICAgICAgICAgICAgICAgICAgICAgIDwvYnV0dG9uPlxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImQtbm9uZVwiPlxuICAgICAgICAgICAgICAgICAgICA8aW5wdXQgdHlwZT1cImhpZGRlblwiIDpuYW1lPVwiYG9wdGlvbnNbJHtpdGVtLnVpZH1dW3VpZF1gXCJcbiAgICAgICAgICAgICAgICAgICAgICA6dmFsdWU9XCJpdGVtLmRhdGEudWlkXCIgLz5cbiAgICAgICAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJoaWRkZW5cIiA6bmFtZT1cImBvcHRpb25zWyR7aXRlbS51aWR9XVt0ZXh0XWBcIlxuICAgICAgICAgICAgICAgICAgICAgIDp2YWx1ZT1cIml0ZW0uZGF0YS50ZXh0XCIgLz5cbiAgICAgICAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJoaWRkZW5cIiA6bmFtZT1cImBvcHRpb25zWyR7aXRlbS51aWR9XVt2YWx1ZV1gXCJcbiAgICAgICAgICAgICAgICAgICAgICA6dmFsdWU9XCJpdGVtLmRhdGEudmFsdWVcIiAvPlxuICAgICAgICAgICAgICAgICAgICA8aW5wdXQgdHlwZT1cImhpZGRlblwiIDpuYW1lPVwiYG9wdGlvbnNbJHtpdGVtLnVpZH1dW2NvbG9yXWBcIlxuICAgICAgICAgICAgICAgICAgICAgIDp2YWx1ZT1cIml0ZW0uZGF0YS5jb2xvclwiIC8+XG4gICAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgPC90ZW1wbGF0ZT5cbiAgICAgICAgICAgIDwvVHJhbnNpdGlvbkdyb3VwPlxuICAgICAgICAgIDwvVnVlRHJhZ2dhYmxlPlxuICAgICAgICA8L2Rpdj5cbiAgICAgIDwvZGl2PlxuICAgIDwvZGl2PlxuICAgIDxkaXYgY2xhc3M9XCJjb2wtbGctNiBsLWZlYXR1cmUtb3B0aW9uLWl0ZW1cIj5cbiAgICAgIDxkaXYgY2xhc3M9XCJjYXJkIGMtb3B0aW9uLWVkaXRcIiA6a2V5PVwiY3VycmVudD8udWlkXCI+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkLWhlYWRlclwiPlxuICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5mZWF0dXJlLm9wdGlvbi5kYXRhLnRpdGxlJykgfX1cbiAgICAgICAgPC9kaXY+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkLWJvZHlcIj5cbiAgICAgICAgICA8ZGl2IHYtaWY9XCJjdXJyZW50XCIgY2xhc3M9XCJjLW9wdGlvbi1lZGl0X19mb3JtXCI+XG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBtYi00XCI+XG4gICAgICAgICAgICAgIDxsYWJlbCBmb3I9XCJpbnB1dC1vcHRpb24tdGV4dFwiIGNsYXNzPVwiZm9ybS1sYWJlbFwiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5mZWF0dXJlLm9wdGlvbi50ZXh0JykgfX1cbiAgICAgICAgICAgICAgPC9sYWJlbD5cbiAgICAgICAgICAgICAgPGlucHV0IGlkPVwiaW5wdXQtb3B0aW9uLXRleHRcIiB0eXBlPVwidGV4dFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCJcbiAgICAgICAgICAgICAgICB2LW1vZGVsPVwiY3VycmVudC5kYXRhLnRleHRcIiAvPlxuICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIG1iLTRcIj5cbiAgICAgICAgICAgICAgPGxhYmVsIGZvcj1cImlucHV0LW9wdGlvbi12YWx1ZVwiIGNsYXNzPVwiZm9ybS1sYWJlbFwiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5mZWF0dXJlLm9wdGlvbi52YWx1ZScpIH19XG4gICAgICAgICAgICAgIDwvbGFiZWw+XG4gICAgICAgICAgICAgIDxpbnB1dCBpZD1cImlucHV0LW9wdGlvbi12YWx1ZVwiIHR5cGU9XCJ0ZXh0XCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIlxuICAgICAgICAgICAgICAgIHYtbW9kZWw9XCJjdXJyZW50LmRhdGEudmFsdWVcIiAvPlxuICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIG1iLTRcIiB2LWlmPVwidHlwZSA9PT0gJ2NvbG9yJ1wiPlxuICAgICAgICAgICAgICA8bGFiZWwgZm9yPVwiaW5wdXQtb3B0aW9uLXZhbHVlXCIgY2xhc3M9XCJmb3JtLWxhYmVsXCI+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LmZlYXR1cmUub3B0aW9uLmNvbG9yJykgfX1cbiAgICAgICAgICAgICAgPC9sYWJlbD5cbiAgICAgICAgICAgICAgPGRpdj5cbiAgICAgICAgICAgICAgICA8aW5wdXQgaWQ9XCJpbnB1dC1vcHRpb24tY29sb3JcIiB0eXBlPVwidGV4dFwiXG4gICAgICAgICAgICAgICAgICB2LWNvbG9ycGlja2VyPVwiY29sb3JQaWNrZXJPcHRpb25zXCJcbiAgICAgICAgICAgICAgICAgIGNsYXNzPVwiZm9ybS1jb250cm9sXCJcbiAgICAgICAgICAgICAgICAgIHYtbW9kZWwubGF6eT1cImN1cnJlbnQuZGF0YS5jb2xvclwiXG4gICAgICAgICAgICAgICAgLz5cbiAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICA8ZGl2IHYtZWxzZT5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkIGJnLWxpZ2h0XCI+XG4gICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkLWJvZHkgdGV4dC1jZW50ZXJcIj5cbiAgICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLnByb2R1Y3QuZmVhdHVyZS5vcHRpb24ubm8uc2VsZWN0JykgfX1cbiAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgPC9kaXY+XG4gICAgICA8L2Rpdj5cbiAgICA8L2Rpdj5cbiAgPC9kaXY+XG48L3RlbXBsYXRlPlxuXG48c3R5bGUgc2NvcGVkPlxuXG48L3N0eWxlPlxuIiwiaW1wb3J0IHsgY3JlYXRlQXBwIH0gZnJvbSAndnVlJztcbmltcG9ydCBQcm9kdWN0RmVhdHVyZUVkaXRBcHAgZnJvbSAnfnNob3Bnby9tb2R1bGVzL3Byb2R1Y3QtZmVhdHVyZS9Qcm9kdWN0RmVhdHVyZUVkaXRBcHAudnVlJztcbmltcG9ydCB7IFNob3BHb1BsdWdpbiB9IGZyb20gJ35zaG9wZ28vc2hvcGdvLXBsdWdpbic7XG5cbmV4cG9ydCBmdW5jdGlvbiBpbml0QXBwKHByb3BzOiBSZWNvcmQ8c3RyaW5nLCBhbnk+KSB7XG4gIGNvbnN0IGFwcCA9IGNyZWF0ZUFwcChQcm9kdWN0RmVhdHVyZUVkaXRBcHAsIHByb3BzKTtcblxuICBhcHAudXNlKFNob3BHb1BsdWdpbik7XG5cbiAgcmV0dXJuIGFwcDtcbn1cblxuXG5cblxuIl0sIm5hbWVzIjpbInVzZUNzc0ltcG9ydCIsIiR0eXBlU2VsZWN0IiwicHJvcHMiLCJfX3Byb3BzIiwidHlwZSIsInJlZiIsIml0ZW1zIiwidW5pcXVlSXRlbUxpc3QiLCJpdGVtIiwiY3VycmVudCIsInNlbGVjdGVkIiwiY29sb3JQaWNrZXJPcHRpb25zIiwic2VsZWN0SXRlbSIsImFkZE5ld0l0ZW0iLCJpIiwiZGF0YSIsInVuaXF1ZUl0ZW0iLCJuZXdJdGVtIiwicmVtb3ZlSXRlbSIsInJlbW92ZUl0ZW1zIiwidG9Kc29uIiwiX2hvaXN0ZWRfMSIsIl9ob2lzdGVkXzIiLCJfaG9pc3RlZF8zIiwiX2hvaXN0ZWRfNCIsIl9ob2lzdGVkXzUiLCJfaG9pc3RlZF82IiwiX2hvaXN0ZWRfOCIsIl9ob2lzdGVkXzEwIiwiX2hvaXN0ZWRfMTEiLCJfaG9pc3RlZF8xNCIsIl9ob2lzdGVkXzE1IiwiX2hvaXN0ZWRfMTYiLCJfaG9pc3RlZF8xOSIsIl9ob2lzdGVkXzI0IiwiX2hvaXN0ZWRfMjUiLCJfaG9pc3RlZF8yNiIsIl9ob2lzdGVkXzI4IiwiX2hvaXN0ZWRfMzAiLCJfaG9pc3RlZF8zNSIsIl9ob2lzdGVkXzM2IiwiX29wZW5CbG9jayIsIl9jcmVhdGVFbGVtZW50QmxvY2siLCJfY3JlYXRlRWxlbWVudFZOb2RlIiwiX3RvRGlzcGxheVN0cmluZyIsIl9jdHgiLCJfY2FjaGUiLCJfY3JlYXRlVGV4dFZOb2RlIiwiJHNldHVwIiwiX2hvaXN0ZWRfNyIsIl9jcmVhdGVWTm9kZSIsIiRldmVudCIsIl9UcmFuc2l0aW9uR3JvdXAiLCJfd2l0aEN0eCIsIl9GcmFnbWVudCIsIl9yZW5kZXJMaXN0IiwiX25vcm1hbGl6ZUNsYXNzIiwiX3dpdGhNb2RpZmllcnMiLCJfaG9pc3RlZF8xMiIsIl9ob2lzdGVkXzEzIiwiX25vcm1hbGl6ZVN0eWxlIiwiX2hvaXN0ZWRfMTciLCJfaG9pc3RlZF8xOCIsIl9ob2lzdGVkXzIwIiwiX2hvaXN0ZWRfMjEiLCJfaG9pc3RlZF8yMiIsIl9ob2lzdGVkXzIzIiwiX2hvaXN0ZWRfMjciLCJfaG9pc3RlZF8yOSIsIl92TW9kZWxUZXh0IiwiX2hvaXN0ZWRfMzEiLCJfaG9pc3RlZF8zMiIsIl9ob2lzdGVkXzMzIiwiX3dpdGhEaXJlY3RpdmVzIiwiX2hvaXN0ZWRfMzQiLCJpbml0QXBwIiwiYXBwIiwiY3JlYXRlQXBwIiwiUHJvZHVjdEZlYXR1cmVFZGl0QXBwIiwiU2hvcEdvUGx1Z2luIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7O1NBUUFBLEVBQWEsY0FBYztBQVEzQixVQUFNQyxJQUFjLFNBQVMsY0FBaUMsa0JBQWtCLEdBRTFFQyxJQUFRQyxHQUlSQyxJQUFPQyxFQUFJSixFQUFZLEtBQUssR0FDNUJLLElBQVFEO0FBQUEsTUFDWkUsRUFBZUwsRUFBTSxXQUFXLENBQUEsQ0FBRSxFQUFFLElBQUksQ0FBQ00sT0FBVTtBQUFBLFFBQ2pELE1BQU1BO0FBQUEsUUFDTixLQUFLQSxFQUFLO0FBQUEsUUFDVixVQUFVO0FBQUEsTUFBQSxFQUNWO0FBQUEsSUFBQSxHQUdFQyxJQUFVSixFQUFxQyxJQUFJLEdBQ25ESyxJQUFXTCxFQUFjLEVBQUUsR0FDM0JNLElBQXFCTixFQUE2QixFQUFFO0FBRTFELElBQUFKLEVBQVksaUJBQWlCLFVBQVUsTUFBTTtBQUMzQyxNQUFBRyxFQUFLLFFBQVFILEVBQVk7QUFBQSxJQUMzQixDQUFDO0FBRUQsYUFBU1csRUFBV0osR0FBZ0M7QUFDbEQsTUFBQUMsRUFBUSxRQUFRRDtBQUFBLElBQ2xCO0FBRUEsYUFBU0ssRUFBV0wsR0FBaUM7QUFDbkQsWUFBTU0sSUFBSU4sSUFBT0YsRUFBTSxNQUFNLFFBQVFFLENBQUksSUFBSSxJQUFJRixFQUFNLE1BQU0sUUFFdkRTLElBQU9DLEVBQVc7QUFBQSxRQUN0QixPQUFPO0FBQUEsUUFDUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsTUFBQSxDQUNSLEdBQ0tDLElBQVU7QUFBQSxRQUNk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
