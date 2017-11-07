<?php

namespace Prj;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    // Configure service manager
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\PrjTable::class => function($container) {
                    $tableGateway = $container->get(Model\PrjTableGateway::class);
                    return new Model\PrjTable($tableGateway);
                },
                Model\PrjTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Prj());
                    return new TableGateway('prj', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
    
    // Factory for controller
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\PrjController::class => function($container) {
                    return new Controller\PrjController(
                        $container->get(Model\PrjTable::class)
                    );
                },
            ],
        ];
    }
}
?>