<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoCustomAttributeType;
use Grayloon\Magento\Models\MagentoCustomer;

class MagentoCustomers extends PaginatableMagentoService
{
    /**
     * The amount of total customers.
     *
     * @return int
     */
    public function count()
    {
        $customers = (new Magento())->api('customers')
            ->all($this->pageSize, $this->currentPage)
            ->json();

        return $customers['total_count'];
    }

    /**
     * Updates customers from the Magento API.
     *
     * @param  array  $customers
     * @return void
     */
    public function updateCustomers($customers)
    {
        if (! $customers) {
            return;
        }

        foreach ($customers as $customer) {
            $this->updateCustomer($customer);
        }

        return $this;
    }

    /**
     * Updates or creates a customer from the Magento API.
     *
     * @param  array  $apiCustomer
     * @return void
     */
    public function updateCustomer($apiCustomer)
    {
        $customer = MagentoCustomer::updateOrCreate(['id' => $apiCustomer['id']], [
            'group_id'           => $apiCustomer['group_id'],
            'default_billing_id' => $apiCustomer['default_billing_id'] ?? null,
            'default_address_id' => $apiCustomer['default_billing_id'] ?? null,
            'created_at'         => $apiCustomer['created_at'],
            'updated_at'         => $apiCustomer['updated_at'],
            'email'              => $apiCustomer['email'],
            'first_name'         => $apiCustomer['firstname'] ?? '',
            'last_name'          => $apiCustomer['lastname'] ?? '',
            'store_id'           => $apiCustomer['store_id'] ?? 1,
            'website_id'         => $apiCustomer['website_id'] ?? 1,
            'synced_at'          => now(),
        ]);

        $this->syncCustomAttributes($apiCustomer['custom_attributes'] ?? [], $customer);
        $this->syncCustomerAddresses($apiCustomer['addresses'] ?? [], $customer);

        return $customer;
    }

    /**
     * Sync the Magento 2 Custom attributes with the Magento Customer.
     *
     * @param  array  $attributes
     * @param  \Grayloon\Magento\Models\MagentoCustomer\ $customer
     * @return void
     */
    protected function syncCustomAttributes($attributes, $customer)
    {
        if (! $attributes) {
            return;
        }

        foreach ($attributes as $attribute) {
            $type = $this->resolveCustomAttributeType($attribute['attribute_code']);
            $value = $this->resolveCustomAttributeValue($type, $attribute['value']);

            $customer
                ->customAttributes()
                ->updateOrCreate(['attribute_type_id' => $type->id], [
                    'attribute_type' => $attribute['attribute_code'],
                    'value'          => $value,
                ]);
        }

        return $this;
    }

    /**
     * Resolve the Custom Attribute Type by the Attribute Code.
     *
     * @param  string  $attributeCode
     * @return \Grayloon\Magento\Models\MagentoCustomAttributeType
     */
    protected function resolveCustomAttributeType($attributeCode)
    {
        $type = MagentoCustomAttributeType::where('name', $attributeCode)
            ->first();

        if (! $type) {
            $api = (new Magento())->api('productAttributes')
                ->show($attributeCode)
                ->json();

            $type = MagentoCustomAttributeType::create([
                'name'         => $attributeCode,
                'display_name' => $api['default_frontend_label'] ?? $attributeCode,
                'options'      => $api['options'] ?? [],
            ]);
        }

        return $type;
    }

    /**
     * Resolve the Custom Attribute Value by the provided options.
     *
     * @param  \Grayloon\Magento\Models\MagentoCustomAttributeType  $type
     * @param  string  $value;
     * @return string|null
     */
    protected function resolveCustomAttributeValue($type, $value)
    {
        if ($type->options) {
            foreach ($type->options as $option) {
                if ($option['value'] == $value) {
                    return $option['label'];
                }
            }
        }

        if (is_array($value)) {
            $value = json_encode($value);
        }

        return $value;
    }

    /**
     * Sync the Magento 2 Customer Addresses with the Magento Customer.
     *
     * @param  array  $addresses
     * @param  \Grayloon\Magento\Models\MagentoCustomer\ $customer
     * @return void
     */
    protected function syncCustomerAddresses($addresses, $customer)
    {
        if (! $addresses) {
            return;
        }

        foreach ($addresses as $address) {
            $customer->addresses()->updateOrCreate(['id' => $address['id']], [
                'customer_id' => $customer->id,
                'region_code' => $address['region']['region_code'] ?? '',
                'region'      => $address['region']['region'] ?? '',
                'region_id'   => $address['region_id'] ?? 0,
                'street'      => $address['street'][0] ?? '',
                'telephone'   => $address['telephone'] ?? null,
                'postal_code' => $address['postcode'] ?? null,
                'city'        => $address['city'] ?? null,
                'first_name'  => $address['firstname'] ?? null,
                'last_name'   => $address['lastname'] ?? null,
            ]);
        }

        return $this;
    }
}
