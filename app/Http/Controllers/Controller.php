<?php

namespace App\Http\Controllers;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Laravel audit",
 *         version="1.0.0"
 *     ),
 *     @OA\Components(
 *           @OA\Header(
 *             header="Accept",
 *             description="Accept header",
 *             @OA\Schema(
 *                 type="string",
 *                 enum={"application/json"},
 *                 default="application/json"
 *             )
 *           ),
 *          @OA\SecurityScheme(
 *              type="http",
 *              scheme="bearer",
 *              bearerFormat="JWT",
 *              securityScheme="bearerAuth"
 *          ),
 *   ),
 * )
 */

abstract class Controller
{
    //
}
