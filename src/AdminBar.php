<?php

namespace Helick\GTM;

use Helick\Contracts\Bootable;
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

        $dataLayer = data_layer();

        // Flatten data layer
        $flatten = static function (array $data, string $prefix = '') use (&$flatten) {
            $flattened = [];

            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $flattened = array_merge($flattened, $flatten($value, $prefix . $key . '.'));
                } else {
                    $flattened[$prefix . $key] = trim(json_encode($value), '"');
                }
            }

            return $flattened;
        };

        foreach ($flatten($dataLayer) as $key => $value) {
            $adminBar->add_node([
                'id'     => sanitize_key('helick-gtm-' . $key),
                'parent' => 'helick-gtm',
                'title'  => sprintf(
                    '<span style="font-weight: bold">%s:</span> %s',
                    $key,
                    wp_unslash($value)
                ),
            ]);
        }
    }
}
