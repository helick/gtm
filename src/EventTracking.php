<?php

namespace Helick\GTM;

use Helick\Contracts\Bootable;

final class EventTracking implements Bootable
{
    /**
     * Boot the service.
     *
     * @return void
     */
    public static function boot(): void
    {
        $self = new static;

        add_action('wp_enqueue_scripts', [$self, 'enqueueScripts']);
    }

    /**
     * Enqueue scripts.
     *
     * @return void
     */
    public function enqueueScripts(): void
    {
        /**
         * Control whether to load the data attribute tracking script.
         *
         * @param bool $isEnabled
         */
        $isEnabled = apply_filters('helick_gtm_enable_event_tracking', true);

        if (!$isEnabled) {
            return;
        }

        wp_enqueue_script(
            'helick-gtm-event-tracking',
            plugin_dir_url(dirname(__FILE__)) . 'resources/js/event-tracking.js',
            [],
            filemtime(plugin_dir_path(dirname(__FILE__)) . 'resources/js/event-tracking.js'),
            true
        );
    }
}
