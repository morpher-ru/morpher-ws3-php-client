<?php
namespace Morpher\Ws3Client;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class WebClient
{
    private string $_tokenBase64 = '';
    private \GuzzleHttp\Client $client;

    function __construct(string $url = 'https://ws3.morpher.ru',string $token = '',float $timeout = 10.0,$handler = null)
    {
        $this->_tokenBase64 = base64_encode($token);

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $url,
            'timeout' => $timeout,
            'handler' => $handler
        ]);    
    }

    public function getStandardHeaders():array
    {
        $headers = ['Accept' => 'application/json'];

        if (!empty($this->_tokenBase64))
        {
            $headers['Authorization'] = 'Basic '.$this->_tokenBase64;
        }
        
        return $headers;
    }

    /**
     * @throws IpBlocked
     * @throws InvalidServerResponse
     * @throws TokenNotFound
     * @throws MorpherError
     * @throws GuzzleException
     * @throws ServiceDenied
     */
    public function send(string $Endpoint, $QueryParameters = [], string $Method = 'GET', $Headers = null, $body = null, $form_params = null): string
    {
        if ($Headers === null)
        {
            $Headers = $this->getStandardHeaders();
        }
        try
        {
            $response = $this->client->request($Method, $Endpoint, [
                'query' => $QueryParameters,
                'headers' => $Headers,
                'http_errors' => true,
                'body' => $body,
                'form_params' => $form_params
            ]);

            $result = $response->getBody();
        }
        catch (ClientException $ex)
        {
            if ($ex->hasResponse())
            {
                $response = $ex->getResponse();
                $code = $response->getStatusCode();
                if ($code >= 400)
                {
                    $data = json_decode($response->getBody(),true);
                    if (empty($data['code']))
                        throw new InvalidServerResponse("В ответе сервера не найден параметр code.");
                    
                    $msg = (string)($data['message'] ?? "Неизвестная ошибка");
                    $morpher_code = (int)($data['code']);

                    if ($morpher_code == 6) throw new InvalidArgumentEmptyString($msg);
                    if ($morpher_code == 1) throw new RequestsDailyLimit($msg);
                    if ($morpher_code == 3) throw new IpBlocked($msg);
                    if ($morpher_code == 9) throw new TokenNotFound($msg);
                    if ($morpher_code == 10) throw new TokenIncorrectFormat($msg);
                    if ($morpher_code == 25) throw new TokenRequired($msg);

                    throw new MorpherError($msg, $morpher_code);
                }
            }

            throw new InvalidServerResponse("В ответе сервера нет тела.");
        }

        return $result;
    }

    public static function JsonDecode(string $text)
    {
        try
        {
            return json_decode($text,true,512,JSON_THROW_ON_ERROR);
        }
        catch (\JsonException $ex)
        {
            throw new \Morpher\Ws3Client\InvalidServerResponse("Некорректный JSON ответ от сервера",$text);
        }
    }
}
