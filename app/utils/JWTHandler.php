<?php

require_once 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\SignatureInvalidException;

class JWTHandler
{
    private $secret_key;

    public function __construct()
    {
        $this->secret_key = "ImTheBestMusic"; // Thay thế bằng khóa bí mật của bạn
    }

    /**
     * Create a JWT token
     *
     * @param array $data Data to encode in the token
     * @param int $expireTime Token expiration time in seconds (default: 1 hour)
     * @return string The JWT token
     */
    public function encode($data, $expireTime = 3600)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + $expireTime;

        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data
        );

        return JWT::encode($payload, $this->secret_key, 'HS256');
    }

    /**
     * Decode a JWT token
     *
     * @param string $jwt The JWT token to decode
     * @return array|null The decoded data or null if invalid
     */
    public function decode($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret_key, 'HS256'));
            return (array) $decoded->data;
        } catch (ExpiredException $e) {
            // Token has expired
            return ['error' => 'token_expired', 'message' => 'Token has expired'];
        } catch (SignatureInvalidException $e) {
            // Invalid signature
            return ['error' => 'invalid_signature', 'message' => 'Token signature is invalid'];
        } catch (Exception $e) {
            // Other errors
            return ['error' => 'invalid_token', 'message' => $e->getMessage()];
        }
    }
}
?>