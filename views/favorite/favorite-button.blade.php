<?php

declare(strict_types=1);

namespace App\view;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $vm        object          The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Edge\Component\ComponentAttributes;

/**
 * @var $attributes ComponentAttributes
 */

$added ??= false;

$tag ??= 'a';

$attributes->props(
    'added',
    'id',
    'class-active',
    'class-inactive',
    'icon-active',
    'icon-inactive',
    'title-active',
    'title-inactive',
    'type',
);

$attributes = $attributes->class('');

if ($tag === 'a') {
    $attributes['href'] = 'javascript://';
}

$attributes['uni-favorite-button'] = true;
$attributes['data-added'] = (int) $added;
$attributes['data-id'] = (int) $id;
$attributes['data-type'] = $type;
$attributes['data-class-active'] = $classActive ??= '';
$attributes['data-class-inactive'] = $classInactive ??= '';
$attributes['data-icon-active'] = $iconActive ??= 'fas fa-heart';
$attributes['data-icon-inactive'] = $iconInactive ??= 'far fa-heart';
$attributes['data-title-active'] = $titleActive ??= $lang('luna.favorite.tooltip.active');
$attributes['data-title-inactive'] = $titleInactive ??= $lang('luna.favorite.tooltip.inactive')

?>

<a {!! $attributes !!}>
    <i class="{{ $iconInactive }}"></i>
</a>
