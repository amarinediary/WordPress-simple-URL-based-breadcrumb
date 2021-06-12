# Where-Is-My-Bread 🍞

## Description

Where-Is-My-Bread is a URL based WordPress breadcrumb, unstyled, minimalist and SEO friendly. A non-invasive, lightweight, lightning fast, WordPress plugin adding URL based breadcrumb support. Plug-and-play plugin, no required configuration.

||Version|
|-|-|
|Requires at least WordPress:|`5.6.0`|
|Requires PHP:|`8.0`|
|Tested up to WordPress:|`5.7.1`|

## Displaying the bread, a formatted crumbs list.

```php
<?php

/**
 * Display the bread, a formatted crumbs list.
 * 
 * @since 1.0.0
 * 
 * @param Array $ingredients[separator] The crumb's separator. Default to >.
 * @param Array $ingredients[offset] Crumbs array offset. Accept positive/negative Integer. Default to 0. Refer to array_slice. https://www.php.net/manual/en/function.array-slice.php.
 * @param Array $ingredients[length] Crumbs array length. Accept positive/negative Integer. Default to null. Refer to array_slice. https://www.php.net/manual/en/function.array-slice.php.
 * 
 * @return Array The formatted crumbs list.
 */
the_bread( array $ingredients = array() );
```

### Parameters

|Parameter|Description|
|-|-|
|`$ingredients`|(Optional) Array of arguments for displaying the bread.|
|`'separator'`|The crumb's separator. Default to `>`.|
|`'offset'`|Crumbs offset. Accept positive/negative Integer. Default to `0`. Refer to [array_slice](https://www.php.net/manual/en/function.array-slice.php).|
|`'length'`|Crumbs length. Accept positive/negative Integer. Default to `null`. Refer to [array_slice](https://www.php.net/manual/en/function.array-slice.php).|

### Example: The breadcrumb with a custom separator

```php
<?php

$ingredients = array(
    'separator' => '→',
);

the_bread( $ingredients );
```

### Example: Displaying only the last 3 crumbs

```php
<?php

$ingredients = array(
    'offset' => -3,
    'length' => 3,
);

the_bread( $ingredients );
```

### Post and page title handling

In some cases, for example, when using apostrophes in posts or pages titles, the crumb might not reflect the actual title. Using the [`url_to_postid()`](https://developer.wordpress.org/reference/functions/url_to_postid/) function in conjonction with the [`get_the_title()`](https://developer.wordpress.org/reference/functions/get_the_title/) function, we can convert the crumb URL into it's matching post or page tile. This is automatically handled by `the_bread()` function.

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

- The `<ol>` tag comes with the css class, `🍞` or `bread` *(fallback)*.
- Each `<li>` tag comes with the class, `crumb`.

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

## Retrieving the crumbs.

Even tho we recomend you to use `the_bread()` function to display and build your own breadcrumb, you can use `get_the_crumbs()` to retrieve the crumbs object.

Crumbs are slugs in-between the host domain name and the start start of the URL parameters defined by `?` character. 

> eg: [https://example.com/where/is/my/bread/?s=bakery&recipe=bread](#!)

In this case crumbs are: "`Where`", "`Is`", "`My`", "`Bread`".

```php
<?php

/**
 * Retrieve the crumbs.
 * 
 * @since 1.0.0
 *
 * @return Array Crumbs array.
 */
get_the_crumbs();
```

### Example: Ouputing the crumbs object

```php
<?php

var_dump( get_the_crumbs() );
```

## Case handling, category and taxonomy crumb redirecting to 404

As WordPress doesn't generate a category/custom taxonomy root page, the crumb will redirect to a 404. 

You can create a page named after your category/custom taxonomy slug and use it as a term's index, by creating a custom page template and looping through them.
