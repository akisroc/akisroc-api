<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class RequestParamsListener
 * @package App\EventListener
 */
class RequestParamsListener
{
    protected const MAX_LIMIT = 100;
    protected const DEFAULT_ORDER = 'DESC';

    /**
     * @param GetResponseEvent $event
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        switch ($request->get('_route')) {
            case 'users.index':
                $this->validateParams($request);
                $this->ensureLimit($request);
                $this->ensureOrder($request);
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function validateParams(Request $request): void
    {
        $limit = $request->query->get('limit');
        if ($limit) {
            if (!is_numeric($limit) || $limit < 0) {
                throw new BadRequestHttpException(
                    "`limit` parameter must be 0 or positive integer, `$limit` given"
                );
            }
        }

        $offset = $request->query->get('offset');
        if ($offset) {
            if (!is_numeric($offset) || $offset < 0) {
                throw new BadRequestHttpException(
                    "`offset` parameter must be 0 or positive integer, `$offset` given."
                );
            }
        }

        $order = $request->query->get('order');
        if ($order) {
            $lOrder = strtolower($order);
            if ($lOrder !== 'asc' && $lOrder !== 'desc') {
                throw new BadRequestHttpException(
                    "`order` parameter only accepts ASC or DESC, `$order` given."
                );
            }
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function ensureLimit(Request $request): void
    {
        $limit = $request->query->get('limit');
        if (!$limit || $limit > 100) {
            $request->query->set('limit', self::MAX_LIMIT);
        } else if ($limit < 0) {
            $request->query->set('limit', 0);
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function ensureOrder(Request $request): void
    {
        if (!$request->query->get('order')) {
            $request->query->set('order', self::DEFAULT_ORDER);
        }
    }
}
