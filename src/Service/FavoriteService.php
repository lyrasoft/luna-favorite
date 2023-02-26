<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\Favorite\Service;

use Lyrasoft\Favorite\Entity\Favorite;
use Windwalker\Database\Driver\StatementInterface;
use Windwalker\ORM\ORM;

/**
 * The FavoriteService class.
 */
class FavoriteService
{
    public function __construct(protected ORM $orm)
    {
    }

    public function getFavorite(string $type, int|string $userId, int|string $targetId): ?Favorite
    {
        return $this->orm->findOne(
            Favorite::class,
            ['user_id' => $userId, 'target_id' => $targetId, 'type' => $type]
        );
    }


    public function addFavorite(string $type, int|string $userId, int|string $targetId): Favorite
    {
        if ($favorite = $this->getFavorite($type, $userId, $targetId)) {
            return $favorite;
        }

        $favorite = new Favorite();
        $favorite->setType($type);
        $favorite->setUserId($userId);
        $favorite->setTargetId($targetId);

        return $this->orm->createOne(Favorite::class, $favorite);
    }

    /**
     * @param  string      $type
     * @param  int|string  $userId
     * @param  int|string  $targetId
     *
     * @return  array<StatementInterface>
     */
    public function removeFavorite(string $type, int|string $userId, int|string $targetId): array
    {
        return $this->orm->deleteWhere(
            Favorite::class,
            ['user_id' => $userId, 'target_id' => $targetId, 'type' => $type]
        );
    }

    public function isFavorited(string $type, int|string $userId, int|string $targetId): bool
    {
        return $this->getFavorite($type, $userId, $targetId) !== null;
    }
}
