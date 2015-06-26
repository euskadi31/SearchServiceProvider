<?php
/*
 * This file is part of the SearchServiceProvider.
 *
 * (c) Axel Etcheverry <axel@etcheverry.biz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Euskadi31\Silex\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Search\Engine\SearchInterface;
use UnexpectedValueException;
use InvalidArgumentException;

/**
 * Search integration for Silex.
 *
 * @author Axel Etcheverry <axel@etcheverry.biz>
 */
class SearchServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Container $app)
    {
        $app['search.options'] = [
            'engine'    => '\Search\Engine\SphinxQL',
            'cache'     => [
                'driver'    => '\Doctrine\Common\Cache\ArrayCache',
                'life'      => 0
            ],
            'server'    => [
                'host' => 'localhost',
                'port' => 9306
            ]
        ];

        $app['search'] = function($app) {

            $engineName = $app['search.options']['engine'];

            if (!class_exists($engineName)) {
                throw new InvalidArgumentException(sprintf(
                    'Engine "%s" not found.',
                    $engineName
                ));
            }

            $search = new $engineName(
                $app['search.options']['server']['host'],
                $app['search.options']['server']['port']
            );

            if (!$search instanceof SearchInterface) {
                throw new UnexpectedValueException(sprintf(
                    '"%s" does not implement \\Search\\Engine\\SearchInterface', get_class($search)
                ));
            }

            $search->setEventDispatcher($app['dispatcher']);

            if (isset($app['cache.factory'])) {
                $cacheFactory = $app['cache.factory']($app['search.options']['cache']);

                $search->setCache($cacheFactory());
            }

            return $search;
        };
    }
}
