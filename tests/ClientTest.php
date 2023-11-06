<?php

use LNDHub\Client;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    private function client(): Client
    {
        $url = '';
        $login = '';
        $password = '';
        return new Client($url, $login, $password);
    }
    public function testCanBeInitialized(): void
    {
        $this->assertInstanceOf(
            Client::class,
            $this->client(),
        );
    }

    public function testCanGetBalance(): void
    {
        $client = $this->client();
        $this->assertIsNumeric($client->getBalance()['balance']);
    }

    public function testCanGetInfo(): void
    {
        $client = $this->client();
        $this->assertIsString($client->getInfo()['alias']);
    }

    public function testCanAddInvoice(): void
    {
        $client = $this->client();
        $response = $client->addInvoice([
            'value' => 23,
            'memo' => 'test invoice'
        ]);
        // print_r($response);
        $this->assertIsString($response['payment_request']);
        $this->assertIsString($response['r_hash']);
    }

    public function testCanGetInvoice(): void
    {
        $client = $this->client();
        $response = $client->addInvoice([
            'value' => 23,
            'memo' => 'test invoice'
        ]);
        $invoice = $client->getInvoice($response['r_hash']);

        $this->assertArrayHasKey('settled', $invoice);
    }

    public function testCanCreateWallet(): void
    {
        $data = Client::createWallet("https://wallets.getalby.com", "bluewallet");
        $this->assertArrayHasKey('login', $data);
        $this->assertArrayHasKey('password', $data);
        $this->assertArrayHasKey('url', $data);
    }
}
