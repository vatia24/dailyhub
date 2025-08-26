<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="DailyHub API",
 *     version="1.0.0",
 *     description="API for authentication, users and products"
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Local server"
 * )
 */
class OpenApi
{
}

/**
 * @OA\Post(
 *     path="/api/authorize",
 *     summary="Authorize user and get JWT token",
 *     tags={"auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"identifier","password"},
 *             @OA\Property(property="identifier", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Authorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="statusCode", type="integer"),
 *             @OA\Property(property="type", type="string"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="token", type="string")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=401, description="Invalid credentials")
 * )
 */
class AuthPaths
{
}

/**
 * @OA\Post(
 *     path="/api/registerUser",
 *     summary="Register a new user and send OTP",
 *     tags={"auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username","name","email","mobile","password","type"},
 *             @OA\Property(property="username", type="string"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="mobile", type="string"),
 *             @OA\Property(property="password", type="string", format="password"),
 *             @OA\Property(property="type", type="string", example="customer")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Registered",
 *         @OA\JsonContent(
 *             @OA\Property(property="statusCode", type="integer"),
 *             @OA\Property(property="type", type="string"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="status", type="string"),
 *                 @OA\Property(property="user_id", type="integer")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=400, description="Validation error")
 * )
 */
class RegisterPaths
{
}

/**
 * @OA\Post(
 *     path="/api/verifyCustomer",
 *     summary="Verify OTP and activate user",
 *     tags={"auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"mobile","otp"},
 *             @OA\Property(property="mobile", type="string"),
 *             @OA\Property(property="otp", type="integer")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Verified"),
 *     @OA\Response(response=400, description="Invalid OTP")
 * )
 */
class VerifyPaths
{
}

/**
 * @OA\Get(
 *     path="/api/facebookAuth",
 *     summary="Facebook OAuth callback",
 *     tags={"auth"},
 *     @OA\Parameter(name="code", in="query", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=401, description="Cannot authorize")
 * )
 */
class FacebookAuthPaths
{
}

/**
 * @OA\Get(
 *     path="/api/googleAuth",
 *     summary="Google OAuth callback",
 *     tags={"auth"},
 *     @OA\Parameter(name="code", in="query", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=401, description="Cannot authorize")
 * )
 */
class GoogleAuthPaths
{
}

/**
 * @OA\Get(
 *     path="/api/getProducts",
 *     summary="Get products (requires Bearer token)",
 *     tags={"products"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(response=200, description="OK")
 * )
 */
class ProductPaths
{
}

/**
 * @OA\Components(
 *     securitySchemes={
 *         @OA\SecurityScheme(
 *             securityScheme="bearerAuth",
 *             type="http",
 *             scheme="bearer",
 *             bearerFormat="JWT"
 *         )
 *     }
 * )
 */
class Components
{
}


