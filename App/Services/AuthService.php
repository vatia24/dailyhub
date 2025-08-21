<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Helpers\JwtHelper;
use App\Helpers\ResponseHelper;
use App\Models\AuthModel;
use Exception;
use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\Google;
use Twilio\Rest\Client;

class AuthService
{
    private AuthModel $authModel;

    private mixed $authConfig;
    private mixed $facebookConfig;
    private mixed $googleConfig;

    public function __construct(AuthModel $authModel, $authConfig)
    {
        $this->authModel = $authModel;
        $this->authConfig = $authConfig;
        $this->facebookConfig = $authConfig['facebook'];
        $this->googleConfig = $authConfig['google'];
    }

    /**
     * @throws Exception
     */
    public function handleFacebookAuth($accessToken): array
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

    public function handleGoogleAuth($accessToken): array
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

        } catch (Exception $e) {
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
            } catch (Exception $e) {
                throw new ApiException(500, 'ACTIVATION_FAILED', 'Failed to activate user account: ' . $e->getMessage());
            }
        }
        return false;
    }

    /**
     * @throws ApiException
     */
    public function authorize($data): array
    {
        $user = $this->authModel->findUserByMailOrNumber($data['identifier']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            throw new ApiException(401,'INVALID_CREDENTIALS', 'Invalid credentials');
        } elseif ($user['status'] == 'unverified') {
            //send otp code to activate user
        }

        // Generate JWT Token
        $token = JwtHelper::generateToken(['id' => $user['id'], 'identifier' => $data['identifier'], 'role' => $user['user_type']]);

        $this->authModel->storeAccessToken($user['id'], $token, date('Y-m-d H:i:s',
            time() + $this->authConfig['jwt_expiration']));

        return ['token' => $token];
    }

    /**
     * @throws ApiException
     */
    public function authorizeRequest(): \stdClass
    {
        $headers = getallheaders(); // Get request headers
        $authHeader = $headers['Authorization'] ?? null;

        if (!$authHeader) {
            throw new ApiException(401, 'UNAUTHORIZED', 'Authorization header not found');
        }

        $token = str_replace('Bearer ', '', $authHeader); // Extract the token
        $decodedToken = JwtHelper::validateToken($token);

        if (!$decodedToken) {
            throw new ApiException(401, 'INVALID_TOKEN', 'Invalid or expired token');
        }

        return $decodedToken; // Return the token if valid
    }

    /**
     * @throws ApiException
     */
    public function facebookAuth(): void
    {
        $accessToken = $_GET['code']; // Get 'code' from Facebook OAuth redirect
        try {
            $result = $this->handleFacebookAuth($accessToken);
            ResponseHelper::response(200, 'SUCCESS', $result);
        } catch (\Exception $e) {
            throw new ApiException(401, 'CANNOT_AUTHORIZE', $e->getMessage());
        }
    }

    /**
     * @throws ApiException
     */
    public function googleAuth(): void
    {
        $accessToken = $_GET['code']; // Get 'code' from Google OAuth redirect
        try {
            $result = $this->handleGoogleAuth($accessToken);
            ResponseHelper::response(200, 'SUCCESS', $result);
        } catch (\Exception $e) {
            throw new ApiException(401, 'CANNOT_AUTHORIZE', $e->getMessage());
        }
    }
}