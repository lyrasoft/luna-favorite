# LYRASOFT Favorite Package

## Installation

Install from composer

```shell
composer require lyrasoft/favorite
```

Then copy files to project

```shell
php windwalker pkg:install lyrasoft/favorite -t routes -t migrations
```

This package has no seeders, you must add your own seeders.

### Language Files

Add this line to admin & front middleware if you don't want to override languages:

```php
$this->lang->loadAllFromVendor('lyrasoft/shopgo', 'ini');
```

Or run this command to copy languages files:

```shell
php windwalker pkg:install lyrasoft/favorite -t lang
```

### CSS/JS

Add these vendors to `fusionfile.mjs`

```javascript
export async function install() {
  return installVendors(
    [
      // ...
    ],
    [
      // Add this
      'lyrasoft/favorite'
    ]
  );
}
```

## Seeders

Write your own seeders with different `type`:

```php
use Lyrasoft\Favorite\Entity\Favorite;

$mapper = $orm->mapper(Favorite::class);

foreach ($faker->randomElements($items, 5) as $item) {
    $favorite = new Favorite();
    $favorite->setType('item');
    $favorite->setUserId($user->getId());
    $favorite->setTargetId($item->getId());
    
    $mapper->createOne($favorite);
}
```

## Add AJAX Buttons

You can add button component in blade templates:

```html
<div class="card c-item-card">
    <x-favorite-button
        type="item"
        :id="$item->getId()"
        :added="$item->favorited"
        class="..."
    ></x-favorite-button>

    <div class="card-body">
        ...
    </div>
</div>
```

Available params:

| Name             | Type          | Description                               |
|------------------|---------------|-------------------------------------------|
| `type`           | string        | The favorite type                         |
| `id`             | string or int | The item ID                               |
| `added`          | bool or int   | Current is favorited or not in this page. |
| `class-active`   | string        | The button class if favorited.            |
| `class-inactive` | string        | The button class if not favorited.        |
| `icon-active`    | string        | The icon class if favorited.              |
| `icon-inactive`  | string        | The button class if not favorited.        |
| `title-active`   | string        | The tooltip title if favorited.           |
| `title-inactive` | string        | The tooltip title if not favorited.       |

### AJAX Type Protect

By default, favorite package will not allow any types sent from browser.

You can configre allowed types in config file:

```php
return [
    'favorite' => [
        // ...

        'ajax' => [
            'type_protect' => true,
            'allow_types' => [
                'article',
                '...' // <-- Add your new types here
            ]
        ],
    ]
];
```

You can also set the `type_protect` to `FALSE` but we don't recommend to do this.

### AJAX Events

You can listen events after favorited actions:

```javascript
// Select all favorite buttons, you can use your own class to select it.
const buttons = document.querySelectorAll('[uni-favorite-button]');

for (const button of buttons) {
  button.addEventListener('favorited', (e) => {
    u.notify(e.detail.message, 'success');
    
    // Available details
    e.detail.favorited;
    e.detail.type;
    e.detail.task;
    e.detail.message;
  });
}
```

Or listen globally:

```javascript
document.addEventListener('favorited', (e) => {
  if (e.detail.type === 'product') {
    if (e.detail.favorited) {
      u.notify('已收藏', 'success');
    } else {
      u.notify('已取消收藏', 'success');
    }
  }
});
```

### Add Button to Vue App

Use `uni-favorite-button` directive to auto enale button in Vue app.

```html
<a href="javascript://"
    uni-favorite-button
    :data-added="favorited"
    :data-type="type"
    data-class-active=""
    data-class-inactive=""
    data-icon-active="fas fa-heart"
    data-icon-inactive="far fa-heart"
    data-title-active="..."
    data-title-inactive="..."
>
    <i></i>
</a>
```

## Use `FavoriteRepository`

### Join to List

```php
use Lyrasoft\Favorite\Repository\FavoriteRepository;

    // In any repository

    public function getFrontListSelector(?User $user = null): ListSelector
    {
        $selector = $this->getListSelector();

        if ($user && $user->isLogin()) {
            FavoriteRepository::joinFavorite(
                $selector,
                'item',
                $user->getId(),
                'item.id'
            );
        }
        
        // ...
```

In blade:

```html
@foreach ($items of $item)
<div>
    ...
    <x-favorite-button
        type="item"
        :id="$item->getId()"
        :added="$item->favorited
        class="..."
    ></x-favorite-button>
    ...
</div>
@endforeach
```


## Use `FavoriteService`

You can use `FavoriteService` to add/remove or check favorited items.

```php
$favoriteService = $app->service(\Lyrasoft\Favorite\Service\FavoriteService::class);

$favoriteService->addFavorite($type, $user->getId(), $targetId);

$favoriteService->removeFavorite($type, $user->getId(), $targetId);

$favoriteService->isFavorited($type, $user->getId(), $targetId);
```


