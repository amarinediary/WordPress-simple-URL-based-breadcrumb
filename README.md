# Where's My Bread ? 🍞

A URL based WordPress breadcrumb, unstyled, minimalist and SEO friendly. A non-invasive WordPress unofficial plugin, both lightweight and lightning fast, adding URL based breadcrumb support. Plug-and-play, with no required configuration.

||Version|
|-|-|
|Requires at least WordPress:|`5.6.0`|
|Requires PHP:|`8.0`|
|Tested up to WordPress:|`5.8.2`|
|Current plugin version:|`1.0.3`|

### Latest changelog

`1.0.3`
- Automatically filter out post types and taxonomies root crumbs.
- Emphasis on WordPress PHP coding standards.

## Table of contents

- [Displaying the bread, a formatted crumbs list](https://github.com/amarinediary/Where-Is-My-Bread#displaying-the-bread-a-formatted-crumbs-list)
- [Parameters](https://github.com/amarinediary/Where-Is-My-Bread#parameters)
- [Example: The bread with a custom separator](https://github.com/amarinediary/Where-Is-My-Bread#example-the-bread-with-a-custom-separator)
- [Example: Displaying the last 3 crumbs](https://github.com/amarinediary/Where-Is-My-Bread#example-displaying-the-last-3-crumbs)
- [HTML5 structure output](https://github.com/amarinediary/Where-Is-My-Bread#html5-structure-output)
- [Styling](https://github.com/amarinediary/Where-Is-My-Bread#styling)
- [Minimal css boilerplate (Optional)](https://github.com/amarinediary/Where-Is-My-Bread#minimal-css-boilerplate-optional)
- [Retrieving the crumbs](https://github.com/amarinediary/Where-Is-My-Bread#retrieving-the-crumbs)
- [Example: Ouputing the crumbs object](https://github.com/amarinediary/Where-Is-My-Bread#example-ouputing-the-crumbs-object)

## Displaying the bread, a formatted crumbs list.

```php
<?php

the_bread( $ingredients = array() );
```

### Parameters

||Parameter|Description|
|-|-|-|
|`ingredients`||(Optional) Array of arguments for displaying the bread.|
||`separator`|The crumb's separator. Default to `>`.|
||`offset`|Crumbs offset. Accept positive/negative Integer. Default to `0`. Refer to [array_slice](https://www.php.net/manual/en/function.array-slice.php).|
||`length`|Crumbs length. Accept positive/negative Integer. Default to `null`. Refer to [array_slice](https://www.php.net/manual/en/function.array-slice.php).|

### Example: The bread with a custom separator

```php
<?php

$ingredients = array(
    'separator' => '→',
);

the_bread( $ingredients );
```

### Example: Displaying the last 3 crumbs

```php
<?php

$ingredients = array(
    'offset' => -3,
    'length' => 3,
);

the_bread( $ingredients );
```

### HTML5 structure output

```html
<ol class="🍞 bread" itemscope="" itemtype="https://schema.org/BreadcrumbList">
    <li class="crumb" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="http://example.com/where/">
            <span itemprop="name">Where</span>
        </a>
        <meta itemprop="position" content="1">
    </li>
    >
    <li class="crumb" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="http://example.com/where/is/">
            <span itemprop="name">Is</span>
        </a>
        <meta itemprop="position" content="2">
    </li>
    >
    <li class="crumb" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="http://example.com/where/is/my/">
            <span itemprop="name">My</span>
        </a>
        <meta itemprop="position" content="3">
    </li>         
    >
    <li class="crumb" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="http://example.com/where/is/my/bread/">
            <span itemprop="name">Bread</span>
        </a>
        <meta itemprop="position" content="4">
    </li>
</ol>
```

### Styling

By default Where-Is-My-Bread has no associated stylesheet, but has two associated css classes:

- The `<ol>` tag comes with the css class, `.🍞` or `.bread` *(fallback)*.
- Each `<li>` tag comes with the class, `.crumb`.

### Minimal css boilerplate (Optional)

```
.🍞,
.bread {
  list-style-type: none;
  margin:0;
  padding:0;
}

.🍞 li,
.bread li {
  display:inline-block;
}

.🍞 li.crumb:last-child a,
.bread li.crumb:last-child a {
  text-decoration: none;
  pointer-events: none;
  color: inherit;
}
```

## Retrieving the crumbs

Even tho we recommend you to use `the_bread()` function to display and build your own breadcrumb, you can use `get_the_crumbs()` to retrieve the crumbs object.

As WordPress doesn't generate a root page for post types and taxonomies, the `get_the_crumbs()` function automatically filter out post types and taxonomies root crumbs by generating an array of banned slugs.

### Example: Ouputing the crumbs object

```php
<?php

var_dump( get_the_crumbs() );
```

## Watch it, Star it, Fork it

We made your day? Give us a star!
