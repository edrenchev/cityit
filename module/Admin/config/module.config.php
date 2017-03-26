<?php

namespace Admin;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'menu' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/menu[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\MenuController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\MenuController::class => Factory\MenuControllerFactory::class,
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
