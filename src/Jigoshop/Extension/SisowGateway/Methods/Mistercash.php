<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;

class Mistercash extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_mistercash';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'mistercash';
	}
	
	public function getTitle()
	{
		return __('Bancontact', 'jigoshop2-sisow');
	}
}