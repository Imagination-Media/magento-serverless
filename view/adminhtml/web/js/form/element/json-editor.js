/**
 * Serverless Shipping
 *
 * Calculate shipping rates using a serverless function.
 *
 * @package ImDigital\ServerlessShipping
 * @author Igor Ludgero Miura <igor@imdigital.com>
 * @copyright Copyright (c) 2023 Imagination Media (https://www.imdigital.com/)
 * @license Private
 */

define([
    'jquery',
    'Magento_Ui/js/form/element/abstract',
    'jsoneditor'
], function ($, Abstract, JSONEditor) {
    'use strict';

    return Abstract.extend({
        defaults: {
            elementTmpl: 'ImDigital_Serverless/form/element/json-editor',
            cloudConfigEditorId: 'cloud_config_jsoneditor'
        },

        // Run code after the element has been rendered
        onReadyFunction: function () {
            const self = this;
            
            // Enable JSON editor
            const elementHtml = document.getElementById(this.cloudConfigEditorId);
            const editor = new JSONEditor(elementHtml, {
                mode: 'code',
                onChangeText: function (value) {                    
                    // Update the input element with the JSON value
                    self.value(value);
                }
            });

            // Initialize JSON editor with the current value
            if (this.value()) {
                const jsonContent = JSON.parse(this.value());
                editor.set(jsonContent);
            }
        }
    });
});
