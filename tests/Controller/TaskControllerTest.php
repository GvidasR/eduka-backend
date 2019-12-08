<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TaskControllerTest extends WebTestCase
{

    public function test()
    {
        $testCase = json_decode('{"question":"'.uniqid().'", "answers": [{"correct":1,"answer":"'.uniqid().'"}]}');
        $client = static::createClient();

        // CREATE TASK
        $client->request('POST', '/api/task/', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($testCase));
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $responseContent = json_decode($client->getResponse()->getContent());
        $this->assertEquals($responseContent->question,$testCase->question);

        $testId = $responseContent->id;

        // GET TASK LIST
        $client->request('GET', '/api/task/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $responseContent = json_decode($client->getResponse()->getContent());
        $this->assertIsArray($responseContent);

        // GET SINGLE TASK
        $client->request('GET', '/api/task/'.$testId);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $responseContent = json_decode($client->getResponse()->getContent());
        $this->assertEquals($responseContent->id,$testId);

        // PUT SINGLE TASK
        $testCaseEdit = clone($testCase);
        $testCaseEdit->question .= "E";
        $client->request('PUT', '/api/task/'.$testId, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($testCaseEdit));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $responseContent = json_decode($client->getResponse()->getContent());
        $this->assertEquals($responseContent->question,$testCase->question."E");

        // DELETE SINGLE TASK
        $client->request('DELETE', '/api/task/'.$testId);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // CHECK ERRORS
        $client->request('GET', '/api/task/'.$testId);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        $client->request('DELETE', '/api/task/'.$testId);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        $client->request('PUT', '/api/task/'.$testId, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($testCase));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
