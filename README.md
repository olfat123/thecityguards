# Initialization

- Fork this repo and create a repo in project **WordPress-Sage**
- Clone the new repo
- Run **composer install** in the theme folder
- Place the asset provided by UI in the public folder
- Go to wp-content/themes/migaloz/app/setup.php and adjust your assets
- Create empty database in phpmyadmin
- Open the project in the browser localhost to start wordpress installer
- Make the table prefix **wp_migaloz** followed by today date, example: today is 16 Dec, Prefix: **wp_migaloz1612_**
- Make the admin username: migaloz_admin and use the strong generated password
- Activate Road9 Media Theme
- Activate needed plugins
- Go to Settings -> Permalinks and select post-name option
- Go to Settings -> WPS Hide Login and make the login URL = adminaccess
- Configure **WPML**
    - Get Licence Key and set Credit = 5000
    - Translation Mode: Manual
    - Remove default switcher from WPML -> Languages -> Footer language switcher
- Add User for the client with Role: **Admin** and username: websiteTitle_admin and use generated password
- Check wp-content/cache/acorn/framework/cache/packages.php if it's empty , paste this code into it

```php
<?php return array (
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/termwind' => 
  array (
    'providers' => 
    array (
      0 => 'Termwind\\Laravel\\TermwindServiceProvider',
    ),
  ),
  'roots/sage' => 
  array (
    'providers' => 
    array (
      0 => 'App\\Providers\\ThemeServiceProvider',
    ),
  ),
);
```

# Pages & Menu

- Delete default WordPress pages
- Add your website pages with adding template blade for each one
- Save the id of each page in migaloz_pages() function
- Modify $menu in wp-content/themes/migaloz/app/View/Composers/App.php to adjust your menu items

# Custom Post Type

This is the default way to create custom post type.

```php
register_post_type('POST TYPE', [
    'supports' => ['title','editor','thumbnail'],
    'public' => true,
    'labels' => [
        'name' => 'POST TYPES',
        'add_new' => 'Add New POST TYPE',
        'add_new_item' => 'Add New POST TYPE',
        'edit_item' => 'Edit POST TYPE',
        'all_items' => 'All POST TYPES',
        'singular_name' => 'POST TYPE',
        'menu_name' => 'POST TYPE',
    ],
    'menu_icon' => 'dashicon-icon',
    'has_archive' => false,
]);
```

- In order to save time and effort, you don't need to type all of this code for each post type, and you can just use
  this function to create standard post type

```php
migaloz_register_post_type('POST TYPE')
```

- You can pass array of $args to overwrite the custom post settings
- If you want for example disable thumbnail

```php
migaloz_register_post_type('POST TYPE WITHOUT THUMBNAIL',[
    'thumbnail' => false
])
```

- These are all avaiable $args you can use

```php
[
    'editor' => true,
    'thumbnail' => true,
    'single_label' => '',
    'plural_label' => '',
    'menu_label' => '',
    'icon' => '',
]
```

- After adding custom post type, go to WPML -> Settings and enable translation for these post types

# Custom Taxonomy

- Same as custom post types

```php
migaloz_register_taxonomy('TAX NAME', 'POST TYPE', [
    'label' => 'LABEL'
]);
```

# Standards

- For all custom post type listing use component to render it
- When you fetch list of custom post type, fetch only the id field to assign it to the component

```php
// Fetch the news
$news = new \WP_Query([
    'post_type' => 'news',
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
    'posts_per_page' => 9,
    'fields' => 'ids',
    'cache_results' => true,
    'update_post_meta_cache' => true,
    'update_post_term_cache' => true,
]);


// Render news in the blade
@foreach ($news->get_posts() as $item)
    <x-news id="{{ $item }}"></x-news>
@endforeach

```

- For all pages use partial view for each section to keep the template file readable
- For ACF images field make them not translatable use this function to pass the post id to get always the english
  version

```php
get_field('image', migaloz_get_original_object_id(get_the_ID(), 'POST TYPE'))
```

- To display date use the following function to get the date translated

```php
// This function has two paramaters
// $date: the default is get_the_date(), you can pass the paramater if you want only overwrite
// $format: the default is J F Y, you can pass the paramater if you want only overwrite
migaloz_get_date();
```

- In laravel blade don't use the following code to print language variable

```php
{{ __('TEXT HERE', 'migaloz') }}
```

- Use this instead so WPML can scan it

```php
<?php echo __('TEXT HERE', 'migaloz') ?>
```

- Keep all php code in the composers and in the blade just loop and print variables

// In theme-helpers.php you fill cf7_dynamic_select_field()
// Modify it and add the following code
if ($tag['name'] == 'subject') {
    $tag['raw_values'] = $tag['values'] = migaloz_get_form_select_options('contact_form_subject', __('Subject*', 'migaloz'));
}

```

## Deployment

- Create database and import your local database file
- Replace site url in options table
- Clone the repo
- Run **composer install** in the theme folder
- Update Permalinks
- Go to Tools -> Better Search Replace
- Replace your localhost URL with the domain URL
- Select Options table and uncheck dry run option
- *Example: Replace http://project.test To https://project.com*
- Register **WPML**
- Check wp-content/cache/acorn/framework/cache/packages.php if it's empty , paste this code into it

```php
<?php return array (
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/termwind' => 
  array (
    'providers' => 
    array (
      0 => 'Termwind\\Laravel\\TermwindServiceProvider',
    ),
  ),
  'roots/sage' => 
  array (
    'providers' => 
    array (
      0 => 'App\\Providers\\ThemeServiceProvider',
    ),
  ),
);
```

- Upload the uploads folder
