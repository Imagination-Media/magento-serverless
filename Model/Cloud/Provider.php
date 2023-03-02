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

namespace ImDigital\Serverless\Model\Cloud;

abstract class Provider
{
    /**
     * Prepare request data when coming from observers
     * @param array $data
     * @return array
     */
    protected function prepareRequestData(array $data): array
    {
        $finalData = [];
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                if (method_exists($value, 'getData')) {
                    $finalData[$key] = $value->getData();
                } elseif (method_exists($value, 'toArray')) {
                    $finalData[$key] = $value->toArray();
                } else {
                    $finalData[$key] = (array)$value;
                    if (count(array_keys($finalData[$key])) === 1) {
                        $firstKey = array_key_first($finalData[$key]);
                        $finalData[$key] = $finalData[$key][$firstKey];
                    }

                    if (is_array($finalData[$key])) {
                        $finalData[$key] = $this->prepareRequestData($finalData[$key]);
                    }
                }

                if (isset($finalData[$key])) {
                    $finalData[$key] = $this->prepareRequestData($finalData[$key]);
                }
            } elseif (is_array($value)) {
                $finalData[$key] = $this->prepareRequestData($value);
            } else {
                $finalData[$key] = $value;
            }
        }

        return $finalData;
    }

    /**
     * Prepare response data when coming from observers
     * @param array $data
     * @return array
     */
    protected function prepareResponseData(array $observerData, array $data): array
    {
        // Remove items that were removed on the $data variable
        if (count($observerData) !== count($data)) {
            foreach ($observerData as $key => $value) {
                if (!array_key_exists($key, $data)) {
                    unset($observerData[$key]);
                }
            }
        }

        foreach ($data as $key => $value) {
            // Add new items
            if (!isset($observerData[$key])) {
                $observerData[$key] = $value;
                continue;
            }

            if (is_array($observerData[$key])) {
                $observerData[$key] = $this->prepareResponseData($observerData[$key], $value);
            } elseif (is_object($observerData[$key])) {
                // Get data as array
                $objData = (array)$observerData[$key];

                // Get data from the '*_data' key
                $objData = $objData[array_key_first($objData)];

                // Loop through items and set data
                $objData = $this->prepareResponseData($objData, $value);

                foreach ($objData as $objKey => $objValue) {
                    $setFunctionName = "set" . str_replace('_', '', ucwords($objKey, '_'));
                    if (method_exists($observerData[$key], $setFunctionName)) {
                        $observerData[$key]->$setFunctionName($objValue);
                    }
                }
            } else {
                $observerData[$key] = $value;
            }
        }

        return $observerData;
    }
}
