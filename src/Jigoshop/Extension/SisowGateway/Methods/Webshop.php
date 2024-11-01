<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;

class Webshop extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_webshop';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'webshop';
	}
	
	public function getTitle()
	{
		return __('Webshop Giftcard', 'jigoshop2-sisow');
	}
}