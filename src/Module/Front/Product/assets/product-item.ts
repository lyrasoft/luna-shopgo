import { ShopGoPlugin } from '@lyrasoft/shopgo';
import { useSwiper } from '@lyrasoft/shopgo';
import { Discount, ListOption, Product, ProductFeature, ProductVariant } from '@lyrasoft/shopgo/src/types';
import { data, useHttpClient } from '@windwalker-io/unicorn-next';
import {
  computed,
  createApp,
  defineComponent,
  onMounted,
  PropType,
  ref,
  useTemplateRef,
  watch
} from 'vue';

// Click quantity make auto select
const qtyInputs = document.querySelectorAll<HTMLInputElement>('[data-role=attachment_quantity]');

for (const qtyInput of qtyInputs) {
  qtyInput.addEventListener('click', () => {
    const idInput = qtyInput.closest<HTMLDivElement>('[data-role=attachment]')
      ?.querySelector<HTMLInputElement>('[data-role=attachment_id]');

    if (idInput) {
      idInput.checked = true;
      idInput.dispatchEvent(new CustomEvent('change'));
    }
  });
}

// Add highlight after selected
const idInputs = document.querySelectorAll<HTMLInputElement>('[data-role=attachment_id]');

for (const idInput of idInputs) {
  idInput.addEventListener('change', () => {
    idInput.closest('[data-role=attachment]')
      ?.classList.toggle('border-primary', idInput.checked);
  });
}

// Additional Purchase Slides
useSwiper('.l-additional-purchases__slides', {
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  slidesPerView: 4,
  spaceBetween: 15,
  rewind: true,
});

// Product Main App
const ProductItemApp = defineComponent({
  name: 'ProductItemApp',
  props: {
    product: {
      type: Object as PropType<Product>,
      required: true
    },
    features: {
      type: Object as PropType<ProductFeature[]>,
      required: true
    },
    mainVariant: {
      type: Object as PropType<ProductVariant>,
      required: true
    },
    discounts: {
      type: Array as PropType<Discount[]>,
      required: true,
    },
  },
  setup(props) {
    // Split state into independent refs
    const imageView = ref<string>(data('image.default'));
    const selected = ref<Record<number, any>>({});
    const currentVariant = ref<ProductVariant | null>(null);
    const hasSubVariants = ref<boolean>(props.product.variants !== 0);
    const quantity = ref<number>(1);

    if (!hasSubVariants.value) {
      currentVariant.value = props.mainVariant;
    }

    const hasDiscount = computed(() => {
      return Number(currentVariant.value?.priceSet.base.price) !== Number(currentVariant.value?.priceSet?.final?.price);
    });

    // Stock
    const outOfStock = computed(() => {
      if (!currentVariant.value?.subtract) {
        return false;
      }

      return Number(currentVariant.value.stockQuantity) - props.product.safeStock < quantity.value;
    });

    // Quantity
    watch(quantity, (qty) => {
      if (qty < 1) {
        quantity.value = 1;
      }
    });

    // Images
    const sliders = useTemplateRef<HTMLDivElement>('slides');

    onMounted(() => {
      useSwiper(sliders.value!, {
        simulateTouch: true,
        allowTouchMove: true,
        autoHeight: true,
        slidesPerView: 6,
        spaceBetween: 8,
        observer: true,
        rewind: true,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      });
    });

    const images = computed<{ url: string; [name: string]: any; }[]>(() => {
      let imgs: { url: string; [name: string]: any; }[] = [];

      if (currentVariant.value) {
        if (!currentVariant.value.primary) {
          imgs = [...props.mainVariant.images, ...currentVariant.value.images];
        } else {
          imgs = currentVariant.value.images;
        }
      } else {
        imgs = props.mainVariant.images;
      }

      return imgs;
    });

    watch(() => images, () => {
      imageView.value = images.value[0]?.url || data('image.default');
    }, { immediate: true });

    const allSelected = computed(() => {
      return props.features.length === Object.values(selected.value).length;
    });

    watch(selected, () => {
      if (allSelected.value) {
        findVariant();
      }
    }, { deep: true });

    async function findVariant() {
      const options = Object.values(selected.value).map(option => option.uid);

      const { get } = await useHttpClient();

      const res = await get(
        '@product_ajax/getVariant',
        {
          params: {
            product_id: props.product.id,
            options
          }
        }
      );

      const { variant } = res.data.data;

      currentVariant.value = variant;
    }

    const errorMsg = 'shopgo.product.message.variant.not.found';

    function toggleOption(option: ListOption, feature: ProductFeature) {
      // mutate the object inside the ref - keep reactivity
      selected.value[feature.id] = option;
    }

    function isSelected(option: ListOption, feature: ProductFeature) {
      return selected.value[feature.id]?.uid === option.uid;
    }

    // Discounts
    const discountNotices = computed(() => {
      if (!currentVariant.value) {
        return [];
      }

      const items: any[] = [];

      for (const discount of props.discounts) {
        let price = null;

        if (discount.method === 'fixed') {
          price = discount.price;
        } else if (discount.method === 'offsets') {
          price = currentVariant.value.price + discount.price;
        } else {
          price = currentVariant.value.price * discount.price / 100;
        }

        const item = {
          minProductQuantity: discount.minProductQuantity,
          price
        };

        items.push(item);
      }

      return items;
    });

    return {
      imageView,
      selected,
      currentVariant,
      hasSubVariants,
      quantity,

      allSelected,
      hasDiscount,
      outOfStock,
      sliders,
      images,
      discountNotices,

      toggleOption,
      isSelected,
    };
  }
});

const app = createApp(ProductItemApp, data('product.item.props'));

app.use(ShopGoPlugin);
app.mount('#product-item-app');
