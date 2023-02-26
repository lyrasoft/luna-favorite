<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\Favorite\Script;

use Unicorn\Script\UnicornScript;
use Windwalker\Core\Asset\AbstractScript;

/**
 * The FavoriteScript class.
 */
class FavoriteScript extends AbstractScript
{
    public function __construct(protected UnicornScript $unicornScript)
    {
    }

    public function favoriteButton(): void
    {
        if ($this->available()) {
            $this->unicornScript->addRoute('@favorite_ajax');

            $this->js('@favorite/favorite-button.js');
        }
    }
}
