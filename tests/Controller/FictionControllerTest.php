<?php

namespace App\tests\Controller;

use App\Entity\Concept\Fiction;
use App\Tests\ApiTestCase;

class FictionControllerTest extends ApiTestCase
{
    public function testPostFiction()
    {
        $data = array(
            'titre' => 'Ajout de titre de texte',
            'description' => 'Une description de fiction comme exemple'
        );

        $response = $this->client->post(ApiTestCase::TEST_PREFIX.'/fictions', [
            'body' => json_encode($data)
        ]);


        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));

        $fictionUrl = $response->getHeader('Location');
        $response = $this->client->get($fictionUrl[0]);

        $payload = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('titre', $payload, "Il n'y a pas de champ titre");

        echo $response->getBody();
        echo "\n\n";

    }

    public function testPostFictionWithTextes()
    {
        $data = array(
            'titre' => 'Nouvel exemple de titre de fiction',
            'description' => 'Une description de fiction comme exemple'
        );

        $data['textes'][0]['titre'] = 'Test 1';
        $data['textes'][0]['description'] = 'Description 1';
        $data['textes'][0]['type'] = 'promesse';

        $response = $this->client->post(ApiTestCase::TEST_PREFIX.'/fictions', [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertTrue($response->hasHeader('Location'));
        $fictionUrl = $response->getHeader('Location');

        $response = $this->client->get($fictionUrl[0]);

        $payload = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('titre', $payload, "Il n'y a pas de champ titre");

        echo $response->getBody();
        echo "\n\n";
    }

    public function testGetFiction()
    {
        $fiction = $this->createFiction();

        $response = $this->client->get(ApiTestCase::TEST_PREFIX.'/fictions/'.$fiction->getId());

        $payload = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('titre', $payload, "Il n'y a pas de champ titre");
        $this->assertEquals($fiction->getId(), $payload['id']);
        $this->assertEquals(200, $response->getStatusCode());

        echo $response->getBody();
        echo "\n\n";
    }

    public function testPutFiction()
    {
        $fiction = $this->createFiction();

        $data = array(
            'titre' => 'Titre de la fiction modifié',
            "description" => "Description test"
        );

        $response = $this->client->put(ApiTestCase::TEST_PREFIX.'/fictions/'.$fiction->getId(), [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->client->get(ApiTestCase::TEST_PREFIX.'/fictions/'.$fiction->getId());

        $payload = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('titre', $payload, "Il n'y a pas de champ surnom");
        $this->assertEquals( $data['titre'], $payload['titre']);
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testDeleteFiction()
    {
        $fiction = $this->createFiction();

        $response = $this->client->delete(ApiTestCase::TEST_PREFIX.'/fictions/'.$fiction->getId());
        $this->assertEquals(202, $response->getStatusCode());
    }
}
