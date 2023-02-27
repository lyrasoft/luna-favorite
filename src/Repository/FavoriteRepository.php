<?php

/**
 * Part of starter project.
 *
 * @copyright      Copyright (C) 2021 __ORGANIZATION__.
 * @license        __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\Favorite\Repository;

use Lyrasoft\Favorite\Entity\Favorite;
use Unicorn\Attributes\ConfigureAction;
use Unicorn\Attributes\Repository;
use Unicorn\Repository\Actions\BatchAction;
use Unicorn\Repository\Actions\ReorderAction;
use Unicorn\Repository\Actions\SaveAction;
use Unicorn\Repository\ListRepositoryInterface;
use Unicorn\Repository\ListRepositoryTrait;
use Unicorn\Repository\ManageRepositoryInterface;
use Unicorn\Repository\ManageRepositoryTrait;
use Unicorn\Selector\ListSelector;
use Windwalker\Query\Query;

use function Windwalker\Query\val;

/**
 * The FavoriteRepository class.
 */
#[Repository(entityClass: Favorite::class)]
class FavoriteRepository implements ManageRepositoryInterface, ListRepositoryInterface
{
    use ManageRepositoryTrait;
    use ListRepositoryTrait;

    public function getListSelector(): ListSelector
    {
        $selector = $this->createSelector();

        $selector->from(Favorite::class);

        return $selector;
    }

    public function getFrontListSelector(string $type): ListSelector
    {
        $selector = $this->getListSelector();

        $selector->where('favorite.type', $type);

        return $selector;
    }

    public static function joinFavorite(
        Query|ListSelector $query,
        string $type,
        int|string $userId,
        string $idField
    ): Query|ListSelector {
        $query->selectRaw('IF(favorite.id IS NOT NULL, 1, 0) AS favorited');
        $query->leftJoin(
            Favorite::class,
            'favorite',
            [
                ['favorite.target_id', $idField],
                ['favorite.user_id', val($userId)],
                ['favorite.type', val($type)],
            ]
        );

        return $query;
    }

    #[ConfigureAction(SaveAction::class)]
    protected function configureSaveAction(SaveAction $action): void
    {
        //
    }

    #[ConfigureAction(ReorderAction::class)]
    protected function configureReorderAction(ReorderAction $action): void
    {
        //
    }

    #[ConfigureAction(BatchAction::class)]
    protected function configureBatchAction(BatchAction $action): void
    {
        //
    }
}
