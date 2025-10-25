import { resolveVueComponent as H } from "vite-plugin-vue-component-override";
import { data as Y, useHttpClient as Z, simpleAlert as ot, __ as et, useStack as st, route as at, simpleConfirm as rt, useFieldFlatpickr as lt } from "@windwalker-io/unicorn-next";
import { defineComponent as J, ref as w, inject as Q, computed as $, onMounted as dt, createElementBlock as v, openBlock as d, createElementVNode as n, createTextVNode as o, toDisplayString as l, Fragment as j, renderList as M, withDirectives as O, vModelCheckbox as nt, createCommentVNode as S, normalizeStyle as ut, watch as B, vModelText as P, createVNode as z, withCtx as K, mergeProps as ct, createBlock as D, normalizeClass as mt, provide as tt, TransitionGroup as pt, createApp as vt } from "vue";
import { uniqueItemList as it } from "@lyrasoft/ts-toolkit/vue";
import { h as W } from "./index.es.js";
import { _ as R } from "./_plugin-vue_export-helper.js";
import { VueDraggable as ft } from "vue-draggable-plus";
import { mergeRecursive as gt, ShopGoPlugin as ht } from "../index.js";
import { ItemCardPlaceholder as bt, ItemCard as kt, MultiUploader as _t } from "vue-multi-uploader";
const yt = /* @__PURE__ */ J({
  __name: "VariantGeneration",
  props: {
    items: {}
  },
  emits: ["generated", "cancel"],
  setup(s, { expose: t, emit: c }) {
    t();
    const e = s, f = c, a = w([]), i = w(!1), r = w(!1), b = Q("product"), k = Q("mainPrice"), _ = $(() => e.items.map((u) => u.hash)), y = $(() => {
      const u = /* @__PURE__ */ new Set();
      for (const g of e.items)
        for (const p of g.options)
          u.add(p.uid);
      return Array.from(u);
    }), V = Y("variants.limit") ?? 100;
    dt(() => {
      E();
    });
    async function E() {
      r.value = !0;
      const { get: u } = await Z();
      try {
        const g = await u("@product_ajax/getFeatureOptions");
        a.value = it(g.data.data).map((p) => (p.checks = 0, p));
        for (const p of a.value) {
          let I = 0;
          for (const L of p.options)
            L.checked = y.value.includes(L.uid), L.checked && I++;
          p.checks = I;
        }
      } finally {
        r.value = !1;
      }
    }
    const U = $(() => a.value.reduce((u, g) => g.checks > 0 ? u * g.checks : u, 1));
    async function N() {
      if (U.value >= V) {
        ot(
          et("shopgo.product.message.too.many.features.selected", U.value, V),
          "",
          "warning"
        );
        return;
      }
      i.value = !0;
      const { post: u } = await Z();
      try {
        const p = (await u(
          "@product_ajax/generateVariants",
          {
            product_id: b?.id,
            options: A(),
            currentHashes: _.value
          }
        )).data.data;
        for (const I of p)
          I.price = Number(k.value);
        f("generated", p);
      } finally {
        i.value = !1;
      }
    }
    function A() {
      const u = {};
      for (const g of a.value) {
        const p = g.options.filter((I) => I.checked);
        p.length > 0 && (u[g.id.toString()] = p);
      }
      return u;
    }
    function F(u, g) {
      const p = g.target;
      u.options.forEach((I) => I.checked = p.checked), u.checks = p.checked ? u.options.length : 0;
    }
    function C(u, g) {
      u.checks = 0, u.options.forEach((p) => {
        p.checked && u.checks++;
      });
    }
    function x() {
      f("cancel");
    }
    const T = { props: e, emit: f, features: a, loadingGenerating: i, loadingGetFeatureOptions: r, product: b, mainPrice: k, currentHashes: _, currentOptionUids: y, variantsLimit: V, getFeatureOptions: E, combinationCount: U, saveGenerate: N, getCheckedOptionGroup: A, featureCheckboxChanged: F, optionCheckboxChanged: C, cancel: x };
    return Object.defineProperty(T, "__isScriptSetup", { enumerable: !1, value: !0 }), T;
  }
}), Ct = { class: "c-variant-generate card sticky-top" }, Vt = { class: "card-header d-flex" }, wt = { class: "c-variant-generate__title" }, xt = { class: "c-variant-generate__actions ms-auto" }, St = ["disabled"], It = ["disabled"], Et = {
  key: 0,
  class: "c-feature-list list-group list-group-flush"
}, Ut = { class: "c-feature-item list-group-item" }, Ot = { class: "mb-3 h5" }, $t = ["checked", "id", ".indeterminate", "onChange"], Gt = ["for"], Pt = { class: "c-option-list row" }, Ft = { class: "c-option-item col-md-4 col-6" }, Lt = { class: "c-option-item__input-wrapper form-check" }, Nt = ["id", "value", "name", "onUpdate:modelValue", "onChange"], At = ["for"], qt = { class: "list-group-item" }, Dt = ["disabled"], Tt = {
  key: 1,
  class: "text-center card-body"
};
function jt(s, t, c, e, f, a) {
  return d(), v("div", Ct, [
    n("div", Vt, [
      n("div", wt, l(s.$lang("shopgo.product.variant.generation.title")) + " (" + l(e.combinationCount || 0) + `)\r
      `, 1),
      t[3] || (t[3] = o()),
      n("div", xt, [
        n("button", {
          type: "button",
          class: "btn btn-primary btn-sm",
          onClick: e.saveGenerate,
          disabled: e.loadingGenerating
        }, [
          t[0] || (t[0] = n("span", { class: "fa fa-save" }, null, -1)),
          o(" " + l(e.loadingGenerating ? s.$lang("shopgo.product.text.saving") : s.$lang("shopgo.product.variant.generation.button.submit")), 1)
        ], 8, St),
        t[2] || (t[2] = o()),
        n("button", {
          type: "button",
          class: "btn btn-outline-secondary btn-sm",
          onClick: e.cancel,
          disabled: e.loadingGenerating
        }, [
          t[1] || (t[1] = n("span", { class: "fa fa-times" }, null, -1)),
          o(" " + l(s.$lang("shopgo.product.button.cancel")), 1)
        ], 8, It)
      ])
    ]),
    t[10] || (t[10] = o()),
    e.loadingGetFeatureOptions ? (d(), v("div", Tt, l(s.$lang("shopgo.product.text.loading")), 1)) : (d(), v("div", Et, [
      (d(!0), v(j, null, M(e.features, (i) => (d(), v("div", Ut, [
        n("h4", Ot, [
          n("span", null, [
            n("input", {
              type: "checkbox",
              checked: i.options.length === i.checks,
              id: "input-feature-" + i.id,
              class: "form-check-input",
              ".indeterminate": i.checks !== 0 && i.options.length > i.checks,
              onChange: (r) => e.featureCheckboxChanged(i, r)
            }, null, 40, $t)
          ]),
          t[4] || (t[4] = o()),
          n("label", {
            for: "input-feature-" + i.id
          }, l(i.title), 9, Gt)
        ]),
        t[7] || (t[7] = o()),
        n("div", Pt, [
          (d(!0), v(j, null, M(i.options, (r) => (d(), v("div", Ft, [
            n("div", Lt, [
              O(n("input", {
                id: "input-option-" + r.uid,
                type: "checkbox",
                value: r.uid,
                name: `options[${i.id}][${r.uid}]`,
                class: "form-check-input",
                "onUpdate:modelValue": (b) => r.checked = b,
                onChange: (b) => e.optionCheckboxChanged(i, r)
              }, null, 40, Nt), [
                [nt, r.checked]
              ]),
              t[6] || (t[6] = o()),
              n("label", {
                for: "input-option-" + r.uid,
                class: "form-check-label d-flex align-items-center"
              }, [
                i.type === "color" ? (d(), v("span", {
                  key: 0,
                  class: "rounded me-2",
                  style: ut([{ width: "20px", height: "20px" }, { "background-color": r.color }])
                }, null, 4)) : S("", !0),
                t[5] || (t[5] = o()),
                n("span", null, l(r.text), 1)
              ], 8, At)
            ])
          ]))), 256))
        ])
      ]))), 256)),
      t[9] || (t[9] = o()),
      n("div", qt, [
        n("button", {
          type: "button",
          class: "btn btn-primary btn-sm w-100",
          onClick: e.saveGenerate,
          disabled: e.loadingGenerating
        }, [
          t[8] || (t[8] = n("span", { class: "fa fa-save" }, null, -1)),
          o(" " + l(e.loadingGenerating ? s.$lang("shopgo.product.text.saving") : s.$lang("shopgo.product.variant.generation.button.submit")), 1)
        ], 8, Dt)
      ])
    ]))
  ]);
}
const Mt = /* @__PURE__ */ R(yt, [["render", jt], ["__file", "VariantGeneration.vue"]]), Ht = /* @__PURE__ */ J({
  __name: "VariantInfoEdit",
  props: {
    variants: {}
  },
  emits: ["cancel"],
  setup(s, { expose: t, emit: c }) {
    t();
    const e = s, f = c, a = w(null), i = w([]), r = w(""), b = w(
      JSON.stringify(
        {
          dateFormat: "Y-m-d H:i:S",
          enableTime: !0,
          enableSeconds: !0,
          allowInput: !0,
          time_24hr: !0,
          // wrap: true,
          monthSelect: !1
        }
      )
    ), k = st("uploading"), _ = w(Y("input.step") || "0.0001");
    B(() => e.variants, (C) => {
      let x = {
        sku: "",
        price: "",
        stockQuantity: "",
        publishUp: "",
        publishDown: "",
        images: [],
        dimension: {
          width: "",
          height: "",
          length: "",
          weight: "",
          unitWeight: ""
        }
      };
      i.value = e.variants, i.value.length === 1 && (x = i.value[0]), r.value = y(x), a.value = x;
    }, { immediate: !0 });
    function y(C) {
      const x = { ...C };
      return delete x.checked, delete x.unsave, W.hashStr(JSON.stringify(x));
    }
    const V = $(() => i.value.length > 1);
    B(() => a.value, () => {
      r.value !== "" && r.value !== y(a.value) && E();
    }, { deep: !0 }), B(() => a.value?.price, (C) => {
      a.value && C != null && C < 0 && (a.value.price = 0);
    });
    function E() {
      if (a.value)
        if (!V.value)
          a.value.cover = a.value.images[0]?.url || "", i.value[0].unsave = !0;
        else
          for (const C of i.value)
            gt(
              C,
              a.value
            ), C.unsave = !0;
    }
    function U() {
      f("cancel");
    }
    function N() {
      return at("file_upload", { profile: "image" });
    }
    const F = { props: e, emit: f, current: a, items: i, currentHash: r, flatpickrOptions: b, stack: k, inputStep: _, hashItem: y, isMultiple: V, updateUnsaves: E, cancelEdit: U, getImageUploaderUrl: N, draggableOptions: {
      handle: ".item",
      animation: 150
    }, get VueDraggable() {
      return ft;
    }, get MultiUploader() {
      return _t;
    }, get ItemCard() {
      return kt;
    }, get ItemCardPlaceholder() {
      return bt;
    } };
    return Object.defineProperty(F, "__isScriptSetup", { enumerable: !1, value: !0 }), F;
  }
}), Jt = { class: "c-variant-edit card" }, Rt = { class: "card-header d-flex align-items-center" }, Bt = { class: "c-variant-edit__title d-flex gap-2" }, Qt = { class: "c-variant-edit__actions ms-auto" }, zt = {
  key: 0,
  class: "card-body"
}, Kt = { class: "c-variant-edit__title mb-4" }, Wt = { class: "lead" }, Yt = { class: "d-flex gap-2" }, Xt = {
  key: 0,
  class: "form-group mb-4"
}, Zt = {
  for: "input-variant-sku",
  class: "form-label"
}, te = { class: "form-group mb-4" }, ee = {
  for: "input-variant-price",
  class: "form-label"
}, ne = ["step"], ie = { class: "d-flex gap-2" }, oe = { class: "form-group mb-4" }, se = {
  for: "input-variant-length",
  class: "form-label"
}, ae = { class: "form-group mb-4" }, re = {
  for: "input-variant-width",
  class: "form-label"
}, le = { class: "form-group mb-4" }, de = {
  for: "input-variant-height",
  class: "form-label"
}, ue = { class: "form-group mb-4" }, ce = {
  for: "input-variant-weight",
  class: "form-label"
}, me = { class: "d-flex gap-2" }, pe = { class: "form-group mb-4" }, ve = {
  for: "input-variant-inventory",
  class: "form-label"
}, fe = { class: "form-group mb-4" }, ge = {
  for: "input-variant-subtract",
  class: "form-label"
}, he = { class: "form-check form-switch" }, be = {
  key: 0,
  class: "variant-images mt-4"
};
function ke(s, t, c, e, f, a) {
  return d(), v("div", Jt, [
    n("div", Rt, [
      n("div", Bt, [
        n("div", null, l(s.$lang("shopgo.product.variant.edit.title")), 1)
      ]),
      t[12] || (t[12] = o()),
      n("div", Qt, [
        n("button", {
          type: "button",
          class: "btn btn-outline-secondary btn-sm",
          onClick: e.cancelEdit
        }, [
          t[11] || (t[11] = n("span", { class: "fa fa-times" }, null, -1)),
          o(" " + l(s.$lang("shopgo.product.button.cancel")), 1)
        ])
      ])
    ]),
    t[31] || (t[31] = o()),
    e.current ? (d(), v("div", zt, [
      n("div", Kt, [
        n("span", Wt, l(e.items.length <= 1 ? e.current.title : s.$lang("shopgo.product.variant.edit.multiple")), 1)
      ]),
      t[27] || (t[27] = o()),
      n("div", Yt, [
        e.items.length <= 1 ? (d(), v("div", Xt, [
          n("label", Zt, l(s.$lang("shopgo.product.field.sku")), 1),
          t[13] || (t[13] = o()),
          O(n("textarea", {
            id: "input-variant-sku",
            type: "text",
            class: "form-control",
            "onUpdate:modelValue": t[0] || (t[0] = (i) => e.current.sku = i),
            rows: "1"
          }, null, 512), [
            [P, e.current.sku]
          ])
        ])) : S("", !0),
        t[15] || (t[15] = o()),
        n("div", te, [
          n("label", ee, l(s.$lang("shopgo.product.field.price")), 1),
          t[14] || (t[14] = o()),
          O(n("input", {
            id: "input-variant-price",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[1] || (t[1] = (i) => e.current.price = i),
            min: "0",
            step: e.inputStep
          }, null, 8, ne), [
            [P, e.current.price]
          ])
        ])
      ]),
      t[28] || (t[28] = o()),
      n("div", ie, [
        n("div", oe, [
          n("label", se, l(s.$lang("shopgo.product.field.length")), 1),
          t[16] || (t[16] = o()),
          O(n("input", {
            id: "input-variant-length",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[2] || (t[2] = (i) => e.current.dimension.length = i),
            min: "0"
          }, null, 512), [
            [P, e.current.dimension.length]
          ])
        ]),
        t[20] || (t[20] = o()),
        n("div", ae, [
          n("label", re, l(s.$lang("shopgo.product.field.width")), 1),
          t[17] || (t[17] = o()),
          O(n("input", {
            id: "input-variant-width",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[3] || (t[3] = (i) => e.current.dimension.width = i),
            min: "0"
          }, null, 512), [
            [P, e.current.dimension.width]
          ])
        ]),
        t[21] || (t[21] = o()),
        n("div", le, [
          n("label", de, l(s.$lang("shopgo.product.field.height")), 1),
          t[18] || (t[18] = o()),
          O(n("input", {
            id: "input-variant-height",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[4] || (t[4] = (i) => e.current.dimension.height = i),
            min: "0"
          }, null, 512), [
            [P, e.current.dimension.height]
          ])
        ]),
        t[22] || (t[22] = o()),
        n("div", ue, [
          n("label", ce, l(s.$lang("shopgo.product.field.weight")), 1),
          t[19] || (t[19] = o()),
          O(n("input", {
            id: "input-variant-weight",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[5] || (t[5] = (i) => e.current.dimension.weight = i),
            min: "0"
          }, null, 512), [
            [P, e.current.dimension.weight]
          ])
        ])
      ]),
      t[29] || (t[29] = o()),
      n("div", me, [
        n("div", pe, [
          n("label", ve, l(s.$lang("shopgo.product.field.stock.quantity")), 1),
          t[23] || (t[23] = o()),
          O(n("input", {
            id: "input-variant-inventory",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[6] || (t[6] = (i) => e.current.stockQuantity = i),
            min: "0"
          }, null, 512), [
            [P, e.current.stockQuantity]
          ])
        ]),
        t[25] || (t[25] = o()),
        n("div", fe, [
          n("label", ge, l(s.$lang("shopgo.product.field.subtract")), 1),
          t[24] || (t[24] = o()),
          n("div", he, [
            O(n("input", {
              type: "checkbox",
              id: "input-variant-subtract",
              class: "form-check-input",
              "onUpdate:modelValue": t[7] || (t[7] = (i) => e.current.subtract = i),
              "true-value": !0,
              "false-value": !1,
              role: "switch"
            }, null, 512), [
              [nt, e.current.subtract]
            ])
          ])
        ])
      ]),
      t[30] || (t[30] = o()),
      e.items.length <= 1 ? (d(), v("div", be, [
        z(e.MultiUploader, {
          "upload-url": e.getImageUploaderUrl(),
          modelValue: e.current.images,
          "onUpdate:modelValue": t[8] || (t[8] = (i) => e.current.images = i),
          options: {
            maxFiles: 6,
            accept: "image/*"
          },
          onUploading: t[9] || (t[9] = (i) => e.stack.push(!0)),
          onUploaded: t[10] || (t[10] = (i) => e.stack.pop())
        }, {
          items: K(({ instance: i, instance: { canUpload: r, openFileSelector: b, deleteItem: k } }) => [
            z(e.VueDraggable, ct({
              modelValue: i.items,
              "onUpdate:modelValue": (_) => i.items = _
            }, e.draggableOptions, { class: "d-flex flex-wrap w-100 gap-3" }), {
              default: K(() => [
                (d(!0), v(j, null, M(i.items, (_, y) => (d(), D(e.ItemCard, {
                  key: _.key,
                  class: "item",
                  item: _,
                  i: y,
                  onDelete: k
                }, null, 8, ["item", "i", "onDelete"]))), 128)),
                t[26] || (t[26] = o()),
                r ? (d(), D(e.ItemCardPlaceholder, {
                  key: 0,
                  class: "",
                  text: "Upload Images",
                  onClick: b
                }, null, 8, ["onClick"])) : S("", !0)
              ]),
              _: 2
            }, 1040, ["modelValue", "onUpdate:modelValue"])
          ]),
          _: 1
        }, 8, ["upload-url", "modelValue"])
      ])) : S("", !0)
    ])) : S("", !0)
  ]);
}
const _e = /* @__PURE__ */ R(Ht, [["render", ke], ["__file", "VariantInfoEdit.vue"]]), ye = /* @__PURE__ */ J({
  __name: "VariantListItem",
  props: {
    item: {},
    i: {},
    active: { type: Boolean }
  },
  emits: ["edit", "remove", "oncheck"],
  setup(s, { expose: t, emit: c }) {
    t();
    const e = s, f = c, a = Y("defaultImage");
    function i() {
      f("edit", e.item);
    }
    function r() {
      f("remove", e.item);
    }
    function b(V) {
      f("oncheck", V, e.i);
    }
    const k = Q("mainPrice"), _ = $(() => Number(e.item.price) - Number(k.value)), y = { props: e, emit: f, defaultImage: a, edit: i, remove: r, multiCheck: b, mainPrice: k, priceOffset: _ };
    return Object.defineProperty(y, "__isScriptSetup", { enumerable: !1, value: !0 }), y;
  }
}), Ce = { class: "list-group-item__wrapper d-flex align-items-center gap-2" }, Ve = { class: "c-variant-item__control d-flex flex-nowrap" }, we = ["checked"], xe = { class: "c-variant-item__image" }, Se = ["src"], Ie = { class: "c-variant-item__title flex-fill text-truncate" }, Ee = {
  class: "text-truncate",
  style: { "max-width": "100%" }
}, Ue = {
  key: 0,
  style: { opacity: ".75" }
}, Oe = {
  key: 1,
  style: { opacity: ".75" }
}, $e = {
  key: 2,
  class: "badge bg-warning"
}, Ge = { class: "c-variant-item__inventory text-end" }, Pe = { class: "c-variant-item__actions d-flex flex-nowrap gap-1" }, Fe = ["disabled"], Le = ["disabled"];
function Ne(s, t, c, e, f, a) {
  return d(), v("div", {
    class: mt(["list-group-item c-variant-item", { active: c.active }])
  }, [
    n("div", Ce, [
      n("div", Ve, [
        n("input", {
          type: "checkbox",
          class: "form-check-input",
          checked: c.item.checked,
          onClick: e.multiCheck
        }, null, 8, we)
      ]),
      t[6] || (t[6] = o()),
      n("div", xe, [
        n("img", {
          src: c.item.cover || e.defaultImage,
          width: "45",
          height: "45",
          alt: "Cover",
          class: "rounded"
        }, null, 8, Se)
      ]),
      t[7] || (t[7] = o()),
      n("div", Ie, [
        n("div", Ee, l(c.item.title), 1),
        t[2] || (t[2] = o()),
        n("div", null, [
          c.item.sku ? (d(), v("span", Ue, `\r
                    #` + l(c.item.sku), 1)) : S("", !0),
          t[0] || (t[0] = o()),
          e.priceOffset !== 0 ? (d(), v("span", Oe, l(s.$offsetFormat(e.priceOffset, "$")), 1)) : S("", !0),
          t[1] || (t[1] = o()),
          c.item.unsave ? (d(), v("span", $e, l(s.$lang("shopgo.product.text.save.required")), 1)) : S("", !0)
        ])
      ]),
      t[8] || (t[8] = o()),
      n("div", Ge, l(s.$numberFormat(c.item.stockQuantity)), 1),
      t[9] || (t[9] = o()),
      n("div", Pe, [
        n("button", {
          type: "button",
          class: "btn btn-sm btn-light border-secondary",
          onClick: e.edit,
          disabled: c.item.saving
        }, [...t[3] || (t[3] = [
          n("span", { class: "fa fa-pencil-alt" }, null, -1)
        ])], 8, Fe),
        t[5] || (t[5] = o()),
        n("button", {
          type: "button",
          class: "btn btn-sm btn-light border-secondary",
          onClick: e.remove,
          disabled: c.item.saving
        }, [...t[4] || (t[4] = [
          n("span", { class: "fa fa-trash text-danger" }, null, -1)
        ])], 8, Le)
      ])
    ])
  ], 2);
}
const Ae = /* @__PURE__ */ R(ye, [["render", Ne], ["__file", "VariantListItem.vue"]]), qe = H("~shopgo/modules/product-edit/components/VariantGeneration.vue", Mt), De = H("~shopgo/modules/product-edit/components/VariantInfoEdit.vue", _e), Te = H("~shopgo/modules/product-edit/components/VariantListItem.vue", Ae), je = /* @__PURE__ */ J({
  __name: "ProductVariantsEditApp",
  props: {
    product: {},
    variants: {}
  },
  setup(s, { expose: t }) {
    t();
    const c = s, e = document.querySelector("#input-item-variant-price"), f = document.querySelector("#admin-form"), a = w(E(c.variants)), i = w({
      edit: !1
    }), r = w(0), b = w(parseFloat(e.value).toString());
    tt("product", c.product || {}), tt("mainPrice", b), e.addEventListener("change", () => {
      b.value = parseFloat(e.value).toString();
    });
    let k = !1;
    const _ = W.hashStr(JSON.stringify(a.value)), y = $(() => W.hashStr(L.value) !== _);
    window.addEventListener("beforeunload", (m) => {
      if (y.value && !k)
        return m.preventDefault(), m.stopPropagation(), m.returnValue = "Save Required", "Save Required";
    }), f.addEventListener("submit", () => {
      k = !0;
    });
    const V = $(() => a.value.filter((m) => m.checked));
    function E(m) {
      return it(m).map((h) => (h.checked = !1, h.unsave = !1, h));
    }
    function U(m, h) {
      const q = m?.target;
      a.value.forEach((G) => {
        G.checked = h ?? q?.checked;
      });
    }
    async function N(m, h) {
      const q = m.target;
      if (m.shiftKey) {
        let G = r.value;
        if (r.value < h)
          for (; G < h; G++)
            a.value[G].checked = q.checked;
        else
          for (; G > h; G--)
            a.value[G].checked = q.checked;
      } else if (a.value[h].checked = q.checked, r.value === null) {
        r.value = h;
        return;
      }
      r.value = h;
    }
    function A() {
      return V.value.length;
    }
    const F = $(() => {
      if (V.value.length === 1)
        return V.value[0];
    }), C = w();
    async function x(m) {
      await g() && (U(void 0, !1), i.value.edit = !1, m.checked = !0);
    }
    async function T() {
      await g() && (i.value.edit = !0);
    }
    function u(m) {
      a.value = a.value.concat(E(m)), i.value.edit = !1;
    }
    async function g() {
      return U(void 0, !1), !0;
    }
    async function p() {
      return !(C.value && C.value.unsave && !await rt(et("shopgo.message.save.required")));
    }
    function I(m) {
      m ? a.value = a.value.filter((h) => h.hash !== m.hash) : a.value = a.value.filter((h) => !h.checked);
    }
    const L = $(() => JSON.stringify(a.value)), X = { props: c, priceInput: e, form: f, items: a, generate: i, lastCheckItemIndex: r, mainPrice: b, get formSubmitting() {
      return k;
    }, set formSubmitting(m) {
      k = m;
    }, initialHash: _, saveRequired: y, checkedItems: V, prepareItems: E, checkAll: U, multiCheck: N, countChecked: A, current: F, variantEdit: C, editVariant: x, generateCombinations: T, generated: u, cancelEdit: g, confirmLeave: p, deleteVariants: I, itemsJSON: L, VariantGeneration: qe, VariantInfoEdit: De, VariantListItem: Te };
    return Object.defineProperty(X, "__isScriptSetup", { enumerable: !1, value: !0 }), X;
  }
}), Me = {
  class: "row",
  "data-novalidate": ""
}, He = { class: "col-lg-6 l-product-variant__list" }, Je = { class: "card c-variant-list" }, Re = { class: "card-header c-variant-list__toolbar d-flex" }, Be = { class: "ms-auto" }, Qe = ["disabled"], ze = ["disabled"], Ke = { class: "c-variant-list__items list-group list-group-flush" }, We = {
  class: "list-group-item c-variant-list__header d-flex",
  style: { "margin-bottom": "0" }
}, Ye = { class: "me-2" }, Xe = [".indeterminate"], Ze = {
  class: "me-2",
  style: { width: "45px" }
}, tn = { class: "me-2 flex-fill" }, en = {
  class: "me-2",
  style: { width: "75px" }
}, nn = {
  class: "",
  style: { width: "66px" }
}, on = {
  class: "c-variant-list__scroll list-group list-group-flush",
  style: { "overflow-y": "scroll", height: "75vh", "min-height": "400px" }
}, sn = { class: "col-lg-6 l-product-variant__manage" }, an = ["value"];
function rn(s, t, c, e, f, a) {
  return d(), v("div", Me, [
    n("div", He, [
      n("div", Je, [
        n("div", Re, [
          n("div", Be, [
            e.countChecked() > 0 ? (d(), v("button", {
              key: 0,
              type: "button",
              class: "btn btn-sm btn-outline-danger",
              onClick: t[0] || (t[0] = (i) => e.deleteVariants()),
              disabled: e.generate.edit
            }, [
              t[4] || (t[4] = n("span", { class: "fa fa-trash" }, null, -1)),
              o(" " + l(s.$lang("shopgo.product.variant.button.delete.variants")), 1)
            ], 8, Qe)) : S("", !0),
            t[6] || (t[6] = o()),
            n("button", {
              type: "button",
              class: "btn btn-sm btn-primary",
              onClick: t[1] || (t[1] = (i) => e.generateCombinations()),
              disabled: e.generate.edit
            }, [
              t[5] || (t[5] = n("span", { class: "fa fa-plus" }, null, -1)),
              o(" " + l(s.$lang("shopgo.product.variant.button.add.variants")), 1)
            ], 8, ze)
          ])
        ]),
        t[12] || (t[12] = o()),
        n("div", Ke, [
          n("div", We, [
            n("div", Ye, [
              n("input", {
                type: "checkbox",
                class: "form-check-input",
                onChange: t[2] || (t[2] = (i) => e.checkAll(i)),
                ".indeterminate": e.countChecked() > 0 && e.countChecked() < e.items.length
              }, null, 40, Xe)
            ]),
            t[7] || (t[7] = o()),
            n("div", Ze, l(s.$lang("shopgo.product.variant.label.cover")), 1),
            t[8] || (t[8] = o()),
            n("div", tn, l(s.$lang("shopgo.product.variant.label.options")), 1),
            t[9] || (t[9] = o()),
            n("div", en, l(s.$lang("shopgo.product.variant.label.stock.quantity")), 1),
            t[10] || (t[10] = o()),
            n("div", nn, l(s.$lang("shopgo.product.variant.label.actions")), 1)
          ]),
          t[11] || (t[11] = o()),
          n("div", on, [
            z(pt, { name: "fade" }, {
              default: K(() => [
                (d(!0), v(j, null, M(e.items, (i, r) => (d(), D(e.VariantListItem, {
                  key: i.uid,
                  "data-id": i.id,
                  item: i,
                  i: r,
                  active: e.current?.hash === i.hash,
                  onEdit: e.editVariant,
                  onRemove: (b) => e.deleteVariants(i),
                  onOncheck: e.multiCheck,
                  style: { "animation-duration": ".3s" }
                }, null, 8, ["data-id", "item", "i", "active", "onRemove"]))), 128))
              ]),
              _: 1
            })
          ])
        ])
      ])
    ]),
    t[14] || (t[14] = o()),
    n("div", sn, [
      e.checkedItems.length ? (d(), D(e.VariantInfoEdit, {
        key: 0,
        ref: "variantEdit",
        variants: e.checkedItems,
        onCancel: e.cancelEdit
      }, null, 8, ["variants"])) : S("", !0),
      t[13] || (t[13] = o()),
      e.generate.edit ? (d(), D(e.VariantGeneration, {
        key: 1,
        items: e.items,
        onGenerated: e.generated,
        onCancel: t[3] || (t[3] = (i) => {
          e.generate.edit = !1;
        }),
        class: ""
      }, null, 8, ["items"])) : S("", !0)
    ]),
    t[15] || (t[15] = o()),
    n("textarea", {
      name: "variants",
      class: "d-none",
      value: e.itemsJSON
    }, null, 8, an)
  ]);
}
const ln = /* @__PURE__ */ R(je, [["render", rn], ["__file", "ProductVariantsEditApp.vue"]]), dn = H("~shopgo/modules/product-edit/ProductVariantsEditApp.vue", ln);
function kn(s) {
  const t = vt(dn, s);
  return lt(), t.use(ht), t;
}
export {
  kn as initApp
};
//# sourceMappingURL=product-variants-edit.js.map
