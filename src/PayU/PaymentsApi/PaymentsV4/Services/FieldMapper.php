<?php

namespace PayU\PaymentsApi\PaymentsV4\Services;

class FieldMapper
{
    /**
     * This function receives an array and a mapping schema and returns a mapped array,
     * by applying the schema on the initial array, field by field
     *
     * E.g of schema used for mapping:
     * [key1 => key2] - map the value from [key1 => value] to [key2 => value]
     * [key1.key2 => => key3] - map the value from [key1 => [key2 => value]] to [key3 => value]
     * [key1.key2.key3 => key4.key5] - map the value from [key1 => [key2 => [key3 => value]]] to [key4 => [key5 => value]]
     *
     * This function can be used for mapping to a key-value array, where the value is a scalar:
     * E.g. Given the array [key1 => [key2 => value]] and schema [key1.key2 => key3],
     * the mapped array [key3 => value] will be returned
     *
     * It also works for more complex cases, for mapping to a key-value array, where the value is an array:
     * E.g. Given the array [key1 => [key2 => value1], [key2 => value2]] and schema [key1.key2 => key3],
     * the mapped array [key3 => [value1, value2]] will be returned
     *
     * Array case (group multiple fields in a single array-object):
     * You can pass a field as array.
     * E.g. You have more fields with values as indexed arrays, and you want to group them in a single key_group.
     * 'FIELD1' => ['key_group.0.key1', 'key_group.1.key1', 'key_group.2.key1']).
     * 'FIELD2' => ['key_group.0.key2', 'key_group.1.key2', 'key_group.2.key2']).
     * Will result in
     * [ key_group => [
     *      0 => [ key1 => val11, key2 => val12 ],
     *      1 => [ key1 => val21, key2 => val22 ]' ]
     * ]
     *
     * @param array $dataToMap - array which will be mapped
     * @param array $map - schema which will be used for mapping
     * @return array - array resulted after mapping the received array using schema
     */
    public function map(array $dataToMap, array $map)
    {
        $convertedData = [];

        foreach ($dataToMap as $key => $value) {
            $convertedData = $this->processKey($map, $convertedData, $key, $value);
        }

        return $convertedData;
    }

    /**
     * @param array $map - array of keys and new names
     * @param array $mappedData - variable for storing the result
     * @param string|array $key
     * @param string|array $value
     * @return mixed
     */
    private function processKey($map, $mappedData, $key, $value)
    {
        if (isset($map[$key])) {
            if (is_array($map[$key])) {
                /** If the item to be mapped is an array, then map each value field, merge and return them. */
                $partial = [];

                foreach ($map[$key] as $valueIdentifier => $realKey) {
                    $tempArrayContainer = $this->mapKeyNodes($realKey, $value[$valueIdentifier]);
                    $partial[] = array_replace_recursive($mappedData, $tempArrayContainer);
                }

                return call_user_func_array('array_replace_recursive', $partial);
            }

            $tempArrayContainer = $this->mapKeyNodes($map[$key], $value);

            return array_merge_recursive($mappedData, $tempArrayContainer);
        }

        if (!is_array($value)) {
            return $mappedData;
        }

        foreach ($value as $k1 => $v1) {
            if (is_numeric($k1)) {
                $mappedData = $this->processKey($map, $mappedData, $key, $v1);
            } else {
                $mappedData = $this->processKey($map, $mappedData, $key . '.' . $k1, $v1);
            }
        }

        return $mappedData;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    private function mapKeyNodes($key, $value)
    {
        $nodes = explode('.', $key);

        $tempArrayContainer = $value;
        foreach (array_reverse($nodes) as $node) {
            $tempArrayContainer = $node !== '' ? [$node => $tempArrayContainer] : [$tempArrayContainer];
        }

        return $tempArrayContainer;
    }
}
