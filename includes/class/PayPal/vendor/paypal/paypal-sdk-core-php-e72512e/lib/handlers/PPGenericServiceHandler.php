<?php

/**
 *
 * Adds non-authentication headers that are common to PayPal's
 * merchant and platform APIs
 */
class PPGenericServiceHandler implements IPPHandler {

	private $sdkName;
	private $sdkVersion;

	public function __construct($sdkName, $sdkVersion) {
		$this->sdkName = $sdkName;
		$this->sdkVersion = $sdkVersion;
	}

	public function handle($httpConfig, $request, $options) {
		$httpConfig->addHeader('X-PAYPAL-REQUEST-DATA-FORMAT', $request->getBindingType());
		$httpConfig->addHeader('X-PAYPAL-RESPONSE-DATA-FORMAT', $request->getBindingType());
		$httpConfig->addHeader('X-PAYPAL-DEVICE-IPADDRESS', PPUtils::getLocalIPAddress());
		$httpConfig->addHeader('X-PAYPAL-REQUEST-SOURCE', $this->getRequestSource());
		if(isset($options['config']['service.SandboxEmailAddress'])) {
			$httpConfig->addHeader('X-PAYPAL-SANDBOX-EMAIL-ADDRESS', $options['config']['service.SandboxEmailAddress']);
		}		
	}

	/**
	 * Compute the value that needs to sent for the PAYPAL_REQUEST_SOURCE
	 * parameter when making API calls
	 */
	private function getRequestSource() {
		return str_replace(" ", "-", $this->sdkName) . "-" . $this->sdkVersion;
	}
}
