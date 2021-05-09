# Where-Is-My-Bread 🍞

## Description

URL based WordPress breadcrumb, unstyled, minimalist and SEO friendly. Where-Is-My-Bread is a non-invasive, lightweight, lightning fast, WordPress plugin adding URL based breadcrumb support. Where-Is-My-Bread is a plug-and-play plugin with no required configuration.

## Retrieving the crumbs.

Crumbs are slugs in-between the host domain name and the start start of the URL parameters defined by `?` character. 

> eg: [https://example.com/where/is/my/bread/?s=searching+for+something&color=blue](#!)

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

## Displaying the bread, a formated crumbs list.

```php
<?php

/**
 * Display the bread, a formated crumbs list.
 * 
 * @since 1.0.0
 * 
 * @param Array $ingredients[separator] The crumb's separator. Default to >.
 * @param Array $ingredients[offset] Crumbs array offset. Accept positive/negative Integer. Default to 0. Refer to array_slice. https://www.php.net/manual/en/function.array-slice.php.
 * @param Array $ingredients[length] Crumbs array length. Accept positive/negative Integer. Default to null. Refer to array_slice. https://www.php.net/manual/en/function.array-slice.php.
 * 
 * @return Array Formated crumbs list.
 */
the_bread( array $ingredients = array() );
```

### Parameters

|Parameter|Description|
|-|-|
|`$ingredients`|(Optional) Array of arguments for displaying the bread.|
|`'separator'`|The crumb's separator. Default to `>`.|
|`'offset'`|Crumbs offset. Accept positive/negative Integer. Default to `0`. Refer to [array_slice](https://www.php.net/manual/en/function.array-slice.php).|
|`'length'`|Crumbs length. Accept positive/negative Integer. Default to `null`. Refer [array_slice](https://www.php.net/manual/en/function.array-slice.php).|

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
