import { useColorPicker } from '@windwalker-io/unicorn-next';
import type { ObjectDirective } from 'vue';

export const vColorpicker: ObjectDirective = {
  async mounted(el, { value }) {
    await useColorPicker(el, Object.assign({}, value));
  },
  async updated(el, { value }) {
    const sp = await useColorPicker(el);

    if (JSON.stringify(value) !== JSON.stringify(sp.options)) {
      sp.rebuild(Object.assign({}, value));
    }
  },
  async unmounted(el) {
    const sp = await useColorPicker(el);
    sp.destroy();
  }
};

