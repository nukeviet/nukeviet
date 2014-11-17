<?php
/**
 *
 * Adds authentication headers (Platform/Merchant) that are
 * specific to PayPal's 3-token credentials
 */
class PPSignatureAuthHandler implements IPPHandler {
		
	public function handle($httpConfig, $request, $options) {
		
		$credential = $request->getCredential();
		if(!isset($credential)) {
			return;
		}		
		$thirdPartyAuth = $credential->getThirdPartyAuthorization();

		switch($request->getBindingType()) {
			case 'NV':
				if(!$thirdPartyAuth || !$thirdPartyAuth instanceof PPTokenAuthorization) {
					$httpConfig->addHeader('X-PAYPAL-SECURITY-USERID', $credential->getUserName());
					$httpConfig->addHeader('X-PAYPAL-SECURITY-PASSWORD', $credential->getPassword());
					$httpConfig->addHeader('X-PAYPAL-SECURITY-SIGNATURE', $credential->getSignature());
					if($thirdPartyAuth) {
						$httpConfig->addHeader('X-PAYPAL-SECURITY-SUBJECT', $thirdPartyAuth->getSubject());
					}
				}
				break;
			case 'SOAP':
				if($thirdPartyAuth && $thirdPartyAuth instanceof PPTokenAuthorization) {
					$request->addBindingInfo('securityHeader' , '<ns:RequesterCredentials/>');
				} else {
					$securityHeader = '<ns:RequesterCredentials><ebl:Credentials>';
					$securityHeader .= '<ebl:Username>' . $credential->getUserName() . '</ebl:Username>';
					$securityHeader .= '<ebl:Password>' . $credential->getPassword() . '</ebl:Password>';
					$securityHeader .= '<ebl:Signature>' . $credential->getSignature() . '</ebl:Signature>';					
					if($thirdPartyAuth && $thirdPartyAuth instanceof PPSubjectAuthorization) {
						$securityHeader .= '<ebl:Subject>' . $thirdPartyAuth->getSubject() . '</ebl:Subject>';
					}
					$securityHeader .= '</ebl:Credentials></ns:RequesterCredentials>';
					$request->addBindingInfo('securityHeader' , $securityHeader);					
				}
				break;
		}
	}
	
}