<?php

declare(strict_types=1);

namespace FluxSE\PayumStripe\Action\StripeWireTransfer;

use ArrayObject;
use FluxSE\PayumStripe\Action\AbstractCaptureAction;
use FluxSE\PayumStripe\Request\Api\Resource\AllCustomer;
use FluxSE\PayumStripe\Request\Api\Resource\CreatePaymentIntent;
use FluxSE\PayumStripe\Request\StripeJs\Api\RenderStripeJs;
use Payum\Core\Request\Generic;
use Stripe\ApiResource;

class CaptureAction extends AbstractCaptureAction
{
    protected function createApiResource(ArrayObject $model, Generic $request): ApiResource
    {dd((array)$model);
//        dd($request);
//        $createRequest = new CreatePaymentIntent($model->getArrayCopy());
//        $this->gateway->execute($createRequest);
//
//        return $createRequest->getApiResource();
        $filter = [
            'email' => $request->getSource()->getClientEmail(),
            'limit' => 1,
        ];
        dd($this->gateway);
        $listCustomers = new AllCustomer($filter);
        $this->gateway->execute($listCustomers);
        dd($listCustomers->getApiResources()->first());
//        return $listCustomers->getApiResources()->first();
        dd((array)$details, $request);
    }

    protected function render(ApiResource $captureResource, Generic $request): void
    {dd($request);
        $token = $this->getRequestToken($request);
        $actionUrl = $token->getAfterUrl();

        $renderRequest = new RenderStripeJs($captureResource, $actionUrl);
        $this->gateway->execute($renderRequest);
    }
}
