<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\Favorite;

use Lyrasoft\Favorite\Script\FavoriteScript;
use Lyrasoft\Favorite\Service\FavoriteService;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Package\PackageInstaller;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;

/**
 * The FavoritePackage class.
 */
class FavoritePackage extends AbstractPackage implements ServiceProviderInterface
{
    public function __construct(protected ApplicationInterface $app)
    {
        //
    }

    public function register(Container $container): void
    {
        $container->share(static::class, $this);
        $container->prepareSharedObject(FavoriteScript::class);
        $container->prepareSharedObject(FavoriteService::class);

        // View
        $container->mergeParameters(
            'renderer.paths',
            [
                static::path('views'),
            ],
            Container::MERGE_OVERRIDE
        );

        $container->mergeParameters(
            'renderer.edge.components',
            [
                'favorite-button' => 'favorite.favorite-button',
            ]
        );

        // Assets
        $container->mergeParameters(
            'asset.import_map.imports',
            [
                '@favorite/' => 'vendor/lyrasoft/favorite/dist/',
            ]
        );
    }

    public function install(PackageInstaller $installer): void
    {
        $installer->installConfig(static::path('etc/*.php'), 'config');
        $installer->installLanguages(static::path('resources/languages/**/*.ini'), 'lang');
        $installer->installMigrations(static::path('resources/migrations/**/*'), 'migrations');
        // $installer->installSeeders(static::path('resources/seeders/**/*'), 'seeders');
        $installer->installRoutes(static::path('routes/**/*.php'), 'routes');
        $installer->installViews(static::path('views/*.blade.php'), 'views');
        $installer->installModules(
            [
                static::path("src/Entity/Favorite.php") => '@source/Entity',
                static::path("src/Repository/FavoriteRepository.php") => '@source/Repository',
            ],
            [
                'Lyrasoft\\Favorite\\Entity' => 'App\\Entity',
                'Lyrasoft\\Favorite\\Repository' => 'App\\Repository',
            ],
            ['modules', 'model']
        );
    }

    public function config(string $name, ?string $delimiter = '.'): mixed
    {
        return $this->app->config('favorite' . $delimiter . $name, $delimiter);
    }

    public function checkAjaxType(string $type): bool
    {
        if (!$this->config('ajax.type_protect')) {
            return true;
        }

        $types = (array) $this->config('ajax.allow_types');

        return in_array($type, $types, true);
    }
}
