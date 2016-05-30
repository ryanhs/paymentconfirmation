<?php
if (!defined('_PS_VERSION_'))
	exit;

class PaymentConfirmation extends Module
{
	const PREFIX = 'PaymentConfirmation_';
	
	protected $_hooks = array(
		'customeraccount',
		'displaynav'
	);
	
	public function __construct()
	{
		$this->name = 'paymentconfirmation';
		$this->version = '1.8.1';
		$this->author = 'Ryan hs';
		$this->need_instance = 0;
		parent::__construct();
		
		$this->displayName = $this->l($this->name);
		$this->description = $this->l('Payment Confirmation');
	}

	public function install()
	{
		if (parent::install()) {
			foreach ($this->_hooks as $hook) {
				if (!$this->registerHook($hook)) {
					return false;
				}
			}
			
			if (!Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'payment_confirmation`;'))
				return false;
			if (!Db::getInstance()->execute('CREATE TABLE `'._DB_PREFIX_.'payment_confirmation` ( '.
												'`id_payment_confirmation` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, '.
												'`email` varchar(128) NOT NULL, '.
												'`orderId` int(11) NOT NULL, '.
												'`account` int(11) NOT NULL, '.
												'`bank` varchar(64) NOT NULL, '.
												'`total` int(11) NOT NULL, '.
												'`date` date NOT NULL, '.
												'`notes` text NOT NULL '.
											') ENGINE=InnoDB DEFAULT CHARSET=latin1;'
				))
				return false;
			
			return true;
		}
		
		return false;
	}
	
	public function uninstall()
	{
		if (parent::uninstall()) {
			foreach ($this->_hooks as $hook) {
				if (!$this->unregisterHook($hook)) {
					return false;
				}
			}
			
			if (!Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'payment_confirmation`;'))
				return false;
				
			return true;
		}
		
		return false;
	}
	
	
	
	
	public function hookCustomerAccount($params)
	{
		return $this->display(__FILE__, 'views/templates/hooks/customeraccount.tpl', $this->getCacheId());
	}
	public function hookDisplayNav($params)
	{
		return $this->display(__FILE__, 'views/templates/hooks/displaynav.tpl', $this->getCacheId());
	}
	
	
	
}
