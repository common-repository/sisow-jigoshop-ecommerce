<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;

class Homepay extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_homepay';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'homepay';
	}
	
	public function getTitle()
	{
		return __('ING Home\'Pay', 'jigoshop2-sisow');
	}
}