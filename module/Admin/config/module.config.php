<?php

namespace Admin;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/admin',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'menu' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/menu[/:action[/:id]]',
                            'defaults' => [
                                'controller' => Controller\MenuController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'static' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/static[/:action[/:id]]',
                            'defaults' => [
                                'controller' => Controller\StaticPageController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                ],
            ],
            'images' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/images[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\ImageController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\MenuController::class => Factory\MenuControllerFactory::class,
            Controller\StaticPageController::class => Factory\StaticPageFactory::class,
            Controller\ImageController::class => InvokableFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\AdminService::class => Factory\AdminServiceFactory::class,
        ],
    ],
    'session_containers' => [
        'Admin'
    ],
    'view_manager' => [
        'template_path_stack' => [
            'admin' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ]
];
