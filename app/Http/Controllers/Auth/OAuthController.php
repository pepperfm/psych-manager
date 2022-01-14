<?php

namespace App\Http\Controllers\Auth;

use App\Http\Responders\APIBaseResponder;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Laravel\Passport\Http\Controllers\AccessTokenController as ATC;

use Exception;

use App\Services\App\Client;

use App\Models\User;

class OAuthController extends ATC
{
    /**
     * Create a new controller instance.
     *
     * @param AuthorizationServer $server
     * @param TokenRepository $tokens
     * @param JwtParser $jwt
     * @param APIBaseResponder $json
     * @param Client $client
     */
    #[Pure] public function __construct(
        AuthorizationServer $server,
        TokenRepository $tokens,
        JwtParser $jwt,
        private APIBaseResponder $json,
        private Client $client
    ) {
        parent::__construct($server, $tokens, $jwt);
    }

    /**
     * Authorize a client to access the user's account.
     *
     * @OA\Post(
     *     path="/login-side",
     *     operationId="oauth2.login.email",
     *     tags={"oAuth"},
     *     summary="Authentication by email",
     *     description="

    ### Example URI
     **POST** https://your-website.com/api/v1/login-side",

     *     @OA\Parameter(name="X-Localization", in="header", required=true, @OA\Schema(type="string"), description="Lang code", example="de"),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"username", "password", "grant_type", "client_id", "client_secret"},
     *                  type="object",
     *                  @OA\Property(property="username", type="string", description="Client email.", example="some_user@flywert.com"),
     *                  @OA\Property(property="password", type="string", description="Client password.", example="some_user@flywert.com"),
     *                  @OA\Property(property="grant_type", type="string", description="oAuth grant type", example="password"),
     *                  @OA\Property(property="client_id", type="integer", description="oAuth client id", example="1"),
     *                  @OA\Property(property="client_secret", type="string", description="oAuth client secret", example="CyQaejvE9Tq2ykXW1aCz4aYpxU8OEpJngkVWjpHj"),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="**OK** Successful auth login",
     *          @OA\JsonContent(
     *              @OA\Property(property="token_type", type="string"),
     *              @OA\Property(property="expires_in", type="string", format="date-time"),
     *              @OA\Property(property="access_token", type="string"),
     *              @OA\Property(property="refresh_token", type="string"),
     *          )
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="**Unauthorized** Invalid credentials.",
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="**Unprocessable Entity** Required fields are missing or cannot be processed.",
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="**Server Errors** Could not create token.",
     *     ),
     * )
     *
     * @OA\Post(
     *     path="/login",
     *     operationId="oauth2.login",
     *     tags={"oAuth"},
     *     summary="Authentication by email",
     *     description="

    ### Example URI
     **POST** https://your-website.com/api/v1/login",

     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"phone", "password", "grant_type", "client_id", "client_secret"},
     *                  type="object",
     *                  @OA\Property(property="email", type="string", description="Client email.", example="mail@qwerty213.ru39"),
     *                  @OA\Property(property="password", type="string", description="Client password.", example="some_user@flywert.com"),
     *                  @OA\Property(property="grant_type", type="string", description="oAuth grant type", example="password"),
     *                  @OA\Property(property="client_id", type="integer", description="oAuth client id", example="1"),
     *                  @OA\Property(property="client_secret", type="string", description="oAuth client secret", example="CyQaejvE9Tq2ykXW1aCz4aYpxU8OEpJngkVWjpHj"),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="**OK** Successful auth login",
     *          @OA\JsonContent(
     *              @OA\Property(property="token_type", type="string"),
     *              @OA\Property(property="expires_in", type="string", format="date-time"),
     *              @OA\Property(property="access_token", type="string"),
     *              @OA\Property(property="refresh_token", type="string"),
     *          )
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="**Unauthorized** Invalid credentials.",
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="**Unprocessable Entity** Required fields are missing or cannot be processed.",
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="**Server Errors** Could not create token.",
     *     ),
     * )
     *
     * @param ServerRequestInterface $request
     * @return JsonResponse|Response
     */
    public function issueToken(ServerRequestInterface $request): JsonResponse|Response
    {
        try {
            $request = $this->client->init($request);

            # get grant type
            $grantType = $request->getParsedBody()['grant_type'];

            if ($grantType === 'password') {
                # get username (default is :email)
                $email = $request->getParsedBody()['username'];

                /** @var User|null $user */
                if (! User::query()->where('email', $email)->first()) {
                    return $this->json->OAuthError(
                        'The user credentials were incorrect.',
                        'invalid_credentials',
                        Response::HTTP_UNAUTHORIZED
                    );
                }
            }

            # generate token
            $tokenResponse = parent::issueToken($request);
            # convert response to json string
            $content = $tokenResponse->getContent();
            # convert json to array
            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            if (isset($data["error"])) {
                return $this->json->OAuthError(
                    'The user credentials were incorrect.',
                    'invalid_credentials',
                    Response::HTTP_UNAUTHORIZED
                );
            }

            return response()->json($data, Response::HTTP_OK);

        } catch (Exception $exception) {
            return $this->json->OAuthError(
                $exception->getMessage(),
                OAuthServerException::invalidGrant()->getErrorType(),
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }
    }

    /**
     * Logout user and invalidate token.
     *
     * @OA\Get(
     *     path="/logout-side",
     *     security={{ "passport": {"*"} }},
     *     operationId="oauth2 logout",
     *     tags={"oAuth"},
     *     summary="Logout and Token Invalidation",
     *     description="

    Sending an request to logout endpoint with a valid API token will also invalidate that token.
    ### Example URI
     **GET** https://your-website.com/api/v1/logout-side",

     *     @OA\Response(
     *          response="200",
     *          description="**OK** Successful register",
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="**Unauthorized** Invalid credentials.",
     *     ),
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()?->revoke();

        return $this->json->response([], __('You are successfully logged out'));
    }
}
