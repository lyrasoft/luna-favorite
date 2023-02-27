<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\Favorite\Module\Front\Favorite;

use Lyrasoft\Favorite\Entity\Favorite;
use Lyrasoft\Favorite\FavoritePackage;
use Lyrasoft\Favorite\Service\FavoriteService;
use Lyrasoft\Luna\User\UserService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Attributes\JsonApi;
use Windwalker\Core\Http\RequestAssert;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Security\Exception\UnauthorizedException;
use Windwalker\ORM\ORM;

/**
 * The WishlistController class.
 */
#[Controller(
    config: __DIR__ . '/wishlist.config.php'
)]
class FavoriteController
{
    use TranslatorTrait;

    #[JsonApi]
    public function ajax(AppContext $app): mixed
    {
        $task = $app->input('task');

        return $app->call([$this, $task]);
    }

    public function addFavorite(
        AppContext $app,
        UserService $userService,
        FavoritePackage $favoritePackage,
        FavoriteService $favoriteService,
    ): Favorite {
        $id = $app->input('id');
        $type = $app->input('type');

        RequestAssert::assert($id, 'No ID');
        RequestAssert::assert($type, 'No type');

        $user = $userService->getUser();

        if (!$user->isLogin()) {
            throw new UnauthorizedException($this->trans('luna.favorite.ajax.message.please.login'), 401);
        }

        if (!$favoritePackage->checkAjaxType($type)) {
            throw new \RuntimeException('Type is not allow.');
        }

        $favorite = $favoriteService->addFavorite($type, $user->getId(), $id);

        $message = $this->trans('luna.favorite.ajax.message.added');

        $app->addMessage($message);

        return $favorite;
    }

    public function removeFavorite(
        AppContext $app,
        UserService $userService,
        FavoritePackage $favoritePackage,
        FavoriteService $favoriteService,
    ): bool {
        $id = $app->input('id');
        $type = $app->input('type');

        RequestAssert::assert($id, 'No ID');
        RequestAssert::assert($type, 'No type');

        $user = $userService->getUser();

        if (!$user->isLogin()) {
            throw new UnauthorizedException($this->trans('luna.favorite.ajax.message.please.login'), 401);
        }

        if (!$favoritePackage->checkAjaxType($type)) {
            throw new \RuntimeException('Type is not allow.');
        }

        $favoriteService->removeFavorite($type, $user->getId(), $id);

        $message = $this->trans('luna.favorite.ajax.message.remove');

        $app->addMessage($message);

        return true;
    }
}
