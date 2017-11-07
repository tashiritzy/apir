<?php
namespace Prj;

use Zend\Router\Http\Segment;

//use Zend\ServiceManager\Factory\InvokableFactory;

return [
  /*
      'controllers' => [
        'factories' => [
            Controller\AlbumController::class => InvokableFactory::class,
        ],
    ],
   */ 
     // Route for prj action
    'router' => [
        'routes' => [
            'prj' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/prj[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\PrjController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    
    'view_manager' => [
        'template_path_stack' => [
            'prj' => __DIR__ . '/../view',
        ],
    ],
];
?>
