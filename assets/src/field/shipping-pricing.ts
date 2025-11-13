import { uniqueItem } from '@lyrasoft/ts-toolkit/vue';
import { data, IFrameModalElement, route, useIframeModal } from '@windwalker-io/unicorn-next';
import { Modal } from 'bootstrap';
import { computed, createApp, defineComponent, PropType, ref } from 'vue';
import { ShopGoPlugin } from '~shopgo/shopgo-plugin';

useIframeModal();

type PricingSegment = {
  fee: number | string;
  threshold: number | string;
  uid: string;
}

type LocationPricing = {
  id?: string;
  title?: string;
  path?: string;
  free: boolean;
  pricing: PricingSegment[];
}

const ShippingPricingEditApp = defineComponent({
  name: 'ShippingPricingEditApp',
  props: {
    pricing: Object as PropType<{
      global: LocationPricing;
      locationCategories: LocationPricing[];
      locations: LocationPricing[];
    }>
  },
  setup(props) {
    const global = ref(props.pricing?.global || {
      free: false,
      pricing: getEmptyPricing(),
    });

    const locationCategories = ref(props.pricing?.locationCategories || []);
    const locations = ref(props.pricing?.locations || []);
    const currentItem = ref(null as LocationPricing | null);

    const selectModal = ref<IFrameModalElement>();
    const pricingModal = ref<HTMLDivElement>();

    function openLocationCategorySelector() {
      const url = route('category_modal', { callback: 'locationCategorySelected' });

      // @ts-ignore
      window.locationCategorySelected = function ({ value: id, title }) {
        locationCategories.value.push({
          id: id as string,
          title: title as string,
          free: false,
          pricing: getEmptyPricing(),
        });

        selectModal.value?.close();
      };

      selectModal.value?.open(url, { size: 'modal-xl' });
    }

    function openLocationSelector() {
      const url = route('location_modal', { callback: 'locationSelected' });

      // @ts-ignore
      window.locationSelected = function ({ value: id, title, path }) {

        locations.value.push({
          id: id as string,
          title: title as string,
          path: path as string,
          free: false,
          pricing: getEmptyPricing(),
        });

        selectModal.value?.close();
      };

      selectModal.value?.open(url, { size: 'modal-xl' });
    }

    function calcLocationPricingCount(location: LocationPricing) {
      return location.pricing.filter((item) => item.fee !== '' && item.threshold !== '').length;
    }

    function configurePricing(item: LocationPricing) {
      currentItem.value = item;

      Modal.getOrCreateInstance(pricingModal.value!).show();
    }

    function getEmptyPricing() {
      const seg = getEmptyPricingSegment();
      seg.threshold = 0;

      return [seg];
    }

    function getEmptyPricingSegment(): PricingSegment {
      return uniqueItem(
        {
          threshold: '',
          fee: '',
        }
      );
    }

    function addPricingSegment(i = 0) {
      currentItem.value?.pricing.splice(i + 1, 0, getEmptyPricingSegment());
    }

    function removePricingSegment(i: number) {
      currentItem.value?.pricing.splice(i, 1);
    }

    const finalResult = computed(() => {
      return JSON.stringify(
        {
          global: global.value,
          locationCategories: locationCategories.value,
          locations: locations.value,
        }
      );
    });

    return {
      global,
      locationCategories,
      locations,
      currentItem,
      finalResult,
      selectModal,
      pricingModal,

      openLocationSelector,
      openLocationCategorySelector,
      calcLocationPricingCount,
      configurePricing,
      addPricingSegment,
      removePricingSegment,
    };
  },
});

export function useShippingPricingEditApp(el: HTMLDivElement) {
  const id = el.getAttribute('id');

  const app = createApp(
    ShippingPricingEditApp,
    data(id + '.props')
  );

  app.use(ShopGoPlugin);
  // app.component('VueDraggable', VueDraggable);
  app.mount(el);
}
