<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Exception;
use Jigoshop\Endpoint\Processable;
use Jigoshop\Payment\Method2;
use Jigoshop\Extension\SisowGateway\API\Sisow;

class Afterpay extends AbstractSisow implements Method2, Processable
{
	const ID = 'sisow_afterpay';
		
	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		return self::ID;
	}
	
	public function getCode()
	{
		return 'afterpay';
	}
	
	public function getTitle()
	{
		return __('Afterpay', 'jigoshop2-sisow');
	}
	
	public function addMethodSettings($settings)
	{						
		$settings[] = array(
							'name'    => sprintf('[%s][useb2b]', $this->getId()),
							'title'   => __('Use B2B', 'jigoshop2-sisow'),
							'type'    => 'checkbox',
							'checked' => $this->settings['useb2b'],
							'classes' => array('switch-medium'),
						);
		
		return $settings;
	}
	
	/**
	 * Renders method fields and data in Checkout page.
	 */
	public function render()
	{		
		echo '<fieldset>'; // start gender
		echo '<div class="row clearfix">';
		echo '<div class="form-group col-lg-6 clearfix">';
		echo '<labelfor="' . $this->getId() . '-gender">' . __('Gender', 'jigoshop2-sisow') . '</label>';
		echo '<select class="form-control" id="' . $this->getId() . '-gender" name="' . $this->getId() . '_gender">';
		echo '<option value="">' . __(' -- Please Choose -- ', 'jigoshop2-sisow') . '</option>';
		echo '<option value="m">' . __('Male', 'jigoshop2-sisow') . '</option>';
		echo '<option value="f">' . __('Female', 'jigoshop2-sisow') . '</option>';
		echo '</select>';
		echo '</div>';
		echo '</div>'; // end gender
		
		echo '<div class="row clearfix">'; // start birthday
		echo '<div class="form-group col-lg-12 clearfix">';
		echo '<labelfor="' . $this->getId() . '-day">' . __('Birthday', 'jigoshop2-sisow') . '</label>';
		echo '<div class="row">';
		echo '<div class="col-lg-2">';
		echo '<select class="form-control col-lg-4" id="' . $this->getId() . '-day" name="' . $this->getId() . '_day">';
		echo '<option value="">' . __(' -- Day -- ', 'jigoshop2-sisow') . '</option>';
		for($i = 1; $i < 32; $i++)
			echo '<option value="' . sprintf("%02d", $i) . '">' . sprintf("%02d", $i) . '</option>';
		echo '</select>';
		echo '</div>';
		
		echo '<div class="col-lg-3">';
		echo '<select class="form-control col-lg-4" id="' . $this->getId() . '-month" name="' . $this->getId() . '_month">';
		print_r('<option value="">' . __(' -- Month -- ', 'jigoshop2-sisow') . '</option>');
		echo '<option value="01">' . __('January', 'jigoshop2-sisow') . '</option>';
		echo '<option value="02">' . __('February', 'jigoshop2-sisow') . '</option>';
		echo '<option value="03">' . __('March', 'jigoshop2-sisow') . '</option>';
		echo '<option value="04">' . __('April', 'jigoshop2-sisow') . '</option>';
		echo '<option value="05">' . __('May', 'jigoshop2-sisow') . '</option>';
		echo '<option value="06">' . __('June', 'jigoshop2-sisow') . '</option>';
		echo '<option value="07">' . __('July', 'jigoshop2-sisow') . '</option>';
		echo '<option value="08">' . __('August', 'jigoshop2-sisow') . '</option>';
		echo '<option value="09">' . __('September', 'jigoshop2-sisow') . '</option>';
		echo '<option value="10">' . __('October', 'jigoshop2-sisow') . '</option>';
		echo '<option value="11">' . __('November', 'jigoshop2-sisow') . '</option>';
		echo '<option value="12">' . __('December', 'jigoshop2-sisow') . '</option>';
		echo '</select>';
		echo '</div>';
		
		echo '<div class="col-lg-2">';
		echo '<select class="form-control col-lg-4" id="' . $this->getId() . '-year" name="' . $this->getId() . '_year">';
		echo '<option value="">' . __(' -- Year -- ', 'jigoshop2-sisow') . '</option>';
		for($i = date("Y") - 17; $i > date("Y") - 150; $i--)
			echo '<option value="' . $i . '">' . $i . '</option>';
		echo '</select>';
		echo '</div>';
		echo '</div>';
				
		echo '</div>';
		echo '</div>'; // end birthday
		
		if($this->settings['useb2b'])
		{
			echo '<div class="row clearfix">';
			echo '<div class="form-group col-lg-6 clearfix">';
			echo '<labelfor="' . $this->getId() . '-coc">' . __('CoC number', 'jigoshop2-sisow') . '</label>';
			echo '<input type="text" class="form-control" id="' . $this->getId() . '-coc" name="' . $this->getId() . '_coc" value="">';
			print_r('<small>' . __('(Only required for B2B.)', 'jigoshop2-sisow') . '</small>');
			echo '</div>';
			echo '</div>';
		}		
		
		echo '</fieldset>';
	}
	
	public function validateFields()
	{
		if(empty($_POST[$this->getId() . '_gender']))
		{			
			throw new Exception(__('Please select your gender!', 'jigoshop2-sisow'));
			return false;
		}
		
		if(empty($_POST[$this->getId() . '_day']) || empty($_POST[$this->getId() . '_month']) || empty($_POST[$this->getId() . '_year']))
		{			
			throw new Exception(__('Please verify your birthday!', 'jigoshop2-sisow'));
			return false;
		}
		
		return true;
	}
}