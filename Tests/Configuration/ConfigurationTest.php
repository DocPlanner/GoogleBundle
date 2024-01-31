<?php

declare(strict_types=1);

namespace AntiMattr\GoogleBundle\Tests\Configuration;

use AntiMattr\GoogleBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

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
