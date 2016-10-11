<?php
namespace app\tests\codeception\functional;
use \Codeception\Util\HttpCode;
use app\tests\codeception\functional\traits\ApiTrait;
use Yii;

/**
 *  Testing create order's precall.
 *
 */
class CallsTest extends \Codeception\Test\Unit
{
    use ApiTrait;

    /**
     * @var \FunctionalTester
     */
    protected $tester;

    /**
     *  @var array success validateion attributes
     */
    private $_successAttributes = [
        'name' => 'Name name Namee',
        'phone' => '123-123-9999',
        'time_called' => '12:15 PM',
        'answered' => 0,
        'left_message' => 1,
        'confirmed' => 1,
        'note' => 'ptates explicabo et sit libero voluptas. Sint consequatur perferendis tenetur perferendis incidunt dicta et. Quia              doloribus ducimus et a',
    ];

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
     *  Calls fields rules.
     *  @return array list of rules
     */
    public static function rules()
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'phone' => 'string:!empty',
            'answered' => 'integer:=0|integer:=1',
            'confirmed' => 'integer:=0|integer:=1',
            'left_message' => 'integer:=0|integer:=1',
            'note' => 'string',
            'time_called' => 'string:date(h:i A)|string:len(0)'
        ];
    }

    /**
     *  Tests allowed methods to create call.
     *
     */
    public function testAllowedMethodsToCreateCall()
    {
        $I = $this->tester;

        // Bad methods
        foreach(['PATCH', 'PUT', 'DELETE', 'GET'] as $method) {
            $I->{'send' . $method}('/orders/1/calls', []);
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
            ]);
        }

        // Success method
        $I->sendPOST('/orders/1/calls', []);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
        ]);
    }

    /**
     *  Dev token test to create call.
     *
     */
    public function testDeveloperTokenToCreateCall()
    {
        // Failed
        $this->checkFailedDevToken('/orders/1/calls', 'POST');

        // Successful
        $this->checkDevToken('/orders/1/calls', 'POST');
    }

    /**
     *  Test for unauthorized access to create call.
     *
     */
    public function testUnauthorizedAccessToCreateCall()
    {
        $I = $this->tester;
        $I->haveHttpHeader('Dev-Token', $this->_developerToken);
        
        $I->sendPOST('/orders/1/calls');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);
    }

    /**
     *  Test for authorized access to create call.
     *
     */
    public function testAuthorizedAccessToCreateCall()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        
        $I->sendPOST('/orders/1/calls');
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
            $I->sendPOST('/orders/' . $id . '/calls');
            $I->seeResponseIsJson();
            $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->seeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }

        // Relevant identifiers
        foreach([1, 2, 3, 4, 5, 6, 7] as $id) {
            $I->sendPOST('/orders/' . $id . '/calls');
            $I->seeResponseIsJson();
            $I->dontSeeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->dontSeeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }
    }


    public function testRequiredFieldsToCreateCall()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        $fields = ['name' => 'namename', 'phone' => '123-123-1231', 'time_called' => '10:30 PM', 'answered' => 0];

        foreach($fields as $name => $value) {
            $tempFields = $fields;
            unset($tempFields[$name]);
            $I->sendPOST('/orders/1/calls', $tempFields);
            $I->seeResponseMatchesJsonType([
                    'field' => 'string',
                    'message' => 'string'
                ], '$.*');
                $I->seeResponseContainsJson([
                    ['field' => $name]
                ]);
        }
    }

    /**
     *  Bad create call.
     *
     */
    public function testBadValidationToCreateCall()
    {
        $I = $this->tester;
        $attributes = [
            'name' => [
                null,
                1,
                '',
                'sadasdasdddanfadnfklandlfnlakdfnkjdsnfjkdsnfjksdfnlksdnfkladnfklasdnfkljsdfklnjnflksfnlkfnlkfnlksflkafnlkafnlkafndsfEt necessitatibus numquam minima eaque ipsum molestiae nihil perspiciatis. Adipisci enim rerum veniam maxime rerum. Omnis voluptatem labore rem corrupti.
                Quasi fugit nihil ratione tempora accusamus explicabo quaerat magnam. Fuga et autem quis quisquam rerum dolorum. Illum et pariatur corrupti nostrum pariatur amet veniam dolorem.
                Dolores fugia',
                true,
                false
            ],
            'phone' => [
                '123123123123',
                123123123213,
                null,
                '123-123-123',
                true,
                false
            ],
            'time_called' => [
                '123123123',
                '12/01/1884',
                '13:01',
                null,
                123123,
                true,
                false
            ],
            'answered' => [
                '123',
                null,
                'true',
                'false',
                123,
                true,
                false
            ],
            'confirmed' => [
                '123',
                'true',
                'false',
                123,
                true,
                false
            ],
            'left_message' => [
                '123',
                'true',
                'false',
                123,
                true,
                false                
            ],
            'note' => [
                't repellat autem qui et error. Est recusandae non dolores doloremque qui quae. Illum culpa qui et aliquid amet laudantium nostrum molestias. Impedit reiciendis suscipit aut. In quasi vel ea doloribus est.
                Quisquam sed voluptates explicabo et sit libero voluptas. Sint consequatur perferendis tenetur perferendis incidunt dicta et. Quia              doloribus ducimus et assumenda earum architecto. Modi perspiciatis quam consequatur.
                Quiuiis perspiciatis perspiciatis rem sunt incidunt ex. Qui et ex ut dolor voluptates iste. Similique et sit et quisquam ipsa asperiores.
                Quos voluptates suscipit',
                true,
                false
            ]
        ];
        $this->authByUsername('driverPortland');

        foreach($attributes as $attribute => $values) {
           foreach($values as $value) {
                $tempFields = $this->_successAttributes;
                $tempFields[$attribute] = $value;
                $I->sendPOST('/orders/1/calls', $tempFields);
                $I->seeResponseIsJson();
                $I->seeResponseMatchesJsonType([
                    'field' => 'string',
                    'message' => 'string'
                ], '$.*');
                $I->seeResponseContainsJson([
                    ['field' => $attribute]
                ]);
            } 
        }
        
    }

    /** 
     *  Successful create call.
     *
     */
    public function testSuccessfulCreateCall()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        $attributes = $this->_successAttributes;

        // Send requests and validate response
        $I->sendPOST('/orders/1/calls', $attributes);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
            ['field' => 'text']
        ]);
        $I->seeResponseMatchesJsonType(self::rules());
        foreach ($attributes as $name => $value) {
            $I->seeResponseContainsJson([
                $name => $value
            ]);
        }
    }
}