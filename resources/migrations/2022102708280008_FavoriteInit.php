<?php

/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2022.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Migration;

use Lyrasoft\Favorite\Entity\Favorite;
use Windwalker\Core\Console\ConsoleApplication;
use Windwalker\Core\Migration\Migration;
use Windwalker\Database\Schema\Schema;

/**
 * Migration UP: 2022102708280008_FavoriteInit.
 *
 * @var Migration          $mig
 * @var ConsoleApplication $app
 */
$mig->up(
    static function () use ($mig) {
        $mig->createTable(
            Favorite::class,
            function (Schema $schema) {
                $schema->primary('id');
                $schema->char('type')->length(20);
                $schema->integer('user_id');
                $schema->integer('target_id');
                $schema->datetime('created');

                $schema->addIndex('type');
                $schema->addIndex('user_id');
                $schema->addIndex('product_id');
            }
        );
    }
);

/**
 * Migration DOWN.
 */
$mig->down(
    static function () use ($mig) {
        $mig->dropTables(Favorite::class);
    }
);
