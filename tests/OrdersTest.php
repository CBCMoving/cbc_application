<?php
namespace app\tests\codeception\functional;
use \Codeception\Util\HttpCode;
use app\tests\codeception\functional\traits\ApiTrait;

/**
 *  Testing changing order's status.
 *
 */
class OrdersTest extends \Codeception\Test\Unit
{
    use ApiTrait;

    /**
     * @var \FunctionalTester
     */
    protected $tester;

    /**
     *  Setting header and load fixtures.
     */
    protected function _before()
    {
        $this->loadFixtures();
        $this->tester->haveHttpHeader('Content-Type', 'application/json');
        $this->addCustomFilters();
    }

    /**
     *  Apply fixtures.
     *  @return array
     */
    public function fixtures()
    {
        return [
            'app\tests\codeception\fixtures\OrdersFixture',
            'app\tests\codeception\fixtures\RoutesFixture'
        ];
    }

    /**
     *  Unload fixtures.
     */
    protected function _after()
    {
        $this->unloadFixtures();
        $this->cleanCustomFilters();
    }

    /**
     *  Order fields rules.
     *  @return array list of rules
     */
    public static function rules()
    {
        return [
            'id' => 'integer',
            'order_number' => 'string:!empty',
            'address1' => 'string:!empty',
            'city' => 'string:!empty',
            'status' => 'string:!empty',
            'type' => 'string:!empty',
            'customer' => 'string',
            'address2' => 'string',
            'zip' => 'string',
            'phone' => 'string',
            'phone_home' => 'string',
            'phone_other' => 'string',
            'fax' => 'string',
            'pieces' => 'integer|null',
            'cartons' => 'integer|null',
            'time_from' => 'string:date(h:i A)|string:len(0)',
            'time_to' => 'string:date(h:i A)|string:len(0)',
            'spec_instructions' => 'string',
            'service' => 'string:in(WG|T|RC)|null'
        ];
    }

    /**
     *  Tests allowed methods to update order.
     *
     */
    public function testAllowedMethodsToUpdateOrder()
    {
        $I = $this->tester;

        // Bad methods
        foreach(['POST', 'DELETE', 'GET'] as $method) {
            $I->{'send' . $method}('/orders/1', []);
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
            ]);
        }

        // Success methods
        $I->sendPUT('/orders/1', []);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
        ]);
        $I->sendPATCH('/orders/1', []);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
        ]);
    }

    /**
     *  Dev token test to update order.
     *
     */
    public function testDeveloperTokenToUpdateOrder()
    {
        // Failed
        $this->checkFailedDevToken('/orders/1', 'PUT');
        $this->checkFailedDevToken('/orders/1', 'PATCH');

        // Success
        $this->checkDevToken('/orders/1', 'PUT');
        $this->checkDevToken('/orders/1', 'PATCH');
    }

    /**
     *  Test for unauthorized access to update order.
     *
     */
    public function testUnauthorizedAccessToUpdateOrder()
    {
        $I = $this->tester;
        $I->haveHttpHeader('Dev-Token', $this->_developerToken);
        
        // Send request and validate
        $I->sendPUT('/orders/1');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);

        // Send request and validate
        $I->sendPATCH('/orders/1');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);
    }

    /**
     *  Test for authorized access to update order.
     *
     */
    public function testAuthorizedAccessToUpdateOrder()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        
        // Send request and validate
        $I->sendPUT('/orders/1');
        $I->seeResponseIsJson();
        $I->dontSeeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->dontSeeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);

        // Send request and validate
        $I->sendPATCH('/orders/1');
        $I->seeResponseIsJson();
        $I->dontSeeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->dontSeeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);
    }

    /**
     *  Test to get access to another order.
     *
     */
    public function testAccessToAnotherOrder()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');

        // Bad identifiers
        foreach([11, 12, 13] as $id) {
            $I->sendPATCH('/orders/' . $id);
            $I->seeResponseIsJson();
            $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->seeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }

        // Good identifiers
        foreach([1, 2, 3, 4, 5, 6, 7] as $id) {
            $I->sendPATCH('/orders/' . $id);
            $I->seeResponseIsJson();
            $I->dontSeeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->dontSeeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }
    }

    /**
     *  Bad update order status.
     *
     */
    public function testBadValidationUpdateOrderStatus()
    {
        $I = $this->tester;
        $values = [
            0,
            '0',
            '23',
            23,
            'sadasdasd',
            '',
            '-1',
            -1,
            true,
            false,
            null
        ];
        $this->authByUsername('driverPortland');

        // Send requests and validate per value
        foreach($values as $value) {
            $I->sendPUT('/orders/1', ['status' => $value]);
            $I->seeResponseIsJson();
            $I->seeResponseMatchesJsonType([
                'field' => 'string',
                'message' => 'string'
            ], '$.*');
            $I->seeResponseContainsJson([
                ['field' => 'status']
            ]);
        }
    }

    /** 
     *  Successful update status order.
     *
     */
    public function testSuccessfulUpdateOrderStatus()
    {
        $I = $this->tester;
        $values = [
            '1' => 'Unknown',
            '2' => 'PUDispatched',
            '3' => 'PUArrived',
            '4' => 'PUInspected',
            '5' => 'PUProof',
            '6' => 'PUAvailable',
            '7' => 'PUDelivered',
            '8' => 'Transfer',
            '9' => 'Inbound',
            '10' => 'Available4PU',
            '11' => 'OnHand',
            '12' => 'InspCompleted',
            '13' => 'Outbound',
            '14' => 'DeliveryPartial',
            '15' => 'Delivery',
            '16' => 'FldDestroy',
            '17' => 'Disposed',
            '18' => 'Exception',
            '19' => 'Refused',
            '20' => 'Cancelled',
            '21' => 'Lost',
            '22' => 'Undelivered'
        ];
        $this->authByUsername('driverPortland');

        // Send request and validate per status
        foreach ($values as $id => $value) {
            $I->sendPUT('/orders/1', ['status' => $id]);
            $I->seeResponseMatchesJsonType(OrdersTest::rules());
            $I->seeResponseContainsJson([
                'status' => $value
            ]);
        }
    }

}