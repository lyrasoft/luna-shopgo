/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

window.ShopgoVueUtilities = class ShopgoVueUtilities {
  /**
   * @template {[name: string]: any} T
   * @param {T} item
   * @param {(item: T) => T} callback
   * @returns {object}
   */
  static prepareVueItem(item, callback = null) {
    if (callback) {
      item = callback(item);
    }

    item.uid = item.uid || u.uid();

    return item;
  }

  /**
   * @template {[name: string]: any} T
   * @param {T[]} items
   * @param {(item: T) => T} callback
   * @returns {object[]}
   */
  static prepareVueItemList(items, callback = null) {
    return items.map((item) => this.prepareVueItem(item, callback));
  }
}

window.ShopGoVuePlugin = function (app) {
  app.config.compilerOptions.whitespace = 'preserve';
}

// Directive

// Colorpicker
window.ShopGoVuePlugin.Colorpicker = {
  async mounted(el, { value }) {
    await u.$ui.colorPicker();
    Spectrum.getInstance(el, Object.assign({}, value));
  },
  updated(el, { value }) {
    const sp = Spectrum.getInstance(el, options);

    if (JSON.stringify(value) !== JSON.stringify(sp.options)) {
      sp.rebuild(Object.assign({}, value));
    }
  },
  unmounted(el) {
    const sp = Spectrum.getInstance(el);
    sp.destroy();
  }
};
