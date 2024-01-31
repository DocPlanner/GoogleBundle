<?php

declare(strict_types=1);

namespace AntiMattr\GoogleBundle\Tests\Configuration;

use AntiMattr\GoogleBundle\DependencyInjection\Configuration;
use AntiMattr\GoogleBundle\DependencyInjection\GoogleExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider provideConfigs
     */
    public function testEmptyConfig(array $configs, array $expectedConfig): void
    {
        $computedConfig = (new Processor())->processConfiguration(new Configuration, $configs);
        self::assertEquals($expectedConfig, $computedConfig);
    }

    public function testDefaultParametersSet(): void
    {
        $container = new ContainerBuilder;
        (new GoogleExtension)->load([], $container);

        self::assertTrue($container->hasParameter('google.adwords.conversions'));
        self::assertEquals([], $container->getParameter('google.analytics.dashboard'));
        self::assertEquals([], $container->getParameter('google.analytics.trackers'));
        self::assertEquals([], $container->getParameter('google.analytics.whitelist'));
        self::assertSame('https://', $container->getParameter('google.analytics.js_source_https'));
        self::assertSame('http://', $container->getParameter('google.analytics.js_source_http'));
        self::assertSame(
            'stats.g.doubleclick.net/dc.js',
            $container->getParameter('google.analytics.js_source_endpoint')
        );
        self::assertSame([], $container->getParameter('google.maps.config'));
    }

    public function testOverridenParametersSet(): void
    {
        $container = new ContainerBuilder;
        $configs   = [];
        $configs[] = [
            'adwords' => [
                'conversions' => [
                    'conversion1' => [
                        'id' => 'conversion1id',
                    ],
                ],
            ],
            'maps'    => [
                'config' => null,
            ],
        ];
        $configs[] = [
            'analytics' => [
                'dashboard'          => [
                    'api_key' => 'test',
                ],
                'whitelist'          => [],
                'js_source_https'    => 'secure://',
                'js_source_http'     => 'insecure://',
                'js_source_endpoint' => 'some_host',
                'trackers'           => [
                    'tracker1' => [
                        'accountId' => 'UA-123',
                    ],
                ],
            ],
        ];
        $configs[] = [
            'maps' => [
                'config' => [
                    'map1key' => [
                        'id' => 'map1id',
                    ],
                ],
            ],
        ];

        (new GoogleExtension)->load($configs, $container);

        self::assertEquals(
            [
                'conversion1' => [
                    'id'       => 'conversion1id',
                    'label'    => null,
                    'value'    => null,
                    'format'   => null,
                    'color'    => null,
                    'language' => null,
                ],
            ],
            $container->getParameter('google.adwords.conversions')
        );
        self::assertEquals(
            ['api_key' => 'test'],
            $container->getParameter('google.analytics.dashboard')
        );
        self::assertEquals(
            ['tracker1' => ['accountId' => 'UA-123']],
            $container->getParameter('google.analytics.trackers')
        );
        self::assertEquals([], $container->getParameter('google.analytics.whitelist'));
        self::assertSame('secure://', $container->getParameter('google.analytics.js_source_https'));
        self::assertSame('insecure://', $container->getParameter('google.analytics.js_source_http'));
        self::assertSame('some_host', $container->getParameter('google.analytics.js_source_endpoint'));
        self::assertSame(
            ['map1key' => ['id' => 'map1id']],
            $container->getParameter('google.maps.config')
        );
    }


    public function provideConfigs(): iterable
    {
        yield 'empty' => [
            [],
            [
                'adwords'   => [
                    'conversions' => [],
                ],
                'analytics' => [
                    'dashboard'          => [],
                    'whitelist'          => [],
                    'js_source_https'    => 'https://',
                    'js_source_http'     => 'http://',
                    'js_source_endpoint' => 'stats.g.doubleclick.net/dc.js',
                    'trackers'           => [],
                ],
                'maps'      => [
                    'config' => [],
                ],
            ],
        ];

        yield 'adwords_null' => [
            [
                [
                    'adwords' => null,
                ],
            ],
            [
                'adwords'   => [
                    'conversions' => [],
                ],
                'analytics' => [
                    'dashboard'          => [],
                    'whitelist'          => [],
                    'js_source_https'    => 'https://',
                    'js_source_http'     => 'http://',
                    'js_source_endpoint' => 'stats.g.doubleclick.net/dc.js',
                    'trackers'           => [],
                ],
                'maps'      => [
                    'config' => [],
                ],
            ],
        ];

        yield 'adwords_conversions_null' => [
            [
                [
                    'adwords' => [
                        'conversions' => null,
                    ],
                ],
            ],
            [
                'adwords'   => [
                    'conversions' => [],
                ],
                'analytics' => [
                    'dashboard'          => [],
                    'whitelist'          => [],
                    'js_source_https'    => 'https://',
                    'js_source_http'     => 'http://',
                    'js_source_endpoint' => 'stats.g.doubleclick.net/dc.js',
                    'trackers'           => [],
                ],
                'maps'      => [
                    'config' => [],
                ],
            ],
        ];

        yield 'adwords' => [
            [
                [
                    'adwords' => [
                        'conversions' => [
                            'foo' => [
                                'id'     => 'foo',
                                'format' => 'xml',
                            ],
                        ],
                    ],
                ],
                [
                    'adwords' => [
                        'conversions' => [
                            'bar' => [
                                'id'       => 'bar',
                                'language' => 'pl',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'adwords'   => [
                    'conversions' => [
                        'foo' => [
                            'id'       => 'foo',
                            'label'    => null,
                            'value'    => null,
                            'format'   => 'xml',
                            'color'    => null,
                            'language' => null,
                        ],
                        'bar' => [
                            'id'       => 'bar',
                            'label'    => null,
                            'value'    => null,
                            'format'   => null,
                            'color'    => null,
                            'language' => 'pl',
                        ],
                    ],
                ],
                'analytics' => [
                    'dashboard'          => [],
                    'whitelist'          => [],
                    'js_source_https'    => 'https://',
                    'js_source_http'     => 'http://',
                    'js_source_endpoint' => 'stats.g.doubleclick.net/dc.js',
                    'trackers'           => [],
                ],
                'maps'      => [
                    'config' => [],
                ],
            ],
        ];

        yield 'analytics_trackers_null' => [
            [
                [
                    'analytics' => [
                        'trackers' => null,
                    ],
                ],
            ],
            [
                'adwords'   => [
                    'conversions' => [],
                ],
                'analytics' => [
                    'dashboard'          => [],
                    'whitelist'          => [],
                    'js_source_https'    => 'https://',
                    'js_source_http'     => 'http://',
                    'js_source_endpoint' => 'stats.g.doubleclick.net/dc.js',
                    'trackers'           => [],
                ],
                'maps'      => [
                    'config' => [],
                ],
            ],
        ];
    }
}
