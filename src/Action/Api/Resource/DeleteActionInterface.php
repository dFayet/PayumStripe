<?php

declare(strict_types=1);

namespace Prometee\PayumStripeCheckoutSession\Action\Api\Resource;

use Prometee\PayumStripeCheckoutSession\Request\Api\Resource\DeleteInterface;
use Stripe\ApiResource;

interface DeleteActionInterface extends ResourceActionInterface
{
    /**
     * @param DeleteInterface $request
     *
     * @return ApiResource
     */
    public function deleteApiResource(DeleteInterface $request): ApiResource;

    /**
     * @param DeleteInterface $request
     *
     * @return bool
     */
    public function supportAlso(DeleteInterface $request): bool;
}
