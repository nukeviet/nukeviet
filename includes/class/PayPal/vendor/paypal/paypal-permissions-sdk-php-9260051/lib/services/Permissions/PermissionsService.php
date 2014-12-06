<?php 

/**
 * AUTO GENERATED code for Permissions
 */
class PermissionsService extends PPBaseService {

	// Service Version
	private static $SERVICE_VERSION = "";

	// Service Name
	private static $SERVICE_NAME = "Permissions";

    // SDK Name
	protected static $SDK_NAME = "permissions-php-sdk";
	
	// SDK Version
	protected static $SDK_VERSION = "2.6.107";

	public function __construct($config = null) {
		parent::__construct(self::$SERVICE_NAME, 'NV', $config);
	}


	/**
	 * Service Call: RequestPermissions
	 * @param RequestPermissionsRequest $requestPermissionsRequest
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return RequestPermissionsResponse
	 * @throws APIException
	 */
	public function RequestPermissions($requestPermissionsRequest, $apiCredential = NULL) {
		$ret = new RequestPermissionsResponse();
        $apiContext = new PPApiContext($this->config);
        $handlers = array(
            new PPPlatformServiceHandler($apiCredential, self::$SDK_NAME, self::$SDK_VERSION),
        );
		$resp = $this->call('Permissions', 'RequestPermissions', $requestPermissionsRequest, $apiContext, $handlers);
		$ret->init(PPUtils::nvpToMap($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetAccessToken
	 * @param GetAccessTokenRequest $getAccessTokenRequest
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetAccessTokenResponse
	 * @throws APIException
	 */
	public function GetAccessToken($getAccessTokenRequest, $apiCredential = NULL) {
		$ret = new GetAccessTokenResponse();
		$apiContext = new PPApiContext($this->config);
        $handlers = array(
            new PPPlatformServiceHandler($apiCredential, self::$SDK_NAME, self::$SDK_VERSION),
        );
		$resp = $this->call('Permissions', 'GetAccessToken', $getAccessTokenRequest, $apiContext, $handlers);
		$ret->init(PPUtils::nvpToMap($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetPermissions
	 * @param GetPermissionsRequest $getPermissionsRequest
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetPermissionsResponse
	 * @throws APIException
	 */
	public function GetPermissions($getPermissionsRequest, $apiCredential = NULL) {
		$ret = new GetPermissionsResponse();
		$apiContext = new PPApiContext($this->config);
        $handlers = array(
            new PPPlatformServiceHandler($apiCredential, self::$SDK_NAME, self::$SDK_VERSION),
        );
		$resp = $this->call('Permissions', 'GetPermissions', $getPermissionsRequest, $apiContext, $handlers);
		$ret->init(PPUtils::nvpToMap($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: CancelPermissions
	 * @param CancelPermissionsRequest $cancelPermissionsRequest
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return CancelPermissionsResponse
	 * @throws APIException
	 */
	public function CancelPermissions($cancelPermissionsRequest, $apiCredential = NULL) {
		$ret = new CancelPermissionsResponse();
		$apiContext = new PPApiContext($this->config);
        $handlers = array(
            new PPPlatformServiceHandler($apiCredential, self::$SDK_NAME, self::$SDK_VERSION),
        );
		$resp = $this->call('Permissions', 'CancelPermissions', $cancelPermissionsRequest, $apiContext, $handlers);
		$ret->init(PPUtils::nvpToMap($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetBasicPersonalData
	 * @param GetBasicPersonalDataRequest $getBasicPersonalDataRequest
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetBasicPersonalDataResponse
	 * @throws APIException
	 */
	public function GetBasicPersonalData($getBasicPersonalDataRequest, $apiCredential = NULL) {
		$ret = new GetBasicPersonalDataResponse();
		$apiContext = new PPApiContext($this->config);
        $handlers = array(
            new PPPlatformServiceHandler($apiCredential, self::$SDK_NAME, self::$SDK_VERSION),
        );
		$resp = $this->call('Permissions', 'GetBasicPersonalData', $getBasicPersonalDataRequest, $apiContext, $handlers);
		$ret->init(PPUtils::nvpToMap($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetAdvancedPersonalData
	 * @param GetAdvancedPersonalDataRequest $getAdvancedPersonalDataRequest
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetAdvancedPersonalDataResponse
	 * @throws APIException
	 */
	public function GetAdvancedPersonalData($getAdvancedPersonalDataRequest, $apiCredential = NULL) {
		$ret = new GetAdvancedPersonalDataResponse();
		$apiContext = new PPApiContext($this->config);
        $handlers = array(
            new PPPlatformServiceHandler($apiCredential, self::$SDK_NAME, self::$SDK_VERSION),
        );
		$resp = $this->call('Permissions', 'GetAdvancedPersonalData', $getAdvancedPersonalDataRequest, $apiContext, $handlers);
		$ret->init(PPUtils::nvpToMap($resp));
		return $ret;
	}
	 
}