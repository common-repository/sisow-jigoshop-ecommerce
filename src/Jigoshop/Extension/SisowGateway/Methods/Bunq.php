<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;

class Bunq extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_bunq';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'bunq';
	}
	
	public function getTitle()
	{
		return __('bunq', 'jigoshop2-sisow');
	}
}