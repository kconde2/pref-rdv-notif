<?php

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;

class AppointmentService
{
    public $client;

    public $url;

    public function __construct()
    {
        $this->client = \Symfony\Component\Panther\Client::createChromeClient(null, ['--headless', '--disable-dev-shm-usage', '--no-sandbox'], [], null);
    }

    public function gotoPage(string $url)
    {
        $this->url = $url;
        $this->client->request('GET', $url);
    }

    public function checkAvailability()
    {
        // Wait for the form to appear, it may take some time because it's done in JS
        $this->client->waitFor('#FormBookingCreate');

        $crawler = $this->client->getCrawler();

        $crawler->selectLink('Accepter')->click();

        $form = $crawler->filter('#FormBookingCreate')->form();

        $form['condition']->tick();

        $this->client->takeScreenshot('screen1.png'); // Yeah, screenshot!

        $this->client->executeScript('document.querySelector("input[name=nextButton]").click()');

        $this->client->waitFor('#container');
        // refresh crawler so you can crawl newly loaded page
        // https://github.com/symfony/panther/issues/172
        $crawler = $this->client->refreshCrawler();

        // Ajouter pour la préfecture de ROUEN
        $form = $crawler->filter('#FormBookingCreate')->form();
        $form['planning']->setValue('25545');
        $this->client->takeScreenshot('screen2.png');
        $this->client->executeScript('document.querySelector("input[name=nextButton]").click()');
        $this->client->waitFor('#container');

        $crawler = $this->client->refreshCrawler();

        $t = $crawler->filter('.FormValidate')->text();

        $this->client->takeScreenshot('screen3.png'); // Yeah, screenshot!

        $availability = "Il n'existe plus de plage horaire libre pour votre demande de rendez-vous. Veuillez recommencer ultérieurement." !== $t;

        return $availability;
    }
}
