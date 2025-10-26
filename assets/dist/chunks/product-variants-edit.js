import { _ as J, r as j } from "./_plugin-vue_export-helper.js";
import { data as Y, useHttpClient as tt, simpleAlert as rt, __ as nt, useStack as lt, route as dt, simpleConfirm as ut, useFieldFlatpickr as ct } from "@windwalker-io/unicorn-next";
import { defineComponent as R, ref as V, inject as Q, computed as U, onMounted as mt, createElementBlock as f, openBlock as c, createElementVNode as n, createTextVNode as o, toDisplayString as a, Fragment as M, renderList as H, withDirectives as E, vModelCheckbox as it, createCommentVNode as S, normalizeStyle as pt, watch as B, vModelText as A, createVNode as z, withCtx as K, mergeProps as vt, createBlock as D, normalizeClass as ft, provide as et, TransitionGroup as gt, createApp as ht } from "vue";
import { uniqueItemList as ot } from "@lyrasoft/ts-toolkit/vue";
import { h as W } from "./index.es.js";
import { VueDraggable as bt } from "vue-draggable-plus";
import { mergeRecursive as kt, ShopGoPlugin as _t } from "../index.js";
import { ItemCardPlaceholder as yt, ItemCard as Ct, MultiUploader as Vt } from "vue-multi-uploader";
const wt = /* @__PURE__ */ R({
  __name: "VariantGeneration",
  props: {
    items: {}
  },
  emits: ["generated", "cancel"],
  setup(s, { expose: t, emit: p }) {
    t();
    const e = s, h = p, l = V([]), i = V(!1), r = V(!1), d = Q("product"), y = Q("mainPrice"), g = U(() => e.items.map((u) => u.hash)), k = U(() => {
      const u = /* @__PURE__ */ new Set();
      for (const _ of e.items)
        for (const v of _.options)
          u.add(v.uid);
      return Array.from(u);
    }), C = Y("variants.limit") ?? 100;
    mt(() => {
      O();
    });
    async function O() {
      r.value = !0;
      const { get: u } = await tt();
      try {
        const _ = await u("@product_ajax/getFeatureOptions");
        l.value = ot(_.data.data).map((v) => (v.checks = 0, v));
        for (const v of l.value) {
          let I = 0;
          for (const N of v.options)
            N.checked = k.value.includes(N.uid), N.checked && I++;
          v.checks = I;
        }
      } finally {
        r.value = !1;
      }
    }
    const $ = U(() => l.value.reduce((u, _) => _.checks > 0 ? u * _.checks : u, 1));
    async function G() {
      if ($.value >= C) {
        rt(
          nt("shopgo.product.message.too.many.features.selected", $.value, C),
          "",
          "warning"
        );
        return;
      }
      i.value = !0;
      const { post: u } = await tt();
      try {
        const v = (await u(
          "@product_ajax/generateVariants",
          {
            product_id: d?.id,
            options: L(),
            currentHashes: g.value
          }
        )).data.data;
        for (const I of v)
          I.price = Number(y.value);
        h("generated", v);
      } finally {
        i.value = !1;
      }
    }
    function L() {
      const u = {};
      for (const _ of l.value) {
        const v = _.options.filter((I) => I.checked);
        v.length > 0 && (u[_.id.toString()] = v);
      }
      return u;
    }
    function P(u, _) {
      const v = _.target;
      u.options.forEach((I) => I.checked = v.checked), u.checks = v.checked ? u.options.length : 0;
    }
    function w(u, _) {
      u.checks = 0, u.options.forEach((v) => {
        v.checked && u.checks++;
      });
    }
    function x() {
      h("cancel");
    }
    const T = { props: e, emit: h, features: l, loadingGenerating: i, loadingGetFeatureOptions: r, product: d, mainPrice: y, currentHashes: g, currentOptionUids: k, variantsLimit: C, getFeatureOptions: O, combinationCount: $, saveGenerate: G, getCheckedOptionGroup: L, featureCheckboxChanged: P, optionCheckboxChanged: w, cancel: x };
    return Object.defineProperty(T, "__isScriptSetup", { enumerable: !1, value: !0 }), T;
  }
}), xt = { class: "c-variant-generate card sticky-top" }, St = { class: "card-header d-flex" }, It = { class: "c-variant-generate__title" }, Et = { class: "c-variant-generate__actions ms-auto" }, Ut = ["disabled"], Ot = ["disabled"], $t = {
  key: 0,
  class: "c-feature-list list-group list-group-flush"
}, Gt = { class: "c-feature-item list-group-item" }, Pt = { class: "mb-3 h5" }, Ft = ["checked", "id", ".indeterminate", "onChange"], Lt = ["for"], Nt = { class: "c-option-list row" }, At = { class: "c-option-item col-md-4 col-6" }, qt = { class: "c-option-item__input-wrapper form-check" }, Dt = ["id", "value", "name", "onUpdate:modelValue", "onChange"], Tt = ["for"], jt = { class: "list-group-item" }, Mt = ["disabled"], Ht = {
  key: 1,
  class: "text-center card-body"
};
function Jt(s, t, p, e, h, l) {
  return c(), f("div", xt, [
    n("div", St, [
      n("div", It, a(s.$lang("shopgo.product.variant.generation.title")) + " (" + a(e.combinationCount || 0) + `)
      `, 1),
      t[3] || (t[3] = o()),
      n("div", Et, [
        n("button", {
          type: "button",
          class: "btn btn-primary btn-sm",
          onClick: e.saveGenerate,
          disabled: e.loadingGenerating
        }, [
          t[0] || (t[0] = n("span", { class: "fa fa-save" }, null, -1)),
          o(" " + a(e.loadingGenerating ? s.$lang("shopgo.product.text.saving") : s.$lang("shopgo.product.variant.generation.button.submit")), 1)
        ], 8, Ut),
        t[2] || (t[2] = o()),
        n("button", {
          type: "button",
          class: "btn btn-outline-secondary btn-sm",
          onClick: e.cancel,
          disabled: e.loadingGenerating
        }, [
          t[1] || (t[1] = n("span", { class: "fa fa-times" }, null, -1)),
          o(" " + a(s.$lang("shopgo.product.button.cancel")), 1)
        ], 8, Ot)
      ])
    ]),
    t[10] || (t[10] = o()),
    e.loadingGetFeatureOptions ? (c(), f("div", Ht, a(s.$lang("shopgo.product.text.loading")), 1)) : (c(), f("div", $t, [
      (c(!0), f(M, null, H(e.features, (i) => (c(), f("div", Gt, [
        n("h4", Pt, [
          n("span", null, [
            n("input", {
              type: "checkbox",
              checked: i.options.length === i.checks,
              id: "input-feature-" + i.id,
              class: "form-check-input",
              ".indeterminate": i.checks !== 0 && i.options.length > i.checks,
              onChange: (r) => e.featureCheckboxChanged(i, r)
            }, null, 40, Ft)
          ]),
          t[4] || (t[4] = o()),
          n("label", {
            for: "input-feature-" + i.id
          }, a(i.title), 9, Lt)
        ]),
        t[7] || (t[7] = o()),
        n("div", Nt, [
          (c(!0), f(M, null, H(i.options, (r) => (c(), f("div", At, [
            n("div", qt, [
              E(n("input", {
                id: "input-option-" + r.uid,
                type: "checkbox",
                value: r.uid,
                name: `options[${i.id}][${r.uid}]`,
                class: "form-check-input",
                "onUpdate:modelValue": (d) => r.checked = d,
                onChange: (d) => e.optionCheckboxChanged(i, r)
              }, null, 40, Dt), [
                [it, r.checked]
              ]),
              t[6] || (t[6] = o()),
              n("label", {
                for: "input-option-" + r.uid,
                class: "form-check-label d-flex align-items-center"
              }, [
                i.type === "color" ? (c(), f("span", {
                  key: 0,
                  class: "rounded me-2",
                  style: pt([{ width: "20px", height: "20px" }, { "background-color": r.color }])
                }, null, 4)) : S("", !0),
                t[5] || (t[5] = o()),
                n("span", null, a(r.text), 1)
              ], 8, Tt)
            ])
          ]))), 256))
        ])
      ]))), 256)),
      t[9] || (t[9] = o()),
      n("div", jt, [
        n("button", {
          type: "button",
          class: "btn btn-primary btn-sm w-100",
          onClick: e.saveGenerate,
          disabled: e.loadingGenerating
        }, [
          t[8] || (t[8] = n("span", { class: "fa fa-save" }, null, -1)),
          o(" " + a(e.loadingGenerating ? s.$lang("shopgo.product.text.saving") : s.$lang("shopgo.product.variant.generation.button.submit")), 1)
        ], 8, Mt)
      ])
    ]))
  ]);
}
const Rt = /* @__PURE__ */ J(wt, [["render", Jt], ["__file", "VariantGeneration.vue"]]), Bt = /* @__PURE__ */ R({
  __name: "VariantInfoEdit",
  props: {
    variants: {}
  },
  emits: ["cancel"],
  setup(s, { expose: t, emit: p }) {
    t();
    const e = s, h = p, l = V(null), i = V([]), r = V(""), d = V(
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
    ), y = lt("uploading"), g = V(Y("input.step") || "0.0001");
    B(() => e.variants, (w) => {
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
      i.value = e.variants, i.value.length === 1 && (x = i.value[0]), r.value = k(x), l.value = x;
    }, { immediate: !0 });
    function k(w) {
      const x = { ...w };
      return delete x.checked, delete x.unsave, W.hashStr(JSON.stringify(x));
    }
    const C = U(() => i.value.length > 1);
    B(() => l.value, () => {
      r.value !== "" && r.value !== k(l.value) && O();
    }, { deep: !0 }), B(() => l.value?.price, (w) => {
      l.value && w != null && w < 0 && (l.value.price = 0);
    });
    function O() {
      if (l.value)
        if (!C.value)
          l.value.cover = l.value.images[0]?.url || "", i.value[0].unsave = !0;
        else
          for (const w of i.value)
            kt(
              w,
              l.value
            ), w.unsave = !0;
    }
    function $() {
      h("cancel");
    }
    function G() {
      return dt("file_upload", { profile: "image" });
    }
    const P = { props: e, emit: h, current: l, items: i, currentHash: r, flatpickrOptions: d, stack: y, inputStep: g, hashItem: k, isMultiple: C, updateUnsaves: O, cancelEdit: $, getImageUploaderUrl: G, draggableOptions: {
      handle: ".item",
      animation: 150
    }, get VueDraggable() {
      return bt;
    }, get MultiUploader() {
      return Vt;
    }, get ItemCard() {
      return Ct;
    }, get ItemCardPlaceholder() {
      return yt;
    } };
    return Object.defineProperty(P, "__isScriptSetup", { enumerable: !1, value: !0 }), P;
  }
}), Qt = { class: "c-variant-edit card" }, zt = { class: "card-header d-flex align-items-center" }, Kt = { class: "c-variant-edit__title d-flex gap-2" }, Wt = { class: "c-variant-edit__actions ms-auto" }, Yt = {
  key: 0,
  class: "card-body"
}, Xt = { class: "c-variant-edit__title mb-4" }, Zt = { class: "lead" }, te = { class: "d-flex gap-2" }, ee = {
  key: 0,
  class: "form-group mb-4"
}, ne = {
  for: "input-variant-sku",
  class: "form-label"
}, ie = { class: "form-group mb-4" }, oe = {
  for: "input-variant-price",
  class: "form-label"
}, se = ["step"], ae = { class: "d-flex gap-2" }, re = { class: "form-group mb-4" }, le = {
  for: "input-variant-length",
  class: "form-label"
}, de = { class: "form-group mb-4" }, ue = {
  for: "input-variant-width",
  class: "form-label"
}, ce = { class: "form-group mb-4" }, me = {
  for: "input-variant-height",
  class: "form-label"
}, pe = { class: "form-group mb-4" }, ve = {
  for: "input-variant-weight",
  class: "form-label"
}, fe = { class: "d-flex gap-2" }, ge = { class: "form-group mb-4" }, he = {
  for: "input-variant-inventory",
  class: "form-label"
}, be = { class: "form-group mb-4" }, ke = {
  for: "input-variant-subtract",
  class: "form-label"
}, _e = { class: "form-check form-switch" }, ye = {
  key: 0,
  class: "variant-images mt-4"
};
function Ce(s, t, p, e, h, l) {
  return c(), f("div", Qt, [
    n("div", zt, [
      n("div", Kt, [
        n("div", null, a(s.$lang("shopgo.product.variant.edit.title")), 1)
      ]),
      t[12] || (t[12] = o()),
      n("div", Wt, [
        n("button", {
          type: "button",
          class: "btn btn-outline-secondary btn-sm",
          onClick: e.cancelEdit
        }, [
          t[11] || (t[11] = n("span", { class: "fa fa-times" }, null, -1)),
          o(" " + a(s.$lang("shopgo.product.button.cancel")), 1)
        ])
      ])
    ]),
    t[31] || (t[31] = o()),
    e.current ? (c(), f("div", Yt, [
      n("div", Xt, [
        n("span", Zt, a(e.items.length <= 1 ? e.current.title : s.$lang("shopgo.product.variant.edit.multiple")), 1)
      ]),
      t[27] || (t[27] = o()),
      n("div", te, [
        e.items.length <= 1 ? (c(), f("div", ee, [
          n("label", ne, a(s.$lang("shopgo.product.field.sku")), 1),
          t[13] || (t[13] = o()),
          E(n("textarea", {
            id: "input-variant-sku",
            type: "text",
            class: "form-control",
            "onUpdate:modelValue": t[0] || (t[0] = (i) => e.current.sku = i),
            rows: "1"
          }, null, 512), [
            [A, e.current.sku]
          ])
        ])) : S("", !0),
        t[15] || (t[15] = o()),
        n("div", ie, [
          n("label", oe, a(s.$lang("shopgo.product.field.price")), 1),
          t[14] || (t[14] = o()),
          E(n("input", {
            id: "input-variant-price",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[1] || (t[1] = (i) => e.current.price = i),
            min: "0",
            step: e.inputStep
          }, null, 8, se), [
            [A, e.current.price]
          ])
        ])
      ]),
      t[28] || (t[28] = o()),
      n("div", ae, [
        n("div", re, [
          n("label", le, a(s.$lang("shopgo.product.field.length")), 1),
          t[16] || (t[16] = o()),
          E(n("input", {
            id: "input-variant-length",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[2] || (t[2] = (i) => e.current.dimension.length = i),
            min: "0"
          }, null, 512), [
            [A, e.current.dimension.length]
          ])
        ]),
        t[20] || (t[20] = o()),
        n("div", de, [
          n("label", ue, a(s.$lang("shopgo.product.field.width")), 1),
          t[17] || (t[17] = o()),
          E(n("input", {
            id: "input-variant-width",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[3] || (t[3] = (i) => e.current.dimension.width = i),
            min: "0"
          }, null, 512), [
            [A, e.current.dimension.width]
          ])
        ]),
        t[21] || (t[21] = o()),
        n("div", ce, [
          n("label", me, a(s.$lang("shopgo.product.field.height")), 1),
          t[18] || (t[18] = o()),
          E(n("input", {
            id: "input-variant-height",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[4] || (t[4] = (i) => e.current.dimension.height = i),
            min: "0"
          }, null, 512), [
            [A, e.current.dimension.height]
          ])
        ]),
        t[22] || (t[22] = o()),
        n("div", pe, [
          n("label", ve, a(s.$lang("shopgo.product.field.weight")), 1),
          t[19] || (t[19] = o()),
          E(n("input", {
            id: "input-variant-weight",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[5] || (t[5] = (i) => e.current.dimension.weight = i),
            min: "0"
          }, null, 512), [
            [A, e.current.dimension.weight]
          ])
        ])
      ]),
      t[29] || (t[29] = o()),
      n("div", fe, [
        n("div", ge, [
          n("label", he, a(s.$lang("shopgo.product.field.stock.quantity")), 1),
          t[23] || (t[23] = o()),
          E(n("input", {
            id: "input-variant-inventory",
            type: "number",
            class: "form-control",
            "onUpdate:modelValue": t[6] || (t[6] = (i) => e.current.stockQuantity = i),
            min: "0"
          }, null, 512), [
            [A, e.current.stockQuantity]
          ])
        ]),
        t[25] || (t[25] = o()),
        n("div", be, [
          n("label", ke, a(s.$lang("shopgo.product.field.subtract")), 1),
          t[24] || (t[24] = o()),
          n("div", _e, [
            E(n("input", {
              type: "checkbox",
              id: "input-variant-subtract",
              class: "form-check-input",
              "onUpdate:modelValue": t[7] || (t[7] = (i) => e.current.subtract = i),
              "true-value": !0,
              "false-value": !1,
              role: "switch"
            }, null, 512), [
              [it, e.current.subtract]
            ])
          ])
        ])
      ]),
      t[30] || (t[30] = o()),
      e.items.length <= 1 ? (c(), f("div", ye, [
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
          items: K(({ instance: i, instance: { canUpload: r, openFileSelector: d, deleteItem: y } }) => [
            z(e.VueDraggable, vt({
              modelValue: i.items,
              "onUpdate:modelValue": (g) => i.items = g
            }, e.draggableOptions, { class: "d-flex flex-wrap w-100 gap-3" }), {
              default: K(() => [
                (c(!0), f(M, null, H(i.items, (g, k) => (c(), D(e.ItemCard, {
                  key: g.key,
                  class: "item",
                  item: g,
                  i: k,
                  onDelete: y
                }, null, 8, ["item", "i", "onDelete"]))), 128)),
                t[26] || (t[26] = o()),
                r ? (c(), D(e.ItemCardPlaceholder, {
                  key: 0,
                  class: "",
                  text: "Upload Images",
                  onClick: d
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
const Ve = /* @__PURE__ */ J(Bt, [["render", Ce], ["__file", "VariantInfoEdit.vue"]]), we = /* @__PURE__ */ R({
  __name: "VariantListItem",
  props: {
    item: {},
    i: {},
    active: { type: Boolean }
  },
  emits: ["edit", "remove", "oncheck"],
  setup(s, { expose: t, emit: p }) {
    t();
    const e = s, h = p, l = Y("defaultImage");
    function i() {
      h("edit", e.item);
    }
    function r() {
      h("remove", e.item);
    }
    function d(C) {
      h("oncheck", C, e.i);
    }
    const y = Q("mainPrice"), g = U(() => Number(e.item.price) - Number(y.value)), k = { props: e, emit: h, defaultImage: l, edit: i, remove: r, multiCheck: d, mainPrice: y, priceOffset: g };
    return Object.defineProperty(k, "__isScriptSetup", { enumerable: !1, value: !0 }), k;
  }
}), xe = { class: "list-group-item__wrapper d-flex align-items-center gap-2" }, Se = { class: "c-variant-item__control d-flex flex-nowrap" }, Ie = ["checked"], Ee = { class: "c-variant-item__image" }, Ue = ["src"], Oe = { class: "c-variant-item__title flex-fill text-truncate" }, $e = {
  class: "text-truncate",
  style: { "max-width": "100%" }
}, Ge = {
  key: 0,
  style: { opacity: ".75" }
}, Pe = {
  key: 1,
  style: { opacity: ".75" }
}, Fe = {
  key: 2,
  class: "badge bg-warning"
}, Le = { class: "c-variant-item__inventory text-end" }, Ne = { class: "c-variant-item__actions d-flex flex-nowrap gap-1" }, Ae = ["disabled"], qe = ["disabled"];
function De(s, t, p, e, h, l) {
  return c(), f("div", {
    class: ft(["list-group-item c-variant-item", { active: p.active }])
  }, [
    n("div", xe, [
      n("div", Se, [
        n("input", {
          type: "checkbox",
          class: "form-check-input",
          checked: p.item.checked,
          onClick: e.multiCheck
        }, null, 8, Ie)
      ]),
      t[6] || (t[6] = o()),
      n("div", Ee, [
        n("img", {
          src: p.item.cover || e.defaultImage,
          width: "45",
          height: "45",
          alt: "Cover",
          class: "rounded"
        }, null, 8, Ue)
      ]),
      t[7] || (t[7] = o()),
      n("div", Oe, [
        n("div", $e, a(p.item.title), 1),
        t[2] || (t[2] = o()),
        n("div", null, [
          p.item.sku ? (c(), f("span", Ge, `
                    #` + a(p.item.sku), 1)) : S("", !0),
          t[0] || (t[0] = o()),
          e.priceOffset !== 0 ? (c(), f("span", Pe, a(s.$offsetFormat(e.priceOffset, "$")), 1)) : S("", !0),
          t[1] || (t[1] = o()),
          p.item.unsave ? (c(), f("span", Fe, a(s.$lang("shopgo.product.text.save.required")), 1)) : S("", !0)
        ])
      ]),
      t[8] || (t[8] = o()),
      n("div", Le, a(s.$numberFormat(p.item.stockQuantity)), 1),
      t[9] || (t[9] = o()),
      n("div", Ne, [
        n("button", {
          type: "button",
          class: "btn btn-sm btn-light border-secondary",
          onClick: e.edit,
          disabled: p.item.saving
        }, [...t[3] || (t[3] = [
          n("span", { class: "fa fa-pencil-alt" }, null, -1)
        ])], 8, Ae),
        t[5] || (t[5] = o()),
        n("button", {
          type: "button",
          class: "btn btn-sm btn-light border-secondary",
          onClick: e.remove,
          disabled: p.item.saving
        }, [...t[4] || (t[4] = [
          n("span", { class: "fa fa-trash text-danger" }, null, -1)
        ])], 8, qe)
      ])
    ])
  ], 2);
}
const Te = /* @__PURE__ */ J(we, [["render", De], ["__file", "VariantListItem.vue"]]), je = /* @__PURE__ */ R({
  __name: "ProductVariantsEditApp",
  props: {
    product: {},
    variants: {}
  },
  setup(s, { expose: t }) {
    t();
    const p = j("VariantGeneration", Rt), e = j("VariantInfoEdit", Ve), h = j("VariantListItem", Te), l = s, i = document.querySelector("#input-item-variant-price"), r = document.querySelector("#admin-form"), d = V(L(l.variants)), y = V({
      edit: !1
    }), g = V(0), k = V(parseFloat(i.value).toString());
    et("product", l.product || {}), et("mainPrice", k), i.addEventListener("change", () => {
      k.value = parseFloat(i.value).toString();
    });
    let C = !1;
    const O = W.hashStr(JSON.stringify(d.value)), $ = U(() => W.hashStr(X.value) !== O);
    window.addEventListener("beforeunload", (m) => {
      if ($.value && !C)
        return m.preventDefault(), m.stopPropagation(), m.returnValue = "Save Required", "Save Required";
    }), r.addEventListener("submit", () => {
      C = !0;
    });
    const G = U(() => d.value.filter((m) => m.checked));
    function L(m) {
      return ot(m).map((b) => (b.checked = !1, b.unsave = !1, b));
    }
    function P(m, b) {
      const q = m?.target;
      d.value.forEach((F) => {
        F.checked = b ?? q?.checked;
      });
    }
    async function w(m, b) {
      const q = m.target;
      if (m.shiftKey) {
        let F = g.value;
        if (g.value < b)
          for (; F < b; F++)
            d.value[F].checked = q.checked;
        else
          for (; F > b; F--)
            d.value[F].checked = q.checked;
      } else if (d.value[b].checked = q.checked, g.value === null) {
        g.value = b;
        return;
      }
      g.value = b;
    }
    function x() {
      return G.value.length;
    }
    const T = U(() => {
      if (G.value.length === 1)
        return G.value[0];
    }), u = V();
    async function _(m) {
      await N() && (P(void 0, !1), y.value.edit = !1, m.checked = !0);
    }
    async function v() {
      await N() && (y.value.edit = !0);
    }
    function I(m) {
      d.value = d.value.concat(L(m)), y.value.edit = !1;
    }
    async function N() {
      return P(void 0, !1), !0;
    }
    async function st() {
      return !(u.value && u.value.unsave && !await ut(nt("shopgo.message.save.required")));
    }
    function at(m) {
      m ? d.value = d.value.filter((b) => b.hash !== m.hash) : d.value = d.value.filter((b) => !b.checked);
    }
    const X = U(() => JSON.stringify(d.value)), Z = { VariantGeneration: p, VariantInfoEdit: e, VariantListItem: h, props: l, priceInput: i, form: r, items: d, generate: y, lastCheckItemIndex: g, mainPrice: k, get formSubmitting() {
      return C;
    }, set formSubmitting(m) {
      C = m;
    }, initialHash: O, saveRequired: $, checkedItems: G, prepareItems: L, checkAll: P, multiCheck: w, countChecked: x, current: T, variantEdit: u, editVariant: _, generateCombinations: v, generated: I, cancelEdit: N, confirmLeave: st, deleteVariants: at, itemsJSON: X };
    return Object.defineProperty(Z, "__isScriptSetup", { enumerable: !1, value: !0 }), Z;
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
function rn(s, t, p, e, h, l) {
  return c(), f("div", Me, [
    n("div", He, [
      n("div", Je, [
        n("div", Re, [
          n("div", Be, [
            e.countChecked() > 0 ? (c(), f("button", {
              key: 0,
              type: "button",
              class: "btn btn-sm btn-outline-danger",
              onClick: t[0] || (t[0] = (i) => e.deleteVariants()),
              disabled: e.generate.edit
            }, [
              t[4] || (t[4] = n("span", { class: "fa fa-trash" }, null, -1)),
              o(" " + a(s.$lang("shopgo.product.variant.button.delete.variants")), 1)
            ], 8, Qe)) : S("", !0),
            t[6] || (t[6] = o()),
            n("button", {
              type: "button",
              class: "btn btn-sm btn-primary",
              onClick: t[1] || (t[1] = (i) => e.generateCombinations()),
              disabled: e.generate.edit
            }, [
              t[5] || (t[5] = n("span", { class: "fa fa-plus" }, null, -1)),
              o(" " + a(s.$lang("shopgo.product.variant.button.add.variants")), 1)
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
            n("div", Ze, a(s.$lang("shopgo.product.variant.label.cover")), 1),
            t[8] || (t[8] = o()),
            n("div", tn, a(s.$lang("shopgo.product.variant.label.options")), 1),
            t[9] || (t[9] = o()),
            n("div", en, a(s.$lang("shopgo.product.variant.label.stock.quantity")), 1),
            t[10] || (t[10] = o()),
            n("div", nn, a(s.$lang("shopgo.product.variant.label.actions")), 1)
          ]),
          t[11] || (t[11] = o()),
          n("div", on, [
            z(gt, { name: "fade" }, {
              default: K(() => [
                (c(!0), f(M, null, H(e.items, (i, r) => (c(), D(e.VariantListItem, {
                  key: i.uid,
                  "data-id": i.id,
                  item: i,
                  i: r,
                  active: e.current?.hash === i.hash,
                  onEdit: e.editVariant,
                  onRemove: (d) => e.deleteVariants(i),
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
      e.checkedItems.length ? (c(), D(e.VariantInfoEdit, {
        key: 0,
        ref: "variantEdit",
        variants: e.checkedItems,
        onCancel: e.cancelEdit
      }, null, 8, ["variants"])) : S("", !0),
      t[13] || (t[13] = o()),
      e.generate.edit ? (c(), D(e.VariantGeneration, {
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
const ln = /* @__PURE__ */ J(je, [["render", rn], ["__file", "ProductVariantsEditApp.vue"]]), dn = j("ProductVariantsEditApp", ln);
function bn(s) {
  const t = ht(dn, s);
  return ct(), t.use(_t), t;
}
export {
  bn as initApp
};
