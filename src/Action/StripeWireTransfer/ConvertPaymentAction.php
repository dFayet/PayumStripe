<?php

declare(strict_types=1);

namespace FluxSE\PayumStripe\Action\StripeWireTransfer;

use FluxSE\PayumStripe\Request\Api\Resource\AllCustomer;
use FluxSE\PayumStripe\Request\Api\Resource\CreateCustomer;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;

final class ConvertPaymentAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    public function execute($request): void
    {//dd($request);
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        $details->offsetSet('amount', $payment->getTotalAmount()/10);
        $details->offsetSet('currency', $payment->getCurrencyCode());
        $this->getCustomerId($request->getSource()->getClientEmail());
//        if (!$customerId = $this->retrieveCustomerId($request->getSource()->getClientEmail())) {
            $customer = new CreateCustomer(['email' => $request->getSource()->getClientEmail()]);
            $this->gateway->execute($customer);
            dd($customer->getApiResource()->offsetGet('id'));
//        }

        $request->setResult($details);
    }

    public function supports($request): bool
    {
        if (false === $request instanceof Convert) {
            return false;
        }

        if ('array' !== $request->getTo()) {
            return false;
        }

        $payment = $request->getSource();
        if (false === $payment instanceof PaymentInterface) {
            return false;
        }

        return true;
    }

    private function retrieveCustomer(string $email): ?string
    {
        $filter = [
            'email' => $email,
            'limit' => 1,
        ];

        $listCustomers = new AllCustomer($filter);
        $this->gateway->execute($listCustomers);

        if (!$customer = $listCustomers->getApiResources()->first()) {
            return null;
        }

        return $customer->offsetGet('id');
    }

    private function createCustomer(string $email): ?string
    {
        $filter = [
            'email' => $email,
            'limit' => 1,
        ];

        $listCustomers = new AllCustomer($filter);
        $this->gateway->execute($listCustomers);

        if (!$customer = $listCustomers->getApiResources()->first()) {
            return null;
        }

        return $customer->offsetGet('id');
    }

    private function getCustomerId(string $customerMail)
    {

    }
}
