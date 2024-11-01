<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;

class Belfius extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_belfius';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'belfius';
	}
	
	public function getTitle()
	{
		return __('Belfius Pay Button', 'jigoshop2-sisow');
	}
}