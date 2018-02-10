<?php
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
class thnxblockfootertext extends Module implements WidgetInterface
{
	public function __construct()
	{
		$this->name = 'thnxblockfootertext';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'thanksit.com';
		$this->need_instance = 0;
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Platinum Theme Footer Text Block');
		$this->description = $this->l('Platinum Theme Theme Footer Text Block.');
		$this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
	}
	public function install()
	{
		if(!parent::install()
			|| !$this->registerHook('displayFooterAfter'))
			return false;
			Configuration::updateValue('thnxfootercopycolumn','12');
			Configuration::updateValue('thnxfootercopyfloat','disable');
			$langs = Language::getLanguages();
			foreach($langs as $l)
			{
				Configuration::updateValue('thnxblockfootertext_'.$l['id_lang'],'Copyright @ 2017 <a href="#">Platinum</a>. All rights reserved.');
			}
			return true;
	}
	public function uninstall()
	{
		if(!parent::uninstall()
			|| !Configuration::deleteByName('thnxfootercopycolumn')
			|| !Configuration::deleteByName('thnxfootercopyfloat')
			)
			return false;
			$langs = Language::getLanguages();
			foreach($langs as $l)
			{
					Configuration::deleteByName('thnxblockfootertext_'.$l['id_lang']);
			}
			return true;
	}
	public function renderWidget($hookName = null, $configuration = array())
	{
	    $this->smarty->assign($this->getWidgetVariables($hookName,$configuration));
	    return $this->fetch('module:'.$this->name.'/views/templates/front/'.$this->name.'.tpl');	
	}
	public function getWidgetVariables($hookName = null, $configuration = array())
	{
		$return_arr = array();
	    $id_lang = (int)$this->context->language->id;
	    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
	    $thnxblockfootertext = Configuration::get('thnxblockfootertext_'.$id_lang);
	    if(empty($thnxblockfootertext)){
	    	$thnxblockfootertext = Configuration::get('thnxblockfootertext_'.$default_lang);
	    }
	    $return_arr['thnxblockfootertext'] = $thnxblockfootertext;
	    $return_arr['thnxfootercopycolumn'] = Configuration::get('thnxfootercopycolumn');
	    $return_arr['thnxfootercopyfloat'] = Configuration::get('thnxfootercopyfloat');
	    return $return_arr;	   
	}
	public function postProcess()
	{
		if (Tools::isSubmit('submit'.$this->name))
		{
			$langs = Language::getLanguages();
			foreach($langs as $l)
			{
				Configuration::updateValue('thnxblockfootertext_'.$l['id_lang'],Tools::getValue('thnxblockfootertext_'.$l['id_lang']), true);
			}
			Configuration::updateValue('thnxfootercopycolumn',Tools::getValue('thnxfootercopycolumn'), true);
			Configuration::updateValue('thnxfootercopyfloat',Tools::getValue('thnxfootercopyfloat'), true);
			return $this->displayConfirmation($this->l('The settings have been updated.'));
		}
		return '';
	}
	public function getContent()
	{
		return $this->postProcess().$this->renderForm();
	}
	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'tinymce' => true,
				'legend' => array(
					'title' => $this->l('Footer Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'textarea',
						'lang' => true,
						'label' => $this->l('Description'),
						'name' => 'thnxblockfootertext',
						'desc' => $this->l('Please enter a short but meaningful description for the footer.'),
						'cols' => 40,
						'rows' => 10,
					),
					array(
					    'type' => 'select',
					    'label' => $this->l('Select column'),
					    'name' => 'thnxfootercopycolumn',
					    'default_val' => '4',
					    'desc' => $this->l('Choose colum for this block'),
					    'options' => array(
					    	'id' => 'id',
					    	'name' => 'name',
					    	'query' => array(
					    		array(
					    			'id' => '6',
					    			'name' => 'Column two (col-sm-6)'
					    			),
					    		array(
					    			'id' => '4',
					    			'name' => 'Column three (col-sm-4)'
					    			),
					    		array(
					    			'id' => '3',
					    			'name' => 'Column four (col-sm-3)'
					    			),
					    		array(
					    			'id' => '12',
					    			'name' => 'Column full (col-sm-12)'
					    			),
					    		array(
					    			'id' => 'none',
					    			'name' => 'None'
					    			),
					    		)
					    	)
					),
					array(
					    'type' => 'select',
					    'label' => $this->l('Float Align'),
					    'name' => 'thnxfootercopyfloat',
					    'default_val' => 'disable',
					    'desc' => $this->l('Choose colum for this block'),
					    'options' => array(
					    	'id' => 'id',
					    	'name' => 'name',
					    	'query' => array(
					    		array(
					    			'id' => 'disable',
					    			'name' => 'Disable'
					    			),
					    		array(
					    			'id' => 'f_left',
					    			'name' => 'Float Left'
					    			),
					    		array(
					    			'id' => 'f_right',
					    			'name' => 'Float Right'
					    			),
					    		array(
					    			'id' => 'f_none',
					    			'name' => 'Float none'
					    			),
					    		)
					    	)
					),
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			),
		);
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->module = $this;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submit'.$this->name;
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'uri' => $this->getPathUri(),
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper->generateForm(array($fields_form));
	}
	public function getConfigFieldsValues()
	{
		$fields = array();
		$langs = Language::getLanguages();
		foreach($langs as $l)
		{
			$fields['thnxblockfootertext'][$l['id_lang']] = Tools::getValue('thnxblockfootertext_'.$l['id_lang'], Configuration::get('thnxblockfootertext_'.$l['id_lang']));
		}
		$fields['thnxfootercopycolumn'] = Tools::getValue('thnxfootercopycolumn', Configuration::get('thnxfootercopycolumn'));
		$fields['thnxfootercopyfloat'] = Tools::getValue('thnxfootercopyfloat', Configuration::get('thnxfootercopyfloat'));
		return $fields;
	}
}
