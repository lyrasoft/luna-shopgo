System.register(["@main"], function (_export, _context) {
  "use strict";

  var WishlistButton;
  return {
    setters: [function (_main) {}],
    execute: function () {
      /**
       * Part of shopgo project.
       *
       * @copyright  Copyright (C) 2023 __ORGANIZATION__.
       * @license    __LICENSE__
       */
      WishlistButton = class WishlistButton {
        constructor(el) {
          this.el = el;
          this.icon = el.querySelector('i, span');
          this.added = el.dataset.added === '1' || el.dataset.added === 'true';
          this.type = el.dataset.type;
          this.id = el.dataset.id;
          this.el.addEventListener('click', async () => {
            await this.toggleFavorite();
            this.refreshStyle();
          });
          this.refreshStyle();
        }
        async toggleFavorite() {
          const task = this.added ? 'removeWishlist' : 'addWishlist';
          try {
            const res = await u.$http.post(`@wishlist_ajax/${task}`, {
              id: this.id
            });
            u.notify(res.data.message);
            this.added = !this.added;
            this.el.dataset.added = this.added ? '1' : '0';
          } catch (e) {
            console.error(e);
            u.alert(e.message, '', 'warning');
            throw e;
          }
        }
        refreshStyle() {
          if (this.added) {
            this.icon.setAttribute('class', this.el.dataset.iconActive);
          } else {
            this.icon.setAttribute('class', this.el.dataset.iconInactive);
          }
        }
      };
      u.directive('wishlist-button', {
        mounted(el) {
          setTimeout(() => {
            u.module(el, 'wishlist.button', () => new WishlistButton(el));
          }, 100);
        }
      });
    }
  };
});
//# sourceMappingURL=wishlist-button.js.map
