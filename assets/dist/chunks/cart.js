import { _ as _export_sfc, r as resolveVueComponent } from "./_plugin-vue_export-helper.js";
import { useHttpClient, __, data, route, useTomSelect, uid, slideDown, slideUp, useQueue, useStack, debounce, simpleAlert, useCssImport } from "@windwalker-io/unicorn-next";
import { defineComponent, mergeModels, useModel, reactive, ref, watch, onMounted, nextTick, createElementBlock, openBlock, createTextVNode, createElementVNode, Fragment, renderList, normalizeClass, toDisplayString, useTemplateRef, computed, createVNode, createCommentVNode, withDirectives, vModelCheckbox, Transition, withCtx, vModelText, createBlock, createApp } from "vue";
import { vTooltip, ShopGoPlugin } from "../index.js";
import { Modal } from "bootstrap";
import { h } from "./index.es.js";
const _sfc_main$5 = /* @__PURE__ */ defineComponent({
  __name: "CascadeSelect",
  props: /* @__PURE__ */ mergeModels({
    options: {},
    selectAttrs: {},
    name: {}
  }, {
    modelValue: { required: !1 },
    modelModifiers: {}
  }),
  emits: /* @__PURE__ */ mergeModels(["change"], ["update:modelValue"]),
  setup(l, { expose: e, emit: n }) {
    const t = l, u = useModel(l, "modelValue"), y = n, o = globalThis.u || window.u, d = {
      id: "cascade-select-" + (o && o.uid ? o.uid() : String(Math.random())),
      selected: "",
      ignoreSelf: null,
      placeholder: "- Select -",
      placeholders: [],
      ajaxUrl: "",
      ajaxValueField: "value",
      source: [],
      labels: [],
      labelWidth: "col-md-3",
      fieldWidth: "col",
      readonly: !1,
      disabled: !1,
      valueField: "id",
      textField: "title",
      horizontal: null,
      horizontalColWidth: null,
      defaultValue: "",
      onSelectInit: (i) => {
      },
      onChange: (i) => {
      },
      onValueInit: (i) => {
      }
    }, s = reactive(Object.assign({}, d, t.options || {})), p = ref([]), f = ref([]), T = ref(!0), F = ref(!1), E = ref(s.ajaxUrl || ""), k = ref(), b = ref([]);
    function I() {
      T.value = !s.readonly && !s.disabled, E.value = s.ajaxUrl || "";
    }
    async function L() {
      if (F.value)
        return;
      F.value = !0, p.value = [];
      let c = [...(u.value || []).slice().map(($) => String($))];
      f.value = [...c], c.length === 0 ? c = [null] : c.unshift(null);
      let N = null;
      for (let $ in c) {
        const q = c[$], O = await g(q);
        O && O.length > 0 && p.value.push(O), N = q;
      }
      C(k.value, N, c), F.value = !1, await nextTick(), b.value && b.value.length > 0 && P(b.value[0]);
    }
    function D() {
      L();
    }
    function H(i) {
      return s.labels[i] || `Level ${i + 1}`;
    }
    function j(i) {
      return `${s.id}__level-${i}`;
    }
    function R(i) {
      return f.value[i] || "";
    }
    function Q(i, c) {
      return String(R(i)) === String(c[s.valueField]);
    }
    function S() {
      const i = f.value.slice();
      if (i.length === 0)
        return s.defaultValue;
      const c = i.filter((N) => N != null).filter((N) => N !== "").pop();
      return c === void 0 ? s.defaultValue : c;
    }
    function A() {
      return f.value.length;
    }
    async function w(i, c) {
      const N = c.target;
      f.value[i] = N.value;
      try {
        s.onChange(c);
      } catch {
      }
      c.stopPropagation();
      const $ = new CustomEvent("change", {
        detail: {
          el: N,
          component: B,
          value: N.value,
          path: f.value
        }
      });
      if (k.value?.dispatchEvent($), u.value = f.value, y("change", $), N.value === "") {
        p.value.splice(i + 1), f.value.splice(i + 1);
        return;
      }
      const q = await g(N.value);
      if (p.value.splice(i + 1), f.value.splice(i + 1), q && q.length > 0) {
        p.value.push(q), await nextTick();
        const O = b.value.length - 1;
        b.value && b.value[O] && P(b.value[O]);
      }
    }
    async function g(i, c) {
      const { get: N } = await useHttpClient();
      return (await N(
        E.value,
        {
          params: {
            [s.ajaxValueField]: i,
            self: s.ignoreSelf || null
          }
        }
      )).data.data;
    }
    function C(i, c, N) {
      const $ = new CustomEvent("value.init", {
        detail: {
          el: i,
          component: B,
          value: c,
          path: N
        }
      });
      k.value?.dispatchEvent($);
    }
    function P(i) {
      const c = new CustomEvent("select.init", {
        detail: {
          el: i,
          component: B
        }
      });
      s.onSelectInit(c), k.value?.dispatchEvent(c);
    }
    function x(i) {
      return i.map((c) => ({
        [s.valueField]: c.value[s.valueField],
        [s.textField]: c.value[s.textField],
        children: c.children
      })).filter((c) => s.ignoreSelf ? c[s.valueField] != s.ignoreSelf : c);
    }
    function U(i, c) {
      return (i || []).filter(($) => $[s.valueField] == c).shift();
    }
    function M(i) {
      return s.placeholders && s.placeholders[i] ? s.placeholders[i] : s.placeholder;
    }
    const B = {
      opt: s,
      lists: p,
      values: f,
      getFinalValue: S,
      getLevel: A,
      getLabel: H,
      getId: j,
      getListValue: R,
      isSelected: Q,
      onChange: w,
      loadItems: g,
      valueInit: C,
      selectInit: P,
      handleSourceItems: x,
      findFromList: U,
      getPlaceholder: M
    };
    watch(u, (i) => {
      (!i || i.length === 0) && D();
    }, { deep: !0 }), onMounted(async () => {
      I(), await L();
    }), e({
      prepareValues: L
    });
    const r = { props: t, modelValue: u, emit: y, u: o, defaultOpt: d, opt: s, lists: p, values: f, canModify: T, loading: F, ajaxUrl: E, root: k, selects: b, init: I, prepareValues: L, reset: D, getLabel: H, getId: j, getListValue: R, isSelected: Q, getFinalValue: S, getLevel: A, onChange: w, loadItems: g, valueInit: C, selectInit: P, handleSourceItems: x, findFromList: U, getPlaceholder: M, componentAPI: B };
    return Object.defineProperty(r, "__isScriptSetup", { enumerable: !1, value: !0 }), r;
  }
}), _hoisted_1$5 = { ref: "root" }, _hoisted_2$5 = ["data-level"], _hoisted_3$5 = ["for"], _hoisted_4$5 = { class: "col c-cascade-select__input" }, _hoisted_5$5 = ["id", "disabled", "onChange"], _hoisted_6$5 = { value: "" }, _hoisted_7$5 = ["value", "selected"], _hoisted_8$5 = ["name", "value"];
function _sfc_render$5(l, e, n, t, u, y) {
  return openBlock(), createElementBlock("div", _hoisted_1$5, [
    (openBlock(!0), createElementBlock(Fragment, null, renderList(t.lists, (o, d) => (openBlock(), createElementBlock("div", {
      class: normalizeClass(["form-group row mb-2", [t.opt.horizontal ? t.opt.horizontalColWidth || "col" : ""]]),
      key: o,
      "data-level": d
    }, [
      createElementVNode("label", {
        for: t.getId(d),
        class: normalizeClass(["c-cascade-select__label mb-2", t.opt.labelWidth || "col-md-3"])
      }, toDisplayString(t.getLabel(d)), 11, _hoisted_3$5),
      e[1] || (e[1] = createTextVNode()),
      createElementVNode("div", _hoisted_4$5, [
        createElementVNode("select", {
          id: t.getId(d),
          disabled: !t.canModify,
          class: "form-select custom-select",
          ref_for: !0,
          ref: (s) => t.selects[d] = s,
          onChange: (s) => t.onChange(d, s)
        }, [
          createElementVNode("option", _hoisted_6$5, toDisplayString(t.getPlaceholder(d)), 1),
          e[0] || (e[0] = createTextVNode()),
          (openBlock(!0), createElementBlock(Fragment, null, renderList(o, (s) => (openBlock(), createElementBlock("option", {
            value: s[t.opt.valueField],
            key: s[t.opt.valueField],
            selected: t.isSelected(d, s)
          }, toDisplayString(s[t.opt.textField]), 9, _hoisted_7$5))), 128))
        ], 40, _hoisted_5$5)
      ])
    ], 10, _hoisted_2$5))), 128)),
    e[2] || (e[2] = createTextVNode()),
    createElementVNode("input", {
      name: t.props.name,
      type: "hidden",
      value: t.getFinalValue()
    }, null, 8, _hoisted_8$5)
  ], 512);
}
const CascadeSelect__Tmp70022 = /* @__PURE__ */ _export_sfc(_sfc_main$5, [["render", _sfc_render$5], ["__file", "CascadeSelect.vue"]]), _sfc_main$4 = /* @__PURE__ */ defineComponent({
  __name: "AddressForm",
  props: /* @__PURE__ */ mergeModels({
    type: {},
    user: {},
    syncData: {},
    title: {},
    syncLabel: {}
  }, {
    modelValue: {
      required: !0
    },
    modelModifiers: {}
  }),
  emits: /* @__PURE__ */ mergeModels(["validated"], ["update:modelValue"]),
  setup(l, { expose: e, emit: n }) {
    const t = resolveVueComponent("CascadeSelect", CascadeSelect__Tmp70022), u = l, y = {
      // addressId: '',
      // locationId: 0,
      // firstname: '',
      // lastname: '',
      // name: '',
      // email: '',
      // phone: '',
      // mobile: '',
      // company: '',
      // vat: '',
      // country: '',
      // state: '',
      // city: '',
      // postcode: '',
      // address1: '',
      // address2: '',
      // save: false,
    }, o = n, d = useModel(l, "modelValue"), s = ref(!1), p = ref(u.syncData == null ? "initializing" : "sync"), f = ref([]), T = {
      ajaxUrl: route("@address_ajax/locationOptions"),
      labels: data("location.labels") || [],
      placeholder: __("unicorn.select.placeholder"),
      onSelectInit(r) {
        const i = r.detail.el;
        useTomSelect(i);
      }
    };
    d.value = Object.assign(
      {},
      y,
      {
        firstName: u.user?.firstname || "",
        lastName: u.user?.lastname || "",
        name: u.user?.name || ""
      },
      d.value
    );
    const F = ref([]), E = ref(""), k = ref(u.syncData != null), b = ref(!1), I = ref(), L = ref(), D = useTemplateRef("modal");
    (!d.value || Object.keys(d.value).length === 0) && g().then((r) => {
      const i = r[0] || null;
      i && (d.value = x(i));
    }), onMounted(async () => {
      if (k.value)
        p.value = "form";
      else {
        const r = await g();
        let i;
        d.value.id && (i = r.find((c) => String(c.id) === String(d.value.id))), i || (i = r[0]), i && U(i), p.value = "selected";
      }
      M();
    });
    function H() {
      if (k.value)
        return !0;
      if (I.value) {
        let r = !0;
        const i = I.value.querySelectorAll("input,textarea,select");
        for (const c of i)
          if (!c.checkValidity()) {
            r = r && !1, c.reportValidity();
            break;
          }
        return o("validated", r), r;
      }
      return !0;
    }
    watch(() => u.syncData, async () => {
      k.value && u.syncData && j();
    }, { deep: !0, immediate: !0 }), watch(k, (r) => {
      r ? u.syncData || u.syncData && (p.value = "sync", j()) : (p.value = "form", d.value.id = void 0, d.value.addressId = void 0);
    });
    function j() {
      d.value = JSON.parse(JSON.stringify(u.syncData || {}));
    }
    const R = computed(() => E.value !== h.hashStr(JSON.stringify(d.value)));
    function Q(r) {
      r.detail && (d.value.locationId = r.detail.value, f.value = r.detail.path);
    }
    function S(r) {
      return `input-${u.type}-${r}`;
    }
    function A(r) {
      return `checkout[${u.type}_data][${r}]`;
    }
    function w() {
      p.value = "new", f.value = [], d.value = Object.assign({}, y);
    }
    async function g() {
      const { get: r } = await useHttpClient();
      return (await r("@address_ajax/myAddresses")).data.data;
    }
    async function C() {
      s.value = !0, Modal.getOrCreateInstance(D.value).show();
      try {
        F.value = await g();
      } finally {
        s.value = !1;
      }
    }
    async function P(r) {
      b.value = !0, d.value = Object.assign(
        {},
        y,
        r
      ), await U(r), E.value = h.hashStr(JSON.stringify(d.value)), await M(), b.value = !1;
    }
    function x(r) {
      return r.locationPath = r.locationPath.map((i) => String(i)), r.addressId = String(r.id), r;
    }
    async function U(r) {
      const i = Object.assign(
        {},
        y,
        r
      );
      d.value = x(i), Modal.getOrCreateInstance(D.value).hide(), await M();
    }
    async function M() {
      f.value = d.value.locationPath || [], await nextTick(), await L.value?.prepareValues();
    }
    e({
      validate: H
    });
    const B = { props: u, defaultAddress: y, emit: o, modelValue: d, addressLoading: s, currentState: p, locationPath: f, cascadeOptions: T, addresses: F, currentAddressHash: E, sync: k, addressSelecting: b, form: I, locationSelector: L, modalElement: D, validate: H, syncAddressFromOutside: j, showSaveButton: R, locationChanged: Q, buildInputId: S, buildInputName: A, createNew: w, findMyAddress: g, openAddressSelector: C, selectAddress: P, prepareAddressData: x, setAddressToData: U, updateLocationList: M, CascadeSelect: t };
    return Object.defineProperty(B, "__isScriptSetup", { enumerable: !1, value: !0 }), B;
  }
}), _hoisted_1$4 = { class: "card mb-4" }, _hoisted_2$4 = { class: "card-body" }, _hoisted_3$4 = { class: "card-title d-flex justify-content-between" }, _hoisted_4$4 = { class: "d-flex align-items-center gap-3" }, _hoisted_5$4 = { class: "m-0" }, _hoisted_6$4 = {
  key: 0,
  class: "form-check"
}, _hoisted_7$4 = ["for"], _hoisted_8$4 = ["id", "name"], _hoisted_9$4 = { key: 0 }, _hoisted_10$4 = { key: 0 }, _hoisted_11$4 = {
  key: 1,
  class: "mt-3",
  style: { "animation-duration": ".3s" }
}, _hoisted_12$4 = {
  key: 2,
  class: "row mt-3",
  style: { "animation-duration": ".3s" },
  ref: "form"
}, _hoisted_13$4 = { class: "col-lg-5" }, _hoisted_14$3 = { class: "form-group row mb-4" }, _hoisted_15$3 = ["for"], _hoisted_16$2 = { class: "col-9" }, _hoisted_17$2 = ["id", "name"], _hoisted_18$2 = { class: "form-group row mb-4" }, _hoisted_19$2 = ["for"], _hoisted_20$2 = { class: "col-9" }, _hoisted_21$2 = ["id", "name"], _hoisted_22$2 = { class: "form-group row mb-4" }, _hoisted_23$2 = ["for"], _hoisted_24$2 = { class: "col-9" }, _hoisted_25$2 = ["id", "name"], _hoisted_26$2 = { class: "form-group row mb-4" }, _hoisted_27$2 = ["for"], _hoisted_28$2 = { class: "col-9" }, _hoisted_29$2 = ["id", "name"], _hoisted_30$2 = { class: "form-group row mb-4" }, _hoisted_31$2 = ["for"], _hoisted_32$2 = { class: "col-9" }, _hoisted_33$2 = ["id", "name"], _hoisted_34$2 = { class: "form-group row mb-4" }, _hoisted_35$2 = ["for"], _hoisted_36$2 = { class: "col-9" }, _hoisted_37$2 = ["id", "name"], _hoisted_38$2 = { class: "form-group row mb-4" }, _hoisted_39$1 = ["for"], _hoisted_40$1 = { class: "col-9" }, _hoisted_41$1 = ["id", "name"], _hoisted_42$1 = { class: "col-lg-7 mb-4 mb-lg-0" }, _hoisted_43$1 = { class: "form-group mb-4" }, _hoisted_44$1 = ["for"], _hoisted_45$1 = { class: "form-group row mb-4" }, _hoisted_46$1 = ["for"], _hoisted_47$1 = { class: "col-9" }, _hoisted_48$1 = ["id", "name"], _hoisted_49$1 = { class: "form-group row mb-4" }, _hoisted_50$1 = ["for"], _hoisted_51$1 = { class: "col-9" }, _hoisted_52$1 = ["id", "name"], _hoisted_53$1 = { class: "form-group row mb-4" }, _hoisted_54$1 = ["for"], _hoisted_55$1 = { class: "col-9" }, _hoisted_56$1 = ["id", "name"], _hoisted_57$1 = {
  key: 0,
  class: "form-group row mb-4"
}, _hoisted_58$1 = ["for"], _hoisted_59 = { class: "col-9" }, _hoisted_60 = ["id", "name"], _hoisted_61 = { class: "d-none" }, _hoisted_62 = ["id", "name"], _hoisted_63 = ["id"], _hoisted_64 = {
  class: "modal-dialog",
  role: "document"
}, _hoisted_65 = { class: "modal-content" }, _hoisted_66 = { class: "modal-header" }, _hoisted_67 = {
  class: "modal-title",
  id: "address-modal-label"
}, _hoisted_68 = { class: "modal-body" }, _hoisted_69 = {
  key: 0,
  class: "list-group list-group-flush"
}, _hoisted_70 = ["onClick"], _hoisted_71 = { class: "btn btn-outline-secondary btn-sm text-nowrap" }, _hoisted_72 = {
  key: 1,
  class: "card bg-light text-center py-5"
}, _hoisted_73 = {
  key: 0,
  class: "spinner spinner-border mx-auto"
};
function _sfc_render$4(l, e, n, t, u, y) {
  return openBlock(), createElementBlock("div", _hoisted_1$4, [
    createElementVNode("div", _hoisted_2$4, [
      createElementVNode("div", _hoisted_3$4, [
        createElementVNode("div", _hoisted_4$4, [
          createElementVNode("h4", _hoisted_5$4, toDisplayString(n.title), 1),
          e[15] || (e[15] = createTextVNode()),
          n.syncData ? (openBlock(), createElementBlock("div", _hoisted_6$4, [
            createElementVNode("label", {
              for: `input-${n.type}-sync`,
              class: "form-check-label"
            }, toDisplayString(n.syncLabel || l.$lang("shopgo.cart.address.form.same.with.buyer")), 9, _hoisted_7$4),
            e[14] || (e[14] = createTextVNode()),
            withDirectives(createElementVNode("input", {
              type: "checkbox",
              "onUpdate:modelValue": e[0] || (e[0] = (o) => t.sync = o),
              id: `input-${n.type}-sync`,
              name: t.buildInputName("sync"),
              class: "form-check-input",
              value: "1"
            }, null, 8, _hoisted_8$4), [
              [vModelCheckbox, t.sync]
            ])
          ])) : createCommentVNode("", !0)
        ]),
        e[17] || (e[17] = createTextVNode()),
        n.user && !t.sync ? (openBlock(), createElementBlock("div", _hoisted_9$4, [
          createElementVNode("button", {
            type: "button",
            class: "btn btn-outline-success btn-sm",
            style: { "min-width": "100px" },
            onClick: t.createNew
          }, toDisplayString(l.$lang("shopgo.cart.address.form.new.address")), 1),
          e[16] || (e[16] = createTextVNode()),
          createElementVNode("button", {
            type: "button",
            class: "btn btn-outline-primary btn-sm",
            style: { "min-width": "100px" },
            onClick: t.openAddressSelector
          }, toDisplayString(l.$lang("shopgo.cart.address.form.select")), 1)
        ])) : createCommentVNode("", !0)
      ]),
      e[42] || (e[42] = createTextVNode()),
      createVNode(Transition, {
        name: "fade",
        mode: "out-in"
      }, {
        default: withCtx(() => [
          t.currentState === "initializing" ? (openBlock(), createElementBlock("div", _hoisted_10$4, [...e[18] || (e[18] = [
            createElementVNode("div", { class: "placeholder-glow" }, [
              createElementVNode("span", { class: "placeholder col-7" })
            ], -1)
          ])])) : !t.sync && t.modelValue.addressId ? (openBlock(), createElementBlock("div", _hoisted_11$4, toDisplayString(t.modelValue.formatted), 1)) : !t.sync && !t.modelValue.addressId ? (openBlock(), createElementBlock("div", _hoisted_12$4, [
            createElementVNode("div", _hoisted_13$4, [
              createElementVNode("div", _hoisted_14$3, [
                createElementVNode("label", {
                  for: t.buildInputId("firstname"),
                  class: "form-label col-3"
                }, toDisplayString(l.$lang("shopgo.address.field.firstname")), 9, _hoisted_15$3),
                e[19] || (e[19] = createTextVNode()),
                createElementVNode("div", _hoisted_16$2, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("firstname"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("firstname"),
                    required: "",
                    "onUpdate:modelValue": e[1] || (e[1] = (o) => t.modelValue.firstname = o)
                  }, null, 8, _hoisted_17$2), [
                    [vModelText, t.modelValue.firstname]
                  ])
                ])
              ]),
              e[26] || (e[26] = createTextVNode()),
              createElementVNode("div", _hoisted_18$2, [
                createElementVNode("label", {
                  for: t.buildInputId("lastname"),
                  class: "form-label col-3"
                }, toDisplayString(l.$lang("shopgo.address.field.lastname")), 9, _hoisted_19$2),
                e[20] || (e[20] = createTextVNode()),
                createElementVNode("div", _hoisted_20$2, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("lastname"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("lastname"),
                    required: "",
                    "onUpdate:modelValue": e[2] || (e[2] = (o) => t.modelValue.lastname = o)
                  }, null, 8, _hoisted_21$2), [
                    [vModelText, t.modelValue.lastname]
                  ])
                ])
              ]),
              e[27] || (e[27] = createTextVNode()),
              createElementVNode("div", _hoisted_22$2, [
                createElementVNode("label", {
                  for: t.buildInputId("email"),
                  class: "form-label col-3"
                }, toDisplayString(l.$lang("shopgo.address.field.email")), 9, _hoisted_23$2),
                e[21] || (e[21] = createTextVNode()),
                createElementVNode("div", _hoisted_24$2, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("email"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("email"),
                    required: "",
                    "onUpdate:modelValue": e[3] || (e[3] = (o) => t.modelValue.email = o)
                  }, null, 8, _hoisted_25$2), [
                    [vModelText, t.modelValue.email]
                  ])
                ])
              ]),
              e[28] || (e[28] = createTextVNode()),
              createElementVNode("div", _hoisted_26$2, [
                createElementVNode("label", {
                  for: t.buildInputId("phone"),
                  class: "form-label col-3"
                }, toDisplayString(l.$lang("shopgo.address.field.phone")), 9, _hoisted_27$2),
                e[22] || (e[22] = createTextVNode()),
                createElementVNode("div", _hoisted_28$2, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("phone"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("phone"),
                    "onUpdate:modelValue": e[4] || (e[4] = (o) => t.modelValue.phone = o)
                  }, null, 8, _hoisted_29$2), [
                    [vModelText, t.modelValue.phone]
                  ])
                ])
              ]),
              e[29] || (e[29] = createTextVNode()),
              createElementVNode("div", _hoisted_30$2, [
                createElementVNode("label", {
                  for: t.buildInputId("mobile"),
                  class: "form-label col-3"
                }, toDisplayString(l.$lang("shopgo.address.field.mobile")), 9, _hoisted_31$2),
                e[23] || (e[23] = createTextVNode()),
                createElementVNode("div", _hoisted_32$2, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("mobile"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("mobile"),
                    required: "",
                    "onUpdate:modelValue": e[5] || (e[5] = (o) => t.modelValue.mobile = o)
                  }, null, 8, _hoisted_33$2), [
                    [vModelText, t.modelValue.mobile]
                  ])
                ])
              ]),
              e[30] || (e[30] = createTextVNode()),
              createElementVNode("div", _hoisted_34$2, [
                createElementVNode("label", {
                  for: t.buildInputId("company"),
                  class: "form-label col-3"
                }, toDisplayString(l.$lang("shopgo.address.field.company")), 9, _hoisted_35$2),
                e[24] || (e[24] = createTextVNode()),
                createElementVNode("div", _hoisted_36$2, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("company"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("company"),
                    "onUpdate:modelValue": e[6] || (e[6] = (o) => t.modelValue.company = o)
                  }, null, 8, _hoisted_37$2), [
                    [vModelText, t.modelValue.company]
                  ])
                ])
              ]),
              e[31] || (e[31] = createTextVNode()),
              createElementVNode("div", _hoisted_38$2, [
                createElementVNode("label", {
                  for: t.buildInputId("vat"),
                  class: "form-label col-3"
                }, toDisplayString(l.$lang("shopgo.address.field.vat")), 9, _hoisted_39$1),
                e[25] || (e[25] = createTextVNode()),
                createElementVNode("div", _hoisted_40$1, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("vat"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("vat"),
                    "onUpdate:modelValue": e[7] || (e[7] = (o) => t.modelValue.vat = o)
                  }, null, 8, _hoisted_41$1), [
                    [vModelText, t.modelValue.vat]
                  ])
                ])
              ])
            ]),
            e[41] || (e[41] = createTextVNode()),
            createElementVNode("div", _hoisted_42$1, [
              createElementVNode("div", _hoisted_43$1, [
                createElementVNode("label", {
                  for: t.buildInputId("country"),
                  class: "form-label"
                }, toDisplayString(l.$lang("shopgo.address.field.country")), 9, _hoisted_44$1),
                e[32] || (e[32] = createTextVNode()),
                createVNode(t.CascadeSelect, {
                  options: t.cascadeOptions,
                  modelValue: t.locationPath,
                  "onUpdate:modelValue": e[8] || (e[8] = (o) => t.locationPath = o),
                  onChange: t.locationChanged,
                  name: t.buildInputName("location_id"),
                  ref: "locationSelector"
                }, null, 8, ["modelValue", "name"])
              ]),
              e[37] || (e[37] = createTextVNode()),
              createElementVNode("div", _hoisted_45$1, [
                createElementVNode("label", {
                  for: t.buildInputId("postcode"),
                  class: "form-label col-3"
                }, toDisplayString(l.$lang("shopgo.address.field.postcode")), 9, _hoisted_46$1),
                e[33] || (e[33] = createTextVNode()),
                createElementVNode("div", _hoisted_47$1, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("postcode"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("postcode"),
                    "onUpdate:modelValue": e[9] || (e[9] = (o) => t.modelValue.postcode = o),
                    maxlength: "10"
                  }, null, 8, _hoisted_48$1), [
                    [vModelText, t.modelValue.postcode]
                  ])
                ])
              ]),
              e[38] || (e[38] = createTextVNode()),
              createElementVNode("div", _hoisted_49$1, [
                createElementVNode("label", {
                  for: t.buildInputId("address1"),
                  class: "form-label col-3"
                }, toDisplayString(l.$lang("shopgo.address.field.address1")), 9, _hoisted_50$1),
                e[34] || (e[34] = createTextVNode()),
                createElementVNode("div", _hoisted_51$1, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("address1"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("address1"),
                    required: "",
                    "onUpdate:modelValue": e[10] || (e[10] = (o) => t.modelValue.address1 = o)
                  }, null, 8, _hoisted_52$1), [
                    [vModelText, t.modelValue.address1]
                  ])
                ])
              ]),
              e[39] || (e[39] = createTextVNode()),
              createElementVNode("div", _hoisted_53$1, [
                createElementVNode("label", {
                  for: t.buildInputId("address2"),
                  class: "form-label col-3"
                }, toDisplayString(l.$lang("shopgo.address.field.address2")), 9, _hoisted_54$1),
                e[35] || (e[35] = createTextVNode()),
                createElementVNode("div", _hoisted_55$1, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("address2"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("address2"),
                    "onUpdate:modelValue": e[11] || (e[11] = (o) => t.modelValue.address2 = o)
                  }, null, 8, _hoisted_56$1), [
                    [vModelText, t.modelValue.address2]
                  ])
                ])
              ]),
              e[40] || (e[40] = createTextVNode()),
              t.showSaveButton ? (openBlock(), createElementBlock("div", _hoisted_57$1, [
                createElementVNode("label", {
                  for: t.buildInputId("save"),
                  class: "form-label col-3"
                }, toDisplayString(l.$lang("shopgo.cart.address.form.save.for.next")), 9, _hoisted_58$1),
                e[36] || (e[36] = createTextVNode()),
                createElementVNode("div", _hoisted_59, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("save"),
                    type: "checkbox",
                    class: "form-check-input",
                    name: t.buildInputName("save"),
                    value: 1,
                    "onUpdate:modelValue": e[12] || (e[12] = (o) => t.modelValue.save = o)
                  }, null, 8, _hoisted_60), [
                    [vModelCheckbox, t.modelValue.save]
                  ])
                ])
              ])) : createCommentVNode("", !0)
            ])
          ], 512)) : createCommentVNode("", !0)
        ]),
        _: 1
      }),
      e[43] || (e[43] = createTextVNode()),
      createElementVNode("div", _hoisted_61, [
        withDirectives(createElementVNode("input", {
          id: t.buildInputId("addressId"),
          type: "hidden",
          name: t.buildInputName("addressId"),
          "onUpdate:modelValue": e[13] || (e[13] = (o) => t.modelValue.addressId = o)
        }, null, 8, _hoisted_62), [
          [vModelText, t.modelValue.addressId]
        ])
      ])
    ]),
    e[48] || (e[48] = createTextVNode()),
    createElementVNode("div", {
      ref: "modal",
      class: "modal fade",
      id: `${n.type}-address-modal`,
      tabindex: "-1",
      role: "dialog",
      "aria-labelledby": "address-modal-label",
      "aria-hidden": "true"
    }, [
      createElementVNode("div", _hoisted_64, [
        createElementVNode("div", _hoisted_65, [
          createElementVNode("div", _hoisted_66, [
            createElementVNode("h4", _hoisted_67, toDisplayString(l.$lang("shopgo.cart.address.form.modal.title")), 1),
            e[44] || (e[44] = createTextVNode()),
            e[45] || (e[45] = createElementVNode("button", {
              type: "button",
              class: "close btn-close",
              "data-bs-dismiss": "modal",
              "data-dismiss": "modal",
              "aria-label": "Close"
            }, [
              createElementVNode("span", {
                "aria-hidden": "true",
                class: "visually-hidden"
              }, "×")
            ], -1))
          ]),
          e[47] || (e[47] = createTextVNode()),
          createElementVNode("div", _hoisted_68, [
            !t.addressLoading && t.addresses.length ? (openBlock(), createElementBlock("div", _hoisted_69, [
              (openBlock(!0), createElementBlock(Fragment, null, renderList(t.addresses, (o) => (openBlock(), createElementBlock("a", {
                href: "javascript://",
                class: "list-group-item d-flex gap-2 justify-content-between",
                key: o,
                onClick: (d) => t.selectAddress(o)
              }, [
                createElementVNode("div", null, toDisplayString(o.formatted), 1),
                e[46] || (e[46] = createTextVNode()),
                createElementVNode("div", null, [
                  createElementVNode("span", _hoisted_71, toDisplayString(l.$lang("shopgo.cart.address.form.button.select")), 1)
                ])
              ], 8, _hoisted_70))), 128))
            ])) : (openBlock(), createElementBlock("div", _hoisted_72, [
              t.addressLoading ? (openBlock(), createElementBlock("span", _hoisted_73)) : (openBlock(), createElementBlock(Fragment, { key: 1 }, [
                createTextVNode(toDisplayString(l.$lang("shopgo.cart.address.form.no.addresses")), 1)
              ], 64))
            ]))
          ])
        ])
      ])
    ], 8, _hoisted_63)
  ]);
}
const AddressForm__Tmp22267 = /* @__PURE__ */ _export_sfc(_sfc_main$4, [["render", _sfc_render$4], ["__file", "AddressForm.vue"]]), _sfc_main$3 = /* @__PURE__ */ defineComponent({
  __name: "CartListItem",
  props: {
    item: {},
    hasCheckbox: { type: Boolean }
  },
  emits: ["remove-item", "update-quantities", "change-item-quantity", "update-checks"],
  setup(l, { expose: e, emit: n }) {
    e();
    const t = l, u = n;
    function y() {
      u("remove-item");
    }
    function o() {
      u("update-quantities");
    }
    function d(f) {
      u("change-item-quantity", f);
    }
    function s() {
      u("update-checks");
    }
    const p = { props: t, emits: u, removeItem: y, updateQuantities: o, changeItemQuantity: d, updateChecks: s };
    return Object.defineProperty(p, "__isScriptSetup", { enumerable: !1, value: !0 }), p;
  }
}), _hoisted_1$3 = ["data-product-id", "data-variant-id"], _hoisted_2$3 = { class: "card-body d-grid d-lg-flex gap-3" }, _hoisted_3$3 = { class: "d-flex gap-3 me-auto" }, _hoisted_4$3 = {
  key: 0,
  class: "c-cart-item__checkbox"
}, _hoisted_5$3 = { class: "c-cart-item__image" }, _hoisted_6$3 = {
  style: { width: "75px" },
  class: "ratio ratio-1x1"
}, _hoisted_7$3 = ["src", "alt"], _hoisted_8$3 = { class: "c-cart-item__content" }, _hoisted_9$3 = ["href"], _hoisted_10$3 = {
  key: 0,
  class: "fs-6 text-muted"
}, _hoisted_11$3 = { class: "text-muted small" }, _hoisted_12$3 = { key: 1 }, _hoisted_13$3 = { class: "badge bg-danger" }, _hoisted_14$2 = { class: "c-cart-item__quantity d-flex gap-2" }, _hoisted_15$2 = { class: "" }, _hoisted_16$1 = { class: "input-group flex-nowrap" }, _hoisted_17$1 = { class: "d-flex gap-3" }, _hoisted_18$1 = {
  class: "c-cart-item__price text-end text-nowrap",
  style: { "min-width": "135px" }
}, _hoisted_19$1 = {
  key: 0,
  class: "small text-muted"
}, _hoisted_20$1 = { class: "fs-5" }, _hoisted_21$1 = {
  key: 0,
  class: "card-footer py-4 px-3 px-lg-5"
}, _hoisted_22$1 = ["data-product-id", "data-variant-id"], _hoisted_23$1 = { class: "d-flex gap-3 flex-grow-1 align-items-center" }, _hoisted_24$1 = { class: "c-attachment__image" }, _hoisted_25$1 = {
  style: { width: "45px" },
  class: "ratio ratio-1x1"
}, _hoisted_26$1 = ["src", "alt"], _hoisted_27$1 = { class: "c-attachment__content" }, _hoisted_28$1 = { class: "fs-6 mb-0" }, _hoisted_29$1 = {
  key: 0,
  class: "text-muted small"
}, _hoisted_30$1 = {
  key: 0,
  class: "badge bg-danger"
}, _hoisted_31$1 = { class: "c-attachment__quantity ms-auto" }, _hoisted_32$1 = { class: "d-flex gap-3 ms-auto ms-lg-0" }, _hoisted_33$1 = {
  class: "c-attachment__total d-flex justify-content-end gap-3",
  style: { width: "250px" }
}, _hoisted_34$1 = {
  class: "c-cart-item__price d-flex align-items-center gap-2 text-end text-nowrap",
  style: { "min-width": "135px" }
}, _hoisted_35$1 = {
  key: 0,
  class: "small text-muted"
}, _hoisted_36$1 = { class: "" }, _hoisted_37$1 = { class: "mt-3 text-end fs-5" }, _hoisted_38$1 = { class: "" };
function _sfc_render$3(l, e, n, t, u, y) {
  return openBlock(), createElementBlock("div", {
    class: "c-cart-item card mb-3",
    "data-product-id": n.item.product.id,
    "data-variant-id": n.item.variant.id
  }, [
    createElementVNode("div", _hoisted_2$3, [
      createElementVNode("div", _hoisted_3$3, [
        n.hasCheckbox ? (openBlock(), createElementBlock("div", _hoisted_4$3, [
          withDirectives(createElementVNode("input", {
            type: "checkbox",
            class: "form-check-input",
            "onUpdate:modelValue": e[0] || (e[0] = (o) => n.item.options.checked = o),
            onChange: t.updateChecks
          }, null, 544), [
            [vModelCheckbox, n.item.options.checked]
          ])
        ])) : createCommentVNode("", !0),
        e[6] || (e[6] = createTextVNode()),
        createElementVNode("div", _hoisted_5$3, [
          createElementVNode("div", _hoisted_6$3, [
            createElementVNode("img", {
              class: "object-fit-cover",
              src: n.item.cover,
              alt: n.item.product.title,
              style: {}
            }, null, 8, _hoisted_7$3)
          ])
        ]),
        e[7] || (e[7] = createTextVNode()),
        createElementVNode("div", _hoisted_8$3, [
          createElementVNode("h5", null, [
            createElementVNode("a", {
              href: n.item.link,
              target: "_blank"
            }, toDisplayString(n.item.product.title), 9, _hoisted_9$3)
          ]),
          e[4] || (e[4] = createTextVNode()),
          n.item.variant.primary ? createCommentVNode("", !0) : (openBlock(), createElementBlock("div", _hoisted_10$3, toDisplayString(n.item.variant.title), 1)),
          e[5] || (e[5] = createTextVNode()),
          createElementVNode("div", _hoisted_11$3, toDisplayString(n.item.product.model), 1)
        ]),
        e[8] || (e[8] = createTextVNode()),
        n.item.outOfStock ? (openBlock(), createElementBlock("div", _hoisted_12$3, [
          createElementVNode("span", _hoisted_13$3, toDisplayString(l.$lang("shopgo.message.out.of.stock")), 1)
        ])) : createCommentVNode("", !0)
      ]),
      e[16] || (e[16] = createTextVNode()),
      createElementVNode("div", _hoisted_14$2, [
        createElementVNode("div", _hoisted_15$2, [
          createElementVNode("div", _hoisted_16$1, [
            createElementVNode("button", {
              type: "button",
              class: "btn btn-secondary btn-sm",
              onClick: e[1] || (e[1] = (o) => t.changeItemQuantity(-1))
            }, [...e[9] || (e[9] = [
              createElementVNode("i", { class: "fa fa-minus" }, null, -1)
            ])]),
            e[11] || (e[11] = createTextVNode()),
            withDirectives(createElementVNode("input", {
              type: "text",
              class: "form-control form-control-sm",
              "onUpdate:modelValue": e[2] || (e[2] = (o) => n.item.quantity = o),
              onChange: t.updateQuantities,
              style: { width: "75px" }
            }, null, 544), [
              [
                vModelText,
                n.item.quantity,
                void 0,
                { number: !0 }
              ]
            ]),
            e[12] || (e[12] = createTextVNode()),
            createElementVNode("button", {
              type: "button",
              class: "btn btn-secondary btn-sm",
              onClick: e[3] || (e[3] = (o) => t.changeItemQuantity(1))
            }, [...e[10] || (e[10] = [
              createElementVNode("i", { class: "fa fa-plus" }, null, -1)
            ])])
          ])
        ])
      ]),
      e[17] || (e[17] = createTextVNode()),
      createElementVNode("div", _hoisted_17$1, [
        createElementVNode("div", _hoisted_18$1, [
          n.item.priceSet.base_total.price !== n.item.priceSet.final_total.price ? (openBlock(), createElementBlock("div", _hoisted_19$1, [
            createElementVNode("del", null, toDisplayString(l.$formatPrice(n.item.priceSet.base_total.price)), 1)
          ])) : createCommentVNode("", !0),
          e[13] || (e[13] = createTextVNode()),
          createElementVNode("div", _hoisted_20$1, toDisplayString(l.$formatPrice(n.item.priceSet.final_total.price, { code: !0 })), 1)
        ]),
        e[15] || (e[15] = createTextVNode()),
        createElementVNode("div", { class: "c-cart-item__actions ms-auto" }, [
          createElementVNode("button", {
            type: "button",
            class: "btn btn-link link-secondary btn-sm",
            onClick: t.removeItem
          }, [...e[14] || (e[14] = [
            createElementVNode("i", { class: "fa fa-trash" }, null, -1)
          ])])
        ])
      ])
    ]),
    e[27] || (e[27] = createTextVNode()),
    n.item.attachments.length > 0 ? (openBlock(), createElementBlock("div", _hoisted_21$1, [
      createElementVNode("h6", null, toDisplayString(l.$lang("shopgo.cart.title.attachments")), 1),
      e[25] || (e[25] = createTextVNode()),
      (openBlock(!0), createElementBlock(Fragment, null, renderList(n.item.attachments, (o) => (openBlock(), createElementBlock("div", {
        class: "c-attachment w-100 d-grid d-lg-flex gap-3 align-items-center py-2 border-bottom",
        "data-product-id": o.product.id,
        "data-variant-id": o.variant.id
      }, [
        createElementVNode("div", _hoisted_23$1, [
          createElementVNode("div", _hoisted_24$1, [
            createElementVNode("div", _hoisted_25$1, [
              createElementVNode("img", {
                class: "object-fit-cover",
                src: o.cover,
                alt: o.product.title,
                style: {}
              }, null, 8, _hoisted_26$1)
            ])
          ]),
          e[19] || (e[19] = createTextVNode()),
          createElementVNode("div", _hoisted_27$1, [
            createElementVNode("h5", _hoisted_28$1, toDisplayString(o.product.title), 1),
            e[18] || (e[18] = createTextVNode()),
            o.variant.primary ? createCommentVNode("", !0) : (openBlock(), createElementBlock("div", _hoisted_29$1, toDisplayString(o.variant.title), 1))
          ]),
          e[20] || (e[20] = createTextVNode()),
          o.outOfStock ? (openBlock(), createElementBlock("span", _hoisted_30$1, toDisplayString(l.$lang("shopgo.message.out.of.stock")), 1)) : createCommentVNode("", !0),
          e[21] || (e[21] = createTextVNode()),
          createElementVNode("div", _hoisted_31$1, `\r
            x` + toDisplayString(o.quantity * n.item.quantity), 1)
        ]),
        e[23] || (e[23] = createTextVNode()),
        createElementVNode("div", _hoisted_32$1, [
          createElementVNode("div", _hoisted_33$1, [
            createElementVNode("div", _hoisted_34$1, [
              o.priceSet.base_total.price !== o.priceSet.final_total.price ? (openBlock(), createElementBlock("div", _hoisted_35$1, [
                createElementVNode("del", null, toDisplayString(l.$formatPrice(o.priceSet.base_total.price)), 1)
              ])) : createCommentVNode("", !0),
              e[22] || (e[22] = createTextVNode()),
              createElementVNode("div", _hoisted_36$1, toDisplayString(l.$formatPrice(o.priceSet.final_total.price)), 1)
            ])
          ])
        ])
      ], 8, _hoisted_22$1))), 256)),
      e[26] || (e[26] = createTextVNode()),
      createElementVNode("div", _hoisted_37$1, [
        createElementVNode("strong", null, toDisplayString(l.$lang("shopgo.cart.label.attached.product.total")), 1),
        e[24] || (e[24] = createTextVNode()),
        createElementVNode("span", _hoisted_38$1, toDisplayString(l.$formatPrice(n.item.priceSet.attached_final_total.price, { code: !0 })), 1)
      ])
    ])) : createCommentVNode("", !0)
  ], 8, _hoisted_1$3);
}
const CartListItem__Tmp46299 = /* @__PURE__ */ _export_sfc(_sfc_main$3, [["render", _sfc_render$3], ["__file", "CartListItem.vue"]]), _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "PaymentItem",
  props: {
    payment: {},
    i: {},
    selected: { type: Boolean }
  },
  emits: ["selected"],
  setup(l, { expose: e, emit: n }) {
    e();
    const t = l, u = n, y = ref(uid()), o = ref({}), d = ref(t.selected), s = ref(data("image.default"));
    watch(() => t.selected, () => {
      d.value = t.selected, setTimeout(() => {
        d.value ? slideDown(f.value) : slideUp(f.value);
      }, 0);
    });
    function p() {
      d.value = !0, u("selected");
    }
    const f = ref(), T = { props: t, emit: u, uidRef: y, data: o, selectedRef: d, imageDefault: s, onSelected: p, optionLayout: f };
    return Object.defineProperty(T, "__isScriptSetup", { enumerable: !1, value: !0 }), T;
  }
}), _hoisted_1$2 = { class: "card-body d-flex align-items-center gap-3" }, _hoisted_2$2 = { class: "form-check" }, _hoisted_3$2 = ["id", "value", "checked"], _hoisted_4$2 = ["for"], _hoisted_5$2 = { class: "" }, _hoisted_6$2 = {
  class: "ratio ratio-1x1",
  style: { width: "45px" }
}, _hoisted_7$2 = ["src"], _hoisted_8$2 = { class: "m-0" }, _hoisted_9$2 = {
  key: 0,
  class: "text-success"
}, _hoisted_10$2 = {
  key: 0,
  class: "card-body border-top ps-5"
}, _hoisted_11$2 = ["innerHTML"], _hoisted_12$2 = {
  ref: "optionLayout",
  style: { display: "none", overflow: "hidden", "animation-duration": ".3s" }
}, _hoisted_13$2 = ["innerHTML"];
function _sfc_render$2(l, e, n, t, u, y) {
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["card my-3", [t.selectedRef ? "border border-primary" : ""]])
  }, [
    createElementVNode("div", _hoisted_1$2, [
      createElementVNode("div", _hoisted_2$2, [
        createElementVNode("input", {
          type: "radio",
          id: `input-payment-id-${n.payment.id}`,
          name: "checkout[payment][id]",
          value: n.payment.id,
          class: "form-check-input",
          onChange: t.onSelected,
          checked: t.selectedRef
        }, null, 40, _hoisted_3$2),
        e[0] || (e[0] = createTextVNode()),
        createElementVNode("label", {
          for: `input-payment-id-${n.payment.id}`,
          class: "stretched-link",
          style: { cursor: "pointer" }
        }, null, 8, _hoisted_4$2)
      ]),
      e[2] || (e[2] = createTextVNode()),
      createElementVNode("div", _hoisted_5$2, [
        createElementVNode("div", _hoisted_6$2, [
          createElementVNode("img", {
            class: "object-fit-cover",
            src: n.payment.image || t.imageDefault,
            alt: "cover"
          }, null, 8, _hoisted_7$2)
        ])
      ]),
      e[3] || (e[3] = createTextVNode()),
      createElementVNode("div", null, [
        createElementVNode("h5", _hoisted_8$2, toDisplayString(n.payment.title), 1),
        e[1] || (e[1] = createTextVNode()),
        n.payment.subtitle ? (openBlock(), createElementBlock("div", _hoisted_9$2, toDisplayString(n.payment.subtitle), 1)) : createCommentVNode("", !0)
      ]),
      e[4] || (e[4] = createTextVNode()),
      e[5] || (e[5] = createElementVNode("div", { class: "ms-auto" }, null, -1))
    ]),
    e[6] || (e[6] = createTextVNode()),
    n.payment.description.trim() ? (openBlock(), createElementBlock("div", _hoisted_10$2, [
      createElementVNode("div", {
        class: "position-relative",
        style: { "z-index": "1" },
        innerHTML: n.payment.description
      }, null, 8, _hoisted_11$2)
    ])) : createCommentVNode("", !0),
    e[7] || (e[7] = createTextVNode()),
    createVNode(Transition, {
      name: "fade",
      mode: "out-in"
    }, {
      default: withCtx(() => [
        createElementVNode("div", _hoisted_12$2, [
          n.payment.optionLayout && t.selectedRef ? (openBlock(), createElementBlock("div", {
            key: 0,
            class: "card-body border-top",
            innerHTML: n.payment.optionLayout
          }, null, 8, _hoisted_13$2)) : createCommentVNode("", !0)
        ], 512)
      ]),
      _: 1
    })
  ], 2);
}
const PaymentItem__Tmp30223 = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["render", _sfc_render$2], ["__file", "PaymentItem.vue"]]), _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "ShippingItem",
  props: {
    shipping: {},
    i: {},
    selected: { type: Boolean }
  },
  emits: ["selected"],
  setup(__props, { expose: __expose, emit: __emit }) {
    __expose();
    const props = __props, emit = __emit, uidRef = ref(uid()), data$1 = ref({}), selectedRef = ref(props.selected), imageDefault = ref(data("image.default"));
    watch(() => props.selected, () => {
      selectedRef.value = props.selected, setTimeout(() => {
        if (selectedRef.value) {
          const scripts = form.value.querySelectorAll(".card-body script");
          for (const script of scripts)
            eval(script.textContent);
          slideDown(form.value);
        } else
          slideUp(form.value);
      }, 0);
    });
    function onSelected() {
      selectedRef.value = !0, emit("selected");
    }
    const form = ref(), __returned__ = { props, emit, uidRef, data: data$1, selectedRef, imageDefault, onSelected, form };
    return Object.defineProperty(__returned__, "__isScriptSetup", { enumerable: !1, value: !0 }), __returned__;
  }
}), _hoisted_1$1 = { class: "card-body d-flex align-items-center gap-3" }, _hoisted_2$1 = { class: "form-check" }, _hoisted_3$1 = ["id", "value", "checked"], _hoisted_4$1 = ["for"], _hoisted_5$1 = { class: "" }, _hoisted_6$1 = {
  class: "ratio ratio-1x1",
  style: { width: "45px" }
}, _hoisted_7$1 = ["src"], _hoisted_8$1 = { class: "m-0" }, _hoisted_9$1 = {
  key: 0,
  class: "text-success"
}, _hoisted_10$1 = { class: "ms-auto" }, _hoisted_11$1 = { class: "fs-5" }, _hoisted_12$1 = {
  key: 0,
  class: "card-body border-top ps-5"
}, _hoisted_13$1 = ["innerHTML"], _hoisted_14$1 = {
  ref: "form",
  style: { display: "none", postion: "relative", "z-index": "1", overflow: "hidden", "animation-duration": ".3s" }
}, _hoisted_15$1 = ["innerHTML"];
function _sfc_render$1(l, e, n, t, u, y) {
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["card my-3", [t.selectedRef ? "border border-primary" : ""]])
  }, [
    createElementVNode("div", _hoisted_1$1, [
      createElementVNode("div", _hoisted_2$1, [
        createElementVNode("input", {
          type: "radio",
          id: `input-shipping-id-${n.shipping.id}`,
          name: "checkout[shipping][id]",
          value: n.shipping.id,
          class: "form-check-input",
          onChange: t.onSelected,
          checked: t.selectedRef
        }, null, 40, _hoisted_3$1),
        e[0] || (e[0] = createTextVNode()),
        createElementVNode("label", {
          for: `input-shipping-id-${n.shipping.id}`,
          class: "stretched-link",
          style: { cursor: "pointer" }
        }, null, 8, _hoisted_4$1)
      ]),
      e[2] || (e[2] = createTextVNode()),
      createElementVNode("div", _hoisted_5$1, [
        createElementVNode("div", _hoisted_6$1, [
          createElementVNode("img", {
            src: n.shipping.image || t.imageDefault,
            alt: "cover"
          }, null, 8, _hoisted_7$1)
        ])
      ]),
      e[3] || (e[3] = createTextVNode()),
      createElementVNode("div", null, [
        createElementVNode("h5", _hoisted_8$1, toDisplayString(n.shipping.title), 1),
        e[1] || (e[1] = createTextVNode()),
        n.shipping.subtitle ? (openBlock(), createElementBlock("div", _hoisted_9$1, toDisplayString(n.shipping.subtitle), 1)) : createCommentVNode("", !0)
      ]),
      e[4] || (e[4] = createTextVNode()),
      createElementVNode("div", _hoisted_10$1, [
        createElementVNode("span", _hoisted_11$1, toDisplayString(l.$formatPrice(n.shipping.fee, !0)), 1)
      ])
    ]),
    e[5] || (e[5] = createTextVNode()),
    n.shipping.description.trim() ? (openBlock(), createElementBlock("div", _hoisted_12$1, [
      createElementVNode("div", {
        class: "position-relative",
        style: { "z-index": "1" },
        innerHTML: n.shipping.description
      }, null, 8, _hoisted_13$1)
    ])) : createCommentVNode("", !0),
    e[6] || (e[6] = createTextVNode()),
    createVNode(Transition, {
      name: "fade",
      mode: "out-in"
    }, {
      default: withCtx(() => [
        createElementVNode("div", _hoisted_14$1, [
          n.shipping.checkoutForm && t.selectedRef ? (openBlock(), createElementBlock("div", {
            key: 0,
            class: "card-body border-top",
            innerHTML: n.shipping.checkoutForm
          }, null, 8, _hoisted_15$1)) : createCommentVNode("", !0)
        ], 512)
      ]),
      _: 1
    })
  ], 2);
}
const ShippingItem__Tmp124 = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["render", _sfc_render$1], ["__file", "ShippingItem.vue"]]), _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "CartApp",
  props: {
    user: {},
    checkoutData: {}
  },
  setup(l, { expose: e }) {
    const n = resolveVueComponent("AddressForm", AddressForm__Tmp22267), t = resolveVueComponent("CartListItem", CartListItem__Tmp46299), u = resolveVueComponent("PaymentItem", PaymentItem__Tmp30223), y = resolveVueComponent("ShippingItem", ShippingItem__Tmp124);
    e();
    const o = l, d = ref(!1), s = ref([]), p = ref({}), f = ref([]), T = ref(o.checkoutData?.payment?.id || ""), F = ref(o.checkoutData?.payment_data || {}), E = ref(o.checkoutData?.shipping?.id || ""), k = ref(o.checkoutData?.shipping_data || {}), b = ref([]), I = ref([]), L = ref({}), D = ref(""), H = ref(o.checkoutData?.note || ""), j = ref(!1), R = ref(data("partial.checkout")), Q = useQueue("shopgo.cart");
    let S = null;
    const A = document.querySelector("#cart-form"), w = ref(), g = useStack("loading");
    g.observe((a, _) => {
      j.value = _ > 0;
    }), q();
    function C(a = 300) {
      setTimeout(() => {
        g.pop();
      }, a);
    }
    const P = debounce(function() {
      return x();
    }, 300);
    async function x(a = !0) {
      S?.abort("Cancel by next load"), S = new AbortController(), g.push(!0);
      const { get: _, isAxiosError: m, isCancel: v } = await useHttpClient();
      try {
        const V = await _(
          "@cart_ajax/getItems",
          {
            params: {
              location_id: k.value.locationId,
              shipping_id: E.value,
              payment_id: T.value
            },
            signal: S.signal
          }
        );
        return await U(V.data.data, a), V;
      } catch (V) {
        v(V) && console.log(V.message), console.error(V), m(V) && simpleAlert(V.message, "", "warning");
      } finally {
        C(), S = null;
      }
    }
    async function U(a, _ = !0) {
      if (s.value = a.items, p.value = a.totals, f.value = a.coupons, _)
        return await z();
    }
    watch(s, () => {
      i();
    }, { deep: !0 });
    const M = computed(() => s.value.map((a) => a.options.checked == null ? !0 : a.options.checked)), B = computed(() => M.value.filter((a) => a === !0).length), r = computed(() => M.value.filter((a) => a === !1).length);
    function i() {
      w.value && (w.value.checked = !1, w.value.indeterminate = !1, B.value > 0 && r.value === 0 ? w.value.checked = !0 : r.value > 0 && B.value === 0 ? w.value.checked = !1 : B.value > 0 && r.value > 0 && (w.value.indeterminate = !0));
    }
    function c() {
      if (w.value) {
        for (const a of s.value)
          a.options.checked = w.value.checked;
        N();
      }
    }
    const N = debounce(async () => {
      S?.abort("Cancel by next modify."), S = new AbortController();
      const a = {};
      for (const v of s.value)
        a[v.key] = v.options.checked ? "1" : "0";
      g.push(!0);
      const { post: _, isAxiosError: m } = await useHttpClient();
      try {
        const v = await _("@cart_ajax/updateChecks", { checks: a }, { signal: S.signal });
        return await x();
      } catch (v) {
        console.error(v), m(v) && simpleAlert(v.message, "", "warning");
      } finally {
        C(), S = null;
      }
    }, 300);
    onMounted(() => {
      $(A);
    });
    function $(a, _ = 30) {
      const m = document.querySelector("header .navbar, .navbar");
      if (!m)
        return;
      const v = m.clientHeight + _;
      a.style.setProperty("--sidebar-offsets-top", v + "px");
    }
    async function q() {
      await x(), d.value = !0;
    }
    async function O(a, _) {
      g.push(!0);
      const { delete: m, isAxiosError: v } = await useHttpClient();
      try {
        const V = await m(`@cart_ajax/removeItem?key=${a.key}`);
        return await P();
      } catch (V) {
        console.error(V), v(V) && simpleAlert(V.message, "", "warning");
      } finally {
        setTimeout(() => {
          g.pop();
        }, 300);
      }
    }
    async function ee() {
      g.push(!0);
      const { put: a, isAxiosError: _ } = await useHttpClient();
      try {
        await a("@cart_ajax/clearCart"), await x(), await simpleAlert(
          __("shopgo.cart.message.items.removed"),
          __("shopgo.cart.message.will.back.to.home"),
          "success"
        ), location.href = route("home");
      } catch (m) {
        console.error(m), _(m) && simpleAlert(m.message, "", "warning");
      } finally {
        g.pop();
      }
    }
    async function te(a, _) {
      a.quantity += _, a.quantity = Math.max(a.quantity, 1), await G(a);
    }
    const G = debounce(async (a) => {
      a.quantity = Math.max(a.quantity, 1);
      const _ = {};
      for (const V of s.value)
        _[V.key] = V.quantity;
      g.push(!0);
      const { post: m, isAxiosError: v } = await useHttpClient();
      try {
        const V = await Q.push(() => m("@cart_ajax/updateQuantities", { values: _ }));
        return await x();
      } catch (V) {
        console.error(V), v(V) && simpleAlert(V.message, "", "warning");
      } finally {
        C();
      }
    }, 600);
    async function oe() {
      if (D.value === "")
        return;
      g.push(!0);
      const { post: a, isAxiosError: _ } = await useHttpClient();
      try {
        const m = await a("@cart_ajax/addCode", { code: D.value });
        D.value = "", await x();
      } catch (m) {
        console.error(m), _(m) && simpleAlert(m.message, "", "warning");
      } finally {
        C();
      }
    }
    async function le(a) {
      g.push(!0);
      const { delete: _, isAxiosError: m } = await useHttpClient();
      try {
        const v = await _("@cart_ajax/removeCode", { id: a });
        await x();
      } catch (v) {
        console.error(v), m(v) && simpleAlert(v.message, "", "warning");
      } finally {
        C();
      }
    }
    const ne = computed(() => {
      const a = [];
      for (const _ in p.value) {
        if (_ === "total" || _ === "grand_total")
          continue;
        const m = p.value[_];
        Number(m.price) !== 0 && a.push(m);
      }
      return a;
    });
    watch(() => k.value.locationId, () => {
      z();
    }), watch(() => E.value, () => {
      x(!1);
    });
    const K = computed(() => b.value.find((a) => String(a.id) === String(E.value))), z = debounce(async function() {
      g.push(!0);
      const { get: a, isAxiosError: _ } = await useHttpClient();
      try {
        const m = await a(`@cart_ajax/shippings?location_id=${k.value.locationId}`);
        b.value = m.data.data, await nextTick(), await nextTick(), b.value.length > 0 ? K.value || (E.value = b.value[0].id) : E.value = null;
      } catch (m) {
        console.error(m), _(m) && simpleAlert(m.message, "", "warning");
      } finally {
        C();
      }
    }, 300);
    watch(() => [k.value.locationId, E.value], () => {
      X();
    });
    const ae = computed(() => I.value.find((a) => a.id === T.value)), X = debounce(async function() {
      g.push(!0);
      const { get: a, isAxiosError: _ } = await useHttpClient();
      try {
        const m = await a(
          "@cart_ajax/payments",
          {
            params: {
              location_id: k.value.locationId,
              shipping_id: E.value
            }
          }
        );
        I.value = m.data.data, await nextTick(), await nextTick(), I.value.length > 0 ? I.value.find((v) => v.id === T.value) || (T.value = I.value[0].id) : T.value = null;
      } catch (m) {
        console.error(m), _(m) && simpleAlert(m.message, "", "warning");
      } finally {
        C();
      }
    }, 300), se = computed(() => !(B.value === 0 || !k.value.locationId || !F.value.locationId || !E.value || !T.value)), W = ref(), J = ref();
    function ie() {
      if (B.value === 0) {
        console.warn("No checked items");
        return;
      }
      if (Number(p.value.grand_total.price) < 0) {
        swal("Cannot process cart with negative prices.", "", "warning");
        return;
      }
      for (const a of s.value) {
        if (Number(a.priceSet.final_total.price) < 0) {
          swal("Cannot process product items with negative prices.", "", "warning");
          return;
        }
        if (Number(a.priceSet.attached_final_total.price) < 0) {
          swal("Cannot process product items with negative prices.", "", "warning");
          return;
        }
      }
      if (W.value && !W.value.validate()) {
        console.log("Shipping Validate Fail");
        return;
      }
      if (J.value && !J.value.validate()) {
        console.log("Payment Validate Fail");
        return;
      }
      if (!A.checkValidity()) {
        A.reportValidity();
        const a = A.querySelector(":invalid");
        a && !Y(a) && a.dataset.validationMessage && simpleAlert(a.dataset.validationMessage);
        return;
      }
      j.value = !0, A.requestSubmit();
    }
    function Y(a) {
      return !!(a.offsetWidth || a.offsetHeight || a.getClientRects().length);
    }
    const Z = { props: o, loaded: d, items: s, totals: p, coupons: f, paymentId: T, paymentData: F, shippingId: E, shippingData: k, shippings: b, payments: I, receiptData: L, code: D, note: H, loading: j, partialCheckout: R, queue: Q, get abort() {
      return S;
    }, set abort(a) {
      S = a;
    }, form: A, toggleAllInput: w, loadingStack: g, popLoading: C, afterItemsChanged: P, loadItems: x, setCartData: U, itemChecks: M, checks: B, unchecks: r, updateToggleAll: i, toggleChecked: c, updateChecks: N, calcNavAndStickySidebar: $, init: q, removeItem: O, clearCart: ee, changeItemQuantity: te, updateQuantities: G, addCode: oe, removeCode: le, filteredTotals: ne, selectedShipping: K, loadShippings: z, selectedPayment: ae, loadPayments: X, canCheckout: se, shippingForm: W, paymentForm: J, checkout: ie, isVisible: Y, get vTooltip() {
      return vTooltip;
    }, AddressForm: n, CartListItem: t, PaymentItem: u, ShippingItem: y };
    return Object.defineProperty(Z, "__isScriptSetup", { enumerable: !1, value: !0 }), Z;
  }
}), _hoisted_1 = { class: "row" }, _hoisted_2 = { class: "col-lg-8 l-cart-page__content" }, _hoisted_3 = { class: "d-flex align-items-center justify-content-between mb-4" }, _hoisted_4 = { class: "d-flex align-items-center gap-2" }, _hoisted_5 = { class: "m-0" }, _hoisted_6 = {
  key: 0,
  class: "form-check"
}, _hoisted_7 = {
  for: "input-toggle-all",
  class: "form-check-label"
}, _hoisted_8 = {
  key: 1,
  class: "spinner spinner-border-sm spinner-border",
  "data-cloak": ""
}, _hoisted_9 = { class: "l-cart-data" }, _hoisted_10 = { class: "l-cart-items" }, _hoisted_11 = { class: "" }, _hoisted_12 = { class: "l-shippings mb-4" }, _hoisted_13 = { key: 0 }, _hoisted_14 = {
  key: 1,
  class: "card bg-light"
}, _hoisted_15 = { class: "card-body py-5 text-center" }, _hoisted_16 = {
  key: 0,
  class: "spinner spinner-border"
}, _hoisted_17 = { class: "l-payments mb-4" }, _hoisted_18 = { key: 0 }, _hoisted_19 = {
  key: 1,
  class: "card bg-light"
}, _hoisted_20 = { class: "card-body py-5 text-center" }, _hoisted_21 = {
  key: 0,
  class: "spinner spinner-border"
}, _hoisted_22 = { class: "l-checkout-note card mb-4" }, _hoisted_23 = { class: "card-body" }, _hoisted_24 = { class: "card-title mb-3" }, _hoisted_25 = ["placeholder"], _hoisted_26 = { class: "col-lg-4 l-cart-page__sidebar" }, _hoisted_27 = {
  class: "l-cart-sidebar position-sticky",
  style: { top: "var(--sidebar-offsets-top, 90px)" }
}, _hoisted_28 = { class: "card" }, _hoisted_29 = { class: "card-body l-cart-coupons border-bottom" }, _hoisted_30 = { class: "d-flex gap-2" }, _hoisted_31 = ["disabled"], _hoisted_32 = {
  key: 0,
  "data-cloak": "",
  class: "list-group list-group-flush mt-4"
}, _hoisted_33 = { class: "list-group-item border-top d-flex" }, _hoisted_34 = { class: "small text-muted" }, _hoisted_35 = { class: "ms-auto" }, _hoisted_36 = ["onClick"], _hoisted_37 = {
  key: 0,
  class: "card-body"
}, _hoisted_38 = {
  key: 1,
  "data-cloak": "",
  class: "card-body l-cart-totals text-end"
}, _hoisted_39 = { class: "l-cart-total d-flex justify-content-between gap-1 mb-1 w-100" }, _hoisted_40 = { class: "l-cart-total__label" }, _hoisted_41 = {
  key: 0,
  class: "l-cart-total__value"
}, _hoisted_42 = { class: "l-cart-total d-flex justify-content-between gap-1 mb-1 w-100" }, _hoisted_43 = { class: "l-cart-total__label d-flex gap-2" }, _hoisted_44 = { key: 0 }, _hoisted_45 = { class: "l-cart-total__value" }, _hoisted_46 = {
  class: "card mt-3 position-sticky",
  style: { bottom: "0" }
}, _hoisted_47 = { class: "card-body d-grid gap-3" }, _hoisted_48 = {
  key: 0,
  class: "l-cart-total d-flex justify-content-between gap-1 w-100 fs-5 fw-bold",
  "data-cloak": ""
}, _hoisted_49 = { class: "l-cart-total__label" }, _hoisted_50 = {
  key: 0,
  class: "l-cart-total__value text-end"
}, _hoisted_51 = {
  key: 0,
  class: "mt-1 small text-muted fw-normal"
}, _hoisted_52 = {
  key: 1,
  class: "d-flex justify-content-between",
  "data-cloak": ""
}, _hoisted_53 = { key: 2 }, _hoisted_54 = { key: 3 }, _hoisted_55 = ["disabled"], _hoisted_56 = { "data-cloak": "" }, _hoisted_57 = {
  key: 0,
  class: "spinner spinner-grow spinner-grow-sm"
}, _hoisted_58 = {
  key: 0,
  "data-loading": ""
};
function _sfc_render(l, e, n, t, u, y) {
  return openBlock(), createElementBlock("div", _hoisted_1, [
    createElementVNode("div", _hoisted_2, [
      createElementVNode("header", _hoisted_3, [
        createElementVNode("div", _hoisted_4, [
          createElementVNode("h3", _hoisted_5, toDisplayString(l.$lang("shopgo.cart.title")), 1),
          e[5] || (e[5] = createTextVNode()),
          t.partialCheckout ? (openBlock(), createElementBlock("div", _hoisted_6, [
            createElementVNode("input", {
              id: "input-toggle-all",
              type: "checkbox",
              class: "form-check-input",
              ref: "toggleAllInput",
              onClick: t.toggleChecked
            }, null, 512),
            e[4] || (e[4] = createTextVNode()),
            createElementVNode("label", _hoisted_7, toDisplayString(l.$lang("shopgo.cart.toggle.all")), 1)
          ])) : createCommentVNode("", !0),
          e[6] || (e[6] = createTextVNode()),
          t.loading ? (openBlock(), createElementBlock("div", _hoisted_8)) : createCommentVNode("", !0)
        ]),
        e[8] || (e[8] = createTextVNode()),
        createElementVNode("div", null, [
          createElementVNode("a", {
            href: "javascript://",
            onClick: t.clearCart
          }, [
            e[7] || (e[7] = createElementVNode("i", { class: "fa fa-times" }, null, -1)),
            createTextVNode(" " + toDisplayString(l.$lang("shopgo.cart.button.remove.all")), 1)
          ])
        ])
      ]),
      e[17] || (e[17] = createTextVNode()),
      e[18] || (e[18] = createElementVNode("div", { "data-loading": "" }, [
        createElementVNode("div", { class: "d-flex py-5" }, [
          createElementVNode("span", { class: "spinner spinner-grow spinner-lg mx-auto" })
        ])
      ], -1)),
      e[19] || (e[19] = createTextVNode()),
      createElementVNode("div", _hoisted_9, [
        createElementVNode("div", _hoisted_10, [
          (openBlock(!0), createElementBlock(Fragment, null, renderList(t.items, (o, d) => (openBlock(), createBlock(t.CartListItem, {
            key: o.key,
            item: o,
            "has-checkbox": t.partialCheckout,
            onRemoveItem: (s) => t.removeItem(o, d),
            onUpdateQuantity: (s) => t.updateQuantities(o),
            onChangeItemQuantity: (s) => t.changeItemQuantity(o, s),
            onUpdateChecks: t.updateChecks
          }, null, 8, ["item", "has-checkbox", "onRemoveItem", "onUpdateQuantity", "onChangeItemQuantity", "onUpdateChecks"]))), 128))
        ]),
        e[13] || (e[13] = createTextVNode()),
        createElementVNode("div", _hoisted_11, [
          createVNode(t.AddressForm, {
            type: "payment",
            title: l.$lang("shopgo.cart.payment.data.title"),
            user: n.user,
            modelValue: t.paymentData,
            "onUpdate:modelValue": e[0] || (e[0] = (o) => t.paymentData = o),
            ref: "paymentForm"
          }, null, 8, ["title", "user", "modelValue"]),
          e[9] || (e[9] = createTextVNode()),
          createVNode(t.AddressForm, {
            type: "shipping",
            title: l.$lang("shopgo.cart.shipping.data.title"),
            user: n.user,
            modelValue: t.shippingData,
            "onUpdate:modelValue": e[1] || (e[1] = (o) => t.shippingData = o),
            "sync-data": t.paymentData,
            ref: "shippingForm"
          }, null, 8, ["title", "user", "modelValue", "sync-data"])
        ]),
        e[14] || (e[14] = createTextVNode()),
        createElementVNode("div", _hoisted_12, [
          createElementVNode("h3", null, toDisplayString(l.$lang("shopgo.cart.shipping.title")), 1),
          e[10] || (e[10] = createTextVNode()),
          t.shippings.length > 0 ? (openBlock(), createElementBlock("div", _hoisted_13, [
            (openBlock(!0), createElementBlock(Fragment, null, renderList(t.shippings, (o, d) => (openBlock(), createBlock(t.ShippingItem, {
              key: o.id,
              style: { "animation-duration": ".1s" },
              shipping: o,
              i: d,
              selected: String(t.shippingId) === String(o.id),
              onSelected: (s) => t.shippingId = o.id
            }, null, 8, ["shipping", "i", "selected", "onSelected"]))), 128))
          ])) : (openBlock(), createElementBlock("div", _hoisted_14, [
            createElementVNode("div", _hoisted_15, [
              t.loading ? (openBlock(), createElementBlock("span", _hoisted_16)) : t.shippingData.locationId ? (openBlock(), createElementBlock(Fragment, { key: 1 }, [
                createTextVNode(toDisplayString(l.$lang("shopgo.cart.text.no.shippings")), 1)
              ], 64)) : (openBlock(), createElementBlock(Fragment, { key: 2 }, [
                createTextVNode(toDisplayString(l.$lang("shopgo.cart.text.select.location.first")), 1)
              ], 64))
            ])
          ]))
        ]),
        e[15] || (e[15] = createTextVNode()),
        createElementVNode("div", _hoisted_17, [
          createElementVNode("h3", null, toDisplayString(l.$lang("shopgo.cart.payment.title")), 1),
          e[11] || (e[11] = createTextVNode()),
          t.payments.length > 0 ? (openBlock(), createElementBlock("div", _hoisted_18, [
            (openBlock(!0), createElementBlock(Fragment, null, renderList(t.payments, (o, d) => (openBlock(), createBlock(t.PaymentItem, {
              key: o.id,
              style: { "animation-duration": ".1s" },
              payment: o,
              i: d,
              selected: String(t.paymentId) === String(o.id),
              onSelected: (s) => t.paymentId = o.id
            }, null, 8, ["payment", "i", "selected", "onSelected"]))), 128))
          ])) : (openBlock(), createElementBlock("div", _hoisted_19, [
            createElementVNode("div", _hoisted_20, [
              t.loading ? (openBlock(), createElementBlock("span", _hoisted_21)) : t.shippingId ? (openBlock(), createElementBlock(Fragment, { key: 1 }, [
                createTextVNode(toDisplayString(l.$lang("shopgo.cart.text.no.payments")), 1)
              ], 64)) : (openBlock(), createElementBlock(Fragment, { key: 2 }, [
                createTextVNode(toDisplayString(l.$lang("shopgo.cart.text.select.shipping.first")), 1)
              ], 64))
            ])
          ]))
        ]),
        e[16] || (e[16] = createTextVNode()),
        createElementVNode("div", _hoisted_22, [
          createElementVNode("div", _hoisted_23, [
            createElementVNode("h5", _hoisted_24, toDisplayString(l.$lang("shopgo.cart.field.note")), 1),
            e[12] || (e[12] = createTextVNode()),
            withDirectives(createElementVNode("textarea", {
              rows: "4",
              class: "form-control",
              "onUpdate:modelValue": e[2] || (e[2] = (o) => t.note = o),
              name: "checkout[note]",
              placeholder: l.$lang("shopgo.cart.field.note.placeholder")
            }, null, 8, _hoisted_25), [
              [vModelText, t.note]
            ])
          ])
        ])
      ])
    ]),
    e[47] || (e[47] = createTextVNode()),
    createElementVNode("div", _hoisted_26, [
      createElementVNode("div", _hoisted_27, [
        createElementVNode("div", _hoisted_28, [
          createElementVNode("div", _hoisted_29, [
            createElementVNode("h5", null, toDisplayString(l.$lang("shopgo.cart.label.discount.code")), 1),
            e[24] || (e[24] = createTextVNode()),
            createElementVNode("div", _hoisted_30, [
              withDirectives(createElementVNode("input", {
                type: "text",
                class: "form-control",
                "onUpdate:modelValue": e[3] || (e[3] = (o) => t.code = o)
              }, null, 512), [
                [vModelText, t.code]
              ]),
              e[20] || (e[20] = createTextVNode()),
              createElementVNode("button", {
                type: "button",
                class: "btn btn-secondary text-nowrap",
                style: { "min-width": "100px" },
                onClick: t.addCode,
                disabled: t.code === "" || t.loading
              }, toDisplayString(l.$lang("shopgo.cart.button.use.discount.code")), 9, _hoisted_31)
            ]),
            e[25] || (e[25] = createTextVNode()),
            t.coupons.length ? (openBlock(), createElementBlock("div", _hoisted_32, [
              (openBlock(!0), createElementBlock(Fragment, null, renderList(t.coupons, (o) => (openBlock(), createElementBlock("div", _hoisted_33, [
                createElementVNode("div", null, [
                  createElementVNode("div", null, [
                    createElementVNode("strong", null, toDisplayString(o.title), 1)
                  ]),
                  e[21] || (e[21] = createTextVNode()),
                  createElementVNode("div", _hoisted_34, toDisplayString(o.code), 1)
                ]),
                e[23] || (e[23] = createTextVNode()),
                createElementVNode("div", _hoisted_35, [
                  withDirectives((openBlock(), createElementBlock("a", {
                    href: "javascript://",
                    class: "link-secondary",
                    title: "{{ $lang('shopgo.cart.button.remove.discount.code') }}",
                    onClick: (d) => t.removeCode(o.id)
                  }, [...e[22] || (e[22] = [
                    createElementVNode("i", { class: "fa fa-trash" }, null, -1)
                  ])], 8, _hoisted_36)), [
                    [t.vTooltip]
                  ])
                ])
              ]))), 256))
            ])) : createCommentVNode("", !0)
          ]),
          e[31] || (e[31] = createTextVNode()),
          t.loaded ? createCommentVNode("", !0) : (openBlock(), createElementBlock("div", _hoisted_37, [...e[26] || (e[26] = [
            createElementVNode("div", { class: "card-text placeholder-glow d-flex my-2" }, [
              createElementVNode("span", { class: "placeholder col-4" }),
              createTextVNode(),
              createElementVNode("span", { class: "placeholder col-3 ms-auto" })
            ], -1)
          ])])),
          e[32] || (e[32] = createTextVNode()),
          t.loaded ? (openBlock(), createElementBlock("div", _hoisted_38, [
            createElementVNode("div", _hoisted_39, [
              createElementVNode("div", _hoisted_40, toDisplayString(l.$lang("shopgo.cart.label.total")), 1),
              e[27] || (e[27] = createTextVNode()),
              t.totals.total ? (openBlock(), createElementBlock("div", _hoisted_41, toDisplayString(l.$formatPrice(t.totals.total.price, { code: !0 })), 1)) : createCommentVNode("", !0)
            ]),
            e[30] || (e[30] = createTextVNode()),
            (openBlock(!0), createElementBlock(Fragment, null, renderList(t.filteredTotals, (o) => (openBlock(), createElementBlock("div", _hoisted_42, [
              createElementVNode("div", _hoisted_43, [
                createElementVNode("div", null, toDisplayString(o.label), 1),
                e[28] || (e[28] = createTextVNode()),
                o.params.type === "coupon" || o.params.subtype === "code" ? (openBlock(), createElementBlock("div", _hoisted_44, [
                  createElementVNode("small", null, "(" + toDisplayString(o.params.code) + ")", 1)
                ])) : createCommentVNode("", !0)
              ]),
              e[29] || (e[29] = createTextVNode()),
              createElementVNode("div", _hoisted_45, toDisplayString(l.$formatPrice(o.price, { code: !0 })), 1)
            ]))), 256))
          ])) : createCommentVNode("", !0)
        ]),
        e[46] || (e[46] = createTextVNode()),
        createElementVNode("div", _hoisted_46, [
          createElementVNode("div", _hoisted_47, [
            t.loaded ? (openBlock(), createElementBlock("div", _hoisted_48, [
              createElementVNode("div", _hoisted_49, toDisplayString(l.$lang("shopgo.cart.label.grand.total")), 1),
              e[34] || (e[34] = createTextVNode()),
              t.totals.grand_total ? (openBlock(), createElementBlock("div", _hoisted_50, [
                createElementVNode("div", null, toDisplayString(l.$formatPrice(t.totals.grand_total.price, { code: !0 })), 1),
                e[33] || (e[33] = createTextVNode()),
                l.$currency.isSubCurrency() ? (openBlock(), createElementBlock("div", _hoisted_51, `\r
                  (` + toDisplayString(l.$currency.formatMainCurrency(t.totals.grand_total.price, { code: !0 })) + `)\r
                `, 1)) : createCommentVNode("", !0)
              ])) : createCommentVNode("", !0)
            ])) : createCommentVNode("", !0),
            e[42] || (e[42] = createTextVNode()),
            t.loaded ? (openBlock(), createElementBlock("div", _hoisted_52, [
              createElementVNode("div", null, [
                e[35] || (e[35] = createElementVNode("i", { class: "fa fa-truck" }, null, -1)),
                createTextVNode(" " + toDisplayString(t.selectedShipping?.title || l.$lang("shopgo.message.no.shipping.selected")), 1)
              ]),
              e[37] || (e[37] = createTextVNode()),
              createElementVNode("div", null, [
                e[36] || (e[36] = createElementVNode("i", { class: "fa fa-credit-card" }, null, -1)),
                createTextVNode(" " + toDisplayString(t.selectedPayment?.title || l.$lang("shopgo.message.no.payment.selected")), 1)
              ])
            ])) : createCommentVNode("", !0),
            e[43] || (e[43] = createTextVNode()),
            t.loaded ? createCommentVNode("", !0) : (openBlock(), createElementBlock("div", _hoisted_53, [...e[38] || (e[38] = [
              createElementVNode("div", {
                class: "card-text placeholder-glow d-flex mb-1",
                style: { height: "1.25rem" }
              }, [
                createElementVNode("span", { class: "placeholder col-3" }),
                createTextVNode(),
                createElementVNode("span", { class: "placeholder col-4 ms-auto" })
              ], -1)
            ])])),
            e[44] || (e[44] = createTextVNode()),
            t.loaded ? createCommentVNode("", !0) : (openBlock(), createElementBlock("div", _hoisted_54, [...e[39] || (e[39] = [
              createElementVNode("div", { class: "card-text placeholder-glow d-flex" }, [
                createElementVNode("span", { class: "placeholder col-3" }),
                createTextVNode(),
                createElementVNode("span", { class: "placeholder col-3 ms-auto" })
              ], -1)
            ])])),
            e[45] || (e[45] = createTextVNode()),
            createElementVNode("button", {
              type: "button",
              class: "btn btn-primary btn-lg",
              disabled: t.loading || !t.canCheckout,
              onClick: t.checkout
            }, [
              createElementVNode("div", _hoisted_56, [
                t.loading ? (openBlock(), createElementBlock("span", _hoisted_57)) : (openBlock(), createElementBlock(Fragment, { key: 1 }, [
                  createTextVNode(toDisplayString(l.$lang("shopgo.cart.button.process.checkout")), 1)
                ], 64))
              ]),
              e[41] || (e[41] = createTextVNode()),
              t.loading ? createCommentVNode("", !0) : (openBlock(), createElementBlock("div", _hoisted_58, [...e[40] || (e[40] = [
                createElementVNode("span", { class: "spinner spinner-grow spinner-grow-sm" }, null, -1)
              ])]))
            ], 8, _hoisted_55)
          ])
        ])
      ])
    ])
  ]);
}
const CartApp__Tmp48066 = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render], ["__file", "CartApp.vue"]]), CartApp = resolveVueComponent("CartApp", CartApp__Tmp48066);
function initApp(l) {
  useCssImport("@vue-animate");
  const e = createApp(CartApp, l);
  return e.use(ShopGoPlugin), e;
}
export {
  initApp
};
//# sourceMappingURL=cart.js.map
