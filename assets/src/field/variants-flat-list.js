/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

u.directive('variants-flat-list', {
  mounted(el, { value }) {
    value = JSON.parse(value || '[]') || [];

    u.module(el, 'variants.flat.list', () => new VariantsFlatList(el, value));
  }
});

class VariantsFlatList {
  el;
  value = [];

  constructor(el, value) {
    this.value = value;
    this.el = el;
    this.productInput = u.selectOne(this.el.dataset.productSelector);

    // this.productInput.addEventListener('change', () => {
    //   this.updateVariantsList();
    // });

    this.updateVariantsList();
  }

  updateVariantsList() {

  }
}
