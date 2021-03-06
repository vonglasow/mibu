<?php

namespace App\tests\Controller;

use App\Entity\Concept\Fiction;
use App\Tests\ApiTestCase;

class FictionControllerTest extends ApiTestCase
{
    public function testPostFiction()
    {
        $data = array(
            "titre" => "Ajout de titre de texte",
            "description" => "Une description de fiction comme exemple"
        );

        $response = $this->client->post(ApiTestCase::TEST_PREFIX.'/fictions', [
            'headers' => $this->getAuthorizedHeaders('gaetan'),
            'body' => json_encode($data)
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));

        $fictionUrl = $response->getHeader('Location');

        $response = $this->client->get($fictionUrl[0], [
            'headers' => $this->getAuthorizedHeaders('gaetan'),
        ]);

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

        $token = $this->getService('lexik_jwt_authentication.encoder')
            ->encode(['pseudo' => 'gaetan']);

        $response = $this->client->post(ApiTestCase::TEST_PREFIX.'/fictions', [
            'headers' => $this->getAuthorizedHeaders('gaetan'),
            'body' => json_encode($data)
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertTrue($response->hasHeader('Location'));
        $fictionUrl = $response->getHeader('Location');

        $response = $this->client->get($fictionUrl[0], [
            'headers' => $this->getAuthorizedHeaders('gaetan'),
            'body' => json_encode($data)
        ]);

        $payload = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('titre', $payload, "Il n'y a pas de champ titre");

        echo $response->getBody();
        echo "\n\n";
    }

    public function testGetFictions()
    {
       for ($i = 0; $i<25; $i++) {
           $this->createFiction('Fiction '.$i);
       }

        $token = $this->getService('lexik_jwt_authentication.encoder')
            ->encode(['pseudo' => 'gaetan']);

        $response = $this->client->get(ApiTestCase::TEST_PREFIX.'/fictions/', [
            'headers' => $this->getAuthorizedHeaders('gaetan'),
        ]);

        //page 1
        $this->assertEquals(200, $response->getStatusCode());
        $payload = json_decode($response->getBody(true), true);

        $this->assertEquals('Fiction 5', $payload['items'][5]['titre']);
        $this->assertEquals(10, $payload['count'], "Il n'y a pas le bon compte de fictions");
        $this->assertEquals(25, $payload['total'], "Il n'y a pas le bon total de fictions");
        $this->assertArrayHasKey('links', $payload, "Il n'y a pas de champ links");
        $this->assertArrayHasKey('next', $payload['links'], "Il n'y a pas de champ links.next");

        //page 2
        $nextLink = $payload['links']['next'];
        $response = $this->client->get($nextLink, [
            'headers' => $this->getAuthorizedHeaders('gaetan'),
        ]);

        $payloadNext = json_decode($response->getBody(true), true);

        $this->assertEquals('Fiction 15', $payloadNext['items'][5]['titre']);
        $this->assertEquals(10, count($payloadNext['items']), "Il n'y a pas le bon compte de fictions");
        $this->assertArrayHasKey('links', $payloadNext, "Il n'y a pas de champ links");
        $this->assertArrayHasKey('next', $payloadNext['links'], "Il n'y a pas de champ links.next");

        //last
        $lastLink = $payload['links']['last'];
        $response = $this->client->get($lastLink, [
            'headers' => $this->getAuthorizedHeaders('gaetan'),
        ]);
        $payloadLast = json_decode($response->getBody(true), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Fiction 24', $payloadLast['items'][4]['titre']);

    }

    public function testGetFiction()
    {
        $fiction = $this->createFiction();

        $token = $this->getService('lexik_jwt_authentication.encoder')
            ->encode(['pseudo' => 'gaetan']);

        $response = $this->client->get(ApiTestCase::TEST_PREFIX.'/fictions/'.$fiction->getId(), [
            'headers' => $this->getAuthorizedHeaders('gaetan')
        ]);

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
            'headers' => $this->getAuthorizedHeaders('gaetan'),
            'body' => json_encode($data),
        ]);

        $this->assertEquals(202, $response->getStatusCode());

        $response = $this->client->get(ApiTestCase::TEST_PREFIX.'/fictions/'.$fiction->getId(), [
            'headers' => $this->getAuthorizedHeaders('gaetan'),
            ]);

        $payload = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('titre', $payload, "Il n'y a pas de champ surnom");
        $this->assertEquals( $data['titre'], $payload['titre']);
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testDeleteFiction()
    {
        $fiction = $this->createFiction();

        $response = $this->client->delete(ApiTestCase::TEST_PREFIX.'/fictions/'.$fiction->getId(), ['headers' => $this->getAuthorizedHeaders('gaetan'),]);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
