<?php
/**
 * An array consisting of implementations of middlewares for a middleware stack to be registered
 *
 *  'stackname' => [
 *      'middleware-identifier' => [
 *         'target' => classname or callable
 *         'before/after' => array of dependencies
 *      ]
 *   ]
 */
return [
    'frontend' => [
        'aoe/restler/request-handler' => [
            'target' => \Aoe\Restler\Middleware\RestlerMiddleware::class,
            'after' => [
                'typo3/cms-frontend/eid'
            ],
            'before' => [
                'typo3/cms-frontend/tsfe'
            ]
        ]
    ]
];
