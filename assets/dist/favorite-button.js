System.register(["@main"], function (_export, _context) {
  "use strict";

  var FavoriteButton;
  return {
    setters: [function (_main) {}],
    execute: function () {
      /**
       * Part of shopgo project.
       *
       * @copyright  Copyright (C) 2023 __ORGANIZATION__.
       * @license    __LICENSE__
       */
      FavoriteButton = class FavoriteButton {
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
          const favData = u.data('favorite');
          if (!favData.isLogin) {
            location.href = favData.loginUri;
            return;
          }
          const task = this.added ? 'removeFavorite' : 'addFavorite';
          try {
            const res = await u.$http.post(`@favorite_ajax/${task}`, {
              id: this.id,
              type: this.type
            });
            this.el.dispatchEvent(new CustomEvent('favorited', {
              detail: {
                favorited: !this.added,
                task,
                type: this.type,
                message: res.data.message
              },
              bubbles: true
            }));
            this.added = !this.added;
            this.el.dataset.added = this.added ? '1' : '0';
          } catch (e) {
            console.error(e);
            u.alert(e.message, '', 'warning');
            throw e;
          }
        }
        refreshStyle() {
          if (this.el.dataset.classInactive || this.el.dataset.classActive) {
            this.el.classList.remove(...this.classToList(this.el.dataset.classInactive), ...this.classToList(this.el.dataset.classActive));
          }
          if (this.added) {
            this.icon.setAttribute('class', this.el.dataset.iconActive);
            if (this.el.dataset.classActive) {
              this.el.classList.add(...this.classToList(this.el.dataset.classActive));
            }
            this.el.setAttribute('data-bs-original-title', this.el.dataset.titleActive);
          } else {
            this.icon.setAttribute('class', this.el.dataset.iconInactive);
            if (this.el.dataset.classInactive) {
              this.el.classList.add(...this.classToList(this.el.dataset.classInactive));
            }
            this.el.setAttribute('data-bs-original-title', this.el.dataset.titleInactive);
          }
          setTimeout(() => {
            const tooltip = u.$ui.bootstrap.tooltip(this.el);
            tooltip.update();
          }, 50);
        }

        /**
         * @param {string} className
         * @returns {string[]}
         */
        classToList(className) {
          return className.split(' ');
        }
      };
      u.directive('favorite-button', {
        mounted(el) {
          setTimeout(() => {
            u.module(el, 'favorite.button', () => new FavoriteButton(el));
          }, 0);
        }
      });
    }
  };
});
//# sourceMappingURL=favorite-button.js.map
