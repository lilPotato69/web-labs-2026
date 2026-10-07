<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient {
    private Client $client;

    public function __construct() {
        $this->client = new Client([
            'timeout' => 5.0,
            'connect_timeout' => 3.0
        ]);
    }

    /**
     * Выполняет GET-запрос к API.
     * Возвращает массив с данными или ['error' => 'сообщение'].
     */
    public function request(string $url): array {
        try {
            $response = $this->client->get($url);
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['error' => 'Некорректный JSON от API: ' . json_last_error_msg()];
            }

            if ($response->getStatusCode() !== 200) {
                return ['error' => 'API вернуло статус ' . $response->getStatusCode()];
            }

            return $data;
        } catch (GuzzleException $e) {
            return ['error' => 'Ошибка запроса к API: ' . $e->getMessage()];
        } catch (\Exception $e) {
            return ['error' => 'Неизвестная ошибка: ' . $e->getMessage()];
        }
    }
}