import { data as W, useHttpClient as X, simpleAlert as it, __ as tt, useStack as ot, route as st, simpleConfirm as at, useFieldFlatpickr as rt } from "@windwalker-io/unicorn-next";
import { defineComponent as J, ref as V, inject as Q, computed as $, onMounted as lt, createElementBlock as v, openBlock as d, createElementVNode as n, createTextVNode as o, toDisplayString as l, Fragment as M, renderList as H, withDirectives as E, vModelCheckbox as et, createCommentVNode as S, normalizeStyle as dt, watch as B, vModelText as P, createVNode as T, withCtx as z, mergeProps as ut, createBlock as D, normalizeClass as ct, provide as Z, TransitionGroup as mt, createApp as pt } from "vue";
import { uniqueItemList as nt } from "@lyrasoft/ts-toolkit/vue";
import { h as K } from "./index.es.js";
import { _ as R } from "./_plugin-vue_export-helper.js";
import { VueDraggable as vt } from "vue-draggable-plus";
import { mergeRecursive as ft, ShopGoPlugin as gt } from "../index.js";
import { ItemCardPlaceholder as ht, ItemCard as bt, MultiUploader as kt } from "vue-multi-uploader";
const _t = /* @__PURE__ */ J({
  __name: "VariantGeneration",
  props: {
    items: {}
  },
  emits: ["generated", "cancel"],
  setup(s, { expose: t, emit: c }) {
    t();
    const e = s, f = c, a = V([]), i = V(!1), r = V(!1), b = Q("product"), k = Q("mainPrice"), _ = $(() => e.items.map((u) => u.hash)), y = $(() => {
      const u = /* @__PURE__ */ new Set();
      for (const g of e.items)
        for (const p of g.options)
          u.add(p.uid);
      return Array.from(u);
    }), w = W("variants.limit") ?? 100;
    lt(() => {
      U();
    });
    async function U() {
      r.value = !0;
      const { get: u } = await X();
      try {
        const g = await u("@product_ajax/getFeatureOptions");
        a.value = nt(g.data.data).map((p) => (p.checks = 0, p));
        for (const p of a.value) {
          let I = 0;
          for (const N of p.options)
            N.checked = y.value.includes(N.uid), N.checked && I++;
          p.checks = I;
        }
      } finally {
        r.value = !1;
      }
    }
    const O = $(() => a.value.reduce((u, g) => g.checks > 0 ? u * g.checks : u, 1));
    async function L() {
      if (O.value >= w) {
        it(
          tt("shopgo.product.message.too.many.features.selected", O.value, w),
          "",
          "warning"
        );
        return;
      }
      i.value = !0;
      const { post: u } = await X();
      try {
        const p = (await u(
          "@product_ajax/generateVariants",
          {
            product_id: b?.id,
            options: q(),
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
    function q() {
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
    const j = { props: e, emit: f, features: a, loadingGenerating: i, loadingGetFeatureOptions: r, product: b, mainPrice: k, currentHashes: _, currentOptionUids: y, variantsLimit: w, getFeatureOptions: U, combinationCount: O, saveGenerate: L, getCheckedOptionGroup: q, featureCheckboxChanged: F, optionCheckboxChanged: C, cancel: x };
    return Object.defineProperty(j, "__isScriptSetup", { enumerable: !1, value: !0 }), j;
  }
}), yt = { class: "c-variant-generate card sticky-top" }, Ct = { class: "card-header d-flex" }, wt = { class: "c-variant-generate__title" }, Vt = { class: "c-variant-generate__actions ms-auto" }, xt = ["disabled"], St = ["disabled"], It = {
  key: 0,
  class: "c-feature-list list-group list-group-flush"
}, Ut = { class: "c-feature-item list-group-item" }, Ot = { class: "mb-3 h5" }, Et = ["checked", "id", ".indeterminate", "onChange"], $t = ["for"], Gt = { class: "c-option-list row" }, Pt = { class: "c-option-item col-md-4 col-6" }, Ft = { class: "c-option-item__input-wrapper form-check" }, Nt = ["id", "value", "name", "onUpdate:modelValue", "onChange"], Lt = ["for"], qt = { class: "list-group-item" }, At = ["disabled"], Dt = {
  key: 1,
  class: "text-center card-body"
};
function jt(s, t, c, e, f, a) {
  return d(), v("div", yt, [
    n("div", Ct, [
      n("div", wt, l(s.$lang("shopgo.product.variant.generation.title")) + " (" + l(e.combinationCount || 0) + `)
      `, 1),
      t[3] || (t[3] = o()),
      n("div", Vt, [
        n("button", {
          type: "button",
          class: "btn btn-primary btn-sm",
          onClick: e.saveGenerate,
          disabled: e.loadingGenerating
        }, [
          t[0] || (t[0] = n("span", { class: "fa fa-save" }, null, -1)),
          o(" " + l(e.loadingGenerating ? s.$lang("shopgo.product.text.saving") : s.$lang("shopgo.product.variant.generation.button.submit")), 1)
        ], 8, xt),
        t[2] || (t[2] = o()),
        n("button", {
          type: "button",
          class: "btn btn-outline-secondary btn-sm",
          onClick: e.cancel,
          disabled: e.loadingGenerating
        }, [
          t[1] || (t[1] = n("span", { class: "fa fa-times" }, null, -1)),
          o(" " + l(s.$lang("shopgo.product.button.cancel")), 1)
        ], 8, St)
      ])
    ]),
    t[10] || (t[10] = o()),
    e.loadingGetFeatureOptions ? (d(), v("div", Dt, l(s.$lang("shopgo.product.text.loading")), 1)) : (d(), v("div", It, [
      (d(!0), v(M, null, H(e.features, (i) => (d(), v("div", Ut, [
        n("h4", Ot, [
          n("span", null, [
            n("input", {
              type: "checkbox",
              checked: i.options.length === i.checks,
              id: "input-feature-" + i.id,
              class: "form-check-input",
              ".indeterminate": i.checks !== 0 && i.options.length > i.checks,
              onChange: (r) => e.featureCheckboxChanged(i, r)
            }, null, 40, Et)
          ]),
          t[4] || (t[4] = o()),
          n("label", {
            for: "input-feature-" + i.id
          }, l(i.title), 9, $t)
        ]),
        t[7] || (t[7] = o()),
        n("div", Gt, [
          (d(!0), v(M, null, H(i.options, (r) => (d(), v("div", Pt, [
            n("div", Ft, [
              E(n("input", {
                id: "input-option-" + r.uid,
                type: "checkbox",
                value: r.uid,
                name: `options[${i.id}][${r.uid}]`,
                class: "form-check-input",
                "onUpdate:modelValue": (b) => r.checked = b,
                onChange: (b) => e.optionCheckboxChanged(i, r)
              }, null, 40, Nt), [
                [et, r.checked]
              ]),
              t[6] || (t[6] = o()),
              n("label", {
                for: "input-option-" + r.uid,
                class: "form-check-label d-flex align-items-center"
              }, [
                i.type === "color" ? (d(), v("span", {
                  key: 0,
                  class: "rounded me-2",
                  style: dt([{ width: "20px", height: "20px" }, { "background-color": r.color }])
                }, null, 4)) : S("", !0),
                t[5] || (t[5] = o()),
                n("span", null, l(r.text), 1)
              ], 8, Lt)
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
        ], 8, At)
      ])
    ]))
  ]);
}
const Mt = /* @__PURE__ */ R(_t, [["render", jt], ["__file", "VariantGeneration.vue"]]), Ht = /* @__PURE__ */ J({
  __name: "VariantInfoEdit",
  props: {
    variants: {}
  },
  emits: ["cancel"],
  setup(s, { expose: t, emit: c }) {
    t();
    const e = s, f = c, a = V(null), i = V([]), r = V(""), b = V(
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
    ), k = ot("uploading"), _ = V(W("input.step") || "0.0001");
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
      return delete x.checked, delete x.unsave, K.hashStr(JSON.stringify(x));
    }
    const w = $(() => i.value.length > 1);
    B(() => a.value, () => {
      r.value !== "" && r.value !== y(a.value) && U();
    }, { deep: !0 }), B(() => a.value?.price, (C) => {
      a.value && C != null && C < 0 && (a.value.price = 0);
    });
    function U() {
      if (a.value)
        if (!w.value)
          a.value.cover = a.value.images[0]?.url || "", i.value[0].unsave = !0;
        else
          for (const C of i.value)
            ft(
              C,
              a.value
            ), C.unsave = !0;
    }
    function O() {
      f("cancel");
    }
    function L() {
      return st("file_upload", { profile: "image" });
    }
    const F = { props: e, emit: f, current: a, items: i, currentHash: r, flatpickrOptions: b, stack: k, inputStep: _, hashItem: y, isMultiple: w, updateUnsaves: U, cancelEdit: O, getImageUploaderUrl: L, draggableOptions: {
      handle: ".item",
      animation: 150
    }, get VueDraggable() {
      return vt;
    }, get MultiUploader() {
      return kt;
    }, get ItemCard() {
      return bt;
    }, get ItemCardPlaceholder() {
      return ht;
    } };
    return Object.defineProperty(F, "__isScriptSetup", { enumerable: !1, value: !0 }), F;
  }
}), Jt = { class: "c-variant-edit card" }, Rt = { class: "card-header d-flex align-items-center" }, Bt = { class: "c-variant-edit__title d-flex gap-2" }, Qt = { class: "c-variant-edit__actions ms-auto" }, Tt = {
  key: 0,
  class: "card-body"
}, zt = { class: "c-variant-edit__title mb-4" }, Kt = { class: "lead" }, Wt = { class: "d-flex gap-2" }, Yt = {
  key: 0,
  class: "form-group mb-4"
}, Xt = {
  for: "input-variant-sku",
  class: "form-label"
}, Zt = { class: "form-group mb-4" }, te = {
  for: "input-variant-price",
  class: "form-label"
}, ee = ["step"], ne = { class: "d-flex gap-2" }, ie = { class: "form-group mb-4" }, oe = {
  for: "input-variant-length",
  class: "form-label"
}, se = { class: "form-group mb-4" }, ae = {
  for: "input-variant-width",
  class: "form-label"
}, re = { class: "form-group mb-4" }, le = {
  for: "input-variant-height",
  class: "form-label"
}, de = { class: "form-group mb-4" }, ue = {
  for: "input-variant-weight",
  class: "form-label"
}, ce = { class: "d-flex gap-2" }, me = { class: "form-group mb-4" }, pe = {
  for: "input-variant-inventory",
  class: "form-label"
}, ve = { class: "form-group mb-4" }, fe = {
  for: "input-variant-subtract",
  class: "form-label"
}, ge = { class: "form-check form-switch" }, he = {
  key: 0,
  class: "variant-images mt-4"
};
function be(s, t, c, e, f, a) {
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
    e.current ? (d(), v("div", Tt, [
      n("div", zt, [
        n("span", Kt, l(e.items.length <= 1 ? e.current.title : s.$lang("shopgo.product.variant.edit.multiple")), 1)
      ]),
      t[27] || (t[27] = o()),
      n("div", Wt, [
        e.items.length <= 1 ? (d(), v("div", Yt, [
          n("label", Xt, l(s.$lang("shopgo.product.field.sku")), 1),
          t[13] || (t[13] = o()),
          E(n("textarea", {
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
        n("div", Zt, [
          n("label", te, l(s.$lang("shopgo.product.field.price")), 1),
          t[14] || (t[14] = o()),
          E(n("input", {
            id: "input-variant-price",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[1] || (t[1] = (i) => e.current.price = i),
            min: "0",
            step: e.inputStep
          }, null, 8, ee), [
            [P, e.current.price]
          ])
        ])
      ]),
      t[28] || (t[28] = o()),
      n("div", ne, [
        n("div", ie, [
          n("label", oe, l(s.$lang("shopgo.product.field.length")), 1),
          t[16] || (t[16] = o()),
          E(n("input", {
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
        n("div", se, [
          n("label", ae, l(s.$lang("shopgo.product.field.width")), 1),
          t[17] || (t[17] = o()),
          E(n("input", {
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
        n("div", re, [
          n("label", le, l(s.$lang("shopgo.product.field.height")), 1),
          t[18] || (t[18] = o()),
          E(n("input", {
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
        n("div", de, [
          n("label", ue, l(s.$lang("shopgo.product.field.weight")), 1),
          t[19] || (t[19] = o()),
          E(n("input", {
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
      n("div", ce, [
        n("div", me, [
          n("label", pe, l(s.$lang("shopgo.product.field.stock.quantity")), 1),
          t[23] || (t[23] = o()),
          E(n("input", {
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
        n("div", ve, [
          n("label", fe, l(s.$lang("shopgo.product.field.subtract")), 1),
          t[24] || (t[24] = o()),
          n("div", ge, [
            E(n("input", {
              type: "checkbox",
              id: "input-variant-subtract",
              class: "form-check-input",
              "onUpdate:modelValue": t[7] || (t[7] = (i) => e.current.subtract = i),
              "true-value": !0,
              "false-value": !1,
              role: "switch"
            }, null, 512), [
              [et, e.current.subtract]
            ])
          ])
        ])
      ]),
      t[30] || (t[30] = o()),
      e.items.length <= 1 ? (d(), v("div", he, [
        T(e.MultiUploader, {
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
          items: z(({ instance: i, instance: { canUpload: r, openFileSelector: b, deleteItem: k } }) => [
            T(e.VueDraggable, ut({
              modelValue: i.items,
              "onUpdate:modelValue": (_) => i.items = _
            }, e.draggableOptions, { class: "d-flex flex-wrap w-100 gap-3" }), {
              default: z(() => [
                (d(!0), v(M, null, H(i.items, (_, y) => (d(), D(e.ItemCard, {
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
const ke = /* @__PURE__ */ R(Ht, [["render", be], ["__file", "VariantInfoEdit.vue"]]), _e = /* @__PURE__ */ J({
  __name: "VariantListItem",
  props: {
    item: {},
    i: {},
    active: { type: Boolean }
  },
  emits: ["edit", "remove", "oncheck"],
  setup(s, { expose: t, emit: c }) {
    t();
    const e = s, f = c, a = W("defaultImage");
    function i() {
      f("edit", e.item);
    }
    function r() {
      f("remove", e.item);
    }
    function b(w) {
      f("oncheck", w, e.i);
    }
    const k = Q("mainPrice"), _ = $(() => Number(e.item.price) - Number(k.value)), y = { props: e, emit: f, defaultImage: a, edit: i, remove: r, multiCheck: b, mainPrice: k, priceOffset: _ };
    return Object.defineProperty(y, "__isScriptSetup", { enumerable: !1, value: !0 }), y;
  }
}), ye = { class: "list-group-item__wrapper d-flex align-items-center gap-2" }, Ce = { class: "c-variant-item__control d-flex flex-nowrap" }, we = ["checked"], Ve = { class: "c-variant-item__image" }, xe = ["src"], Se = { class: "c-variant-item__title flex-fill text-truncate" }, Ie = {
  class: "text-truncate",
  style: { "max-width": "100%" }
}, Ue = {
  key: 0,
  style: { opacity: ".75" }
}, Oe = {
  key: 1,
  style: { opacity: ".75" }
}, Ee = {
  key: 2,
  class: "badge bg-warning"
}, $e = { class: "c-variant-item__inventory text-end" }, Ge = { class: "c-variant-item__actions d-flex flex-nowrap gap-1" }, Pe = ["disabled"], Fe = ["disabled"];
function Ne(s, t, c, e, f, a) {
  return d(), v("div", {
    class: ct(["list-group-item c-variant-item", { active: c.active }])
  }, [
    n("div", ye, [
      n("div", Ce, [
        n("input", {
          type: "checkbox",
          class: "form-check-input",
          checked: c.item.checked,
          onClick: e.multiCheck
        }, null, 8, we)
      ]),
      t[6] || (t[6] = o()),
      n("div", Ve, [
        n("img", {
          src: c.item.cover || e.defaultImage,
          width: "45",
          height: "45",
          alt: "Cover",
          class: "rounded"
        }, null, 8, xe)
      ]),
      t[7] || (t[7] = o()),
      n("div", Se, [
        n("div", Ie, l(c.item.title), 1),
        t[2] || (t[2] = o()),
        n("div", null, [
          c.item.sku ? (d(), v("span", Ue, `
                    #` + l(c.item.sku), 1)) : S("", !0),
          t[0] || (t[0] = o()),
          e.priceOffset !== 0 ? (d(), v("span", Oe, l(s.$offsetFormat(e.priceOffset, "$")), 1)) : S("", !0),
          t[1] || (t[1] = o()),
          c.item.unsave ? (d(), v("span", Ee, l(s.$lang("shopgo.product.text.save.required")), 1)) : S("", !0)
        ])
      ]),
      t[8] || (t[8] = o()),
      n("div", $e, l(s.$numberFormat(c.item.stockQuantity)), 1),
      t[9] || (t[9] = o()),
      n("div", Ge, [
        n("button", {
          type: "button",
          class: "btn btn-sm btn-light border-secondary",
          onClick: e.edit,
          disabled: c.item.saving
        }, [...t[3] || (t[3] = [
          n("span", { class: "fa fa-pencil-alt" }, null, -1)
        ])], 8, Pe),
        t[5] || (t[5] = o()),
        n("button", {
          type: "button",
          class: "btn btn-sm btn-light border-secondary",
          onClick: e.remove,
          disabled: c.item.saving
        }, [...t[4] || (t[4] = [
          n("span", { class: "fa fa-trash text-danger" }, null, -1)
        ])], 8, Fe)
      ])
    ])
  ], 2);
}
const Le = /* @__PURE__ */ R(_e, [["render", Ne], ["__file", "VariantListItem.vue"]]), qe = /* @__PURE__ */ J({
  __name: "ProductVariantsEditApp",
  props: {
    product: {},
    variants: {}
  },
  setup(s, { expose: t }) {
    t();
    const c = s, e = document.querySelector("#input-item-variant-price"), f = document.querySelector("#admin-form"), a = V(U(c.variants)), i = V({
      edit: !1
    }), r = V(0), b = V(parseFloat(e.value).toString());
    Z("product", c.product || {}), Z("mainPrice", b), e.addEventListener("change", () => {
      b.value = parseFloat(e.value).toString();
    });
    let k = !1;
    const _ = K.hashStr(JSON.stringify(a.value)), y = $(() => K.hashStr(N.value) !== _);
    window.addEventListener("beforeunload", (m) => {
      if (y.value && !k)
        return m.preventDefault(), m.stopPropagation(), m.returnValue = "Save Required", "Save Required";
    }), f.addEventListener("submit", () => {
      k = !0;
    });
    const w = $(() => a.value.filter((m) => m.checked));
    function U(m) {
      return nt(m).map((h) => (h.checked = !1, h.unsave = !1, h));
    }
    function O(m, h) {
      const A = m?.target;
      a.value.forEach((G) => {
        G.checked = h ?? A?.checked;
      });
    }
    async function L(m, h) {
      const A = m.target;
      if (m.shiftKey) {
        let G = r.value;
        if (r.value < h)
          for (; G < h; G++)
            a.value[G].checked = A.checked;
        else
          for (; G > h; G--)
            a.value[G].checked = A.checked;
      } else if (a.value[h].checked = A.checked, r.value === null) {
        r.value = h;
        return;
      }
      r.value = h;
    }
    function q() {
      return w.value.length;
    }
    const F = $(() => {
      if (w.value.length === 1)
        return w.value[0];
    }), C = V();
    async function x(m) {
      await g() && (O(void 0, !1), i.value.edit = !1, m.checked = !0);
    }
    async function j() {
      await g() && (i.value.edit = !0);
    }
    function u(m) {
      a.value = a.value.concat(U(m)), i.value.edit = !1;
    }
    async function g() {
      return O(void 0, !1), !0;
    }
    async function p() {
      return !(C.value && C.value.unsave && !await at(tt("shopgo.message.save.required")));
    }
    function I(m) {
      m ? a.value = a.value.filter((h) => h.hash !== m.hash) : a.value = a.value.filter((h) => !h.checked);
    }
    const N = $(() => JSON.stringify(a.value)), Y = { props: c, priceInput: e, form: f, items: a, generate: i, lastCheckItemIndex: r, mainPrice: b, get formSubmitting() {
      return k;
    }, set formSubmitting(m) {
      k = m;
    }, initialHash: _, saveRequired: y, checkedItems: w, prepareItems: U, checkAll: O, multiCheck: L, countChecked: q, current: F, variantEdit: C, editVariant: x, generateCombinations: j, generated: u, cancelEdit: g, confirmLeave: p, deleteVariants: I, itemsJSON: N, VariantGeneration: Mt, VariantInfoEdit: ke, VariantListItem: Le };
    return Object.defineProperty(Y, "__isScriptSetup", { enumerable: !1, value: !0 }), Y;
  }
}), Ae = {
  class: "row",
  "data-novalidate": ""
}, De = { class: "col-lg-6 l-product-variant__list" }, je = { class: "card c-variant-list" }, Me = { class: "card-header c-variant-list__toolbar d-flex" }, He = { class: "ms-auto" }, Je = ["disabled"], Re = ["disabled"], Be = { class: "c-variant-list__items list-group list-group-flush" }, Qe = {
  class: "list-group-item c-variant-list__header d-flex",
  style: { "margin-bottom": "0" }
}, Te = { class: "me-2" }, ze = [".indeterminate"], Ke = {
  class: "me-2",
  style: { width: "45px" }
}, We = { class: "me-2 flex-fill" }, Ye = {
  class: "me-2",
  style: { width: "75px" }
}, Xe = {
  class: "",
  style: { width: "66px" }
}, Ze = {
  class: "c-variant-list__scroll list-group list-group-flush",
  style: { "overflow-y": "scroll", height: "75vh", "min-height": "400px" }
}, tn = { class: "col-lg-6 l-product-variant__manage" }, en = ["value"];
function nn(s, t, c, e, f, a) {
  return d(), v("div", Ae, [
    n("div", De, [
      n("div", je, [
        n("div", Me, [
          n("div", He, [
            e.countChecked() > 0 ? (d(), v("button", {
              key: 0,
              type: "button",
              class: "btn btn-sm btn-outline-danger",
              onClick: t[0] || (t[0] = (i) => e.deleteVariants()),
              disabled: e.generate.edit
            }, [
              t[4] || (t[4] = n("span", { class: "fa fa-trash" }, null, -1)),
              o(" " + l(s.$lang("shopgo.product.variant.button.delete.variants")), 1)
            ], 8, Je)) : S("", !0),
            t[6] || (t[6] = o()),
            n("button", {
              type: "button",
              class: "btn btn-sm btn-primary",
              onClick: t[1] || (t[1] = (i) => e.generateCombinations()),
              disabled: e.generate.edit
            }, [
              t[5] || (t[5] = n("span", { class: "fa fa-plus" }, null, -1)),
              o(" " + l(s.$lang("shopgo.product.variant.button.add.variants")), 1)
            ], 8, Re)
          ])
        ]),
        t[12] || (t[12] = o()),
        n("div", Be, [
          n("div", Qe, [
            n("div", Te, [
              n("input", {
                type: "checkbox",
                class: "form-check-input",
                onChange: t[2] || (t[2] = (i) => e.checkAll(i)),
                ".indeterminate": e.countChecked() > 0 && e.countChecked() < e.items.length
              }, null, 40, ze)
            ]),
            t[7] || (t[7] = o()),
            n("div", Ke, l(s.$lang("shopgo.product.variant.label.cover")), 1),
            t[8] || (t[8] = o()),
            n("div", We, l(s.$lang("shopgo.product.variant.label.options")), 1),
            t[9] || (t[9] = o()),
            n("div", Ye, l(s.$lang("shopgo.product.variant.label.stock.quantity")), 1),
            t[10] || (t[10] = o()),
            n("div", Xe, l(s.$lang("shopgo.product.variant.label.actions")), 1)
          ]),
          t[11] || (t[11] = o()),
          n("div", Ze, [
            T(mt, { name: "fade" }, {
              default: z(() => [
                (d(!0), v(M, null, H(e.items, (i, r) => (d(), D(e.VariantListItem, {
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
    n("div", tn, [
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
    }, null, 8, en)
  ]);
}
const on = /* @__PURE__ */ R(qe, [["render", nn], ["__file", "ProductVariantsEditApp.vue"]]);
function pn(s) {
  const t = pt(on, s);
  return rt(), t.use(gt), t;
}
export {
  pn as initApp
};
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoicHJvZHVjdC12YXJpYW50cy1lZGl0LmpzIiwic291cmNlcyI6WyIuLi8uLi9zcmMvbW9kdWxlcy9wcm9kdWN0LWVkaXQvY29tcG9uZW50cy9WYXJpYW50R2VuZXJhdGlvbi52dWUiLCIuLi8uLi9zcmMvbW9kdWxlcy9wcm9kdWN0LWVkaXQvY29tcG9uZW50cy9WYXJpYW50SW5mb0VkaXQudnVlIiwiLi4vLi4vc3JjL21vZHVsZXMvcHJvZHVjdC1lZGl0L2NvbXBvbmVudHMvVmFyaWFudExpc3RJdGVtLnZ1ZSIsIi4uLy4uL3NyYy9tb2R1bGVzL3Byb2R1Y3QtZWRpdC9Qcm9kdWN0VmFyaWFudHNFZGl0QXBwLnZ1ZSIsIi4uLy4uL3NyYy9tb2R1bGVzL3Byb2R1Y3QtZWRpdC9wcm9kdWN0LXZhcmlhbnRzLWVkaXQudHMiXSwic291cmNlc0NvbnRlbnQiOlsiPHNjcmlwdCBzZXR1cCBsYW5nPVwidHNcIj5cbmltcG9ydCB7IHVuaXF1ZUl0ZW1MaXN0IH0gZnJvbSAnQGx5cmFzb2Z0L3RzLXRvb2xraXQvdnVlJztcbmltcG9ydCB7IF9fLCBBcGlSZXR1cm4sIGRhdGEsIHNpbXBsZUFsZXJ0LCB1c2VIdHRwQ2xpZW50IH0gZnJvbSAnQHdpbmR3YWxrZXItaW8vdW5pY29ybi1uZXh0JztcbmltcG9ydCB7IGluamVjdCwgUmVmLCByZWYsIGNvbXB1dGVkLCBvbk1vdW50ZWQgfSBmcm9tICd2dWUnO1xuaW1wb3J0IHsgTGlzdE9wdGlvbiwgUHJvZHVjdCwgUHJvZHVjdEZlYXR1cmUsIFByb2R1Y3RWYXJpYW50IH0gZnJvbSAnfnNob3Bnby90eXBlcyc7XG5cbmNvbnN0IHByb3BzID0gZGVmaW5lUHJvcHM8e1xuICBpdGVtczogUHJvZHVjdFZhcmlhbnRbXTtcbn0+KCk7XG5cbmNvbnN0IGVtaXQgPSBkZWZpbmVFbWl0czx7XG4gIGdlbmVyYXRlZDogW3ZhcmlhbnRzOiBQcm9kdWN0VmFyaWFudFtdXTtcbiAgY2FuY2VsOiBbXTtcbn0+KClcblxuY29uc3QgZmVhdHVyZXMgPSByZWY8UHJvZHVjdEZlYXR1cmVbXT4oW10pO1xuY29uc3QgbG9hZGluZ0dlbmVyYXRpbmcgPSByZWYoZmFsc2UpO1xuY29uc3QgbG9hZGluZ0dldEZlYXR1cmVPcHRpb25zID0gcmVmKGZhbHNlKTtcblxuY29uc3QgcHJvZHVjdCA9IGluamVjdDxQcm9kdWN0PigncHJvZHVjdCcpITtcbmNvbnN0IG1haW5QcmljZSA9IGluamVjdDxSZWY8c3RyaW5nPj4oJ21haW5QcmljZScpITtcbmNvbnN0IGN1cnJlbnRIYXNoZXMgPSBjb21wdXRlZCgoKSA9PiBwcm9wcy5pdGVtcy5tYXAoaXRlbSA9PiBpdGVtLmhhc2gpKTtcbmNvbnN0IGN1cnJlbnRPcHRpb25VaWRzID0gY29tcHV0ZWQoKCkgPT4ge1xuICBjb25zdCBvcHRpb25zID0gbmV3IFNldCgpO1xuXG4gIGZvciAoY29uc3QgaXRlbSBvZiBwcm9wcy5pdGVtcykge1xuICAgIGZvciAoY29uc3Qgb3B0aW9uIG9mIGl0ZW0ub3B0aW9ucykge1xuICAgICAgb3B0aW9ucy5hZGQob3B0aW9uLnVpZCk7XG4gICAgfVxuICB9XG5cbiAgcmV0dXJuIEFycmF5LmZyb20ob3B0aW9ucyk7XG59KTtcbmNvbnN0IHZhcmlhbnRzTGltaXQgPSBkYXRhPG51bWJlcj4oJ3ZhcmlhbnRzLmxpbWl0JykgPz8gMTAwO1xuXG5vbk1vdW50ZWQoKCkgPT4ge1xuICBnZXRGZWF0dXJlT3B0aW9ucygpO1xufSk7XG5cbmFzeW5jIGZ1bmN0aW9uIGdldEZlYXR1cmVPcHRpb25zKCkge1xuICBsb2FkaW5nR2V0RmVhdHVyZU9wdGlvbnMudmFsdWUgPSB0cnVlO1xuXG4gIGNvbnN0IHsgZ2V0IH0gPSBhd2FpdCB1c2VIdHRwQ2xpZW50KCk7XG5cbiAgdHJ5IHtcbiAgICBjb25zdCByZXMgPSBhd2FpdCBnZXQ8QXBpUmV0dXJuPFByb2R1Y3RGZWF0dXJlW10+PignQHByb2R1Y3RfYWpheC9nZXRGZWF0dXJlT3B0aW9ucycpO1xuXG4gICAgZmVhdHVyZXMudmFsdWUgPSB1bmlxdWVJdGVtTGlzdChyZXMuZGF0YS5kYXRhKS5tYXAoKGZlYXR1cmUpID0+IHtcbiAgICAgIGZlYXR1cmUuY2hlY2tzID0gMDtcblxuICAgICAgcmV0dXJuIGZlYXR1cmU7XG4gICAgfSk7XG5cbiAgICBmb3IgKGNvbnN0IGZlYXR1cmUgb2YgZmVhdHVyZXMudmFsdWUpIHtcbiAgICAgIGxldCBpID0gMDtcbiAgICAgIGZvciAoY29uc3Qgb3B0aW9uIG9mIGZlYXR1cmUub3B0aW9ucykge1xuICAgICAgICBvcHRpb24uY2hlY2tlZCA9IGN1cnJlbnRPcHRpb25VaWRzLnZhbHVlLmluY2x1ZGVzKG9wdGlvbi51aWQpO1xuXG4gICAgICAgIGlmIChvcHRpb24uY2hlY2tlZCkge1xuICAgICAgICAgIGkrKztcbiAgICAgICAgfVxuICAgICAgfVxuXG4gICAgICBmZWF0dXJlLmNoZWNrcyA9IGk7XG4gICAgfVxuICB9IGZpbmFsbHkge1xuICAgIGxvYWRpbmdHZXRGZWF0dXJlT3B0aW9ucy52YWx1ZSA9IGZhbHNlO1xuICB9XG59XG5cbmNvbnN0IGNvbWJpbmF0aW9uQ291bnQgPSBjb21wdXRlZCgoKSA9PiB7XG4gIHJldHVybiBmZWF0dXJlcy52YWx1ZS5yZWR1Y2UoKGNhcnJ5LCBmZWF0dXJlKSA9PiB7XG4gICAgcmV0dXJuIGZlYXR1cmUuY2hlY2tzID4gMCA/IGNhcnJ5ICogZmVhdHVyZS5jaGVja3MgOiBjYXJyeTtcbiAgfSwgMSk7XG59KTtcblxuYXN5bmMgZnVuY3Rpb24gc2F2ZUdlbmVyYXRlKCkge1xuICAvLyBQcmV2ZW50IHRvbyBtYW55IHNlbGVjdGVkXG4gIGlmIChjb21iaW5hdGlvbkNvdW50LnZhbHVlID49IHZhcmlhbnRzTGltaXQpIHtcbiAgICBzaW1wbGVBbGVydChcbiAgICAgIF9fKCdzaG9wZ28ucHJvZHVjdC5tZXNzYWdlLnRvby5tYW55LmZlYXR1cmVzLnNlbGVjdGVkJywgY29tYmluYXRpb25Db3VudC52YWx1ZSwgdmFyaWFudHNMaW1pdCksXG4gICAgICAnJyxcbiAgICAgICd3YXJuaW5nJ1xuICAgICk7XG4gICAgcmV0dXJuO1xuICB9XG5cbiAgbG9hZGluZ0dlbmVyYXRpbmcudmFsdWUgPSB0cnVlO1xuXG4gIGNvbnN0IHsgcG9zdCB9ID0gYXdhaXQgdXNlSHR0cENsaWVudCgpO1xuXG4gIHRyeSB7XG4gICAgY29uc3QgcmVzID0gYXdhaXQgcG9zdChcbiAgICAgICdAcHJvZHVjdF9hamF4L2dlbmVyYXRlVmFyaWFudHMnLFxuICAgICAge1xuICAgICAgICBwcm9kdWN0X2lkOiBwcm9kdWN0Py5pZCxcbiAgICAgICAgb3B0aW9uczogZ2V0Q2hlY2tlZE9wdGlvbkdyb3VwKCksXG4gICAgICAgIGN1cnJlbnRIYXNoZXM6IGN1cnJlbnRIYXNoZXMudmFsdWVcbiAgICAgIH1cbiAgICApO1xuXG4gICAgY29uc3QgdmFyaWFudHMgPSByZXMuZGF0YS5kYXRhO1xuXG4gICAgZm9yIChjb25zdCB2YXJpYW50IG9mIHZhcmlhbnRzKSB7XG4gICAgICB2YXJpYW50LnByaWNlID0gTnVtYmVyKG1haW5QcmljZS52YWx1ZSk7XG4gICAgfVxuXG4gICAgZW1pdCgnZ2VuZXJhdGVkJywgdmFyaWFudHMpO1xuICB9IGZpbmFsbHkge1xuICAgIGxvYWRpbmdHZW5lcmF0aW5nLnZhbHVlID0gZmFsc2U7XG4gIH1cbn1cblxuZnVuY3Rpb24gZ2V0Q2hlY2tlZE9wdGlvbkdyb3VwKCkge1xuICBjb25zdCBkYXRhOiBSZWNvcmQ8c3RyaW5nLCBMaXN0T3B0aW9uW10+ID0ge307XG5cbiAgZm9yIChjb25zdCBmZWF0dXJlIG9mIGZlYXR1cmVzLnZhbHVlKSB7XG4gICAgY29uc3Qgb3B0aW9ucyA9IGZlYXR1cmUub3B0aW9uc1xuICAgICAgLmZpbHRlcihvcHRpb24gPT4gb3B0aW9uLmNoZWNrZWQpO1xuXG4gICAgaWYgKG9wdGlvbnMubGVuZ3RoID4gMCkge1xuICAgICAgZGF0YVtmZWF0dXJlLmlkLnRvU3RyaW5nKCldID0gb3B0aW9ucztcbiAgICB9XG4gIH1cblxuICByZXR1cm4gZGF0YTtcbn1cblxuLy8gZnVuY3Rpb24gc29ydE9wdGlvbkdyb3VwcyhmZWF0dXJlT3B0R3JvdXBzLCBwYXJlbnRHcm91cCA9IFtdKSB7XG4vLyAgICAgZmVhdHVyZU9wdEdyb3VwcyA9IFsuLi5mZWF0dXJlT3B0R3JvdXBzXTtcbi8vICAgICBjb25zdCBjdXJyZW50T3B0aW9ucyA9IGZlYXR1cmVPcHRHcm91cHMucG9wKCk7XG4vL1xuLy8gICAgIGxldCByZXR1cm5WYWx1ZSA9IFtdO1xuLy9cbi8vICAgICBmb3IgKGNvbnN0IG9wdGlvbiBvZiBjdXJyZW50T3B0aW9ucykge1xuLy8gICAgICAgICBjb25zdCBncm91cCA9IFsuLi5wYXJlbnRHcm91cF07XG4vL1xuLy8gICAgICAgICBncm91cC5wdXNoKG9wdGlvbik7XG4vL1xuLy8gICAgICAgICBpZiAoZmVhdHVyZU9wdEdyb3Vwcy5sZW5ndGggPiAwKSB7XG4vLyAgICAgICAgICAgICByZXR1cm5WYWx1ZSA9IHJldHVyblZhbHVlLmNvbmNhdChzb3J0T3B0aW9uR3JvdXBzKGZlYXR1cmVPcHRHcm91cHMsIGdyb3VwKSk7XG4vLyAgICAgICAgIH0gZWxzZSB7XG4vLyAgICAgICAgICAgICByZXR1cm5WYWx1ZSA9IHJldHVyblZhbHVlLmNvbmNhdChbZ3JvdXBdKTtcbi8vICAgICAgICAgfVxuLy8gICAgIH1cbi8vXG4vLyAgICAgcmV0dXJuIHJldHVyblZhbHVlO1xuLy8gfVxuXG5mdW5jdGlvbiBmZWF0dXJlQ2hlY2tib3hDaGFuZ2VkKGZlYXR1cmU6IFByb2R1Y3RGZWF0dXJlLCAkZXZlbnQ6IEV2ZW50KSB7XG4gIGNvbnN0IHRhcmdldCA9ICRldmVudC50YXJnZXQgYXMgSFRNTElucHV0RWxlbWVudDtcblxuICBmZWF0dXJlLm9wdGlvbnMuZm9yRWFjaChvcHRpb24gPT4gb3B0aW9uLmNoZWNrZWQgPSB0YXJnZXQuY2hlY2tlZCk7XG4gIGZlYXR1cmUuY2hlY2tzID0gdGFyZ2V0LmNoZWNrZWQgPyBmZWF0dXJlLm9wdGlvbnMubGVuZ3RoIDogMDtcbn1cblxuZnVuY3Rpb24gb3B0aW9uQ2hlY2tib3hDaGFuZ2VkKGZlYXR1cmU6IGFueSwgX29wdGlvbjogYW55KSB7XG4gIGZlYXR1cmUuY2hlY2tzID0gMDtcblxuICBmZWF0dXJlLm9wdGlvbnMuZm9yRWFjaCgob3B0aW9uOiBhbnkpID0+IHtcbiAgICBpZiAob3B0aW9uLmNoZWNrZWQpIHtcbiAgICAgIGZlYXR1cmUuY2hlY2tzKys7XG4gICAgfVxuICB9KTtcbn1cblxuZnVuY3Rpb24gY2FuY2VsKCkge1xuICBlbWl0KCdjYW5jZWwnKTtcbn1cbjwvc2NyaXB0PlxuXG48dGVtcGxhdGU+XG4gIDxkaXYgY2xhc3M9XCJjLXZhcmlhbnQtZ2VuZXJhdGUgY2FyZCBzdGlja3ktdG9wXCI+XG4gICAgPGRpdiBjbGFzcz1cImNhcmQtaGVhZGVyIGQtZmxleFwiPlxuICAgICAgPGRpdiBjbGFzcz1cImMtdmFyaWFudC1nZW5lcmF0ZV9fdGl0bGVcIj5cbiAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LnZhcmlhbnQuZ2VuZXJhdGlvbi50aXRsZScpIH19ICh7eyBjb21iaW5hdGlvbkNvdW50IHx8IDAgfX0pXG4gICAgICA8L2Rpdj5cbiAgICAgIDxkaXYgY2xhc3M9XCJjLXZhcmlhbnQtZ2VuZXJhdGVfX2FjdGlvbnMgbXMtYXV0b1wiPlxuICAgICAgICA8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImJ0biBidG4tcHJpbWFyeSBidG4tc21cIlxuICAgICAgICAgIEBjbGljaz1cInNhdmVHZW5lcmF0ZVwiIDpkaXNhYmxlZD1cImxvYWRpbmdHZW5lcmF0aW5nXCI+XG4gICAgICAgICAgPHNwYW4gY2xhc3M9XCJmYSBmYS1zYXZlXCI+PC9zcGFuPlxuICAgICAgICAgIHt7IGxvYWRpbmdHZW5lcmF0aW5nID8gJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LnRleHQuc2F2aW5nJykgOiAkbGFuZygnc2hvcGdvLnByb2R1Y3QudmFyaWFudC5nZW5lcmF0aW9uLmJ1dHRvbi5zdWJtaXQnKSB9fVxuICAgICAgICA8L2J1dHRvbj5cbiAgICAgICAgPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJidG4gYnRuLW91dGxpbmUtc2Vjb25kYXJ5IGJ0bi1zbVwiXG4gICAgICAgICAgQGNsaWNrPVwiY2FuY2VsXCIgOmRpc2FibGVkPVwibG9hZGluZ0dlbmVyYXRpbmdcIj5cbiAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLXRpbWVzXCI+PC9zcGFuPlxuICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5idXR0b24uY2FuY2VsJykgfX1cbiAgICAgICAgPC9idXR0b24+XG4gICAgICA8L2Rpdj5cbiAgICA8L2Rpdj5cblxuICAgIDxkaXYgdi1pZj1cIiFsb2FkaW5nR2V0RmVhdHVyZU9wdGlvbnNcIiBjbGFzcz1cImMtZmVhdHVyZS1saXN0IGxpc3QtZ3JvdXAgbGlzdC1ncm91cC1mbHVzaFwiPlxuICAgICAgPGRpdiB2LWZvcj1cImZlYXR1cmUgb2YgZmVhdHVyZXNcIiBjbGFzcz1cImMtZmVhdHVyZS1pdGVtIGxpc3QtZ3JvdXAtaXRlbVwiPlxuICAgICAgICA8IS0tIEZlYXR1cmUgVGl0bGUtLT5cbiAgICAgICAgPGg0IGNsYXNzPVwibWItMyBoNVwiPlxuICAgICAgICAgICAgICAgIDxzcGFuPlxuICAgICAgICAgICAgICAgICAgICA8aW5wdXQgdHlwZT1cImNoZWNrYm94XCIgOmNoZWNrZWQ9XCJmZWF0dXJlLm9wdGlvbnMubGVuZ3RoID09PSBmZWF0dXJlLmNoZWNrc1wiXG4gICAgICAgICAgICAgICAgICAgICAgOmlkPVwiJ2lucHV0LWZlYXR1cmUtJyArIGZlYXR1cmUuaWRcIlxuICAgICAgICAgICAgICAgICAgICAgIGNsYXNzPVwiZm9ybS1jaGVjay1pbnB1dFwiXG4gICAgICAgICAgICAgICAgICAgICAgOmluZGV0ZXJtaW5hdGUucHJvcD1cImZlYXR1cmUuY2hlY2tzICE9PSAwICYmIGZlYXR1cmUub3B0aW9ucy5sZW5ndGggPiBmZWF0dXJlLmNoZWNrc1wiXG4gICAgICAgICAgICAgICAgICAgICAgQGNoYW5nZT1cImZlYXR1cmVDaGVja2JveENoYW5nZWQoZmVhdHVyZSwgJGV2ZW50KVwiIC8+XG4gICAgICAgICAgICAgICAgPC9zcGFuPlxuICAgICAgICAgIDxsYWJlbCA6Zm9yPVwiJ2lucHV0LWZlYXR1cmUtJyArIGZlYXR1cmUuaWRcIj5cbiAgICAgICAgICAgIHt7IGZlYXR1cmUudGl0bGUgfX1cbiAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICA8L2g0PlxuXG4gICAgICAgIDwhLS0gRmVhdHVyZSBPcHRpb25zIC0tPlxuICAgICAgICA8ZGl2IGNsYXNzPVwiYy1vcHRpb24tbGlzdCByb3dcIj5cblxuICAgICAgICAgIDwhLS0gRmVhdHVyZSBPcHRpb24gSXRlbSAtLT5cbiAgICAgICAgICA8ZGl2IHYtZm9yPVwib3B0aW9uIG9mIGZlYXR1cmUub3B0aW9uc1wiIGNsYXNzPVwiYy1vcHRpb24taXRlbSBjb2wtbWQtNCBjb2wtNlwiPlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImMtb3B0aW9uLWl0ZW1fX2lucHV0LXdyYXBwZXIgZm9ybS1jaGVja1wiPlxuXG4gICAgICAgICAgICAgIDwhLS0gRmVhdHVyZSBJbnB1dCAtLT5cbiAgICAgICAgICAgICAgPGlucHV0IDppZD1cIidpbnB1dC1vcHRpb24tJyArIG9wdGlvbi51aWRcIiB0eXBlPVwiY2hlY2tib3hcIlxuICAgICAgICAgICAgICAgIDp2YWx1ZT1cIm9wdGlvbi51aWRcIlxuICAgICAgICAgICAgICAgIDpuYW1lPVwiYG9wdGlvbnNbJHtmZWF0dXJlLmlkfV1bJHtvcHRpb24udWlkfV1gXCJcbiAgICAgICAgICAgICAgICBjbGFzcz1cImZvcm0tY2hlY2staW5wdXRcIlxuICAgICAgICAgICAgICAgIHYtbW9kZWw9XCJvcHRpb24uY2hlY2tlZFwiXG4gICAgICAgICAgICAgICAgQGNoYW5nZT1cIm9wdGlvbkNoZWNrYm94Q2hhbmdlZChmZWF0dXJlLCBvcHRpb24pXCIgLz5cblxuICAgICAgICAgICAgICA8IS0tIEZlYXR1cmUgTGFiZWwgLS0+XG4gICAgICAgICAgICAgIDxsYWJlbCA6Zm9yPVwiJ2lucHV0LW9wdGlvbi0nICsgb3B0aW9uLnVpZFwiIGNsYXNzPVwiZm9ybS1jaGVjay1sYWJlbCBkLWZsZXggYWxpZ24taXRlbXMtY2VudGVyXCI+XG4gICAgICAgICAgICAgICAgPHNwYW4gdi1pZj1cImZlYXR1cmUudHlwZSA9PT0gJ2NvbG9yJ1wiXG4gICAgICAgICAgICAgICAgICBjbGFzcz1cInJvdW5kZWQgbWUtMlwiXG4gICAgICAgICAgICAgICAgICBzdHlsZT1cIndpZHRoOiAyMHB4OyBoZWlnaHQ6IDIwcHg7XCJcbiAgICAgICAgICAgICAgICAgIDpzdHlsZT1cInsnYmFja2dyb3VuZC1jb2xvcic6IG9wdGlvbi5jb2xvcn1cIlxuICAgICAgICAgICAgICAgID48L3NwYW4+XG4gICAgICAgICAgICAgICAgPHNwYW4+XG4gICAgICAgICAgICAgICAgICB7eyBvcHRpb24udGV4dCB9fVxuICAgICAgICAgICAgICAgIDwvc3Bhbj5cbiAgICAgICAgICAgICAgPC9sYWJlbD5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICA8L2Rpdj5cbiAgICAgIDwvZGl2PlxuXG4gICAgICA8ZGl2IGNsYXNzPVwibGlzdC1ncm91cC1pdGVtXCI+XG4gICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1wcmltYXJ5IGJ0bi1zbSB3LTEwMFwiXG4gICAgICAgICAgQGNsaWNrPVwic2F2ZUdlbmVyYXRlXCIgOmRpc2FibGVkPVwibG9hZGluZ0dlbmVyYXRpbmdcIj5cbiAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLXNhdmVcIj48L3NwYW4+XG4gICAgICAgICAge3sgbG9hZGluZ0dlbmVyYXRpbmcgPyAkbGFuZygnc2hvcGdvLnByb2R1Y3QudGV4dC5zYXZpbmcnKSA6ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC52YXJpYW50LmdlbmVyYXRpb24uYnV0dG9uLnN1Ym1pdCcpIH19XG4gICAgICAgIDwvYnV0dG9uPlxuICAgICAgPC9kaXY+XG4gICAgPC9kaXY+XG4gICAgPGRpdiB2LWVsc2UgY2xhc3M9XCJ0ZXh0LWNlbnRlciBjYXJkLWJvZHlcIj5cbiAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC50ZXh0LmxvYWRpbmcnKSB9fVxuICAgIDwvZGl2PlxuICA8L2Rpdj5cbjwvdGVtcGxhdGU+XG5cbjxzdHlsZSBzY29wZWQ+XG5cbjwvc3R5bGU+XG4iLCI8c2NyaXB0IHNldHVwIGxhbmc9XCJ0c1wiPlxuaW1wb3J0IHsgZGF0YSwgcm91dGUsIHVzZVN0YWNrIH0gZnJvbSAnQHdpbmR3YWxrZXItaW8vdW5pY29ybi1uZXh0JztcbmltcG9ydCB7IFNvcnRhYmxlT3B0aW9ucyB9IGZyb20gJ3NvcnRhYmxlanMnO1xuaW1wb3J0IHsgTWQ1IH0gZnJvbSAndHMtbWQ1JztcbmltcG9ydCB7IHJlZiwgY29tcHV0ZWQsIHdhdGNoIH0gZnJvbSAndnVlJztcbmltcG9ydCB7IFZ1ZURyYWdnYWJsZSB9IGZyb20gJ3Z1ZS1kcmFnZ2FibGUtcGx1cyc7XG5pbXBvcnQgeyBQcm9kdWN0VmFyaWFudCB9IGZyb20gJ35zaG9wZ28vdHlwZXMnO1xuaW1wb3J0IHsgbWVyZ2VSZWN1cnNpdmUgfSBmcm9tICd+c2hvcGdvL3V0aWxpdGllcyc7XG5pbXBvcnQgeyBNdWx0aVVwbG9hZGVyLCBJdGVtQ2FyZCwgSXRlbUNhcmRQbGFjZWhvbGRlcn0gZnJvbSAndnVlLW11bHRpLXVwbG9hZGVyJztcblxuY29uc3QgcHJvcHMgPSBkZWZpbmVQcm9wczx7XG4gIHZhcmlhbnRzOiBQcm9kdWN0VmFyaWFudFtdO1xufT4oKTtcblxuY29uc3QgZW1pdCA9IGRlZmluZUVtaXRzPHtcbiAgY2FuY2VsOiBbXTtcbn0+KClcblxuY29uc3QgY3VycmVudCA9IHJlZjxQcm9kdWN0VmFyaWFudCB8IG51bGw+KG51bGwpO1xuY29uc3QgaXRlbXMgPSByZWY8UHJvZHVjdFZhcmlhbnRbXT4oW10pO1xuY29uc3QgY3VycmVudEhhc2ggPSByZWYoJycpO1xuY29uc3QgZmxhdHBpY2tyT3B0aW9ucyA9IHJlZjxzdHJpbmc+KFxuICBKU09OLnN0cmluZ2lmeShcbiAgICB7XG4gICAgICBkYXRlRm9ybWF0OiAnWS1tLWQgSDppOlMnLFxuICAgICAgZW5hYmxlVGltZTogdHJ1ZSxcbiAgICAgIGVuYWJsZVNlY29uZHM6IHRydWUsXG4gICAgICBhbGxvd0lucHV0OiB0cnVlLFxuICAgICAgdGltZV8yNGhyOiB0cnVlLFxuICAgICAgLy8gd3JhcDogdHJ1ZSxcbiAgICAgIG1vbnRoU2VsZWN0OiBmYWxzZSxcbiAgICB9XG4gIClcbik7XG5jb25zdCBzdGFjayA9IHVzZVN0YWNrKCd1cGxvYWRpbmcnKTtcbmNvbnN0IGlucHV0U3RlcCA9IHJlZjxzdHJpbmc+KGRhdGEoJ2lucHV0LnN0ZXAnKSB8fCAnMC4wMDAxJyk7XG5cbndhdGNoKCgpID0+IHByb3BzLnZhcmlhbnRzLCAodikgPT4ge1xuICBsZXQgaXRlbTogYW55ID0ge1xuICAgIHNrdTogJycsXG4gICAgcHJpY2U6ICcnLFxuICAgIHN0b2NrUXVhbnRpdHk6ICcnLFxuICAgIHB1Ymxpc2hVcDogJycsXG4gICAgcHVibGlzaERvd246ICcnLFxuICAgIGltYWdlczogW10sXG4gICAgZGltZW5zaW9uOiB7XG4gICAgICB3aWR0aDogJycsXG4gICAgICBoZWlnaHQ6ICcnLFxuICAgICAgbGVuZ3RoOiAnJyxcbiAgICAgIHdlaWdodDogJycsXG4gICAgICB1bml0V2VpZ2h0OiAnJyxcbiAgICB9XG4gIH07XG4gIGl0ZW1zLnZhbHVlID0gcHJvcHMudmFyaWFudHM7XG5cbiAgaWYgKGl0ZW1zLnZhbHVlLmxlbmd0aCA9PT0gMSkge1xuICAgIGl0ZW0gPSBpdGVtcy52YWx1ZVswXTtcbiAgfVxuXG4gIGN1cnJlbnRIYXNoLnZhbHVlID0gaGFzaEl0ZW0oaXRlbSk7XG5cbiAgY3VycmVudC52YWx1ZSA9IGl0ZW07XG59LCB7IGltbWVkaWF0ZTogdHJ1ZSB9KTtcblxuZnVuY3Rpb24gaGFzaEl0ZW0oaXRlbTogUHJvZHVjdFZhcmlhbnQpIHtcbiAgY29uc3QgbmV3SXRlbSA9IHsgLi4uaXRlbSB9O1xuXG4gIGRlbGV0ZSBuZXdJdGVtLmNoZWNrZWQ7XG4gIGRlbGV0ZSBuZXdJdGVtLnVuc2F2ZTtcblxuICByZXR1cm4gTWQ1Lmhhc2hTdHIoSlNPTi5zdHJpbmdpZnkobmV3SXRlbSkpO1xufVxuXG5jb25zdCBpc011bHRpcGxlID0gY29tcHV0ZWQoKCkgPT4gaXRlbXMudmFsdWUubGVuZ3RoID4gMSk7XG4vLyBjb25zdCB1bnNhdmUgPSBjb21wdXRlZCgoKSA9PiBzdGF0ZS5vcmlnaW5Db3B5ICE9PSBKU09OLnN0cmluZ2lmeShzdGF0ZS5jdXJyZW50KSk7XG5cbndhdGNoKCgpID0+IGN1cnJlbnQudmFsdWUsICgpID0+IHtcbiAgaWYgKGN1cnJlbnRIYXNoLnZhbHVlICE9PSAnJyAmJiBjdXJyZW50SGFzaC52YWx1ZSAhPT0gaGFzaEl0ZW0oY3VycmVudC52YWx1ZSEpKSB7XG4gICAgdXBkYXRlVW5zYXZlcygpO1xuICB9XG59LCB7IGRlZXA6IHRydWUgfSk7XG5cbndhdGNoKCgpID0+IGN1cnJlbnQudmFsdWU/LnByaWNlLCAodikgPT4ge1xuICBpZiAoIWN1cnJlbnQudmFsdWUpIHtcbiAgICByZXR1cm47XG4gIH1cblxuICBpZiAodiAhPSBudWxsICYmIHYgPCAwKSB7XG4gICAgY3VycmVudC52YWx1ZS5wcmljZSA9IDA7XG4gIH1cbn0pO1xuXG5mdW5jdGlvbiB1cGRhdGVVbnNhdmVzKCkge1xuICBpZiAoIWN1cnJlbnQudmFsdWUpIHtcbiAgICByZXR1cm47XG4gIH1cblxuICBpZiAoIWlzTXVsdGlwbGUudmFsdWUpIHtcbiAgICBjdXJyZW50LnZhbHVlLmNvdmVyID0gY3VycmVudC52YWx1ZS5pbWFnZXNbMF0/LnVybCB8fCAnJztcbiAgICBpdGVtcy52YWx1ZVswXS51bnNhdmUgPSB0cnVlO1xuICB9IGVsc2Uge1xuICAgIGZvciAoY29uc3QgaXRlbSBvZiBpdGVtcy52YWx1ZSkge1xuICAgICAgbWVyZ2VSZWN1cnNpdmUoXG4gICAgICAgIGl0ZW0sXG4gICAgICAgIGN1cnJlbnQudmFsdWUsXG4gICAgICApO1xuXG4gICAgICBpdGVtLnVuc2F2ZSA9IHRydWU7XG4gICAgfVxuICB9XG59XG5cbmZ1bmN0aW9uIGNhbmNlbEVkaXQoKSB7XG4gIGVtaXQoJ2NhbmNlbCcpO1xufVxuXG5mdW5jdGlvbiBnZXRJbWFnZVVwbG9hZGVyVXJsKCkge1xuICByZXR1cm4gcm91dGUoJ2ZpbGVfdXBsb2FkJywgeyBwcm9maWxlOiAnaW1hZ2UnIH0pO1xufVxuXG5jb25zdCBkcmFnZ2FibGVPcHRpb25zOiBTb3J0YWJsZU9wdGlvbnMgPSB7XG4gIGhhbmRsZTogJy5pdGVtJyxcbiAgYW5pbWF0aW9uOiAxNTAsXG59O1xuPC9zY3JpcHQ+XG5cbjx0ZW1wbGF0ZT5cbiAgPGRpdiBjbGFzcz1cImMtdmFyaWFudC1lZGl0IGNhcmRcIj5cbiAgICA8ZGl2IGNsYXNzPVwiY2FyZC1oZWFkZXIgZC1mbGV4IGFsaWduLWl0ZW1zLWNlbnRlclwiPlxuICAgICAgPGRpdiBjbGFzcz1cImMtdmFyaWFudC1lZGl0X190aXRsZSBkLWZsZXggZ2FwLTJcIj5cbiAgICAgICAgPGRpdj5cbiAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLnByb2R1Y3QudmFyaWFudC5lZGl0LnRpdGxlJykgfX1cbiAgICAgICAgPC9kaXY+XG4gICAgICA8L2Rpdj5cbiAgICAgIDxkaXYgY2xhc3M9XCJjLXZhcmlhbnQtZWRpdF9fYWN0aW9ucyBtcy1hdXRvXCI+XG4gICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1vdXRsaW5lLXNlY29uZGFyeSBidG4tc21cIlxuICAgICAgICAgIEBjbGljaz1cImNhbmNlbEVkaXRcIj5cbiAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLXRpbWVzXCI+PC9zcGFuPlxuICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5idXR0b24uY2FuY2VsJykgfX1cbiAgICAgICAgPC9idXR0b24+XG4gICAgICA8L2Rpdj5cbiAgICA8L2Rpdj5cbiAgICA8ZGl2IHYtaWY9XCJjdXJyZW50XCIgY2xhc3M9XCJjYXJkLWJvZHlcIj5cbiAgICAgIDxkaXYgY2xhc3M9XCJjLXZhcmlhbnQtZWRpdF9fdGl0bGUgbWItNFwiPlxuICAgICAgICA8c3BhblxuICAgICAgICAgIGNsYXNzPVwibGVhZFwiPnt7IGl0ZW1zLmxlbmd0aCA8PSAxID8gY3VycmVudC50aXRsZSA6ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC52YXJpYW50LmVkaXQubXVsdGlwbGUnKSB9fTwvc3Bhbj5cbiAgICAgIDwvZGl2PlxuXG4gICAgICA8IS0tICAgICAgICA8ZGl2IGNsYXNzPVwiZC1mbGV4IG1iLTIgYWxpZ24taXRlbXMtY2VudGVyXCIgdi1pZj1cIml0ZW1zLmxlbmd0aCA8PSAxXCI+LS0+XG4gICAgICA8IS0tICAgICAgICAgICAgPGxhYmVsIGZvcj1cImlucHV0LXZhcmlhbnQtZGVmYXVsdFwiIGNsYXNzPVwibXItMlwiPuioreeCuumgkOiorTwvbGFiZWw+LS0+XG4gICAgICA8IS0tICAgICAgICAgICAgPHBob2VuaXgtc3dpdGNoIG5hbWU9XCJkZWZhdWx0XCIgdi1tb2RlbD1cImN1cnJlbnQuZGVmYXVsdFwiIHNpemU9XCJzbVwiLS0+XG4gICAgICAgIDwhLS0gICAgICAgICAgICAgICAgdHJ1ZS12YWx1ZT1cIjFcIi0tPlxuICAgICAgICA8IS0tICAgICAgICAgICAgICAgIGZhbHNlLXZhbHVlPVwiMFwiLS0+XG4gICAgICAgIDwhLS0gICAgICAgICAgICAgICAgc2hhcGU9XCJjaXJjbGVcIj48L3Bob2VuaXgtc3dpdGNoPi0tPlxuICAgICAgPCEtLSAgICAgICAgPC9kaXY+LS0+XG5cbiAgICAgIDxkaXYgY2xhc3M9XCJkLWZsZXggZ2FwLTJcIj5cbiAgICAgICAgPCEtLSAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIG1iLTRcIiB2LWlmPVwiaXRlbXMubGVuZ3RoIDw9IDFcIj4tLT5cbiAgICAgICAgPCEtLSAgICAgICAgICAgICAgICA8bGFiZWwgZm9yPVwiaW5wdXQtdmFyaWFudC1tb2RlbFwiPuWei+iZnzwvbGFiZWw+LS0+XG4gICAgICAgIDwhLS0gICAgICAgICAgICAgICAgPGlucHV0IGlkPVwiaW5wdXQtdmFyaWFudC1tb2RlbFwiIHR5cGU9XCJ0ZXh0XCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIi0tPlxuICAgICAgICAgIDwhLS0gICAgICAgICAgICAgICAgICAgIHYtbW9kZWw9XCJjdXJyZW50Lm1vZGVsXCIgLz4tLT5cbiAgICAgICAgPCEtLSAgICAgICAgICAgIDwvZGl2Pi0tPlxuICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBtYi00XCIgdi1pZj1cIml0ZW1zLmxlbmd0aCA8PSAxXCI+XG4gICAgICAgICAgPGxhYmVsIGZvcj1cImlucHV0LXZhcmlhbnQtc2t1XCIgY2xhc3M9XCJmb3JtLWxhYmVsXCI+XG4gICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLnByb2R1Y3QuZmllbGQuc2t1JykgfX1cbiAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgIDx0ZXh0YXJlYSBpZD1cImlucHV0LXZhcmlhbnQtc2t1XCIgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiXG4gICAgICAgICAgICB2LW1vZGVsPVwiY3VycmVudC5za3VcIiByb3dzPVwiMVwiPjwvdGV4dGFyZWE+XG4gICAgICAgIDwvZGl2PlxuXG4gICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIG1iLTRcIj5cbiAgICAgICAgICA8bGFiZWwgZm9yPVwiaW5wdXQtdmFyaWFudC1wcmljZVwiIGNsYXNzPVwiZm9ybS1sYWJlbFwiPlxuICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LmZpZWxkLnByaWNlJykgfX1cbiAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgIDxpbnB1dCBpZD1cImlucHV0LXZhcmlhbnQtcHJpY2VcIiB0eXBlPVwibnVtYmVyXCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIlxuICAgICAgICAgICAgdi1tb2RlbD1cImN1cnJlbnQucHJpY2VcIlxuICAgICAgICAgICAgbWluPVwiMFwiXG4gICAgICAgICAgICA6c3RlcD1cImlucHV0U3RlcFwiXG4gICAgICAgICAgLz5cbiAgICAgICAgPC9kaXY+XG4gICAgICA8L2Rpdj5cblxuICAgICAgPGRpdiBjbGFzcz1cImQtZmxleCBnYXAtMlwiPlxuICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBtYi00XCI+XG4gICAgICAgICAgPGxhYmVsIGZvcj1cImlucHV0LXZhcmlhbnQtbGVuZ3RoXCIgY2xhc3M9XCJmb3JtLWxhYmVsXCI+XG4gICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLnByb2R1Y3QuZmllbGQubGVuZ3RoJykgfX1cbiAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgIDxpbnB1dCBpZD1cImlucHV0LXZhcmlhbnQtbGVuZ3RoXCIgdHlwZT1cIm51bWJlclwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCJcbiAgICAgICAgICAgIHYtbW9kZWw9XCJjdXJyZW50LmRpbWVuc2lvbi5sZW5ndGhcIlxuICAgICAgICAgICAgbWluPVwiMFwiXG4gICAgICAgICAgLz5cbiAgICAgICAgPC9kaXY+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIG1iLTRcIj5cbiAgICAgICAgICA8bGFiZWwgZm9yPVwiaW5wdXQtdmFyaWFudC13aWR0aFwiIGNsYXNzPVwiZm9ybS1sYWJlbFwiPlxuICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LmZpZWxkLndpZHRoJykgfX1cbiAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgIDxpbnB1dCBpZD1cImlucHV0LXZhcmlhbnQtd2lkdGhcIiB0eXBlPVwibnVtYmVyXCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIlxuICAgICAgICAgICAgdi1tb2RlbD1cImN1cnJlbnQuZGltZW5zaW9uLndpZHRoXCJcbiAgICAgICAgICAgIG1pbj1cIjBcIlxuICAgICAgICAgIC8+XG4gICAgICAgIDwvZGl2PlxuICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBtYi00XCI+XG4gICAgICAgICAgPGxhYmVsIGZvcj1cImlucHV0LXZhcmlhbnQtaGVpZ2h0XCIgY2xhc3M9XCJmb3JtLWxhYmVsXCI+XG4gICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLnByb2R1Y3QuZmllbGQuaGVpZ2h0JykgfX1cbiAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgIDxpbnB1dCBpZD1cImlucHV0LXZhcmlhbnQtaGVpZ2h0XCIgdHlwZT1cIm51bWJlclwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCJcbiAgICAgICAgICAgIHYtbW9kZWw9XCJjdXJyZW50LmRpbWVuc2lvbi5oZWlnaHRcIlxuICAgICAgICAgICAgbWluPVwiMFwiXG4gICAgICAgICAgLz5cbiAgICAgICAgPC9kaXY+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIG1iLTRcIj5cbiAgICAgICAgICA8bGFiZWwgZm9yPVwiaW5wdXQtdmFyaWFudC13ZWlnaHRcIiBjbGFzcz1cImZvcm0tbGFiZWxcIj5cbiAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5maWVsZC53ZWlnaHQnKSB9fVxuICAgICAgICAgIDwvbGFiZWw+XG4gICAgICAgICAgPGlucHV0IGlkPVwiaW5wdXQtdmFyaWFudC13ZWlnaHRcIiB0eXBlPVwibnVtYmVyXCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIlxuICAgICAgICAgICAgdi1tb2RlbD1cImN1cnJlbnQuZGltZW5zaW9uLndlaWdodFwiXG4gICAgICAgICAgICBtaW49XCIwXCJcbiAgICAgICAgICAvPlxuICAgICAgICA8L2Rpdj5cbiAgICAgIDwvZGl2PlxuXG4gICAgICA8ZGl2IGNsYXNzPVwiZC1mbGV4IGdhcC0yXCI+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIG1iLTRcIj5cbiAgICAgICAgICA8bGFiZWwgZm9yPVwiaW5wdXQtdmFyaWFudC1pbnZlbnRvcnlcIiBjbGFzcz1cImZvcm0tbGFiZWxcIj5cbiAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5maWVsZC5zdG9jay5xdWFudGl0eScpIH19XG4gICAgICAgICAgPC9sYWJlbD5cbiAgICAgICAgICA8aW5wdXQgaWQ9XCJpbnB1dC12YXJpYW50LWludmVudG9yeVwiIHR5cGU9XCJudW1iZXJcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiXG4gICAgICAgICAgICB2LW1vZGVsPVwiY3VycmVudC5zdG9ja1F1YW50aXR5XCIgbWluPVwiMFwiIC8+XG4gICAgICAgIDwvZGl2PlxuICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBtYi00XCI+XG4gICAgICAgICAgPGxhYmVsIGZvcj1cImlucHV0LXZhcmlhbnQtc3VidHJhY3RcIiBjbGFzcz1cImZvcm0tbGFiZWxcIj5cbiAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC5maWVsZC5zdWJ0cmFjdCcpIH19XG4gICAgICAgICAgPC9sYWJlbD5cbiAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1jaGVjayBmb3JtLXN3aXRjaFwiPlxuICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJjaGVja2JveFwiIGlkPVwiaW5wdXQtdmFyaWFudC1zdWJ0cmFjdFwiXG4gICAgICAgICAgICAgIGNsYXNzPVwiZm9ybS1jaGVjay1pbnB1dFwiXG4gICAgICAgICAgICAgIHYtbW9kZWw9XCJjdXJyZW50LnN1YnRyYWN0XCJcbiAgICAgICAgICAgICAgOnRydWUtdmFsdWU9XCJ0cnVlXCJcbiAgICAgICAgICAgICAgOmZhbHNlLXZhbHVlPVwiZmFsc2VcIlxuICAgICAgICAgICAgICByb2xlPVwic3dpdGNoXCJcbiAgICAgICAgICAgIC8+XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgIDwvZGl2PlxuICAgICAgPC9kaXY+XG5cbiAgICAgIDxkaXYgY2xhc3M9XCJ2YXJpYW50LWltYWdlcyBtdC00XCIgdi1pZj1cIml0ZW1zLmxlbmd0aCA8PSAxXCI+XG4gICAgICAgIDxNdWx0aVVwbG9hZGVyXG4gICAgICAgICAgOnVwbG9hZC11cmw9XCJnZXRJbWFnZVVwbG9hZGVyVXJsKClcIlxuICAgICAgICAgIHYtbW9kZWw9XCJjdXJyZW50LmltYWdlc1wiXG4gICAgICAgICAgOm9wdGlvbnM9XCJ7XG4gICAgICAgICAgICBtYXhGaWxlczogNixcbiAgICAgICAgICAgIGFjY2VwdDogJ2ltYWdlLyonLFxuICAgICAgICAgIH1cIlxuICAgICAgICAgIEB1cGxvYWRpbmc9XCJzdGFjay5wdXNoKHRydWUpXCJcbiAgICAgICAgICBAdXBsb2FkZWQ9XCJzdGFjay5wb3AoKVwiXG4gICAgICAgID5cbiAgICAgICAgICA8dGVtcGxhdGUgI2l0ZW1zPVwieyBpbnN0YW5jZSwgaW5zdGFuY2U6IHsgY2FuVXBsb2FkLCBvcGVuRmlsZVNlbGVjdG9yLCBkZWxldGVJdGVtIH0gfVwiPlxuICAgICAgICAgICAgPFZ1ZURyYWdnYWJsZSB2LW1vZGVsPVwiaW5zdGFuY2UuaXRlbXNcIiB2LWJpbmQ9XCJkcmFnZ2FibGVPcHRpb25zXCJcbiAgICAgICAgICAgICAgY2xhc3M9XCJkLWZsZXggZmxleC13cmFwIHctMTAwIGdhcC0zXCJcbiAgICAgICAgICAgID5cbiAgICAgICAgICAgICAgPEl0ZW1DYXJkIHYtZm9yPVwiKGl0ZW0sIGluZGV4KSBvZiBpbnN0YW5jZS5pdGVtc1wiXG4gICAgICAgICAgICAgICAgOmtleT1cIml0ZW0ua2V5XCJcbiAgICAgICAgICAgICAgICBjbGFzcz1cIml0ZW1cIlxuICAgICAgICAgICAgICAgIDppdGVtXG4gICAgICAgICAgICAgICAgOmk9XCJpbmRleFwiXG4gICAgICAgICAgICAgICAgQGRlbGV0ZT1cImRlbGV0ZUl0ZW1cIlxuICAgICAgICAgICAgICAvPlxuXG4gICAgICAgICAgICAgIDxJdGVtQ2FyZFBsYWNlaG9sZGVyXG4gICAgICAgICAgICAgICAgdi1pZj1cImNhblVwbG9hZFwiXG4gICAgICAgICAgICAgICAgY2xhc3M9XCJcIlxuICAgICAgICAgICAgICAgIHRleHQ9XCJVcGxvYWQgSW1hZ2VzXCJcbiAgICAgICAgICAgICAgICBAY2xpY2s9XCJvcGVuRmlsZVNlbGVjdG9yXCJcbiAgICAgICAgICAgICAgLz5cbiAgICAgICAgICAgIDwvVnVlRHJhZ2dhYmxlPlxuICAgICAgICAgIDwvdGVtcGxhdGU+XG4gICAgICAgIDwvTXVsdGlVcGxvYWRlcj5cbiAgICAgIDwvZGl2PlxuICAgIDwvZGl2PlxuICA8L2Rpdj5cbjwvdGVtcGxhdGU+XG5cbjxzdHlsZSBzY29wZWQ+XG5cbjwvc3R5bGU+XG4iLCI8c2NyaXB0IHNldHVwIGxhbmc9XCJ0c1wiPlxuaW1wb3J0IHsgZGF0YSB9IGZyb20gJ0B3aW5kd2Fsa2VyLWlvL3VuaWNvcm4tbmV4dCc7XG5pbXBvcnQgeyBpbmplY3QsIFJlZiwgY29tcHV0ZWQgfSBmcm9tICd2dWUnO1xuaW1wb3J0IHsgUHJvZHVjdFZhcmlhbnQgfSBmcm9tICd+c2hvcGdvL3R5cGVzJztcblxuY29uc3QgcHJvcHMgPSBkZWZpbmVQcm9wczx7XG4gIGl0ZW06IFByb2R1Y3RWYXJpYW50O1xuICBpOiBudW1iZXI7XG4gIGFjdGl2ZTogYm9vbGVhbjtcbn0+KCk7XG5cbmNvbnN0IGVtaXQgPSBkZWZpbmVFbWl0czx7XG4gICdlZGl0JzogW2l0ZW06IFByb2R1Y3RWYXJpYW50XTtcbiAgJ3JlbW92ZSc6IFtpdGVtOiBQcm9kdWN0VmFyaWFudF07XG4gICdvbmNoZWNrJzogW2V2ZW50OiBNb3VzZUV2ZW50LCBpbmRleDogbnVtYmVyXTtcbn0+KCk7XG5cbmNvbnN0IGRlZmF1bHRJbWFnZSA9IGRhdGE8c3RyaW5nPignZGVmYXVsdEltYWdlJykhO1xuXG5mdW5jdGlvbiBlZGl0KCkge1xuICBlbWl0KCdlZGl0JywgcHJvcHMuaXRlbSk7XG59XG5cbmZ1bmN0aW9uIHJlbW92ZSgpIHtcbiAgZW1pdCgncmVtb3ZlJywgcHJvcHMuaXRlbSk7XG59XG5cbmZ1bmN0aW9uIG11bHRpQ2hlY2soJGV2ZW50OiBNb3VzZUV2ZW50KSB7XG4gIGVtaXQoJ29uY2hlY2snLCAkZXZlbnQsIHByb3BzLmkpO1xufVxuXG5jb25zdCBtYWluUHJpY2UgPSBpbmplY3Q8UmVmPHN0cmluZz4+KCdtYWluUHJpY2UnKSE7XG5cbmNvbnN0IHByaWNlT2Zmc2V0ID0gY29tcHV0ZWQoKCkgPT4ge1xuICByZXR1cm4gTnVtYmVyKHByb3BzLml0ZW0ucHJpY2UpIC0gTnVtYmVyKG1haW5QcmljZS52YWx1ZSk7XG59KTtcbjwvc2NyaXB0PlxuXG48dGVtcGxhdGU+XG4gIDxkaXYgY2xhc3M9XCJsaXN0LWdyb3VwLWl0ZW0gYy12YXJpYW50LWl0ZW1cIlxuICAgIDpjbGFzcz1cInsgYWN0aXZlIH1cIj5cbiAgICA8ZGl2IGNsYXNzPVwibGlzdC1ncm91cC1pdGVtX193cmFwcGVyIGQtZmxleCBhbGlnbi1pdGVtcy1jZW50ZXIgZ2FwLTJcIj5cbiAgICAgIDxkaXYgY2xhc3M9XCJjLXZhcmlhbnQtaXRlbV9fY29udHJvbCBkLWZsZXggZmxleC1ub3dyYXBcIj5cbiAgICAgICAgPGlucHV0IHR5cGU9XCJjaGVja2JveFwiXG4gICAgICAgICAgY2xhc3M9XCJmb3JtLWNoZWNrLWlucHV0XCJcbiAgICAgICAgICA6Y2hlY2tlZD1cIml0ZW0uY2hlY2tlZFwiXG4gICAgICAgICAgQGNsaWNrPVwibXVsdGlDaGVja1wiIC8+XG4gICAgICA8L2Rpdj5cbiAgICAgIDxkaXYgY2xhc3M9XCJjLXZhcmlhbnQtaXRlbV9faW1hZ2VcIj5cbiAgICAgICAgPGltZyA6c3JjPVwiaXRlbS5jb3ZlciB8fCBkZWZhdWx0SW1hZ2VcIlxuICAgICAgICAgIHdpZHRoPVwiNDVcIiBoZWlnaHQ9XCI0NVwiIGFsdD1cIkNvdmVyXCIgY2xhc3M9XCJyb3VuZGVkXCI+XG4gICAgICA8L2Rpdj5cbiAgICAgIDxkaXYgY2xhc3M9XCJjLXZhcmlhbnQtaXRlbV9fdGl0bGUgZmxleC1maWxsIHRleHQtdHJ1bmNhdGVcIj5cbiAgICAgICAgPGRpdiBjbGFzcz1cInRleHQtdHJ1bmNhdGVcIiBzdHlsZT1cIm1heC13aWR0aDogMTAwJVwiPlxuICAgICAgICAgIHt7IGl0ZW0udGl0bGUgfX1cbiAgICAgICAgPC9kaXY+XG4gICAgICAgIDxkaXY+XG4gICAgICAgICAgICAgICAgPHNwYW4gdi1pZj1cIml0ZW0uc2t1XCJcbiAgICAgICAgICAgICAgICAgIHN0eWxlPVwib3BhY2l0eTogLjc1XCI+XG4gICAgICAgICAgICAgICAgICAgICN7eyBpdGVtLnNrdSB9fVxuICAgICAgICAgICAgICAgIDwvc3Bhbj5cblxuICAgICAgICAgIDxzcGFuIHYtaWY9XCJwcmljZU9mZnNldCAhPT0gMFwiXG4gICAgICAgICAgICBzdHlsZT1cIm9wYWNpdHk6IC43NVwiPlxuICAgICAgICAgICAgICAgICAgICB7eyAkb2Zmc2V0Rm9ybWF0KHByaWNlT2Zmc2V0LCAnJCcpIH19XG4gICAgICAgICAgICAgICAgPC9zcGFuPlxuXG4gICAgICAgICAgPHNwYW4gdi1pZj1cIml0ZW0udW5zYXZlXCJcbiAgICAgICAgICAgIGNsYXNzPVwiYmFkZ2UgYmctd2FybmluZ1wiPlxuICAgICAgICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLnByb2R1Y3QudGV4dC5zYXZlLnJlcXVpcmVkJykgfX1cbiAgICAgICAgICAgICAgICA8L3NwYW4+XG5cbiAgICAgICAgICA8IS0tICAgICAgICAgICAgICAgIDxzcGFuIHYtaWY9XCJpdGVtLmRlZmF1bHQgPT09ICcxJ1wiIGNsYXNzPVwiYmFkZ2UgYmFkZ2UtaW5mb1wiPi0tPlxuICAgICAgICAgIDwhLS0gICAgICAgICAgICAgICAgICAgIOmgkOiorS0tPlxuICAgICAgICAgIDwhLS0gICAgICAgICAgICAgICAgPC9zcGFuPi0tPlxuXG4gICAgICAgICAgPCEtLSAgICAgICAgICAgICAgICA8c3BhbiB2LWlmPVwiaXRlbS5zYXZpbmdcIiBjbGFzcz1cImJhZGdlIGJhZGdlLXdhcm5pbmdcIj4tLT5cbiAgICAgICAgICA8IS0tICAgICAgICAgICAgICAgICAgICDmm7TmlrDkuK0uLi4tLT5cbiAgICAgICAgICA8IS0tICAgICAgICAgICAgICAgIDwvc3Bhbj4tLT5cbiAgICAgICAgPC9kaXY+XG4gICAgICA8L2Rpdj5cbiAgICAgIDxkaXYgY2xhc3M9XCJjLXZhcmlhbnQtaXRlbV9faW52ZW50b3J5IHRleHQtZW5kXCI+XG4gICAgICAgIHt7ICRudW1iZXJGb3JtYXQoaXRlbS5zdG9ja1F1YW50aXR5KSB9fVxuICAgICAgPC9kaXY+XG4gICAgICA8ZGl2IGNsYXNzPVwiYy12YXJpYW50LWl0ZW1fX2FjdGlvbnMgZC1mbGV4IGZsZXgtbm93cmFwIGdhcC0xXCI+XG4gICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1zbSBidG4tbGlnaHQgYm9yZGVyLXNlY29uZGFyeVwiXG4gICAgICAgICAgQGNsaWNrPVwiZWRpdFwiIDpkaXNhYmxlZD1cIml0ZW0uc2F2aW5nXCI+XG4gICAgICAgICAgPHNwYW4gY2xhc3M9XCJmYSBmYS1wZW5jaWwtYWx0XCI+PC9zcGFuPlxuICAgICAgICA8L2J1dHRvbj5cbiAgICAgICAgPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJidG4gYnRuLXNtIGJ0bi1saWdodCBib3JkZXItc2Vjb25kYXJ5XCJcbiAgICAgICAgICBAY2xpY2s9XCJyZW1vdmVcIiA6ZGlzYWJsZWQ9XCJpdGVtLnNhdmluZ1wiPlxuICAgICAgICAgIDxzcGFuIGNsYXNzPVwiZmEgZmEtdHJhc2ggdGV4dC1kYW5nZXJcIj48L3NwYW4+XG4gICAgICAgIDwvYnV0dG9uPlxuICAgICAgPC9kaXY+XG4gICAgPC9kaXY+XG4gIDwvZGl2PlxuPC90ZW1wbGF0ZT5cblxuPHN0eWxlIHNjb3BlZD5cblxuPC9zdHlsZT5cbiIsIjxzY3JpcHQgc2V0dXAgbGFuZz1cInRzXCI+XG5cbmltcG9ydCB7IHVuaXF1ZUl0ZW1MaXN0IH0gZnJvbSAnQGx5cmFzb2Z0L3RzLXRvb2xraXQvdnVlJztcbmltcG9ydCB7IF9fLCBzaW1wbGVDb25maXJtIH0gZnJvbSAnQHdpbmR3YWxrZXItaW8vdW5pY29ybi1uZXh0JztcbmltcG9ydCB7IE1kNSB9IGZyb20gJ3RzLW1kNSc7XG5pbXBvcnQgeyBwcm92aWRlLCBjb21wdXRlZCwgcmVmIH0gZnJvbSAndnVlJztcbmltcG9ydCBWYXJpYW50R2VuZXJhdGlvbiBmcm9tICd+c2hvcGdvL21vZHVsZXMvcHJvZHVjdC1lZGl0L2NvbXBvbmVudHMvVmFyaWFudEdlbmVyYXRpb24udnVlJztcbmltcG9ydCBWYXJpYW50SW5mb0VkaXQgZnJvbSAnfnNob3Bnby9tb2R1bGVzL3Byb2R1Y3QtZWRpdC9jb21wb25lbnRzL1ZhcmlhbnRJbmZvRWRpdC52dWUnO1xuaW1wb3J0IFZhcmlhbnRMaXN0SXRlbSBmcm9tICd+c2hvcGdvL21vZHVsZXMvcHJvZHVjdC1lZGl0L2NvbXBvbmVudHMvVmFyaWFudExpc3RJdGVtLnZ1ZSc7XG5pbXBvcnQgeyBQcm9kdWN0LCBQcm9kdWN0VmFyaWFudCB9IGZyb20gJ35zaG9wZ28vdHlwZXMnO1xuXG5jb25zdCBwcm9wcyA9IGRlZmluZVByb3BzPHtcbiAgcHJvZHVjdDogUHJvZHVjdDtcbiAgdmFyaWFudHM6IFByb2R1Y3RWYXJpYW50W107XG59PigpO1xuXG5jb25zdCBwcmljZUlucHV0ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcjxIVE1MSW5wdXRFbGVtZW50PignI2lucHV0LWl0ZW0tdmFyaWFudC1wcmljZScpITtcbmNvbnN0IGZvcm0gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yPEhUTUxGb3JtRWxlbWVudD4oJyNhZG1pbi1mb3JtJykhO1xuXG4vLyBTcGxpdCBzdGF0ZSBpbnRvIHNlcGFyYXRlIHJlZnNcbmNvbnN0IGl0ZW1zID0gcmVmPFByb2R1Y3RWYXJpYW50W10+KHByZXBhcmVJdGVtcyhwcm9wcy52YXJpYW50cykpO1xuY29uc3QgZ2VuZXJhdGUgPSByZWYoe1xuICBlZGl0OiBmYWxzZVxufSk7XG5jb25zdCBsYXN0Q2hlY2tJdGVtSW5kZXggPSByZWY8bnVtYmVyIHwgbnVsbD4oMCk7XG5cbmNvbnN0IG1haW5QcmljZSA9IHJlZjxzdHJpbmc+KHBhcnNlRmxvYXQocHJpY2VJbnB1dC52YWx1ZSkudG9TdHJpbmcoKSk7XG5cbnByb3ZpZGUoJ3Byb2R1Y3QnLCBwcm9wcy5wcm9kdWN0IHx8IHt9KTtcbnByb3ZpZGUoJ21haW5QcmljZScsIG1haW5QcmljZSk7XG5cbnByaWNlSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcignY2hhbmdlJywgKCkgPT4ge1xuICBtYWluUHJpY2UudmFsdWUgPSBwYXJzZUZsb2F0KHByaWNlSW5wdXQudmFsdWUpLnRvU3RyaW5nKCk7XG59KTtcblxuLy8gVW5zYXZlXG5sZXQgZm9ybVN1Ym1pdHRpbmcgPSBmYWxzZTtcbmNvbnN0IGluaXRpYWxIYXNoID0gTWQ1Lmhhc2hTdHIoSlNPTi5zdHJpbmdpZnkoaXRlbXMudmFsdWUpKTtcbmNvbnN0IHNhdmVSZXF1aXJlZCA9IGNvbXB1dGVkKCgpID0+IE1kNS5oYXNoU3RyKGl0ZW1zSlNPTi52YWx1ZSkgIT09IGluaXRpYWxIYXNoKTtcblxud2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2JlZm9yZXVubG9hZCcsIChlKSA9PiB7XG4gIGlmIChzYXZlUmVxdWlyZWQudmFsdWUgJiYgIWZvcm1TdWJtaXR0aW5nKSB7XG4gICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgIGUuc3RvcFByb3BhZ2F0aW9uKCk7XG4gICAgZS5yZXR1cm5WYWx1ZSA9ICdTYXZlIFJlcXVpcmVkJztcblxuICAgIHJldHVybiAnU2F2ZSBSZXF1aXJlZCc7XG4gIH1cbn0pO1xuXG5mb3JtLmFkZEV2ZW50TGlzdGVuZXIoJ3N1Ym1pdCcsICgpID0+IHtcbiAgZm9ybVN1Ym1pdHRpbmcgPSB0cnVlO1xufSk7XG5cbmNvbnN0IGNoZWNrZWRJdGVtcyA9IGNvbXB1dGVkKCgpID0+IHtcbiAgcmV0dXJuIGl0ZW1zLnZhbHVlLmZpbHRlcigoaXRlbSkgPT4gaXRlbS5jaGVja2VkKTtcbn0pO1xuXG5mdW5jdGlvbiBwcmVwYXJlSXRlbXMoaXRlbXM6IFByb2R1Y3RWYXJpYW50W10pIHtcbiAgcmV0dXJuIHVuaXF1ZUl0ZW1MaXN0KGl0ZW1zKS5tYXAoKGl0ZW0pID0+IHtcbiAgICBpdGVtLmNoZWNrZWQgPSBmYWxzZTtcbiAgICBpdGVtLnVuc2F2ZSA9IGZhbHNlO1xuXG4gICAgcmV0dXJuIGl0ZW07XG4gIH0pO1xufVxuXG5mdW5jdGlvbiBjaGVja0FsbCgkZXZlbnQ/OiBFdmVudCwgdmFsdWU/OiBib29sZWFuKSB7XG4gIGNvbnN0IHRhcmdldCA9ICRldmVudD8udGFyZ2V0IGFzIEhUTUxJbnB1dEVsZW1lbnQgfCB1bmRlZmluZWQ7XG5cbiAgaXRlbXMudmFsdWUuZm9yRWFjaCgoaXRlbSkgPT4ge1xuICAgIGl0ZW0uY2hlY2tlZCA9IHZhbHVlID09IG51bGwgPyB0YXJnZXQ/LmNoZWNrZWQgOiB2YWx1ZTtcbiAgfSk7XG59XG5cbmFzeW5jIGZ1bmN0aW9uIG11bHRpQ2hlY2soJGV2ZW50OiBNb3VzZUV2ZW50LCBpOiBudW1iZXIpIHtcbiAgY29uc3QgdGFyZ2V0ID0gJGV2ZW50LnRhcmdldCBhcyBIVE1MSW5wdXRFbGVtZW50O1xuXG4gIGlmICgkZXZlbnQuc2hpZnRLZXkpIHtcbiAgICBsZXQgayA9IGxhc3RDaGVja0l0ZW1JbmRleC52YWx1ZSBhcyBudW1iZXI7XG5cbiAgICBpZiAoKGxhc3RDaGVja0l0ZW1JbmRleC52YWx1ZSBhcyBudW1iZXIpIDwgaSkge1xuICAgICAgZm9yICg7IGsgPCBpOyBrKyspIHtcbiAgICAgICAgaXRlbXMudmFsdWVba10uY2hlY2tlZCA9IHRhcmdldC5jaGVja2VkO1xuICAgICAgfVxuICAgIH0gZWxzZSB7XG4gICAgICBmb3IgKDsgayA+IGk7IGstLSkge1xuICAgICAgICBpdGVtcy52YWx1ZVtrXS5jaGVja2VkID0gdGFyZ2V0LmNoZWNrZWQ7XG4gICAgICB9XG4gICAgfVxuICB9IGVsc2Uge1xuICAgIGl0ZW1zLnZhbHVlW2ldLmNoZWNrZWQgPSB0YXJnZXQuY2hlY2tlZDtcblxuICAgIGlmIChsYXN0Q2hlY2tJdGVtSW5kZXgudmFsdWUgPT09IG51bGwpIHtcbiAgICAgIGxhc3RDaGVja0l0ZW1JbmRleC52YWx1ZSA9IGk7XG4gICAgICByZXR1cm47XG4gICAgfVxuICB9XG5cbiAgbGFzdENoZWNrSXRlbUluZGV4LnZhbHVlID0gaTtcbn1cblxuZnVuY3Rpb24gY291bnRDaGVja2VkKCkge1xuICByZXR1cm4gY2hlY2tlZEl0ZW1zLnZhbHVlLmxlbmd0aDtcbn1cblxuLy8gRWRpdGluZ1xuY29uc3QgY3VycmVudCA9IGNvbXB1dGVkPFByb2R1Y3RWYXJpYW50IHwgdW5kZWZpbmVkPigoKSA9PiB7XG4gIGlmIChjaGVja2VkSXRlbXMudmFsdWUubGVuZ3RoID09PSAxKSB7XG4gICAgcmV0dXJuIGNoZWNrZWRJdGVtcy52YWx1ZVswXTtcbiAgfVxuXG4gIHJldHVybiB1bmRlZmluZWQ7XG59KTtcbmNvbnN0IHZhcmlhbnRFZGl0ID0gcmVmKCk7XG5cbmFzeW5jIGZ1bmN0aW9uIGVkaXRWYXJpYW50KGl0ZW06IFByb2R1Y3RWYXJpYW50KSB7XG4gIGlmICghYXdhaXQgY2FuY2VsRWRpdCgpKSB7XG4gICAgcmV0dXJuO1xuICB9XG5cbiAgY2hlY2tBbGwodW5kZWZpbmVkLCBmYWxzZSk7XG5cbiAgZ2VuZXJhdGUudmFsdWUuZWRpdCA9IGZhbHNlO1xuICBpdGVtLmNoZWNrZWQgPSB0cnVlO1xufVxuXG5hc3luYyBmdW5jdGlvbiBnZW5lcmF0ZUNvbWJpbmF0aW9ucygpIHtcbiAgaWYgKCFhd2FpdCBjYW5jZWxFZGl0KCkpIHtcbiAgICByZXR1cm47XG4gIH1cblxuICBnZW5lcmF0ZS52YWx1ZS5lZGl0ID0gdHJ1ZTtcbn1cblxuZnVuY3Rpb24gZ2VuZXJhdGVkKHZhcmlhbnRzOiBQcm9kdWN0VmFyaWFudFtdKSB7XG4gIGl0ZW1zLnZhbHVlID0gaXRlbXMudmFsdWUuY29uY2F0KHByZXBhcmVJdGVtcyh2YXJpYW50cykpO1xuXG4gIGdlbmVyYXRlLnZhbHVlLmVkaXQgPSBmYWxzZTtcbn1cblxuYXN5bmMgZnVuY3Rpb24gY2FuY2VsRWRpdCgpIHtcbiAgY2hlY2tBbGwodW5kZWZpbmVkLCBmYWxzZSk7XG5cbiAgcmV0dXJuIHRydWU7XG59XG5cbmFzeW5jIGZ1bmN0aW9uIGNvbmZpcm1MZWF2ZSgpIHtcbiAgaWYgKHZhcmlhbnRFZGl0LnZhbHVlKSB7XG4gICAgaWYgKHZhcmlhbnRFZGl0LnZhbHVlLnVuc2F2ZSkge1xuICAgICAgY29uc3QgdiA9IGF3YWl0IHNpbXBsZUNvbmZpcm0oX18oJ3Nob3Bnby5tZXNzYWdlLnNhdmUucmVxdWlyZWQnKSk7XG5cbiAgICAgIGlmICghdikge1xuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICB9XG4gICAgfVxuICB9XG5cbiAgcmV0dXJuIHRydWU7XG59XG5cbmZ1bmN0aW9uIGRlbGV0ZVZhcmlhbnRzKGl0ZW0/OiBQcm9kdWN0VmFyaWFudCkge1xuICBpZiAoIWl0ZW0pIHtcbiAgICBpdGVtcy52YWx1ZSA9IGl0ZW1zLnZhbHVlLmZpbHRlcihpdCA9PiAhaXQuY2hlY2tlZCk7XG4gIH0gZWxzZSB7XG4gICAgaXRlbXMudmFsdWUgPSBpdGVtcy52YWx1ZS5maWx0ZXIoaXQgPT4gaXQuaGFzaCAhPT0gaXRlbS5oYXNoKTtcbiAgfVxufVxuXG4vLyBJbnB1dFxuY29uc3QgaXRlbXNKU09OID0gY29tcHV0ZWQoKCkgPT4gSlNPTi5zdHJpbmdpZnkoaXRlbXMudmFsdWUpKTtcbjwvc2NyaXB0PlxuXG48dGVtcGxhdGU+XG4gIDxkaXYgY2xhc3M9XCJyb3dcIiBkYXRhLW5vdmFsaWRhdGU+XG4gICAgPCEtLSBWYXJpYW50cyBMaXN0IC0tPlxuICAgIDxkaXYgY2xhc3M9XCJjb2wtbGctNiBsLXByb2R1Y3QtdmFyaWFudF9fbGlzdFwiPlxuICAgICAgPGRpdiBjbGFzcz1cImNhcmQgYy12YXJpYW50LWxpc3RcIj5cbiAgICAgICAgPCEtLSBIZWFkZXIgLS0+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkLWhlYWRlciBjLXZhcmlhbnQtbGlzdF9fdG9vbGJhciBkLWZsZXhcIj5cbiAgICAgICAgICA8ZGl2IGNsYXNzPVwibXMtYXV0b1wiPlxuICAgICAgICAgICAgPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJidG4gYnRuLXNtIGJ0bi1vdXRsaW5lLWRhbmdlclwiXG4gICAgICAgICAgICAgIHYtaWY9XCJjb3VudENoZWNrZWQoKSA+IDBcIlxuICAgICAgICAgICAgICBAY2xpY2s9XCJkZWxldGVWYXJpYW50cygpXCJcbiAgICAgICAgICAgICAgOmRpc2FibGVkPVwiZ2VuZXJhdGUuZWRpdFwiPlxuICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLXRyYXNoXCI+PC9zcGFuPlxuICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLnByb2R1Y3QudmFyaWFudC5idXR0b24uZGVsZXRlLnZhcmlhbnRzJykgfX1cbiAgICAgICAgICAgIDwvYnV0dG9uPlxuXG4gICAgICAgICAgICA8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImJ0biBidG4tc20gYnRuLXByaW1hcnlcIlxuICAgICAgICAgICAgICBAY2xpY2s9XCJnZW5lcmF0ZUNvbWJpbmF0aW9ucygpXCIgOmRpc2FibGVkPVwiZ2VuZXJhdGUuZWRpdFwiPlxuICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLXBsdXNcIj48L3NwYW4+XG4gICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ucHJvZHVjdC52YXJpYW50LmJ1dHRvbi5hZGQudmFyaWFudHMnKSB9fVxuICAgICAgICAgICAgPC9idXR0b24+XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgIDwvZGl2PlxuXG4gICAgICAgIDxkaXYgY2xhc3M9XCJjLXZhcmlhbnQtbGlzdF9faXRlbXMgbGlzdC1ncm91cCBsaXN0LWdyb3VwLWZsdXNoXCI+XG4gICAgICAgICAgPCEtLSBWYXJpYW50IExpc3QgSGVhZGVyIC0tPlxuICAgICAgICAgIDxkaXYgY2xhc3M9XCJsaXN0LWdyb3VwLWl0ZW0gYy12YXJpYW50LWxpc3RfX2hlYWRlciBkLWZsZXhcIlxuICAgICAgICAgICAgc3R5bGU9XCJtYXJnaW4tYm90dG9tOiAwO1wiPlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cIm1lLTJcIj5cbiAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJjaGVja2JveFwiXG4gICAgICAgICAgICAgICAgY2xhc3M9XCJmb3JtLWNoZWNrLWlucHV0XCJcbiAgICAgICAgICAgICAgICBAY2hhbmdlPVwiY2hlY2tBbGwoJGV2ZW50KVwiXG4gICAgICAgICAgICAgICAgOmluZGV0ZXJtaW5hdGUucHJvcD1cImNvdW50Q2hlY2tlZCgpID4gMCAmJiBjb3VudENoZWNrZWQoKSA8IGl0ZW1zLmxlbmd0aFwiIC8+XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJtZS0yXCIgc3R5bGU9XCJ3aWR0aDogNDVweDtcIj5cbiAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LnZhcmlhbnQubGFiZWwuY292ZXInKSB9fVxuICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwibWUtMiBmbGV4LWZpbGxcIj5cbiAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LnZhcmlhbnQubGFiZWwub3B0aW9ucycpIH19XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJtZS0yXCIgc3R5bGU9XCJ3aWR0aDogNzVweDtcIj5cbiAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LnZhcmlhbnQubGFiZWwuc3RvY2sucXVhbnRpdHknKSB9fVxuICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiXCIgc3R5bGU9XCJ3aWR0aDogNjZweDtcIj5cbiAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5wcm9kdWN0LnZhcmlhbnQubGFiZWwuYWN0aW9ucycpIH19XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgIDwhLS0gVmFyaWFudHMgLS0+XG4gICAgICAgICAgPGRpdiBjbGFzcz1cImMtdmFyaWFudC1saXN0X19zY3JvbGwgbGlzdC1ncm91cCBsaXN0LWdyb3VwLWZsdXNoXCJcbiAgICAgICAgICAgIHN0eWxlPVwib3ZlcmZsb3cteTogc2Nyb2xsOyBoZWlnaHQ6IDc1dmg7IG1pbi1oZWlnaHQ6IDQwMHB4XCI+XG4gICAgICAgICAgICA8dHJhbnNpdGlvbi1ncm91cCBuYW1lPVwiZmFkZVwiPlxuICAgICAgICAgICAgICA8VmFyaWFudExpc3RJdGVtXG4gICAgICAgICAgICAgICAgdi1mb3I9XCIoaXRlbSwgaSkgb2YgaXRlbXNcIlxuICAgICAgICAgICAgICAgIDprZXk9XCJpdGVtLnVpZFwiXG4gICAgICAgICAgICAgICAgOmRhdGEtaWQ9XCJpdGVtLmlkXCJcbiAgICAgICAgICAgICAgICA6aXRlbT1cIml0ZW1cIlxuICAgICAgICAgICAgICAgIDppPVwiaVwiXG4gICAgICAgICAgICAgICAgOmFjdGl2ZT1cImN1cnJlbnQ/Lmhhc2ggPT09IGl0ZW0uaGFzaFwiXG4gICAgICAgICAgICAgICAgQGVkaXQ9XCJlZGl0VmFyaWFudFwiXG4gICAgICAgICAgICAgICAgQHJlbW92ZT1cImRlbGV0ZVZhcmlhbnRzKGl0ZW0pXCJcbiAgICAgICAgICAgICAgICBAb25jaGVjaz1cIm11bHRpQ2hlY2tcIlxuICAgICAgICAgICAgICAgIHN0eWxlPVwiYW5pbWF0aW9uLWR1cmF0aW9uOiAuM3NcIlxuICAgICAgICAgICAgICAvPlxuICAgICAgICAgICAgPC90cmFuc2l0aW9uLWdyb3VwPlxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICA8L2Rpdj5cbiAgICAgIDwvZGl2PlxuICAgIDwvZGl2PlxuXG4gICAgPCEtLSBSaWdodCAtLT5cbiAgICA8ZGl2IGNsYXNzPVwiY29sLWxnLTYgbC1wcm9kdWN0LXZhcmlhbnRfX21hbmFnZVwiPlxuICAgICAgPFZhcmlhbnRJbmZvRWRpdCB2LWlmPVwiY2hlY2tlZEl0ZW1zLmxlbmd0aFwiXG4gICAgICAgIHJlZj1cInZhcmlhbnRFZGl0XCJcbiAgICAgICAgOnZhcmlhbnRzPVwiY2hlY2tlZEl0ZW1zXCJcbiAgICAgICAgQGNhbmNlbD1cImNhbmNlbEVkaXRcIlxuICAgICAgPjwvVmFyaWFudEluZm9FZGl0PlxuXG4gICAgICA8VmFyaWFudEdlbmVyYXRpb24gdi1pZj1cImdlbmVyYXRlLmVkaXRcIlxuICAgICAgICA6aXRlbXM9XCJpdGVtc1wiXG4gICAgICAgIEBnZW5lcmF0ZWQ9XCJnZW5lcmF0ZWRcIlxuICAgICAgICBAY2FuY2VsPVwiZ2VuZXJhdGUuZWRpdCA9IGZhbHNlO1wiXG4gICAgICAgIGNsYXNzPVwiXCI+XG4gICAgICA8L1ZhcmlhbnRHZW5lcmF0aW9uPlxuICAgIDwvZGl2PlxuXG4gICAgPHRleHRhcmVhIG5hbWU9XCJ2YXJpYW50c1wiIGNsYXNzPVwiZC1ub25lXCIgOnZhbHVlPVwiaXRlbXNKU09OXCI+PC90ZXh0YXJlYT5cbiAgPC9kaXY+XG48L3RlbXBsYXRlPlxuXG48c3R5bGUgc2NvcGVkPlxuXG48L3N0eWxlPlxuIiwiaW1wb3J0IHsgdXNlRmllbGRGbGF0cGlja3IgfSBmcm9tICdAd2luZHdhbGtlci1pby91bmljb3JuLW5leHQnO1xuaW1wb3J0IHsgY3JlYXRlQXBwIH0gZnJvbSAndnVlJztcbmltcG9ydCBQcm9kdWN0VmFyaWFudHNFZGl0QXBwIGZyb20gJ35zaG9wZ28vbW9kdWxlcy9wcm9kdWN0LWVkaXQvUHJvZHVjdFZhcmlhbnRzRWRpdEFwcC52dWUnO1xuaW1wb3J0IHsgU2hvcEdvUGx1Z2luIH0gZnJvbSAnfnNob3Bnby9zaG9wZ28tcGx1Z2luJztcblxuZXhwb3J0IGZ1bmN0aW9uIGluaXRBcHAocHJvcHM6IFJlY29yZDxzdHJpbmcsIGFueT4pIHtcbiAgY29uc3QgYXBwID0gY3JlYXRlQXBwKFByb2R1Y3RWYXJpYW50c0VkaXRBcHAsIHByb3BzKTtcblxuICB1c2VGaWVsZEZsYXRwaWNrcigpO1xuXG4gIGFwcC51c2UoU2hvcEdvUGx1Z2luKTtcblxuICByZXR1cm4gYXBwO1xufVxuXG4iXSwibmFtZXMiOlsicHJvcHMiLCJfX3Byb3BzIiwiZW1pdCIsIl9fZW1pdCIsImZlYXR1cmVzIiwicmVmIiwibG9hZGluZ0dlbmVyYXRpbmciLCJsb2FkaW5nR2V0RmVhdHVyZU9wdGlvbnMiLCJwcm9kdWN0IiwiaW5qZWN0IiwibWFpblByaWNlIiwiY3VycmVudEhhc2hlcyIsImNvbXB1dGVkIiwiaXRlbSIsImN1cnJlbnRPcHRpb25VaWRzIiwib3B0aW9ucyIsIm9wdGlvbiIsInZhcmlhbnRzTGltaXQiLCJkYXRhIiwib25Nb3VudGVkIiwiZ2V0RmVhdHVyZU9wdGlvbnMiLCJnZXQiLCJ1c2VIdHRwQ2xpZW50IiwicmVzIiwidW5pcXVlSXRlbUxpc3QiLCJmZWF0dXJlIiwiaSIsImNvbWJpbmF0aW9uQ291bnQiLCJjYXJyeSIsInNhdmVHZW5lcmF0ZSIsInNpbXBsZUFsZXJ0IiwiX18iLCJwb3N0IiwidmFyaWFudHMiLCJnZXRDaGVja2VkT3B0aW9uR3JvdXAiLCJ2YXJpYW50IiwiZmVhdHVyZUNoZWNrYm94Q2hhbmdlZCIsIiRldmVudCIsInRhcmdldCIsIm9wdGlvbkNoZWNrYm94Q2hhbmdlZCIsIl9vcHRpb24iLCJjYW5jZWwiLCJfaG9pc3RlZF8xIiwiX2hvaXN0ZWRfMiIsIl9ob2lzdGVkXzMiLCJfaG9pc3RlZF80IiwiX2hvaXN0ZWRfOCIsIl9ob2lzdGVkXzkiLCJfaG9pc3RlZF8xMiIsIl9ob2lzdGVkXzEzIiwiX2hvaXN0ZWRfMTQiLCJfaG9pc3RlZF8xNyIsIl9vcGVuQmxvY2siLCJfY3JlYXRlRWxlbWVudEJsb2NrIiwiX2NyZWF0ZUVsZW1lbnRWTm9kZSIsIl90b0Rpc3BsYXlTdHJpbmciLCJfY3R4IiwiJHNldHVwIiwiX2NhY2hlIiwiX2NyZWF0ZVRleHRWTm9kZSIsIl9ob2lzdGVkXzUiLCJfaG9pc3RlZF82IiwiX2hvaXN0ZWRfMTkiLCJfaG9pc3RlZF83IiwiX0ZyYWdtZW50IiwiX3JlbmRlckxpc3QiLCJfaG9pc3RlZF8xMCIsIl9ob2lzdGVkXzExIiwiX3dpdGhEaXJlY3RpdmVzIiwiX2hvaXN0ZWRfMTUiLCJfdk1vZGVsQ2hlY2tib3giLCJfbm9ybWFsaXplU3R5bGUiLCJfY3JlYXRlQ29tbWVudFZOb2RlIiwiX2hvaXN0ZWRfMTYiLCJfaG9pc3RlZF8xOCIsImN1cnJlbnQiLCJpdGVtcyIsImN1cnJlbnRIYXNoIiwiZmxhdHBpY2tyT3B0aW9ucyIsInN0YWNrIiwidXNlU3RhY2siLCJpbnB1dFN0ZXAiLCJ3YXRjaCIsInYiLCJoYXNoSXRlbSIsIm5ld0l0ZW0iLCJNZDUiLCJpc011bHRpcGxlIiwidXBkYXRlVW5zYXZlcyIsIm1lcmdlUmVjdXJzaXZlIiwiY2FuY2VsRWRpdCIsImdldEltYWdlVXBsb2FkZXJVcmwiLCJyb3V0ZSIsIl9ob2lzdGVkXzIxIiwiX2hvaXN0ZWRfMjMiLCJfaG9pc3RlZF8yNCIsIl9ob2lzdGVkXzI2IiwiX2hvaXN0ZWRfMjgiLCJfdk1vZGVsVGV4dCIsIl9ob2lzdGVkXzIwIiwiX2hvaXN0ZWRfMjIiLCJfaG9pc3RlZF8yNSIsIl9ob2lzdGVkXzI3IiwiX2hvaXN0ZWRfMjkiLCJfY3JlYXRlVk5vZGUiLCJfd2l0aEN0eCIsImluc3RhbmNlIiwiY2FuVXBsb2FkIiwib3BlbkZpbGVTZWxlY3RvciIsImRlbGV0ZUl0ZW0iLCJfbWVyZ2VQcm9wcyIsImluZGV4IiwiX2NyZWF0ZUJsb2NrIiwiZGVmYXVsdEltYWdlIiwiZWRpdCIsInJlbW92ZSIsIm11bHRpQ2hlY2siLCJwcmljZU9mZnNldCIsIl9ub3JtYWxpemVDbGFzcyIsIiRwcm9wcyIsInByaWNlSW5wdXQiLCJmb3JtIiwicHJlcGFyZUl0ZW1zIiwiZ2VuZXJhdGUiLCJsYXN0Q2hlY2tJdGVtSW5kZXgiLCJwcm92aWRlIiwiZm9ybVN1Ym1pdHRpbmciLCJpbml0aWFsSGFzaCIsInNhdmVSZXF1aXJlZCIsIml0ZW1zSlNPTiIsImUiLCJjaGVja2VkSXRlbXMiLCJjaGVja0FsbCIsInZhbHVlIiwiayIsImNvdW50Q2hlY2tlZCIsInZhcmlhbnRFZGl0IiwiZWRpdFZhcmlhbnQiLCJnZW5lcmF0ZUNvbWJpbmF0aW9ucyIsImdlbmVyYXRlZCIsImNvbmZpcm1MZWF2ZSIsInNpbXBsZUNvbmZpcm0iLCJkZWxldGVWYXJpYW50cyIsIml0IiwiX1RyYW5zaXRpb25Hcm91cCIsImluaXRBcHAiLCJhcHAiLCJjcmVhdGVBcHAiLCJQcm9kdWN0VmFyaWFudHNFZGl0QXBwIiwidXNlRmllbGRGbGF0cGlja3IiLCJTaG9wR29QbHVnaW4iXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7Ozs7QUFNQSxVQUFNQSxJQUFRQyxHQUlSQyxJQUFPQyxHQUtQQyxJQUFXQyxFQUFzQixFQUFFLEdBQ25DQyxJQUFvQkQsRUFBSSxFQUFLLEdBQzdCRSxJQUEyQkYsRUFBSSxFQUFLLEdBRXBDRyxJQUFVQyxFQUFnQixTQUFTLEdBQ25DQyxJQUFZRCxFQUFvQixXQUFXLEdBQzNDRSxJQUFnQkMsRUFBUyxNQUFNWixFQUFNLE1BQU0sSUFBSSxDQUFBYSxNQUFRQSxFQUFLLElBQUksQ0FBQyxHQUNqRUMsSUFBb0JGLEVBQVMsTUFBTTtBQUN2QyxZQUFNRyx3QkFBYyxJQUFBO0FBRXBCLGlCQUFXRixLQUFRYixFQUFNO0FBQ3ZCLG1CQUFXZ0IsS0FBVUgsRUFBSztBQUN4QixVQUFBRSxFQUFRLElBQUlDLEVBQU8sR0FBRztBQUkxQixhQUFPLE1BQU0sS0FBS0QsQ0FBTztBQUFBLElBQzNCLENBQUMsR0FDS0UsSUFBZ0JDLEVBQWEsZ0JBQWdCLEtBQUs7QUFFeEQsSUFBQUMsR0FBVSxNQUFNO0FBQ2QsTUFBQUMsRUFBQTtBQUFBLElBQ0YsQ0FBQztBQUVELG1CQUFlQSxJQUFvQjtBQUNqQyxNQUFBYixFQUF5QixRQUFRO0FBRWpDLFlBQU0sRUFBRSxLQUFBYyxNQUFRLE1BQU1DLEVBQUE7QUFFdEIsVUFBSTtB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
