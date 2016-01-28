<?php namespace Kevupton\MerchantWarrior\Tests;

use Illuminate\Foundation\Testing\TestCase;
use Kevupton\MerchantWarrior\Exceptions\CardException;
use Kevupton\MerchantWarrior\MerchantWarrior;
use Kevupton\MerchantWarrior\Repositories\CardInfoRepository;
use Kevupton\MerchantWarrior\Repositories\CardRepository;

trait MerchantWarriorTest
{
    /** @var MerchantWarrior */
    private $mw = null;
    protected static $temp_id;

    private static $data = array(
        'transactionAmount' => '10.00',
        'transactionCurrency' => 'AUD',
        'transactionProduct' => 'Test Product',
        'customerName' => 'Foo',
        'customerAddress' => '123 Fake St',
        'customerCountry' => 'AU',
        'customerState' => 'Western Australia',
        'customerCity' => 'Perth',
        'customerPostCode' => '1111',
        'cardID' => '',
        //'paymentCardCSC' => 123
    );

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

        $this->assertTrue($response->success());

        self::$temp_id = $response->result()->cardID;
        self::$data['cardID'] = self::$temp_id;
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

        $this->assertFalse($response->success());

    }



    public function testCardInfo() {
        $response = $this->mw()->cardInfo(['cardID' => self::$temp_id]);

        $this->assertTrue($response->success());

        $repo = new CardInfoRepository();

        $this->assertNotNull($repo->retrieveByID($response->result()->cardID));

    }

    public function testMakePayment() {
        self::$data['custom1'] = json_encode(['user_id' => 1]);
        $response = $this->mw()->processCard(self::$data);

        $this->assertTrue($response->success());
    }

    public function testProcessCardWithoutToken() {
        $data = [
            'transactionAmount' => '10.00',
            'transactionCurrency' => 'AUD',
            'transactionProduct' => 'Test Product',
            'customerName' => 'Foo',
            'customerAddress' => '123 Fake St',
            'customerCountry' => 'AU',
            'customerState' => 'Queensland',
            'customerCity' => 'Brisbane',
            'customerPostCode' => '4000',
            'paymentCardNumber' => '4005550000000001',
            'paymentCardExpiry' => '0517',
            'paymentCardName' => 'Mr Example Person',
            'paymentCardCSC' => 123
        ];

        $response = $this->mw()->processCard($data, false);

        $this->assertTrue($response->success());
    }



    //Run after no  more need for card ---------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------

    public function testChangeExpiry() {
        $repo = new CardInfoRepository();

        $month = '01';
        $year = '20';

        $card_info = $repo->retrieveByID(self::$temp_id);
        $response = $this->mw()->changeExpiry([
            'cardID' => $card_info->cardID,
            'cardExpiryMonth' => $month,
            'cardExpiryYear' => $year
        ]);

        $this->assertTrue($response->success());

        $card_info = $repo->retrieveByID(self::$temp_id);
        $this->assertEquals($card_info->cardExpiryMonth, $month);
        $this->assertEquals($card_info->cardExpiryYear, $year);

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
        } catch (CardException $e) {

        }

        $this->assertTrue($response->success());

        try {
            $repo->retrieveByID(self::$temp_id);
            $this->assertFalse(true, 'exception was thrown');
        } catch(CardException $e) {
            $this->assertTrue(true, 'exception wasnt thrown');
        }
    }


    private function mw() {
        return is_null($this->mw)? $this->mw = new MerchantWarrior(): $this->mw;
    }
}
