<?php

namespace Helick\GTM;

/**
 * Get the container ID.
 *
 * @return string
 */
function containerId(): string
{
    return defined('GTM_CONTAINER_ID') ? GTM_CONTAINER_ID : '';
}

/**
 * Get the data layer variable.
 *
 * @return string
 */
function dataLayerVariable(): string
{
    $dataLayerVar = 'dataLayer';

    /**
     * Control the data layer variable.
     *
     * @param string $dataLayerVar
     */
    $dataLayerVar = apply_filters('helick_gtm_data_layer_variable', $dataLayerVar);

    $dataLayerVar = preg_replace('/[^a-z0-9_\-]/i', '', (string)$dataLayerVar);

    return $dataLayerVar;
}

/**
 * Get the data layer.
 *
 * @return array
 */
function dataLayer(): array
{
    $dataLayer = [];

    /**
     * Control the data layer.
     *
     * @param array $dataLayer
     */
    $dataLayer = apply_filters('helick_gtm_data_layer', $dataLayer);

    return (array)$dataLayer;
}
