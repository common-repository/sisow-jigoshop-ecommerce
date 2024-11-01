<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;

class Idealqr extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_idealqr';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'idealqr';
	}
	
	public function getTitle()
	{
		return __('iDEAL QR', 'jigoshop2-sisow');
	}
}