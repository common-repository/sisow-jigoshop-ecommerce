<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;

class Overboeking extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_overboeking';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'overboeking';
	}
	
	public function getTitle()
	{
		return __('Banktransfer', 'jigoshop2-sisow');
	}
	
	public function addMethodSettings($settings)
	{
		$settings[] = array(
							'name'  => sprintf('[%s][days]', $this->getId()),
							'title' => __('Days', 'jigoshop2-sisow'),
							'tip'   => __('Days a banktransfer is valid', 'jigoshop2-sisow'),
							'type'  => 'text',
							'value' => $this->settings['days'],
						);
						
		$settings[] = array(
							'name'    => sprintf('[%s][include]', $this->getId()),
							'title'   => __('Inlcude iDEAL Link', 'jigoshop2-sisow'),
							'type'    => 'checkbox',
							'checked' => $this->settings['include'],
							'classes' => array('switch-medium'),
						);
		
		return $settings;
	}
}