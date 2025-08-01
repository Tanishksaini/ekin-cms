<?php
namespace Xibo\Controller;

use Xibo\Support\Sanitizer;
use Xibo\Database\PDOConnect;

class PlayerRegistration {

    // Generate Code API
    public function generateCode($request, $response, $args) {

        $body = $request->getParsedBody();

        $hardwareKey = Sanitizer::sanitize($body['hardwareKey'] ?? '');
        $deviceInfo = json_encode($body['deviceInfo'] ?? []);
        $userCode = strtoupper(substr(md5(uniqid()), 0, 6)); // random 6 digit code
        $deviceCode = md5(uniqid()); // unique secret code

        $db = PDOConnect::get();
        $stmt = $db->prepare("INSERT INTO device_registration_codes (user_code, device_code, hardware_key, device_info, status, created_at)
                              VALUES (?, ?, ?, ?, 'pending', NOW())");
        $stmt->execute([$userCode, $deviceCode, $hardwareKey, $deviceInfo]);

        $data = [
            'user_code' => $userCode,
            'device_code' => $deviceCode
        ];

        return $response->withJson($data);
    }

    // Get Details API
    public function getDetails($request, $response, $args) {

        $userCode = Sanitizer::sanitize($args['user_code']);

        $db = PDOConnect::get();
        $stmt = $db->prepare("SELECT * FROM device_registration_codes WHERE user_code = ?");
        $stmt->execute([$userCode]);
        $record = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($record && $record['status'] === 'approved') {
            return $response->withJson([
                'cms_url' => 'http://your-local-cms-url',
                'server_key' => 'YOUR_SERVER_KEY'
            ]);
        } else {
            return $response->withJson(['status' => 'pending']);
        }
    }
}
