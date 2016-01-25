<?php namespace Kevupton\MerchantWarrior\Tests;

use Illuminate\Foundation\Testing\TestCase;
use Kevupton\MerchantWarrior\Exceptions\CardException;
use Kevupton\MerchantWarrior\MerchantWarrior;
use Kevupton\MerchantWarrior\Repositories\CardRepository;

trait MerchantWarriorTest
{
    /** @var MerchantWarrior */
    private $mw = null;
    private static $temp_id;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateCard()
    {
        $card = [
            'cardName' => 'Kevin Upton',
            'cardNumber' => '4005550000000001',
            'cardExpiryMonth' => '05',
            'cardExpiryYear' => '17'
        ];

        $response = $this->mw()->addCard($card);

        $this->assertTrue($response->isSuccess());

        self::$temp_id = $response->getResult()->cardID;

    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateInvalidCard()
    {

        $card = [
            'cardName' => 'Kevin Upton',
            'cardNumber' => '40055500001110001',
            'cardExpiryMonth' => '04',
            'cardExpiryYear' => '17'
        ];

        $response = $this->mw()->addCard($card);

        $this->assertFalse($response->isSuccess());

    }



    public function testCardInfo() {
        $response = $this->mw()->cardInfo(['cardID' => self::$temp_id]);

        $this->assertTrue($response->isSuccess());
        var_dump($response->getMessage());
        var_dump($response->getResult()->getAttributes());
    }





    public function testRemoveBothInvalidCard() {

        $repo = new CardRepository();
        $card = $repo->retrieveByID(self::$temp_id);

        $data = [
            'cardID' => $card->cardID . 'as',
            'cardkey' => $card->cardKey . 'asd'
        ];

        try {
            $this->mw()->removeCard($data);
            $this->assertFalse(true, 'no exceptions were thrown');
        } catch (\Exception $e) {

        }
    }

    public function testRemoveCard() {

        $repo = new CardRepository();
        $card = $repo->retrieveByID(self::$temp_id);

        $data = [
            'cardID' => $card->cardID,
            'cardkey' => $card->cardKey
        ];

        try {
            $response = $this->mw()->removeCard($data);
            $this->assertFalse(true, 'no error was thrown');

            $this->assertTrue($response->isSuccess());

            $this->assertNull($repo->retrieveByID(self::$temp_id));
        } catch (CardException $e) {

        }
    }


    private function mw() {
        return is_null($this->mw)? $this->mw = new MerchantWarrior(): $this->mw;
    }
}
