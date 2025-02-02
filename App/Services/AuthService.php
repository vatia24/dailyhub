<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Helpers\JwtHelper;
use App\Models\AuthModel;
use Exception;
use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\Google;
use Twilio\Rest\Client;

class AuthService
{
    private AuthModel $authModel;
    private mixed $facebookConfig;
    private mixed $googleConfig;

    public function __construct()
    {
        $this->authModel = new AuthModel();
//        $config = require_once __DIR__ . '/../../Config/Db.php';
//        $this->facebookConfig = $config['facebook'];
//        $this->googleConfig = $config['google'];
    }

    /**
     * @throws Exception
     */
    public function handleFacebookAuth($accessToken)
    {
        $provider = new Facebook([
            'clientId' => $this->facebookConfig['client_id'],
            'clientSecret' => $this->facebookConfig['client_secret'],
            'redirectUri' => $this->facebookConfig['redirect_uri'],
        ]);

        try {
            // Get the Facebook user details
            $facebookUser = $provider->getResourceOwner($provider->getAccessToken('authorization_code', [
                'code' => $accessToken,
            ]));

            // Extract relevant user details
            $userData = [
                'id' => $facebookUser->getId(),
                'email' => $facebookUser->getEmail(),
                'name' => $facebookUser->getName(),
            ];

            // Generate JWT token
            return [
                'token' => JwtHelper::generateToken($userData),
                'user' => $userData,
            ];
        } catch (Exception $e) {
            throw new Exception('Facebook login failed: ' . $e->getMessage());
        }
    }

    public function handleGoogleAuth($accessToken)
    {
        $provider = new Google([
            'clientId' => $this->googleConfig['client_id'],
            'clientSecret' => $this->googleConfig['client_secret'],
            'redirectUri' => $this->googleConfig['redirect_uri'],
        ]);

        try {
            // Get the Google user details
            $googleUser = $provider->getResourceOwner($provider->getAccessToken('authorization_code', [
                'code' => $accessToken,
            ]));

            // Extract relevant user details
            $userData = [
                'id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
            ];

            // Generate JWT token
            return [
                'token' => JwtHelper::generateToken($userData),
                'user' => $userData,
            ];
        } catch (Exception $e) {
            throw new Exception('Google login failed: ' . $e->getMessage());
        }
    }

    /**
     * @throws ApiException
     */
    public function sendOtp(string $mobile): void
    {
        if (empty($mobile)) {
            throw new ApiException(400, 'BAD_REQUEST', 'Mobile number is required to send OTP');
        }

        $sid    = $_ENV['TWILIO_SID'];
        $token  = $_ENV['TWILIO_AUTH_TOKEN'];
        $ver_sid  = 'VA2f94da819aa97e3af62374593d5334c6';

        try {

            $twilio = new Client($sid, $token);

            // Generate a random 6-digit OTP
            $otp = random_int(100000, 999999);
            // Store OTP in database (pseudo implementation)
            $this->authModel->storeOtp($mobile, $otp);

            $twilio->verify->v2->services("$ver_sid")
                ->verifications
                ->create($mobile, "sms", ["customCode" => $otp]);

        } catch (\Exception $e) {
            throw new ApiException(500, 'OTP_SEND_FAILED', 'Failed to send OTP: ' . $e->getMessage());
        }
    }

    /**
     * @throws ApiException
     */
    public function checkOtp(string $mobile, int $otp): bool
    {
        if (empty($mobile) || empty($otp)) {
            throw new ApiException(400, 'BAD_REQUEST', 'Mobile number and OTP are required for verification');
        }

        // Verify OTP from database (pseudo implementation)
        $isValid = $this->authModel->verifyOtp($mobile, $otp);

        if (!$isValid) {
            throw new ApiException(400, 'INVALID_OTP', 'The provided OTP is invalid or has expired');
        }

        return true;
    }

    /**
     * Verify OTP and activate user account
     *
     * @throws ApiException
     */
    public function verifyAndActivateUser(array $data): bool
    {
        // Verify the OTP
        if ($this->checkOtp($data['mobile'], $data['otp'])) {
            try {
                // Activate the user status
                $this->authModel->activateUser($data['mobile']);
                return true;
            } catch (\Exception $e) {
                throw new ApiException(500, 'ACTIVATION_FAILED', 'Failed to activate user account: ' . $e->getMessage());
            }
        }
        return false;
    }

    /**
     * @throws \Exception
     */
    public function login($identifier, $password)
    {
        $user = $this->authModel->findUserByMailOrNumber($identifier);

        if (!$user || !password_verify($password, $user['password'])) {
            throw new \Exception('Invalid credentials', 401);
        }

        // Generate JWT Token
        $token = JwtHelper::generateToken(['id' => $user['id'], 'username' => $user['username']]);

        return ['token' => $token];
    }
}