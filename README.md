# Helick Google Tag Manager

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)
[![Quality Score][ico-code-quality]][link-code-quality]

Google Tag Manager integration.

## Requirements

Make sure all dependencies have been installed before moving on:

* [PHP](http://php.net/manual/en/install.php) >= 7.1
* [Composer](https://getcomposer.org/download/)

## Install

Install via Composer:

``` bash
$ composer require helick/gtm
```

Update your application config by declaring the following:

``` php
Config::define('GTM_CONTAINER_ID', env('GTM_CONTAINER_ID'));
```

Update your env variables by declaring the following:

``` dotenv
GTM_CONTAINER_ID=GTM-XXXX
```

## Usage

To support the fallback iframe for devices without javascript add the following code just after the opening `<body>` tag in your theme:

``` php
<?php wp_body_open(); ?>
```

### Data layer

Google Tag Manager offers a [data layer](https://developers.google.com/tag-manager/devguide) which allows you pass arbitrary data that can be used to modify which tags are added to your site.

This plugin adds some default information such as post, tags and categories and provides a simple filter for adding in your own custom data.

``` php
add_filter('helick_gtm_data_layer', function (array $dataLayer) {
    $dataLayer['foo'] = 'bar';

    return $dataLayer;
});
```

### Event tracking

To deactivate the event tracking you may declare the following:

``` php
add_filter('helick_gtm_enable_event_tracking', '__return_false');
```

By default the event listener script will look for the elements on the page with special data attributes in your markup and listen for the specified event to push to the data layer.

The data attributes are:

- `data-gtm-variable` *string* Optionally override the default data layer variable name for this event.
- `data-gtm-on` *enum* [click|submit|keyup|focus|blur] The JS event to listen for, defaults to 'click'.
- `data-gtm-event` *string* The event name.
- `data-gtm-category` *string* Optional group the event belongs to.
- `data-gtm-label` *string* Optional human readable label for the event.
- `data-gtm-value` *number* Optional numeric value associated with the event.
- `data-gtm-fields` *string* Optional extra data provided as encoded JSON.

Example:

``` html
<button
    data-gtm-on="click"
    data-gtm-event="play"
    data-gtm-category="videos"
    data-gtm-label="Featured Promotional Video"
>
  Play video
</button>
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email evgenii@helick.io instead of using the issue tracker.

## Credits

- [Evgenii Nasyrov][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/helick/gtm.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/helick/gtm.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/helick/gtm.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/helick/gtm
[link-code-quality]: https://scrutinizer-ci.com/g/helick/gtm
[link-downloads]: https://packagist.org/packages/helick/gtm
[link-author]: https://github.com/nasyrov
[link-contributors]: ../../contributors
