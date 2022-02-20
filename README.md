# Where's My Bread ? 🍞 (@see [so/q/50893992](https://stackoverflow.com/a/67453887/3645650))

A URL based WordPress breadcrumb, unstyled, minimalist and SEO friendly. A non-invasive WordPress unofficial plugin, both lightweight and lightning fast, adding URL based breadcrumb support. Plug-and-play, with no required configuration.

||Version|
|-|-|
|Requires at least WordPress:|`3.0.0`|
|Requires PHP:|`8.0.0`|
|Tested up to WordPress:|`5.9.0`|
|Current plugin version:|`1.0.5`|

### Latest changelog

#### `1.0.5`
- - [x] Fix an issue preventing numbered based slugs to be properly outputted.
- - [x] Adding `$ingredients['root']` argument.
- - [x] Adding `$ingredients['root']['slug']`.
- - [x] Adding `$ingredient['root']['url']`.
- - [x] Emphasis on WordPress PHP coding standards.
- Special thanks to [@hanyseyedy](https://github.com/hanyseyedy) for pointing out a few issues in `1.0.4`.

An idea on how the search's breadcrumb should be handled? [Open a new issue](https://github.com/amarinediary/Where-Is-My-Bread/issues/new/choose).

## Give us feedback 🙏

Let us know how we can improve this plugin. Either [Open a new issue](https://github.com/amarinediary/Where-Is-My-Bread/issues/new/choose) or if you don't have a GitHub account you can give us feedback through the following Google [Where-Is-My-Bread Plugin Feedback Form](https://forms.gle/m9PM6dEX8aZrmedG9). (No account required).

## Table of contents

- [Discrepancies between Google Schema Validation tools and the Google Search Console Enhancement Reports and Performance Reports.](https://github.com/amarinediary/Where-Is-My-Bread#discrepancies-between-google-schema-validation-tools-and-the-google-search-console-enhancement-reports-and-performance-reports)
- [Displaying the bread, a formatted crumbs list](https://github.com/amarinediary/Where-Is-My-Bread#displaying-the-bread-a-formatted-crumbs-list)
- [Parameters](https://github.com/amarinediary/Where-Is-My-Bread#parameters)
- [Example: The bread with a custom separator](https://github.com/amarinediary/Where-Is-My-Bread#example-the-bread-with-a-custom-separator)
- [Example: Displaying the last 3 crumbs](https://github.com/amarinediary/Where-Is-My-Bread#example-displaying-the-last-3-crumbs)
- [Example: The bread with a root crumb](https://github.com/amarinediary/Where-Is-My-Bread#example-the-bread-with-a-root-crumb)
- [HTML5 structure output](https://github.com/amarinediary/Where-Is-My-Bread#html5-structure-output)
- [Styling](https://github.com/amarinediary/Where-Is-My-Bread#styling)
- [Minimal css boilerplate (Optional)](https://github.com/amarinediary/Where-Is-My-Bread#minimal-css-boilerplate-optional)
- [Retrieving the crumbs](https://github.com/amarinediary/Where-Is-My-Bread#retrieving-the-crumbs)
- [Example: Ouputing the crumbs object](https://github.com/amarinediary/Where-Is-My-Bread#example-ouputing-the-crumbs-object)

## Discrepancies between Google Schema Validation tools and the Google Search Console Enhancement Reports and Performance Reports.
In the event your Breadcrumb is passing both structured data testing tool from [Google Test your structured data](https://developers.google.com/search/docs/advanced/structured-data).

Since the January 31 2022, validation coming from the Google Search Console seems to currently be inaccurate. This is probably due to the recent update to the Google Search Console:

> Search Console has changed the way that it evaluates and reports errors in Breadcrumbs and HowTo structured data. As a result, you may see changes in the number of Breadcrumbs > and HowTo entities and issues reported for your property, as well as a change in severity of some issues from errors to warnings.

- Source @ https://support.google.com/webmasters/answer/6211453?hl=en#zippy=%2Crich-result-reports

> [...] Our team has investigated a couple of instances where this search verifies that the markup is there, despite the disagreement with Search Console and has found a solution, where the errors decline steadily over the course of a few days [...]

It would seems that the issue is coming from the Google Bot validation itelf and not the structured data of the plugin. We will continue to monitor the situation as 2022 unfold.

Additional Source:
- [How To Resolve Misattributed Errors In The New Google Search Console](https://www.schemaapp.com/schema-markup/how-to-resolve-misattributed-errors-in-the-new-google-search-console/)
- [Discrepancies in Google Search Console: Enhancement Reports vs. Performance Reports](https://support.schemaapp.com/support/solutions/articles/33000267425-discrepancies-in-google-search-console-enhancement-reports-vs-performance-reports)

## Displaying the bread, a formatted crumbs list.

```php
<?php

the_bread( $ingredients = array() );
```

### Parameters

|Parameter|Description|
|-|-|
|`$ingredients`|(Optional) `Array` of arguments for displaying the bread.|
|`$ingredients['root']`|`Array` Root crumb. Default to `null`.|
|`$ingredients['root']['slug']`|(Required if `$ingredients['root']`). Root crumb slug.|
|`$ingredients['root']['url']`|(Required if `$ingredients['root']`). Root crumb url.|
|`$ingredients['separator']`|The crumb's separator.|
|`$ingredients['offset']`|Crumbs offset. Accept positive/negative `Integer`. Default to `0`. Refer to [array_slice](https://www.php.net/manual/en/function.array-slice.php).|
|`$ingredients['length']`|Crumbs length. Accept positive/negative `Integer`. Default to `null`. Refer to [array_slice](https://www.php.net/manual/en/function.array-slice.php).|

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

### Example: The bread with a root crumb

```php
<?php

$ingredients = array(
    'root' => array(
        'slug' => 'home',
        'url' => get_home_url(),
    ),
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

### Example: Outputting the crumbs object

```php
<?php

var_dump( get_the_crumbs() );
```

## Watch it, Star it, Fork it

We made your day? Give us a star!
