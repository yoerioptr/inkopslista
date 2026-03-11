<?php

namespace App\Tests\Feature\Basket;

use App\Enum\Unit;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class BasketCreationTest extends WebTestCase
{
    private readonly KernelBrowser $client;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        /** @var DatabaseToolCollection $databaseToolCollection */
        $databaseToolCollection = static::getContainer()->get(DatabaseToolCollection::class);
        $databaseToolCollection->get()->loadAllFixtures();
    }

    public function test_redirects_unauthenticated_users_to_login(): void
    {
        $this->client->request('GET', '/baskets/new');

        $this->assertResponseRedirects('/login');
    }

    public function test_displays_the_basket_form(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'user1@example.com']);

        $this->client->loginUser($testUser);
        $this->client->request('GET', '/baskets/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name="basket"]');
        $this->assertSelectorExists('input[name="basket[name]"]');
        $this->assertSelectorExists('button[data-action="basket-items-collection#add"]');
        $this->assertSelectorExists('button[name="basket[save]"]');
    }

    public function test_can_create_a_basket(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'user1@example.com']);

        $this->client->loginUser($testUser);
        $this->client->request('GET', '/baskets/new');

        $crawler = $this->client->request('GET', '/baskets/new');
        $token = $crawler->filter('input[name="basket[_token]"]')->attr('value');

        $this->client->request('POST', '/baskets/new', [
            'basket' => [
                'name' => 'My Weekly Groceries',
                'items' => [
                    ['product' => 'Apples', 'amount' => '5', 'unit' => Unit::Piece->value],
                    ['product' => 'Milk', 'amount' => '1', 'unit' => Unit::Litre->value],
                ],
                '_token' => $token,
                'save' => '',
            ],
        ]);

        $this->assertResponseRedirects('/baskets');

        $this->client->followRedirect();

        $this->assertSelectorTextContains('body', 'My Weekly Groceries');
    }
}
