<?php

/**
* @OA\Info(
*     description="This is a sample Petstore server.  You can find
out more about Swagger at
[http://swagger.io](http://swagger.io) or on
[irc.freenode.net, #swagger](http://swagger.io/irc/).",
*     version="1.0.0",
*     title="Swagger Directory App",
*     termsOfService="http://swagger.io/terms/",
*     @OA\Contact(
*         email="apiteam@swagger.io"
*     ),
*     @OA\License(
*         name="Apache 2.0",
*         url="http://www.apache.org/licenses/LICENSE-2.0.html"
*     ),
* )
*/
/**
 *  @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="L5 Swagger OpenApi dynamic host server"
 *  )
 */
 /**
  * @OA\SecurityScheme(
  *     type="apiKey",
  *     description="To authenticate set header 'Authorization: Bearer <access_token>'",
  *     name="Authorization",
  *     in="header",
  *     securityScheme="Bearer",
  * )
  */
/**
* @OA\Post(
*     path="/api/auth/login",
*     tags={"auth-login"},
*     summary="Login user",
*     operationId="loginUser",
*     @OA\Response(
*         response=200,
*         description="successful operation",
*          @OA\JsonContent()
*     ),
*    @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
* @OA\RequestBody(
*  @OA\JsonContent(
*    type="object",
*    @OA\Property(property="email", type="string"),
*    @OA\Property(property="password", type="string")
*  ),
* )
* )
*/
/**
* @OA\Post(
*     path="/api/auth/account",
*     tags={"auth-account"},
*     summary="Create user",
*     operationId="createUser",
*     @OA\Response(
*         response=200,
*         description="successful operation",
*          @OA\JsonContent(type="object", @OA\Property(property="status", type="boolean"))
*     ),
*     @OA\RequestBody(
*     @OA\JsonContent(
*     type="object",
*     @OA\Property(property="name", type="string"),
*     @OA\Property(property="email", type="string"),
*     @OA\Property(property="password", type="string"),
*     @OA\Property(property="password_confirmation", type="string"),
*     @OA\Property(property="is_trader", type="integer"),
*  ),
* )
* )
*/
/**
* @OA\Post(
*     path="/api/auth/forgot/password",
*     tags={"auth-forgot-password"},
*     summary="Reset password via email",
*     operationId="forgotPassword",
*     @OA\Response(
*         response=201,
*         description="successful operation",
*          @OA\JsonContent(example={"msg":"Reset link sent to your email.","status":true},
*
*      )
*     ),
*    @OA\Response(response=401, description="Unable to send reset link",
*    @OA\JsonContent(
*      example={"msg":"Unable to send reset link.","status":false},
*     )
*    ),
* )
*/
/**
* @OA\Post(
*     path="/api/auth/refresh",
*     tags={"auth-refresh-token"},
*     summary="Refresh Token",
*     operationId="refreshToken",
*     @OA\Response(
*         response=200,
*         description="successful operation",
*          @OA\JsonContent(example={
*           "name": "User",
*           "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwO2JhY2CJleHAmXow",
*           "token_type": "bearer",
*           "expires_in": 3600
*         },
*        )
*     ),
*    @OA\Response(response=401, description="Token not valid, please login",
*    @OA\JsonContent(
*      example={"msg":"Token not valid, please login.","status":false},
*     )
*    ),
* )
*/
/**
* @OA\Get(
*     path="/brands",
*     tags={"get-brands"},
*     summary="List brands",
*     operationId="listBrands",
*     @OA\Parameter(
*         name="category",
*         in="query",
*         description="Category to filter by",
*         required=false,
*         @OA\Schema(
*             type="integer",
*             format="int64"
*         )
*     ),
*     @OA\Parameter(
*         name="q",
*         in="query",
*         description="Brand name to filter by",
*         required=false,
*         @OA\Schema(
*             type="string",
*         )
*     ),
*     @OA\Parameter(
*         name="top_brand",
*         in="query",
*         description="Restrict to top brands",
*         required=false,
*         @OA\Schema(
*             type="string",
*         )
*     ),
*     @OA\Response(
*         response=200,
*         description="successful operation",
*         @OA\JsonContent(
*             type="array",
*             @OA\Items(type="string",))
*         ),
*     @OA\Response(
*         response=400,
*         description="Invalid status value"
*     ),
* )
*/
/**
* @OA\Get(
*     path="/api/directoryoverview",
*     tags={"get-brands-home"},
*     summary="Brand overview",
*     operationId="listBrandsOverview",
*     @OA\Response(
*         response=200,
*         description="successful operation",
*         @OA\JsonContent(
*             type="array",
*             @OA\Items(type="string",))
*         ),
*     @OA\Response(
*         response=400,
*         description="Invalid status value"
*     ),
* )
*/
/**
* @OA\Get(
*     path="/store",
*     tags={"get-stores"},
*     summary="List stores",
*     operationId="listStores",
*     @OA\Parameter(
*         name="category",
*         in="query",
*         description="Tags to filter by",
*         required=false,
*         @OA\Schema(
*             type="integer",
*             format="int64"
*         )
*     ),
*     @OA\Response(
*         response=200,
*         description="successful operation",
*         @OA\JsonContent(
*             type="array",
*             @OA\Items(type="string",))
*         ),
*     @OA\Response(
*         response=400,
*         description="Invalid status value"
*     ),
* )
*/
/**
* @OA\Get(
*     path="/api/me/brands",
*     tags={"api-me-brands"},
*     summary="List my brands",
*     operationId="meBrands",
*     @OA\Response(
*         response=200,
*         description="successful operation",
*         @OA\JsonContent(
*             type="array",
*             @OA\Items(type="string",))
*         ),
*     @OA\Response(
*         response=401,
*         description="Not Authorized"
*     ),
*     security={
*         {"Bearer": {}}
*     }
* ),
* )
*/
/**
* @OA\Get(
*     path="/api/me/storeanalytics/{storeid}",
*     tags={"api-me-storeanalytics"},
*     summary="Store overview",
*     operationId="meStoreAnalytics",
*    @OA\Parameter(
*          name="storeid",
*          description="Store hash id, retrieved from store {hid: field}",
*          required=true,
*          in="path",
*          @OA\Schema(
*              type="string"
*          )
*      ),
*     @OA\Response(
*         response=200,
*         description="successful operation",
*         @OA\JsonContent(
*             type="array",
*             @OA\Items(type="string",))
*         ),
*     @OA\Response(
*         response=401,
*         description="Not Authorized"
*     ),
*     security={
*         {"Bearer": {}}
*     }
* ),
* )
*/
?>
