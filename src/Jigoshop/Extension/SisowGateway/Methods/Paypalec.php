<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;

class Paypalec extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_paypal';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'paypalec';
	}
	
	public function getTitle()
	{
		return __('PayPal', 'jigoshop2-sisow');
	}
}