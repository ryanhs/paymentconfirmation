<?php
if (!defined('_PS_VERSION_'))
	exit;
//~ phpinfo();exit;
class PaymentConfirmationConfirmationModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		$this->display_column_left = false;
		$this->display_column_right = false;
		
		if ($this->validateProcess()) {
			$this->setTemplate('thankyou.tpl');
		} else {
			if (isset($this->context->customer->id))
				$this->context->smarty->assign('customer', $this->context->customer);
				
			$this->context->smarty->assign('currentDate', date('Y-m-d'));
			$this->setTemplate('form.tpl');
		}
		
		parent::initContent();
	}
	
	public function validateProcess()
	{		
		$error = false;
		if (Tools::isSubmit('submitPayment')) {
			if (!Validate::isEmail($email = Tools::getValue('email'))) {
				$this->errors[] = Tools::displayError('mohon email ditulis dalam format email!');
				$error = true;
			}
			
			if (!Validate::isInt($orderId = Tools::getValue('order_id'))) {
				$this->errors[] = Tools::displayError('mohon no order ditulis hanya berupa angka!');
				$error = true;
			}
			
			if (!Validate::isInt($rekening = Tools::getValue('rekening'))) {
				$this->errors[] = Tools::displayError('mohon no rekening ditulis hanya berupa angka!');
				$error = true;
			}
			
			if (!Validate::isCleanHtml($bank = Tools::getValue('bank'))) {
				$this->errors[] = Tools::displayError('silahkan tulis nama bank dengan jelas!');
				$error = true;
			}
			
			if (!Validate::isInt($total = Tools::getValue('total'))) {
				$this->errors[] = Tools::displayError('mohon total bayar ditulis hanya berupa angka!');
				$error = true;
			}
			
			if (intval($month = Tools::getValue('Date_Month')) <= 0) {
				$this->errors[] = Tools::displayError('data bulan error!');
				$error = true;
			}
			
			if (intval($day = Tools::getValue('Date_Day')) <= 0) {
				$this->errors[] = Tools::displayError('data tanggal error!');
				$error = true;
			}
			
			if (intval($year = Tools::getValue('Date_Year')) <= 0) {
				$this->errors[] = Tools::displayError('data tahun error!');
				$error = true;
			}
			
			if (!Validate::isCleanHtml($catatan = strval(Tools::getValue('catatan')))) {
				$this->errors[] = Tools::displayError('mohon catatan ditulis dengan jelas!');
				$error = true;
			}
			
			$this->context->smarty->assign('customDate', "{$year}-{$month}-{$day}");
			if ($error) return;
			
			
			//~ var_dump(Contact::getContacts($this->context->language->id)); exit;
			$contactId = 2;
			$contact = new Contact($contactId, $this->context->language->id);
			//~ $from = 'paymentconfirmation@'.$_SERVER['SERVER_NAME'];
			$message =  "payment confirmation order #{$orderId}".PHP_EOL.
						"".PHP_EOL.
						"Order: {$orderId}".PHP_EOL.
						"Rekening: {$rekening}".PHP_EOL.
						"Bank: {$bank}".PHP_EOL.
						"Tanggal: {$year}-{$month}-{$day}".PHP_EOL.
						"Bayar: Rp {$total}".PHP_EOL.
						
						(strlen($catatan) > 0 ? PHP_EOL."Catatan: ".PHP_EOL.$catatan : '')
					;
			
			// customer thread
			$customer = $this->context->customer;
			if (!$customer->id) {
				$customer->getByEmail($email);
			}
			//~ var_dump($customer); exit;
			$ct = new CustomerThread();
			if (isset($customer->id)) {
				$ct->id_customer = (int)$customer->id;
			}
			$ct->id_shop = (int)$this->context->shop->id;
			$ct->id_order = (int)$orderId;
			$ct->id_contact = (int)$contactId;
			$ct->id_lang = (int)$this->context->language->id;
			$ct->email = isset($customer->id) ? $customer->email : $email;
			$ct->status = 'open';
			$ct->token = Tools::passwdGen(12);
			$ct->add();
			
			// send mail
			if ($ct->id) {
				$cm = new CustomerMessage();
				$cm->id_customer_thread = $ct->id;
				$cm->message = $message;
				$cm->ip_address = (int)ip2long(Tools::getRemoteAddr());
				$cm->user_agent = $_SERVER['HTTP_USER_AGENT'];
				if (!$cm->add()) {
					$this->errors[] = Tools::displayError('An error occurred while sending the message.');
				} else {
					// save to db as backup
					Db::getInstance()->autoExecute(_DB_PREFIX_.'payment_confirmation', array(
						'email' => $email,
						'orderId' => $orderId,
						'account' => $rekening,
						'bank' => $bank,
						'total' => $total,
						'date' => "{$year}-{$month}-{$day}",
						'notes' => $catatan,
					), 'INSERT');
				}
			} else {
				$this->errors[] = Tools::displayError('An error occurred while sending the message.');
			}
			
			
			// check true
			if (count($this->errors) > 0) {
				array_unique($this->errors);
			} else {
				return true;
			}
		}
		return false;
	}
}
