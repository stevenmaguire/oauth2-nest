<?php namespace Stevenmaguire\OAuth2\Client\Test\Tool;

use GuzzleHttp\Exception\BadResponseException;
use Mockery as m;

class ProviderRedirectTraitTest extends \PHPUnit_Framework_TestCase
{
    protected $provider;

    protected function setUp()
    {
        $this->provider = new \Stevenmaguire\OAuth2\Client\Provider\Nest([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
    }

    public function testSetRedirectLimit()
    {
        $redirectLimit = rand(3,5);
        $this->provider->setRedirectLimit($redirectLimit);
        $this->assertEquals($redirectLimit, $this->provider->getRedirectLimit());
    }

    /**
     * @expectedException InvalidArgumentException
     **/
    public function testSetRedirectLimitThrowsExceptionWhenNonNumericProvided()
    {
        $redirectLimit = 'florp';
        $this->provider->setRedirectLimit($redirectLimit);
    }

    public function testClientLimitsRedirectResponse()
    {
        $redirectLimit = rand(3,5);
        $status = rand(301,399);
        $redirectUrl = uniqid();
        $request = m::mock('Psr\Http\Message\RequestInterface');
        $request->shouldReceive('withUri')->andReturn($request);
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('hasHeader')->with('Location')->andReturn(true);
        $response->shouldReceive('getHeader')->with('Location')->andReturn($redirectUrl);
        $response->shouldReceive('getStatusCode')->andReturn($status);
        $response->shouldReceive('getBody')->andReturn('{"foo": "bar"}');
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times($redirectLimit)
            ->andReturn($response);
        $this->provider->setHttpClient($client)->setRedirectLimit($redirectLimit);
        $finalResponse = $this->provider->getResponse($request);
    }

    public function testClientErrorReturnsResponse()
    {
        $status = rand(301,399);
        $result = ['foo' => 'bar'];
        $request = m::mock('Psr\Http\Message\RequestInterface');
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn($status);
        $response->shouldReceive('getBody')->andReturn(json_encode($result));
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);

        $exception = new BadResponseException(
            'test exception',
            $request,
            $response
        );

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->andThrow($exception);
        $this->provider->setHttpClient($client);
        $finalResponse = $this->provider->getResponse($request);
        $this->assertEquals($result, $finalResponse);
    }
}
