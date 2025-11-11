<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="RREC Member API",
 *     description="API for RREC member area",
 *     @OA\Contact(
 *         email="support@rrec.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class SwaggerConfig
{
    // This class is used only for Swagger annotations
} 