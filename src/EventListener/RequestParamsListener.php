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
    protected const DEFAULT_COUNT = false;

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
            case 'categories.index':
            case 'episodes.index':
            case 'messages.index':
            case 'places.index':
            case 'posts.index':
            case 'protagonists.index':
            case 'stories.index':
            case 'threads.index':
            case 'users.index':
                $this->ensureLimit($request);
                $this->ensureOffset($request);
                $this->ensureOrder($request);
                $this->ensureCount($request);
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function ensureLimit(Request $request): void
    {
        $limit = $request->query->get('limit');

        if ($limit !== null) {
            if (!is_numeric($limit) || $limit < 0) {
                throw new BadRequestHttpException(
                    "`limit` parameter must be 0 or positive integer, `$limit` given"
                );
            }
        }

        if ($limit === null || $limit > 100) {
            $request->query->set('limit', self::MAX_LIMIT);
        } else if ($limit < 0) {
            $request->query->set('limit', 0);
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function ensureOffset(Request $request): void
    {
        $offset = $request->query->get('offset');
        if ($offset !== null) {
            if (!is_numeric($offset) || $offset < 0) {
                throw new BadRequestHttpException(
                    "`offset` parameter must be 0 or positive integer, `$offset` given."
                );
            }
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function ensureOrder(Request $request): void
    {
        $order = $request->query->get('order');

        if ($order !== null) {
            $lOrder = strtolower($order);
            if ($lOrder !== 'asc' && $lOrder !== 'desc') {
                throw new BadRequestHttpException(
                    "`order` parameter only accepts ASC or DESC, `$order` given."
                );
            }
        }

        if ($order === null) {
            $request->query->set('order', self::DEFAULT_ORDER);
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function ensureCount(Request $request): void
    {
        $count = $request->query->get('count');

        if ($count !== null) {
            if (!is_numeric($count) || ($count !== '0' && $count !== '1')) {
                throw new BadRequestHttpException(
                    "`count` parameter only accepts 0 or 1, `$count` given."
                );
            }
        }

        if ($count === null) {
            $request->query->set('count', self::DEFAULT_COUNT);
        }
    }
}
