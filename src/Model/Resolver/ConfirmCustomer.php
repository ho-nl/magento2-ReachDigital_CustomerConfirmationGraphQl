<?php

namespace ReachDigital\CustomerConfirmationGraphQl\Model\Resolver;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Exception\State\InvalidTransitionException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class ConfirmCustomer implements ResolverInterface
{
    private AccountManagementInterface $customerAccountManagement;

    public function __construct(
        AccountManagementInterface $customerAccountManagement
    ) {
        $this->customerAccountManagement = $customerAccountManagement;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): bool
    {
        $customerId = $args['customerId'];
        $token = $args['confirmationToken'];

        try {
            $this->customerAccountManagement->activateById($customerId, $token);
            return true;
        } catch (InvalidTransitionException $e) {
            throw new GraphQlInputException(__('The customer is already confirmed.'));
        } catch (InputMismatchException $e) {
            throw new GraphQlInputException(__('The given confirmationToken is invalid.'));
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__('The customer was not found.'));
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getMessage()), $e);
        }
    }
}
