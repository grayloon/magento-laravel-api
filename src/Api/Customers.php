<?php

namespace Interiordefine\Magento\Api;

use Exception;
use Illuminate\Http\Client\Response;

class Customers extends AbstractApi
{
    /**
     * The list of customers.
     *
     * @param int $pageSize
     * @param int $currentPage
     * @param array $filters
     * @return Response
     * @throws Exception
     */
    public function all(array $filters = [], int $pageSize = 50, int $currentPage = 1): Response
    {
        return $this->get('/customers/search', array_merge($filters, [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]));
    }

    /**
     * Get customer info by Email
     *
     * @param string $email
     * @param int $pageSize
     * @param int $currentPage
     * @return Response
     * @throws Exception
     */
    public function showByEmail(string $email, int $pageSize = 50, int $currentPage = 1): Response
    {
        return $this->get('/customers/search', [
            'searchCriteria[filterGroups][0][filters][0][conditionType]' => 'eq',
            'searchCriteria[filterGroups][0][filters][0][field]' => 'email',
            'searchCriteria[filterGroups][0][filters][0][value]' => $email,
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]);
    }

    /**
     * Create customer account. Perform necessary business operations like sending email.
     *
     * @param array $customer
     * @param string|null $password
     * @return Response
     * @throws Exception
     */
    public function create(array $customer, string $password = null): Response
    {
        return $this->post('/customers', [
            'customer' => array_merge($customer, [
                'website_id' => self::WEBSITE_ID,
            ]),
            'password' => $password,
        ]);
    }

    /**
     * Email the customer with a password reset link.
     *
     * @param string $email
     * @param string $template
     * @param int $websiteId
     * @return Response
     * @throws Exception
     */
    public function password(string $email, string $template, int $websiteId): Response
    {
        return $this->put('/customers/password', [
            'email'     => $email,
            'template'  => $template,
            'websiteId' => $websiteId,
        ]);
    }

    /**
     * Reset customer password.
     *
     * @param string $email
     * @param string $resetToken
     * @param string $newPassword
     * @return Response
     * @throws Exception
     */
    public function resetPassword(string $email, string $resetToken, string $newPassword): Response
    {
        return $this->post('/customers/resetPassword', [
            'email'       => $email,
            'resetToken'  => $resetToken,
            'newPassword' => $newPassword,
        ]);
    }

    /**
     * Get the customer by Customer ID.
     *
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function show(int $id): Response
    {
        return $this->get('/customers/'.$id);
    }

    /**
     * Update the Customer by Customer ID.
     * Or create a new customer with Email, First and Last Name
     *
     * https://magento.redoc.ly/2.4.3-admin/tag/customerscustomerId#operation/customerCustomerRepositoryV1SavePut
     *
     * @param int $id
     * @param string $params
     * @return Response
     * @throws Exception
     */
    public function edit(int $id, string $params): Response
    {
        return $this->put('/customers/'.$id, [
            'customer' => $params
        ]);
    }

    /**
     * Check if a customer exists by email
     *
     * @param string $email
     * @return Response
     * @throws Exception
     */
    public function isCustomerAvailable(string $email): Response
    {
        return $this->post('/customers/isEmailAvailable', [
            'customerEmail' => $email,
            'websiteId' => self::WEBSITE_ID,
        ]);
    }

    /**
     * Get customer id by email address.
     *
     * @param  string  $email
     * @return object
     */
    public function getCustomerID(string $email): Response
    {
        return $this->get('/customers/search', [
            'searchCriteria[filter_groups][0][filters][0][field]' => 'email',
            'searchCriteria[filter_groups][0][filters][0][value]' => $email,
            'searchCriteria[filter_groups][0][filters][0][condition_type]' => 'eq'
        ]);
    }

    /**
     * Update customer by customer ID.
     *
     * @param  int  $customerID
     * @param  array  $customerGroupUpdateBody
     * @return array
     */
    public function updateCustomerByID(int $customerID, int $CustomerGroupID): Response
    {
        return $this->put('/customers/' . $customerID, [
            'customer' => [
                'group_id' => $CustomerGroupID
            ]
        ]);
    }

     /**
     * Update customer Subscription with customer ID.
     *
     * @param  int  $customerID
     * @return array
     */
    public function updateCustomerSubscription(int $customerID): Response
    {
        return $this->put('/customers/' . $customerID, [
            "customer" => [
                'extension_attributes' => [
                    'is_subscribed' => false
                ]
            ]
        ]);
    }

    /**
     * Unsubscribe customer via email if it exists.
     *
     * @param  string  $email
     * @return array
     */
    public function unsubscribe(string $email)
    {
        $customerExists = $this->isCustomerAvailable($email);
        $customerExists = $customerExists->body();
        if ($customerExists == "false") {
            $customerID = $this->getCustomerID($email);
            $customerID = $customerID->json('items');
            $customerID = $customerID[0]['id'];
            return $this->updateCustomerSubscription($customerID);
        }

        return "Email does not exist in M2.";
    }

}
