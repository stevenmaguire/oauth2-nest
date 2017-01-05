<?php

namespace Stevenmaguire\OAuth2\Client\Tool;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Uri;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

trait ProviderRedirectTrait
{
    /**
     * Maximum number of times to follow provider initiated redirects
     *
     * @var integer
     */
    protected $redirectLimit = 2;

    /**
     * Retrieves current redirect limit.
     *
     * @return integer
     */
    public function getRedirectLimit()
    {
        return $this->redirectLimit;
    }

    /**
     * Sends a request instance and returns a response instance.
     *
     * @param  RequestInterface $request
     * @return ResponseInterface
     */
    protected function sendRequest(RequestInterface $request)
    {
        $attempts = 0;

        $requestOptions = [
            'allow_redirects' => false
        ];

        $isRedirect = function (ResponseInterface $response) {
            $statusCode = $response->getStatusCode();

            return $statusCode > 300 && $statusCode < 400 && $response->hasHeader('Location');
        };

        try {
            while ($attempts < $this->redirectLimit) {
                $attempts++;
                $response = $this->getHttpClient()->send($request, $requestOptions);
                $statusCode = $response->getStatusCode();

                if ($isRedirect($response)) {
                    $redirectUrl = new Uri($response->getHeader('Location')[0]);
                    $request = $request->withUri($redirectUrl);
                } else {
                    break;
                }
            }
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    /**
     * Updates the redirect limit.
     *
     * @param integer $limit
     * @return League\OAuth2\Client\Provider\AbstractProvider
     * @throws InvalidArgumentException
     */
    public function setRedirectLimit($limit)
    {
        if (!is_numeric($limit)) {
            throw new InvalidArgumentException('setRedirectLimit function only accepts numeric values.');
        }

        $this->redirectLimit = $limit;

        return $this;
    }
}
