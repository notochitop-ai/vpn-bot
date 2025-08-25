<?php
class XUIApi {
    private $baseUrl;
    private $username;
    private $password;
    private $cookie;
    
    public function __construct($host, $port, $username, $password) {
        $this->baseUrl = "http://$host:$port";
        $this->username = $username;
        $this->password = $password;
        $this->cookie = tempnam(sys_get_temp_dir(), 'xui_cookie_');
    }
    
    public function testConnection() {
        return $this->login();
    }
    
    public function login() {
        $url = $this->baseUrl . "/login";
        $data = json_encode([
            'username' => $this->username,
            'password' => $this->password
        ]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200) {
            $result = json_decode($response, true);
            return isset($result['success']) && $result['success'];
        }
        return false;
    }
}
?>
