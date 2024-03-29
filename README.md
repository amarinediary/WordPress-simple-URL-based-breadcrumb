# WordPress simple URL based breadcrumb

🍞 A non-invasive WordPress unofficial plugin, minimalist and SEO friendly. both lightweight and lightning fast, adding URL based breadcrumb support. Plug-and-play, with no required configuration.

||Version|
|-|-|
|Requires at least WordPress:|`5.0.0`|
|Requires at least PHP:|`7.0.0`|
|[Tested up to WordPress:](https://wordpress.org/download/releases/)|`6.0.2`|
|[Current plugin version:](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb/releases/tag/1.2.4)|`1.2.4`|

### Latest changelog

#### `1.2.4`

`1.2.4` brings further improvements conditional logic based on the PHP version and further improvements. `1.2.4` hasn't been through any testing. Feedback for `1.2.4` is apreciated and more than welcome.

[`1.2.3`](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb/releases/tag/1.2.3) remains the current most stable version. 

- [x] Introduced `safe_str_ends_with`. `safe_str_ends_with` uses the native PHP > 8.0.0 function when available, or falls back to the custom implementation.
- [x] Introduced `safe_str_contains`. `safe_str_contains` uses the native PHP > 8.0.0 function when available, or falls back to the custom implementation.
- [x] Introduced `attempt_to_retrieve_server_scheme`.
- [x] `get_the_crumbs` was updated to use introduced functions.
- [x] `the_bread` was updated. Improvements to how default parameters are introduced were made. The use of `extract()` saves us the trouble of writing multiple if statements.
- [x] Code commenting improvements.
- [x] Emphasis on [WordPress coding standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/). 
- [x] [README.md](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb/blob/main/README.md) updates.
- Special thanks to [@mattmacneil](https://github.com/mattmacneil) for the feedback and kind words.

We are looking for ideas on how the search breadcrumb should be handled (with/without pagination) ? [Open a new issue](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb/issues/new/choose) to share what's on your mind.

## Give us feedback 🙏

Let us know how we can improve this plugin, [Open a new issue](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb/issues/new/choose).

## Unofficial plugin ?
Open source is a key value for us. Unofficial here means Open Source. Restricting it through the WordPress plugin store would be counterproductive. Put your hands in the engine and have fun. Star-it, Fork-it and Modify-it at your convenience.

## Table of contents

- [Displaying the bread, a formatted crumbs list](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#displaying-the-bread-a-formatted-crumbs-list)
- [Parameters](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#parameters)
- [Example: The bread with a custom separator](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#example-the-bread-with-a-custom-separator)
- [Example: Displaying the last 3 crumbs](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#example-displaying-the-last-3-crumbs)
- [Example: The bread with a root crumb](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#example-the-bread-with-a-root-crumb)
- [Example: Intercepting the crumbs array](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#example-intercepting-the-crumbs-array)
- [HTML5 structure output](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#html5-structure-output)
- [Styling](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#styling)
- [Minimal css boilerplate (Optional)](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#minimal-css-boilerplate-optional)
- [Retrieving the crumbs](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#retrieving-the-crumbs)
- [Example: Ouputing the crumbs object](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#example-ouputing-the-crumbs-object)
- [Breadcrumb behaviour and taxonomies](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#breadcrumb-behaviour-and-taxonomies)
- [Discrepancies between Google Schema Validation tools and the Google Search Console Enhancement Reports and Performance Reports.](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#discrepancies-between-google-schema-validation-tools-and-the-google-search-console-enhancement-reports-and-performance-reports)
- [Localhost development](https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb#localhost-development)

## Displaying the bread, a formatted crumbs list.

```php
<?php

the_bread( $ingredients = array() );
```

### Parameters

|Parameter|Description|
|-|-|
|`$ingredients`|(Optional) `Array` The bread arguments.|
|`$ingredients['crumbs']`|`Array` The crumbs array. Default to `get_the_crumbs()`.|
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

### Example: Intercepting the crumbs array

```php
<?php

//Intercept the crumbs array...
$crumbs = get_the_crumbs();

//... Do something with it:
//In our case we're appending a new crumb to the crumbs array.
array_push( $crumbs,
    array(
        'slug' => 'search',
        'url' => 'https://.../search/',
    )
);

//And intercepting a specific crumb to modify it...
array_walk( $crumbs, function( &$value, $key ) {

    if ( 'something' == $value['slug'] ) {

        $value['slug'] = 'somethingelse';

    };

} );

//And use it with our bread...
$ingredients = array(
    'crumbs' => $crumbs,
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

## Breadcrumb behaviour and taxonomies

As WordPress doesn't create a default root crumb index page for taxonomies, you often end up with a crumb redirecting to a 404. Each request has to be made against a term: Accessing `https://.../taxonomy/my-term/` will return a `200` status code, but trying to access the root crumb, `https://.../taxonomy/`, will return a `404`.

Having that in mind, we decided to filter out each taxonomies root crumbs. As a result, `get_the_crumbs()`, which is called by `the_bread()`, won't return any taxonomies root crumb. This approach is intended to match WordPress behaviour.

## Discrepancies between Google Schema Validation tools and the Google Search Console Enhancement Reports and Performance Reports.
In the event your Breadcrumb isn't successfully passing both structured data testing tool from [Google Test your structured data](https://developers.google.com/search/docs/advanced/structured-data):

Since the January 31 2022, validation coming from the Google Search Console seems to currently be inaccurate. This is probably due to the recent update to the Google Search Console:

> Search Console has changed the way that it evaluates and reports errors in Breadcrumbs and HowTo structured data. As a result, you may see changes in the number of Breadcrumbs > and HowTo entities and issues reported for your property, as well as a change in severity of some issues from errors to warnings.

- Source @ [Data anomalies in Search Console](https://support.google.com/webmasters/answer/6211453?hl=en#zippy=%2Crich-result-reports)

> [...] Our team has investigated a couple of instances where this search verifies that the markup is there, despite the disagreement with Search Console and has found a solution, where the errors decline steadily over the course of a few days [...]

- Source @ [How To Resolve Misattributed Errors In The New Google Search Console](https://www.schemaapp.com/schema-markup/how-to-resolve-misattributed-errors-in-the-new-google-search-console/)
- Source @ [Discrepancies in Google Search Console: Enhancement Reports vs. Performance Reports](https://support.schemaapp.com/support/solutions/articles/33000267425-discrepancies-in-google-search-console-enhancement-reports-vs-performance-reports)

It would seems that the issue is coming from the Google Bot validation itelf and not the structured data of the plugin. We will continue to monitor the situation as 2022 unfold.

## Localhost development

As we are reading and parsing the url, the crumbs on a localhost environement will reflect the development folder architecture. This will not be reflected on a live site. eg: `Home > Www > Wordpress > My awesome post` on a local branch, `Home > My awesome post` on a live branch.

## Watch it, Star-it, Fork-it !

We made your day? Give us a star!
