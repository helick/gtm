<?php

namespace Helick\GTM;

use Helick\Contracts\Bootable;

final class Snippet implements Bootable
{
    /**
     * Boot the service.
     *
     * @return void
     */
    public static function boot(): void
    {
        $self = new static;

        add_action('wp_head', [$self, 'dataLayer']);
        add_action('wp_head', [$self, 'head']);
        add_action('wp_body_open', [$self, 'body']);
    }

    /**
     * Display the data layer snippet.
     *
     * @return void
     */
    public function dataLayer(): void
    {
        $snippet = '<script>%s=[%s]</script>';

        $dataLayerVariable = data_layer_variable();
        $dataLayer         = data_layer();

        if (empty($dataLayer)) {
            return;
        }

        echo sprintf(
            $snippet,
            esc_js($dataLayerVariable),
            wp_json_encode($dataLayer)
        ), "\n";
    }

    /**
     * Display the head snippet.
     *
     * @return void
     */
    public function head(): void
    {
        $snippet = <<<HTML
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','%s','%s');</script>
<!-- End Google Tag Manager -->
HTML;

        $dataLayerVariable = data_layer_variable();
        $containerId       = container_id();

        echo sprintf(
            $snippet,
            esc_js($dataLayerVariable),
            esc_js($containerId)
        ), "\n";
    }

    /**
     * Display the body snippet.
     *
     * @return void
     */
    public function body(): void
    {
        $snippet = <<<HTML
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=%s"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
HTML;

        $containerId = container_id();

        echo sprintf($snippet, esc_js($containerId)), "\n";
    }
}
