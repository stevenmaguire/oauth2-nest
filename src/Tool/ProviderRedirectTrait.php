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
     * Returns the HTTP client instance.
     *
     * @return GuzzleHttp\ClientInterface
     */
    abstract public function getHttpClient();

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
        $response = null;
        $requestOptions = [
            'allow_redirects' => false
        ];
        $attempts = 0;

        $isRedirect = function (ResponseInterface $response) {
            $statusCode = $response->getStatusCode();

            return $statusCode > 300 && $statusCode < 400 && $response->hasHeader('Location');
        };

        try {
            while ($attempts < $this->redirectLimit) {
                $attempts++;
                $response = $this->getHttpClient()->send($request, $requestOptions);

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

        $this->redirectLimit = (integer) $limit;

        return $this;
    }
}
