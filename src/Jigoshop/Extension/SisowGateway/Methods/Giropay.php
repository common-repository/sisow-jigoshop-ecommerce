<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Exception;
use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;
use Jigoshop\Extension\SisowGateway\API\Sisow;

class Giropay extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_giropay';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'giropay';
	}
	
	public function getTitle()
	{
		return __('Giropay', 'jigoshop2-sisow');
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
		print_r(__('With giropay you pay simply, fast and secure by the online banking of your participating bank. You will be redirected to the online banking of your bank where you authorize the credit transfer.', 'jigoshop2-sisow'));
		echo '</div>';
		echo '</div>';
		echo '<div class="row clearfix" style="padding-top: 20px;">';
		echo '<div class="form-group col-lg-6 clearfix">';
		echo '<labelfor="ideal-issuer">' . __('Your bank', 'jigoshop2-sisow') . '</label>';
		echo '<input type="text" class="form-control" id="' . $this->getId() . '_bic" name="' . $this->getId() . '_bic" value="" onkeyup="girocheckout_widget(this, event, \'bic\', \'1\')">';
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