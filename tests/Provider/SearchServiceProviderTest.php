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

use Euskadi31\Silex\Provider\SearchServiceProvider;
use Euskadi31\Silex\Provider\CacheServiceProvider;
use Silex\Application;

class SearchProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $app = new Application(['debug' => true]);

        $app->register(new SearchServiceProvider());

        $this->assertTrue(isset($app['search.options']));

        $this->assertEquals('\Search\Engine\SphinxQL', $app['search.options']['engine']);
        $this->assertEquals('\Doctrine\Common\Cache\ArrayCache', $app['search.options']['cache']['driver']);
        $this->assertEquals(0, $app['search.options']['cache']['life']);
        $this->assertEquals('localhost', $app['search.options']['server']['host']);
        $this->assertEquals(9306, $app['search.options']['server']['port']);

        $this->assertInstanceOf('Search\Engine\SphinxQL', $app['search']);
    }

    public function testRegisterWithCache()
    {
        $app = new Application(['debug' => true]);

        $app->register(new CacheServiceProvider());

        $app->register(new SearchServiceProvider());

        $this->assertTrue(isset($app['search.options']));

        $this->assertEquals('\Search\Engine\SphinxQL', $app['search.options']['engine']);
        $this->assertEquals('\Doctrine\Common\Cache\ArrayCache', $app['search.options']['cache']['driver']);
        $this->assertEquals(0, $app['search.options']['cache']['life']);
        $this->assertEquals('localhost', $app['search.options']['server']['host']);
        $this->assertEquals(9306, $app['search.options']['server']['port']);

        $this->assertInstanceOf('Search\Engine\SphinxQL', $app['search']);

        $this->assertInstanceOf('\Doctrine\Common\Cache\Cache', $app['search']->getCache());
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testBadEngineClassName()
    {
        $app = new Application();

        $app->register(new SearchServiceProvider(), [
            'search.options' => [
                'engine' => '\\stdClass'
            ]
        ]);
        $app['search'];
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadEngine()
    {
        $app = new Application();

        $app->register(new SearchServiceProvider, [
            'search.options' => [
                'engine' => 'foo'
            ]
        ]);
        $app['search'];
    }
}
