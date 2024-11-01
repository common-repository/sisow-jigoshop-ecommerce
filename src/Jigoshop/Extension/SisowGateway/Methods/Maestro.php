<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;

class Maestro extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_maestro';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'maestro';
	}
	
	public function getTitle()
	{
		return __('Maestro', 'jigoshop2-sisow');
	}
}