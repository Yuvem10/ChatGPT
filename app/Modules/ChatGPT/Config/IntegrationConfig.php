<?php
namespace ChatGPT\Config;

use CodeIgniter\Config\BaseConfig;

class IntegrationConfig extends BaseConfig
{

	public $emailConfig = [
		'protocol'    	=> 'smtp',
		'SMTPHost'    	=> 'ssl0.ovh.net',
		'SMTPPort'    	=> 465,
		'SMTPCrypto'  	=> 'ssl',
		'SMTPUser'    	=> '',
		'SMTPPass'    	=> '',
		'mailType'    	=> 'html',
		'charset'     	=> 'utf-8',
		'wordWrap'    	=> true,
		'From'    		=> PROJECT_ID.' <projet@ingefox.com>',
	];
}
