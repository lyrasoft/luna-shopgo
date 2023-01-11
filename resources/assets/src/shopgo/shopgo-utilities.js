/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

class ShopGoUtilities {
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

window.ShopGoUtilities = ShopGoUtilities;

window.ShopGoAppPlugin = function (app) {
  app.config.compilerOptions.whitespace = 'preserve';
}
