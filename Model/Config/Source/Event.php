<?php

/**
 * Serverless
 *
 * Use serverless function to modify the Magento business logic.
 *
 * @package ImDigital\Serverless
 * @author Igor Ludgero Miura <igor@imdigital.com>
 * @copyright Copyright (c) 2023 Imagination Media (https://www.imdigital.com/)
 * @license Private
 */

declare(strict_types=1);

namespace ImDigital\Serverless\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Event implements OptionSourceInterface
{
    public const EVENTS_LIST = [
            'abstract_search_result_load_after',
            'abstract_search_result_load_before',
            'adminhtml_block_eav_attribute_edit_form_init',
            'adminhtml_block_html_before',
            'adminhtml_block_promo_widget_chooser_prepare_collection',
            'adminhtml_block_salesrule_actions_prepareform',
            'adminhtml_cache_flush_all',
            'adminhtml_cache_flush_system',
            'adminhtml_cache_refresh_type',
            'adminhtml_catalog_category_tree_can_add_root_category',
            'adminhtml_catalog_category_tree_can_add_sub_category',
            'adminhtml_catalog_category_tree_is_moveable',
            'adminhtml_catalog_product_attribute_edit_frontend_prepare_form',
            'adminhtml_catalog_product_attribute_set_main_html_before',
            'adminhtml_catalog_product_attribute_set_toolbar_main_html_before',
            'adminhtml_catalog_product_edit_element_types',
            'adminhtml_catalog_product_edit_prepare_form',
            'adminhtml_catalog_product_edit_tab_attributes_create_html_before',
            'adminhtml_catalog_product_form_prepare_excluded_field_list',
            'adminhtml_catalog_product_grid_prepare_massaction',
            'adminhtml_cmspage_on_delete',
            'adminhtml_controller_catalogrule_prepare_save',
            'adminhtml_controller_salesrule_prepare_save',
            'adminhtml_customer_orders_add_action_renderer',
            'adminhtml_customer_prepare_save',
            'adminhtml_customer_save_after',
            'adminhtml_product_attribute_types',
            'adminhtml_promo_quote_edit_tab_coupons_form_prepare_form',
            'adminhtml_sales_order_create_process_data',
            'adminhtml_sales_order_create_process_data_before',
            'adminhtml_sales_order_create_process_item_after',
            'adminhtml_sales_order_create_process_item_before',
            'adminhtml_sales_order_creditmemo_register_before',
            'adminhtml_store_edit_form_prepare_form',
            'adminhtml_system_config_advanced_disableoutput_render_before',
            'adminhtml_widget_grid_filter_collection',
            'admin_permissions_role_prepare_save',
            'admin_system_config_changed_section_currency',
            'admin_system_config_changed_section_currency_before_reinit',
            'admin_system_config_changed_section_design',
            'admin_system_config_save',
            'admin_user_authenticate_after',
            'admin_user_authenticate_before',
            'amazon_customer_authenticated',
            'amazon_login_authorize_error',
            'amazon_login_authorize_validation_error',
            'amazon_payment_authorize_before',
            'amazon_payment_pending_authorization_hard_decline_after',
            'amazon_payment_pending_authorization_soft_decline_after',
            'assigned_theme_changed',
            'assign_theme_to_stores_after',
            'backend_auth_user_login_failed',
            'backend_auth_user_login_success',
            'backend_block_widget_grid_prepare_grid_before',
            'braintree_googlepay_update_quote_after',
            'braintree_googlepay_update_quote_before',
            'braintree_paypal_update_quote_after',
            'braintree_paypal_update_quote_before',
            'catalogrule_dirty_notice',
            'catalogsearch_reset_search_result',
            'catalogsearch_searchable_attributes_load_after',
            'catalog_block_product_list_collection',
            'catalog_block_product_status_display',
            'catalog_category_change_products',
            'catalog_category_delete_after_done',
            'catalog_category_flat_loadnodes_before',
            'catalog_category_prepare_save',
            'catalog_category_tree_init_inactive_category_ids',
            'catalog_controller_category_delete',
            'catalog_controller_category_init_after',
            'catalog_controller_product_init_after',
            'catalog_controller_product_init_before',
            'catalog_controller_product_view',
            'catalog_prepare_price_select',
            'catalog_product_attribute_update_before',
            'catalog_product_collection_apply_limitations_after',
            'catalog_product_collection_before_add_count_to_categories',
            'catalog_product_collection_load_after',
            "catalog_product_compare_add_product",
            "catalog_product_compare_item_collection_clear",
            "catalog_product_compare_remove_product",
            "catalog_product_delete_after_done",
            "catalog_product_edit_action",
            "catalog_product_gallery_prepare_layout",
            "catalog_product_gallery_upload_image_after",
            "catalog_product_get_final_price",
            "catalog_product_import_bunch_delete_after",
            "catalog_product_import_bunch_delete_commit_before",
            "catalog_product_import_bunch_save_after",
            "catalog_product_import_finish_before",
            "catalog_product_is_salable_after",
            "catalog_product_is_salable_before",
            "catalog_product_new_action",
            "catalog_product_option_price_configuration_after",
            "catalog_product_prepare_index_select",
            "catalog_product_to_website_change",
            "catalog_product_upsell",
            "catalog_product_validate_variations_before",
            "catalog_product_view_config",
            "category_move",
            "category_prepare_ajax_response",
            "catelogsearch_searchable_attributes_load_after",
            "checkout_allow_guest",
            "checkout_cart_add_product_complete",
            "checkout_cart_product_add_before",
            "checkout_cart_product_update_after",
            "checkout_cart_save_after",
            "checkout_cart_save_before",
            "checkout_cart_update_items_after",
            "checkout_cart_update_items_before",
            "checkout_cart_update_item_complete",
            "checkout_controller_multishipping_shipping_post",
            "checkout_controller_onepage_saveOrder",
            "checkout_multishipping_refund_all",
            "checkout_onepage_controller_success_action",
            "checkout_quote_destroy",
            "checkout_quote_init",
            "checkout_submit_all_after",
            "checkout_submit_before",
            "checkout_type_multishipping_create_orders_single",
            "checkout_type_multishipping_set_shipping_items",
            "checkout_type_onepage_save_order_after",
            "clean_cache_after_reindex",
            "clean_cache_by_tags",
            "clean_catalog_images_cache_after",
            "clean_media_cache_after",
            "clean_static_files_cache_after",
            "cms_controller_router_match_before",
            "cms_page_prepare_save",
            "cms_page_render",
            "cms_wysiwyg_images_static_urls_allowed",
            "controller_action_catalog_product_save_entity_after",
            "controller_action_inventory_populate_source_with_data",
            "controller_action_inventory_populate_stock_with_data",
            "controller_action_inventory_source_save_after",
            "controller_action_layout_render_before",
            "controller_action_nocookies",
            "controller_action_noroute",
            "controller_action_postdispatch",
            "controller_action_predispatch",
            "controller_front_send_response_before",
            "core_app_init_current_store_after",
            "core_collection_abstract_load_after",
            "core_collection_abstract_load_before",
            "core_layout_block_create_after",
            "core_layout_render_element",
            "cron_job_run",
            "currency_display_options_forming",
            "customer_account_edited",
            "customer_address_format",
            "customer_customer_authenticated",
            "customer_data_object_login",
            "customer_login",
            "customer_logout"
    ];

    public const MODEL_EVENTS = [
        "_load_before",
        "_load_after",
        "_save_commit_after",
        "_save_before",
        "_save_after",
        "_delete_before",
        "_delete_after",
        "_delete_commit_after",
        "_clear",
    ];

    /**
     * Get a list of all available events
     * @return array
     */
    public function toOptionArray(): array
    {
        $events = [
            [
                'value' => '',
                'label' => __('-- Please Select --'),
            ]
        ];

        foreach (self::EVENTS_LIST as $event) {
            $events[] = [
                'value' => $event,
                'label' => $event,
            ];
        }

        // Get all models and their codes to add to the list
        $abstractModelClasses = [];

        $magentoFiles = $this->findMagentoFiles([
            BP . DIRECTORY_SEPARATOR . 'app/code',
            BP . DIRECTORY_SEPARATOR . 'vendor',
        ]);

        // Sort order alphabetically
        sort($magentoFiles);

        $classNames = $this->getMagentoClassNames($magentoFiles);

        foreach ($classNames as $class) {
            if (strpos($class, 'Interceptor') === false &&
                    strpos($class, 'Abstract') === false &&
                    strpos($class, 'Test') === false
            ) {
                $abstractModelClasses[] = $class;
            }
        }

        foreach ($abstractModelClasses as $abstractModelClass) {
            $reflectionClass = new \ReflectionClass($abstractModelClass);
            $instance = $reflectionClass->newInstanceWithoutConstructor();

            // Check if the $instance variable is an object and has the function getEventPrefix
            if (!is_object($instance) || !method_exists($instance, 'getEventPrefix')) {
                continue;
            }

            $prefix = $instance->getEventPrefix();
            foreach (self::MODEL_EVENTS as $event) {
                $events[] = [
                    'value' => $prefix . $event,
                    'label' => $prefix . $event,
                ];
            }
        }

        // Sort order the $events array by the value field
        usort($events, function ($a, $b) {
            return $a['value'] <=> $b['value'];
        });

        // Remove duplicate values
        $events = array_map("unserialize", array_unique(array_map("serialize", $events)));

        return $events;
    }

    /**
     * Find all Magento files using the $_eventPrefix variable
     * @param array $paths
     * @return array
     */
    protected function findMagentoFiles(array $paths): array
    {
        $result = [];
        foreach ($paths as $path) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
            );
    
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() == 'php') {
                    $content = file_get_contents($file->getPathname());
                    if (strpos($content, '$_eventPrefix') !== false) {
                        $result[] = $file->getPathname();
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Get all class names from a list of files
     * @param array $files
     * @return array
     */
    protected function getMagentoClassNames(array $files): array
    {
        $classNames = [];

        foreach ($files as $filePath) {
            if (!is_file($filePath) || pathinfo($filePath, PATHINFO_EXTENSION) !== 'php') {
                continue;
            }

            $fileContents = file_get_contents($filePath);

            // Regular expression to extract the namespace and class name from the file contents
            $classRegex = '/namespace\s+([^\s;]+(\s*\\\s*[^\s;]+)*)[\s\S]*?class\s+(\w+)/';

            if (preg_match($classRegex, $fileContents, $matches)) {
                $namespace = $matches[1];
                $className = $matches[3];
                $fullClassName = $namespace . '\\' . $className;
                $classNames[] = $fullClassName;
            }
        }

        return $classNames;
    }
}
