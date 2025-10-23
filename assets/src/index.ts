import { FavoriteButtonModule } from '~favorite/favorite-button';

export function useFavoriteButton(): Promise<FavoriteButtonModule> {
  return import('~favorite/favorite-button');
}
