<?php

namespace Helick\GTM;

use Helick\GTM\Contracts\Bootable;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use WP_Admin_Bar;

final class AdminBar implements Bootable
{
    /**
     * Boot the service.
     *
     * @return void
     */
    public static function boot(): void
    {
        $self = new static;

        add_action('admin_bar_menu', [$self, 'extendMenu']);
    }

    /**
     * Extend the admin bar menu.
     *
     * @param WP_Admin_Bar $adminBar
     *
     * @return void
     */
    public function extendMenu(WP_Admin_Bar $adminBar): void
    {
        $isUserAllowed = current_user_can('manage_options');
        $isBackend     = is_admin();

        /**
         * Control whether to show the data layer UI.
         *
         * @param bool $isEnabled
         */
        $isEnabled = apply_filters('helick_gtm_enable_data_layer_ui', true);

        if (!$isUserAllowed || $isBackend || !$isEnabled) {
            return;
        }

        $adminBar->add_menu([
            'id'    => 'helick-gtm',
            'title' => sprintf(
                '<span class="ab-icon dashicons-filter"></span> <span class="ab-label">%s</span>',
                __('Data Layer', DOMAIN)
            ),
        ]);

        $dataLayer = dataLayer();

        // Flatten data layer
        $iterator = new RecursiveArrayIterator($dataLayer);
        $iterator = new RecursiveIteratorIterator($iterator);

        $dataLayer = iterator_to_array($iterator);

        foreach ($dataLayer as $key => $value) {
            $adminBar->add_node([
                'id'     => sanitize_key('helick-gtm-' . $key),
                'parent' => 'helick-gtm',
                'title'  => sprintf(
                    '<span style="font-weight: bold">%s:</span> %s',
                    $key,
                    esc_html($value)
                ),
            ]);
        }
    }
}
