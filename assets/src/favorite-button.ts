import {
  UnicornApp,
  useUnicorn,
  useHttpClient,
  simpleAlert,
  useBs5Tooltip,
  useUniDirective,
  module,
} from '@windwalker-io/unicorn-next';

export class FavoriteButtonHandler {
  protected el: HTMLElement;
  protected icon: HTMLElement;
  protected added: boolean;
  protected type: string;
  protected id: string;
  protected u: UnicornApp;

  constructor(el: HTMLElement) {

    this.el = el!;
    this.icon = el.querySelector('i, span')!;
    this.added = el.dataset.added === '1' || el.dataset.added === 'true';
    this.type = el.dataset.type!;
    this.id = el.dataset.id!;
    this.u = useUnicorn();

    this.el.addEventListener('click', async () => {
      await this.toggleFavorite();
      this.refreshStyle();
    });

    this.refreshStyle();
  }

  async toggleFavorite() {
    const favData = this.u.data('favorite');

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
  classToList(className: string) {
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
      }
    }
  );
}

export const ready = init();

export interface FavoriteButtonModule {
  FavoriteButtonHandler: typeof FavoriteButtonHandler;
  ready: typeof ready;
}
