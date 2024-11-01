<?php
namespace Jigoshop\Extension\SisowGateway\Methods;

use Jigoshop\Core\Messages;
use Jigoshop\Core\Options;
use Jigoshop\Endpoint\Processable;
use Jigoshop\Entity\Order;
use Jigoshop\Exception;
use Jigoshop\Frontend\Pages;
use Jigoshop\Helper\Api;
use Jigoshop\Helper\Currency;
use Jigoshop\Helper\Options as OptionsHelper;
use Jigoshop\Helper\Order as OrderHelper;
use Jigoshop\Integration\Helper\Render;
use Jigoshop\Payment\Method2;
use Jigoshop\Service\CartServiceInterface;
use Jigoshop\Service\OrderServiceInterface;
use Jigoshop\Extension\SisowGateway\API\Sisow;

class AbstractSisow
{	
	public function __construct(Options $options, CartServiceInterface $cartService, OrderServiceInterface $orderService, Messages $messages)
	{
		$this->options = $options;
		$this->messages = $messages;
		$this->cartService = $cartService;
		$this->orderService = $orderService;
		
		$this->settings = $options->get('payment.'.$this->getId());
	}

	/**
	 * @return string ID of payment method.
	 */
	public function getId()
	{
		throw new \Exception(__('getId not implemented.', 'jigoshop2-sisow'));
	}
	
	/**
	 * @return string ID of payment method.
	 */
	public function getTitle()
	{
		throw new \Exception(__('getTitle not implemented.', 'jigoshop2-sisow'));
	}

	/**
	 * @return string Human readable name of method.
	 */
	public function getName()
	{
		return is_admin() ? 'Sisow ' . $this->getTitle() : $this->settings['title'];
	}

	/**
	 * @return bool Whether current method is enabled and able to work.
	 */
	public function isEnabled()
	{
		return $this->settings['enabled'];
	}

	public function isActive() {
		if(isset($this->settings['enabled'])) {
			return $this->settings['enabled'];
		}
	}

	public function setActive($state) {
		$this->settings['enabled'] = $state;

		return $this->settings;
	}

	public function isConfigured() {
		if(isset($this->settings['test_mode']) && $this->settings['test_mode']) {
			if(isset($this->settings['test_email']) && $this->settings['test_email']) {
				return true;
			}
			return false;
		}

		if(isset($this->settings['email']) && $this->settings['email']) {
			return true;
		}

		return false;
	}
	
	public function hasTestMode() {
		return true;
	}

	public function isTestModeEnabled() {
		if(isset($this->settings['testmode'])) {
			return $this->settings['testmode'];
		}
	}

	public function setTestMode($state) {
		$this->settings['testmode'] = $state;
	
		return $this->settings;
	}

	/**
	 * @return array List of options to display on Payment settings page.
	 */
	public function getOptions()
	{
		$settings = array(
						array(
							'name'    => sprintf('[%s][enabled]', $this->getId()),
							'title'   => __('Is enabled?', 'jigoshop2-sisow'),
							'type'    => 'checkbox',
							'checked' => $this->settings['enabled'],
							'classes' => array('switch-medium'),
						),
						array(
							'name'  => sprintf('[%s][merchantid]', $this->getId()),
							'title' => __('Merchant ID', 'jigoshop2-sisow'),
							'type'  => 'text',
							'value' => $this->settings['merchantid'],
						),
						array(
							'name'  => sprintf('[%s][merchantkey]', $this->getId()),
							'title' => __('Merchant Key', 'jigoshop2-sisow'),
							'type'  => 'text',
							'value' => $this->settings['merchantkey'],
						),
						array(
							'name'  => sprintf('[%s][shopid]', $this->getId()),
							'title' => __('Shop ID', 'jigoshop2-sisow'),
							'type'  => 'text',
							'value' => $this->settings['shopid'],
						),
						array(
							'name'  => sprintf('[%s][title]', $this->getId()),
							'title' => __('Title', 'jigoshop2-sisow'),
							'type'  => 'text',
							'value' => empty($this->settings['title']) ? $this->getTitle() : $this->settings['title'],
						),
						array(
							'name'  => sprintf('[%s][description]', $this->getId()),
							'title' => __('Description', 'jigoshop2-sisow'),
							'tip'   => 'Description on bank account', 'jigoshop2-sisow',
							'type'  => 'text',
							'value' => $this->settings['description'],
						),
						array(
							'name'    => sprintf('[%s][testmode]', $this->getId()),
							'title'   => __('Testmode', 'jigoshop2-sisow'),
							'type'    => 'checkbox',
							'checked' => $this->settings['testmode'],
							'classes' => array('switch-medium'),
						),
					);
		
		return $this->addMethodSettings($settings);
	}
	
	public function addMethodSettings($settings)
	{
		return $settings;
	}

	/**
	 * Validates and returns properly sanitized options.
	 *
	 * @param $settings array Input options.
	 *
	 * @return array Sanitized result.
	 */
	public function validateOptions($settings)
	{
		$settings['enabled'] = $settings['enabled'] == 'on';
		$settings['title'] = trim(htmlspecialchars(strip_tags($settings['title'])));
		$settings['description'] = trim(htmlspecialchars(strip_tags($settings['description'], '<p><a><strong><em><b><i>')));
		
		$settings['merchantid'] = trim(htmlspecialchars(strip_tags($settings['merchantid'])));
		$settings['merchantkey'] = trim(htmlspecialchars(strip_tags($settings['merchantkey'])));
		$settings['shopid'] = trim(htmlspecialchars(strip_tags($settings['shopid'])));
		$settings['testmode'] = $settings['testmode'] == 'on';
		$settings['useb2b'] = $settings['useb2b'] == 'on';
		$settings['include'] = $settings['include'] == 'on';
		$settings['days'] = $settings['days'];

		return $settings;
	}

	/**
	 * Renders method fields and data in Checkout page.
	 */
	public function render()
	{
	}
	
	public function validateFields()
	{
		return true;
	}

	/**
	 * @param Order $order Order to process payment for.
	 *
	 * @return string URL to redirect to.
	 * @throws Exception On any payment error.
	 */
	public function process($order)
	{
		if($this->validateFields())
		{		
			$sisow = new Sisow($this->settings['merchantid'], $this->settings['merchantkey'], $this->settings['shopid']);
			$sisow->AddBillingAddress(
								$order->getCustomer()->getBillingAddress()->getFirstName(),
								$order->getCustomer()->getBillingAddress()->getLastName(),
								$order->getCustomer()->getBillingAddress()->getEmail(),
								'',
								$order->getCustomer()->getBillingAddress()->getAddress(),
								$order->getCustomer()->getBillingAddress()->getState(),
								$order->getCustomer()->getBillingAddress()->getPostcode(),
								$order->getCustomer()->getBillingAddress()->getCity(),
								$order->getCustomer()->getBillingAddress()->getCountry(),
								$order->getCustomer()->getBillingAddress()->getPhone()
							);
							
			$sisow->AddShippingAddress(
								$order->getCustomer()->getShippingAddress()->getFirstName(),
								$order->getCustomer()->getShippingAddress()->getLastName(),
								$order->getCustomer()->getShippingAddress()->getEmail(),
								'',
								$order->getCustomer()->getShippingAddress()->getAddress(),
								$order->getCustomer()->getShippingAddress()->getState(),
								$order->getCustomer()->getShippingAddress()->getPostcode(),
								$order->getCustomer()->getShippingAddress()->getCity(),
								$order->getCustomer()->getShippingAddress()->getCountry(),
								$order->getCustomer()->getShippingAddress()->getPhone()
							);
				
			foreach($order->getItems() as $item)
			{
				$sisow->AddProduct(
								$item->getProductId(),
								$item->getName(),
								$item->getQuantity(),
								$item->getPrice(),
								$item->getCost() + $item->getTax(),
								$item->getCost(),
								$item->getTax(),
								round(((($item->getCost() + $item->getTax()) * 100.0) / $item->getCost()) - 100.0)
							);
			}
			
			if($order->getShippingPrice() > 0)
			{				
				$sisow->AddProduct(
								'shipping',
								$order->getShippingMethod()->getName(),
								1,
								$order->getShippingPrice(),
								$order->getShippingPrice() + reset($order->getShippingTax()),
								$order->getShippingPrice(),
								reset($order->getShippingTax()),
								round(((($order->getShippingPrice() + reset($order->getShippingTax())) * 100.0) / $order->getShippingPrice()) - 100.0)
							);
			}			
			
			foreach($order->getDiscounts() as $discount)
			{
				$sisow->AddProduct(
								$discount->getCode(),
								$discount->getCode(),
								1,
								$discount->getAmount() * -1,
								$discount->getAmount() * -1,
								$discount->getAmount() * -1,
								0,
								0
							);
			}

			if(!empty($_POST[$this->getId() . '_issuer']))
				$sisow->AddIssuer($_POST[$this->getId() . '_issuer']);
			
			if(!empty($_POST[$this->getId() . '_bic']))
				$sisow->AddBic($_POST[$this->getId() . '_bic']);
			
			if(!empty($_POST[$this->getId() . '_gender']))
				$sisow->AddGender($_POST[$this->getId() . '_gender']);
			
			if(!empty($_POST[$this->getId() . '_day']) && !empty($_POST[$this->getId() . '_month']) && !empty($_POST[$this->getId() . '_year']))
				$sisow->AddBirthdate($_POST[$this->getId() . '_day'] . $_POST[$this->getId() . '_month'] . $_POST[$this->getId() . '_year']);
			
			if(!empty($_POST[$this->getId() . '_coc']))
				$sisow->AddCoc($_POST[$this->getId() . '_coc']);
			
			if(!empty($_POST[$this->getId() . '_iban']))
				$sisow->AddIban($_POST[$this->getId() . '_iban']);
			
			if(array_key_exists('days', $this->settings) && $this->settings['days'] > 0)
				$sisow->AddDays($this->settings['days']);
			
			if(array_key_exists('include', $this->settings) && $this->settings['include'] == 'on')
				$sisow->SetIncluding();
			
			$returnUrl = Api::getUrl($this->getId());
			$cancelUrl = Api::getUrl($this->getId());
			$notifyUrl = Api::getUrl($this->getId());
			$callbackUrl = Api::getUrl($this->getId());
			
			if(!$sisow->TransactionRequest($this->getCode(), $order->getNumber(), $order->getTotal(), $this->options->get('general.currency'), $this->settings['testmode'] == 'on', $this->settings['description'] . ' ' . $order->getNumber(), $returnUrl, $cancelUrl, $notifyUrl, $callbackUrl, $order->getId()))
			{
				if($this->getCode() == 'focum')
					throw new \Exception(sprintf(__('Unfortunately the payment with %s can\'t be completed at this moment, please choose a different payment method.', 'jigoshop2-sisow'), $this->settings['title'] ));
				else if($this->getCode() == 'afterpay')
					throw new \Exception('Het spijt ons u te moeten mededelen dat uw aanvraag om uw bestelling achteraf te betalen op dit moment niet door AfterPay wordt geaccepteerd. Dit kan om diverse (tijdelijke) redenen zijn.Voor vragen over uw afwijzing kunt u contact opnemen met de Klantenservice van AfterPay. Of kijk op de website van AfterPay bij “Veel gestelde vragen” via de link http://www.afterpay.nl/page/consument-faq onder het kopje “Gegevenscontrole”. Wij adviseren u voor een andere betaalmethode te kiezen om alsnog de betaling van uw bestelling af te ronden.');
				else
					throw new \Exception(sprintf(__('Error occured while starting payment, please try again. (%s)', 'jigoshop2-sisow'), $sisow->errorCode));
				exit;
			}
			
			$url = '';
			$noRedirect = array('overboeking', 'afterpay', 'focum');
			
			if(in_array($this->getCode(), $noRedirect))
			{
				$url = OrderHelper::getThankYouLink($order);
				
				$order->setStatus(Order\Status::ON_HOLD, sprintf(__('Payment via Sisow %s.', 'jigoshop2-sisow'), $this->getTitle()));
				$this->orderService->save($order);
			}
			else
				$url = $sisow->issuerUrl;
			
			wp_redirect( $url );
			exit;
		}
	}

	public function processResponse()
	{
		$orderId = $_GET['ec'];
		$trxId = $_GET['trxid'];
		$order = $this->orderService->find($orderId);
				
		if(isset($_GET['notify']) || isset($_GET['callback']))
		{
			$sisow = new Sisow($this->settings['merchantid'], $this->settings['merchantkey'], $this->settings['shopid']);
			if(!$sisow->StatusRequest($trxId))
				exit('StatusRequest Failed');
			
			switch($sisow->status)
			{
				case 'Success':
					$order->setStatus(OrderHelper::getStatusAfterCompletePayment($order), __('Sisow payment completed', 'jigoshop2-sisow'));
					break;
				case 'Cancelled':
					exit('Do nothing, cancel URL handles it');
				case 'Expired':
				case 'Failure':
				case 'Denied':
					$order->setStatus(Order\Status::CANCELLED, sprintf(__('Payment %s via Sisow.', 'jigoshop2-sisow'), $sisow->status));
					break;
				case 'Open':
				case 'Pending':
				case 'Reservation':
					$order->setStatus(Order\Status::ON_HOLD, sprintf(__('Payment %s via Sisow.', 'jigoshop2-sisow'), $sisow->status));
					break;
				case 'Reversed':
					$order->setStatus(Order\Status::REFUNDED, sprintf(__('Payment %s via Sisow.', 'jigoshop2-sisow'), $sisow->status));
					break;
				default:
					exit('Status not known: ' + $sisow->status);
			}
			
			$this->orderService->save($order);
			exit('Notify done!');
		}
		
		$status = $_GET['status'];
		
		if($status == 'Success' || $status == 'Open' || $status == 'Pending' || $status == 'Reservation')
			$url = OrderHelper::getThankYouLink($order);
		else
			$url = OrderHelper::getCancelLink($order);
		
		wp_redirect( $url );
		exit;
	}
}