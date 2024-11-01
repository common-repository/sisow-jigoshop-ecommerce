<?php
namespace Jigoshop\Extension\SisowGateway;

use Jigoshop\Integration;
use Jigoshop\Container;

class Gateways
{
	public function __construct()
	{
		Integration::addPsr4Autoload(__NAMESPACE__ . '\\', __DIR__);
		Integration\Helper\Render::addLocation('sisow', JIGOSHOP_SISOW_GATEWAY_DIR);
		
		$methods = array(
			'ideal',
			'idealqr',
			'overboeking',
			'bunq',
			'creditcard',
			'maestro',
			'vpay',
			'sofort',
			'giropay',
			'eps',
			'mistercash',
			'belfius',
			'homepay',
			'paypalec',
			'focum',
			'vvv',
			'webshop',
			'afterpay'
		);
		
		foreach($methods as $method)
		{
			/**@var Container $di*/
			$di = Integration::getService('di');
			$di->services->setDetails('jigoshop.payment.sisow_' . $method, __NAMESPACE__ . '\\Methods\\' . ucwords(strtolower($method)), array(
				'jigoshop.options',
				'jigoshop.service.cart',
				'jigoshop.service.order',
				'jigoshop.messages',
			));
			$di->triggers->add('jigoshop.service.payment', 'addMethod', array('jigoshop.payment.sisow_' . $method));
			
			$di->services->setDetails('jigoshop.endpoint.sisow_' . $method, __NAMESPACE__ . '\\Methods\\' . ucwords(strtolower($method)), array(
				'jigoshop.options',
				'jigoshop.service.cart',
				'jigoshop.service.order',
				'jigoshop.messages',
			));
		//$di->triggers->add('jigoshop.service.payment', 'addMethod', array('jigoshop.payment.sisow_mistercash'));
		}
		
	}
}
new Gateways();