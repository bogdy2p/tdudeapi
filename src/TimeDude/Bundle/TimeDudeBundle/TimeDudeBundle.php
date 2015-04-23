<?php

namespace TimeDude\Bundle\TimeDudeBundle;

use TimeDude\Bundle\TimeDudeBundle\DependencyInjection\Security\Factory\WsseFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use \Symfony\Component\DependencyInjection\ContainerBuilder;

class TimeDudeBundle extends Bundle
{
	public function build(ContainerBuilder $container){
		parent::build($container);

		$extension = $container->getExtension('security');
		$extension->addSecurityListenerFactory(new WsseFactory());
	}
}
