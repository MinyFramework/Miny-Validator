<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

use Miny\Application\BaseApplication;
use Miny\Factory\Container;

class Module extends \Miny\Modules\Module
{
    public function defaultConfiguration()
    {
        return [
            'enable_annotations' => true
        ];
    }

    public function getDependencies()
    {
        $dependencies = parent::getDependencies();
        if ($this->getConfiguration('enable_annotations')) {
            $dependencies[] = 'Annotation';
        }

        return $dependencies;
    }

    public function init(BaseApplication $app)
    {
        $this->ifModule(
            'Annotation',
            function (BaseApplication $app) {
                $app->getContainer()->addCallback(
                    __NAMESPACE__ . '\\ValidatorService',
                    function (ValidatorService $service, Container $container) {
                        $service->setAnnotationReader(
                            $container->get('Modules\\Annotation\\Reader')
                        );
                    }
                );
            }
        );
    }
}
