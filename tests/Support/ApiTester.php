<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * Gửi request API với đầy đủ thông tin xác thực tự động
     */
    public function sendApiRequest(string $module, string $action, array $data = [])
    {
        $apiKey = $_ENV['API_KEY'] ?? '';
        $secret = $_ENV['NV_SECRET'] ?? '';
        $timestamp = time();
        $hashSecret = password_hash($secret . '_' . $timestamp, PASSWORD_DEFAULT);

        $authData = [
            'apikey' => $apiKey,
            'timestamp' => $timestamp,
            'hashsecret' => $hashSecret,
            'language' => 'vi'
        ];

        $data['module'] = $module;
        $data['action'] = $action;
        
        // Hợp nhất dữ liệu auth với dữ liệu truyền thêm (auth không bị thay thế)
        $payload = array_merge($authData, $data);
        
        $this->sendPost('/api.php', $payload);
    }
}
