<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;

class Vvv extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_vvv';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'vvv';
	}
	
	public function getTitle()
	{
		return __('VVV Giftcard', 'jigoshop2-sisow');
	}
}