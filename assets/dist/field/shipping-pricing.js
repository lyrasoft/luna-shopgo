System.register([], function (_export, _context) {
  "use strict";

  var ref, onMounted, computed, createApp, toRefs, reactive, ShippingPricing;
  return {
    setters: [],
    execute: function () {
      /**
       * Part of shopgo project.
       *
       * @copyright  Copyright (C) 2023 __ORGANIZATION__.
       * @license    __LICENSE__
       */
      // App
      ({
        ref,
        onMounted,
        computed,
        createApp,
        toRefs,
        reactive
      } = Vue);
      ShippingPricing = {
        name: 'ShippingPricingApp',
        props: {
          pricing: Object
        },
        setup(props) {
          var _props$pricing, _props$pricing2, _props$pricing3;
          u.$ui.iframeModal();
          const state = reactive({
            global: ((_props$pricing = props.pricing) === null || _props$pricing === void 0 ? void 0 : _props$pricing.global) || {
              free: false,
              pricing: getEmptyPricing()
            },
            locationCategories: ((_props$pricing2 = props.pricing) === null || _props$pricing2 === void 0 ? void 0 : _props$pricing2.locationCategories) || [],
            locations: ((_props$pricing3 = props.pricing) === null || _props$pricing3 === void 0 ? void 0 : _props$pricing3.locations) || [],
            currentItem: null
          });
          const selectModal = ref(null);
          const pricingModal = ref(null);
          function openLocationCategorySelector() {
            const url = u.route('category_modal', {
              callback: 'locationCategorySelected'
            });
            window.locationCategorySelected = function (_ref) {
              let {
                value: id,
                title
              } = _ref;
              state.locationCategories.push({
                id,
                title,
                free: false,
                pricing: getEmptyPricing()
              });
              selectModal.value.close();
            };
            selectModal.value.open(url, {
              size: 'modal-xl'
            });
          }
          function openLocationSelector() {
            const url = u.route('location_modal', {
              callback: 'locationSelected'
            });
            window.locationSelected = function (_ref2) {
              let {
                value: id,
                title,
                path
              } = _ref2;
              state.locations.push({
                id,
                title,
                path,
                free: false,
                pricing: getEmptyPricing()
              });
              selectModal.value.close();
            };
            selectModal.value.open(url, {
              size: 'modal-xl'
            });
          }
          function configurePricing(item) {
            state.currentItem = item;
            u.$ui.bootstrap.modal(pricingModal.value).show();
          }
          function getEmptyPricing() {
            const seg = getEmptyPricingSegment();
            seg.threshold = 0;
            return [seg];
          }
          function getEmptyPricingSegment() {
            return ShopgoVueUtilities.prepareVueItem({
              threshold: '',
              fee: ''
            });
          }
          function addPricingSegment() {
            let i = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
            state.currentItem.pricing.splice(i + 1, 0, getEmptyPricingSegment());
          }
          function removePricingSegment(i) {
            state.currentItem.pricing.splice(i, 1);
          }
          const finalResult = computed(() => {
            return JSON.stringify({
              global: state.global,
              locationCategories: state.locationCategories,
              locations: state.locations
            });
          });
          return {
            ...toRefs(state),
            finalResult,
            selectModal,
            pricingModal,
            openLocationSelector,
            openLocationCategorySelector,
            configurePricing,
            addPricingSegment,
            removePricingSegment
          };
        },
        create(el) {
          const id = el.getAttribute('id');
          const app = createApp(this, u.data(id + '.props'));
          app.use(ShopGoVuePlugin);
          app.component('draggable', vuedraggable);
          app.mount(el);
        }
      };
      window.ShippingPricing = ShippingPricing;
    }
  };
});
//# sourceMappingURL=shipping-pricing.js.map
