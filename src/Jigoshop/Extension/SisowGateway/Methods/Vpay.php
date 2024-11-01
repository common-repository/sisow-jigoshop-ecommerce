<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;

class Vpay extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_vpay';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'vpay';
	}
	
	public function getTitle()
	{
		return __('V PAY', 'jigoshop2-sisow');
	}
}