import { useHttpClient, __, data, route, useTomSelect, uid, slideDown, slideUp, useStack, debounce, simpleAlert, useCssImport } from "@windwalker-io/unicorn-next";
import { defineComponent, mergeModels, useModel, reactive, ref, watch, onMounted, nextTick, createElementBlock, openBlock, createTextVNode, createElementVNode, Fragment, renderList, normalizeClass, toDisplayString, useTemplateRef, computed, createVNode, createCommentVNode, withDirectives, vModelCheckbox, Transition, withCtx, vModelText, createBlock, createApp } from "vue";
import { vTooltip, ShopGoPlugin } from "../index.js";
import { Modal } from "bootstrap";
import { h } from "./index.es.js";
import { _ as _export_sfc } from "./_plugin-vue_export-helper.js";
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
  setup(n, { expose: e, emit: l }) {
    const t = n, m = useModel(n, "modelValue"), V = l, o = globalThis.u || window.u, r = {
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
      onSelectInit: (d) => {
      },
      onChange: (d) => {
      },
      onValueInit: (d) => {
      }
    }, s = reactive(Object.assign({}, r, t.options || {})), p = ref([]), f = ref([]), E = ref(!0), b = ref(!1), D = ref(s.ajaxUrl || ""), x = ref(), w = ref([]);
    function P() {
      E.value = !s.readonly && !s.disabled, D.value = s.ajaxUrl || "";
    }
    async function F() {
      if (b.value)
        return;
      b.value = !0, p.value = [];
      let u = [...(m.value || []).slice().map(($) => String($))];
      f.value = [...u], u.length === 0 ? u = [null] : u.unshift(null);
      let y = null;
      for (let $ in u) {
        const H = u[$], U = await B(H);
        U && U.length > 0 && p.value.push(U), y = H;
      }
      j(x.value, y, u), b.value = !1, await nextTick(), w.value && w.value.length > 0 && M(w.value[0]);
    }
    function C() {
      F();
    }
    function k(d) {
      return s.labels[d] || `Level ${d + 1}`;
    }
    function N(d) {
      return `${s.id}__level-${d}`;
    }
    function S(d) {
      return f.value[d] || "";
    }
    function R(d, u) {
      return String(S(d)) === String(u[s.valueField]);
    }
    function T() {
      const d = f.value.slice();
      if (d.length === 0)
        return s.defaultValue;
      const u = d.filter((y) => y != null).filter((y) => y !== "").pop();
      return u === void 0 ? s.defaultValue : u;
    }
    function z() {
      return f.value.length;
    }
    async function A(d, u) {
      const y = u.target;
      f.value[d] = y.value;
      try {
        s.onChange(u);
      } catch {
      }
      u.stopPropagation();
      const $ = new CustomEvent("change", {
        detail: {
          el: y,
          component: i,
          value: y.value,
          path: f.value
        }
      });
      if (x.value?.dispatchEvent($), m.value = f.value, V("change", $), y.value === "") {
        p.value.splice(d + 1), f.value.splice(d + 1);
        return;
      }
      const H = await B(y.value);
      if (p.value.splice(d + 1), f.value.splice(d + 1), H && H.length > 0) {
        p.value.push(H), await nextTick();
        const U = w.value.length - 1;
        w.value && w.value[U] && M(w.value[U]);
      }
    }
    async function B(d, u) {
      const { get: y } = await useHttpClient();
      return (await y(
        D.value,
        {
          params: {
            [s.ajaxValueField]: d,
            self: s.ignoreSelf || null
          }
        }
      )).data.data;
    }
    function j(d, u, y) {
      const $ = new CustomEvent("value.init", {
        detail: {
          el: d,
          component: i,
          value: u,
          path: y
        }
      });
      x.value?.dispatchEvent($);
    }
    function M(d) {
      const u = new CustomEvent("select.init", {
        detail: {
          el: d,
          component: i
        }
      });
      s.onSelectInit(u), x.value?.dispatchEvent(u);
    }
    function q(d) {
      return d.map((u) => ({
        [s.valueField]: u.value[s.valueField],
        [s.textField]: u.value[s.textField],
        children: u.children
      })).filter((u) => s.ignoreSelf ? u[s.valueField] != s.ignoreSelf : u);
    }
    function L(d, u) {
      return (d || []).filter(($) => $[s.valueField] == u).shift();
    }
    function O(d) {
      return s.placeholders && s.placeholders[d] ? s.placeholders[d] : s.placeholder;
    }
    const i = {
      opt: s,
      lists: p,
      values: f,
      getFinalValue: T,
      getLevel: z,
      getLabel: k,
      getId: N,
      getListValue: S,
      isSelected: R,
      onChange: A,
      loadItems: B,
      valueInit: j,
      selectInit: M,
      handleSourceItems: q,
      findFromList: L,
      getPlaceholder: O
    };
    watch(m, (d) => {
      (!d || d.length === 0) && C();
    }, { deep: !0 }), onMounted(async () => {
      P(), await F();
    }), e({
      prepareValues: F
    });
    const v = { props: t, modelValue: m, emit: V, u: o, defaultOpt: r, opt: s, lists: p, values: f, canModify: E, loading: b, ajaxUrl: D, root: x, selects: w, init: P, prepareValues: F, reset: C, getLabel: k, getId: N, getListValue: S, isSelected: R, getFinalValue: T, getLevel: z, onChange: A, loadItems: B, valueInit: j, selectInit: M, handleSourceItems: q, findFromList: L, getPlaceholder: O, componentAPI: i };
    return Object.defineProperty(v, "__isScriptSetup", { enumerable: !1, value: !0 }), v;
  }
}), _hoisted_1$5 = { ref: "root" }, _hoisted_2$5 = ["data-level"], _hoisted_3$5 = ["for"], _hoisted_4$5 = { class: "col c-cascade-select__input" }, _hoisted_5$5 = ["id", "disabled", "onChange"], _hoisted_6$5 = { value: "" }, _hoisted_7$5 = ["value", "selected"], _hoisted_8$5 = ["name", "value"];
function _sfc_render$5(n, e, l, t, m, V) {
  return openBlock(), createElementBlock("div", _hoisted_1$5, [
    (openBlock(!0), createElementBlock(Fragment, null, renderList(t.lists, (o, r) => (openBlock(), createElementBlock("div", {
      class: normalizeClass(["form-group row mb-2", [t.opt.horizontal ? t.opt.horizontalColWidth || "col" : ""]]),
      key: o,
      "data-level": r
    }, [
      createElementVNode("label", {
        for: t.getId(r),
        class: normalizeClass(["c-cascade-select__label mb-2", t.opt.labelWidth || "col-md-3"])
      }, toDisplayString(t.getLabel(r)), 11, _hoisted_3$5),
      e[1] || (e[1] = createTextVNode()),
      createElementVNode("div", _hoisted_4$5, [
        createElementVNode("select", {
          id: t.getId(r),
          disabled: !t.canModify,
          class: "form-select custom-select",
          ref_for: !0,
          ref: (s) => t.selects[r] = s,
          onChange: (s) => t.onChange(r, s)
        }, [
          createElementVNode("option", _hoisted_6$5, toDisplayString(t.getPlaceholder(r)), 1),
          e[0] || (e[0] = createTextVNode()),
          (openBlock(!0), createElementBlock(Fragment, null, renderList(o, (s) => (openBlock(), createElementBlock("option", {
            value: s[t.opt.valueField],
            key: s[t.opt.valueField],
            selected: t.isSelected(r, s)
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
const CascadeSelect = /* @__PURE__ */ _export_sfc(_sfc_main$5, [["render", _sfc_render$5], ["__file", "CascadeSelect.vue"]]), _sfc_main$4 = /* @__PURE__ */ defineComponent({
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
  setup(n, { expose: e, emit: l }) {
    const t = n, m = {
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
    }, V = l, o = useModel(n, "modelValue"), r = ref(!1), s = ref(t.syncData == null ? "initializing" : "sync"), p = ref([]), f = {
      ajaxUrl: route("@address_ajax/locationOptions"),
      labels: data("location.labels") || [],
      placeholder: __("unicorn.select.placeholder"),
      onSelectInit(i) {
        const v = i.detail.el;
        useTomSelect(v);
      }
    };
    o.value = Object.assign(
      {},
      m,
      {
        firstName: t.user?.firstname || "",
        lastName: t.user?.lastname || "",
        name: t.user?.name || ""
      },
      o.value
    );
    const E = ref([]), b = ref(""), D = ref(t.syncData != null), x = ref(!1), w = ref(), P = ref(), F = useTemplateRef("modal");
    (!o.value || Object.keys(o.value).length === 0) && A().then((i) => {
      const v = i[0] || null;
      v && (o.value = M(v));
    }), onMounted(async () => {
      if (D.value)
        s.value = "form";
      else {
        const i = await A();
        let v;
        o.value.id && (v = i.find((d) => String(d.id) === String(o.value.id))), v || (v = i[0]), v && q(v), s.value = "selected";
      }
      L();
    });
    function C() {
      if (D.value)
        return !0;
      if (w.value) {
        let i = !0;
        const v = w.value.querySelectorAll("input,textarea,select");
        for (const d of v)
          if (!d.checkValidity()) {
            i = i && !1, d.reportValidity();
            break;
          }
        return V("validated", i), i;
      }
      return !0;
    }
    watch(() => t.syncData, async () => {
      D.value && t.syncData && k();
    }, { deep: !0, immediate: !0 }), watch(D, (i) => {
      i ? t.syncData || t.syncData && (s.value = "sync", k()) : (s.value = "form", o.value.id = void 0, o.value.addressId = void 0);
    });
    function k() {
      o.value = JSON.parse(JSON.stringify(t.syncData || {}));
    }
    const N = computed(() => b.value !== h.hashStr(JSON.stringify(o.value)));
    function S(i) {
      i.detail && (o.value.locationId = i.detail.value, p.value = i.detail.path);
    }
    function R(i) {
      return `input-${t.type}-${i}`;
    }
    function T(i) {
      return `checkout[${t.type}_data][${i}]`;
    }
    function z() {
      s.value = "new", p.value = [], o.value = Object.assign({}, m);
    }
    async function A() {
      const { get: i } = await useHttpClient();
      return (await i("@address_ajax/myAddresses")).data.data;
    }
    async function B() {
      r.value = !0, Modal.getOrCreateInstance(F.value).show();
      try {
        E.value = await A();
      } finally {
        r.value = !1;
      }
    }
    async function j(i) {
      x.value = !0, o.value = Object.assign(
        {},
        m,
        i
      ), await q(i), b.value = h.hashStr(JSON.stringify(o.value)), await L(), x.value = !1;
    }
    function M(i) {
      return console.log(i), i.locationPath = i.locationPath.map((v) => String(v)), i.addressId = String(i.id), i;
    }
    async function q(i) {
      const v = Object.assign(
        {},
        m,
        i
      );
      o.value = M(v), Modal.getOrCreateInstance(F.value).hide(), await L();
    }
    async function L() {
      p.value = o.value.locationPath || [], await nextTick(), await P.value?.prepareValues();
    }
    e({
      validate: C
    });
    const O = { props: t, defaultAddress: m, emit: V, modelValue: o, addressLoading: r, currentState: s, locationPath: p, cascadeOptions: f, addresses: E, currentAddressHash: b, sync: D, addressSelecting: x, form: w, locationSelector: P, modalElement: F, validate: C, syncAddressFromOutside: k, showSaveButton: N, locationChanged: S, buildInputId: R, buildInputName: T, createNew: z, findMyAddress: A, openAddressSelector: B, selectAddress: j, prepareAddressData: M, setAddressToData: q, updateLocationList: L, CascadeSelect };
    return Object.defineProperty(O, "__isScriptSetup", { enumerable: !1, value: !0 }), O;
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
function _sfc_render$4(n, e, l, t, m, V) {
  return openBlock(), createElementBlock("div", _hoisted_1$4, [
    createElementVNode("div", _hoisted_2$4, [
      createElementVNode("div", _hoisted_3$4, [
        createElementVNode("div", _hoisted_4$4, [
          createElementVNode("h4", _hoisted_5$4, toDisplayString(l.title), 1),
          e[15] || (e[15] = createTextVNode()),
          l.syncData ? (openBlock(), createElementBlock("div", _hoisted_6$4, [
            createElementVNode("label", {
              for: `input-${l.type}-sync`,
              class: "form-check-label"
            }, toDisplayString(l.syncLabel || n.$lang("shopgo.cart.address.form.same.with.buyer")), 9, _hoisted_7$4),
            e[14] || (e[14] = createTextVNode()),
            withDirectives(createElementVNode("input", {
              type: "checkbox",
              "onUpdate:modelValue": e[0] || (e[0] = (o) => t.sync = o),
              id: `input-${l.type}-sync`,
              name: t.buildInputName("sync"),
              class: "form-check-input",
              value: "1"
            }, null, 8, _hoisted_8$4), [
              [vModelCheckbox, t.sync]
            ])
          ])) : createCommentVNode("", !0)
        ]),
        e[17] || (e[17] = createTextVNode()),
        l.user && !t.sync ? (openBlock(), createElementBlock("div", _hoisted_9$4, [
          createElementVNode("button", {
            type: "button",
            class: "btn btn-outline-success btn-sm",
            style: { "min-width": "100px" },
            onClick: t.createNew
          }, toDisplayString(n.$lang("shopgo.cart.address.form.new.address")), 1),
          e[16] || (e[16] = createTextVNode()),
          createElementVNode("button", {
            type: "button",
            class: "btn btn-outline-primary btn-sm",
            style: { "min-width": "100px" },
            onClick: t.openAddressSelector
          }, toDisplayString(n.$lang("shopgo.cart.address.form.select")), 1)
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
                }, toDisplayString(n.$lang("shopgo.address.field.firstname")), 9, _hoisted_15$3),
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
                }, toDisplayString(n.$lang("shopgo.address.field.lastname")), 9, _hoisted_19$2),
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
                }, toDisplayString(n.$lang("shopgo.address.field.email")), 9, _hoisted_23$2),
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
                }, toDisplayString(n.$lang("shopgo.address.field.phone")), 9, _hoisted_27$2),
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
                }, toDisplayString(n.$lang("shopgo.address.field.mobile")), 9, _hoisted_31$2),
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
                }, toDisplayString(n.$lang("shopgo.address.field.company")), 9, _hoisted_35$2),
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
                }, toDisplayString(n.$lang("shopgo.address.field.vat")), 9, _hoisted_39$1),
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
                }, toDisplayString(n.$lang("shopgo.address.field.country")), 9, _hoisted_44$1),
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
                }, toDisplayString(n.$lang("shopgo.address.field.postcode")), 9, _hoisted_46$1),
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
                }, toDisplayString(n.$lang("shopgo.address.field.address1")), 9, _hoisted_50$1),
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
                }, toDisplayString(n.$lang("shopgo.address.field.address2")), 9, _hoisted_54$1),
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
                }, toDisplayString(n.$lang("shopgo.cart.address.form.save.for.next")), 9, _hoisted_58$1),
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
      id: `${l.type}-address-modal`,
      tabindex: "-1",
      role: "dialog",
      "aria-labelledby": "address-modal-label",
      "aria-hidden": "true"
    }, [
      createElementVNode("div", _hoisted_64, [
        createElementVNode("div", _hoisted_65, [
          createElementVNode("div", _hoisted_66, [
            createElementVNode("h4", _hoisted_67, toDisplayString(n.$lang("shopgo.cart.address.form.modal.title")), 1),
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
                onClick: (r) => t.selectAddress(o)
              }, [
                createElementVNode("div", null, toDisplayString(o.formatted), 1),
                e[46] || (e[46] = createTextVNode()),
                createElementVNode("div", null, [
                  createElementVNode("span", _hoisted_71, toDisplayString(n.$lang("shopgo.cart.address.form.button.select")), 1)
                ])
              ], 8, _hoisted_70))), 128))
            ])) : (openBlock(), createElementBlock("div", _hoisted_72, [
              t.addressLoading ? (openBlock(), createElementBlock("span", _hoisted_73)) : (openBlock(), createElementBlock(Fragment, { key: 1 }, [
                createTextVNode(toDisplayString(n.$lang("shopgo.cart.address.form.no.addresses")), 1)
              ], 64))
            ]))
          ])
        ])
      ])
    ], 8, _hoisted_63)
  ]);
}
const AddressForm = /* @__PURE__ */ _export_sfc(_sfc_main$4, [["render", _sfc_render$4], ["__file", "AddressForm.vue"]]), _sfc_main$3 = /* @__PURE__ */ defineComponent({
  __name: "CartListItem",
  props: {
    item: {},
    hasCheckbox: { type: Boolean }
  },
  emits: ["remove-item", "update-quantities", "change-item-quantity", "update-checks"],
  setup(n, { expose: e, emit: l }) {
    e();
    const t = n, m = l;
    function V() {
      m("remove-item");
    }
    function o() {
      m("update-quantities");
    }
    function r(f) {
      m("change-item-quantity", f);
    }
    function s() {
      m("update-checks");
    }
    const p = { props: t, emits: m, removeItem: V, updateQuantities: o, changeItemQuantity: r, updateChecks: s };
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
function _sfc_render$3(n, e, l, t, m, V) {
  return openBlock(), createElementBlock("div", {
    class: "c-cart-item card mb-3",
    "data-product-id": l.item.product.id,
    "data-variant-id": l.item.variant.id
  }, [
    createElementVNode("div", _hoisted_2$3, [
      createElementVNode("div", _hoisted_3$3, [
        l.hasCheckbox ? (openBlock(), createElementBlock("div", _hoisted_4$3, [
          withDirectives(createElementVNode("input", {
            type: "checkbox",
            class: "form-check-input",
            "onUpdate:modelValue": e[0] || (e[0] = (o) => l.item.options.checked = o),
            onChange: t.updateChecks
          }, null, 544), [
            [vModelCheckbox, l.item.options.checked]
          ])
        ])) : createCommentVNode("", !0),
        e[6] || (e[6] = createTextVNode()),
        createElementVNode("div", _hoisted_5$3, [
          createElementVNode("div", _hoisted_6$3, [
            createElementVNode("img", {
              class: "object-fit-cover",
              src: l.item.cover,
              alt: l.item.product.title,
              style: {}
            }, null, 8, _hoisted_7$3)
          ])
        ]),
        e[7] || (e[7] = createTextVNode()),
        createElementVNode("div", _hoisted_8$3, [
          createElementVNode("h5", null, [
            createElementVNode("a", {
              href: l.item.link,
              target: "_blank"
            }, toDisplayString(l.item.product.title), 9, _hoisted_9$3)
          ]),
          e[4] || (e[4] = createTextVNode()),
          l.item.variant.primary ? createCommentVNode("", !0) : (openBlock(), createElementBlock("div", _hoisted_10$3, toDisplayString(l.item.variant.title), 1)),
          e[5] || (e[5] = createTextVNode()),
          createElementVNode("div", _hoisted_11$3, toDisplayString(l.item.product.model), 1)
        ]),
        e[8] || (e[8] = createTextVNode()),
        l.item.outOfStock ? (openBlock(), createElementBlock("div", _hoisted_12$3, [
          createElementVNode("span", _hoisted_13$3, toDisplayString(n.$lang("shopgo.message.out.of.stock")), 1)
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
              "onUpdate:modelValue": e[2] || (e[2] = (o) => l.item.quantity = o),
              onChange: t.updateQuantities,
              style: { width: "75px" }
            }, null, 544), [
              [
                vModelText,
                l.item.quantity,
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
          l.item.priceSet.base_total.price !== l.item.priceSet.final_total.price ? (openBlock(), createElementBlock("div", _hoisted_19$1, [
            createElementVNode("del", null, toDisplayString(n.$formatPrice(l.item.priceSet.base_total.price)), 1)
          ])) : createCommentVNode("", !0),
          e[13] || (e[13] = createTextVNode()),
          createElementVNode("div", _hoisted_20$1, toDisplayString(n.$formatPrice(l.item.priceSet.final_total.price, { code: !0 })), 1)
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
    l.item.attachments.length > 0 ? (openBlock(), createElementBlock("div", _hoisted_21$1, [
      createElementVNode("h6", null, toDisplayString(n.$lang("shopgo.cart.title.attachments")), 1),
      e[25] || (e[25] = createTextVNode()),
      (openBlock(!0), createElementBlock(Fragment, null, renderList(l.item.attachments, (o) => (openBlock(), createElementBlock("div", {
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
          o.outOfStock ? (openBlock(), createElementBlock("span", _hoisted_30$1, toDisplayString(n.$lang("shopgo.message.out.of.stock")), 1)) : createCommentVNode("", !0),
          e[21] || (e[21] = createTextVNode()),
          createElementVNode("div", _hoisted_31$1, `
            x` + toDisplayString(o.quantity * l.item.quantity), 1)
        ]),
        e[23] || (e[23] = createTextVNode()),
        createElementVNode("div", _hoisted_32$1, [
          createElementVNode("div", _hoisted_33$1, [
            createElementVNode("div", _hoisted_34$1, [
              o.priceSet.base_total.price !== o.priceSet.final_total.price ? (openBlock(), createElementBlock("div", _hoisted_35$1, [
                createElementVNode("del", null, toDisplayString(n.$formatPrice(o.priceSet.base_total.price)), 1)
              ])) : createCommentVNode("", !0),
              e[22] || (e[22] = createTextVNode()),
              createElementVNode("div", _hoisted_36$1, toDisplayString(n.$formatPrice(o.priceSet.final_total.price)), 1)
            ])
          ])
        ])
      ], 8, _hoisted_22$1))), 256)),
      e[26] || (e[26] = createTextVNode()),
      createElementVNode("div", _hoisted_37$1, [
        createElementVNode("strong", null, toDisplayString(n.$lang("shopgo.cart.label.attached.product.total")), 1),
        e[24] || (e[24] = createTextVNode()),
        createElementVNode("span", _hoisted_38$1, toDisplayString(n.$formatPrice(l.item.priceSet.attached_final_total.price, { code: !0 })), 1)
      ])
    ])) : createCommentVNode("", !0)
  ], 8, _hoisted_1$3);
}
const CartListItem = /* @__PURE__ */ _export_sfc(_sfc_main$3, [["render", _sfc_render$3], ["__file", "CartListItem.vue"]]), _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "PaymentItem",
  props: {
    payment: {},
    i: {},
    selected: { type: Boolean }
  },
  emits: ["selected"],
  setup(n, { expose: e, emit: l }) {
    e();
    const t = n, m = l, V = ref(uid()), o = ref({}), r = ref(t.selected), s = ref(data("image.default"));
    watch(() => t.selected, () => {
      r.value = t.selected, setTimeout(() => {
        r.value ? slideDown(f.value) : slideUp(f.value);
      }, 0);
    });
    function p() {
      r.value = !0, m("selected");
    }
    const f = ref(), E = { props: t, emit: m, uidRef: V, data: o, selectedRef: r, imageDefault: s, onSelected: p, optionLayout: f };
    return Object.defineProperty(E, "__isScriptSetup", { enumerable: !1, value: !0 }), E;
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
function _sfc_render$2(n, e, l, t, m, V) {
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["card my-3", [t.selectedRef ? "border border-primary" : ""]])
  }, [
    createElementVNode("div", _hoisted_1$2, [
      createElementVNode("div", _hoisted_2$2, [
        createElementVNode("input", {
          type: "radio",
          id: `input-payment-id-${l.payment.id}`,
          name: "checkout[payment][id]",
          value: l.payment.id,
          class: "form-check-input",
          onChange: t.onSelected,
          checked: t.selectedRef
        }, null, 40, _hoisted_3$2),
        e[0] || (e[0] = createTextVNode()),
        createElementVNode("label", {
          for: `input-payment-id-${l.payment.id}`,
          class: "stretched-link",
          style: { cursor: "pointer" }
        }, null, 8, _hoisted_4$2)
      ]),
      e[2] || (e[2] = createTextVNode()),
      createElementVNode("div", _hoisted_5$2, [
        createElementVNode("div", _hoisted_6$2, [
          createElementVNode("img", {
            class: "object-fit-cover",
            src: l.payment.image || t.imageDefault,
            alt: "cover"
          }, null, 8, _hoisted_7$2)
        ])
      ]),
      e[3] || (e[3] = createTextVNode()),
      createElementVNode("div", null, [
        createElementVNode("h5", _hoisted_8$2, toDisplayString(l.payment.title), 1),
        e[1] || (e[1] = createTextVNode()),
        l.payment.subtitle ? (openBlock(), createElementBlock("div", _hoisted_9$2, toDisplayString(l.payment.subtitle), 1)) : createCommentVNode("", !0)
      ]),
      e[4] || (e[4] = createTextVNode()),
      e[5] || (e[5] = createElementVNode("div", { class: "ms-auto" }, null, -1))
    ]),
    e[6] || (e[6] = createTextVNode()),
    l.payment.description.trim() ? (openBlock(), createElementBlock("div", _hoisted_10$2, [
      createElementVNode("div", {
        class: "position-relative",
        style: { "z-index": "1" },
        innerHTML: l.payment.description
      }, null, 8, _hoisted_11$2)
    ])) : createCommentVNode("", !0),
    e[7] || (e[7] = createTextVNode()),
    createVNode(Transition, {
      name: "fade",
      mode: "out-in"
    }, {
      default: withCtx(() => [
        createElementVNode("div", _hoisted_12$2, [
          l.payment.optionLayout && t.selectedRef ? (openBlock(), createElementBlock("div", {
            key: 0,
            class: "card-body border-top",
            innerHTML: l.payment.optionLayout
          }, null, 8, _hoisted_13$2)) : createCommentVNode("", !0)
        ], 512)
      ]),
      _: 1
    })
  ], 2);
}
const PaymentItem = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["render", _sfc_render$2], ["__file", "PaymentItem.vue"]]), _sfc_main$1 = /* @__PURE__ */ defineComponent({
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
function _sfc_render$1(n, e, l, t, m, V) {
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["card my-3", [t.selectedRef ? "border border-primary" : ""]])
  }, [
    createElementVNode("div", _hoisted_1$1, [
      createElementVNode("div", _hoisted_2$1, [
        createElementVNode("input", {
          type: "radio",
          id: `input-shipping-id-${l.shipping.id}`,
          name: "checkout[shipping][id]",
          value: l.shipping.id,
          class: "form-check-input",
          onChange: t.onSelected,
          checked: t.selectedRef
        }, null, 40, _hoisted_3$1),
        e[0] || (e[0] = createTextVNode()),
        createElementVNode("label", {
          for: `input-shipping-id-${l.shipping.id}`,
          class: "stretched-link",
          style: { cursor: "pointer" }
        }, null, 8, _hoisted_4$1)
      ]),
      e[2] || (e[2] = createTextVNode()),
      createElementVNode("div", _hoisted_5$1, [
        createElementVNode("div", _hoisted_6$1, [
          createElementVNode("img", {
            src: l.shipping.image || t.imageDefault,
            alt: "cover"
          }, null, 8, _hoisted_7$1)
        ])
      ]),
      e[3] || (e[3] = createTextVNode()),
      createElementVNode("div", null, [
        createElementVNode("h5", _hoisted_8$1, toDisplayString(l.shipping.title), 1),
        e[1] || (e[1] = createTextVNode()),
        l.shipping.subtitle ? (openBlock(), createElementBlock("div", _hoisted_9$1, toDisplayString(l.shipping.subtitle), 1)) : createCommentVNode("", !0)
      ]),
      e[4] || (e[4] = createTextVNode()),
      createElementVNode("div", _hoisted_10$1, [
        createElementVNode("span", _hoisted_11$1, toDisplayString(n.$formatPrice(l.shipping.fee, !0)), 1)
      ])
    ]),
    e[5] || (e[5] = createTextVNode()),
    l.shipping.description.trim() ? (openBlock(), createElementBlock("div", _hoisted_12$1, [
      createElementVNode("div", {
        class: "position-relative",
        style: { "z-index": "1" },
        innerHTML: l.shipping.description
      }, null, 8, _hoisted_13$1)
    ])) : createCommentVNode("", !0),
    e[6] || (e[6] = createTextVNode()),
    createVNode(Transition, {
      name: "fade",
      mode: "out-in"
    }, {
      default: withCtx(() => [
        createElementVNode("div", _hoisted_14$1, [
          l.shipping.checkoutForm && t.selectedRef ? (openBlock(), createElementBlock("div", {
            key: 0,
            class: "card-body border-top",
            innerHTML: l.shipping.checkoutForm
          }, null, 8, _hoisted_15$1)) : createCommentVNode("", !0)
        ], 512)
      ]),
      _: 1
    })
  ], 2);
}
const ShippingItem = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["render", _sfc_render$1], ["__file", "ShippingItem.vue"]]), _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "CartApp",
  props: {
    user: {},
    checkoutData: {}
  },
  setup(n, { expose: e }) {
    e();
    const l = n, t = ref(!1), m = ref([]), V = ref({}), o = ref([]), r = ref(l.checkoutData?.payment?.id || ""), s = ref(l.checkoutData?.payment_data || {}), p = ref(l.checkoutData?.shipping?.id || ""), f = ref(l.checkoutData?.shipping_data || {}), E = ref([]), b = ref([]), D = ref({}), x = ref(""), w = ref(l.checkoutData?.note || ""), P = ref(!1), F = ref(data("partial.checkout")), C = document.querySelector("#cart-form"), k = ref(), N = useStack("loading");
    N.observe((a, _) => {
      P.value = _ > 0;
    }), i();
    function S(a = 300) {
      setTimeout(() => {
        N.pop();
      }, a);
    }
    const R = debounce(function() {
      return T();
    }, 300);
    async function T(a = !0) {
      N.push(!0);
      const { get: _, isAxiosError: c } = await useHttpClient();
      try {
        const g = await _(
          "@cart_ajax/getItems",
          {
            params: {
              location_id: f.value.locationId,
              shipping_id: p.value,
              payment_id: r.value
            }
          }
        );
        return await z(g.data.data, a), g;
      } catch (g) {
        console.error(g), c(g) && simpleAlert(g.message, "", "warning");
      } finally {
        S();
      }
    }
    async function z(a, _ = !0) {
      if (m.value = a.items, V.value = a.totals, o.value = a.coupons, _)
        return await Q();
    }
    watch(m, () => {
      M();
    }, { deep: !0 });
    const A = computed(() => m.value.map((a) => a.options.checked == null ? !0 : a.options.checked)), B = computed(() => A.value.filter((a) => a === !0).length), j = computed(() => A.value.filter((a) => a === !1).length);
    function M() {
      k.value && (k.value.checked = !1, k.value.indeterminate = !1, B.value > 0 && j.value === 0 ? k.value.checked = !0 : j.value > 0 && B.value === 0 ? k.value.checked = !1 : B.value > 0 && j.value > 0 && (k.value.indeterminate = !0));
    }
    function q() {
      if (k.value) {
        for (const a of m.value)
          a.options.checked = k.value.checked;
        L();
      }
    }
    const L = debounce(async () => {
      const a = {};
      for (const g of m.value)
        a[g.key] = g.options.checked ? "1" : "0";
      N.push(!0);
      const { post: _, isAxiosError: c } = await useHttpClient();
      try {
        const g = await _("@cart_ajax/updateChecks", { checks: a });
        return await T();
      } catch (g) {
        console.error(g), c(g) && simpleAlert(g.message, "", "warning");
      } finally {
        S();
      }
    }, 300);
    onMounted(() => {
      O(C);
    });
    function O(a, _ = 30) {
      const c = document.querySelector("header .navbar, .navbar");
      if (!c)
        return;
      const g = c.clientHeight + _;
      a.style.setProperty("--sidebar-offsets-top", g + "px");
    }
    async function i() {
      await T(), t.value = !0;
    }
    async function v(a, _) {
      N.push(!0);
      const { delete: c, isAxiosError: g } = await useHttpClient();
      try {
        const I = await c(`@cart_ajax/removeItem?key=${a.key}`);
        return await R();
      } catch (I) {
        console.error(I), g(I) && simpleAlert(I.message, "", "warning");
      } finally {
        N.pop();
      }
    }
    async function d() {
      N.push(!0);
      const { put: a, isAxiosError: _ } = await useHttpClient();
      try {
        await a("@cart_ajax/clearCart"), await T(), await simpleAlert(
          __("shopgo.cart.message.items.removed"),
          __("shopgo.cart.message.will.back.to.home"),
          "success"
        ), location.href = route("home");
      } catch (c) {
        console.error(c), _(c) && simpleAlert(c.message, "", "warning");
      } finally {
        N.pop();
      }
    }
    async function u(a, _) {
      a.quantity += _, a.quantity = Math.max(a.quantity, 1), await y(a);
    }
    const y = debounce(async (a) => {
      a.quantity = Math.max(a.quantity, 1);
      const _ = {};
      for (const I of m.value)
        _[I.key] = I.quantity;
      N.push(!0);
      const { post: c, isAxiosError: g } = await useHttpClient();
      try {
        const I = await c("@cart_ajax/updateQuantities", { values: _ });
        return await T();
      } catch (I) {
        console.error(I), g(I) && simpleAlert(I.message, "", "warning");
      } finally {
        S();
      }
    }, 300);
    async function $() {
      if (x.value === "")
        return;
      N.push(!0);
      const { post: a, isAxiosError: _ } = await useHttpClient();
      try {
        const c = await a("@cart_ajax/addCode", { code: x.value });
        x.value = "", await T();
      } catch (c) {
        console.error(c), _(c) && simpleAlert(c.message, "", "warning");
      } finally {
        S();
      }
    }
    async function H(a) {
      N.push(!0);
      const { delete: _, isAxiosError: c } = await useHttpClient();
      try {
        const g = await _("@cart_ajax/removeCode", { id: a });
        await T();
      } catch (g) {
        console.error(g), c(g) && simpleAlert(g.message, "", "warning");
      } finally {
        S();
      }
    }
    const U = computed(() => {
      const a = [];
      for (const _ in V.value) {
        if (_ === "total" || _ === "grand_total")
          continue;
        const c = V.value[_];
        Number(c.price) !== 0 && a.push(c);
      }
      return a;
    });
    watch(() => f.value.locationId, () => {
      Q();
    }), watch(() => p.value, () => {
      T(!1);
    });
    const G = computed(() => E.value.find((a) => String(a.id) === String(p.value))), Q = debounce(async function() {
      N.push(!0);
      const { get: a, isAxiosError: _ } = await useHttpClient();
      try {
        const c = await a(`@cart_ajax/shippings?location_id=${f.value.locationId}`);
        E.value = c.data.data, await nextTick(), await nextTick(), E.value.length > 0 ? G.value || (p.value = E.value[0].id) : p.value = null;
      } catch (c) {
        console.error(c), _(c) && simpleAlert(c.message, "", "warning");
      } finally {
        S();
      }
    }, 300);
    watch(() => [f.value.locationId, p.value], () => {
      K();
    });
    const Z = computed(() => b.value.find((a) => a.id === r.value)), K = debounce(async function() {
      N.push(!0);
      const { get: a, isAxiosError: _ } = await useHttpClient();
      try {
        const c = await a(
          "@cart_ajax/payments",
          {
            params: {
              location_id: f.value.locationId,
              shipping_id: p.value
            }
          }
        );
        b.value = c.data.data, await nextTick(), await nextTick(), b.value.length > 0 ? b.value.find((g) => g.id === r.value) || (r.value = b.value[0].id) : r.value = null;
      } catch (c) {
        console.error(c), _(c) && simpleAlert(c.message, "", "warning");
      } finally {
        S();
      }
    }, 300), ee = computed(() => !(B.value === 0 || !f.value.locationId || !s.value.locationId || !p.value || !r.value)), W = ref(), J = ref();
    function te() {
      if (B.value === 0) {
        console.warn("No checked items");
        return;
      }
      if (Number(V.value.grand_total.price) < 0) {
        swal("Cannot process cart with negative prices.", "", "warning");
        return;
      }
      for (const a of m.value) {
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
      if (!C.checkValidity()) {
        C.reportValidity();
        const a = C.querySelector(":invalid");
        a && !X(a) && a.dataset.validationMessage && simpleAlert(a.dataset.validationMessage);
        return;
      }
      P.value = !0, C.requestSubmit();
    }
    function X(a) {
      return !!(a.offsetWidth || a.offsetHeight || a.getClientRects().length);
    }
    const Y = { props: l, loaded: t, items: m, totals: V, coupons: o, paymentId: r, paymentData: s, shippingId: p, shippingData: f, shippings: E, payments: b, receiptData: D, code: x, note: w, loading: P, partialCheckout: F, form: C, toggleAllInput: k, loadingStack: N, popLoading: S, afterItemsChanged: R, loadItems: T, setCartData: z, itemChecks: A, checks: B, unchecks: j, updateToggleAll: M, toggleChecked: q, updateChecks: L, calcNavAndStickySidebar: O, init: i, removeItem: v, clearCart: d, changeItemQuantity: u, updateQuantities: y, addCode: $, removeCode: H, filteredTotals: U, selectedShipping: G, loadShippings: Q, selectedPayment: Z, loadPayments: K, canCheckout: ee, shippingForm: W, paymentForm: J, checkout: te, isVisible: X, get vTooltip() {
      return vTooltip;
    }, AddressForm, CartListItem, PaymentItem, ShippingItem };
    return Object.defineProperty(Y, "__isScriptSetup", { enumerable: !1, value: !0 }), Y;
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
function _sfc_render(n, e, l, t, m, V) {
  return openBlock(), createElementBlock("div", _hoisted_1, [
    createElementVNode("div", _hoisted_2, [
      createElementVNode("header", _hoisted_3, [
        createElementVNode("div", _hoisted_4, [
          createElementVNode("h3", _hoisted_5, toDisplayString(n.$lang("shopgo.cart.title")), 1),
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
            createElementVNode("label", _hoisted_7, toDisplayString(n.$lang("shopgo.cart.toggle.all")), 1)
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
            createTextVNode(" " + toDisplayString(n.$lang("shopgo.cart.button.remove.all")), 1)
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
          (openBlock(!0), createElementBlock(Fragment, null, renderList(t.items, (o, r) => (openBlock(), createBlock(t.CartListItem, {
            key: o.key,
            item: o,
            "has-checkbox": t.partialCheckout,
            onRemoveItem: (s) => t.removeItem(o, r),
            onUpdateQuantity: (s) => t.updateQuantities(o),
            onChangeItemQuantity: (s) => t.changeItemQuantity(o, s),
            onUpdateChecks: t.updateChecks
          }, null, 8, ["item", "has-checkbox", "onRemoveItem", "onUpdateQuantity", "onChangeItemQuantity", "onUpdateChecks"]))), 128))
        ]),
        e[13] || (e[13] = createTextVNode()),
        createElementVNode("div", _hoisted_11, [
          createVNode(t.AddressForm, {
            type: "payment",
            title: n.$lang("shopgo.cart.payment.data.title"),
            user: l.user,
            modelValue: t.paymentData,
            "onUpdate:modelValue": e[0] || (e[0] = (o) => t.paymentData = o),
            ref: "paymentForm"
          }, null, 8, ["title", "user", "modelValue"]),
          e[9] || (e[9] = createTextVNode()),
          createVNode(t.AddressForm, {
            type: "shipping",
            title: n.$lang("shopgo.cart.shipping.data.title"),
            user: l.user,
            modelValue: t.shippingData,
            "onUpdate:modelValue": e[1] || (e[1] = (o) => t.shippingData = o),
            "sync-data": t.paymentData,
            ref: "shippingForm"
          }, null, 8, ["title", "user", "modelValue", "sync-data"])
        ]),
        e[14] || (e[14] = createTextVNode()),
        createElementVNode("div", _hoisted_12, [
          createElementVNode("h3", null, toDisplayString(n.$lang("shopgo.cart.shipping.title")), 1),
          e[10] || (e[10] = createTextVNode()),
          t.shippings.length > 0 ? (openBlock(), createElementBlock("div", _hoisted_13, [
            (openBlock(!0), createElementBlock(Fragment, null, renderList(t.shippings, (o, r) => (openBlock(), createBlock(t.ShippingItem, {
              key: o.id,
              style: { "animation-duration": ".1s" },
              shipping: o,
              i: r,
              selected: t.shippingId === o.id,
              onSelected: (s) => t.shippingId = o.id
            }, null, 8, ["shipping", "i", "selected", "onSelected"]))), 128))
          ])) : (openBlock(), createElementBlock("div", _hoisted_14, [
            createElementVNode("div", _hoisted_15, [
              t.loading ? (openBlock(), createElementBlock("span", _hoisted_16)) : t.shippingData.locationId ? (openBlock(), createElementBlock(Fragment, { key: 1 }, [
                createTextVNode(toDisplayString(n.$lang("shopgo.cart.text.no.shippings")), 1)
              ], 64)) : (openBlock(), createElementBlock(Fragment, { key: 2 }, [
                createTextVNode(toDisplayString(n.$lang("shopgo.cart.text.select.location.first")), 1)
              ], 64))
            ])
          ]))
        ]),
        e[15] || (e[15] = createTextVNode()),
        createElementVNode("div", _hoisted_17, [
          createElementVNode("h3", null, toDisplayString(n.$lang("shopgo.cart.payment.title")), 1),
          e[11] || (e[11] = createTextVNode()),
          t.payments.length > 0 ? (openBlock(), createElementBlock("div", _hoisted_18, [
            (openBlock(!0), createElementBlock(Fragment, null, renderList(t.payments, (o, r) => (openBlock(), createBlock(t.PaymentItem, {
              key: o.id,
              style: { "animation-duration": ".1s" },
              payment: o,
              i: r,
              selected: t.paymentId === o.id,
              onSelected: (s) => t.paymentId = o.id
            }, null, 8, ["payment", "i", "selected", "onSelected"]))), 128))
          ])) : (openBlock(), createElementBlock("div", _hoisted_19, [
            createElementVNode("div", _hoisted_20, [
              t.loading ? (openBlock(), createElementBlock("span", _hoisted_21)) : t.shippingData.shippingId ? (openBlock(), createElementBlock(Fragment, { key: 1 }, [
                createTextVNode(toDisplayString(n.$lang("shopgo.cart.text.no.payments")), 1)
              ], 64)) : (openBlock(), createElementBlock(Fragment, { key: 2 }, [
                createTextVNode(toDisplayString(n.$lang("shopgo.cart.text.select.shipping.first")), 1)
              ], 64))
            ])
          ]))
        ]),
        e[16] || (e[16] = createTextVNode()),
        createElementVNode("div", _hoisted_22, [
          createElementVNode("div", _hoisted_23, [
            createElementVNode("h5", _hoisted_24, toDisplayString(n.$lang("shopgo.cart.field.note")), 1),
            e[12] || (e[12] = createTextVNode()),
            withDirectives(createElementVNode("textarea", {
              rows: "4",
              class: "form-control",
              "onUpdate:modelValue": e[2] || (e[2] = (o) => t.note = o),
              name: "checkout[note]",
              placeholder: n.$lang("shopgo.cart.field.note.placeholder")
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
            createElementVNode("h5", null, toDisplayString(n.$lang("shopgo.cart.label.discount.code")), 1),
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
              }, toDisplayString(n.$lang("shopgo.cart.button.use.discount.code")), 9, _hoisted_31)
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
                    onClick: (r) => t.removeCode(o.id)
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
              createElementVNode("div", _hoisted_40, toDisplayString(n.$lang("shopgo.cart.label.total")), 1),
              e[27] || (e[27] = createTextVNode()),
              t.totals.total ? (openBlock(), createElementBlock("div", _hoisted_41, toDisplayString(n.$formatPrice(t.totals.total.price, { code: !0 })), 1)) : createCommentVNode("", !0)
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
              createElementVNode("div", _hoisted_45, toDisplayString(n.$formatPrice(o.price, { code: !0 })), 1)
            ]))), 256))
          ])) : createCommentVNode("", !0)
        ]),
        e[46] || (e[46] = createTextVNode()),
        createElementVNode("div", _hoisted_46, [
          createElementVNode("div", _hoisted_47, [
            t.loaded ? (openBlock(), createElementBlock("div", _hoisted_48, [
              createElementVNode("div", _hoisted_49, toDisplayString(n.$lang("shopgo.cart.label.grand.total")), 1),
              e[34] || (e[34] = createTextVNode()),
              t.totals.grand_total ? (openBlock(), createElementBlock("div", _hoisted_50, [
                createElementVNode("div", null, toDisplayString(n.$formatPrice(t.totals.grand_total.price, { code: !0 })), 1),
                e[33] || (e[33] = createTextVNode()),
                n.$currency.isSubCurrency() ? (openBlock(), createElementBlock("div", _hoisted_51, `
                  (` + toDisplayString(n.$currency.formatMainCurrency(t.totals.grand_total.price, { code: !0 })) + `)
                `, 1)) : createCommentVNode("", !0)
              ])) : createCommentVNode("", !0)
            ])) : createCommentVNode("", !0),
            e[42] || (e[42] = createTextVNode()),
            t.loaded ? (openBlock(), createElementBlock("div", _hoisted_52, [
              createElementVNode("div", null, [
                e[35] || (e[35] = createElementVNode("i", { class: "fa fa-truck" }, null, -1)),
                createTextVNode(" " + toDisplayString(t.selectedShipping?.title || n.$lang("shopgo.message.no.shipping.selected")), 1)
              ]),
              e[37] || (e[37] = createTextVNode()),
              createElementVNode("div", null, [
                e[36] || (e[36] = createElementVNode("i", { class: "fa fa-credit-card" }, null, -1)),
                createTextVNode(" " + toDisplayString(t.selectedPayment?.title || n.$lang("shopgo.message.no.payment.selected")), 1)
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
                  createTextVNode(toDisplayString(n.$lang("shopgo.cart.button.process.checkout")), 1)
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
const CartApp = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render], ["__file", "CartApp.vue"]]);
function initApp(n) {
  useCssImport("@vue-animate");
  const e = createApp(CartApp, n);
  return e.use(ShopGoPlugin), e;
}
export {
  initApp
};
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiY2FydC5qcyIsInNvdXJjZXMiOlsiLi4vLi4vc3JjL21vZHVsZXMvY2FydC9jb21wb25lbnRzL0Nhc2NhZGVTZWxlY3QudnVlIiwiLi4vLi4vc3JjL21vZHVsZXMvY2FydC9jb21wb25lbnRzL0FkZHJlc3NGb3JtLnZ1ZSIsIi4uLy4uL3NyYy9tb2R1bGVzL2NhcnQvY29tcG9uZW50cy9DYXJ0TGlzdEl0ZW0udnVlIiwiLi4vLi4vc3JjL21vZHVsZXMvY2FydC9jb21wb25lbnRzL1BheW1lbnRJdGVtLnZ1ZSIsIi4uLy4uL3NyYy9tb2R1bGVzL2NhcnQvY29tcG9uZW50cy9TaGlwcGluZ0l0ZW0udnVlIiwiLi4vLi4vc3JjL21vZHVsZXMvY2FydC9DYXJ0QXBwLnZ1ZSIsIi4uLy4uL3NyYy9tb2R1bGVzL2NhcnQvY2FydC50cyJdLCJzb3VyY2VzQ29udGVudCI6WyI8c2NyaXB0IGxhbmc9XCJ0c1wiPlxuZXhwb3J0IGludGVyZmFjZSBDYXNjYWRlT3B0aW9ucyB7XG4gIGlkPzogc3RyaW5nO1xuICBzZWxlY3RlZD86IHN0cmluZztcbiAgaWdub3JlU2VsZj86IGFueTtcbiAgcGxhY2Vob2xkZXI/OiBzdHJpbmc7XG4gIHBsYWNlaG9sZGVycz86IHN0cmluZ1tdO1xuICBhamF4VXJsPzogc3RyaW5nO1xuICBhamF4VmFsdWVGaWVsZD86IHN0cmluZztcbiAgc291cmNlPzogYW55W107XG4gIGxhYmVscz86IHN0cmluZ1tdO1xuICBsYWJlbFdpZHRoPzogc3RyaW5nO1xuICBmaWVsZFdpZHRoPzogc3RyaW5nO1xuICByZWFkb25seT86IGJvb2xlYW47XG4gIGRpc2FibGVkPzogYm9vbGVhbjtcbiAgdmFsdWVGaWVsZD86IHN0cmluZztcbiAgdGV4dEZpZWxkPzogc3RyaW5nO1xuICBob3Jpem9udGFsPzogYm9vbGVhbiB8IG51bGw7XG4gIGhvcml6b250YWxDb2xXaWR0aD86IHN0cmluZyB8IG51bGw7XG4gIGRlZmF1bHRWYWx1ZT86IGFueTtcbiAgb25TZWxlY3RJbml0PzogKGU6IEN1c3RvbUV2ZW50KSA9PiB2b2lkO1xuICBvbkNoYW5nZT86IChlOiBFdmVudCkgPT4gdm9pZDtcbiAgb25WYWx1ZUluaXQ/OiAoZTogRXZlbnQpID0+IHZvaWQ7XG59XG48L3NjcmlwdD5cbjxzY3JpcHQgc2V0dXAgbGFuZz1cInRzXCI+XG5pbXBvcnQgeyB1c2VIdHRwQ2xpZW50IH0gZnJvbSAnQHdpbmR3YWxrZXItaW8vdW5pY29ybi1uZXh0JztcbmltcG9ydCB7IHJlZiwgcmVhY3RpdmUsIG9uTW91bnRlZCwgd2F0Y2gsIG5leHRUaWNrIH0gZnJvbSAndnVlJztcblxuLy8gUHJvcHNcbmNvbnN0IHByb3BzID0gZGVmaW5lUHJvcHM8e1xuICBvcHRpb25zPzogQ2FzY2FkZU9wdGlvbnM7XG4gIHNlbGVjdEF0dHJzPzogUmVjb3JkPHN0cmluZywgYW55PjtcbiAgbmFtZT86IHN0cmluZztcbn0+KCk7XG5cbmNvbnN0IG1vZGVsVmFsdWUgPSBkZWZpbmVNb2RlbDxhbnlbXT4oeyByZXF1aXJlZDogZmFsc2UgfSk7XG5cbi8vIEVtaXRzXG5jb25zdCBlbWl0ID0gZGVmaW5lRW1pdHM8e1xuICAoZTogJ2NoYW5nZScsIGV2ZW50OiBDdXN0b21FdmVudCk6IHZvaWQ7XG59PigpO1xuXG4vLyBBY2Nlc3MgZ2xvYmFsIGhlbHBlciBgdWAgKHNhbWUgYXMgb3JpZ2luYWwgY29kZSlcbmNvbnN0IHUgPSAoZ2xvYmFsVGhpcyBhcyBhbnkpLnUgfHwgKHdpbmRvdyBhcyBhbnkpLnU7XG5cbi8vIERlZmF1bHQgb3B0aW9uc1xuY29uc3QgZGVmYXVsdE9wdDogQ2FzY2FkZU9wdGlvbnMgPSB7XG4gIGlkOiAnY2FzY2FkZS1zZWxlY3QtJyArICh1ICYmIHUudWlkID8gdS51aWQoKSA6IFN0cmluZyhNYXRoLnJhbmRvbSgpKSksXG4gIHNlbGVjdGVkOiAnJyxcbiAgaWdub3JlU2VsZjogbnVsbCxcbiAgcGxhY2Vob2xkZXI6ICctIFNlbGVjdCAtJyxcbiAgcGxhY2Vob2xkZXJzOiBbXSxcbiAgYWpheFVybDogJycsXG4gIGFqYXhWYWx1ZUZpZWxkOiAndmFsdWUnLFxuICBzb3VyY2U6IFtdLFxuICBsYWJlbHM6IFtdLFxuICBsYWJlbFdpZHRoOiAnY29sLW1kLTMnLFxuICBmaWVsZFdpZHRoOiAnY29sJyxcbiAgcmVhZG9ubHk6IGZhbHNlLFxuICBkaXNhYmxlZDogZmFsc2UsXG4gIHZhbHVlRmllbGQ6ICdpZCcsXG4gIHRleHRGaWVsZDogJ3RpdGxlJyxcbiAgaG9yaXpvbnRhbDogbnVsbCxcbiAgaG9yaXpvbnRhbENvbFdpZHRoOiBudWxsLFxuICBkZWZhdWx0VmFsdWU6ICcnLFxuICBvblNlbGVjdEluaXQ6IChlOiBDdXN0b21FdmVudCkgPT4ge30sXG4gIG9uQ2hhbmdlOiAoZTogRXZlbnQpID0+IHt9LFxuICBvblZhbHVlSW5pdDogKGU6IEV2ZW50KSA9PiB7fSxcbn07XG5cbi8vIFJlYWN0aXZlIHN0YXRlXG5jb25zdCBvcHQgPSByZWFjdGl2ZTxDYXNjYWRlT3B0aW9ucz4oT2JqZWN0LmFzc2lnbih7fSwgZGVmYXVsdE9wdCwgcHJvcHMub3B0aW9ucyB8fCB7fSkpO1xuY29uc3QgbGlzdHMgPSByZWY8YW55W10+KFtdKTtcbmNvbnN0IHZhbHVlcyA9IHJlZjxhbnlbXT4oW10pO1xuY29uc3QgY2FuTW9kaWZ5ID0gcmVmKHRydWUpO1xuY29uc3QgbG9hZGluZyA9IHJlZihmYWxzZSk7XG5jb25zdCBhamF4VXJsID0gcmVmKG9wdC5hamF4VXJsIHx8ICcnKTtcblxuLy8gUmVmcyBmb3IgRE9NXG5jb25zdCByb290ID0gcmVmPEhUTUxFbGVtZW50PigpO1xuY29uc3Qgc2VsZWN0cyA9IHJlZjxIVE1MU2VsZWN0RWxlbWVudFtdPihbXSk7XG5cbmZ1bmN0aW9uIGluaXQoKSB7XG4gIGNhbk1vZGlmeS52YWx1ZSA9ICFvcHQucmVhZG9ubHkgJiYgIW9wdC5kaXNhYmxlZDtcbiAgYWpheFVybC52YWx1ZSA9IG9wdC5hamF4VXJsIHx8ICcnO1xufVxuXG5hc3luYyBmdW5jdGlvbiBwcmVwYXJlVmFsdWVzKCkge1xuICBpZiAobG9hZGluZy52YWx1ZSkge1xuICAgIHJldHVybjtcbiAgfVxuXG4gIGxvYWRpbmcudmFsdWUgPSB0cnVlO1xuICBsaXN0cy52YWx1ZSA9IFtdO1xuXG4gIGNvbnN0IGluY29taW5nID0gKG1vZGVsVmFsdWUudmFsdWUgfHwgW10pLnNsaWNlKCkubWFwKCh2OiBhbnkpID0+IFN0cmluZyh2KSk7XG4gIGxldCB2YWxzID0gWy4uLmluY29taW5nXTtcbiAgdmFsdWVzLnZhbHVlID0gWy4uLnZhbHNdO1xuXG4gIGlmICh2YWxzLmxlbmd0aCA9PT0gMCkge1xuICAgIHZhbHMgPSBbbnVsbF07XG4gIH0gZWxzZSB7XG4gICAgdmFscy51bnNoaWZ0KG51bGwpO1xuICB9XG5cbiAgbGV0IGxhc3RWYWx1ZTogYW55ID0gbnVsbDtcblxuICBmb3IgKGxldCBpIGluIHZhbHMpIHtcbiAgICBjb25zdCB2ID0gdmFsc1tpXTtcbiAgICAvLyBsb2FkSXRlbXMgcmV0dXJucyBQcm9taXNlIG9mIGxpc3RcbiAgICAvLyBpIGlzIHN0cmluZyBpbmRleDsgY29udmVydCB0byBudW1iZXIgaWYgbmVlZGVkIGluIGxvYWRJdGVtc1xuICAgIC8vIHBhc3MgaSBzbyBsb2FkSXRlbXMgY2FuIHVzZSBwcmV2aW91cyBsaXN0XG4gICAgLy8gbG9hZEl0ZW1zIG1heSBjYWxsIGFqYXggb3Igc291cmNlXG4gICAgLy8gd2UgYXdhaXQgZWFjaCBsZXZlbFxuICAgIC8vIE5vdGU6IGxvYWRJdGVtcyBleHBlY3RzIChwYXJlbnRJZCwgaSlcbiAgICAvLyB1c2UgTnVtYmVyKGkpIHdoZW4gcGFzc2luZyB0byBoYW5kbGUgc291cmNlIGxvb2t1cFxuICAgIGNvbnN0IGxpc3QgPSBhd2FpdCBsb2FkSXRlbXModiwgTnVtYmVyKGkpKTtcblxuICAgIGlmIChsaXN0ICYmIGxpc3QubGVuZ3RoID4gMCkge1xuICAgICAgbGlzdHMudmFsdWUucHVzaChsaXN0KTtcbiAgICB9XG5cbiAgICBsYXN0VmFsdWUgPSB2O1xuICB9XG5cbiAgdmFsdWVJbml0KHJvb3QudmFsdWUsIGxhc3RWYWx1ZSwgdmFscyk7XG5cbiAgbG9hZGluZy52YWx1ZSA9IGZhbHNlO1xuXG4gIGF3YWl0IG5leHRUaWNrKCk7XG4gIC8vIGNhbGwgc2VsZWN0SW5pdCBmb3IgZmlyc3Qgc2VsZWN0IGlmIGV4aXN0c1xuICBpZiAoc2VsZWN0cy52YWx1ZSAmJiBzZWxlY3RzLnZhbHVlLmxlbmd0aCA+IDApIHtcbiAgICBzZWxlY3RJbml0KHNlbGVjdHMudmFsdWVbMF0pO1xuICB9XG59XG5cbmZ1bmN0aW9uIHJlc2V0KCkge1xuICB2b2lkIHByZXBhcmVWYWx1ZXMoKTtcbn1cblxuZnVuY3Rpb24gZ2V0TGFiZWwoaTogbnVtYmVyKSB7XG4gIHJldHVybiBvcHQubGFiZWxzW2ldIHx8IGBMZXZlbCAke2kgKyAxfWA7XG59XG5cbmZ1bmN0aW9uIGdldElkKGk6IG51bWJlcikge1xuICByZXR1cm4gYCR7b3B0LmlkfV9fbGV2ZWwtJHtpfWA7XG59XG5cbmZ1bmN0aW9uIGdldExpc3RWYWx1ZShpOiBudW1iZXIpIHtcbiAgcmV0dXJuIHZhbHVlcy52YWx1ZVtpXSB8fCAnJztcbn1cblxuZnVuY3Rpb24gaXNTZWxlY3RlZChpOiBudW1iZXIsIGl0ZW06IGFueSkge1xuICByZXR1cm4gU3RyaW5nKGdldExpc3RWYWx1ZShpKSkgPT09IFN0cmluZyhpdGVtW29wdC52YWx1ZUZpZWxkXSk7XG59XG5cbmZ1bmN0aW9uIGdldEZpbmFsVmFsdWUoKSB7XG4gIGNvbnN0IHZzID0gdmFsdWVzLnZhbHVlLnNsaWNlKCk7XG5cbiAgaWYgKHZzLmxlbmd0aCA9PT0gMCkge1xuICAgIHJldHVybiBvcHQuZGVmYXVsdFZhbHVlO1xuICB9XG5cbiAgY29uc3QgdiA9IHZzXG4gICAgLmZpbHRlcigodjIpID0+IHYyICE9IG51bGwpXG4gICAgLmZpbHRlcigodjIpID0+IHYyICE9PSAnJylcbiAgICAucG9wKCk7XG5cbiAgaWYgKHYgPT09IHVuZGVmaW5lZCkge1xuICAgIHJldHVybiBvcHQuZGVmYXVsdFZhbHVlO1xuICB9XG5cbiAgcmV0dXJuIHY7XG59XG5cbmZ1bmN0aW9uIGdldExldmVsKCkge1xuICByZXR1cm4gdmFsdWVzLnZhbHVlLmxlbmd0aDtcbn1cblxuYXN5bmMgZnVuY3Rpb24gb25DaGFuZ2UoaTogbnVtYmVyLCBldmVudDogRXZlbnQpIHtcbiAgY29uc3QgZWwgPSBldmVudC50YXJnZXQgYXMgSFRNTFNlbGVjdEVsZW1lbnQ7XG5cbiAgdmFsdWVzLnZhbHVlW2ldID0gZWwudmFsdWU7XG5cbiAgLy8gY2FsbCB1c2VyIHByb3ZpZGVkIG9uQ2hhbmdlXG4gIHRyeSB7XG4gICAgb3B0Lm9uQ2hhbmdlKGV2ZW50KTtcbiAgfSBjYXRjaCAoZSkge1xuICAgIC8vIGlnbm9yZSBlcnJvcnMgZnJvbSB1c2VyIGNhbGxiYWNrXG4gIH1cblxuICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICBjb25zdCBjaGFuZ2VFdmVudCA9IG5ldyBDdXN0b21FdmVudCgnY2hhbmdlJywge1xuICAgIGRldGFpbDoge1xuICAgICAgZWwsXG4gICAgICBjb21wb25lbnQ6IGNvbXBvbmVudEFQSSxcbiAgICAgIHZhbHVlOiBlbC52YWx1ZSxcbiAgICAgIHBhdGg6IHZhbHVlcy52YWx1ZSxcbiAgICB9LFxuICB9KTtcblxuICAvLyBkaXNwYXRjaCBvbiByb290IGVsZW1lbnRcbiAgcm9vdC52YWx1ZT8uZGlzcGF0Y2hFdmVudChjaGFuZ2VFdmVudCk7XG5cbiAgLy8gdXBkYXRlIGxvY2FsIG1vZGVsIChkZWZpbmVNb2RlbClcbiAgbW9kZWxWYWx1ZS52YWx1ZSA9IHZhbHVlcy52YWx1ZTtcblxuICAvLyBrZWVwIGVtaXR0aW5nIGV2ZW50cyBmb3IgYmFja3dhcmQgY29tcGF0aWJpbGl0eVxuICBlbWl0KCdjaGFuZ2UnLCBjaGFuZ2VFdmVudCk7XG5cbiAgaWYgKGVsLnZhbHVlID09PSAnJykge1xuICAgIC8vIENsZWFyIGNoaWxkXG4gICAgbGlzdHMudmFsdWUuc3BsaWNlKGkgKyAxKTtcbiAgICB2YWx1ZXMudmFsdWUuc3BsaWNlKGkgKyAxKTtcbiAgICByZXR1cm47XG4gIH1cblxuICAvLyBHZXQgY2hpbGQgbGlzdFxuICBjb25zdCBsaXN0ID0gYXdhaXQgbG9hZEl0ZW1zKGVsLnZhbHVlLCBpKTtcblxuICAvLyBDbGVhciBjaGlsZFxuICBsaXN0cy52YWx1ZS5zcGxpY2UoaSArIDEpO1xuICB2YWx1ZXMudmFsdWUuc3BsaWNlKGkgKyAxKTtcblxuICBpZiAobGlzdCAmJiBsaXN0Lmxlbmd0aCA+IDApIHtcbiAgICBsaXN0cy52YWx1ZS5wdXNoKGxpc3QpO1xuXG4gICAgYXdhaXQgbmV4dFRpY2soKTtcblxuICAgIC8vIGluaXRpYWxpemUgbmV3bHkgYWRkZWQgc2VsZWN0XG4gICAgY29uc3QgbGFzdEluZGV4ID0gc2VsZWN0cy52YWx1ZS5sZW5ndGggLSAxO1xuICAgIGlmIChzZWxlY3RzLnZhbHVlICYmIHNlbGVjdHMudmFsdWVbbGFzdEluZGV4XSkge1xuICAgICAgc2VsZWN0SW5pdChzZWxlY3RzLnZhbHVlW2xhc3RJbmRleF0pO1xuICAgIH1cbiAgfVxufVxuXG5hc3luYyBmdW5jdGlvbiBsb2FkSXRlbXMocGFyZW50SWQ6IG51bWJlciB8IHVuZGVmaW5lZCwgaTogbnVtYmVyKSB7XG4gIGNvbnN0IHsgZ2V0IH0gPSBhd2FpdCB1c2VIdHRwQ2xpZW50KCk7XG5cbiAgY29uc3QgcmVzID0gYXdhaXQgZ2V0KFxuICAgIGFqYXhVcmwudmFsdWUsXG4gICAge1xuICAgICAgcGFyYW1zOiB7XG4gICAgICAgIFtvcHQuYWpheFZhbHVlRmllbGRdOiBwYXJlbnRJZCxcbiAgICAgICAgc2VsZjogb3B0Lmlnbm9yZVNlbGYgfHwgbnVsbCxcbiAgICAgIH0sXG4gICAgfVxuICApO1xuXG4gIHJldHVybiByZXMuZGF0YS5kYXRhO1xuXG4gIC8vIFNvdXJjZVxuICBpZiAocGFyZW50SWQpIHtcbiAgICBjb25zdCBwcmV2TGlzdCA9IGxpc3RzLnZhbHVlW2kgLSAxXSB8fCBbXTtcbiAgICBjb25zdCBub2RlID0gZmluZEZyb21MaXN0KHByZXZMaXN0LCBwYXJlbnRJZCk7XG4gICAgY29uc3QgY2hpbGRyZW4gPSBub2RlPy5jaGlsZHJlbiB8fCBbXTtcbiAgICByZXR1cm4gaGFuZGxlU291cmNlSXRlbXMoY2hpbGRyZW4pO1xuICB9XG5cbiAgcmV0dXJuIGhhbmRsZVNvdXJjZUl0ZW1zKG9wdC5zb3VyY2UgfHwgW10pO1xufVxuXG5mdW5jdGlvbiB2YWx1ZUluaXQoJHNlbGVjdDogYW55LCB2YWx1ZTogYW55LCBwYXRoOiBhbnlbXSkge1xuICBjb25zdCBldmVudCA9IG5ldyBDdXN0b21FdmVudCgndmFsdWUuaW5pdCcsIHtcbiAgICBkZXRhaWw6IHtcbiAgICAgIGVsOiAkc2VsZWN0LFxuICAgICAgY29tcG9uZW50OiBjb21wb25lbnRBUEksXG4gICAgICB2YWx1ZSxcbiAgICAgIHBhdGgsXG4gICAgfSxcbiAgfSk7XG5cbiAgcm9vdC52YWx1ZT8uZGlzcGF0Y2hFdmVudChldmVudCk7XG59XG5cbmZ1bmN0aW9uIHNlbGVjdEluaXQoJHNlbGVjdDogYW55KSB7XG4gIGNvbnN0IGV2ZW50ID0gbmV3IEN1c3RvbUV2ZW50KCdzZWxlY3QuaW5pdCcsIHtcbiAgICBkZXRhaWw6IHtcbiAgICAgIGVsOiAkc2VsZWN0LFxuICAgICAgY29tcG9uZW50OiBjb21wb25lbnRBUEksXG4gICAgfSxcbiAgfSk7XG5cbiAgb3B0Lm9uU2VsZWN0SW5pdChldmVudCk7XG5cbiAgcm9vdC52YWx1ZT8uZGlzcGF0Y2hFdmVudChldmVudCk7XG59XG5cbmZ1bmN0aW9uIGhhbmRsZVNvdXJjZUl0ZW1zKGl0ZW1zSW46IGFueVtdKSB7XG4gIHJldHVybiBpdGVtc0luLm1hcCgoaXRlbSkgPT4ge1xuICAgIHJldHVybiB7XG4gICAgICBbb3B0LnZhbHVlRmllbGRdOiBpdGVtLnZhbHVlW29wdC52YWx1ZUZpZWxkXSxcbiAgICAgIFtvcHQudGV4dEZpZWxkXTogaXRlbS52YWx1ZVtvcHQudGV4dEZpZWxkXSxcbiAgICAgIGNoaWxkcmVuOiBpdGVtLmNoaWxkcmVuLFxuICAgIH07XG4gIH0pLmZpbHRlcigoaXRlbSkgPT4ge1xuICAgIGlmIChvcHQuaWdub3JlU2VsZikge1xuICAgICAgcmV0dXJuIGl0ZW1bb3B0LnZhbHVlRmllbGRdICE9IG9wdC5pZ25vcmVTZWxmO1xuICAgIH1cblxuICAgIHJldHVybiBpdGVtO1xuICB9KTtcbn1cblxuZnVuY3Rpb24gZmluZEZyb21MaXN0KGl0ZW1zSW46IGFueVtdLCB2YWx1ZTogYW55KSB7XG4gIGNvbnN0IGZvdW5kID0gKGl0ZW1zSW4gfHwgW10pLmZpbHRlcigoaXRlbSkgPT4gaXRlbVtvcHQudmFsdWVGaWVsZF0gPT0gdmFsdWUpO1xuICByZXR1cm4gZm91bmQuc2hpZnQoKTtcbn1cblxuZnVuY3Rpb24gZ2V0UGxhY2Vob2xkZXIoaTogbnVtYmVyKSB7XG4gIGlmIChvcHQucGxhY2Vob2xkZXJzICYmIG9wdC5wbGFjZWhvbGRlcnNbaV0pIHtcbiAgICByZXR1cm4gb3B0LnBsYWNlaG9sZGVyc1tpXTtcbiAgfVxuXG4gIHJldHVybiBvcHQucGxhY2Vob2xkZXI7XG59XG5cbi8vIGNvbXBvbmVudEFQSSB1c2VkIGluIGV2ZW50cyB0byBlbXVsYXRlIGB0aGlzYCByZWZlcmVuY2UgZnJvbSBvcmlnaW5hbFxuY29uc3QgY29tcG9uZW50QVBJID0ge1xuICBvcHQsXG4gIGxpc3RzLFxuICB2YWx1ZXMsXG4gIGdldEZpbmFsVmFsdWUsXG4gIGdldExldmVsLFxuICBnZXRMYWJlbCxcbiAgZ2V0SWQsXG4gIGdldExpc3RWYWx1ZSxcbiAgaXNTZWxlY3RlZCxcbiAgb25DaGFuZ2UsXG4gIGxvYWRJdGVtcyxcbiAgdmFsdWVJbml0LFxuICBzZWxlY3RJbml0LFxuICBoYW5kbGVTb3VyY2VJdGVtcyxcbiAgZmluZEZyb21MaXN0LFxuICBnZXRQbGFjZWhvbGRlcixcbn07XG5cbi8vIFdhdGNoIHByb3BzLm1vZGVsVmFsdWUgdG8gcmVzZXQgd2hlbiBlbXB0aWVkXG53YXRjaChtb2RlbFZhbHVlLCAodjogYW55KSA9PiB7XG4gIGlmICghdiB8fCB2Lmxlbmd0aCA9PT0gMCkge1xuICAgIHJlc2V0KCk7XG4gIH1cbn0sIHsgZGVlcDogdHJ1ZSB9KTtcblxuLy8gTW91bnRlZFxub25Nb3VudGVkKGFzeW5jICgpID0+IHtcbiAgaW5pdCgpO1xuICBhd2FpdCBwcmVwYXJlVmFsdWVzKCk7XG59KTtcblxuZGVmaW5lRXhwb3NlKHtcbiAgcHJlcGFyZVZhbHVlc1xufSlcbjwvc2NyaXB0PlxuXG48dGVtcGxhdGU+XG4gIDxkaXYgcmVmPVwicm9vdFwiPlxuICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIHJvdyBtYi0yXCJcbiAgICAgIHYtZm9yPVwiKGl0ZW1zLCBpKSBvZiBsaXN0c1wiIDprZXk9XCJpdGVtc1wiXG4gICAgICA6Y2xhc3M9XCJbb3B0Lmhvcml6b250YWwgPyAob3B0Lmhvcml6b250YWxDb2xXaWR0aCB8fCAnY29sJykgOiAnJ11cIlxuICAgICAgOmRhdGEtbGV2ZWw9XCJpXCJcbiAgICA+XG4gICAgICA8bGFiZWwgOmZvcj1cImdldElkKGkpXCJcbiAgICAgICAgY2xhc3M9XCJjLWNhc2NhZGUtc2VsZWN0X19sYWJlbCBtYi0yXCJcbiAgICAgICAgOmNsYXNzPVwib3B0LmxhYmVsV2lkdGggfHwgJ2NvbC1tZC0zJ1wiPlxuICAgICAgICB7eyBnZXRMYWJlbChpKSB9fVxuICAgICAgPC9sYWJlbD5cblxuICAgICAgPGRpdiBjbGFzcz1cImNvbCBjLWNhc2NhZGUtc2VsZWN0X19pbnB1dFwiPlxuICAgICAgICA8c2VsZWN0IDppZD1cImdldElkKGkpXCIgOmRpc2FibGVkPVwiIWNhbk1vZGlmeVwiXG4gICAgICAgICAgY2xhc3M9XCJmb3JtLXNlbGVjdCBjdXN0b20tc2VsZWN0XCJcbiAgICAgICAgICA6cmVmPVwiZWwgPT4gKHNlbGVjdHNbaV0gPSBlbClcIlxuICAgICAgICAgIEBjaGFuZ2U9XCJvbkNoYW5nZShpLCAkZXZlbnQpXCJcbiAgICAgICAgPlxuICAgICAgICAgIDxvcHRpb24gdmFsdWU9XCJcIj5cbiAgICAgICAgICAgIHt7IGdldFBsYWNlaG9sZGVyKGkpIH19XG4gICAgICAgICAgPC9vcHRpb24+XG4gICAgICAgICAgPG9wdGlvbiA6dmFsdWU9XCJpdGVtW29wdC52YWx1ZUZpZWxkXVwiXG4gICAgICAgICAgICB2LWZvcj1cIml0ZW0gb2YgaXRlbXNcIiA6a2V5PVwiaXRlbVtvcHQudmFsdWVGaWVsZF1cIlxuICAgICAgICAgICAgOnNlbGVjdGVkPVwiaXNTZWxlY3RlZChpLCBpdGVtKVwiXG4gICAgICAgICAgPlxuICAgICAgICAgICAge3sgaXRlbVtvcHQudGV4dEZpZWxkXSB9fVxuICAgICAgICAgIDwvb3B0aW9uPlxuICAgICAgICA8L3NlbGVjdD5cbiAgICAgIDwvZGl2PlxuICAgIDwvZGl2PlxuXG4gICAgPGlucHV0IDpuYW1lPVwicHJvcHMubmFtZVwiIHR5cGU9XCJoaWRkZW5cIiA6dmFsdWU9XCJnZXRGaW5hbFZhbHVlKClcIiAvPlxuICA8L2Rpdj5cbjwvdGVtcGxhdGU+XG5cbjxzdHlsZSBzY29wZWQ+XG5cbjwvc3R5bGU+XG4iLCI8c2NyaXB0IHNldHVwIGxhbmc9XCJ0c1wiPlxuaW1wb3J0IHsgX18sIGRhdGEsIGRhdGEgYXMgdWRhdGEsIHJvdXRlLCB1c2VIdHRwQ2xpZW50LCB1c2VUb21TZWxlY3QgfSBmcm9tICdAd2luZHdhbGtlci1pby91bmljb3JuLW5leHQnO1xuaW1wb3J0IHsgTW9kYWwgfSBmcm9tICdib290c3RyYXAnO1xuaW1wb3J0IHsgTWQ1IH0gZnJvbSAndHMtbWQ1JztcbmltcG9ydCB7IG5leHRUaWNrLCB3YXRjaCwgcmVmLCBjb21wdXRlZCwgb25Nb3VudGVkLCB1c2VUZW1wbGF0ZVJlZiB9IGZyb20gJ3Z1ZSc7XG5pbXBvcnQgeyBDb21wb25lbnRFeHBvc2VkIH0gZnJvbSAndnVlLWNvbXBvbmVudC10eXBlLWhlbHBlcnMnO1xuaW1wb3J0IENhc2NhZGVTZWxlY3QgZnJvbSAnfnNob3Bnby9tb2R1bGVzL2NhcnQvY29tcG9uZW50cy9DYXNjYWRlU2VsZWN0LnZ1ZSc7XG5pbXBvcnQgeyBBZGRyZXNzLCBVc2VyIH0gZnJvbSAnfnNob3Bnby90eXBlcyc7XG5cbmNvbnN0IHByb3BzID0gZGVmaW5lUHJvcHM8e1xuICB0eXBlOiBzdHJpbmc7XG4gIHVzZXI6IFVzZXIgfCBudWxsO1xuICBzeW5jRGF0YT86IGFueTtcbiAgdGl0bGU6IHN0cmluZztcbiAgc3luY0xhYmVsPzogc3RyaW5nO1xufT4oKTtcblxuY29uc3QgZGVmYXVsdEFkZHJlc3MgPSB7XG4gIC8vIGFkZHJlc3NJZDogJycsXG4gIC8vIGxvY2F0aW9uSWQ6IDAsXG4gIC8vIGZpcnN0bmFtZTogJycsXG4gIC8vIGxhc3RuYW1lOiAnJyxcbiAgLy8gbmFtZTogJycsXG4gIC8vIGVtYWlsOiAnJyxcbiAgLy8gcGhvbmU6ICcnLFxuICAvLyBtb2JpbGU6ICcnLFxuICAvLyBjb21wYW55OiAnJyxcbiAgLy8gdmF0OiAnJyxcbiAgLy8gY291bnRyeTogJycsXG4gIC8vIHN0YXRlOiAnJyxcbiAgLy8gY2l0eTogJycsXG4gIC8vIHBvc3Rjb2RlOiAnJyxcbiAgLy8gYWRkcmVzczE6ICcnLFxuICAvLyBhZGRyZXNzMjogJycsXG4gIC8vIHNhdmU6IGZhbHNlLFxufTtcblxudHlwZSBBZGRyZXNzRm9ybURhdGEgPSBBZGRyZXNzICYge1xuICAvLyBXZSBzYXZlIGlkIGFzIGFkZHJlc3NJZCB0byBhdm9pZCBjb25mdXNpb24gb2Ygc2hpcHBpbmcgLyBwYXltZW50IGRhdGFcbiAgYWRkcmVzc0lkPzogc3RyaW5nIHwgbnVtYmVyO1xuICBsb2NhdGlvblBhdGg6IChudW1iZXIgfCBzdHJpbmcpW107XG4gIGZvcm1hdHRlZDogc3RyaW5nO1xufVxuXG5jb25zdCBlbWl0ID0gZGVmaW5lRW1pdHM8e1xuICAndmFsaWRhdGVkJzogW3Bhc3M6IGJvb2xlYW5dO1xufT4oKVxuXG5jb25zdCBtb2RlbFZhbHVlID0gZGVmaW5lTW9kZWw8QWRkcmVzc0Zvcm1EYXRhPih7XG4gIHJlcXVpcmVkOiB0cnVlLFxufSk7XG5cbi8vIFJlcGxhY2UgcmVhY3RpdmUgYHN0YXRlYCB3aXRoIGluZGl2aWR1YWwgcmVmc1xuY29uc3QgYWRkcmVzc0xvYWRpbmcgPSByZWYoZmFsc2UpO1xuY29uc3QgY3VycmVudFN0YXRlID0gcmVmKHByb3BzLnN5bmNEYXRhID09IG51bGwgPyAnaW5pdGlhbGl6aW5nJyA6ICdzeW5jJyk7XG5jb25zdCBsb2NhdGlvblBhdGggPSByZWY8KG51bWJlciB8IHN0cmluZylbXT4oW10pO1xuY29uc3QgY2FzY2FkZU9wdGlvbnMgPSB7XG4gIGFqYXhVcmw6IHJvdXRlKCdAYWRkcmVzc19hamF4L2xvY2F0aW9uT3B0aW9ucycpLFxuICBsYWJlbHM6IHVkYXRhKCdsb2NhdGlvbi5sYWJlbHMnKSB8fCBbXSxcbiAgcGxhY2Vob2xkZXI6IF9fKCd1bmljb3JuLnNlbGVjdC5wbGFjZWhvbGRlcicpLFxuICBvblNlbGVjdEluaXQoZTogQ3VzdG9tRXZlbnQpIHtcbiAgICBjb25zdCBzZWxlY3QgPSBlLmRldGFpbC5lbDtcblxuICAgIHVzZVRvbVNlbGVjdChzZWxlY3QpO1xuICB9XG59O1xuXG5tb2RlbFZhbHVlLnZhbHVlID0gT2JqZWN0LmFzc2lnbihcbiAge30sXG4gIGRlZmF1bHRBZGRyZXNzLFxuICB7XG4gICAgZmlyc3ROYW1lOiBwcm9wcy51c2VyPy5maXJzdG5hbWUgfHwgJycsXG4gICAgbGFzdE5hbWU6IHByb3BzLnVzZXI/Lmxhc3RuYW1lIHx8ICcnLFxuICAgIG5hbWU6IHByb3BzLnVzZXI/Lm5hbWUgfHwgJycsXG4gIH0sXG4gIG1vZGVsVmFsdWUudmFsdWVcbik7XG5jb25zdCBhZGRyZXNzZXMgPSByZWY8YW55W10+KFtdKTtcbmNvbnN0IGN1cnJlbnRBZGRyZXNzSGFzaCA9IHJlZignJyk7XG5jb25zdCBzeW5jID0gcmVmKHByb3BzLnN5bmNEYXRhICE9IG51bGwpO1xuY29uc3QgYWRkcmVzc1NlbGVjdGluZyA9IHJlZihmYWxzZSk7XG5cbmNvbnN0IGZvcm0gPSByZWYoKTtcbmNvbnN0IGxvY2F0aW9uU2VsZWN0b3IgPSByZWY8Q29tcG9uZW50RXhwb3NlZDx0eXBlb2YgQ2FzY2FkZVNlbGVjdD4+KCk7XG5jb25zdCBtb2RhbEVsZW1lbnQgPSB1c2VUZW1wbGF0ZVJlZjxIVE1MRGl2RWxlbWVudD4oJ21vZGFsJyk7XG5cbmlmICghbW9kZWxWYWx1ZS52YWx1ZSB8fCBPYmplY3Qua2V5cyhtb2RlbFZhbHVlLnZhbHVlKS5sZW5ndGggPT09IDApIHtcbiAgZmluZE15QWRkcmVzcygpLnRoZW4oKGFkZHJzKSA9PiB7XG4gICAgY29uc3QgZmlyc3RBZGRyZXNzID0gYWRkcnNbMF0gfHwgbnVsbDtcblxuICAgIGlmIChmaXJzdEFkZHJlc3MpIHtcbiAgICAgIG1vZGVsVmFsdWUudmFsdWUgPSBwcmVwYXJlQWRkcmVzc0RhdGEoZmlyc3RBZGRyZXNzKTtcbiAgICB9XG4gIH0pO1xufVxuXG5vbk1vdW50ZWQoYXN5bmMgKCkgPT4ge1xuICBpZiAoIXN5bmMudmFsdWUpIHtcbiAgICBjb25zdCBhZGRycyA9IGF3YWl0IGZpbmRNeUFkZHJlc3MoKTtcbiAgICBsZXQgYWRkcmVzcztcblxuICAgIGlmIChtb2RlbFZhbHVlLnZhbHVlLmlkKSB7XG4gICAgICBhZGRyZXNzID0gYWRkcnMuZmluZCgoYWRkcjogQWRkcmVzcykgPT4gU3RyaW5nKGFkZHIuaWQpID09PSBTdHJpbmcobW9kZWxWYWx1ZS52YWx1ZS5pZCkpO1xuICAgIH1cblxuICAgIGlmICghYWRkcmVzcykge1xuICAgICAgYWRkcmVzcyA9IGFkZHJzWzBdO1xuICAgIH1cblxuICAgIGlmIChhZGRyZXNzKSB7XG4gICAgICBzZXRBZGRyZXNzVG9EYXRhKGFkZHJlc3MpO1xuICAgIH1cblxuICAgIGN1cnJlbnRTdGF0ZS52YWx1ZSA9ICdzZWxlY3RlZCc7XG4gIH0gZWxzZSB7XG4gICAgY3VycmVudFN0YXRlLnZhbHVlID0gJ2Zvcm0nO1xuICB9XG5cbiAgdXBkYXRlTG9jYXRpb25MaXN0KCk7XG59KTtcblxuZnVuY3Rpb24gdmFsaWRhdGUoKSB7XG4gIGlmIChzeW5jLnZhbHVlKSB7XG4gICAgcmV0dXJuIHRydWU7XG4gIH1cblxuICBpZiAoZm9ybS52YWx1ZSkge1xuICAgIGxldCBwYXNzID0gdHJ1ZTtcbiAgICBjb25zdCBpbnB1dHMgPSBmb3JtLnZhbHVlLnF1ZXJ5U2VsZWN0b3JBbGwoJ2lucHV0LHRleHRhcmVhLHNlbGVjdCcpO1xuXG4gICAgZm9yIChjb25zdCBpbnB1dCBvZiBpbnB1dHMpIHtcbiAgICAgIGlmICghaW5wdXQuY2hlY2tWYWxpZGl0eSgpKSB7XG4gICAgICAgIHBhc3MgPSBwYXNzICYmIGZhbHNlO1xuXG4gICAgICAgIGlucHV0LnJlcG9ydFZhbGlkaXR5KCk7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgIH1cblxuICAgIGVtaXQoJ3ZhbGlkYXRlZCcsIHBhc3MpO1xuXG4gICAgcmV0dXJuIHBhc3M7XG4gIH1cblxuICByZXR1cm4gdHJ1ZTtcbn1cblxuLy8gd2F0Y2goKCkgPT4gc3RhdGUuZGF0YSwgKCkgPT4ge1xuLy8gICBlbWl0KCd1cGRhdGU6bW9kZWxWYWx1ZScsIHN0YXRlLmRhdGEpO1xuLy8gfSwgeyBkZWVwOiB0cnVlLCBpbW1lZGlhdGU6IHRydWUgfSk7XG5cbndhdGNoKCgpID0+IHByb3BzLnN5bmNEYXRhLCBhc3luYyAoKSA9PiB7XG4gIGlmIChzeW5jLnZhbHVlICYmIHByb3BzLnN5bmNEYXRhKSB7XG4gICAgc3luY0FkZHJlc3NGcm9tT3V0c2lkZSgpO1xuICB9XG59LCB7IGRlZXA6IHRydWUsIGltbWVkaWF0ZTogdHJ1ZSB9KTtcblxud2F0Y2goc3luYywgKHYpID0+IHtcbiAgaWYgKCF2KSB7XG4gICAgY3VycmVudFN0YXRlLnZhbHVlID0gJ2Zvcm0nO1xuICAgIG1vZGVsVmFsdWUudmFsdWUuaWQgPSB1bmRlZmluZWQ7XG4gICAgbW9kZWxWYWx1ZS52YWx1ZS5hZGRyZXNzSWQgPSB1bmRlZmluZWQ7XG4gIH0gZWxzZSBpZiAocHJvcHMuc3luY0RhdGEpIHtcbiAgfSBlbHNlIGlmIChwcm9wcy5zeW5jRGF0YSkge1xuICAgIGN1cnJlbnRTdGF0ZS52YWx1ZSA9ICdzeW5jJztcbiAgICBzeW5jQWRkcmVzc0Zyb21PdXRzaWRlKCk7XG4gIH1cbn0pO1xuXG5mdW5jdGlvbiBzeW5jQWRkcmVzc0Zyb21PdXRzaWRlKCkge1xuICBtb2RlbFZhbHVlLnZhbHVlID0gSlNPTi5wYXJzZShKU09OLnN0cmluZ2lmeShwcm9wcy5zeW5jRGF0YSB8fCB7fSkpO1xuXG4gIC8vIGF3YWl0IHVwZGF0ZUxvY2F0aW9uTGlzdCgpO1xufVxuXG5jb25zdCBzaG93U2F2ZUJ1dHRvbiA9IGNvbXB1dGVkKCgpID0+IHtcbiAgcmV0dXJuIGN1cnJlbnRBZGRyZXNzSGFzaC52YWx1ZSAhPT0gTWQ1Lmhhc2hTdHIoSlNPTi5zdHJpbmdpZnkobW9kZWxWYWx1ZS52YWx1ZSkpO1xufSk7XG5cbmZ1bmN0aW9uIGxvY2F0aW9uQ2hhbmdlZChlOiBDdXN0b21FdmVudCkge1xuICBpZiAoZS5kZXRhaWwpIHtcbiAgICBtb2RlbFZhbHVlLnZhbHVlLmxvY2F0aW9uSWQgPSBlLmRldGFpbC52YWx1ZTtcbiAgICBsb2NhdGlvblBhdGgudmFsdWUgPSBlLmRldGFpbC5wYXRoO1xuICB9XG59XG5cbmZ1bmN0aW9uIGJ1aWxkSW5wdXRJZChuYW1lOiBzdHJpbmcpIHtcbiAgcmV0dXJuIGBpbnB1dC0ke3Byb3BzLnR5cGV9LSR7bmFtZX1gO1xufVxuXG5mdW5jdGlvbiBidWlsZElucHV0TmFtZShuYW1lOiBzdHJpbmcpIHtcbiAgcmV0dXJuIGBjaGVja291dFske3Byb3BzLnR5cGV9X2RhdGFdWyR7bmFtZX1dYDtcbn1cblxuZnVuY3Rpb24gY3JlYXRlTmV3KCkge1xuICBjdXJyZW50U3RhdGUudmFsdWUgPSAnbmV3JztcbiAgbG9jYXRpb25QYXRoLnZhbHVlID0gW107XG4gIG1vZGVsVmFsdWUudmFsdWUgPSBPYmplY3QuYXNzaWduKHt9LCBkZWZhdWx0QWRkcmVzcykgYXMgQWRkcmVzc0Zvcm1EYXRhO1xufVxuXG5hc3luYyBmdW5jdGlvbiBmaW5kTXlBZGRyZXNzKCk6IFByb21pc2U8QWRkcmVzc0Zvcm1EYXRhW10+IHtcbiAgY29uc3QgeyBnZXQgfSA9IGF3YWl0IHVzZUh0dHBDbGllbnQoKTtcblxuICBjb25zdCByZXMgPSBhd2FpdCBnZXQoJ0BhZGRyZXNzX2FqYXgvbXlBZGRyZXNzZXMnKTtcblxuICByZXR1cm4gcmVzLmRhdGEuZGF0YTtcbn1cblxuLy8gU2VsZWN0XG5cbmFzeW5jIGZ1bmN0aW9uIG9wZW5BZGRyZXNzU2VsZWN0b3IoKSB7XG4gIGFkZHJlc3NMb2FkaW5nLnZhbHVlID0gdHJ1ZTtcblxuICBjb25zdCBtb2RhbEluc3RhbmNlID0gTW9kYWwuZ2V0T3JDcmVhdGVJbnN0YW5jZShtb2RhbEVsZW1lbnQudmFsdWUhKTtcblxuICBtb2RhbEluc3RhbmNlLnNob3coKTtcblxuICB0cnkge1xuICAgIGFkZHJlc3Nlcy52YWx1ZSA9IGF3YWl0IGZpbmRNeUFkZHJlc3MoKTtcbiAgfSBmaW5hbGx5IHtcbiAgICBhZGRyZXNzTG9hZGluZy52YWx1ZSA9IGZhbHNlO1xuICB9XG59XG5cbmFzeW5jIGZ1bmN0aW9uIHNlbGVjdEFkZHJlc3MoYWRkcmVzczogYW55KSB7XG4gIGFkZHJlc3NTZWxlY3RpbmcudmFsdWUgPSB0cnVlO1xuICBtb2RlbFZhbHVlLnZhbHVlID0gT2JqZWN0LmFzc2lnbihcbiAgICB7fSxcbiAgICBkZWZhdWx0QWRkcmVzcyxcbiAgICBhZGRyZXNzXG4gICk7XG5cbiAgYXdhaXQgc2V0QWRkcmVzc1RvRGF0YShhZGRyZXNzKTtcblxuICBjdXJyZW50QWRkcmVzc0hhc2gudmFsdWUgPSBNZDUuaGFzaFN0cihKU09OLnN0cmluZ2lmeShtb2RlbFZhbHVlLnZhbHVlKSk7XG5cbiAgYXdhaXQgdXBkYXRlTG9jYXRpb25MaXN0KCk7XG5cbiAgYWRkcmVzc1NlbGVjdGluZy52YWx1ZSA9IGZhbHNlO1xufVxuXG5mdW5jdGlvbiBwcmVwYXJlQWRkcmVzc0RhdGEoZGF0YTogQWRkcmVzc0Zvcm1EYXRhKTogQWRkcmVzc0Zvcm1EYXRhIHtcbiAgY29uc29sZS5sb2coZGF0YSk7XG4gIGRhdGEubG9jYXRpb25QYXRoID0gZGF0YS5sb2NhdGlvblBhdGgubWFwKCh2KSA9PiBTdHJpbmcodikpO1xuICBkYXRhLmFkZHJlc3NJZCA9IFN0cmluZyhkYXRhLmlkKTtcblxuICByZXR1cm4gZGF0YTtcbn1cblxuYXN5bmMgZnVuY3Rpb24gc2V0QWRkcmVzc1RvRGF0YShhZGRyZXNzOiBBZGRyZXNzRm9ybURhdGEpIHtcbiAgXG4gIGNvbnN0IGRhdGEgPSBPYmplY3QuYXNzaWduKFxuICAgIHt9LFxuICAgIGRlZmF1bHRBZGRyZXNzLFxuICAgIGFkZHJlc3NcbiAgKTtcblxuICBtb2RlbFZhbHVlLnZhbHVlID0gcHJlcGFyZUFkZHJlc3NEYXRhKGRhdGEpO1xuXG4gIE1vZGFsLmdldE9yQ3JlYXRlSW5zdGFuY2UobW9kYWxFbGVtZW50LnZhbHVlISkuaGlkZSgpO1xuXG4gIGF3YWl0IHVwZGF0ZUxvY2F0aW9uTGlzdCgpO1xufVxuXG5hc3luYyBmdW5jdGlvbiB1cGRhdGVMb2NhdGlvbkxpc3QoKSB7XG4gIGxvY2F0aW9uUGF0aC52YWx1ZSA9IG1vZGVsVmFsdWUudmFsdWUubG9jYXRpb25QYXRoIHx8IFtdO1xuICBhd2FpdCBuZXh0VGljaygpO1xuICBhd2FpdCBsb2NhdGlvblNlbGVjdG9yLnZhbHVlPy5wcmVwYXJlVmFsdWVzKCk7XG59XG5cbmRlZmluZUV4cG9zZSh7XG4gIHZhbGlkYXRlLFxufSk7XG48L3NjcmlwdD5cblxuPHRlbXBsYXRlPlxuICA8ZGl2IGNsYXNzPVwiY2FyZCBtYi00XCI+XG4gICAgPGRpdiBjbGFzcz1cImNhcmQtYm9keVwiPlxuICAgICAgPGRpdiBjbGFzcz1cImNhcmQtdGl0bGUgZC1mbGV4IGp1c3RpZnktY29udGVudC1iZXR3ZWVuXCI+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJkLWZsZXggYWxpZ24taXRlbXMtY2VudGVyIGdhcC0zXCI+XG4gICAgICAgICAgPGg0IGNsYXNzPVwibS0wXCI+XG4gICAgICAgICAgICB7eyB0aXRsZSB9fVxuICAgICAgICAgIDwvaDQ+XG5cbiAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1jaGVja1wiIHYtaWY9XCJzeW5jRGF0YVwiPlxuICAgICAgICAgICAgPGxhYmVsIDpmb3I9XCJgaW5wdXQtJHt0eXBlfS1zeW5jYFwiIGNsYXNzPVwiZm9ybS1jaGVjay1sYWJlbFwiPlxuICAgICAgICAgICAgICB7eyBzeW5jTGFiZWwgfHwgJGxhbmcoJ3Nob3Bnby5jYXJ0LmFkZHJlc3MuZm9ybS5zYW1lLndpdGguYnV5ZXInKSB9fVxuICAgICAgICAgICAgPC9sYWJlbD5cbiAgICAgICAgICAgIDxpbnB1dCB0eXBlPVwiY2hlY2tib3hcIiB2LW1vZGVsPVwic3luY1wiIDppZD1cImBpbnB1dC0ke3R5cGV9LXN5bmNgXCJcbiAgICAgICAgICAgICAgOm5hbWU9XCJidWlsZElucHV0TmFtZSgnc3luYycpXCJcbiAgICAgICAgICAgICAgY2xhc3M9XCJmb3JtLWNoZWNrLWlucHV0XCJcbiAgICAgICAgICAgICAgdmFsdWU9XCIxXCJcbiAgICAgICAgICAgIC8+XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgIDwvZGl2PlxuXG4gICAgICAgIDxkaXYgdi1pZj1cInVzZXIgJiYgIXN5bmNcIj5cbiAgICAgICAgICA8YnV0dG9uIHR5cGU9XCJidXR0b25cIlxuICAgICAgICAgICAgY2xhc3M9XCJidG4gYnRuLW91dGxpbmUtc3VjY2VzcyBidG4tc21cIlxuICAgICAgICAgICAgc3R5bGU9XCJtaW4td2lkdGg6IDEwMHB4XCJcbiAgICAgICAgICAgIEBjbGljaz1cImNyZWF0ZU5ld1wiXG4gICAgICAgICAgPlxuICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5jYXJ0LmFkZHJlc3MuZm9ybS5uZXcuYWRkcmVzcycpIH19XG4gICAgICAgICAgPC9idXR0b24+XG4gICAgICAgICAgPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCJcbiAgICAgICAgICAgIGNsYXNzPVwiYnRuIGJ0bi1vdXRsaW5lLXByaW1hcnkgYnRuLXNtXCJcbiAgICAgICAgICAgIHN0eWxlPVwibWluLXdpZHRoOiAxMDBweFwiXG4gICAgICAgICAgICBAY2xpY2s9XCJvcGVuQWRkcmVzc1NlbGVjdG9yXCJcbiAgICAgICAgICA+XG4gICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmNhcnQuYWRkcmVzcy5mb3JtLnNlbGVjdCcpIH19XG4gICAgICAgICAgPC9idXR0b24+XG4gICAgICAgIDwvZGl2PlxuICAgICAgPC9kaXY+XG5cbiAgICAgIDx0cmFuc2l0aW9uIG5hbWU9XCJmYWRlXCIgbW9kZT1cIm91dC1pblwiPlxuICAgICAgICA8ZGl2IHYtaWY9XCJjdXJyZW50U3RhdGUgPT09ICdpbml0aWFsaXppbmcnXCI+XG4gICAgICAgICAgPGRpdiBjbGFzcz1cInBsYWNlaG9sZGVyLWdsb3dcIj5cbiAgICAgICAgICAgIDxzcGFuIGNsYXNzPVwicGxhY2Vob2xkZXIgY29sLTdcIj48L3NwYW4+XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgIDwvZGl2PlxuICAgICAgICA8ZGl2IHYtZWxzZS1pZj1cIiFzeW5jICYmIG1vZGVsVmFsdWUuYWRkcmVzc0lkXCIgY2xhc3M9XCJtdC0zXCJcbiAgICAgICAgICBzdHlsZT1cImFuaW1hdGlvbi1kdXJhdGlvbjogLjNzXCI+XG4gICAgICAgICAge3sgbW9kZWxWYWx1ZS5mb3JtYXR0ZWQgfX1cbiAgICAgICAgPC9kaXY+XG4gICAgICAgIDxkaXYgdi1lbHNlLWlmPVwiIXN5bmMgJiYgIW1vZGVsVmFsdWUuYWRkcmVzc0lkXCIgY2xhc3M9XCJyb3cgbXQtM1wiIHN0eWxlPVwiYW5pbWF0aW9uLWR1cmF0aW9uOiAuM3NcIlxuICAgICAgICAgIHJlZj1cImZvcm1cIj5cbiAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLWxnLTVcIj5cbiAgICAgICAgICAgIDwhLS0gRmlyc3QgTmFtZSAtLT5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIHJvdyBtYi00XCI+XG4gICAgICAgICAgICAgIDxsYWJlbCA6Zm9yPVwiYnVpbGRJbnB1dElkKCdmaXJzdG5hbWUnKVwiIGNsYXNzPVwiZm9ybS1sYWJlbCBjb2wtM1wiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uYWRkcmVzcy5maWVsZC5maXJzdG5hbWUnKSB9fVxuICAgICAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLTlcIj5cbiAgICAgICAgICAgICAgICA8aW5wdXQgOmlkPVwiYnVpbGRJbnB1dElkKCdmaXJzdG5hbWUnKVwiIHR5cGU9XCJ0ZXh0XCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIlxuICAgICAgICAgICAgICAgICAgOm5hbWU9XCJidWlsZElucHV0TmFtZSgnZmlyc3RuYW1lJylcIlxuICAgICAgICAgICAgICAgICAgcmVxdWlyZWRcbiAgICAgICAgICAgICAgICAgIHYtbW9kZWw9XCJtb2RlbFZhbHVlLmZpcnN0bmFtZVwiIC8+XG4gICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgIDwhLS0gTGFzdCBOYW1lIC0tPlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXAgcm93IG1iLTRcIj5cbiAgICAgICAgICAgICAgPGxhYmVsIDpmb3I9XCJidWlsZElucHV0SWQoJ2xhc3RuYW1lJylcIiBjbGFzcz1cImZvcm0tbGFiZWwgY29sLTNcIj5cbiAgICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmFkZHJlc3MuZmllbGQubGFzdG5hbWUnKSB9fVxuICAgICAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLTlcIj5cbiAgICAgICAgICAgICAgICA8aW5wdXQgOmlkPVwiYnVpbGRJbnB1dElkKCdsYXN0bmFtZScpXCIgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiXG4gICAgICAgICAgICAgICAgICA6bmFtZT1cImJ1aWxkSW5wdXROYW1lKCdsYXN0bmFtZScpXCJcbiAgICAgICAgICAgICAgICAgIHJlcXVpcmVkXG4gICAgICAgICAgICAgICAgICB2LW1vZGVsPVwibW9kZWxWYWx1ZS5sYXN0bmFtZVwiIC8+XG4gICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgIDwhLS0gRW1haWwgLS0+XG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCByb3cgbWItNFwiPlxuICAgICAgICAgICAgICA8bGFiZWwgOmZvcj1cImJ1aWxkSW5wdXRJZCgnZW1haWwnKVwiIGNsYXNzPVwiZm9ybS1sYWJlbCBjb2wtM1wiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uYWRkcmVzcy5maWVsZC5lbWFpbCcpIH19XG4gICAgICAgICAgICAgIDwvbGFiZWw+XG4gICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjb2wtOVwiPlxuICAgICAgICAgICAgICAgIDxpbnB1dCA6aWQ9XCJidWlsZElucHV0SWQoJ2VtYWlsJylcIiB0eXBlPVwidGV4dFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCJcbiAgICAgICAgICAgICAgICAgIDpuYW1lPVwiYnVpbGRJbnB1dE5hbWUoJ2VtYWlsJylcIlxuICAgICAgICAgICAgICAgICAgcmVxdWlyZWRcbiAgICAgICAgICAgICAgICAgIHYtbW9kZWw9XCJtb2RlbFZhbHVlLmVtYWlsXCIgLz5cbiAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgICAgPCEtLSBQaG9uZSAtLT5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIHJvdyBtYi00XCI+XG4gICAgICAgICAgICAgIDxsYWJlbCA6Zm9yPVwiYnVpbGRJbnB1dElkKCdwaG9uZScpXCIgY2xhc3M9XCJmb3JtLWxhYmVsIGNvbC0zXCI+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5hZGRyZXNzLmZpZWxkLnBob25lJykgfX1cbiAgICAgICAgICAgICAgPC9sYWJlbD5cbiAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImNvbC05XCI+XG4gICAgICAgICAgICAgICAgPGlucHV0IDppZD1cImJ1aWxkSW5wdXRJZCgncGhvbmUnKVwiIHR5cGU9XCJ0ZXh0XCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIlxuICAgICAgICAgICAgICAgICAgOm5hbWU9XCJidWlsZElucHV0TmFtZSgncGhvbmUnKVwiXG4gICAgICAgICAgICAgICAgICB2LW1vZGVsPVwibW9kZWxWYWx1ZS5waG9uZVwiIC8+XG4gICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgIDwhLS0gTW9iaWxlIC0tPlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXAgcm93IG1iLTRcIj5cbiAgICAgICAgICAgICAgPGxhYmVsIDpmb3I9XCJidWlsZElucHV0SWQoJ21vYmlsZScpXCIgY2xhc3M9XCJmb3JtLWxhYmVsIGNvbC0zXCI+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5hZGRyZXNzLmZpZWxkLm1vYmlsZScpIH19XG4gICAgICAgICAgICAgIDwvbGFiZWw+XG4gICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjb2wtOVwiPlxuICAgICAgICAgICAgICAgIDxpbnB1dCA6aWQ9XCJidWlsZElucHV0SWQoJ21vYmlsZScpXCIgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiXG4gICAgICAgICAgICAgICAgICA6bmFtZT1cImJ1aWxkSW5wdXROYW1lKCdtb2JpbGUnKVwiXG4gICAgICAgICAgICAgICAgICByZXF1aXJlZFxuICAgICAgICAgICAgICAgICAgdi1tb2RlbD1cIm1vZGVsVmFsdWUubW9iaWxlXCIgLz5cbiAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgICAgPCEtLSBDb21wYW55IC0tPlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXAgcm93IG1iLTRcIj5cbiAgICAgICAgICAgICAgPGxhYmVsIDpmb3I9XCJidWlsZElucHV0SWQoJ2NvbXBhbnknKVwiIGNsYXNzPVwiZm9ybS1sYWJlbCBjb2wtM1wiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uYWRkcmVzcy5maWVsZC5jb21wYW55JykgfX1cbiAgICAgICAgICAgICAgPC9sYWJlbD5cbiAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImNvbC05XCI+XG4gICAgICAgICAgICAgICAgPGlucHV0IDppZD1cImJ1aWxkSW5wdXRJZCgnY29tcGFueScpXCIgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiXG4gICAgICAgICAgICAgICAgICA6bmFtZT1cImJ1aWxkSW5wdXROYW1lKCdjb21wYW55JylcIlxuICAgICAgICAgICAgICAgICAgdi1tb2RlbD1cIm1vZGVsVmFsdWUuY29tcGFueVwiIC8+XG4gICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgIDwhLS0gVkFUIC0tPlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXAgcm93IG1iLTRcIj5cbiAgICAgICAgICAgICAgPGxhYmVsIDpmb3I9XCJidWlsZElucHV0SWQoJ3ZhdCcpXCIgY2xhc3M9XCJmb3JtLWxhYmVsIGNvbC0zXCI+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5hZGRyZXNzLmZpZWxkLnZhdCcpIH19XG4gICAgICAgICAgICAgIDwvbGFiZWw+XG4gICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjb2wtOVwiPlxuICAgICAgICAgICAgICAgIDxpbnB1dCA6aWQ9XCJidWlsZElucHV0SWQoJ3ZhdCcpXCIgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiXG4gICAgICAgICAgICAgICAgICA6bmFtZT1cImJ1aWxkSW5wdXROYW1lKCd2YXQnKVwiXG4gICAgICAgICAgICAgICAgICB2LW1vZGVsPVwibW9kZWxWYWx1ZS52YXRcIiAvPlxuICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgPGRpdiBjbGFzcz1cImNvbC1sZy03IG1iLTQgbWItbGctMFwiPlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXAgbWItNFwiPlxuICAgICAgICAgICAgICA8bGFiZWwgOmZvcj1cImJ1aWxkSW5wdXRJZCgnY291bnRyeScpXCIgY2xhc3M9XCJmb3JtLWxhYmVsXCI+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5hZGRyZXNzLmZpZWxkLmNvdW50cnknKSB9fVxuICAgICAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgICAgICA8Q2FzY2FkZVNlbGVjdCA6b3B0aW9ucz1cImNhc2NhZGVPcHRpb25zXCJcbiAgICAgICAgICAgICAgICB2LW1vZGVsPVwibG9jYXRpb25QYXRoXCJcbiAgICAgICAgICAgICAgICBAY2hhbmdlPVwibG9jYXRpb25DaGFuZ2VkXCJcbiAgICAgICAgICAgICAgICA6bmFtZT1cImJ1aWxkSW5wdXROYW1lKCdsb2NhdGlvbl9pZCcpXCJcbiAgICAgICAgICAgICAgICByZWY9XCJsb2NhdGlvblNlbGVjdG9yXCJcbiAgICAgICAgICAgICAgPlxuXG4gICAgICAgICAgICAgIDwvQ2FzY2FkZVNlbGVjdD5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXAgcm93IG1iLTRcIj5cbiAgICAgICAgICAgICAgPGxhYmVsIDpmb3I9XCJidWlsZElucHV0SWQoJ3Bvc3Rjb2RlJylcIiBjbGFzcz1cImZvcm0tbGFiZWwgY29sLTNcIj5cbiAgICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmFkZHJlc3MuZmllbGQucG9zdGNvZGUnKSB9fVxuICAgICAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLTlcIj5cbiAgICAgICAgICAgICAgICA8aW5wdXQgOmlkPVwiYnVpbGRJbnB1dElkKCdwb3N0Y29kZScpXCIgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiXG4gICAgICAgICAgICAgICAgICA6bmFtZT1cImJ1aWxkSW5wdXROYW1lKCdwb3N0Y29kZScpXCJcbiAgICAgICAgICAgICAgICAgIHYtbW9kZWw9XCJtb2RlbFZhbHVlLnBvc3Rjb2RlXCIgbWF4bGVuZ3RoPVwiMTBcIiAvPlxuICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXAgcm93IG1iLTRcIj5cbiAgICAgICAgICAgICAgPGxhYmVsIDpmb3I9XCJidWlsZElucHV0SWQoJ2FkZHJlc3MxJylcIiBjbGFzcz1cImZvcm0tbGFiZWwgY29sLTNcIj5cbiAgICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmFkZHJlc3MuZmllbGQuYWRkcmVzczEnKSB9fVxuICAgICAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLTlcIj5cbiAgICAgICAgICAgICAgICA8aW5wdXQgOmlkPVwiYnVpbGRJbnB1dElkKCdhZGRyZXNzMScpXCIgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiXG4gICAgICAgICAgICAgICAgICA6bmFtZT1cImJ1aWxkSW5wdXROYW1lKCdhZGRyZXNzMScpXCJcbiAgICAgICAgICAgICAgICAgIHJlcXVpcmVkXG4gICAgICAgICAgICAgICAgICB2LW1vZGVsPVwibW9kZWxWYWx1ZS5hZGRyZXNzMVwiIC8+XG4gICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCByb3cgbWItNFwiPlxuICAgICAgICAgICAgICA8bGFiZWwgOmZvcj1cImJ1aWxkSW5wdXRJZCgnYWRkcmVzczInKVwiIGNsYXNzPVwiZm9ybS1sYWJlbCBjb2wtM1wiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uYWRkcmVzcy5maWVsZC5hZGRyZXNzMicpIH19XG4gICAgICAgICAgICAgIDwvbGFiZWw+XG4gICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjb2wtOVwiPlxuICAgICAgICAgICAgICAgIDxpbnB1dCA6aWQ9XCJidWlsZElucHV0SWQoJ2FkZHJlc3MyJylcIiB0eXBlPVwidGV4dFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCJcbiAgICAgICAgICAgICAgICAgIDpuYW1lPVwiYnVpbGRJbnB1dE5hbWUoJ2FkZHJlc3MyJylcIlxuICAgICAgICAgICAgICAgICAgdi1tb2RlbD1cIm1vZGVsVmFsdWUuYWRkcmVzczJcIiAvPlxuICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPGRpdiB2LWlmPVwic2hvd1NhdmVCdXR0b25cIiBjbGFzcz1cImZvcm0tZ3JvdXAgcm93IG1iLTRcIj5cbiAgICAgICAgICAgICAgPGxhYmVsIDpmb3I9XCJidWlsZElucHV0SWQoJ3NhdmUnKVwiIGNsYXNzPVwiZm9ybS1sYWJlbCBjb2wtM1wiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uY2FydC5hZGRyZXNzLmZvcm0uc2F2ZS5mb3IubmV4dCcpIH19XG4gICAgICAgICAgICAgIDwvbGFiZWw+XG4gICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjb2wtOVwiPlxuICAgICAgICAgICAgICAgIDxpbnB1dCA6aWQ9XCJidWlsZElucHV0SWQoJ3NhdmUnKVwiIHR5cGU9XCJjaGVja2JveFwiIGNsYXNzPVwiZm9ybS1jaGVjay1pbnB1dFwiXG4gICAgICAgICAgICAgICAgICA6bmFtZT1cImJ1aWxkSW5wdXROYW1lKCdzYXZlJylcIlxuICAgICAgICAgICAgICAgICAgOnZhbHVlPVwiMVwiXG4gICAgICAgICAgICAgICAgICB2LW1vZGVsPVwibW9kZWxWYWx1ZS5zYXZlXCIgLz5cbiAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICA8L2Rpdj5cbiAgICAgIDwvdHJhbnNpdGlvbj5cblxuICAgICAgPGRpdiBjbGFzcz1cImQtbm9uZVwiPlxuICAgICAgICA8aW5wdXQgOmlkPVwiYnVpbGRJbnB1dElkKCdhZGRyZXNzSWQnKVwiIHR5cGU9XCJoaWRkZW5cIlxuICAgICAgICAgIDpuYW1lPVwiYnVpbGRJbnB1dE5hbWUoJ2FkZHJlc3NJZCcpXCJcbiAgICAgICAgICB2LW1vZGVsPVwibW9kZWxWYWx1ZS5hZGRyZXNzSWRcIlxuICAgICAgICAvPlxuICAgICAgPC9kaXY+XG4gICAgPC9kaXY+XG5cbiAgICA8IS0tIE1vZGFsIC0tPlxuICAgIDxkaXYgcmVmPVwibW9kYWxcIiBjbGFzcz1cIm1vZGFsIGZhZGVcIiA6aWQ9XCJgJHt0eXBlfS1hZGRyZXNzLW1vZGFsYFwiIHRhYmluZGV4PVwiLTFcIiByb2xlPVwiZGlhbG9nXCIgYXJpYS1sYWJlbGxlZGJ5PVwiYWRkcmVzcy1tb2RhbC1sYWJlbFwiXG4gICAgICBhcmlhLWhpZGRlbj1cInRydWVcIj5cbiAgICAgIDxkaXYgY2xhc3M9XCJtb2RhbC1kaWFsb2dcIiByb2xlPVwiZG9jdW1lbnRcIj5cbiAgICAgICAgPGRpdiBjbGFzcz1cIm1vZGFsLWNvbnRlbnRcIj5cbiAgICAgICAgICA8ZGl2IGNsYXNzPVwibW9kYWwtaGVhZGVyXCI+XG4gICAgICAgICAgICA8aDQgY2xhc3M9XCJtb2RhbC10aXRsZVwiIGlkPVwiYWRkcmVzcy1tb2RhbC1sYWJlbFwiPlxuICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmNhcnQuYWRkcmVzcy5mb3JtLm1vZGFsLnRpdGxlJykgfX1cbiAgICAgICAgICAgIDwvaDQ+XG4gICAgICAgICAgICA8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImNsb3NlIGJ0bi1jbG9zZVwiIGRhdGEtYnMtZGlzbWlzcz1cIm1vZGFsXCIgZGF0YS1kaXNtaXNzPVwibW9kYWxcIlxuICAgICAgICAgICAgICBhcmlhLWxhYmVsPVwiQ2xvc2VcIj5cbiAgICAgICAgICAgICAgPHNwYW4gYXJpYS1oaWRkZW49XCJ0cnVlXCIgY2xhc3M9XCJ2aXN1YWxseS1oaWRkZW5cIj4mdGltZXM7PC9zcGFuPlxuICAgICAgICAgICAgPC9idXR0b24+XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgPGRpdiBjbGFzcz1cIm1vZGFsLWJvZHlcIj5cbiAgICAgICAgICAgIDxkaXYgdi1pZj1cIiFhZGRyZXNzTG9hZGluZyAmJiBhZGRyZXNzZXMubGVuZ3RoXCIgY2xhc3M9XCJsaXN0LWdyb3VwIGxpc3QtZ3JvdXAtZmx1c2hcIj5cbiAgICAgICAgICAgICAgPGEgaHJlZj1cImphdmFzY3JpcHQ6Ly9cIiBjbGFzcz1cImxpc3QtZ3JvdXAtaXRlbSBkLWZsZXggZ2FwLTIganVzdGlmeS1jb250ZW50LWJldHdlZW5cIlxuICAgICAgICAgICAgICAgIHYtZm9yPVwiYWRkcmVzcyBvZiBhZGRyZXNzZXNcIlxuICAgICAgICAgICAgICAgIDprZXk9XCJhZGRyZXNzXCJcbiAgICAgICAgICAgICAgICBAY2xpY2s9XCJzZWxlY3RBZGRyZXNzKGFkZHJlc3MpXCJcbiAgICAgICAgICAgICAgPlxuICAgICAgICAgICAgICAgIDxkaXY+XG4gICAgICAgICAgICAgICAgICB7eyBhZGRyZXNzLmZvcm1hdHRlZCB9fVxuICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgIDxkaXY+XG4gICAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImJ0biBidG4tb3V0bGluZS1zZWNvbmRhcnkgYnRuLXNtIHRleHQtbm93cmFwXCI+XG4gICAgICAgICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5jYXJ0LmFkZHJlc3MuZm9ybS5idXR0b24uc2VsZWN0JykgfX1cbiAgICAgICAgICAgICAgICAgIDwvc3Bhbj5cbiAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgPC9hPlxuICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICA8ZGl2IHYtZWxzZSBjbGFzcz1cImNhcmQgYmctbGlnaHQgdGV4dC1jZW50ZXIgcHktNVwiPlxuICAgICAgICAgICAgICA8dGVtcGxhdGUgdi1pZj1cImFkZHJlc3NMb2FkaW5nXCI+XG4gICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJzcGlubmVyIHNwaW5uZXItYm9yZGVyIG14LWF1dG9cIj48L3NwYW4+XG4gICAgICAgICAgICAgIDwvdGVtcGxhdGU+XG4gICAgICAgICAgICAgIDx0ZW1wbGF0ZSB2LWVsc2U+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5jYXJ0LmFkZHJlc3MuZm9ybS5uby5hZGRyZXNzZXMnKSB9fVxuICAgICAgICAgICAgICA8L3RlbXBsYXRlPlxuICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgIDwvZGl2PlxuICAgICAgPC9kaXY+XG4gICAgPC9kaXY+XG4gIDwvZGl2PlxuPC90ZW1wbGF0ZT5cblxuPHN0eWxlIHNjb3BlZD5cblxuPC9zdHlsZT5cbiIsIjxzY3JpcHQgc2V0dXAgbGFuZz1cInRzXCI+XG5pbXBvcnQgeyBDYXJ0SXRlbSB9IGZyb20gJ35zaG9wZ28vdHlwZXMnO1xuXG5jb25zdCBwcm9wcyA9IGRlZmluZVByb3BzPHtcbiAgaXRlbTogQ2FydEl0ZW07XG4gIGhhc0NoZWNrYm94OiBib29sZWFuO1xufT4oKTtcblxuY29uc3QgZW1pdHMgPSBkZWZpbmVFbWl0czx7XG4gICdyZW1vdmUtaXRlbSc6IFtdO1xuICAndXBkYXRlLXF1YW50aXRpZXMnOiBbXTtcbiAgJ2NoYW5nZS1pdGVtLXF1YW50aXR5JzogW2RlbHRhOiBudW1iZXJdO1xuICAndXBkYXRlLWNoZWNrcyc6IFtdO1xufT4oKTtcblxuZnVuY3Rpb24gcmVtb3ZlSXRlbSgpIHtcbiAgZW1pdHMoJ3JlbW92ZS1pdGVtJyk7XG59XG5cbmZ1bmN0aW9uIHVwZGF0ZVF1YW50aXRpZXMoKSB7XG4gIGVtaXRzKCd1cGRhdGUtcXVhbnRpdGllcycpO1xufVxuXG5mdW5jdGlvbiBjaGFuZ2VJdGVtUXVhbnRpdHkoZGVsdGE6IG51bWJlcikge1xuICBlbWl0cygnY2hhbmdlLWl0ZW0tcXVhbnRpdHknLCBkZWx0YSk7XG59XG5cbmZ1bmN0aW9uIHVwZGF0ZUNoZWNrcygpIHtcbiAgZW1pdHMoJ3VwZGF0ZS1jaGVja3MnKTtcbn1cbjwvc2NyaXB0PlxuXG48dGVtcGxhdGU+XG4gIDxkaXYgY2xhc3M9XCJjLWNhcnQtaXRlbSBjYXJkIG1iLTNcIlxuICAgIDpkYXRhLXByb2R1Y3QtaWQ9XCJpdGVtLnByb2R1Y3QuaWRcIlxuICAgIDpkYXRhLXZhcmlhbnQtaWQ9XCJpdGVtLnZhcmlhbnQuaWRcIlxuICA+XG4gICAgPGRpdiBjbGFzcz1cImNhcmQtYm9keSBkLWdyaWQgZC1sZy1mbGV4IGdhcC0zXCI+XG4gICAgICA8ZGl2IGNsYXNzPVwiZC1mbGV4IGdhcC0zIG1lLWF1dG9cIj5cbiAgICAgICAgPCEtLSBDaGVja2JveCAtLT5cbiAgICAgICAgPGRpdiB2LWlmPVwiaGFzQ2hlY2tib3hcIiBjbGFzcz1cImMtY2FydC1pdGVtX19jaGVja2JveFwiPlxuICAgICAgICAgIDxpbnB1dCB0eXBlPVwiY2hlY2tib3hcIiBjbGFzcz1cImZvcm0tY2hlY2staW5wdXRcIlxuICAgICAgICAgICAgdi1tb2RlbD1cIml0ZW0ub3B0aW9ucy5jaGVja2VkXCJcbiAgICAgICAgICAgIEBjaGFuZ2U9XCJ1cGRhdGVDaGVja3NcIlxuICAgICAgICAgIC8+XG4gICAgICAgIDwvZGl2PlxuXG4gICAgICAgIDwhLS0gQ292ZXIgLS0+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJjLWNhcnQtaXRlbV9faW1hZ2VcIj5cbiAgICAgICAgICA8ZGl2IHN0eWxlPVwid2lkdGg6IDc1cHhcIiBjbGFzcz1cInJhdGlvIHJhdGlvLTF4MVwiPlxuICAgICAgICAgICAgPGltZyBjbGFzcz1cIm9iamVjdC1maXQtY292ZXJcIiA6c3JjPVwiaXRlbS5jb3ZlclwiIDphbHQ9XCJpdGVtLnByb2R1Y3QudGl0bGVcIlxuICAgICAgICAgICAgICBzdHlsZT1cIlwiPlxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICA8L2Rpdj5cblxuICAgICAgICA8IS0tIENvbnRlbnQgLS0+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJjLWNhcnQtaXRlbV9fY29udGVudFwiPlxuICAgICAgICAgIDxoNT5cbiAgICAgICAgICAgIDxhIDpocmVmPVwiaXRlbS5saW5rXCIgdGFyZ2V0PVwiX2JsYW5rXCI+XG4gICAgICAgICAgICAgIHt7IGl0ZW0ucHJvZHVjdC50aXRsZSB9fVxuICAgICAgICAgICAgPC9hPlxuICAgICAgICAgIDwvaDU+XG4gICAgICAgICAgPGRpdiB2LWlmPVwiIWl0ZW0udmFyaWFudC5wcmltYXJ5XCIgY2xhc3M9XCJmcy02IHRleHQtbXV0ZWRcIj5cbiAgICAgICAgICAgIHt7IGl0ZW0udmFyaWFudC50aXRsZSB9fVxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDxkaXYgY2xhc3M9XCJ0ZXh0LW11dGVkIHNtYWxsXCI+XG4gICAgICAgICAgICB7eyBpdGVtLnByb2R1Y3QubW9kZWwgfX1cbiAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgPGRpdiB2LWlmPVwiaXRlbS5vdXRPZlN0b2NrXCI+XG4gICAgICAgICAgPHNwYW5cbiAgICAgICAgICAgIGNsYXNzPVwiYmFkZ2UgYmctZGFuZ2VyXCI+XG4gICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28ubWVzc2FnZS5vdXQub2Yuc3RvY2snKSB9fVxuICAgICAgICAgIDwvc3Bhbj5cbiAgICAgICAgPC9kaXY+XG4gICAgICA8L2Rpdj5cblxuICAgICAgPCEtLSBRdWFudGl0eSAtLT5cbiAgICAgIDxkaXYgY2xhc3M9XCJjLWNhcnQtaXRlbV9fcXVhbnRpdHkgZC1mbGV4IGdhcC0yXCI+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJcIj5cbiAgICAgICAgICA8ZGl2IGNsYXNzPVwiaW5wdXQtZ3JvdXAgZmxleC1ub3dyYXBcIj5cbiAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1zZWNvbmRhcnkgYnRuLXNtXCJcbiAgICAgICAgICAgICAgQGNsaWNrPVwiY2hhbmdlSXRlbVF1YW50aXR5KC0xKVwiPlxuICAgICAgICAgICAgICA8aSBjbGFzcz1cImZhIGZhLW1pbnVzXCI+PC9pPlxuICAgICAgICAgICAgPC9idXR0b24+XG4gICAgICAgICAgICA8aW5wdXQgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbCBmb3JtLWNvbnRyb2wtc21cIlxuICAgICAgICAgICAgICB2LW1vZGVsLm51bWJlcj1cIml0ZW0ucXVhbnRpdHlcIlxuICAgICAgICAgICAgICBAY2hhbmdlPVwidXBkYXRlUXVhbnRpdGllc1wiXG4gICAgICAgICAgICAgIHN0eWxlPVwid2lkdGg6IDc1cHhcIlxuICAgICAgICAgICAgLz5cbiAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1zZWNvbmRhcnkgYnRuLXNtXCJcbiAgICAgICAgICAgICAgQGNsaWNrPVwiY2hhbmdlSXRlbVF1YW50aXR5KCsxKVwiPlxuICAgICAgICAgICAgICA8aSBjbGFzcz1cImZhIGZhLXBsdXNcIj48L2k+XG4gICAgICAgICAgICA8L2J1dHRvbj5cbiAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgPC9kaXY+XG4gICAgICA8L2Rpdj5cblxuICAgICAgPGRpdiBjbGFzcz1cImQtZmxleCBnYXAtM1wiPlxuICAgICAgICA8IS0tIEl0ZW0gVG90YWwgLS0+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJjLWNhcnQtaXRlbV9fcHJpY2UgdGV4dC1lbmQgdGV4dC1ub3dyYXAgXCJcbiAgICAgICAgICBzdHlsZT1cIm1pbi13aWR0aDogMTM1cHhcIj5cblxuICAgICAgICAgIDxkaXYgdi1pZj1cIml0ZW0ucHJpY2VTZXQuYmFzZV90b3RhbC5wcmljZSAhPT0gaXRlbS5wcmljZVNldC5maW5hbF90b3RhbC5wcmljZVwiXG4gICAgICAgICAgICBjbGFzcz1cInNtYWxsIHRleHQtbXV0ZWRcIj5cbiAgICAgICAgICAgIDxkZWw+e3sgJGZvcm1hdFByaWNlKGl0ZW0ucHJpY2VTZXQuYmFzZV90b3RhbC5wcmljZSkgfX08L2RlbD5cbiAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgIDxkaXYgY2xhc3M9XCJmcy01XCI+XG4gICAgICAgICAgICB7eyAkZm9ybWF0UHJpY2UoaXRlbS5wcmljZVNldC5maW5hbF90b3RhbC5wcmljZSwgeyBjb2RlOiB0cnVlIH0pIH19XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgIDwvZGl2PlxuXG4gICAgICAgIDxkaXYgY2xhc3M9XCJjLWNhcnQtaXRlbV9fYWN0aW9ucyBtcy1hdXRvXCI+XG4gICAgICAgICAgPCEtLSBSZW1vdmUgLS0+XG4gICAgICAgICAgPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJidG4gYnRuLWxpbmsgbGluay1zZWNvbmRhcnkgYnRuLXNtXCJcbiAgICAgICAgICAgIEBjbGljaz1cInJlbW92ZUl0ZW1cIj5cbiAgICAgICAgICAgIDxpIGNsYXNzPVwiZmEgZmEtdHJhc2hcIj48L2k+XG4gICAgICAgICAgPC9idXR0b24+XG4gICAgICAgIDwvZGl2PlxuICAgICAgPC9kaXY+XG4gICAgPC9kaXY+XG5cbiAgICA8IS0tIEF0dGFjaG1lbnRzIC0tPlxuICAgIDxkaXYgdi1pZj1cIml0ZW0uYXR0YWNobWVudHMubGVuZ3RoID4gMFwiIGNsYXNzPVwiY2FyZC1mb290ZXIgcHktNCBweC0zIHB4LWxnLTVcIj5cbiAgICAgIDxoNj57eyAkbGFuZygnc2hvcGdvLmNhcnQudGl0bGUuYXR0YWNobWVudHMnKSB9fTwvaDY+XG5cbiAgICAgIDxkaXYgdi1mb3I9XCJhdHRhY2htZW50IG9mIGl0ZW0uYXR0YWNobWVudHNcIlxuICAgICAgICBjbGFzcz1cImMtYXR0YWNobWVudCB3LTEwMCBkLWdyaWQgZC1sZy1mbGV4IGdhcC0zIGFsaWduLWl0ZW1zLWNlbnRlciBweS0yIGJvcmRlci1ib3R0b21cIlxuICAgICAgICA6ZGF0YS1wcm9kdWN0LWlkPVwiYXR0YWNobWVudC5wcm9kdWN0LmlkXCJcbiAgICAgICAgOmRhdGEtdmFyaWFudC1pZD1cImF0dGFjaG1lbnQudmFyaWFudC5pZFwiXG4gICAgICA+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJkLWZsZXggZ2FwLTMgZmxleC1ncm93LTEgYWxpZ24taXRlbXMtY2VudGVyXCI+XG5cbiAgICAgICAgICA8IS0tIENvdmVyIC0tPlxuICAgICAgICAgIDxkaXYgY2xhc3M9XCJjLWF0dGFjaG1lbnRfX2ltYWdlXCI+XG4gICAgICAgICAgICA8ZGl2IHN0eWxlPVwid2lkdGg6IDQ1cHhcIiBjbGFzcz1cInJhdGlvIHJhdGlvLTF4MVwiPlxuICAgICAgICAgICAgICA8aW1nIGNsYXNzPVwib2JqZWN0LWZpdC1jb3ZlclwiXG4gICAgICAgICAgICAgICAgOnNyYz1cImF0dGFjaG1lbnQuY292ZXJcIiA6YWx0PVwiYXR0YWNobWVudC5wcm9kdWN0LnRpdGxlXCJcbiAgICAgICAgICAgICAgICBzdHlsZT1cIlwiPlxuICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICA8IS0tIENvbnRlbnQgLS0+XG4gICAgICAgICAgPGRpdiBjbGFzcz1cImMtYXR0YWNobWVudF9fY29udGVudFwiPlxuICAgICAgICAgICAgPGg1IGNsYXNzPVwiZnMtNiBtYi0wXCI+e3sgYXR0YWNobWVudC5wcm9kdWN0LnRpdGxlIH19PC9oNT5cbiAgICAgICAgICAgIDxkaXYgdi1pZj1cIiFhdHRhY2htZW50LnZhcmlhbnQucHJpbWFyeVwiIGNsYXNzPVwidGV4dC1tdXRlZCBzbWFsbFwiPlxuICAgICAgICAgICAgICB7eyBhdHRhY2htZW50LnZhcmlhbnQudGl0bGUgfX1cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgPHNwYW4gdi1pZj1cImF0dGFjaG1lbnQub3V0T2ZTdG9ja1wiXG4gICAgICAgICAgICBjbGFzcz1cImJhZGdlIGJnLWRhbmdlclwiPlxuICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5tZXNzYWdlLm91dC5vZi5zdG9jaycpIH19XG4gICAgICAgICAgPC9zcGFuPlxuXG4gICAgICAgICAgPGRpdiBjbGFzcz1cImMtYXR0YWNobWVudF9fcXVhbnRpdHkgbXMtYXV0b1wiPlxuICAgICAgICAgICAgeHt7IGF0dGFjaG1lbnQucXVhbnRpdHkgKiBpdGVtLnF1YW50aXR5IH19XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgIDwvZGl2PlxuXG4gICAgICAgIDxkaXYgY2xhc3M9XCJkLWZsZXggZ2FwLTMgbXMtYXV0byBtcy1sZy0wXCI+XG4gICAgICAgICAgPGRpdiBjbGFzcz1cImMtYXR0YWNobWVudF9fdG90YWwgZC1mbGV4IGp1c3RpZnktY29udGVudC1lbmQgZ2FwLTNcIlxuICAgICAgICAgICAgc3R5bGU9XCJ3aWR0aDogMjUwcHhcIj5cbiAgICAgICAgICAgIDwhLS0gSXRlbSBUb3RhbCAtLT5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjLWNhcnQtaXRlbV9fcHJpY2UgZC1mbGV4IGFsaWduLWl0ZW1zLWNlbnRlciBnYXAtMiB0ZXh0LWVuZCB0ZXh0LW5vd3JhcFwiXG4gICAgICAgICAgICAgIHN0eWxlPVwibWluLXdpZHRoOiAxMzVweFwiPlxuICAgICAgICAgICAgICA8ZGl2IHYtaWY9XCJhdHRhY2htZW50LnByaWNlU2V0LmJhc2VfdG90YWwucHJpY2UgIT09IGF0dGFjaG1lbnQucHJpY2VTZXQuZmluYWxfdG90YWwucHJpY2VcIlxuICAgICAgICAgICAgICAgIGNsYXNzPVwic21hbGwgdGV4dC1tdXRlZFwiPlxuICAgICAgICAgICAgICAgIDxkZWw+e3sgJGZvcm1hdFByaWNlKGF0dGFjaG1lbnQucHJpY2VTZXQuYmFzZV90b3RhbC5wcmljZSkgfX08L2RlbD5cbiAgICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIlwiPlxuICAgICAgICAgICAgICAgIHt7ICRmb3JtYXRQcmljZShhdHRhY2htZW50LnByaWNlU2V0LmZpbmFsX3RvdGFsLnByaWNlKSB9fVxuICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICA8L2Rpdj5cbiAgICAgIDwvZGl2PlxuXG4gICAgICA8IS0tIFByb2R1Y3QgVG90YWwgLS0+XG4gICAgICA8ZGl2IGNsYXNzPVwibXQtMyB0ZXh0LWVuZCBmcy01XCI+XG4gICAgICAgIDxzdHJvbmc+e3sgJGxhbmcoJ3Nob3Bnby5jYXJ0LmxhYmVsLmF0dGFjaGVkLnByb2R1Y3QudG90YWwnKSB9fTwvc3Ryb25nPlxuXG4gICAgICAgIDxzcGFuIGNsYXNzPVwiXCI+XG4gICAgICAgICAge3sgJGZvcm1hdFByaWNlKGl0ZW0ucHJpY2VTZXQuYXR0YWNoZWRfZmluYWxfdG90YWwucHJpY2UsIHsgY29kZTogdHJ1ZSB9KSB9fVxuICAgICAgICA8L3NwYW4+XG4gICAgICA8L2Rpdj5cbiAgICA8L2Rpdj5cbiAgPC9kaXY+XG48L3RlbXBsYXRlPlxuXG48c3R5bGUgc2NvcGVkPlxuXG48L3N0eWxlPlxuIiwiPHNjcmlwdCBzZXR1cCBsYW5nPVwidHNcIj5cbmltcG9ydCB7IGRhdGEgYXMgdWRhdGEsIHNsaWRlRG93biwgc2xpZGVVcCwgdWlkIH0gZnJvbSAnQHdpbmR3YWxrZXItaW8vdW5pY29ybi1uZXh0JztcbmltcG9ydCB7IHdhdGNoLCByZWYgfSBmcm9tICd2dWUnO1xuaW1wb3J0IHsgUGF5bWVudCB9IGZyb20gJ35zaG9wZ28vdHlwZXMnO1xuXG5jb25zdCBwcm9wcyA9IGRlZmluZVByb3BzPHtcbiAgcGF5bWVudDogUGF5bWVudDtcbiAgaTogbnVtYmVyO1xuICBzZWxlY3RlZDogYm9vbGVhbjtcbn0+KCk7XG5cbmNvbnN0IGVtaXQgPSBkZWZpbmVFbWl0czx7XG4gIChlOiAnc2VsZWN0ZWQnKTogdm9pZDtcbn0+KCk7XG5cbi8vIHNwbGl0IHN0YXRlIGludG8gaW5kaXZpZHVhbCByZWZzXG5jb25zdCB1aWRSZWYgPSByZWYodWlkKCkpO1xuY29uc3QgZGF0YSA9IHJlZih7fSk7XG5jb25zdCBzZWxlY3RlZFJlZiA9IHJlZihwcm9wcy5zZWxlY3RlZCk7XG5jb25zdCBpbWFnZURlZmF1bHQgPSByZWYodWRhdGEoJ2ltYWdlLmRlZmF1bHQnKSk7XG5cbndhdGNoKCgpID0+IHByb3BzLnNlbGVjdGVkLCAoKSA9PiB7XG4gIHNlbGVjdGVkUmVmLnZhbHVlID0gcHJvcHMuc2VsZWN0ZWQ7XG5cbiAgc2V0VGltZW91dCgoKSA9PiB7XG4gICAgaWYgKHNlbGVjdGVkUmVmLnZhbHVlKSB7XG4gICAgICBzbGlkZURvd24ob3B0aW9uTGF5b3V0LnZhbHVlISk7XG4gICAgfSBlbHNlIHtcbiAgICAgIHNsaWRlVXAob3B0aW9uTGF5b3V0LnZhbHVlISk7XG4gICAgfVxuICB9LCAwKTtcbn0pO1xuXG5mdW5jdGlvbiBvblNlbGVjdGVkKCkge1xuICBzZWxlY3RlZFJlZi52YWx1ZSA9IHRydWU7XG5cbiAgZW1pdCgnc2VsZWN0ZWQnKTtcbn1cblxuY29uc3Qgb3B0aW9uTGF5b3V0ID0gcmVmPEhUTUxEaXZFbGVtZW50PigpO1xuPC9zY3JpcHQ+XG5cbjx0ZW1wbGF0ZT5cbiAgPGRpdiBjbGFzcz1cImNhcmQgbXktM1wiXG4gICAgOmNsYXNzPVwiWyBzZWxlY3RlZFJlZiA/ICdib3JkZXIgYm9yZGVyLXByaW1hcnknIDogJycgXVwiPlxuICAgIDxkaXYgY2xhc3M9XCJjYXJkLWJvZHkgZC1mbGV4IGFsaWduLWl0ZW1zLWNlbnRlciBnYXAtM1wiPlxuICAgICAgPGRpdiBjbGFzcz1cImZvcm0tY2hlY2tcIj5cbiAgICAgICAgPGlucHV0IHR5cGU9XCJyYWRpb1wiXG4gICAgICAgICAgOmlkPVwiYGlucHV0LXBheW1lbnQtaWQtJHtwYXltZW50LmlkfWBcIlxuICAgICAgICAgIG5hbWU9XCJjaGVja291dFtwYXltZW50XVtpZF1cIlxuICAgICAgICAgIDp2YWx1ZT1cInBheW1lbnQuaWRcIlxuICAgICAgICAgIGNsYXNzPVwiZm9ybS1jaGVjay1pbnB1dFwiXG4gICAgICAgICAgQGNoYW5nZT1cIm9uU2VsZWN0ZWRcIlxuICAgICAgICAgIDpjaGVja2VkPVwic2VsZWN0ZWRSZWZcIlxuICAgICAgICAvPlxuICAgICAgICA8bGFiZWwgOmZvcj1cImBpbnB1dC1wYXltZW50LWlkLSR7cGF5bWVudC5pZH1gXCJcbiAgICAgICAgICBjbGFzcz1cInN0cmV0Y2hlZC1saW5rXCJcbiAgICAgICAgICBzdHlsZT1cImN1cnNvcjogcG9pbnRlcjtcIlxuICAgICAgICA+PC9sYWJlbD5cbiAgICAgIDwvZGl2PlxuICAgICAgPGRpdiBjbGFzcz1cIlwiPlxuICAgICAgICA8ZGl2IGNsYXNzPVwicmF0aW8gcmF0aW8tMXgxXCJcbiAgICAgICAgICBzdHlsZT1cIndpZHRoOiA0NXB4XCI+XG4gICAgICAgICAgPGltZyBjbGFzcz1cIm9iamVjdC1maXQtY292ZXJcIiA6c3JjPVwicGF5bWVudC5pbWFnZSB8fCBpbWFnZURlZmF1bHRcIiBhbHQ9XCJjb3ZlclwiPlxuICAgICAgICA8L2Rpdj5cbiAgICAgIDwvZGl2PlxuICAgICAgPGRpdj5cbiAgICAgICAgPGg1IGNsYXNzPVwibS0wXCI+XG4gICAgICAgICAge3sgcGF5bWVudC50aXRsZSB9fVxuICAgICAgICA8L2g1PlxuICAgICAgICA8ZGl2IHYtaWY9XCJwYXltZW50LnN1YnRpdGxlXCIgY2xhc3M9XCJ0ZXh0LXN1Y2Nlc3NcIj5cbiAgICAgICAgICB7eyBwYXltZW50LnN1YnRpdGxlIH19XG4gICAgICAgIDwvZGl2PlxuICAgICAgPC9kaXY+XG5cbiAgICAgIDxkaXYgY2xhc3M9XCJtcy1hdXRvXCI+XG5cbiAgICAgIDwvZGl2PlxuICAgIDwvZGl2PlxuXG4gICAgPGRpdiB2LWlmPVwicGF5bWVudC5kZXNjcmlwdGlvbi50cmltKClcIiBjbGFzcz1cImNhcmQtYm9keSBib3JkZXItdG9wIHBzLTVcIj5cbiAgICAgIDxkaXYgY2xhc3M9XCJwb3NpdGlvbi1yZWxhdGl2ZVwiIHN0eWxlPVwiei1pbmRleDogMVwiXG4gICAgICAgIHYtaHRtbD1cInBheW1lbnQuZGVzY3JpcHRpb25cIj5cblxuICAgICAgPC9kaXY+XG4gICAgPC9kaXY+XG5cbiAgICA8dHJhbnNpdGlvbiBuYW1lPVwiZmFkZVwiIG1vZGU9XCJvdXQtaW5cIj5cbiAgICAgIDxkaXZcbiAgICAgICAgcmVmPVwib3B0aW9uTGF5b3V0XCJcbiAgICAgICAgc3R5bGU9XCJkaXNwbGF5OiBub25lOyBvdmVyZmxvdzogaGlkZGVuOyBhbmltYXRpb24tZHVyYXRpb246IC4zc1wiPlxuICAgICAgICA8ZGl2IHYtaWY9XCJwYXltZW50Lm9wdGlvbkxheW91dCAmJiBzZWxlY3RlZFJlZlwiXG4gICAgICAgICAgY2xhc3M9XCJjYXJkLWJvZHkgYm9yZGVyLXRvcFwiXG4gICAgICAgICAgdi1odG1sPVwicGF5bWVudC5vcHRpb25MYXlvdXRcIlxuICAgICAgICA+XG4gICAgICAgIDwvZGl2PlxuICAgICAgPC9kaXY+XG4gICAgPC90cmFuc2l0aW9uPlxuICA8L2Rpdj5cbjwvdGVtcGxhdGU+XG5cbjxzdHlsZSBzY29wZWQ+XG5cbjwvc3R5bGU+XG4iLCI8c2NyaXB0IHNldHVwIGxhbmc9XCJ0c1wiPlxuaW1wb3J0IHsgdWlkLCBkYXRhIGFzIHVkYXRhLCBzbGlkZVVwLCBzbGlkZURvd24gfSBmcm9tICdAd2luZHdhbGtlci1pby91bmljb3JuLW5leHQnO1xuaW1wb3J0IHsgd2F0Y2gsIHJlZiB9IGZyb20gJ3Z1ZSc7XG5pbXBvcnQgeyBTaGlwcGluZyB9IGZyb20gJ35zaG9wZ28vdHlwZXMnO1xuXG5jb25zdCBwcm9wcyA9IGRlZmluZVByb3BzPHtcbiAgc2hpcHBpbmc6IFNoaXBwaW5nO1xuICBpOiBudW1iZXI7XG4gIHNlbGVjdGVkOiBib29sZWFuO1xufT4oKTtcblxuY29uc3QgZW1pdCA9IGRlZmluZUVtaXRzPHtcbiAgKGU6ICdzZWxlY3RlZCcpOiB2b2lkO1xufT4oKVxuXG4vLyBzcGxpdCBzdGF0ZSBpbnRvIGluZGVwZW5kZW50IHJlZnNcbmNvbnN0IHVpZFJlZiA9IHJlZih1aWQoKSk7XG5jb25zdCBkYXRhID0gcmVmKHt9KTtcbmNvbnN0IHNlbGVjdGVkUmVmID0gcmVmKHByb3BzLnNlbGVjdGVkKTtcbmNvbnN0IGltYWdlRGVmYXVsdCA9IHJlZih1ZGF0YSgnaW1hZ2UuZGVmYXVsdCcpKTtcblxud2F0Y2goKCkgPT4gcHJvcHMuc2VsZWN0ZWQsICgpID0+IHtcbiAgc2VsZWN0ZWRSZWYudmFsdWUgPSBwcm9wcy5zZWxlY3RlZDtcblxuICBzZXRUaW1lb3V0KCgpID0+IHtcbiAgICBpZiAoc2VsZWN0ZWRSZWYudmFsdWUpIHtcbiAgICAgIGNvbnN0IHNjcmlwdHMgPSBmb3JtLnZhbHVlIS5xdWVyeVNlbGVjdG9yQWxsKCcuY2FyZC1ib2R5IHNjcmlwdCcpO1xuICAgICAgZm9yIChjb25zdCBzY3JpcHQgb2Ygc2NyaXB0cykge1xuICAgICAgICBldmFsKHNjcmlwdC50ZXh0Q29udGVudCk7XG4gICAgICB9XG5cbiAgICAgIHNsaWRlRG93bihmb3JtLnZhbHVlISk7XG4gICAgfSBlbHNlIHtcbiAgICAgIHNsaWRlVXAoZm9ybS52YWx1ZSEpO1xuICAgIH1cbiAgfSwgMCk7XG59KTtcblxuZnVuY3Rpb24gb25TZWxlY3RlZCgpIHtcbiAgc2VsZWN0ZWRSZWYudmFsdWUgPSB0cnVlO1xuXG4gIGVtaXQoJ3NlbGVjdGVkJyk7XG59XG5cbmNvbnN0IGZvcm0gPSByZWY8SFRNTERpdkVsZW1lbnQ+KCk7XG48L3NjcmlwdD5cblxuPHRlbXBsYXRlPlxuICA8ZGl2IGNsYXNzPVwiY2FyZCBteS0zXCJcbiAgICA6Y2xhc3M9XCJbIHNlbGVjdGVkUmVmID8gJ2JvcmRlciBib3JkZXItcHJpbWFyeScgOiAnJyBdXCI+XG4gICAgPGRpdiBjbGFzcz1cImNhcmQtYm9keSBkLWZsZXggYWxpZ24taXRlbXMtY2VudGVyIGdhcC0zXCI+XG4gICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1jaGVja1wiPlxuICAgICAgICA8aW5wdXQgdHlwZT1cInJhZGlvXCJcbiAgICAgICAgICA6aWQ9XCJgaW5wdXQtc2hpcHBpbmctaWQtJHtzaGlwcGluZy5pZH1gXCJcbiAgICAgICAgICBuYW1lPVwiY2hlY2tvdXRbc2hpcHBpbmddW2lkXVwiXG4gICAgICAgICAgOnZhbHVlPVwic2hpcHBpbmcuaWRcIlxuICAgICAgICAgIGNsYXNzPVwiZm9ybS1jaGVjay1pbnB1dFwiXG4gICAgICAgICAgQGNoYW5nZT1cIm9uU2VsZWN0ZWRcIlxuICAgICAgICAgIDpjaGVja2VkPVwic2VsZWN0ZWRSZWZcIlxuICAgICAgICAvPlxuICAgICAgICA8bGFiZWwgOmZvcj1cImBpbnB1dC1zaGlwcGluZy1pZC0ke3NoaXBwaW5nLmlkfWBcIlxuICAgICAgICAgIGNsYXNzPVwic3RyZXRjaGVkLWxpbmtcIlxuICAgICAgICAgIHN0eWxlPVwiY3Vyc29yOiBwb2ludGVyO1wiXG4gICAgICAgID48L2xhYmVsPlxuICAgICAgPC9kaXY+XG4gICAgICA8ZGl2IGNsYXNzPVwiXCI+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJyYXRpbyByYXRpby0xeDFcIlxuICAgICAgICAgIHN0eWxlPVwid2lkdGg6IDQ1cHhcIj5cbiAgICAgICAgICA8aW1nIDpzcmM9XCJzaGlwcGluZy5pbWFnZSB8fCBpbWFnZURlZmF1bHRcIiBhbHQ9XCJjb3ZlclwiPlxuICAgICAgICA8L2Rpdj5cbiAgICAgIDwvZGl2PlxuICAgICAgPGRpdj5cbiAgICAgICAgPGg1IGNsYXNzPVwibS0wXCI+XG4gICAgICAgICAge3sgc2hpcHBpbmcudGl0bGUgfX1cbiAgICAgICAgPC9oNT5cbiAgICAgICAgPGRpdiB2LWlmPVwic2hpcHBpbmcuc3VidGl0bGVcIiBjbGFzcz1cInRleHQtc3VjY2Vzc1wiPlxuICAgICAgICAgIHt7IHNoaXBwaW5nLnN1YnRpdGxlIH19XG4gICAgICAgIDwvZGl2PlxuICAgICAgPC9kaXY+XG5cbiAgICAgIDxkaXYgY2xhc3M9XCJtcy1hdXRvXCI+XG4gICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZzLTVcIj5cbiAgICAgICAgICAgIHt7ICRmb3JtYXRQcmljZShzaGlwcGluZy5mZWUsIHRydWUpIH19XG4gICAgICAgICAgICA8L3NwYW4+XG4gICAgICA8L2Rpdj5cbiAgICA8L2Rpdj5cblxuICAgIDxkaXYgdi1pZj1cInNoaXBwaW5nLmRlc2NyaXB0aW9uLnRyaW0oKVwiIGNsYXNzPVwiY2FyZC1ib2R5IGJvcmRlci10b3AgcHMtNVwiPlxuICAgICAgPGRpdiBjbGFzcz1cInBvc2l0aW9uLXJlbGF0aXZlXCIgc3R5bGU9XCJ6LWluZGV4OiAxXCJcbiAgICAgICAgdi1odG1sPVwic2hpcHBpbmcuZGVzY3JpcHRpb25cIj5cblxuICAgICAgPC9kaXY+XG4gICAgPC9kaXY+XG5cbiAgICA8dHJhbnNpdGlvbiBuYW1lPVwiZmFkZVwiIG1vZGU9XCJvdXQtaW5cIj5cbiAgICAgIDxkaXZcbiAgICAgICAgcmVmPVwiZm9ybVwiXG4gICAgICAgIHN0eWxlPVwiZGlzcGxheTogbm9uZTsgcG9zdGlvbjogcmVsYXRpdmU7IHotaW5kZXg6IDE7IG92ZXJmbG93OiBoaWRkZW47IGFuaW1hdGlvbi1kdXJhdGlvbjogLjNzXCI+XG4gICAgICAgIDxkaXYgdi1pZj1cInNoaXBwaW5nLmNoZWNrb3V0Rm9ybSAmJiBzZWxlY3RlZFJlZlwiXG4gICAgICAgICAgIGNsYXNzPVwiY2FyZC1ib2R5IGJvcmRlci10b3BcIlxuICAgICAgICAgICB2LWh0bWw9XCJzaGlwcGluZy5jaGVja291dEZvcm1cIj5cbiAgICAgICAgPC9kaXY+XG4gICAgICA8L2Rpdj5cbiAgICA8L3RyYW5zaXRpb24+XG4gIDwvZGl2PlxuPC90ZW1wbGF0ZT5cblxuPHN0eWxlIHNjb3BlZD5cblxuPC9zdHlsZT5cbiIsIjxzY3JpcHQgc2V0dXAgbGFuZz1cInRzXCI+XG5pbXBvcnQgeyBfXywgZGF0YSwgZGVib3VuY2UsIHJvdXRlLCBzaW1wbGVBbGVydCwgdXNlSHR0cENsaWVudCwgdXNlU3RhY2sgfSBmcm9tICdAd2luZHdhbGtlci1pby91bmljb3JuLW5leHQnO1xuaW1wb3J0IHsgY29tcHV0ZWQsIG5leHRUaWNrLCBvbk1vdW50ZWQsIHJlZiwgd2F0Y2ggfSBmcm9tICd2dWUnO1xuaW1wb3J0IHsgQ29tcG9uZW50RXhwb3NlZCB9IGZyb20gJ3Z1ZS1jb21wb25lbnQtdHlwZS1oZWxwZXJzJztcbmltcG9ydCB7IHZUb29sdGlwIH0gZnJvbSAnfnNob3Bnby9kaXJlY3RpdmVzJztcbmltcG9ydCBBZGRyZXNzRm9ybSBmcm9tICd+c2hvcGdvL21vZHVsZXMvY2FydC9jb21wb25lbnRzL0FkZHJlc3NGb3JtLnZ1ZSc7XG5pbXBvcnQgQ2FydExpc3RJdGVtIGZyb20gJ35zaG9wZ28vbW9kdWxlcy9jYXJ0L2NvbXBvbmVudHMvQ2FydExpc3RJdGVtLnZ1ZSc7XG5pbXBvcnQgUGF5bWVudEl0ZW0gZnJvbSAnfnNob3Bnby9tb2R1bGVzL2NhcnQvY29tcG9uZW50cy9QYXltZW50SXRlbS52dWUnO1xuaW1wb3J0IFNoaXBwaW5nSXRlbSBmcm9tICd+c2hvcGdvL21vZHVsZXMvY2FydC9jb21wb25lbnRzL1NoaXBwaW5nSXRlbS52dWUnO1xuaW1wb3J0IHsgQ2FydERhdGEsIENhcnRJdGVtLCBEaXNjb3VudCwgT3JkZXJUb3RhbCwgUGF5bWVudCwgU2hpcHBpbmcsIFVzZXIgfSBmcm9tICd+c2hvcGdvL3R5cGVzJztcblxuY29uc3QgcHJvcHMgPSBkZWZpbmVQcm9wczx7XG4gIHVzZXI6IFVzZXIgfCBudWxsO1xuICBjaGVja291dERhdGE6IGFueTtcbn0+KCk7XG5cbmNvbnN0IGxvYWRlZCA9IHJlZihmYWxzZSk7XG5jb25zdCBpdGVtcyA9IHJlZjxDYXJ0SXRlbVtdPihbXSk7XG5jb25zdCB0b3RhbHMgPSByZWY8UmVjb3JkPHN0cmluZywgYW55Pj4oe30pO1xuY29uc3QgY291cG9ucyA9IHJlZjxEaXNjb3VudFtdPihbXSk7XG5jb25zdCBwYXltZW50SWQgPSByZWYocHJvcHMuY2hlY2tvdXREYXRhPy5wYXltZW50Py5pZCB8fCAnJyk7XG5jb25zdCBwYXltZW50RGF0YSA9IHJlZihwcm9wcy5jaGVja291dERhdGE/LnBheW1lbnRfZGF0YSB8fCB7fSk7XG5jb25zdCBzaGlwcGluZ0lkID0gcmVmKHByb3BzLmNoZWNrb3V0RGF0YT8uc2hpcHBpbmc/LmlkIHx8ICcnKTtcbmNvbnN0IHNoaXBwaW5nRGF0YSA9IHJlZihwcm9wcy5jaGVja291dERhdGE/LnNoaXBwaW5nX2RhdGEgfHwge30pO1xuY29uc3Qgc2hpcHBpbmdzID0gcmVmPFNoaXBwaW5nW10+KFtdKTtcbmNvbnN0IHBheW1lbnRzID0gcmVmPFBheW1lbnRbXT4oW10pO1xuY29uc3QgcmVjZWlwdERhdGEgPSByZWY8YW55Pih7fSk7XG5jb25zdCBjb2RlID0gcmVmKCcnKTtcbmNvbnN0IG5vdGUgPSByZWYocHJvcHMuY2hlY2tvdXREYXRhPy5ub3RlIHx8ICcnKTtcbmNvbnN0IGxvYWRpbmcgPSByZWYoZmFsc2UpO1xuY29uc3QgcGFydGlhbENoZWNrb3V0ID0gcmVmKGRhdGEoJ3BhcnRpYWwuY2hlY2tvdXQnKSk7XG5cbmNvbnN0IGZvcm0gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yPEhUTUxGb3JtRWxlbWVudD4oJyNjYXJ0LWZvcm0nKSE7XG5jb25zdCB0b2dnbGVBbGxJbnB1dCA9IHJlZjxIVE1MSW5wdXRFbGVtZW50PigpO1xuY29uc3QgbG9hZGluZ1N0YWNrID0gdXNlU3RhY2soJ2xvYWRpbmcnKTtcblxubG9hZGluZ1N0YWNrLm9ic2VydmUoKHN0YWNrLCBsZW5ndGgpID0+IHtcbiAgbG9hZGluZy52YWx1ZSA9IGxlbmd0aCA+IDA7XG59KTtcblxuaW5pdCgpO1xuXG5mdW5jdGlvbiBwb3BMb2FkaW5nKHdhaXQgPSAzMDApIHtcbiAgc2V0VGltZW91dCgoKSA9PiB7XG4gICAgbG9hZGluZ1N0YWNrLnBvcCgpO1xuICB9LCB3YWl0KTtcbn1cblxuY29uc3QgYWZ0ZXJJdGVtc0NoYW5nZWQgPSBkZWJvdW5jZShmdW5jdGlvbiAoKSB7XG4gIHJldHVybiBsb2FkSXRlbXMoKTtcbn0sIDMwMCk7XG5cbmFzeW5jIGZ1bmN0aW9uIGxvYWRJdGVtcyh1cGRhdGVTaGlwcGluZ3MgPSB0cnVlKSB7XG4gIGxvYWRpbmdTdGFjay5wdXNoKHRydWUpO1xuXG4gIGNvbnN0IHsgZ2V0LCBpc0F4aW9zRXJyb3IgfSA9IGF3YWl0IHVzZUh0dHBDbGllbnQoKTtcblxuICB0cnkge1xuICAgIGNvbnN0IHJlcyA9IGF3YWl0IGdldChcbiAgICAgICdAY2FydF9hamF4L2dldEl0ZW1zJyxcbiAgICAgIHtcbiAgICAgICAgcGFyYW1zOiB7XG4gICAgICAgICAgbG9jYXRpb25faWQ6IHNoaXBwaW5nRGF0YS52YWx1ZS5sb2NhdGlvbklkLFxuICAgICAgICAgIHNoaXBwaW5nX2lkOiBzaGlwcGluZ0lkLnZhbHVlLFxuICAgICAgICAgIHBheW1lbnRfaWQ6IHBheW1lbnRJZC52YWx1ZSxcbiAgICAgICAgfVxuICAgICAgfVxuICAgICk7XG5cbiAgICBhd2FpdCBzZXRDYXJ0RGF0YShyZXMuZGF0YS5kYXRhLCB1cGRhdGVTaGlwcGluZ3MpO1xuXG4gICAgcmV0dXJuIHJlcztcbiAgfSBjYXRjaCAoZSkge1xuICAgIGNvbnNvbGUuZXJyb3IoZSk7XG4gICAgaWYgKGlzQXhpb3NFcnJvcihlKSkge1xuICAgICAgc2ltcGxlQWxlcnQoZS5tZXNzYWdlLCAnJywgJ3dhcm5pbmcnKTtcbiAgICB9XG4gIH0gZmluYWxseSB7XG4gICAgcG9wTG9hZGluZygpO1xuICB9XG59XG5cbmFzeW5jIGZ1bmN0aW9uIHNldENhcnREYXRhKGRhdGE6IENhcnREYXRhLCB1cGRhdGVTaGlwcGluZ3MgPSB0cnVlKSB7XG4gIGl0ZW1zLnZhbHVlID0gZGF0YS5pdGVtcztcbiAgdG90YWxzLnZhbHVlID0gZGF0YS50b3RhbHM7XG4gIGNvdXBvbnMudmFsdWUgPSBkYXRhLmNvdXBvbnM7XG5cbiAgaWYgKHVwZGF0ZVNoaXBwaW5ncykge1xuICAgIHJldHVybiBhd2FpdCBsb2FkU2hpcHBpbmdzKCk7XG4gIH1cblxuICByZXR1cm47XG59XG5cbi8vIFRvZ2dsZSBjaGVja3NcbndhdGNoKGl0ZW1zLCAoKSA9PiB7XG4gIHVwZGF0ZVRvZ2dsZUFsbCgpO1xufSwgeyBkZWVwOiB0cnVlIH0pO1xuXG5jb25zdCBpdGVtQ2hlY2tzID0gY29tcHV0ZWQoKCkgPT4ge1xuICByZXR1cm4gaXRlbXMudmFsdWUubWFwKChpdGVtOiBhbnkpID0+IHtcbiAgICBpZiAoaXRlbS5vcHRpb25zLmNoZWNrZWQgPT0gbnVsbCkge1xuICAgICAgcmV0dXJuIHRydWU7XG4gICAgfVxuXG4gICAgcmV0dXJuIGl0ZW0ub3B0aW9ucy5jaGVja2VkO1xuICB9KTtcbn0pO1xuXG5jb25zdCBjaGVja3MgPSBjb21wdXRlZCgoKSA9PiBpdGVtQ2hlY2tzLnZhbHVlLmZpbHRlcihjaGVja2VkID0+IGNoZWNrZWQgPT09IHRydWUpLmxlbmd0aCk7XG5jb25zdCB1bmNoZWNrcyA9IGNvbXB1dGVkKCgpID0+IGl0ZW1DaGVja3MudmFsdWUuZmlsdGVyKGNoZWNrZWQgPT4gY2hlY2tlZCA9PT0gZmFsc2UpLmxlbmd0aCk7XG5cbmZ1bmN0aW9uIHVwZGF0ZVRvZ2dsZUFsbCgpIHtcbiAgaWYgKCF0b2dnbGVBbGxJbnB1dC52YWx1ZSkge1xuICAgIHJldHVybjtcbiAgfVxuXG4gIHRvZ2dsZUFsbElucHV0LnZhbHVlLmNoZWNrZWQgPSBmYWxzZTtcbiAgdG9nZ2xlQWxsSW5wdXQudmFsdWUuaW5kZXRlcm1pbmF0ZSA9IGZhbHNlO1xuXG4gIGlmIChjaGVja3MudmFsdWUgPiAwICYmIHVuY2hlY2tzLnZhbHVlID09PSAwKSB7XG4gICAgdG9nZ2xlQWxsSW5wdXQudmFsdWUuY2hlY2tlZCA9IHRydWU7XG4gIH0gZWxzZSBpZiAodW5jaGVja3MudmFsdWUgPiAwICYmIGNoZWNrcy52YWx1ZSA9PT0gMCkge1xuICAgIHRvZ2dsZUFsbElucHV0LnZhbHVlLmNoZWNrZWQgPSBmYWxzZTtcbiAgfSBlbHNlIGlmIChjaGVja3MudmFsdWUgPiAwICYmIHVuY2hlY2tzLnZhbHVlID4gMCkge1xuICAgIHRvZ2dsZUFsbElucHV0LnZhbHVlLmluZGV0ZXJtaW5hdGUgPSB0cnVlO1xuICB9XG59XG5cbmZ1bmN0aW9uIHRvZ2dsZUNoZWNrZWQoKSB7XG4gIGlmICghdG9nZ2xlQWxsSW5wdXQudmFsdWUpIHtcbiAgICByZXR1cm47XG4gIH1cblxuICBmb3IgKGNvbnN0IGl0ZW0gb2YgaXRlbXMudmFsdWUpIHtcbiAgICBpdGVtLm9wdGlvbnMuY2hlY2tlZCA9IHRvZ2dsZUFsbElucHV0LnZhbHVlLmNoZWNrZWQ7XG4gIH1cblxuICB1cGRhdGVDaGVja3MoKTtcbn1cblxuY29uc3QgdXBkYXRlQ2hlY2tzID0gZGVib3VuY2UoYXN5bmMgKCkgPT4ge1xuICBjb25zdCBjaGVja3M6IFJlY29yZDxzdHJpbmcsICcxJyB8ICcwJz4gPSB7fTtcblxuICBmb3IgKGNvbnN0IGl0ZW0gb2YgaXRlbXMudmFsdWUpIHtcbiAgICBjaGVja3NbaXRlbS5rZXldID0gaXRlbS5vcHRpb25zLmNoZWNrZWQgPyAnMScgOiAnMCc7XG4gIH1cblxuICBsb2FkaW5nU3RhY2sucHVzaCh0cnVlKTtcblxuICBjb25zdCB7IHBvc3QsIGlzQXhpb3NFcnJvciB9ID0gYXdhaXQgdXNlSHR0cENsaWVudCgpO1xuXG4gIHRyeSB7XG4gICAgY29uc3QgcmVzID0gYXdhaXQgcG9zdCgnQGNhcnRfYWpheC91cGRhdGVDaGVja3MnLCB7IGNoZWNrcyB9KTtcblxuICAgIHJldHVybiBhd2FpdCBsb2FkSXRlbXMoKTtcbiAgfSBjYXRjaCAoZSkge1xuICAgIGNvbnNvbGUuZXJyb3IoZSk7XG4gICAgaWYgKGlzQXhpb3NFcnJvcihlKSkge1xuICAgICAgc2ltcGxlQWxlcnQoZS5tZXNzYWdlLCAnJywgJ3dhcm5pbmcnKTtcbiAgICB9XG4gIH0gZmluYWxseSB7XG4gICAgcG9wTG9hZGluZygpO1xuICB9XG59LCAzMDApO1xuXG5vbk1vdW50ZWQoKCkgPT4ge1xuICBjYWxjTmF2QW5kU3RpY2t5U2lkZWJhcihmb3JtKTtcbn0pO1xuXG5mdW5jdGlvbiBjYWxjTmF2QW5kU3RpY2t5U2lkZWJhcihmb3JtOiBIVE1MRm9ybUVsZW1lbnQsIG9mZnNldHMgPSAzMCkge1xuICBjb25zdCBuYXZiYXIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yPEhUTUxEaXZFbGVtZW50PignaGVhZGVyIC5uYXZiYXIsIC5uYXZiYXInKTtcblxuICBpZiAoIW5hdmJhcikge1xuICAgIHJldHVybjtcbiAgfVxuXG4gIGNvbnN0IHRvcCA9IG5hdmJhci5jbGllbnRIZWlnaHQgKyBvZmZzZXRzO1xuXG4gIGZvcm0uc3R5bGUuc2V0UHJvcGVydHkoJy0tc2lkZWJhci1vZmZzZXRzLXRvcCcsIHRvcCArICdweCcpO1xufVxuXG5hc3luYyBmdW5jdGlvbiBpbml0KCkge1xuICBhd2FpdCBsb2FkSXRlbXMoKTtcblxuICBsb2FkZWQudmFsdWUgPSB0cnVlO1xufVxuXG4vLyBBY3Rpb25zXG5hc3luYyBmdW5jdGlvbiByZW1vdmVJdGVtKGl0ZW06IENhcnRJdGVtLCBpOiBudW1iZXIpIHtcbiAgbG9hZGluZ1N0YWNrLnB1c2godHJ1ZSk7XG5cbiAgY29uc3QgeyBkZWxldGU6IGRlbCwgaXNBeGlvc0Vycm9yIH0gPSBhd2FpdCB1c2VIdHRwQ2xpZW50KCk7XG5cbiAgdHJ5IHtcbiAgICBjb25zdCByZXMgPSBhd2FpdCBkZWwoYEBjYXJ0X2FqYXgvcmVtb3ZlSXRlbT9rZXk9JHtpdGVtLmtleX1gKTtcblxuICAgIHJldHVybiBhd2FpdCBhZnRlckl0ZW1zQ2hhbmdlZCgpO1xuICB9IGNhdGNoIChlKSB7XG4gICAgY29uc29sZS5lcnJvcihlKTtcbiAgICBpZiAoaXNBeGlvc0Vycm9yKGUpKSB7XG4gICAgICBzaW1wbGVBbGVydChlLm1lc3NhZ2UsICcnLCAnd2FybmluZycpO1xuICAgIH1cbiAgfSBmaW5hbGx5IHtcbiAgICBsb2FkaW5nU3RhY2sucG9wKCk7XG4gIH1cbn1cblxuYXN5bmMgZnVuY3Rpb24gY2xlYXJDYXJ0KCkge1xuICBsb2FkaW5nU3RhY2sucHVzaCh0cnVlKTtcblxuICBjb25zdCB7IHB1dCwgaXNBeGlvc0Vycm9yIH0gPSBhd2FpdCB1c2VIdHRwQ2xpZW50KCk7XG5cbiAgdHJ5IHtcbiAgICBhd2FpdCBwdXQoYEBjYXJ0X2FqYXgvY2xlYXJDYXJ0YCk7XG5cbiAgICBhd2FpdCBsb2FkSXRlbXMoKTtcblxuICAgIGF3YWl0IHNpbXBsZUFsZXJ0KFxuICAgICAgX18oJ3Nob3Bnby5jYXJ0Lm1lc3NhZ2UuaXRlbXMucmVtb3ZlZCcpLFxuICAgICAgX18oJ3Nob3Bnby5jYXJ0Lm1lc3NhZ2Uud2lsbC5iYWNrLnRvLmhvbWUnKSxcbiAgICAgICdzdWNjZXNzJ1xuICAgICk7XG5cbiAgICBsb2NhdGlvbi5ocmVmID0gcm91dGUoJ2hvbWUnKTtcbiAgfSBjYXRjaCAoZSkge1xuICAgIGNvbnNvbGUuZXJyb3IoZSk7XG4gICAgaWYgKGlzQXhpb3NFcnJvcihlKSkge1xuICAgICAgc2ltcGxlQWxlcnQoZS5tZXNzYWdlLCAnJywgJ3dhcm5pbmcnKTtcbiAgICB9XG4gIH0gZmluYWxseSB7XG4gICAgbG9hZGluZ1N0YWNrLnBvcCgpO1xuICB9XG59XG5cbi8vIFF1YW50aXR5XG5hc3luYyBmdW5jdGlvbiBjaGFuZ2VJdGVtUXVhbnRpdHkoaXRlbTogQ2FydEl0ZW0sIG9mZnNldHM6IG51bWJlcikge1xuICBpdGVtLnF1YW50aXR5ICs9IG9mZnNldHM7XG5cbiAgaXRlbS5xdWFudGl0eSA9IE1hdGgubWF4KGl0ZW0ucXVhbnRpdHksIDEpO1xuXG4gIGF3YWl0IHVwZGF0ZVF1YW50aXRpZXMoaXRlbSk7XG59XG5cbmNvbnN0IHVwZGF0ZVF1YW50aXRpZXMgPSBkZWJvdW5jZShhc3luYyAoaXRlbTogQ2FydEl0ZW0pID0+IHtcbiAgaXRlbS5xdWFudGl0eSA9IE1hdGgubWF4KGl0ZW0ucXVhbnRpdHksIDEpO1xuXG4gIGNvbnN0IHZhbHVlczogUmVjb3JkPHN0cmluZywgbnVtYmVyPiA9IHt9O1xuXG4gIGZvciAoY29uc3QgaXRlbSBvZiBpdGVtcy52YWx1ZSkge1xuICAgIHZhbHVlc1tpdGVtLmtleV0gPSBpdGVtLnF1YW50aXR5O1xuICB9XG5cbiAgbG9hZGluZ1N0YWNrLnB1c2godHJ1ZSk7XG5cbiAgY29uc3QgeyBwb3N0LCBpc0F4aW9zRXJyb3IgfSA9IGF3YWl0IHVzZUh0dHBDbGllbnQoKTtcblxuICB0cnkge1xuICAgIGNvbnN0IHJlcyA9IGF3YWl0IHBvc3QoJ0BjYXJ0X2FqYXgvdXBkYXRlUXVhbnRpdGllcycsIHsgdmFsdWVzIH0pO1xuXG4gICAgcmV0dXJuIGF3YWl0IGxvYWRJdGVtcygpO1xuICB9IGNhdGNoIChlKSB7XG4gICAgY29uc29sZS5lcnJvcihlKTtcbiAgICBpZiAoaXNBeGlvc0Vycm9yKGUpKSB7XG4gICAgICBzaW1wbGVBbGVydChlLm1lc3NhZ2UsICcnLCAnd2FybmluZycpO1xuICAgIH1cbiAgfSBmaW5hbGx5IHtcbiAgICBwb3BMb2FkaW5nKCk7XG4gIH1cbn0sIDMwMCk7XG5cbi8vIENvZGUgLyBDb3Vwb25zXG5hc3luYyBmdW5jdGlvbiBhZGRDb2RlKCkge1xuICBpZiAoY29kZS52YWx1ZSA9PT0gJycpIHtcbiAgICByZXR1cm47XG4gIH1cblxuICBsb2FkaW5nU3RhY2sucHVzaCh0cnVlKTtcblxuICBjb25zdCB7IHBvc3QsIGlzQXhpb3NFcnJvciB9ID0gYXdhaXQgdXNlSHR0cENsaWVudCgpO1xuXG4gIHRyeSB7XG4gICAgY29uc3QgcmVzID0gYXdhaXQgcG9zdCgnQGNhcnRfYWpheC9hZGRDb2RlJywgeyBjb2RlOiBjb2RlLnZhbHVlIH0pO1xuXG4gICAgY29kZS52YWx1ZSA9ICcnO1xuXG4gICAgYXdhaXQgbG9hZEl0ZW1zKCk7XG4gIH0gY2F0Y2ggKGUpIHtcbiAgICBjb25zb2xlLmVycm9yKGUpO1xuICAgIGlmIChpc0F4aW9zRXJyb3IoZSkpIHtcbiAgICAgIHNpbXBsZUFsZXJ0KGUubWVzc2FnZSwgJycsICd3YXJuaW5nJyk7XG4gICAgfVxuICB9IGZpbmFsbHkge1xuICAgIHBvcExvYWRpbmcoKTtcbiAgfVxufVxuXG5hc3luYyBmdW5jdGlvbiByZW1vdmVDb2RlKGlkOiBudW1iZXIgfCBzdHJpbmcpIHtcbiAgbG9hZGluZ1N0YWNrLnB1c2godHJ1ZSk7XG5cbiAgY29uc3QgeyBkZWxldGU6IGRlbCwgaXNBeGlvc0Vycm9yIH0gPSBhd2FpdCB1c2VIdHRwQ2xpZW50KCk7XG5cbiAgdHJ5IHtcbiAgICBjb25zdCByZXMgPSBhd2FpdCBkZWwoJ0BjYXJ0X2FqYXgvcmVtb3ZlQ29kZScsIHsgaWQgfSk7XG5cbiAgICBhd2FpdCBsb2FkSXRlbXMoKTtcbiAgfSBjYXRjaCAoZSkge1xuICAgIGNvbnNvbGUuZXJyb3IoZSk7XG4gICAgaWYgKGlzQXhpb3NFcnJvcihlKSkge1xuICAgICAgc2ltcGxlQWxlcnQoZS5tZXNzYWdlLCAnJywgJ3dhcm5pbmcnKTtcbiAgICB9XG4gIH0gZmluYWxseSB7XG4gICAgcG9wTG9hZGluZygpO1xuICB9XG59XG5cbi8vIFRvdGFsc1xuY29uc3QgZmlsdGVyZWRUb3RhbHMgPSBjb21wdXRlZCgoKSA9PiB7XG4gIGNvbnN0IF90b3RhbHM6IGFueVtdID0gW107XG5cbiAgZm9yIChjb25zdCBuYW1lIGluIHRvdGFscy52YWx1ZSkge1xuICAgIGlmIChuYW1lID09PSAndG90YWwnKSB7XG4gICAgICBjb250aW51ZTtcbiAgICB9XG5cbiAgICBpZiAobmFtZSA9PT0gJ2dyYW5kX3RvdGFsJykge1xuICAgICAgY29udGludWU7XG4gICAgfVxuXG4gICAgY29uc3QgdG90YWwgPSB0b3RhbHMudmFsdWVbbmFtZV07XG5cbiAgICBpZiAoTnVtYmVyKHRvdGFsLnByaWNlKSA9PT0gMCkge1xuICAgICAgY29udGludWU7XG4gICAgfVxuXG4gICAgX3RvdGFscy5wdXNoKHRvdGFsKTtcbiAgfVxuXG4gIHJldHVybiBfdG90YWxzO1xufSk7XG5cbi8vIFNoaXBwaW5nc1xud2F0Y2goKCkgPT4gc2hpcHBpbmdEYXRhLnZhbHVlLmxvY2F0aW9uSWQsICgpID0+IHtcbiAgbG9hZFNoaXBwaW5ncygpO1xufSk7XG53YXRjaCgoKSA9PiBzaGlwcGluZ0lkLnZhbHVlLCAoKSA9PiB7XG4gIGxvYWRJdGVtcyhmYWxzZSk7XG59KTtcblxuY29uc3Qgc2VsZWN0ZWRTaGlwcGluZyA9IGNvbXB1dGVkKCgpID0+IHtcbiAgcmV0dXJuIHNoaXBwaW5ncy52YWx1ZS5maW5kKChpdGVtOiBhbnkpID0+IFN0cmluZyhpdGVtLmlkKSA9PT0gU3RyaW5nKHNoaXBwaW5nSWQudmFsdWUpKTtcbn0pO1xuXG5jb25zdCBsb2FkU2hpcHBpbmdzID0gZGVib3VuY2UoYXN5bmMgZnVuY3Rpb24gKCkge1xuICBsb2FkaW5nU3RhY2sucHVzaCh0cnVlKTtcblxuICBjb25zdCB7IGdldCwgaXNBeGlvc0Vycm9yIH0gPSBhd2FpdCB1c2VIdHRwQ2xpZW50KCk7XG5cbiAgdHJ5IHtcbiAgICBjb25zdCByZXMgPSBhd2FpdCBnZXQoYEBjYXJ0X2FqYXgvc2hpcHBpbmdzP2xvY2F0aW9uX2lkPSR7c2hpcHBpbmdEYXRhLnZhbHVlLmxvY2F0aW9uSWR9YCk7XG5cbiAgICBzaGlwcGluZ3MudmFsdWUgPSByZXMuZGF0YS5kYXRhO1xuXG4gICAgYXdhaXQgbmV4dFRpY2soKTtcbiAgICBhd2FpdCBuZXh0VGljaygpO1xuXG4gICAgaWYgKHNoaXBwaW5ncy52YWx1ZS5sZW5ndGggPiAwKSB7XG4gICAgICBpZiAoIXNlbGVjdGVkU2hpcHBpbmcudmFsdWUpIHtcbiAgICAgICAgc2hpcHBpbmdJZC52YWx1ZSA9IHNoaXBwaW5ncy52YWx1ZVswXS5pZDtcbiAgICAgIH1cbiAgICB9IGVsc2Uge1xuICAgICAgc2hpcHBpbmdJZC52YWx1ZSA9IG51bGw7XG4gICAgfVxuICB9IGNhdGNoIChlKSB7XG4gICAgY29uc29sZS5lcnJvcihlKTtcbiAgICBpZiAoaXNBeGlvc0Vycm9yKGUpKSB7XG4gICAgICBzaW1wbGVBbGVydChlLm1lc3NhZ2UsICcnLCAnd2FybmluZycpO1xuICAgIH1cbiAgfSBmaW5hbGx5IHtcbiAgICBwb3BMb2FkaW5nKCk7XG4gIH1cbn0sIDMwMCk7XG5cbi8vIFBheW1lbnRzXG53YXRjaCgoKSA9PiBbc2hpcHBpbmdEYXRhLnZhbHVlLmxvY2F0aW9uSWQsIHNoaXBwaW5nSWQudmFsdWVdLCAoKSA9PiB7XG4gIGxvYWRQYXltZW50cygpO1xufSk7XG5cbmNvbnN0IHNlbGVjdGVkUGF5bWVudCA9IGNvbXB1dGVkKCgpID0+IHtcbiAgcmV0dXJuIHBheW1lbnRzLnZhbHVlLmZpbmQoKGl0ZW06IGFueSkgPT4gaXRlbS5pZCA9PT0gcGF5bWVudElkLnZhbHVlKTtcbn0pO1xuXG5jb25zdCBsb2FkUGF5bWVudHMgPSBkZWJvdW5jZShhc3luYyBmdW5jdGlvbiAoKSB7XG4gIGxvYWRpbmdTdGFjay5wdXNoKHRydWUpO1xuXG4gIGNvbnN0IHsgZ2V0LCBpc0F4aW9zRXJyb3IgfSA9IGF3YWl0IHVzZUh0dHBDbGllbnQoKTtcblxuICB0cnkge1xuICAgIGNvbnN0IHJlcyA9IGF3YWl0IGdldChcbiAgICAgIGBAY2FydF9hamF4L3BheW1lbnRzYCxcbiAgICAgIHtcbiAgICAgICAgcGFyYW1zOiB7XG4gICAgICAgICAgbG9jYXRpb25faWQ6IHNoaXBwaW5nRGF0YS52YWx1ZS5sb2NhdGlvbklkLFxuICAgICAgICAgIHNoaXBwaW5nX2lkOiBzaGlwcGluZ0lkLnZhbHVlXG4gICAgICAgIH1cbiAgICAgIH1cbiAgICApO1xuXG4gICAgcGF5bWVudHMudmFsdWUgPSByZXMuZGF0YS5kYXRhO1xuXG4gICAgYXdhaXQgbmV4dFRpY2soKTtcbiAgICBhd2FpdCBuZXh0VGljaygpO1xuXG4gICAgaWYgKHBheW1lbnRzLnZhbHVlLmxlbmd0aCA+IDApIHtcbiAgICAgIGlmICghcGF5bWVudHMudmFsdWUuZmluZCgocGF5bWVudDogYW55KSA9PiBwYXltZW50LmlkID09PSBwYXltZW50SWQudmFsdWUpKSB7XG4gICAgICAgIHBheW1lbnRJZC52YWx1ZSA9IHBheW1lbnRzLnZhbHVlWzBdLmlkO1xuICAgICAgfVxuICAgIH0gZWxzZSB7XG4gICAgICBwYXltZW50SWQudmFsdWUgPSBudWxsO1xuICAgIH1cbiAgfSBjYXRjaCAoZSkge1xuICAgIGNvbnNvbGUuZXJyb3IoZSk7XG4gICAgaWYgKGlzQXhpb3NFcnJvcihlKSkge1xuICAgICAgc2ltcGxlQWxlcnQoZS5tZXNzYWdlLCAnJywgJ3dhcm5pbmcnKTtcbiAgICB9XG4gIH0gZmluYWxseSB7XG4gICAgcG9wTG9hZGluZygpO1xuICB9XG59LCAzMDApO1xuXG4vLyBDaGVja291dFxuY29uc3QgY2FuQ2hlY2tvdXQgPSBjb21wdXRlZCgoKSA9PiB7XG4gIGlmIChjaGVja3MudmFsdWUgPT09IDApIHtcbiAgICByZXR1cm4gZmFsc2U7XG4gIH1cblxuICBpZiAoIXNoaXBwaW5nRGF0YS52YWx1ZS5sb2NhdGlvbklkKSB7XG4gICAgcmV0dXJuIGZhbHNlO1xuICB9XG5cbiAgaWYgKCFwYXltZW50RGF0YS52YWx1ZS5sb2NhdGlvbklkKSB7XG4gICAgcmV0dXJuIGZhbHNlO1xuICB9XG5cbiAgaWYgKCFzaGlwcGluZ0lkLnZhbHVlKSB7XG4gICAgcmV0dXJuIGZhbHNlO1xuICB9XG5cbiAgaWYgKCFwYXltZW50SWQudmFsdWUpIHtcbiAgICByZXR1cm4gZmFsc2U7XG4gIH1cblxuICByZXR1cm4gdHJ1ZTtcbn0pO1xuXG5jb25zdCBzaGlwcGluZ0Zvcm0gPSByZWY8Q29tcG9uZW50RXhwb3NlZDx0eXBlb2YgQWRkcmVzc0Zvcm0+PigpO1xuY29uc3QgcGF5bWVudEZvcm0gPSByZWY8Q29tcG9uZW50RXhwb3NlZDx0eXBlb2YgQWRkcmVzc0Zvcm0+PigpO1xuXG5mdW5jdGlvbiBjaGVja291dCgpIHtcbiAgaWYgKGNoZWNrcy52YWx1ZSA9PT0gMCkge1xuICAgIGNvbnNvbGUud2FybignTm8gY2hlY2tlZCBpdGVtcycpO1xuICAgIHJldHVybjtcbiAgfVxuXG4gIGlmIChOdW1iZXIodG90YWxzLnZhbHVlLmdyYW5kX3RvdGFsLnByaWNlKSA8IDApIHtcbiAgICBzd2FsKCdDYW5ub3QgcHJvY2VzcyBjYXJ0IHdpdGggbmVnYXRpdmUgcHJpY2VzLicsICcnLCAnd2FybmluZycpO1xuICAgIHJldHVybjtcbiAgfVxuXG4gIGZvciAoY29uc3QgaXRlbSBvZiBpdGVtcy52YWx1ZSkge1xuICAgIGlmIChOdW1iZXIoaXRlbS5wcmljZVNldC5maW5hbF90b3RhbC5wcmljZSkgPCAwKSB7XG4gICAgICBzd2FsKCdDYW5ub3QgcHJvY2VzcyBwcm9kdWN0IGl0ZW1zIHdpdGggbmVnYXRpdmUgcHJpY2VzLicsICcnLCAnd2FybmluZycpO1xuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIGlmIChOdW1iZXIoaXRlbS5wcmljZVNldC5hdHRhY2hlZF9maW5hbF90b3RhbC5wcmljZSkgPCAwKSB7XG4gICAgICBzd2FsKCdDYW5ub3QgcHJvY2VzcyBwcm9kdWN0IGl0ZW1zIHdpdGggbmVnYXRpdmUgcHJpY2VzLicsICcnLCAnd2FybmluZycpO1xuICAgICAgcmV0dXJuO1xuICAgIH1cbiAgfVxuXG4gIGlmIChzaGlwcGluZ0Zvcm0udmFsdWUgJiYgIXNoaXBwaW5nRm9ybS52YWx1ZS52YWxpZGF0ZSgpKSB7XG4gICAgY29uc29sZS5sb2coJ1NoaXBwaW5nIFZhbGlkYXRlIEZhaWwnKTtcbiAgICByZXR1cm47XG4gIH1cblxuICBpZiAocGF5bWVudEZvcm0udmFsdWUgJiYgIXBheW1lbnRGb3JtLnZhbHVlLnZhbGlkYXRlKCkpIHtcbiAgICBjb25zb2xlLmxvZygnUGF5bWVudCBWYWxpZGF0ZSBGYWlsJyk7XG4gICAgcmV0dXJuO1xuICB9XG5cbiAgaWYgKCFmb3JtLmNoZWNrVmFsaWRpdHkoKSkge1xuICAgIGZvcm0ucmVwb3J0VmFsaWRpdHkoKTtcblxuICAgIGNvbnN0IGludmFsaWQgPSBmb3JtLnF1ZXJ5U2VsZWN0b3I8SFRNTElucHV0RWxlbWVudCB8IEhUTUxTZWxlY3RFbGVtZW50IHwgSFRNTFRleHRBcmVhRWxlbWVudD4oJzppbnZhbGlkJyk7XG5cbiAgICBpZiAoaW52YWxpZCAmJiAhaXNWaXNpYmxlKGludmFsaWQpICYmIGludmFsaWQuZGF0YXNldC52YWxpZGF0aW9uTWVzc2FnZSkge1xuICAgICAgc2ltcGxlQWxlcnQoaW52YWxpZC5kYXRhc2V0LnZhbGlkYXRpb25NZXNzYWdlKTtcbiAgICB9XG5cbiAgICByZXR1cm47XG4gIH1cblxuICBsb2FkaW5nLnZhbHVlID0gdHJ1ZTtcblxuICBmb3JtLnJlcXVlc3RTdWJtaXQoKTtcbn1cblxuZnVuY3Rpb24gaXNWaXNpYmxlKGVsOiBIVE1MSW5wdXRFbGVtZW50IHwgSFRNTFRleHRBcmVhRWxlbWVudCB8IEhUTUxTZWxlY3RFbGVtZW50KSB7XG4gIHJldHVybiAhIShlbC5vZmZzZXRXaWR0aCB8fCBlbC5vZmZzZXRIZWlnaHQgfHwgZWwuZ2V0Q2xpZW50UmVjdHMoKS5sZW5ndGgpO1xufVxuPC9zY3JpcHQ+XG5cbjx0ZW1wbGF0ZT5cblxuICA8ZGl2IGNsYXNzPVwicm93XCI+XG4gICAgPGRpdiBjbGFzcz1cImNvbC1sZy04IGwtY2FydC1wYWdlX19jb250ZW50XCI+XG4gICAgICA8IS0tIEhlYWRlciAtLT5cbiAgICAgIDxoZWFkZXIgY2xhc3M9XCJkLWZsZXggYWxpZ24taXRlbXMtY2VudGVyIGp1c3RpZnktY29udGVudC1iZXR3ZWVuIG1iLTRcIj5cbiAgICAgICAgPGRpdiBjbGFzcz1cImQtZmxleCBhbGlnbi1pdGVtcy1jZW50ZXIgZ2FwLTJcIj5cbiAgICAgICAgICA8aDMgY2xhc3M9XCJtLTBcIj57eyAkbGFuZygnc2hvcGdvLmNhcnQudGl0bGUnKSB9fTwvaDM+XG4gICAgICAgICAgPGRpdiB2LWlmPVwicGFydGlhbENoZWNrb3V0XCIgY2xhc3M9XCJmb3JtLWNoZWNrXCI+XG4gICAgICAgICAgICA8aW5wdXQgaWQ9XCJpbnB1dC10b2dnbGUtYWxsXCIgdHlwZT1cImNoZWNrYm94XCIgY2xhc3M9XCJmb3JtLWNoZWNrLWlucHV0XCJcbiAgICAgICAgICAgICAgcmVmPVwidG9nZ2xlQWxsSW5wdXRcIlxuICAgICAgICAgICAgICBAY2xpY2s9XCJ0b2dnbGVDaGVja2VkXCJcbiAgICAgICAgICAgIC8+XG4gICAgICAgICAgICA8bGFiZWwgZm9yPVwiaW5wdXQtdG9nZ2xlLWFsbFwiIGNsYXNzPVwiZm9ybS1jaGVjay1sYWJlbFwiPlxuICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmNhcnQudG9nZ2xlLmFsbCcpIH19XG4gICAgICAgICAgICA8L2xhYmVsPlxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDxkaXYgdi1pZj1cImxvYWRpbmdcIiBjbGFzcz1cInNwaW5uZXIgc3Bpbm5lci1ib3JkZXItc20gc3Bpbm5lci1ib3JkZXJcIlxuICAgICAgICAgICAgZGF0YS1jbG9haz5cblxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICA8L2Rpdj5cblxuICAgICAgICA8ZGl2PlxuICAgICAgICAgIDxhIGhyZWY9XCJqYXZhc2NyaXB0Oi8vXCJcbiAgICAgICAgICAgIEBjbGljaz1cImNsZWFyQ2FydFwiPlxuICAgICAgICAgICAgPGkgY2xhc3M9XCJmYSBmYS10aW1lc1wiPjwvaT5cbiAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uY2FydC5idXR0b24ucmVtb3ZlLmFsbCcpIH19XG4gICAgICAgICAgPC9hPlxuICAgICAgICA8L2Rpdj5cbiAgICAgIDwvaGVhZGVyPlxuXG4gICAgICA8IS0tIEJvZHkgTG9hZGluZyAtLT5cbiAgICAgIDxkaXYgZGF0YS1sb2FkaW5nPlxuICAgICAgICA8ZGl2IGNsYXNzPVwiZC1mbGV4IHB5LTVcIj5cbiAgICAgICAgICA8c3BhbiBjbGFzcz1cInNwaW5uZXIgc3Bpbm5lci1ncm93IHNwaW5uZXItbGcgbXgtYXV0b1wiPjwvc3Bhbj5cbiAgICAgICAgPC9kaXY+XG4gICAgICA8L2Rpdj5cblxuICAgICAgPGRpdiBjbGFzcz1cImwtY2FydC1kYXRhXCI+XG5cbiAgICAgICAgPCEtLSBDYXJ0IEl0ZW1zIC0tPlxuICAgICAgICA8ZGl2IGNsYXNzPVwibC1jYXJ0LWl0ZW1zXCI+XG4gICAgICAgICAgPENhcnRMaXN0SXRlbSB2LWZvcj1cIihpdGVtLCBpKSBvZiBpdGVtc1wiIDprZXk9XCJpdGVtLmtleVwiXG4gICAgICAgICAgICA6aXRlbVxuICAgICAgICAgICAgOmhhcy1jaGVja2JveD1cInBhcnRpYWxDaGVja291dFwiXG4gICAgICAgICAgICBAcmVtb3ZlLWl0ZW09XCJyZW1vdmVJdGVtKGl0ZW0sIGkpXCJcbiAgICAgICAgICAgIEB1cGRhdGUtcXVhbnRpdHk9XCJ1cGRhdGVRdWFudGl0aWVzKGl0ZW0pXCJcbiAgICAgICAgICAgIEBjaGFuZ2UtaXRlbS1xdWFudGl0eT1cImNoYW5nZUl0ZW1RdWFudGl0eShpdGVtLCAkZXZlbnQpXCJcbiAgICAgICAgICAgIEB1cGRhdGUtY2hlY2tzPVwidXBkYXRlQ2hlY2tzXCJcbiAgICAgICAgICAvPlxuICAgICAgICA8L2Rpdj5cblxuICAgICAgICA8IS0tIEFkZHJlc3NlcyAtLT5cbiAgICAgICAgPGRpdiBjbGFzcz1cIlwiPlxuICAgICAgICAgIDxBZGRyZXNzRm9ybSB0eXBlPVwicGF5bWVudFwiXG4gICAgICAgICAgICA6dGl0bGU9XCIkbGFuZygnc2hvcGdvLmNhcnQucGF5bWVudC5kYXRhLnRpdGxlJylcIlxuICAgICAgICAgICAgOnVzZXI9XCJ1c2VyXCJcbiAgICAgICAgICAgIHYtbW9kZWw9XCJwYXltZW50RGF0YVwiXG4gICAgICAgICAgICByZWY9XCJwYXltZW50Rm9ybVwiXG4gICAgICAgICAgPjwvQWRkcmVzc0Zvcm0+XG4gICAgICAgICAgPEFkZHJlc3NGb3JtIHR5cGU9XCJzaGlwcGluZ1wiXG4gICAgICAgICAgICA6dGl0bGU9XCIkbGFuZygnc2hvcGdvLmNhcnQuc2hpcHBpbmcuZGF0YS50aXRsZScpXCJcbiAgICAgICAgICAgIDp1c2VyPVwidXNlclwiXG4gICAgICAgICAgICB2LW1vZGVsPVwic2hpcHBpbmdEYXRhXCJcbiAgICAgICAgICAgIDpzeW5jLWRhdGE9XCJwYXltZW50RGF0YVwiXG4gICAgICAgICAgICByZWY9XCJzaGlwcGluZ0Zvcm1cIlxuICAgICAgICAgID48L0FkZHJlc3NGb3JtPlxuICAgICAgICA8L2Rpdj5cblxuICAgICAgICA8IS0tIFNoaXBwaW5ncyAtLT5cbiAgICAgICAgPGRpdiBjbGFzcz1cImwtc2hpcHBpbmdzIG1iLTRcIj5cbiAgICAgICAgICA8aDM+e3sgJGxhbmcoJ3Nob3Bnby5jYXJ0LnNoaXBwaW5nLnRpdGxlJykgfX08L2gzPlxuXG4gICAgICAgICAgPGRpdiB2LWlmPVwic2hpcHBpbmdzLmxlbmd0aCA+IDBcIj5cbiAgICAgICAgICAgIDxTaGlwcGluZ0l0ZW0gdi1mb3I9XCIoc2hpcHBpbmcsIGkpIG9mIHNoaXBwaW5nc1wiIDprZXk9XCJzaGlwcGluZy5pZFwiXG4gICAgICAgICAgICAgIHN0eWxlPVwiYW5pbWF0aW9uLWR1cmF0aW9uOiAuMXNcIlxuICAgICAgICAgICAgICA6c2hpcHBpbmc9XCJzaGlwcGluZ1wiXG4gICAgICAgICAgICAgIDppPVwiaVwiXG4gICAgICAgICAgICAgIDpzZWxlY3RlZD1cInNoaXBwaW5nSWQgPT09IHNoaXBwaW5nLmlkXCJcbiAgICAgICAgICAgICAgdi1vbjpzZWxlY3RlZD1cInNoaXBwaW5nSWQgPSBzaGlwcGluZy5pZFwiXG4gICAgICAgICAgICA+XG4gICAgICAgICAgICA8L1NoaXBwaW5nSXRlbT5cbiAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICA8ZGl2IHYtZWxzZSBjbGFzcz1cImNhcmQgYmctbGlnaHRcIj5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkLWJvZHkgcHktNSB0ZXh0LWNlbnRlclwiPlxuICAgICAgICAgICAgICA8dGVtcGxhdGUgdi1pZj1cImxvYWRpbmdcIj5cbiAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cInNwaW5uZXIgc3Bpbm5lci1ib3JkZXJcIj48L3NwYW4+XG4gICAgICAgICAgICAgIDwvdGVtcGxhdGU+XG4gICAgICAgICAgICAgIDx0ZW1wbGF0ZSB2LWVsc2UtaWY9XCJzaGlwcGluZ0RhdGEubG9jYXRpb25JZFwiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uY2FydC50ZXh0Lm5vLnNoaXBwaW5ncycpIH19XG4gICAgICAgICAgICAgIDwvdGVtcGxhdGU+XG4gICAgICAgICAgICAgIDx0ZW1wbGF0ZSB2LWVsc2U+XG4gICAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5jYXJ0LnRleHQuc2VsZWN0LmxvY2F0aW9uLmZpcnN0JykgfX1cbiAgICAgICAgICAgICAgPC90ZW1wbGF0ZT5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICA8L2Rpdj5cblxuICAgICAgICA8IS0tIFBheW1lbnRzIC0tPlxuICAgICAgICA8ZGl2IGNsYXNzPVwibC1wYXltZW50cyBtYi00XCI+XG4gICAgICAgICAgPGgzPnt7ICRsYW5nKCdzaG9wZ28uY2FydC5wYXltZW50LnRpdGxlJykgfX08L2gzPlxuXG4gICAgICAgICAgPGRpdiB2LWlmPVwicGF5bWVudHMubGVuZ3RoID4gMFwiPlxuICAgICAgICAgICAgPFBheW1lbnRJdGVtIHYtZm9yPVwiKHBheW1lbnQsIGkpIG9mIHBheW1lbnRzXCIgOmtleT1cInBheW1lbnQuaWRcIlxuICAgICAgICAgICAgICBzdHlsZT1cImFuaW1hdGlvbi1kdXJhdGlvbjogLjFzXCJcbiAgICAgICAgICAgICAgOnBheW1lbnQ9XCJwYXltZW50XCJcbiAgICAgICAgICAgICAgOmk9XCJpXCJcbiAgICAgICAgICAgICAgOnNlbGVjdGVkPVwicGF5bWVudElkID09PSBwYXltZW50LmlkXCJcbiAgICAgICAgICAgICAgdi1vbjpzZWxlY3RlZD1cInBheW1lbnRJZCA9IHBheW1lbnQuaWRcIlxuICAgICAgICAgICAgPlxuICAgICAgICAgICAgPC9QYXltZW50SXRlbT5cbiAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICA8ZGl2IHYtZWxzZSBjbGFzcz1cImNhcmQgYmctbGlnaHRcIj5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkLWJvZHkgcHktNSB0ZXh0LWNlbnRlclwiPlxuICAgICAgICAgICAgICA8dGVtcGxhdGUgdi1pZj1cImxvYWRpbmdcIj5cbiAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cInNwaW5uZXIgc3Bpbm5lci1ib3JkZXJcIj48L3NwYW4+XG4gICAgICAgICAgICAgIDwvdGVtcGxhdGU+XG4gICAgICAgICAgICAgIDx0ZW1wbGF0ZSB2LWVsc2UtaWY9XCJzaGlwcGluZ0RhdGEuc2hpcHBpbmdJZFwiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uY2FydC50ZXh0Lm5vLnBheW1lbnRzJykgfX1cbiAgICAgICAgICAgICAgPC90ZW1wbGF0ZT5cbiAgICAgICAgICAgICAgPHRlbXBsYXRlIHYtZWxzZT5cbiAgICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmNhcnQudGV4dC5zZWxlY3Quc2hpcHBpbmcuZmlyc3QnKSB9fVxuICAgICAgICAgICAgICA8L3RlbXBsYXRlPlxuICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgIDwvZGl2PlxuXG4gICAgICAgIDwhLS0gTm90ZSAtLT5cbiAgICAgICAgPGRpdiBjbGFzcz1cImwtY2hlY2tvdXQtbm90ZSBjYXJkIG1iLTRcIj5cbiAgICAgICAgICA8ZGl2IGNsYXNzPVwiY2FyZC1ib2R5XCI+XG4gICAgICAgICAgICA8aDUgY2xhc3M9XCJjYXJkLXRpdGxlIG1iLTNcIj5cbiAgICAgICAgICAgICAge3sgJGxhbmcoJ3Nob3Bnby5jYXJ0LmZpZWxkLm5vdGUnKSB9fVxuICAgICAgICAgICAgPC9oNT5cblxuICAgICAgICAgICAgPHRleHRhcmVhIHJvd3M9XCI0XCJcbiAgICAgICAgICAgICAgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIlxuICAgICAgICAgICAgICB2LW1vZGVsPVwibm90ZVwiXG4gICAgICAgICAgICAgIG5hbWU9XCJjaGVja291dFtub3RlXVwiXG4gICAgICAgICAgICAgIDpwbGFjZWhvbGRlcj1cIiRsYW5nKCdzaG9wZ28uY2FydC5maWVsZC5ub3RlLnBsYWNlaG9sZGVyJylcIlxuICAgICAgICAgICAgPjwvdGV4dGFyZWE+XG4gICAgICAgICAgPC9kaXY+XG4gICAgICAgIDwvZGl2PlxuICAgICAgPC9kaXY+XG4gICAgPC9kaXY+XG5cbiAgICA8IS0tIFNpZGViYXIgLS0+XG4gICAgPGRpdiBjbGFzcz1cImNvbC1sZy00IGwtY2FydC1wYWdlX19zaWRlYmFyXCI+XG4gICAgICA8ZGl2IGNsYXNzPVwibC1jYXJ0LXNpZGViYXIgcG9zaXRpb24tc3RpY2t5XCJcbiAgICAgICAgc3R5bGU9XCJ0b3A6IHZhcigtLXNpZGViYXItb2Zmc2V0cy10b3AsIDkwcHgpO1wiXG4gICAgICA+XG4gICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkXCI+XG4gICAgICAgICAgPCEtLSBDb2RlIElucHV0IC0tPlxuICAgICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkLWJvZHkgbC1jYXJ0LWNvdXBvbnMgYm9yZGVyLWJvdHRvbVwiPlxuICAgICAgICAgICAgPGg1Pnt7ICRsYW5nKCdzaG9wZ28uY2FydC5sYWJlbC5kaXNjb3VudC5jb2RlJykgfX08L2g1PlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImQtZmxleCBnYXAtMlwiPlxuICAgICAgICAgICAgICA8aW5wdXQgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiIHYtbW9kZWw9XCJjb2RlXCIgLz5cbiAgICAgICAgICAgICAgPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJidG4gYnRuLXNlY29uZGFyeSB0ZXh0LW5vd3JhcFwiXG4gICAgICAgICAgICAgICAgc3R5bGU9XCJtaW4td2lkdGg6IDEwMHB4XCJcbiAgICAgICAgICAgICAgICBAY2xpY2s9XCJhZGRDb2RlXCJcbiAgICAgICAgICAgICAgICA6ZGlzYWJsZWQ9XCJjb2RlID09PSAnJyB8fCBsb2FkaW5nXCJcbiAgICAgICAgICAgICAgPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uY2FydC5idXR0b24udXNlLmRpc2NvdW50LmNvZGUnKSB9fVxuICAgICAgICAgICAgICA8L2J1dHRvbj5cbiAgICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgICA8IS0tIENvdXBvbnMgLS0+XG4gICAgICAgICAgICA8ZGl2IHYtaWY9XCJjb3Vwb25zLmxlbmd0aFwiIGRhdGEtY2xvYWsgY2xhc3M9XCJsaXN0LWdyb3VwIGxpc3QtZ3JvdXAtZmx1c2ggbXQtNFwiPlxuICAgICAgICAgICAgICA8ZGl2IHYtZm9yPVwiY291cG9uIG9mIGNvdXBvbnNcIiBjbGFzcz1cImxpc3QtZ3JvdXAtaXRlbSBib3JkZXItdG9wIGQtZmxleFwiPlxuICAgICAgICAgICAgICAgIDxkaXY+XG4gICAgICAgICAgICAgICAgICA8ZGl2PlxuICAgICAgICAgICAgICAgICAgICA8c3Ryb25nPlxuICAgICAgICAgICAgICAgICAgICAgIHt7IGNvdXBvbi50aXRsZSB9fVxuICAgICAgICAgICAgICAgICAgICA8L3N0cm9uZz5cbiAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cInNtYWxsIHRleHQtbXV0ZWRcIj5cbiAgICAgICAgICAgICAgICAgICAge3sgY291cG9uLmNvZGUgfX1cbiAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIm1zLWF1dG9cIj5cbiAgICAgICAgICAgICAgICAgIDxhIGhyZWY9XCJqYXZhc2NyaXB0Oi8vXCJcbiAgICAgICAgICAgICAgICAgICAgY2xhc3M9XCJsaW5rLXNlY29uZGFyeVwiXG4gICAgICAgICAgICAgICAgICAgIHYtdG9vbHRpcFxuICAgICAgICAgICAgICAgICAgICB0aXRsZT1cInt7ICRsYW5nKCdzaG9wZ28uY2FydC5idXR0b24ucmVtb3ZlLmRpc2NvdW50LmNvZGUnKSB9fVwiXG4gICAgICAgICAgICAgICAgICAgIEBjbGljaz1cInJlbW92ZUNvZGUoY291cG9uLmlkKVwiPlxuICAgICAgICAgICAgICAgICAgICA8aSBjbGFzcz1cImZhIGZhLXRyYXNoXCI+PC9pPlxuICAgICAgICAgICAgICAgICAgPC9hPlxuICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgPCEtLSBUb3RhbHMgTG9hZGluZyAtLT5cbiAgICAgICAgICA8ZGl2IHYtaWY9XCIhbG9hZGVkXCIgY2xhc3M9XCJjYXJkLWJvZHlcIj5cbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjYXJkLXRleHQgcGxhY2Vob2xkZXItZ2xvdyBkLWZsZXggbXktMlwiPlxuICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cInBsYWNlaG9sZGVyIGNvbC00XCI+PC9zcGFuPlxuICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cInBsYWNlaG9sZGVyIGNvbC0zIG1zLWF1dG9cIj48L3NwYW4+XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgIDwhLS0gVG90YWxzIC0tPlxuICAgICAgICAgIDxkaXYgdi1pZj1cImxvYWRlZFwiIGRhdGEtY2xvYWsgY2xhc3M9XCJjYXJkLWJvZHkgbC1jYXJ0LXRvdGFscyB0ZXh0LWVuZFwiPlxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImwtY2FydC10b3RhbCBkLWZsZXgganVzdGlmeS1jb250ZW50LWJldHdlZW4gZ2FwLTEgbWItMSB3LTEwMFwiPlxuICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwibC1jYXJ0LXRvdGFsX19sYWJlbFwiPlxuICAgICAgICAgICAgICAgIHt7ICRsYW5nKCdzaG9wZ28uY2FydC5sYWJlbC50b3RhbCcpIH19XG4gICAgICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgICAgIDxkaXYgdi1pZj1cInRvdGFscy50b3RhbFwiIGNsYXNzPVwibC1jYXJ0LXRvdGFsX192YWx1ZVwiPlxuICAgICAgICAgICAgICAgIHt7ICRmb3JtYXRQcmljZSh0b3RhbHMudG90YWwucHJpY2UsIHsgY29kZTogdHJ1ZSB9KSB9fVxuICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwibC1jYXJ0LXRvdGFsIGQtZmxleCBqdXN0aWZ5LWNvbnRlbnQtYmV0d2VlbiBnYXAtMSBtYi0xIHctMTAwXCJcbiAgICAgICAgICAgICAgdi1mb3I9XCJ0b3RhbCBvZiBmaWx0ZXJlZFRvdGFsc1wiPlxuICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwibC1jYXJ0LXRvdGFsX19sYWJlbCBkLWZsZXggZ2FwLTJcIj5cbiAgICAgICAgICAgICAgICA8ZGl2PlxuICAgICAgICAgICAgICAgICAge3sgdG90YWwubGFiZWwgfX1cbiAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgICA8ZGl2XG4gICAgICAgICAgICAgICAgICB2LWlmPVwidG90YWwucGFyYW1zLnR5cGUgPT09ICdjb3Vwb24nIHx8IHRvdGFsLnBhcmFtcy5zdWJ0eXBlID09PSAnY29kZSdcIj5cbiAgICAgICAgICAgICAgICAgIDxzbWFsbD4oe3sgdG90YWwucGFyYW1zLmNvZGUgfX0pPC9zbWFsbD5cbiAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImwtY2FydC10b3RhbF9fdmFsdWVcIj5cbiAgICAgICAgICAgICAgICB7eyAkZm9ybWF0UHJpY2UodG90YWwucHJpY2UsIHsgY29kZTogdHJ1ZSB9KSB9fVxuICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICA8L2Rpdj5cblxuICAgICAgICA8IS0tIENoZWNrYm94IC0tPlxuICAgICAgICA8ZGl2IGNsYXNzPVwiY2FyZCBtdC0zIHBvc2l0aW9uLXN0aWNreVwiXG4gICAgICAgICAgc3R5bGU9XCJib3R0b206IDA7XCI+XG4gICAgICAgICAgPGRpdiBjbGFzcz1cImNhcmQtYm9keSBkLWdyaWQgZ2FwLTNcIj5cbiAgICAgICAgICAgIDwhLS0gR3JhbmQgVG90YWwgLS0+XG4gICAgICAgICAgICA8ZGl2IHYtaWY9XCJsb2FkZWRcIlxuICAgICAgICAgICAgICBjbGFzcz1cImwtY2FydC10b3RhbCBkLWZsZXgganVzdGlmeS1jb250ZW50LWJldHdlZW4gZ2FwLTEgdy0xMDAgZnMtNSBmdy1ib2xkXCJcbiAgICAgICAgICAgICAgZGF0YS1jbG9haz5cbiAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImwtY2FydC10b3RhbF9fbGFiZWxcIj5cbiAgICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmNhcnQubGFiZWwuZ3JhbmQudG90YWwnKSB9fVxuICAgICAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgICAgICA8ZGl2IHYtaWY9XCJ0b3RhbHMuZ3JhbmRfdG90YWxcIiBjbGFzcz1cImwtY2FydC10b3RhbF9fdmFsdWUgdGV4dC1lbmRcIj5cbiAgICAgICAgICAgICAgICA8ZGl2PlxuICAgICAgICAgICAgICAgICAge3sgJGZvcm1hdFByaWNlKHRvdGFscy5ncmFuZF90b3RhbC5wcmljZSwgeyBjb2RlOiB0cnVlIH0pIH19XG4gICAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICAgICAgPGRpdiB2LWlmPVwiJGN1cnJlbmN5LmlzU3ViQ3VycmVuY3koKVwiIGNsYXNzPVwibXQtMSBzbWFsbCB0ZXh0LW11dGVkIGZ3LW5vcm1hbFwiPlxuICAgICAgICAgICAgICAgICAgKHt7ICRjdXJyZW5jeS5mb3JtYXRNYWluQ3VycmVuY3kodG90YWxzLmdyYW5kX3RvdGFsLnByaWNlLCB7IGNvZGU6IHRydWUgfSkgfX0pXG4gICAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgIDwhLS0gU2hpcHBpbmcgLyBQYXltZW50IEluZm8gLS0+XG4gICAgICAgICAgICA8ZGl2IHYtaWY9XCJsb2FkZWRcIiBjbGFzcz1cImQtZmxleCBqdXN0aWZ5LWNvbnRlbnQtYmV0d2VlblwiXG4gICAgICAgICAgICAgIGRhdGEtY2xvYWs+XG4gICAgICAgICAgICAgIDxkaXY+XG4gICAgICAgICAgICAgICAgPGkgY2xhc3M9XCJmYSBmYS10cnVja1wiPjwvaT5cbiAgICAgICAgICAgICAgICB7eyBzZWxlY3RlZFNoaXBwaW5nPy50aXRsZSB8fCAkbGFuZygnc2hvcGdvLm1lc3NhZ2Uubm8uc2hpcHBpbmcuc2VsZWN0ZWQnKSB9fVxuICAgICAgICAgICAgICA8L2Rpdj5cblxuICAgICAgICAgICAgICA8ZGl2PlxuICAgICAgICAgICAgICAgIDxpIGNsYXNzPVwiZmEgZmEtY3JlZGl0LWNhcmRcIj48L2k+XG4gICAgICAgICAgICAgICAge3sgc2VsZWN0ZWRQYXltZW50Py50aXRsZSB8fCAkbGFuZygnc2hvcGdvLm1lc3NhZ2Uubm8ucGF5bWVudC5zZWxlY3RlZCcpIH19XG4gICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgIDwhLS0gTG9hZGluZyAtLT5cbiAgICAgICAgICAgIDxkaXYgdi1pZj1cIiFsb2FkZWRcIj5cbiAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImNhcmQtdGV4dCBwbGFjZWhvbGRlci1nbG93IGQtZmxleCBtYi0xXCIgc3R5bGU9XCJoZWlnaHQ6IDEuMjVyZW07XCI+XG4gICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJwbGFjZWhvbGRlciBjb2wtM1wiPjwvc3Bhbj5cbiAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cInBsYWNlaG9sZGVyIGNvbC00IG1zLWF1dG9cIj48L3NwYW4+XG4gICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgPC9kaXY+XG5cbiAgICAgICAgICAgIDwhLS0gTG9hZGluZyAtLT5cbiAgICAgICAgICAgIDxkaXYgdi1pZj1cIiFsb2FkZWRcIj5cbiAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImNhcmQtdGV4dCBwbGFjZWhvbGRlci1nbG93IGQtZmxleFwiPlxuICAgICAgICAgICAgICAgIDxzcGFuIGNsYXNzPVwicGxhY2Vob2xkZXIgY29sLTNcIj48L3NwYW4+XG4gICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJwbGFjZWhvbGRlciBjb2wtMyBtcy1hdXRvXCI+PC9zcGFuPlxuICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgICA8IS0tIENoZWNrb3V0IEJ1dHRvbiAtLT5cbiAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1wcmltYXJ5IGJ0bi1sZ1wiXG4gICAgICAgICAgICAgIDpkaXNhYmxlZD1cImxvYWRpbmcgfHwgIWNhbkNoZWNrb3V0XCJcbiAgICAgICAgICAgICAgQGNsaWNrPVwiY2hlY2tvdXRcIlxuICAgICAgICAgICAgPlxuICAgICAgICAgICAgICA8ZGl2IGRhdGEtY2xvYWs+XG4gICAgICAgICAgICAgICAgPHRlbXBsYXRlIHYtaWY9XCJsb2FkaW5nXCI+XG4gICAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cInNwaW5uZXIgc3Bpbm5lci1ncm93IHNwaW5uZXItZ3Jvdy1zbVwiPjwvc3Bhbj5cbiAgICAgICAgICAgICAgICA8L3RlbXBsYXRlPlxuICAgICAgICAgICAgICAgIDx0ZW1wbGF0ZSB2LWVsc2U+XG4gICAgICAgICAgICAgICAgICB7eyAkbGFuZygnc2hvcGdvLmNhcnQuYnV0dG9uLnByb2Nlc3MuY2hlY2tvdXQnKSB9fVxuICAgICAgICAgICAgICAgIDwvdGVtcGxhdGU+XG4gICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICA8ZGl2IHYtaWY9XCIhbG9hZGluZ1wiIGRhdGEtbG9hZGluZz5cbiAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cInNwaW5uZXIgc3Bpbm5lci1ncm93IHNwaW5uZXItZ3Jvdy1zbVwiPjwvc3Bhbj5cbiAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICA8L2J1dHRvbj5cbiAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgPC9kaXY+XG4gICAgICA8L2Rpdj5cbiAgICAgIDwhLS0gRW5kIFNpZGViYXItLT5cbiAgICA8L2Rpdj5cbiAgPC9kaXY+XG48L3RlbXBsYXRlPlxuXG48c3R5bGUgc2NvcGVkPlxuXG48L3N0eWxlPlxuIiwiaW1wb3J0IHsgdXNlQ3NzSW1wb3J0IH0gZnJvbSAnQHdpbmR3YWxrZXItaW8vdW5pY29ybi1uZXh0JztcbmltcG9ydCB7IGNyZWF0ZUFwcCB9IGZyb20gJ3Z1ZSc7XG5pbXBvcnQgQ2FydEFwcCBmcm9tICd+c2hvcGdvL21vZHVsZXMvY2FydC9DYXJ0QXBwLnZ1ZSc7XG5pbXBvcnQgeyBTaG9wR29QbHVnaW4gfSBmcm9tICd+c2hvcGdvL3Nob3Bnby1wbHVnaW4nO1xuXG5leHBvcnQgZnVuY3Rpb24gaW5pdEFwcChwcm9wczogUmVjb3JkPHN0cmluZywgYW55Pikge1xuICB1c2VDc3NJbXBvcnQoJ0B2dWUtYW5pbWF0ZScpO1xuXG4gIGNvbnN0IGFwcCA9IGNyZWF0ZUFwcChDYXJ0QXBwLCBwcm9wcyk7XG5cbiAgYXBwLnVzZShTaG9wR29QbHVnaW4pO1xuXG4gIHJldHVybiBhcHA7XG59XG5cblxuIl0sIm5hbWVzIjpbInByb3BzIiwiX19wcm9wcyIsIm1vZGVsVmFsdWUiLCJfdXNlTW9kZWwiLCJlbWl0IiwiX19lbWl0IiwidSIsImRlZmF1bHRPcHQiLCJlIiwib3B0IiwibGlzdHMiLCJ2YWx1ZXMiLCJjYW5Nb2RpZnkiLCJsb2FkaW5nIiwiYWpheFVybCIsInJvb3QiLCJzZWxlY3RzIiwiaW5pdCIsInByZXBhcmVWYWx1ZXMiLCJ2YWxzIiwidiIsImxhc3RWYWx1ZSIsImkiLCJsaXN0IiwibG9hZEl0ZW1zIiwidmFsdWVJbml0Iiwic2VsZWN0SW5pdCIsInJlc2V0IiwiZ2V0TGFiZWwiLCJnZXRJZCIsImdldExpc3RWYWx1ZSIsImlzU2VsZWN0ZWQiLCJpdGVtIiwiZ2V0RmluYWxWYWx1ZSIsInZzIiwidjIiLCJnZXRMZXZlbCIsIm9uQ2hhbmdlIiwiZXZlbnQiLCJlbCIsImNoYW5nZUV2ZW50IiwiY29tcG9uZW50QVBJIiwibGFzdEluZGV4IiwicGFyZW50SWQiLCJnZXQiLCIkc2VsZWN0IiwidmFsdWUiLCJwYXRoIiwiaGFuZGxlU291cmNlSXRlbXMiLCJpdGVtc0luIiwiZmluZEZyb21MaXN0IiwiZ2V0UGxhY2Vob2xkZXIiLCJfX2V4cG9zZSIsIl9ob2lzdGVkXzEiLCJfaG9pc3RlZF80IiwiX2hvaXN0ZWRfNiIsIl9vcGVuQmxvY2siLCJfY3JlYXRlRWxlbWVudEJsb2NrIiwiX0ZyYWdtZW50IiwiX3JlbmRlckxpc3QiLCIkc2V0dXAiLCJpdGVtcyIsIl9ub3JtYWxpemVDbGFzcyIsIl9jcmVhdGVFbGVtZW50Vk5vZGUiLCJfdG9EaXNwbGF5U3RyaW5nIiwiX2hvaXN0ZWRfMyIsIl9jYWNoZSIsIl9jcmVhdGVUZXh0Vk5vZGUiLCIkZXZlbnQiLCJfaG9pc3RlZF83IiwiX2hvaXN0ZWRfOCIsImRlZmF1bHRBZGRyZXNzIiwiYWRkcmVzc0xvYWRpbmciLCJjdXJyZW50U3RhdGUiLCJsb2NhdGlvblBhdGgiLCJjYXNjYWRlT3B0aW9ucyIsInVkYXRhIiwic2VsZWN0IiwiYWRkcmVzc2VzIiwiY3VycmVudEFkZHJlc3NIYXNoIiwic3luYyIsImFkZHJlc3NTZWxlY3RpbmciLCJmb3JtIiwibG9jYXRpb25TZWxlY3RvciIsIm1vZGFsRWxlbWVudCIsImZpbmRNeUFkZHJlc3MiLCJhZGRycyIsImZpcnN0QWRkcmVzcyIsInByZXBhcmVBZGRyZXNzRGF0YSIsImFkZHJlc3MiLCJhZGRyIiwic2V0QWRkcmVzc1RvRGF0YSIsInVwZGF0ZUxvY2F0aW9uTGlzdCIsInZhbGlkYXRlIiwicGFzcyIsImlucHV0cyIsImlucHV0Iiwic3luY0FkZHJlc3NGcm9tT3V0c2lkZSIsInNob3dTYXZlQnV0dG9uIiwiTWQ1IiwibG9jYXRpb25DaGFuZ2VkIiwiYnVpbGRJbnB1dElkIiwibmFtZSIsImJ1aWxkSW5wdXROYW1lIiwiY3JlYXRlTmV3Iiwib3BlbkFkZHJlc3NTZWxlY3RvciIsInNlbGVjdEFkZHJlc3MiLCJkYXRhIiwiX2hvaXN0ZWRfMiIsIl9ob2lzdGVkXzUiLCJfaG9pc3RlZF8xMyIsIl9ob2lzdGVkXzE0IiwiX2hvaXN0ZWRfMTYiLCJfaG9pc3RlZF8xOCIsIl9ob2lzdGVkXzIwIiwiX2hvaXN0ZWRfMjIiLCJfaG9pc3RlZF8yNCIsIl9ob2lzdGVkXzI2IiwiX2hvaXN0ZWRfMjgiLCJfaG9pc3RlZF8zMCIsIl9ob2lzdGVkXzMyIiwiX2hvaXN0ZWRfMzQiLCJfaG9pc3RlZF8zNiIsIl9ob2lzdGVkXzM4IiwiX2hvaXN0ZWRfNDAiLCJfaG9pc3RlZF80MiIsIl9ob2lzdGVkXzQzIiwiX2hvaXN0ZWRfNDUiLCJfaG9pc3RlZF80NyIsIl9ob2lzdGVkXzQ5IiwiX2hvaXN0ZWRfNTEiLCJfaG9pc3RlZF81MyIsIl9ob2lzdGVkXzU1IiwiJHByb3BzIiwiX2N0eCIsIl9ob2lzdGVkXzkiLCJfY3JlYXRlQ29tbWVudFZOb2RlIiwiX2NyZWF0ZVZOb2RlIiwiX1RyYW5zaXRpb24iLCJfaG9pc3RlZF8xMCIsIl9ob2lzdGVkXzExIiwiX2hvaXN0ZWRfMTIiLCJfaG9pc3RlZF8xNSIsIl93aXRoRGlyZWN0aXZlcyIsIl9ob2lzdGVkXzE3IiwiX3ZNb2RlbFRleHQiLCJfaG9pc3RlZF8xOSIsIl9ob2lzdGVkXzIxIiwiX2hvaXN0ZWRfMjMiLCJfaG9pc3RlZF8yNSIsIl9ob2lzdGVkXzI3IiwiX2hvaXN0ZWRfMjkiLCJfaG9pc3RlZF8zMSIsIl9ob2lzdGVkXzMzIiwiX2hvaXN0ZWRfMzUiLCJfaG9pc3RlZF8zNyIsIl9ob2lzdGVkXzM5IiwiX2hvaXN0ZWRfNDEiLCJfaG9pc3RlZF80NCIsIl9ob2lzdGVkXzQ2IiwiX2hvaXN0ZWRfNDgiLCJfaG9pc3RlZF81MCIsIl9ob2lzdGVkXzUyIiwiX2hvaXN0ZWRfNTQiLCJfaG9pc3RlZF81NiIsIl9ob2lzdGVkXzU3IiwiX2hvaXN0ZWRfNTgiLCJfdk1vZGVsQ2hlY2tib3giLCJlbWl0cyIsInJlbW92ZUl0ZW0iLCJ1cGRhdGVRdWFudGl0aWVzIiwiY2hhbmdlSXRlbVF1YW50aXR5IiwiZGVsdGEiLCJ1cGRhdGVDaGVja3MiLCJhdHRhY2htZW50IiwidWlkUmVmIiwic2VsZWN0ZWRSZWYiLCJpbWFnZURlZmF1bHQiLCJvcHRpb25MYXlvdXQiLCJvblNlbGVjdGVkIiwibG9hZGVkIiwidG90YWxzIiwiY291cG9ucyIsInBheW1lbnRJZCIsInBheW1lbnREYXRhIiwic2hpcHBpbmdJZCIsInNoaXBwaW5nRGF0YSIsInNoaXBwaW5ncyIsInBheW1lbnRzIiwicmVjZWlwdERhdGEiLCJjb2RlIiwibm90ZSIsInBhcnRpYWxDaGVja291dCIsInRvZ2dsZUFsbElucHV0IiwibG9hZGluZ1N0YWNrIiwic3RhY2siLCJsZW5ndGgiLCJwb3BMb2FkaW5nIiwid2FpdCIsImFmdGVySXRlbXNDaGFuZ2VkIiwidXBkYXRlU2hpcHBpbmdzIiwiaXNBeGlvc0Vycm9yIiwicmVzIiwic2V0Q2FydERhdGEiLCJsb2FkU2hpcHBpbmdzIiwidXBkYXRlVG9nZ2xlQWxsIiwiaXRlbUNoZWNrcyIsImNoZWNrcyIsImNoZWNrZWQiLCJ1bmNoZWNrcyIsInRvZ2dsZUNoZWNrZWQiLCJwb3N0IiwiY2FsY05hdkFuZFN0aWNreVNpZGViYXIiLCJvZmZzZXRzIiwibmF2YmFyIiwidG9wIiwiZGVsIiwiY2xlYXJDYXJ0IiwicHV0IiwiYWRkQ29kZSIsInJlbW92ZUNvZGUiLCJpZCIsImZpbHRlcmVkVG90YWxzIiwiX3RvdGFscyIsInRvdGFsIiwic2VsZWN0ZWRTaGlwcGluZyIsImxvYWRQYXltZW50cyIsInNlbGVjdGVkUGF5bWVudCIsInBheW1lbnQiLCJjYW5DaGVja291dCIsInNoaXBwaW5nRm9ybSIsInBheW1lbnRGb3JtIiwiY2hlY2tvdXQiLCJpbnZhbGlkIiwiaXNWaXNpYmxlIiwiX2NyZWF0ZUJsb2NrIiwic2hpcHBpbmciLCJjb3Vwb24iLCJhcHAiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQThCQSxVQUFNQSxJQUFRQyxHQU1SQyxJQUFhQyxTQUFrQkYsR0FBQSxZQUFvQixHQUduREcsSUFBT0MsR0FLUEMsSUFBSyxXQUFtQixLQUFNLE9BQWUsR0FHN0NDLElBQTZCO0FBQUEsTUFDakMsSUFBSSxxQkFBcUJELEtBQUtBLEVBQUUsTUFBTUEsRUFBRSxJQUFBLElBQVEsT0FBTyxLQUFLLE9BQUEsQ0FBUTtBQUFBLE1BQ3BFLFVBQVU7QUFBQSxNQUNWLFlBQVk7QUFBQSxNQUNaLGFBQWE7QUFBQSxNQUNiLGNBQWMsQ0FBQTtBQUFBLE1BQ2QsU0FBUztBQUFBLE1BQ1QsZ0JBQWdCO0FBQUEsTUFDaEIsUUFBUSxDQUFBO0FBQUEsTUFDUixRQUFRLENBQUE7QUFBQSxNQUNSLFlBQVk7QUFBQSxNQUNaLFlBQVk7QUFBQSxNQUNaLFVBQVU7QUFBQSxNQUNWLFVBQVU7QUFBQSxNQUNWLFlBQVk7QUFBQSxNQUNaLFdBQVc7QUFBQSxNQUNYLFlBQVk7QUFBQSxNQUNaLG9CQUFvQjtBQUFBLE1BQ3BCLGNBQWM7QUFBQSxNQUNkLGNBQWMsQ0FBQ0UsTUFBbUI7QUFBQSxNQUFDO0FBQUEsTUFDbkMsVUFBVSxDQUFDQSxNQUFhO0FBQUEsTUFBQztBQUFBLE1BQ3pCLGFBQWEsQ0FBQ0EsTUFBYTtBQUFBLE1BQUM7QUFBQSxJQUFBLEdBSXhCQyxJQUFNLFNBQXlCLE9BQU8sT0FBTyxJQUFJRixHQUFZUCxFQUFNLFdBQVcsQ0FBQSxDQUFFLENBQUMsR0FDakZVLElBQVEs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
