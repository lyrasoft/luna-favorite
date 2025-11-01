export declare class FavoriteButtonHandler {
    protected el: HTMLElement;
    protected icon: HTMLElement;
    protected added: boolean;
    protected type: string;
    protected id: string;
    constructor(el: HTMLElement);
    off(): void;
    private onClick;
    toggleFavorite(): Promise<void>;
    refreshStyle(): void;
    /**
     * @param {string} className
     * @returns {string[]}
     */
    classToList(className: string): string[];
}

export declare function useFavoriteButton(): Promise<void>;

export { }
