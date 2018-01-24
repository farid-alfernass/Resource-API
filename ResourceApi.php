<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class ResourceApi
{
    protected $accessToken = [];
    protected static $instance = null;
    protected $headers = [];    
 
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }    

    public function getHeaders()
    {
        return $this->headers;
    }    

    public function setParams($params)
    {
        $this->params = $params;
    }    

    public function getParams()
    {
        return $this->params;
    }    

    /*
    * Requests can be created using various methods of a client.
    * You can create and send requests using one of the following methods:
    *
    * GET: Sends a GET request
    * HEAD: Sends a HEAD request
    * POST: Sends a POST request
    * PUT: Sends a PUT request
    * DELETE: Sends a DELETE request
    * OPTIONS: Sends an OPTIONS request
    *
    */
    public function createRequest($method, $api)
    {
        $client = new Client();        
        try {
            $request = $client->createRequest(
                strtoupper($method),
                $api,
                [
                    'auth'    => [ 'pecel', 'lele' ],
                    $this->getParams()
                ]
            );            
            $response = $client->send($request);            
            return $response->json();
        } 
        catch (ClientException $e) {
        // In the event of a networking error (connection timeout, DNS errors, etc.)
            $response = $e->getResponse();
            $body = $response->json();            
            $body['header'] = [
                'code' => $response->getStatusCode(),
                'message' => $response->getReasonPhrase(),
                'description' => $response->json()
            ];            
            return $body;
        } 
        catch (ClientException $e) {
        // Thrown for 400 level errors
            $response = $e->getResponse();
            $body = $response->json();            
            $body['header'] = [
                'code' => $response->getStatusCode(),
                'message' => $response->getReasonPhrase(),
                'description' => $response->json()
            ];            
            return $body;
        } 
        catch (ServerException $e) {
        // Thrown for 500 level errors
            $response = $e->getResponse();
            $body['header'] = [
                'code' => $response->getStatusCode(),
                'message' => $response->getReasonPhrase(),
                'description' => $response->getBody()
            ];            
            return $body;
        }   
    }
}
