import { UnicornApp } from '@windwalker-io/unicorn-next';

declare class FavoriteButtonHandler {
    protected el: HTMLElement;
    protected icon: HTMLElement;
    protected added: boolean;
    protected type: string;
    protected id: string;
    protected u: UnicornApp;
    constructor(el: HTMLElement);
    toggleFavorite(): Promise<void>;
    refreshStyle(): void;
    /**
     * @param {string} className
     * @returns {string[]}
     */
    classToList(className: string): string[];
}

declare interface FavoriteButtonModule {
    FavoriteButtonHandler: typeof FavoriteButtonHandler;
    ready: typeof ready;
}

declare const ready: Promise<void>;

export declare function useFavoriteButton(): Promise<FavoriteButtonModule>;

export { }
