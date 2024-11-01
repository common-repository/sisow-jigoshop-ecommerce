<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Exception;
use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;
use Jigoshop\Extension\SisowGateway\API\Sisow;

class Eps extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_eps';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'eps';
	}
	
	public function getTitle()
	{
		return __('EPS', 'jigoshop2-sisow');
	}
	
	/**
	 * Renders method fields and data in Checkout page.
	 */
	public function render()
	{
		echo '<link rel="stylesheet" href="https://bankauswahl.giropay.de/widget/v2/style.css" />';
		echo '<script src="https://bankauswahl.giropay.de/widget/v2/girocheckoutwidget.js"></script>';
		
		echo '<fieldset>';
		echo '<div class="row clearfix">';
		echo '<div class="col-lg-12">';
		print_r(__('With eps online transfer you pay simply, fast and secure by online banking of your bank. Your will be redirected to the online banking at your bank where you authorize the credit transfer with PIN and TAN.', 'jigoshop2-sisow'));
		echo '</div>';
		echo '</div>';
		echo '<div class="row clearfix" style="padding-top: 20px;">';
		echo '<div class="form-group col-lg-6 clearfix">';
		echo '<labelfor="ideal-issuer">' . __('Your bank', 'jigoshop2-sisow') . '</label>';
		echo '<input type="text" class="form-control" id="' . $this->getId() . '_bic" name="' . $this->getId() . '_bic" value="" onkeyup="girocheckout_widget(this, event, \'bic\', \'3\')">';
		print_r('<small>' . __('(Search by bank name, bank code, city or bic.)', 'jigoshop2-sisow') . '</small>');
		echo '</div>';
		echo '</div>';
		echo '</fieldset>';
	}
	
	public function validateFields()
	{
		if(empty($_POST[$this->getId() . '_bic']))
		{			
			throw new Exception(__('Please enter your bank!', 'jigoshop2-sisow'));
			return false;
		}
		
		return true;
	}
}