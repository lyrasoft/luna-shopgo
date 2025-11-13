import { _ as _export_sfc, r as resolveVueComponent } from "./_plugin-vue_export-helper.js";
import { useHttpClient, __, data, route, useTomSelect, uid, useInject, slideDown, slideUp, useQueue, useStack, debounce, simpleAlert, useCssImport } from "@windwalker-io/unicorn-next";
import { defineComponent, mergeModels, useModel, reactive, ref, watch, onMounted, nextTick, createElementBlock, openBlock, createTextVNode, createElementVNode, Fragment, renderList, normalizeClass, toDisplayString, useTemplateRef, computed, createVNode, createCommentVNode, withDirectives, vModelCheckbox, Transition, withCtx, vModelText, renderSlot, createBlock, resolveDynamicComponent, mergeProps, provide, getCurrentInstance, createApp } from "vue";
import { Modal } from "bootstrap";
import { h } from "./index.es.js";
import { vTooltip, ShopGoPlugin } from "../index.js";
const _sfc_main$a = /* @__PURE__ */ defineComponent({
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
  setup(a, { expose: e, emit: n }) {
    const t = a, d = useModel(a, "modelValue"), u = n, o = globalThis.u || window.u, r = {
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
    }, l = reactive(Object.assign({}, r, t.options || {})), g = ref([]), p = ref([]), C = ref(!0), S = ref(!1), v = ref(l.ajaxUrl || ""), x = ref(), y = ref([]);
    function B() {
      C.value = !l.readonly && !l.disabled, v.value = l.ajaxUrl || "";
    }
    async function D() {
      if (S.value)
        return;
      S.value = !0, g.value = [];
      let c = [...(d.value || []).slice().map((N) => String(N))];
      p.value = [...c], c.length === 0 ? c = [null] : c.unshift(null);
      let b = null;
      for (let N in c) {
        const R = c[N], M = await T(R);
        M && M.length > 0 && g.value.push(M), b = R;
      }
      w(x.value, b, c), S.value = !1, await nextTick(), y.value && y.value.length > 0 && U(y.value[0]);
    }
    function $() {
      D();
    }
    function A(i) {
      return l.labels[i] || `Level ${i + 1}`;
    }
    function j(i) {
      return `${l.id}__level-${i}`;
    }
    function P(i) {
      return p.value[i] || "";
    }
    function Q(i, c) {
      return String(P(i)) === String(c[l.valueField]);
    }
    function H() {
      const i = p.value.slice();
      if (i.length === 0)
        return l.defaultValue;
      const c = i.filter((b) => b != null).filter((b) => b !== "").pop();
      return c === void 0 ? l.defaultValue : c;
    }
    function E() {
      return p.value.length;
    }
    async function I(i, c) {
      const b = c.target;
      p.value[i] = b.value;
      try {
        l.onChange(c);
      } catch {
      }
      c.stopPropagation();
      const N = new CustomEvent("change", {
        detail: {
          el: b,
          component: O,
          value: b.value,
          path: p.value
        }
      });
      if (x.value?.dispatchEvent(N), d.value = p.value, u("change", N), b.value === "") {
        g.value.splice(i + 1), p.value.splice(i + 1);
        return;
      }
      const R = await T(b.value);
      if (g.value.splice(i + 1), p.value.splice(i + 1), R && R.length > 0) {
        g.value.push(R), await nextTick();
        const M = y.value.length - 1;
        y.value && y.value[M] && U(y.value[M]);
      }
    }
    async function T(i, c) {
      const { get: b } = await useHttpClient();
      return (await b(
        v.value,
        {
          params: {
            [l.ajaxValueField]: i,
            self: l.ignoreSelf || null
          }
        }
      )).data.data;
    }
    function w(i, c, b) {
      const N = new CustomEvent("value.init", {
        detail: {
          el: i,
          component: O,
          value: c,
          path: b
        }
      });
      x.value?.dispatchEvent(N);
    }
    function U(i) {
      const c = new CustomEvent("select.init", {
        detail: {
          el: i,
          component: O
        }
      });
      l.onSelectInit(c), x.value?.dispatchEvent(c);
    }
    function q(i) {
      return i.map((c) => ({
        [l.valueField]: c.value[l.valueField],
        [l.textField]: c.value[l.textField],
        children: c.children
      })).filter((c) => l.ignoreSelf ? c[l.valueField] != l.ignoreSelf : c);
    }
    function F(i, c) {
      return (i || []).filter((N) => N[l.valueField] == c).shift();
    }
    function L(i) {
      return l.placeholders && l.placeholders[i] ? l.placeholders[i] : l.placeholder;
    }
    const O = {
      opt: l,
      lists: g,
      values: p,
      getFinalValue: H,
      getLevel: E,
      getLabel: A,
      getId: j,
      getListValue: P,
      isSelected: Q,
      onChange: I,
      loadItems: T,
      valueInit: w,
      selectInit: U,
      handleSourceItems: q,
      findFromList: F,
      getPlaceholder: L
    };
    watch(d, (i) => {
      (!i || i.length === 0) && $();
    }, { deep: !0 }), onMounted(async () => {
      B(), await D();
    }), e({
      prepareValues: D
    });
    const m = { props: t, modelValue: d, emit: u, u: o, defaultOpt: r, opt: l, lists: g, values: p, canModify: C, loading: S, ajaxUrl: v, root: x, selects: y, init: B, prepareValues: D, reset: $, getLabel: A, getId: j, getListValue: P, isSelected: Q, getFinalValue: H, getLevel: E, onChange: I, loadItems: T, valueInit: w, selectInit: U, handleSourceItems: q, findFromList: F, getPlaceholder: L, componentAPI: O };
    return Object.defineProperty(m, "__isScriptSetup", { enumerable: !1, value: !0 }), m;
  }
}), _hoisted_1$9 = { ref: "root" }, _hoisted_2$8 = ["data-level"], _hoisted_3$8 = ["for"], _hoisted_4$8 = { class: "col c-cascade-select__input" }, _hoisted_5$8 = ["id", "disabled", "onChange"], _hoisted_6$6 = { value: "" }, _hoisted_7$6 = ["value", "selected"], _hoisted_8$6 = ["name", "value"];
function _sfc_render$a(a, e, n, t, d, u) {
  return openBlock(), createElementBlock("div", _hoisted_1$9, [
    (openBlock(!0), createElementBlock(Fragment, null, renderList(t.lists, (o, r) => (openBlock(), createElementBlock("div", {
      class: normalizeClass(["form-group row mb-2", [t.opt.horizontal ? t.opt.horizontalColWidth || "col" : ""]]),
      key: o,
      "data-level": r
    }, [
      createElementVNode("label", {
        for: t.getId(r),
        class: normalizeClass(["c-cascade-select__label mb-2", t.opt.labelWidth || "col-md-3"])
      }, toDisplayString(t.getLabel(r)), 11, _hoisted_3$8),
      e[1] || (e[1] = createTextVNode()),
      createElementVNode("div", _hoisted_4$8, [
        createElementVNode("select", {
          id: t.getId(r),
          disabled: !t.canModify,
          class: "form-select custom-select",
          ref_for: !0,
          ref: (l) => t.selects[r] = l,
          onChange: (l) => t.onChange(r, l)
        }, [
          createElementVNode("option", _hoisted_6$6, toDisplayString(t.getPlaceholder(r)), 1),
          e[0] || (e[0] = createTextVNode()),
          (openBlock(!0), createElementBlock(Fragment, null, renderList(o, (l) => (openBlock(), createElementBlock("option", {
            value: l[t.opt.valueField],
            key: l[t.opt.valueField],
            selected: t.isSelected(r, l)
          }, toDisplayString(l[t.opt.textField]), 9, _hoisted_7$6))), 128))
        ], 40, _hoisted_5$8)
      ])
    ], 10, _hoisted_2$8))), 128)),
    e[2] || (e[2] = createTextVNode()),
    createElementVNode("input", {
      name: t.props.name,
      type: "hidden",
      value: t.getFinalValue()
    }, null, 8, _hoisted_8$6)
  ], 512);
}
const CascadeSelect__Tmp72230 = /* @__PURE__ */ _export_sfc(_sfc_main$a, [["render", _sfc_render$a], ["__file", "CascadeSelect.vue"]]), _sfc_main$9 = /* @__PURE__ */ defineComponent({
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
  setup(a, { expose: e, emit: n }) {
    const t = resolveVueComponent("CascadeSelect", CascadeSelect__Tmp72230), d = a, u = {
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
    }, o = n, r = useModel(a, "modelValue"), l = ref(!1), g = ref(d.syncData == null ? "initializing" : "sync"), p = ref([]), C = {
      ajaxUrl: route("@address_ajax/locationOptions"),
      labels: data("location.labels") || [],
      placeholder: __("unicorn.select.placeholder"),
      onSelectInit(m) {
        const i = m.detail.el;
        useTomSelect(i);
      }
    };
    r.value = Object.assign(
      {},
      u,
      {
        firstName: d.user?.firstname || "",
        lastName: d.user?.lastname || "",
        name: d.user?.name || ""
      },
      r.value
    );
    const S = ref([]), v = ref(""), x = ref(d.syncData != null), y = ref(!1), B = ref(), D = ref(), $ = useTemplateRef("modal");
    (!r.value || Object.keys(r.value).length === 0) && T().then((m) => {
      const i = m[0] || null;
      i && (r.value = q(i));
    }), onMounted(async () => {
      if (x.value)
        g.value = "form";
      else {
        const m = await T();
        let i;
        r.value.id && (i = m.find((c) => String(c.id) === String(r.value.id))), i || (i = m[0]), i && F(i), g.value = "selected";
      }
      L();
    });
    function A() {
      if (x.value)
        return !0;
      if (B.value) {
        let m = !0;
        const i = B.value.querySelectorAll("input,textarea,select");
        for (const c of i)
          if (!c.checkValidity()) {
            m = m && !1, c.reportValidity();
            break;
          }
        return o("validated", m), m;
      }
      return !0;
    }
    watch(() => d.syncData, async () => {
      x.value && d.syncData && j();
    }, { deep: !0, immediate: !0 }), watch(x, (m) => {
      m ? d.syncData || d.syncData && (g.value = "sync", j()) : (g.value = "form", r.value.id = void 0, r.value.addressId = void 0);
    });
    function j() {
      r.value = JSON.parse(JSON.stringify(d.syncData || {}));
    }
    const P = computed(() => v.value !== h.hashStr(JSON.stringify(r.value)));
    function Q(m) {
      m.detail && (r.value.locationId = m.detail.value, p.value = m.detail.path);
    }
    function H(m) {
      return `input-${d.type}-${m}`;
    }
    function E(m) {
      return `checkout[${d.type}_data][${m}]`;
    }
    function I() {
      g.value = "new", p.value = [], r.value = Object.assign({}, u);
    }
    async function T() {
      const { get: m } = await useHttpClient();
      return (await m("@address_ajax/myAddresses")).data.data;
    }
    async function w() {
      l.value = !0, Modal.getOrCreateInstance($.value).show();
      try {
        S.value = await T();
      } finally {
        l.value = !1;
      }
    }
    async function U(m) {
      y.value = !0, r.value = Object.assign(
        {},
        u,
        m
      ), await F(m), v.value = h.hashStr(JSON.stringify(r.value)), await L(), y.value = !1;
    }
    function q(m) {
      return m.locationPath = m.locationPath.map((i) => String(i)), m.addressId = String(m.id), m;
    }
    async function F(m) {
      const i = Object.assign(
        {},
        u,
        m
      );
      r.value = q(i), Modal.getOrCreateInstance($.value).hide(), await L();
    }
    async function L() {
      p.value = r.value.locationPath || [], await nextTick(), await D.value?.prepareValues();
    }
    e({
      validate: A
    });
    const O = { props: d, defaultAddress: u, emit: o, modelValue: r, addressLoading: l, currentState: g, locationPath: p, cascadeOptions: C, addresses: S, currentAddressHash: v, sync: x, addressSelecting: y, form: B, locationSelector: D, modalElement: $, validate: A, syncAddressFromOutside: j, showSaveButton: P, locationChanged: Q, buildInputId: H, buildInputName: E, createNew: I, findMyAddress: T, openAddressSelector: w, selectAddress: U, prepareAddressData: q, setAddressToData: F, updateLocationList: L, CascadeSelect: t };
    return Object.defineProperty(O, "__isScriptSetup", { enumerable: !1, value: !0 }), O;
  }
}), _hoisted_1$8 = { class: "card mb-4" }, _hoisted_2$7 = { class: "card-body" }, _hoisted_3$7 = { class: "card-title d-flex justify-content-between" }, _hoisted_4$7 = { class: "d-flex align-items-center gap-3" }, _hoisted_5$7 = { class: "m-0" }, _hoisted_6$5 = {
  key: 0,
  class: "form-check"
}, _hoisted_7$5 = ["for"], _hoisted_8$5 = ["id", "name"], _hoisted_9$5 = { key: 0 }, _hoisted_10$5 = { key: 0 }, _hoisted_11$5 = {
  key: 1,
  class: "mt-3",
  style: { "animation-duration": ".3s" }
}, _hoisted_12$5 = {
  key: 2,
  class: "row mt-3",
  style: { "animation-duration": ".3s" },
  ref: "form"
}, _hoisted_13$5 = { class: "col-lg-5" }, _hoisted_14$4 = { class: "form-group row mb-4" }, _hoisted_15$4 = ["for"], _hoisted_16$3 = { class: "col-9" }, _hoisted_17$2 = ["id", "name"], _hoisted_18$2 = { class: "form-group row mb-4" }, _hoisted_19$2 = ["for"], _hoisted_20$2 = { class: "col-9" }, _hoisted_21$2 = ["id", "name"], _hoisted_22$2 = { class: "form-group row mb-4" }, _hoisted_23$2 = ["for"], _hoisted_24$2 = { class: "col-9" }, _hoisted_25$2 = ["id", "name"], _hoisted_26$2 = { class: "form-group row mb-4" }, _hoisted_27$2 = ["for"], _hoisted_28$2 = { class: "col-9" }, _hoisted_29$2 = ["id", "name"], _hoisted_30$2 = { class: "form-group row mb-4" }, _hoisted_31$1 = ["for"], _hoisted_32$1 = { class: "col-9" }, _hoisted_33$1 = ["id", "name"], _hoisted_34$1 = { class: "form-group row mb-4" }, _hoisted_35$1 = ["for"], _hoisted_36$1 = { class: "col-9" }, _hoisted_37$1 = ["id", "name"], _hoisted_38$1 = { class: "form-group row mb-4" }, _hoisted_39 = ["for"], _hoisted_40 = { class: "col-9" }, _hoisted_41 = ["id", "name"], _hoisted_42 = { class: "col-lg-7 mb-4 mb-lg-0" }, _hoisted_43 = { class: "form-group mb-4" }, _hoisted_44 = ["for"], _hoisted_45 = { class: "form-group row mb-4" }, _hoisted_46 = ["for"], _hoisted_47 = { class: "col-9" }, _hoisted_48 = ["id", "name"], _hoisted_49 = { class: "form-group row mb-4" }, _hoisted_50 = ["for"], _hoisted_51 = { class: "col-9" }, _hoisted_52 = ["id", "name"], _hoisted_53 = { class: "form-group row mb-4" }, _hoisted_54 = ["for"], _hoisted_55 = { class: "col-9" }, _hoisted_56 = ["id", "name"], _hoisted_57 = {
  key: 0,
  class: "form-group row mb-4"
}, _hoisted_58 = ["for"], _hoisted_59 = { class: "col-9" }, _hoisted_60 = ["id", "name"], _hoisted_61 = { class: "d-none" }, _hoisted_62 = ["id", "name"], _hoisted_63 = ["id"], _hoisted_64 = {
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
function _sfc_render$9(a, e, n, t, d, u) {
  return openBlock(), createElementBlock("div", _hoisted_1$8, [
    createElementVNode("div", _hoisted_2$7, [
      createElementVNode("div", _hoisted_3$7, [
        createElementVNode("div", _hoisted_4$7, [
          createElementVNode("h4", _hoisted_5$7, toDisplayString(n.title), 1),
          e[15] || (e[15] = createTextVNode()),
          n.syncData ? (openBlock(), createElementBlock("div", _hoisted_6$5, [
            createElementVNode("label", {
              for: `input-${n.type}-sync`,
              class: "form-check-label"
            }, toDisplayString(n.syncLabel || a.$lang("shopgo.cart.address.form.same.with.buyer")), 9, _hoisted_7$5),
            e[14] || (e[14] = createTextVNode()),
            withDirectives(createElementVNode("input", {
              type: "checkbox",
              "onUpdate:modelValue": e[0] || (e[0] = (o) => t.sync = o),
              id: `input-${n.type}-sync`,
              name: t.buildInputName("sync"),
              class: "form-check-input",
              value: "1"
            }, null, 8, _hoisted_8$5), [
              [vModelCheckbox, t.sync]
            ])
          ])) : createCommentVNode("", !0)
        ]),
        e[17] || (e[17] = createTextVNode()),
        n.user && !t.sync ? (openBlock(), createElementBlock("div", _hoisted_9$5, [
          createElementVNode("button", {
            type: "button",
            class: "btn btn-outline-success btn-sm",
            style: { "min-width": "100px" },
            onClick: t.createNew
          }, toDisplayString(a.$lang("shopgo.cart.address.form.new.address")), 1),
          e[16] || (e[16] = createTextVNode()),
          createElementVNode("button", {
            type: "button",
            class: "btn btn-outline-primary btn-sm",
            style: { "min-width": "100px" },
            onClick: t.openAddressSelector
          }, toDisplayString(a.$lang("shopgo.cart.address.form.select")), 1)
        ])) : createCommentVNode("", !0)
      ]),
      e[42] || (e[42] = createTextVNode()),
      createVNode(Transition, {
        name: "fade",
        mode: "out-in"
      }, {
        default: withCtx(() => [
          t.currentState === "initializing" ? (openBlock(), createElementBlock("div", _hoisted_10$5, [...e[18] || (e[18] = [
            createElementVNode("div", { class: "placeholder-glow" }, [
              createElementVNode("span", { class: "placeholder col-7" })
            ], -1)
          ])])) : !t.sync && t.modelValue.addressId ? (openBlock(), createElementBlock("div", _hoisted_11$5, toDisplayString(t.modelValue.formatted), 1)) : !t.sync && !t.modelValue.addressId ? (openBlock(), createElementBlock("div", _hoisted_12$5, [
            createElementVNode("div", _hoisted_13$5, [
              createElementVNode("div", _hoisted_14$4, [
                createElementVNode("label", {
                  for: t.buildInputId("firstname"),
                  class: "form-label col-3"
                }, toDisplayString(a.$lang("shopgo.address.field.firstname")), 9, _hoisted_15$4),
                e[19] || (e[19] = createTextVNode()),
                createElementVNode("div", _hoisted_16$3, [
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
                }, toDisplayString(a.$lang("shopgo.address.field.lastname")), 9, _hoisted_19$2),
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
                }, toDisplayString(a.$lang("shopgo.address.field.email")), 9, _hoisted_23$2),
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
                }, toDisplayString(a.$lang("shopgo.address.field.phone")), 9, _hoisted_27$2),
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
                }, toDisplayString(a.$lang("shopgo.address.field.mobile")), 9, _hoisted_31$1),
                e[23] || (e[23] = createTextVNode()),
                createElementVNode("div", _hoisted_32$1, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("mobile"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("mobile"),
                    required: "",
                    "onUpdate:modelValue": e[5] || (e[5] = (o) => t.modelValue.mobile = o)
                  }, null, 8, _hoisted_33$1), [
                    [vModelText, t.modelValue.mobile]
                  ])
                ])
              ]),
              e[30] || (e[30] = createTextVNode()),
              createElementVNode("div", _hoisted_34$1, [
                createElementVNode("label", {
                  for: t.buildInputId("company"),
                  class: "form-label col-3"
                }, toDisplayString(a.$lang("shopgo.address.field.company")), 9, _hoisted_35$1),
                e[24] || (e[24] = createTextVNode()),
                createElementVNode("div", _hoisted_36$1, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("company"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("company"),
                    "onUpdate:modelValue": e[6] || (e[6] = (o) => t.modelValue.company = o)
                  }, null, 8, _hoisted_37$1), [
                    [vModelText, t.modelValue.company]
                  ])
                ])
              ]),
              e[31] || (e[31] = createTextVNode()),
              createElementVNode("div", _hoisted_38$1, [
                createElementVNode("label", {
                  for: t.buildInputId("vat"),
                  class: "form-label col-3"
                }, toDisplayString(a.$lang("shopgo.address.field.vat")), 9, _hoisted_39),
                e[25] || (e[25] = createTextVNode()),
                createElementVNode("div", _hoisted_40, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("vat"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("vat"),
                    "onUpdate:modelValue": e[7] || (e[7] = (o) => t.modelValue.vat = o)
                  }, null, 8, _hoisted_41), [
                    [vModelText, t.modelValue.vat]
                  ])
                ])
              ])
            ]),
            e[41] || (e[41] = createTextVNode()),
            createElementVNode("div", _hoisted_42, [
              createElementVNode("div", _hoisted_43, [
                createElementVNode("label", {
                  for: t.buildInputId("country"),
                  class: "form-label"
                }, toDisplayString(a.$lang("shopgo.address.field.country")), 9, _hoisted_44),
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
              createElementVNode("div", _hoisted_45, [
                createElementVNode("label", {
                  for: t.buildInputId("postcode"),
                  class: "form-label col-3"
                }, toDisplayString(a.$lang("shopgo.address.field.postcode")), 9, _hoisted_46),
                e[33] || (e[33] = createTextVNode()),
                createElementVNode("div", _hoisted_47, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("postcode"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("postcode"),
                    "onUpdate:modelValue": e[9] || (e[9] = (o) => t.modelValue.postcode = o),
                    maxlength: "10"
                  }, null, 8, _hoisted_48), [
                    [vModelText, t.modelValue.postcode]
                  ])
                ])
              ]),
              e[38] || (e[38] = createTextVNode()),
              createElementVNode("div", _hoisted_49, [
                createElementVNode("label", {
                  for: t.buildInputId("address1"),
                  class: "form-label col-3"
                }, toDisplayString(a.$lang("shopgo.address.field.address1")), 9, _hoisted_50),
                e[34] || (e[34] = createTextVNode()),
                createElementVNode("div", _hoisted_51, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("address1"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("address1"),
                    required: "",
                    "onUpdate:modelValue": e[10] || (e[10] = (o) => t.modelValue.address1 = o)
                  }, null, 8, _hoisted_52), [
                    [vModelText, t.modelValue.address1]
                  ])
                ])
              ]),
              e[39] || (e[39] = createTextVNode()),
              createElementVNode("div", _hoisted_53, [
                createElementVNode("label", {
                  for: t.buildInputId("address2"),
                  class: "form-label col-3"
                }, toDisplayString(a.$lang("shopgo.address.field.address2")), 9, _hoisted_54),
                e[35] || (e[35] = createTextVNode()),
                createElementVNode("div", _hoisted_55, [
                  withDirectives(createElementVNode("input", {
                    id: t.buildInputId("address2"),
                    type: "text",
                    class: "form-control",
                    name: t.buildInputName("address2"),
                    "onUpdate:modelValue": e[11] || (e[11] = (o) => t.modelValue.address2 = o)
                  }, null, 8, _hoisted_56), [
                    [vModelText, t.modelValue.address2]
                  ])
                ])
              ]),
              e[40] || (e[40] = createTextVNode()),
              t.showSaveButton ? (openBlock(), createElementBlock("div", _hoisted_57, [
                createElementVNode("label", {
                  for: t.buildInputId("save"),
                  class: "form-label col-3"
                }, toDisplayString(a.$lang("shopgo.cart.address.form.save.for.next")), 9, _hoisted_58),
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
            createElementVNode("h4", _hoisted_67, toDisplayString(a.$lang("shopgo.cart.address.form.modal.title")), 1),
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
                  createElementVNode("span", _hoisted_71, toDisplayString(a.$lang("shopgo.cart.address.form.button.select")), 1)
                ])
              ], 8, _hoisted_70))), 128))
            ])) : (openBlock(), createElementBlock("div", _hoisted_72, [
              t.addressLoading ? (openBlock(), createElementBlock("span", _hoisted_73)) : (openBlock(), createElementBlock(Fragment, { key: 1 }, [
                createTextVNode(toDisplayString(a.$lang("shopgo.cart.address.form.no.addresses")), 1)
              ], 64))
            ]))
          ])
        ])
      ])
    ], 8, _hoisted_63)
  ]);
}
const AddressForm__Tmp3211 = /* @__PURE__ */ _export_sfc(_sfc_main$9, [["render", _sfc_render$9], ["__file", "AddressForm.vue"]]), _sfc_main$8 = /* @__PURE__ */ defineComponent({
  __name: "AddressFormSet",
  props: /* @__PURE__ */ mergeModels({
    user: {}
  }, {
    payment: {
      required: !0
    },
    paymentModifiers: {},
    shipping: {
      required: !0
    },
    shippingModifiers: {}
  }),
  emits: ["update:payment", "update:shipping"],
  setup(a, { expose: e }) {
    const n = resolveVueComponent("AddressForm", AddressForm__Tmp3211);
    e();
    const t = useModel(a, "payment"), d = useModel(a, "shipping"), u = { paymentData: t, shippingData: d, AddressForm: n };
    return Object.defineProperty(u, "__isScriptSetup", { enumerable: !1, value: !0 }), u;
  }
}), _hoisted_1$7 = { class: "" };
function _sfc_render$8(a, e, n, t, d, u) {
  return openBlock(), createElementBlock("div", _hoisted_1$7, [
    createVNode(t.AddressForm, {
      type: "payment",
      title: a.$lang("shopgo.cart.payment.data.title"),
      user: n.user,
      modelValue: t.paymentData,
      "onUpdate:modelValue": e[0] || (e[0] = (o) => t.paymentData = o),
      ref: "paymentForm"
    }, null, 8, ["title", "user", "modelValue"]),
    e[2] || (e[2] = createTextVNode()),
    createVNode(t.AddressForm, {
      type: "shipping",
      title: a.$lang("shopgo.cart.shipping.data.title"),
      user: n.user,
      modelValue: t.shippingData,
      "onUpdate:modelValue": e[1] || (e[1] = (o) => t.shippingData = o),
      "sync-data": t.paymentData,
      ref: "shippingForm"
    }, null, 8, ["title", "user", "modelValue", "sync-data"])
  ]);
}
const AddressFormSet__Tmp46532 = /* @__PURE__ */ _export_sfc(_sfc_main$8, [["render", _sfc_render$8], ["__file", "AddressFormSet.vue"]]), _sfc_main$7 = /* @__PURE__ */ defineComponent({
  __name: "CartForm",
  props: /* @__PURE__ */ mergeModels({
    user: {},
    shippings: {},
    payments: {},
    checkoutData: {}
  }, {
    payment: {
      required: !0
    },
    paymentModifiers: {},
    shipping: {
      required: !0
    },
    shippingModifiers: {},
    "payment-id": {
      required: !0
    },
    "payment-idModifiers": {},
    "shipping-id": {
      required: !0
    },
    "shipping-idModifiers": {},
    note: {
      default: ""
    },
    noteModifiers: {}
  }),
  emits: ["update:payment", "update:shipping", "update:payment-id", "update:shipping-id", "update:note"],
  setup(a, { expose: e }) {
    e();
    const n = a, t = useModel(a, "payment"), d = useModel(a, "shipping"), u = useModel(a, "payment-id"), o = useModel(a, "shipping-id"), r = useModel(a, "note"), l = { props: n, paymentData: t, shippingData: d, paymentId: u, shippingId: o, note: r };
    return Object.defineProperty(l, "__isScriptSetup", { enumerable: !1, value: !0 }), l;
  }
});
function _sfc_render$7(a, e, n, t, d, u) {
  return openBlock(), createElementBlock("div", null, [
    renderSlot(a.$slots, "default", {
      user: n.user,
      shippings: n.shippings,
      payments: n.payments,
      shippingData: t.shippingData,
      paymentData: t.paymentData,
      shippingId: t.shippingId,
      paymentId: t.paymentId,
      note: t.note,
      checkoutData: n.checkoutData
    })
  ]);
}
const CartForm__Tmp33221 = /* @__PURE__ */ _export_sfc(_sfc_main$7, [["render", _sfc_render$7], ["__file", "CartForm.vue"]]), _sfc_main$6 = /* @__PURE__ */ defineComponent({
  __name: "CartListItem",
  props: {
    item: {},
    hasCheckbox: { type: Boolean }
  },
  emits: ["remove-item", "update-quantities", "change-item-quantity", "update-checks"],
  setup(a, { expose: e, emit: n }) {
    e();
    const t = a, d = n;
    function u() {
      d("remove-item");
    }
    function o() {
      d("update-quantities");
    }
    function r(p) {
      d("change-item-quantity", p);
    }
    function l() {
      d("update-checks");
    }
    const g = { props: t, emits: d, removeItem: u, updateQuantities: o, changeItemQuantity: r, updateChecks: l };
    return Object.defineProperty(g, "__isScriptSetup", { enumerable: !1, value: !0 }), g;
  }
}), _hoisted_1$6 = ["data-product-id", "data-variant-id"], _hoisted_2$6 = { class: "card-body d-grid d-lg-flex gap-3" }, _hoisted_3$6 = { class: "d-flex gap-3 me-auto" }, _hoisted_4$6 = {
  key: 0,
  class: "c-cart-item__checkbox"
}, _hoisted_5$6 = { class: "c-cart-item__image" }, _hoisted_6$4 = {
  style: { width: "75px" },
  class: "ratio ratio-1x1"
}, _hoisted_7$4 = ["src", "alt"], _hoisted_8$4 = { class: "c-cart-item__content" }, _hoisted_9$4 = ["href"], _hoisted_10$4 = {
  key: 0,
  class: "fs-6 text-muted"
}, _hoisted_11$4 = { class: "text-muted small" }, _hoisted_12$4 = { key: 1 }, _hoisted_13$4 = { class: "badge bg-danger" }, _hoisted_14$3 = { class: "c-cart-item__quantity d-flex gap-2" }, _hoisted_15$3 = { class: "" }, _hoisted_16$2 = { class: "input-group flex-nowrap" }, _hoisted_17$1 = { class: "d-flex gap-3" }, _hoisted_18$1 = {
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
}, _hoisted_31 = { class: "c-attachment__quantity ms-auto" }, _hoisted_32 = { class: "d-flex gap-3 ms-auto ms-lg-0" }, _hoisted_33 = {
  class: "c-attachment__total d-flex justify-content-end gap-3",
  style: { width: "250px" }
}, _hoisted_34 = {
  class: "c-cart-item__price d-flex align-items-center gap-2 text-end text-nowrap",
  style: { "min-width": "135px" }
}, _hoisted_35 = {
  key: 0,
  class: "small text-muted"
}, _hoisted_36 = { class: "" }, _hoisted_37 = { class: "mt-3 text-end fs-5" }, _hoisted_38 = { class: "" };
function _sfc_render$6(a, e, n, t, d, u) {
  return openBlock(), createElementBlock("div", {
    class: "c-cart-item card mb-3",
    "data-product-id": n.item.product.id,
    "data-variant-id": n.item.variant.id
  }, [
    createElementVNode("div", _hoisted_2$6, [
      createElementVNode("div", _hoisted_3$6, [
        n.hasCheckbox ? (openBlock(), createElementBlock("div", _hoisted_4$6, [
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
        createElementVNode("div", _hoisted_5$6, [
          createElementVNode("div", _hoisted_6$4, [
            createElementVNode("img", {
              class: "object-fit-cover",
              src: n.item.cover,
              alt: n.item.product.title,
              style: {}
            }, null, 8, _hoisted_7$4)
          ])
        ]),
        e[7] || (e[7] = createTextVNode()),
        createElementVNode("div", _hoisted_8$4, [
          createElementVNode("h5", null, [
            createElementVNode("a", {
              href: n.item.link,
              target: "_blank"
            }, toDisplayString(n.item.product.title), 9, _hoisted_9$4)
          ]),
          e[4] || (e[4] = createTextVNode()),
          n.item.variant.primary ? createCommentVNode("", !0) : (openBlock(), createElementBlock("div", _hoisted_10$4, toDisplayString(n.item.variant.title), 1)),
          e[5] || (e[5] = createTextVNode()),
          createElementVNode("div", _hoisted_11$4, toDisplayString(n.item.product.model), 1)
        ]),
        e[8] || (e[8] = createTextVNode()),
        n.item.outOfStock ? (openBlock(), createElementBlock("div", _hoisted_12$4, [
          createElementVNode("span", _hoisted_13$4, toDisplayString(a.$lang("shopgo.message.out.of.stock")), 1)
        ])) : createCommentVNode("", !0)
      ]),
      e[16] || (e[16] = createTextVNode()),
      createElementVNode("div", _hoisted_14$3, [
        createElementVNode("div", _hoisted_15$3, [
          createElementVNode("div", _hoisted_16$2, [
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
            createElementVNode("del", null, toDisplayString(a.$formatPrice(n.item.priceSet.base_total.price)), 1)
          ])) : createCommentVNode("", !0),
          e[13] || (e[13] = createTextVNode()),
          createElementVNode("div", _hoisted_20$1, toDisplayString(a.$formatPrice(n.item.priceSet.final_total.price, { code: !0 })), 1)
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
      createElementVNode("h6", null, toDisplayString(a.$lang("shopgo.cart.title.attachments")), 1),
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
          o.outOfStock ? (openBlock(), createElementBlock("span", _hoisted_30$1, toDisplayString(a.$lang("shopgo.message.out.of.stock")), 1)) : createCommentVNode("", !0),
          e[21] || (e[21] = createTextVNode()),
          createElementVNode("div", _hoisted_31, `
            x` + toDisplayString(o.quantity * n.item.quantity), 1)
        ]),
        e[23] || (e[23] = createTextVNode()),
        createElementVNode("div", _hoisted_32, [
          createElementVNode("div", _hoisted_33, [
            createElementVNode("div", _hoisted_34, [
              o.priceSet.base_total.price !== o.priceSet.final_total.price ? (openBlock(), createElementBlock("div", _hoisted_35, [
                createElementVNode("del", null, toDisplayString(a.$formatPrice(o.priceSet.base_total.price)), 1)
              ])) : createCommentVNode("", !0),
              e[22] || (e[22] = createTextVNode()),
              createElementVNode("div", _hoisted_36, toDisplayString(a.$formatPrice(o.priceSet.final_total.price)), 1)
            ])
          ])
        ])
      ], 8, _hoisted_22$1))), 256)),
      e[26] || (e[26] = createTextVNode()),
      createElementVNode("div", _hoisted_37, [
        createElementVNode("strong", null, toDisplayString(a.$lang("shopgo.cart.label.attached.product.total")), 1),
        e[24] || (e[24] = createTextVNode()),
        createElementVNode("span", _hoisted_38, toDisplayString(a.$formatPrice(n.item.priceSet.attached_final_total.price, { code: !0 })), 1)
      ])
    ])) : createCommentVNode("", !0)
  ], 8, _hoisted_1$6);
}
const CartListItem__Tmp34906 = /* @__PURE__ */ _export_sfc(_sfc_main$6, [["render", _sfc_render$6], ["__file", "CartListItem.vue"]]), _sfc_main$5 = /* @__PURE__ */ defineComponent({
  __name: "CartSidebar",
  props: /* @__PURE__ */ mergeModels({
    totals: {},
    coupons: {},
    loading: { type: Boolean },
    loaded: { type: Boolean },
    selectedShipping: {},
    selectedPayment: {},
    canCheckout: { type: Boolean }
  }, {
    code: {
      default: ""
    },
    codeModifiers: {}
  }),
  emits: /* @__PURE__ */ mergeModels(["add-code", "remove-code", "checkout"], ["update:code"]),
  setup(a, { expose: e, emit: n }) {
    e();
    const t = a, d = n, u = useModel(a, "code");
    async function o() {
      u.value !== "" && d("add-code", u.value);
    }
    async function r(C) {
      d("remove-code", C);
    }
    const l = computed(() => {
      const C = [];
      for (const S in t.totals) {
        if (S === "total" || S === "grand_total")
          continue;
        const v = t.totals[S];
        Number(v.price) !== 0 && C.push(v);
      }
      return C;
    });
    function g() {
      d("checkout");
    }
    const p = { props: t, emits: d, code: u, addCode: o, removeCode: r, filteredTotals: l, checkout: g, get vTooltip() {
      return vTooltip;
    } };
    return Object.defineProperty(p, "__isScriptSetup", { enumerable: !1, value: !0 }), p;
  }
}), _hoisted_1$5 = {
  class: "l-cart-sidebar position-sticky",
  style: { top: "var(--sidebar-offsets-top, 90px)" }
}, _hoisted_2$5 = { class: "card" }, _hoisted_3$5 = { class: "card-body l-cart-coupons border-bottom" }, _hoisted_4$5 = { class: "d-flex gap-2" }, _hoisted_5$5 = ["disabled"], _hoisted_6$3 = {
  key: 0,
  "data-cloak": "",
  class: "list-group list-group-flush mt-4"
}, _hoisted_7$3 = { class: "list-group-item border-top d-flex" }, _hoisted_8$3 = { class: "small text-muted" }, _hoisted_9$3 = { class: "ms-auto" }, _hoisted_10$3 = ["onClick"], _hoisted_11$3 = {
  key: 0,
  class: "card-body"
}, _hoisted_12$3 = {
  key: 1,
  "data-cloak": "",
  class: "card-body l-cart-totals text-end"
}, _hoisted_13$3 = { class: "l-cart-total d-flex justify-content-between gap-1 mb-1 w-100" }, _hoisted_14$2 = { class: "l-cart-total__label" }, _hoisted_15$2 = {
  key: 0,
  class: "l-cart-total__value"
}, _hoisted_16$1 = { class: "l-cart-total d-flex justify-content-between gap-1 mb-1 w-100" }, _hoisted_17 = { class: "l-cart-total__label d-flex gap-2" }, _hoisted_18 = { key: 0 }, _hoisted_19 = { class: "l-cart-total__value" }, _hoisted_20 = {
  class: "card mt-3 position-sticky",
  style: { bottom: "0" }
}, _hoisted_21 = { class: "card-body d-grid gap-3" }, _hoisted_22 = {
  key: 0,
  class: "l-cart-total d-flex justify-content-between gap-1 w-100 fs-5 fw-bold",
  "data-cloak": ""
}, _hoisted_23 = { class: "l-cart-total__label" }, _hoisted_24 = {
  key: 0,
  class: "l-cart-total__value text-end"
}, _hoisted_25 = {
  key: 0,
  class: "mt-1 small text-muted fw-normal"
}, _hoisted_26 = {
  key: 1,
  class: "d-flex justify-content-between",
  "data-cloak": ""
}, _hoisted_27 = { key: 2 }, _hoisted_28 = { key: 3 }, _hoisted_29 = ["disabled"], _hoisted_30 = {
  key: 0,
  class: "spinner spinner-grow spinner-grow-sm"
};
function _sfc_render$5(a, e, n, t, d, u) {
  return openBlock(), createElementBlock("div", _hoisted_1$5, [
    createElementVNode("div", _hoisted_2$5, [
      createElementVNode("div", _hoisted_3$5, [
        createElementVNode("h5", null, toDisplayString(a.$lang("shopgo.cart.label.discount.code")), 1),
        e[5] || (e[5] = createTextVNode()),
        createElementVNode("div", _hoisted_4$5, [
          withDirectives(createElementVNode("input", {
            type: "text",
            class: "form-control",
            "onUpdate:modelValue": e[0] || (e[0] = (o) => t.code = o)
          }, null, 512), [
            [vModelText, t.code]
          ]),
          e[1] || (e[1] = createTextVNode()),
          createElementVNode("button", {
            type: "button",
            class: "btn btn-secondary text-nowrap",
            style: { "min-width": "100px" },
            onClick: t.addCode,
            disabled: t.code === "" || n.loading
          }, toDisplayString(a.$lang("shopgo.cart.button.use.discount.code")), 9, _hoisted_5$5)
        ]),
        e[6] || (e[6] = createTextVNode()),
        n.coupons.length ? (openBlock(), createElementBlock("div", _hoisted_6$3, [
          (openBlock(!0), createElementBlock(Fragment, null, renderList(n.coupons, (o) => (openBlock(), createElementBlock("div", _hoisted_7$3, [
            createElementVNode("div", null, [
              createElementVNode("div", null, [
                createElementVNode("strong", null, toDisplayString(o.title), 1)
              ]),
              e[2] || (e[2] = createTextVNode()),
              createElementVNode("div", _hoisted_8$3, toDisplayString(o.code), 1)
            ]),
            e[4] || (e[4] = createTextVNode()),
            createElementVNode("div", _hoisted_9$3, [
              withDirectives((openBlock(), createElementBlock("a", {
                href: "javascript://",
                class: "link-secondary",
                title: "{{ $lang('shopgo.cart.button.remove.discount.code') }}",
                onClick: (r) => t.removeCode(o.id)
              }, [...e[3] || (e[3] = [
                createElementVNode("i", { class: "fa fa-trash" }, null, -1)
              ])], 8, _hoisted_10$3)), [
                [t.vTooltip]
              ])
            ])
          ]))), 256))
        ])) : createCommentVNode("", !0)
      ]),
      e[12] || (e[12] = createTextVNode()),
      n.loaded ? createCommentVNode("", !0) : (openBlock(), createElementBlock("div", _hoisted_11$3, [...e[7] || (e[7] = [
        createElementVNode("div", { class: "card-text placeholder-glow d-flex my-2" }, [
          createElementVNode("span", { class: "placeholder col-4" }),
          createTextVNode(),
          createElementVNode("span", { class: "placeholder col-3 ms-auto" })
        ], -1)
      ])])),
      e[13] || (e[13] = createTextVNode()),
      n.loaded ? (openBlock(), createElementBlock("div", _hoisted_12$3, [
        createElementVNode("div", _hoisted_13$3, [
          createElementVNode("div", _hoisted_14$2, toDisplayString(a.$lang("shopgo.cart.label.total")), 1),
          e[8] || (e[8] = createTextVNode()),
          n.totals.total ? (openBlock(), createElementBlock("div", _hoisted_15$2, toDisplayString(a.$formatPrice(n.totals.total.price, { code: !0 })), 1)) : createCommentVNode("", !0)
        ]),
        e[11] || (e[11] = createTextVNode()),
        (openBlock(!0), createElementBlock(Fragment, null, renderList(t.filteredTotals, (o) => (openBlock(), createElementBlock("div", _hoisted_16$1, [
          createElementVNode("div", _hoisted_17, [
            createElementVNode("div", null, toDisplayString(o.label), 1),
            e[9] || (e[9] = createTextVNode()),
            o.params.type === "coupon" || o.params.subtype === "code" ? (openBlock(), createElementBlock("div", _hoisted_18, [
              createElementVNode("small", null, "(" + toDisplayString(o.params.code) + ")", 1)
            ])) : createCommentVNode("", !0)
          ]),
          e[10] || (e[10] = createTextVNode()),
          createElementVNode("div", _hoisted_19, toDisplayString(a.$formatPrice(o.price, { code: !0 })), 1)
        ]))), 256))
      ])) : createCommentVNode("", !0)
    ]),
    e[25] || (e[25] = createTextVNode()),
    createElementVNode("div", _hoisted_20, [
      createElementVNode("div", _hoisted_21, [
        n.loaded ? (openBlock(), createElementBlock("div", _hoisted_22, [
          createElementVNode("div", _hoisted_23, toDisplayString(a.$lang("shopgo.cart.label.grand.total")), 1),
          e[15] || (e[15] = createTextVNode()),
          n.totals.grand_total ? (openBlock(), createElementBlock("div", _hoisted_24, [
            createElementVNode("div", null, toDisplayString(a.$formatPrice(n.totals.grand_total.price, { code: !0 })), 1),
            e[14] || (e[14] = createTextVNode()),
            a.$currency.isSubCurrency() ? (openBlock(), createElementBlock("div", _hoisted_25, `
              (` + toDisplayString(a.$currency.formatMainCurrency(n.totals.grand_total.price, { code: !0 })) + `)
            `, 1)) : createCommentVNode("", !0)
          ])) : createCommentVNode("", !0)
        ])) : createCommentVNode("", !0),
        e[21] || (e[21] = createTextVNode()),
        n.loaded ? (openBlock(), createElementBlock("div", _hoisted_26, [
          createElementVNode("div", null, [
            e[16] || (e[16] = createElementVNode("i", { class: "fa fa-truck" }, null, -1)),
            createTextVNode(" " + toDisplayString(n.selectedShipping?.title || a.$lang("shopgo.message.no.shipping.selected")), 1)
          ]),
          e[18] || (e[18] = createTextVNode()),
          createElementVNode("div", null, [
            e[17] || (e[17] = createElementVNode("i", { class: "fa fa-credit-card" }, null, -1)),
            createTextVNode(" " + toDisplayString(n.selectedPayment?.title || a.$lang("shopgo.message.no.payment.selected")), 1)
          ])
        ])) : createCommentVNode("", !0),
        e[22] || (e[22] = createTextVNode()),
        n.loaded ? createCommentVNode("", !0) : (openBlock(), createElementBlock("div", _hoisted_27, [...e[19] || (e[19] = [
          createElementVNode("div", {
            class: "card-text placeholder-glow d-flex mb-1",
            style: { height: "1.25rem" }
          }, [
            createElementVNode("span", { class: "placeholder col-3" }),
            createTextVNode(),
            createElementVNode("span", { class: "placeholder col-4 ms-auto" })
          ], -1)
        ])])),
        e[23] || (e[23] = createTextVNode()),
        n.loaded ? createCommentVNode("", !0) : (openBlock(), createElementBlock("div", _hoisted_28, [...e[20] || (e[20] = [
          createElementVNode("div", { class: "card-text placeholder-glow d-flex" }, [
            createElementVNode("span", { class: "placeholder col-3" }),
            createTextVNode(),
            createElementVNode("span", { class: "placeholder col-3 ms-auto" })
          ], -1)
        ])])),
        e[24] || (e[24] = createTextVNode()),
        createElementVNode("button", {
          type: "button",
          class: "btn btn-primary btn-lg",
          disabled: n.loading || !n.canCheckout,
          onClick: t.checkout
        }, [
          n.loading ? (openBlock(), createElementBlock("span", _hoisted_30)) : (openBlock(), createElementBlock(Fragment, { key: 1 }, [
            createTextVNode(toDisplayString(a.$lang("shopgo.cart.button.process.checkout")), 1)
          ], 64))
        ], 8, _hoisted_29)
      ])
    ])
  ]);
}
const CartSidebar__Tmp47500 = /* @__PURE__ */ _export_sfc(_sfc_main$5, [["render", _sfc_render$5], ["__file", "CartSidebar.vue"]]), _sfc_main$4 = /* @__PURE__ */ defineComponent({
  __name: "PaymentItem",
  props: {
    payment: {},
    i: {},
    selected: { type: Boolean }
  },
  emits: ["selected"],
  setup(a, { expose: e, emit: n }) {
    e();
    const t = a, d = n, u = ref(uid()), o = ref(t.payment.checkoutFormComponent), r = ref({}), l = ref(t.selected), g = ref(data("image.default")), p = computed(() => o.value?.injectId ? useInject(o.value.injectId) : null), C = computed(() => o.value?.props || {});
    watch(() => t.selected, () => {
      l.value = t.selected, setTimeout(() => {
        l.value ? slideDown(v.value) : slideUp(v.value);
      }, 0);
    });
    function S() {
      l.value = !0, d("selected");
    }
    const v = ref(), x = { props: t, emit: d, uidRef: u, checkoutFormComponent: o, data: r, selectedRef: l, imageDefault: g, PaymentForm: p, formProps: C, onSelected: S, optionLayout: v };
    return Object.defineProperty(x, "__isScriptSetup", { enumerable: !1, value: !0 }), x;
  }
}), _hoisted_1$4 = { class: "card-body d-flex align-items-center gap-3" }, _hoisted_2$4 = { class: "form-check" }, _hoisted_3$4 = ["id", "value", "checked"], _hoisted_4$4 = ["for"], _hoisted_5$4 = { class: "" }, _hoisted_6$2 = {
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
}, _hoisted_13$2 = {
  key: 0,
  class: "card-body border-top"
};
function _sfc_render$4(a, e, n, t, d, u) {
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["card", [t.selectedRef ? "border border-primary" : ""]])
  }, [
    createElementVNode("div", _hoisted_1$4, [
      createElementVNode("div", _hoisted_2$4, [
        createElementVNode("input", {
          type: "radio",
          id: `input-payment-id-${n.payment.id}`,
          name: "checkout[payment][id]",
          value: n.payment.id,
          class: "form-check-input",
          onChange: t.onSelected,
          checked: t.selectedRef
        }, null, 40, _hoisted_3$4),
        e[0] || (e[0] = createTextVNode()),
        createElementVNode("label", {
          for: `input-payment-id-${n.payment.id}`,
          class: "stretched-link",
          style: { cursor: "pointer" }
        }, null, 8, _hoisted_4$4)
      ]),
      e[2] || (e[2] = createTextVNode()),
      createElementVNode("div", _hoisted_5$4, [
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
          t.PaymentForm && t.selectedRef ? (openBlock(), createElementBlock("div", _hoisted_13$2, [
            (openBlock(), createBlock(resolveDynamicComponent(t.PaymentForm), mergeProps({
              uid: t.uidRef,
              payment: n.payment
            }, t.formProps), null, 16, ["uid", "payment"]))
          ])) : createCommentVNode("", !0)
        ], 512)
      ]),
      _: 1
    })
  ], 2);
}
const PaymentItem__Tmp28337 = /* @__PURE__ */ _export_sfc(_sfc_main$4, [["render", _sfc_render$4], ["__file", "PaymentItem.vue"]]), _sfc_main$3 = /* @__PURE__ */ defineComponent({
  __name: "PaymentSelector",
  props: /* @__PURE__ */ mergeModels({
    payments: {},
    paymentData: {},
    loading: { type: Boolean },
    shippingId: {}
  }, {
    modelValue: {},
    modelModifiers: {}
  }),
  emits: ["update:modelValue"],
  setup(a, { expose: e }) {
    const n = resolveVueComponent("PaymentItem", PaymentItem__Tmp28337);
    e();
    const d = { paymentId: useModel(a, "modelValue"), PaymentItem: n };
    return Object.defineProperty(d, "__isScriptSetup", { enumerable: !1, value: !0 }), d;
  }
}), _hoisted_1$3 = { class: "l-payments" }, _hoisted_2$3 = {
  key: 0,
  class: "d-flex flex-column gap-3"
}, _hoisted_3$3 = {
  key: 1,
  class: "card bg-light"
}, _hoisted_4$3 = { class: "card-body py-5 text-center" }, _hoisted_5$3 = {
  key: 0,
  class: "spinner spinner-border"
};
function _sfc_render$3(a, e, n, t, d, u) {
  return openBlock(), createElementBlock("div", _hoisted_1$3, [
    createElementVNode("h3", null, toDisplayString(a.$lang("shopgo.cart.payment.title")), 1),
    e[0] || (e[0] = createTextVNode()),
    n.payments.length > 0 ? (openBlock(), createElementBlock("div", _hoisted_2$3, [
      (openBlock(!0), createElementBlock(Fragment, null, renderList(n.payments, (o, r) => (openBlock(), createBlock(t.PaymentItem, {
        key: o.id,
        style: { "animation-duration": ".1s" },
        payment: o,
        i: r,
        selected: String(t.paymentId) === String(o.id),
        onSelected: (l) => t.paymentId = o.id
      }, null, 8, ["payment", "i", "selected", "onSelected"]))), 128))
    ])) : (openBlock(), createElementBlock("div", _hoisted_3$3, [
      createElementVNode("div", _hoisted_4$3, [
        n.loading ? (openBlock(), createElementBlock("span", _hoisted_5$3)) : n.shippingId ? (openBlock(), createElementBlock(Fragment, { key: 1 }, [
          createTextVNode(toDisplayString(a.$lang("shopgo.cart.text.no.payments")), 1)
        ], 64)) : (openBlock(), createElementBlock(Fragment, { key: 2 }, [
          createTextVNode(toDisplayString(a.$lang("shopgo.cart.text.select.shipping.first")), 1)
        ], 64))
      ])
    ]))
  ]);
}
const PaymentSelector__Tmp46513 = /* @__PURE__ */ _export_sfc(_sfc_main$3, [["render", _sfc_render$3], ["__file", "PaymentSelector.vue"]]), _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "ShippingItem",
  props: {
    shipping: {},
    i: {},
    selected: { type: Boolean }
  },
  emits: ["selected"],
  setup(__props, { expose: __expose, emit: __emit }) {
    __expose();
    const props = __props, emit = __emit, uidRef = ref(uid()), checkoutFormComponent = ref(props.shipping.checkoutFormComponent), data$1 = ref({}), selectedRef = ref(props.selected), imageDefault = ref(data("image.default")), ShippingForm = computed(() => checkoutFormComponent.value?.injectId ? useInject(checkoutFormComponent.value.injectId) : null), formProps = computed(() => checkoutFormComponent.value?.props || {});
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
    const form = ref(), __returned__ = { props, emit, uidRef, checkoutFormComponent, data: data$1, selectedRef, imageDefault, ShippingForm, formProps, onSelected, form };
    return Object.defineProperty(__returned__, "__isScriptSetup", { enumerable: !1, value: !0 }), __returned__;
  }
}), _hoisted_1$2 = { class: "card-body d-flex align-items-center gap-3" }, _hoisted_2$2 = { class: "form-check" }, _hoisted_3$2 = ["id", "value", "checked"], _hoisted_4$2 = ["for"], _hoisted_5$2 = { class: "" }, _hoisted_6$1 = {
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
  style: { display: "none", position: "relative", "z-index": "1", overflow: "hidden", "animation-duration": ".3s" }
}, _hoisted_15$1 = {
  key: 0,
  class: "card-body border-top"
};
function _sfc_render$2(a, e, n, t, d, u) {
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["card", [t.selectedRef ? "border border-primary" : ""]])
  }, [
    createElementVNode("div", _hoisted_1$2, [
      createElementVNode("div", _hoisted_2$2, [
        createElementVNode("input", {
          type: "radio",
          id: `input-shipping-id-${n.shipping.id}`,
          name: "checkout[shipping][id]",
          value: n.shipping.id,
          class: "form-check-input",
          onChange: t.onSelected,
          checked: t.selectedRef
        }, null, 40, _hoisted_3$2),
        e[0] || (e[0] = createTextVNode()),
        createElementVNode("label", {
          for: `input-shipping-id-${n.shipping.id}`,
          class: "stretched-link",
          style: { cursor: "pointer" }
        }, null, 8, _hoisted_4$2)
      ]),
      e[2] || (e[2] = createTextVNode()),
      createElementVNode("div", _hoisted_5$2, [
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
        createElementVNode("span", _hoisted_11$1, toDisplayString(a.$formatPrice(n.shipping.fee, { code: !0 })), 1)
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
          t.ShippingForm && t.selectedRef ? (openBlock(), createElementBlock("div", _hoisted_15$1, [
            (openBlock(), createBlock(resolveDynamicComponent(t.ShippingForm), mergeProps({
              uid: t.uidRef,
              shipping: n.shipping
            }, t.formProps), null, 16, ["uid", "shipping"]))
          ])) : createCommentVNode("", !0)
        ], 512)
      ]),
      _: 1
    })
  ], 2);
}
const ShippingItem__Tmp29158 = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["render", _sfc_render$2], ["__file", "ShippingItem.vue"]]), _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "ShippingSelector",
  props: /* @__PURE__ */ mergeModels({
    shippings: {},
    shippingData: {},
    loading: { type: Boolean }
  }, {
    modelValue: {},
    modelModifiers: {}
  }),
  emits: ["update:modelValue"],
  setup(a, { expose: e }) {
    const n = resolveVueComponent("ShippingItem", ShippingItem__Tmp29158);
    e();
    const d = { shippingId: useModel(a, "modelValue"), ShippingItem: n };
    return Object.defineProperty(d, "__isScriptSetup", { enumerable: !1, value: !0 }), d;
  }
}), _hoisted_1$1 = { class: "l-shippings" }, _hoisted_2$1 = {
  key: 0,
  class: "d-flex flex-column gap-3"
}, _hoisted_3$1 = {
  key: 1,
  class: "card bg-light"
}, _hoisted_4$1 = { class: "card-body py-5 text-center" }, _hoisted_5$1 = {
  key: 0,
  class: "spinner spinner-border"
};
function _sfc_render$1(a, e, n, t, d, u) {
  return openBlock(), createElementBlock("div", _hoisted_1$1, [
    createElementVNode("h3", null, toDisplayString(a.$lang("shopgo.cart.shipping.title")), 1),
    e[0] || (e[0] = createTextVNode()),
    n.shippings.length > 0 ? (openBlock(), createElementBlock("div", _hoisted_2$1, [
      (openBlock(!0), createElementBlock(Fragment, null, renderList(n.shippings, (o, r) => (openBlock(), createBlock(t.ShippingItem, {
        key: o.id,
        style: { "animation-duration": ".1s" },
        shipping: o,
        i: r,
        selected: String(t.shippingId) === String(o.id),
        onSelected: (l) => t.shippingId = o.id
      }, null, 8, ["shipping", "i", "selected", "onSelected"]))), 128))
    ])) : (openBlock(), createElementBlock("div", _hoisted_3$1, [
      createElementVNode("div", _hoisted_4$1, [
        n.loading ? (openBlock(), createElementBlock("span", _hoisted_5$1)) : n.shippingData?.locationId ? (openBlock(), createElementBlock(Fragment, { key: 1 }, [
          createTextVNode(toDisplayString(a.$lang("shopgo.cart.text.no.shippings")), 1)
        ], 64)) : (openBlock(), createElementBlock(Fragment, { key: 2 }, [
          createTextVNode(toDisplayString(a.$lang("shopgo.cart.text.select.location.first")), 1)
        ], 64))
      ])
    ]))
  ]);
}
const ShippingSelector__Tmp40431 = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["render", _sfc_render$1], ["__file", "ShippingSelector.vue"]]), _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "CartApp",
  props: {
    user: {},
    checkoutData: {}
  },
  setup(a, { expose: e }) {
    resolveVueComponent("AddressForm", AddressForm__Tmp3211);
    const n = resolveVueComponent("AddressFormSet", AddressFormSet__Tmp46532), t = resolveVueComponent("CartForm", CartForm__Tmp33221), d = resolveVueComponent("CartListItem", CartListItem__Tmp34906), u = resolveVueComponent("CartSidebar", CartSidebar__Tmp47500), o = resolveVueComponent("PaymentSelector", PaymentSelector__Tmp46513), r = resolveVueComponent("ShippingSelector", ShippingSelector__Tmp40431);
    e();
    const l = a, g = ref(!1), p = ref([]), C = ref({}), S = ref([]), v = ref(l.checkoutData?.payment?.id || ""), x = ref(l.checkoutData?.payment_data || {}), y = ref(l.checkoutData?.shipping?.id || ""), B = ref(l.checkoutData?.shipping_data || {}), D = ref([]), $ = ref([]), A = ref(""), j = ref(l.checkoutData?.note || ""), P = ref(!1), Q = ref(data("partial.checkout")), H = useQueue("shopgo.cart"), E = useStack("loading");
    provide("checkoutData", l.checkoutData), provide("items", p), provide("totals", C), provide("coupons", S), provide("shippingData", B), provide("paymentData", x), provide("shippingId", y), provide("paymentId", v), provide("shippings", D), provide("payments", $), provide("loading", P), provide("code", A), provide("note", j), provide("queue", H), provide("partialCheckout", Q);
    let I = null;
    const T = document.querySelector("#cart-form"), w = ref(), U = getCurrentInstance(), q = U?.appContext.components || {};
    function F(s) {
      return q[s] ?? null;
    }
    const L = F("AfterAddressForm"), O = F("AfterShippingForm"), m = F("AfterPaymentForm"), i = F("AfterNoteForm");
    E.observe((s, f) => {
      P.value = f > 0;
    }), ee();
    function c(s = 300) {
      setTimeout(() => {
        E.pop();
      }, s);
    }
    const b = debounce(function() {
      return N();
    }, 300);
    async function N(s = !0) {
      I?.abort("Cancel by next load"), I = new AbortController(), E.push(!0);
      const { get: f, isAxiosError: _, isCancel: V } = await useHttpClient();
      try {
        const k = await f(
          "@cart_ajax/getItems",
          {
            params: {
              location_id: B.value.locationId,
              shipping_id: y.value,
              payment_id: v.value
            },
            signal: I.signal
          }
        );
        return await R(k.data.data, s), k;
      } catch (k) {
        V(k) && console.log(k.message), console.error(k), _(k) && simpleAlert(k.message, "", "warning");
      } finally {
        c(), I = null;
      }
    }
    async function R(s, f = !0) {
      if (p.value = s.items, C.value = s.totals, S.value = s.coupons, f)
        return await J();
    }
    watch(p, () => {
      X();
    }, { deep: !0 });
    const M = computed(() => p.value.map((s) => s.options.checked == null ? !0 : s.options.checked)), z = computed(() => M.value.filter((s) => s === !0).length), W = computed(() => M.value.filter((s) => s === !1).length);
    function X() {
      w.value && (w.value.checked = !1, w.value.indeterminate = !1, z.value > 0 && W.value === 0 ? w.value.checked = !0 : W.value > 0 && z.value === 0 ? w.value.checked = !1 : z.value > 0 && W.value > 0 && (w.value.indeterminate = !0));
    }
    function se() {
      if (w.value) {
        for (const s of p.value)
          s.options.checked = w.value.checked;
        Y();
      }
    }
    const Y = debounce(async () => {
      I?.abort("Cancel by next modify."), I = new AbortController();
      const s = {};
      for (const V of p.value)
        s[V.key] = V.options.checked ? "1" : "0";
      E.push(!0);
      const { post: f, isAxiosError: _ } = await useHttpClient();
      try {
        const V = await f("@cart_ajax/updateChecks", { checks: s }, { signal: I.signal });
        return await N();
      } catch (V) {
        console.error(V), _(V) && simpleAlert(V.message, "", "warning");
      } finally {
        c(), I = null;
      }
    }, 300);
    onMounted(() => {
      Z(T);
    });
    function Z(s, f = 30) {
      const _ = document.querySelector("header .navbar, .navbar");
      if (!_)
        return;
      const V = _.clientHeight + f;
      s.style.setProperty("--sidebar-offsets-top", V + "px");
    }
    async function ee() {
      await N(), g.value = !0;
    }
    async function ie(s, f) {
      E.push(!0);
      const { delete: _, isAxiosError: V } = await useHttpClient();
      try {
        const k = await _(`@cart_ajax/removeItem?key=${s.key}`);
        return await b();
      } catch (k) {
        console.error(k), V(k) && simpleAlert(k.message, "", "warning");
      } finally {
        setTimeout(() => {
          E.pop();
        }, 300);
      }
    }
    async function de() {
      E.push(!0);
      const { put: s, isAxiosError: f } = await useHttpClient();
      try {
        await s("@cart_ajax/clearCart"), await N(), await simpleAlert(
          __("shopgo.cart.message.items.removed"),
          __("shopgo.cart.message.will.back.to.home"),
          "success"
        ), location.href = route("home");
      } catch (_) {
        console.error(_), f(_) && simpleAlert(_.message, "", "warning");
      } finally {
        E.pop();
      }
    }
    async function re(s, f) {
      s.quantity += f, s.quantity = Math.max(s.quantity, 1), await te(s);
    }
    const te = debounce(async (s) => {
      s.quantity = Math.max(s.quantity, 1);
      const f = {};
      for (const k of p.value)
        f[k.key] = k.quantity;
      E.push(!0);
      const { post: _, isAxiosError: V } = await useHttpClient();
      try {
        const k = await H.push(() => _("@cart_ajax/updateQuantities", { values: f }));
        return await N();
      } catch (k) {
        console.error(k), V(k) && simpleAlert(k.message, "", "warning");
      } finally {
        c();
      }
    }, 600);
    async function ce() {
      if (A.value === "")
        return;
      E.push(!0);
      const { post: s, isAxiosError: f } = await useHttpClient();
      try {
        const _ = await s("@cart_ajax/addCode", { code: A.value });
        A.value = "", await N();
      } catch (_) {
        console.error(_), f(_) && simpleAlert(_.message, "", "warning");
      } finally {
        c();
      }
    }
    async function me(s) {
      E.push(!0);
      const { delete: f, isAxiosError: _ } = await useHttpClient();
      try {
        const V = await f("@cart_ajax/removeCode", { id: s });
        await N();
      } catch (V) {
        console.error(V), _(V) && simpleAlert(V.message, "", "warning");
      } finally {
        c();
      }
    }
    watch(() => B.value.locationId, () => {
      J();
    }), watch(() => y.value, () => {
      N(!1);
    });
    const oe = computed(() => D.value.find((s) => String(s.id) === String(y.value))), J = debounce(async function() {
      E.push(!0);
      const { get: s, isAxiosError: f } = await useHttpClient();
      try {
        const _ = await s(`@cart_ajax/shippings?location_id=${B.value.locationId}`);
        D.value = _.data.data, await nextTick(), await nextTick(), D.value.length > 0 ? oe.value || (y.value = D.value[0].id) : y.value = null;
      } catch (_) {
        console.error(_), f(_) && simpleAlert(_.message, "", "warning");
      } finally {
        c();
      }
    }, 300);
    watch(() => [B.value.locationId, y.value], () => {
      ne();
    });
    const ue = computed(() => $.value.find((s) => s.id === v.value)), ne = debounce(async function() {
      E.push(!0);
      const { get: s, isAxiosError: f } = await useHttpClient();
      try {
        const _ = await s(
          "@cart_ajax/payments",
          {
            params: {
              location_id: B.value.locationId,
              shipping_id: y.value
            }
          }
        );
        $.value = _.data.data, await nextTick(), await nextTick(), $.value.length > 0 ? $.value.find((V) => V.id === v.value) || (v.value = $.value[0].id) : v.value = null;
      } catch (_) {
        console.error(_), f(_) && simpleAlert(_.message, "", "warning");
      } finally {
        c();
      }
    }, 300), pe = computed(() => !(z.value === 0 || !B.value.locationId || !x.value.locationId || !y.value || !v.value)), G = ref(), K = ref();
    function _e() {
      if (z.value === 0) {
        console.warn("No checked items");
        return;
      }
      if (Number(C.value.grand_total.price) < 0) {
        swal("Cannot process cart with negative prices.", "", "warning");
        return;
      }
      for (const s of p.value) {
        if (Number(s.priceSet.final_total.price) < 0) {
          swal("Cannot process product items with negative prices.", "", "warning");
          return;
        }
        if (Number(s.priceSet.attached_final_total.price) < 0) {
          swal("Cannot process product items with negative prices.", "", "warning");
          return;
        }
      }
      if (G.value && !G.value.validate()) {
        console.log("Shipping Validate Fail");
        return;
      }
      if (K.value && !K.value.validate()) {
        console.log("Payment Validate Fail");
        return;
      }
      if (!T.checkValidity()) {
        T.reportValidity();
        const s = T.querySelector(":invalid");
        s && !ae(s) && s.dataset.validationMessage && simpleAlert(s.dataset.validationMessage);
        return;
      }
      P.value = !0, T.requestSubmit();
    }
    function ae(s) {
      return !!(s.offsetWidth || s.offsetHeight || s.getClientRects().length);
    }
    const le = { props: l, loaded: g, items: p, totals: C, coupons: S, paymentId: v, paymentData: x, shippingId: y, shippingData: B, shippings: D, payments: $, code: A, note: j, loading: P, partialCheckout: Q, queue: H, loadingStack: E, get abort() {
      return I;
    }, set abort(s) {
      I = s;
    }, form: T, toggleAllInput: w, inc: U, components: q, resolveOverrideComponent: F, AfterAddressForm: L, AfterShippingForm: O, AfterPaymentForm: m, AfterNoteForm: i, popLoading: c, afterItemsChanged: b, loadItems: N, setCartData: R, itemChecks: M, checks: z, unchecks: W, updateToggleAll: X, toggleChecked: se, updateChecks: Y, calcNavAndStickySidebar: Z, init: ee, removeItem: ie, clearCart: de, changeItemQuantity: re, updateQuantities: te, addCode: ce, removeCode: me, selectedShipping: oe, loadShippings: J, selectedPayment: ue, loadPayments: ne, canCheckout: pe, shippingForm: G, paymentForm: K, checkout: _e, isVisible: ae, AddressFormSet: n, CartForm: t, CartListItem: d, CartSidebar: u, PaymentSelector: o, ShippingSelector: r };
    return Object.defineProperty(le, "__isScriptSetup", { enumerable: !1, value: !0 }), le;
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
}, _hoisted_9 = { class: "l-cart-data d-flex flex-column gap-4" }, _hoisted_10 = { class: "l-cart-items" }, _hoisted_11 = { class: "l-cart-address-set" }, _hoisted_12 = { class: "l-checkout-note card mb-4" }, _hoisted_13 = { class: "card-body" }, _hoisted_14 = { class: "card-title mb-3" }, _hoisted_15 = ["placeholder"], _hoisted_16 = { class: "col-lg-4 l-cart-page__sidebar" };
function _sfc_render(a, e, n, t, d, u) {
  return openBlock(), createElementBlock("div", _hoisted_1, [
    createElementVNode("div", _hoisted_2, [
      createElementVNode("header", _hoisted_3, [
        createElementVNode("div", _hoisted_4, [
          createElementVNode("h3", _hoisted_5, toDisplayString(a.$lang("shopgo.cart.title")), 1),
          e[11] || (e[11] = createTextVNode()),
          t.partialCheckout ? (openBlock(), createElementBlock("div", _hoisted_6, [
            createElementVNode("input", {
              id: "input-toggle-all",
              type: "checkbox",
              class: "form-check-input",
              ref: "toggleAllInput",
              onClick: t.toggleChecked
            }, null, 512),
            e[10] || (e[10] = createTextVNode()),
            createElementVNode("label", _hoisted_7, toDisplayString(a.$lang("shopgo.cart.toggle.all")), 1)
          ])) : createCommentVNode("", !0),
          e[12] || (e[12] = createTextVNode()),
          t.loading ? (openBlock(), createElementBlock("div", _hoisted_8)) : createCommentVNode("", !0)
        ]),
        e[14] || (e[14] = createTextVNode()),
        createElementVNode("div", null, [
          createElementVNode("a", {
            href: "javascript://",
            onClick: t.clearCart
          }, [
            e[13] || (e[13] = createElementVNode("i", { class: "fa fa-times" }, null, -1)),
            createTextVNode(" " + toDisplayString(a.$lang("shopgo.cart.button.remove.all")), 1)
          ])
        ])
      ]),
      e[25] || (e[25] = createTextVNode()),
      createElementVNode("div", _hoisted_9, [
        createElementVNode("div", _hoisted_10, [
          (openBlock(!0), createElementBlock(Fragment, null, renderList(t.items, (o, r) => (openBlock(), createBlock(t.CartListItem, {
            key: o.key,
            item: o,
            "has-checkbox": t.partialCheckout,
            onRemoveItem: (l) => t.removeItem(o, r),
            onUpdateQuantity: (l) => t.updateQuantities(o),
            onChangeItemQuantity: (l) => t.changeItemQuantity(o, l),
            onUpdateChecks: t.updateChecks
          }, null, 8, ["item", "has-checkbox", "onRemoveItem", "onUpdateQuantity", "onChangeItemQuantity", "onUpdateChecks"]))), 128))
        ]),
        e[24] || (e[24] = createTextVNode()),
        createVNode(t.CartForm, {
          class: "l-cart-form d-flex flex-column gap-4",
          user: n.user,
          shippings: t.shippings,
          payments: t.payments,
          checkoutData: n.checkoutData,
          payment: t.paymentData,
          "onUpdate:payment": e[5] || (e[5] = (o) => t.paymentData = o),
          shipping: t.shippingData,
          "onUpdate:shipping": e[6] || (e[6] = (o) => t.shippingData = o),
          "shipping-id": t.shippingId,
          "onUpdate:shippingId": e[7] || (e[7] = (o) => t.shippingId = o),
          "payment-id": t.paymentId,
          "onUpdate:paymentId": e[8] || (e[8] = (o) => t.paymentId = o)
        }, {
          default: withCtx(() => [
            createElementVNode("div", _hoisted_11, [
              createVNode(t.AddressFormSet, {
                user: n.user,
                payment: t.paymentData,
                "onUpdate:payment": e[0] || (e[0] = (o) => t.paymentData = o),
                shipping: t.shippingData,
                "onUpdate:shipping": e[1] || (e[1] = (o) => t.shippingData = o)
              }, null, 8, ["user", "payment", "shipping"])
            ]),
            e[16] || (e[16] = createTextVNode()),
            t.AfterAddressForm ? (openBlock(), createBlock(resolveDynamicComponent(t.AfterAddressForm), { key: 0 })) : createCommentVNode("", !0),
            e[17] || (e[17] = createTextVNode()),
            createVNode(t.ShippingSelector, {
              shippings: t.shippings,
              shippingData: t.shippingData,
              modelValue: t.shippingId,
              "onUpdate:modelValue": e[2] || (e[2] = (o) => t.shippingId = o)
            }, null, 8, ["shippings", "shippingData", "modelValue"]),
            e[18] || (e[18] = createTextVNode()),
            t.AfterShippingForm ? (openBlock(), createBlock(resolveDynamicComponent(t.AfterShippingForm), { key: 1 })) : createCommentVNode("", !0),
            e[19] || (e[19] = createTextVNode()),
            createVNode(t.PaymentSelector, {
              payments: t.payments,
              paymentData: t.paymentData,
              shippingId: t.shippingId,
              modelValue: t.paymentId,
              "onUpdate:modelValue": e[3] || (e[3] = (o) => t.paymentId = o)
            }, null, 8, ["payments", "paymentData", "shippingId", "modelValue"]),
            e[20] || (e[20] = createTextVNode()),
            renderSlot(a.$slots, "after-payment"),
            e[21] || (e[21] = createTextVNode()),
            t.AfterPaymentForm ? (openBlock(), createBlock(resolveDynamicComponent(t.AfterPaymentForm), { key: 2 })) : createCommentVNode("", !0),
            e[22] || (e[22] = createTextVNode()),
            createElementVNode("div", _hoisted_12, [
              createElementVNode("div", _hoisted_13, [
                createElementVNode("h5", _hoisted_14, toDisplayString(a.$lang("shopgo.cart.field.note")), 1),
                e[15] || (e[15] = createTextVNode()),
                withDirectives(createElementVNode("textarea", {
                  rows: "4",
                  class: "form-control",
                  "onUpdate:modelValue": e[4] || (e[4] = (o) => t.note = o),
                  name: "checkout[note]",
                  placeholder: a.$lang("shopgo.cart.field.note.placeholder")
                }, null, 8, _hoisted_15), [
                  [vModelText, t.note]
                ])
              ])
            ]),
            e[23] || (e[23] = createTextVNode()),
            t.AfterNoteForm ? (openBlock(), createBlock(resolveDynamicComponent(t.AfterNoteForm), { key: 3 })) : createCommentVNode("", !0)
          ]),
          _: 3
        }, 8, ["user", "shippings", "payments", "checkoutData", "payment", "shipping", "shipping-id", "payment-id"])
      ])
    ]),
    e[26] || (e[26] = createTextVNode()),
    createElementVNode("div", _hoisted_16, [
      createVNode(t.CartSidebar, {
        totals: t.totals,
        coupons: t.coupons,
        loaded: t.loaded,
        loading: t.loading,
        selectedShipping: t.selectedShipping,
        selectedPayment: t.selectedPayment,
        canCheckout: t.canCheckout,
        code: t.code,
        "onUpdate:code": e[9] || (e[9] = (o) => t.code = o),
        onAddCode: t.addCode,
        onRemoveCode: t.removeCode,
        onCheckout: t.checkout
      }, null, 8, ["totals", "coupons", "loaded", "loading", "selectedShipping", "selectedPayment", "canCheckout", "code"])
    ])
  ]);
}
const CartApp__Tmp25236 = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render], ["__file", "CartApp.vue"]]), CartApp = resolveVueComponent("CartApp", CartApp__Tmp25236);
function initApp(a) {
  useCssImport("@vue-animate");
  const e = createApp(CartApp, a);
  return e.use(ShopGoPlugin), e;
}
export {
  initApp
};
//# sourceMappingURL=cart.js.map
