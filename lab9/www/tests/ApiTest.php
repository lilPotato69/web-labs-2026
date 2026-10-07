<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ApiTest extends TestCase
{
    // --- 1. HTTP-тест через MockHandler (без реального запроса) ---
    public function testMockRequestReturns200(): void
    {
        $mock = new MockHandler([
            new Response(200, [], 'OK'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $response = $client->get('http://example.com/test');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', (string)$response->getBody());
    }

    // --- 2. Реальный HTTP-запрос к сервису web (Nginx) ---
    public function testRealRequestToForm(): void
    {
        $client = new Client([
            'base_uri'    => 'http://web',
            'timeout'     => 5.0,
            'http_errors' => false,
        ]);

        $response = $client->get('/form.html');

        $this->assertEquals(200, $response->getStatusCode());
        $body = (string)$response->getBody();
        $this->assertStringContainsString('Запись на экскурсию', $body);
    }
}