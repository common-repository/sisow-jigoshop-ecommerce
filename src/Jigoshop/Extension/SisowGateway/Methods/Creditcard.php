<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;

class Creditcard extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_creditcard';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'creditcard';
	}
	
	public function getTitle()
	{
		return __('Creditcard', 'jigoshop2-sisow');
	}
}