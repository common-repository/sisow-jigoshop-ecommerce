<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Exception;
use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;
use Jigoshop\Extension\SisowGateway\API\Sisow;

class Ideal extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_ideal';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'ideal';
	}
	
	public function getTitle()
	{
		return __('iDEAL', 'jigoshop2-sisow');
	}
	
	/**
	 * Renders method fields and data in Checkout page.
	 */
	public function render()
	{
		$sisow = new Sisow($this->settings['merchantid'], $this->settings['merchantkey'], $this->settings['shopid']);
		
		echo '<fieldset>';
		echo '<div class="row clearfix">';
		echo '<div class="form-group col-lg-6 clearfix">';
		echo '<labelfor="ideal-issuer">' . __('Please, choose your bank.', 'jigoshop2-sisow') . '</label>';
		echo '<select class="form-control" id="ideal-issuer" name="' . $this->getId() . '_issuer">';
		print_r('<option value="">' . __(' -- Please Choose -- ', 'jigoshop2-sisow') . '</option>');
		
		if($sisow->DirectoryRequest($this->settings['testmode'] == 'on'))
		{
			foreach($sisow->issuers as $k => $v)
			echo '<option value="' . $k . '">' . $v . '</option>';
		}
		
		echo '</select>';
		echo '</div>';
		echo '</div>';
		echo '</fieldset>';
	}
	
	public function validateFields()
	{
		if(empty($_POST[$this->getId() . '_issuer']))
		{			
			throw new Exception(__('Please select your bank!', 'jigoshop2-sisow'));
			return false;
		}
		
		return true;
	}
}