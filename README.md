# Where's My Bread ? 🍞

A URL based WordPress breadcrumb, unstyled, minimalist and SEO friendly. A non-invasive WordPress plugin, both lightweight and lightning fast, adding URL based breadcrumb support. A plug-and-play plugin, with no required configuration.

||Version|
|-|-|
|Requires at least WordPress:|`5.6.0`|
|Requires PHP:|`8.0`|
|Tested up to WordPress:|`5.8.2`|

## Table of contents

- [Installation](https://github.com/amarinediary/Where-Is-My-Bread#installation)
- [Displaying the bread, a formatted crumbs list](https://github.com/amarinediary/Where-Is-My-Bread#displaying-the-bread-a-formatted-crumbs-list)
- [Parameters](https://github.com/amarinediary/Where-Is-My-Bread#parameters)
- [Example: The bread with a custom separator](https://github.com/amarinediary/Where-Is-My-Bread#example-the-bread-with-a-custom-separator)
- [Example: Displaying the last 3 crumbs](https://github.com/amarinediary/Where-Is-My-Bread#example-displaying-the-last-3-crumbs)
- [Post and page title handling](https://github.com/amarinediary/Where-Is-My-Bread#post-and-page-title-handling)
- [HTML5 structure output](https://github.com/amarinediary/Where-Is-My-Bread#html5-structure-output)
- [Styling](https://github.com/amarinediary/Where-Is-My-Bread#styling)
- [Minimal css boilerplate (Optional)](https://github.com/amarinediary/Where-Is-My-Bread#minimal-css-boilerplate-optional)
- [Retrieving the crumbs](https://github.com/amarinediary/Where-Is-My-Bread#retrieving-the-crumbs)
- [Example: Ouputing the crumbs object](https://github.com/amarinediary/Where-Is-My-Bread#example-ouputing-the-crumbs-object)
- [Case handling, category/custom taxonomy base crumbs redirecting to 404](https://github.com/amarinediary/Where-Is-My-Bread#case-handling-categorycustom-taxonomy-base-crumbs-redirecting-to-404)
- [Related stackoverflow post](https://github.com/amarinediary/Where-Is-My-Bread#related-stackoverflow-post)

## Installation

If you have a copy of the plugin as a zip file, you can manually upload it and install it through the Plugins admin screen.

1. Navigate to Plugins `→` Add New.
2. Click the Upload Plugin button at the top of the screen.
3. [Download the plugin as a zip file](https://github.com/amarinediary/Where-Is-My-Bread/archive/refs/heads/main.zip), Select it from your local filesystem.
4. Click the Install Now button.
5. When installation is complete, you’ll see “Plugin installed successfully.” Click the Activate Plugin button at the bottom of the page.

## Displaying the bread, a formatted crumbs list.

```php
<?php

the_bread( array $ingredients = array() );
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

### Post and page title handling

Our secret sauce. In some cases, when using apostrophes in posts or pages titles, the crumb might not reflect the actual title. Using the [`url_to_postid()`](https://developer.wordpress.org/reference/functions/url_to_postid/) and [get_page_by_path()](https://developer.wordpress.org/reference/functions/get_page_by_path/) functions in conjonction with the [`get_the_title()`](https://developer.wordpress.org/reference/functions/get_the_title/) function, we can convert the crumb URL into it's matching post or page title. This is automatically handled by `the_bread()` function.

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

Even tho we recomend you to use `the_bread()` function to display and build your own breadcrumb, you can use `get_the_crumbs()` to retrieve the crumbs object.

Crumbs are slugs in-between the host domain name and the start start of the URL parameters defined by `?` character. 

> eg: [https://example.com/where/is/my/bread/?s=bakery&recipe=bread](#!)

In this case crumbs are: `where`, `is`, `my`, `bread`.

```php
<?php

get_the_crumbs();
```

### Example: Ouputing the crumbs object

```php
<?php

var_dump( get_the_crumbs() );
```

## Case handling, category/custom taxonomy base crumbs redirecting to 404

As WordPress doesn't generate a category/custom taxonomy root page, the crumb will redirect to a 404. Here are a few things to explore:

- You could create a page named after your category/custom taxonomy slug and use it as a term's index, by creating a custom page template and looping through them.
- You could redirect any category/custom taxonomy root page query to that category/custom taxonomy first term's page.

## Related stackoverflow post

- https://stackoverflow.com/a/67453887/3645650
