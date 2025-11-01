import {
  useHttpClient,
  simpleAlert,
  useBs5Tooltip,
  useUniDirective,
  module,
  data, removeData
} from '@windwalker-io/unicorn-next';

export class FavoriteButtonHandler {
  protected el: HTMLElement;
  protected icon: HTMLElement;
  protected added: boolean;
  protected type: string;
  protected id: string;

  constructor(el: HTMLElement) {

    this.el = el!;
    this.icon = el.querySelector('i, span')!;
    this.added = el.dataset.added === '1' || el.dataset.added === 'true';
    this.type = el.dataset.type!;
    this.id = el.dataset.id!;

    this.el.addEventListener('click', this.onClick.bind(this));

    this.refreshStyle();
  }

  off() {
    this.el.removeEventListener('click', this.onClick);
  }

  private async onClick() {
    await this.toggleFavorite();
    this.refreshStyle();
  }

  async toggleFavorite() {
    const favData = data('favorite');

    if (!favData.isLogin) {
      location.href = favData.loginUri;
      return;
    }

    const task = this.added ? 'removeFavorite' : 'addFavorite';
    const { post, isAxiosError } = await useHttpClient();
    try {

      const res = await post(
        `@favorite_ajax/${task}`,
        {
          id: this.id,
          type: this.type,
        }
      );

      this.el.dispatchEvent(
        new CustomEvent('favorited', {
          detail: {
            favorited: !this.added,
            task,
            type: this.type,
            message: res.data.message,
          },
          bubbles: true
        })
      );

      this.added = !this.added;
      this.el.dataset.added = this.added ? '1' : '0';
    } catch (e) {
      if (isAxiosError(e)) {
        await simpleAlert(e.message, '', 'warning');
      }

      console.error(e);
      throw e;
    }
  }

  refreshStyle() {
    if (this.el.dataset.classInactive || this.el.dataset.classActive) {
      this.el.classList.remove(
        ...this.classToList(this.el.dataset.classInactive!),
        ...this.classToList(this.el.dataset.classActive!)
      );
    }

    if (this.added) {
      this.icon.setAttribute('class', this.el.dataset.iconActive!);

      if (this.el.dataset.classActive) {
        this.el.classList.add(
          ...this.classToList(this.el.dataset.classActive)
        );
      }

      this.el.setAttribute('data-bs-original-title', this.el.dataset.titleActive!);
    } else {
      this.icon.setAttribute('class', this.el.dataset.iconInactive!);

      if (this.el.dataset.classInactive) {
        this.el.classList.add(
          ...this.classToList(this.el.dataset.classInactive)
        );
      }

      this.el.setAttribute('data-bs-original-title', this.el.dataset.titleInactive!);
    }

    setTimeout(async () => {
      const tooltip = await useBs5Tooltip(this.el);

      tooltip[0].update();
    }, 50);
  }

  /**
   * @param {string} className
   * @returns {string[]}
   */
  classToList(className: string): string[] {
    return className.split(' ');
  }
}

async function init() {
  return useUniDirective<HTMLElement>(
    'favorite-button',
    {
      mounted(el) {
        setTimeout(() => {
          module(el, 'favorite.button', (el) => new FavoriteButtonHandler(el));
        }, 0);
      },
      unmounted(el) {
        const instance = module<FavoriteButtonHandler>(el, 'favorite.button');

        instance?.off();

        removeData(el, 'favorite.button');
      }
    }
  );
}

export function useFavoriteButton() {
  return useUniDirective<HTMLElement>(
    'favorite-button',
    {
      mounted(el) {
        setTimeout(() => {
          module(el, 'favorite.button', (el) => new FavoriteButtonHandler(el));
        }, 0);
      },
      unmounted(el) {
        const instance = module<FavoriteButtonHandler>(el, 'favorite.button');

        instance?.off();

        removeData(el, 'favorite.button');
      }
    }
  );
}
