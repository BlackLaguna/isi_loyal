<?php

declare(strict_types=1);

namespace Tests\Integration\Invitation\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Utils\TestDB;
use Doctrine\DBAL\Connection;

class InvitationControllerTest extends WebTestCase
{
    protected KernelBrowser $httpClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = self::createClient();
        $this->httpClient->getCookieJar()->clear();

        TestDB::$connection = $this->httpClient->getContainer()->get(Connection::class);
    }

    public function testItSuccessfullyRegister()
    {
        // GIVEN
        TestDB::assertRecordMissing('partners', ['email' => 'test@mail.com']);

        // WHEN
        $this->httpClient->jsonRequest(
            method: 'POST',
            uri: '/api/register',
            parameters: [
                'email' => 'test@mail.com',
                'password' => 'test@mail.com',
                'repeatedPassword' => 'test@mail.com',
            ],
        );

        // THEN
        TestDB::assertRecordExists('partners', ['email' => 'test@mail.com']);
        self::assertEquals(
            474,
            strlen(json_decode($this->httpClient->getResponse()->getContent(), associative: true)['token'])
        );
    }
}