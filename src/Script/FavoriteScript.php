<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\Favorite\Script;

use Lyrasoft\Luna\User\UserService;
use Psr\Http\Message\UriInterface;
use Unicorn\Script\UnicornScript;
use Windwalker\Core\Asset\AbstractScript;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\RouteUri;

/**
 * The FavoriteScript class.
 */
class FavoriteScript extends AbstractScript
{
    public function __construct(
        protected UnicornScript $unicornScript,
        protected Navigator $nav,
        protected UserService $userService,
    ) {
    }

    public function favoriteButton(UriInterface|string|null $loginUri = null): void
    {
        if ($this->available()) {
            if ($this->userService->isLogin()) {
                $this->unicornScript->data(
                    'favorite',
                    [
                        'isLogin' => true
                    ]
                );
            } else {
                $this->unicornScript->data(
                    'favorite',
                    [
                        'isLogin' => false,
                        'loginUri' => (string) ($loginUri ?? $this->nav->to('front::login')
                            ->withReturn()
                            ->full())
                    ]
                );
            }

            $this->unicornScript->addRoute('@favorite_ajax');

            $this->js('@favorite/favorite-button.js');
        }
    }
}
