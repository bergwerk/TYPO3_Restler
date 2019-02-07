<?php

namespace Aoe\Restler\Middleware;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Aoe\Restler\System\Restler\Builder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * @package Restler
 */
class RestlerMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     *
     * @throws \Luracast\Restler\RestException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->canHandleRequest($request)) {
            return $handler->handle($request);
        }

        // We define this constant, so that any TYPO3-Extension can check, if the REST-API is running
        define('REST_API_IS_RUNNING', true);

        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        /** @var Builder $restlerBuilder */
        $restlerBuilder = $objectManager->get(Builder::class);

        return $restlerBuilder->build()->handle();
    }

    /**
     * This request handler can handle any frontend request.
     *
     * @param ServerRequestInterface $request
     *
     * @return bool If the request is not an eID request, TRUE otherwise FALSE
     */
    public function canHandleRequest(ServerRequestInterface $request)
    {
        return $this->isRequestApi($request) || $this->isRequestApiExplorer($request);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    private function isRequestApi(ServerRequestInterface $request)
    {
        return strpos($request->getUri()->getPath(), '/api/') === 0;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    private function isRequestApiExplorer(ServerRequestInterface $request)
    {
        return strpos($request->getUri()->getPath(), '/api_explorer/') === 0;
    }
}
