<?php
namespace app\tests\codeception\functional;
use \Codeception\Util\HttpCode;
use app\tests\codeception\functional\traits\ApiTrait;

/**
 *  Testing get five last routes of current driver, get more detail per route, update route.
 *
 */
class RoutesTest extends \Codeception\Test\Unit
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
            'app\tests\codeception\fixtures\RoutesFixture',
            'app\tests\codeception\fixtures\OrdersFixture',
            'app\tests\codeception\fixtures\RouteNotesFixture',
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
     *  Route fields rules.
     *  @return array list of rules
     */
    public static function rules()
    {
        return [
            'id' => 'integer',
            'date' => 'string:date(m/d/y)',
            'name' => 'string:!empty',
            'frame_open' => 'string:date(h:i A)|string:len(0)',
            'frame_close' => 'string:date(h:i A)|string:len(0)',
            'stops' => 'integer|null',
            'time_start' => 'string:date(h:i A)|string:len(0)',
            'time_end' => 'string:date(h:i A)|string:len(0)',
            'door' => 'string',
            'truck' => 'string',
            'miles_start' => 'integer|null',
            'miles_end' => 'integer|null',
            'limit_cub_ft' => 'integer|null',
            'limit_stops' => 'integer|null'
        ];
    }

    /**
     *  Tests allowed methods to get routes.
     *
     */
    public function testAllowedMethodsToGetRoutes()
    {
        $I = $this->tester;

        // Bad methods
        foreach(['POST', 'PATCH', 'PUT', 'DELETE'] as $method) {
            $I->{'send' . $method}('/routes', []);
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
            ]);
        }

        // Success method
        $I->sendGET('/routes', []);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
        ]);
    }

    /**
     *  Tests allowed methods to view or update route.
     *
     */
    public function testAllowedMethodsToViewOrUpdateRoute()
    {
        $I = $this->tester;

        // Bad methods
        foreach(['POST', 'DELETE'] as $method) {
            $I->{'send' . $method}('/routes/1', []);
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
            ]);
        }

        // Success method
        $I->sendPUT('/routes/1', []);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
            'previous' => ['name' => 'Invalid Route'],
        ]);
        $I->sendPATCH('/routes/1', []);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
            'previous' => ['name' => 'Invalid Route'],
        ]);
        $I->sendGET('/routes/1', []);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
            'previous' => ['name' => 'Invalid Route'],
        ]);        
    }


    /**
     *  Dev token test to get 5 lastest routes.
     *
     */
    public function testDeveloperTokenToGetRoutes()
    {
        // Failed
        $this->checkFailedDevToken('/routes', 'GET');

        // Success
        $this->checkDevToken('/routes', 'GET');
    }


    /**
     *  Dev token test to update a route.
     *
     */
    public function testDeveloperTokenToUpdateRoute()
    {
        // Failed
        $this->checkFailedDevToken('/routes/1', 'PUT');
        $this->checkFailedDevToken('/routes/1', 'PATCH');

        // Success
        $this->checkDevToken('/routes', 'PUT');
        $this->checkDevToken('/routes', 'PATCH');
    }

    /**
     *  Dev token test to get more information by route.
     *
     */
    public function testDeveloperTokenToGetMoreInformationByRoute()
    {
        // Failed
        $this->checkFailedDevToken('/routes/1', 'GET');

        // Success
        $this->checkDevToken('/routes/1', 'GET');
    }

    /**
     *  Test for unauthorized access to routes.
     *
     */
    public function testUnauthorizedAccessToRoutes()
    {
        $I = $this->tester;
        $I->haveHttpHeader('Dev-Token', $this->_developerToken);
        
        $I->sendGET('/routes');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);

        $I->sendPUT('/routes/1');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);

        $I->sendPATCH('/routes/1');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);

        $I->sendGET('/routes/1');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);
    }

    /**
     *  Test for authorized access to routes.
     *
     */
    public function testAuthorizedAccessToRoutes()
    {
        $I = $this->tester;
        
        $this->authByUsername('driverPortland');
        
        $I->sendGET('/routes');
        $I->seeResponseIsJson();
        $I->dontSeeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->dontSeeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);

        $I->sendPUT('/routes/1');
        $I->seeResponseIsJson();
        $I->dontSeeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->dontSeeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);

        $I->sendPATCH('/routes/1');
        $I->seeResponseIsJson();
        $I->dontSeeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->dontSeeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);

        $I->sendGET('/routes/1');
        $I->seeResponseIsJson();
        $I->dontSeeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->dontSeeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);   
    }

    /**
     *  Test load empty data.
     *
     */
    public function testEmptyRoutesStructure()
    {
        $I = $this->tester;
        $this->authByUsername('driver');
        $I->sendGET('/routes');
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
            []
        ]); 
    }

    /**
     *  Check routes structure.
     *
     */
    public function testGetLastFiveRoutes()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        $I->sendGET('/routes');
        $I->seeResponseIsJson();
        $routes = $I->grabDataFromResponseByJsonPath('*');
        $this->assertEquals(4, count($routes), 'Expected 4 routes.');
        $I->seeResponseMatchesJsonType(self::rules(), '*');
    }

    /**
     *  Check count routes by relevant date.
     *
     */
    public function testOldDateRoutes()
    {
        $I = $this->tester;
        $this->authByUsername('driverKent');
        $I->sendGET('/routes');
        $I->seeResponseIsJson();
        $routes = $I->grabDataFromResponseByJsonPath('*');
        $this->assertEquals(1, count($routes), 'Expected 1 route.');
        $I->seeResponseMatchesJsonType(self::rules(), '*');
    }

    /**
     *  Test to get access to another driver route.
     *
     */
    public function testAccessToAnotherRoute()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        foreach([6, 7, 9] as $id) {
            $I->sendGET('/routes/' . $id);
            $I->seeResponseIsJson();
            $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->seeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }

        // Relevant ID
        foreach([1, 2, 3, 4, 5] as $id) {
            $I->sendGET('/routes/' . $id);
            $I->seeResponseIsJson();
            $I->dontSeeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->dontSeeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }
    }

    /**
     *  Check structure more detail of route.
     *
     */
    public function testStructureMoreDetailsRoute()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        $I->sendGET('/routes/1');
        $I->seeResponseIsJson();
        
        $I->seeResponseMatchesJsonType(self::rules() + [
            'notes' => 'array',
            'orders' => 'array'
        ]);

        // Check notes
        $routeNotes = $I->grabDataFromResponseByJsonPath('$.notes[*]');
        
        if (count($routeNotes)) {
            $I->seeResponseMatchesJsonType(RouteNotesTest::rules(), '$.notes[*]');
        }

        // Check orders
        $I->seeResponseMatchesJsonType(OrdersTest::rules() + [
            'items' => 'array',
            'notes' => 'array',
            'call' => 'array|null'
        ], '$.orders[*]');

        // Check order items
        $I->seeResponseMatchesJsonType([
                'id' => 'integer',
                'quantity' => 'integer',
                'weight' => 'integer|null',
                'cubic_feet' => 'integer|null',
                'cartons' => 'integer|null',
                'commodity' => 'string',
                'model' => 'string',
                'description' => 'string'
            ], '$.orders[*].items[?(@.id)]');


        // Check order notes
        $orderNotes = $I->grabDataFromResponseByJsonPath('$.orders[*].notes[?(@.id)]');
        if (count($orderNotes)) {
            $I->seeResponseMatchesJsonType(OrderNotesTest::rules(), '$.orders[*].notes[?(@.id)]');
        }

        //Check call
        $orderCalls = $I->grabDataFromResponseByJsonPath('$.orders[?(@.call!=null)].call');
        if (count($orderCalls)) {
            $I->seeResponseMatchesJsonType(CallsTest::rules(), '$.orders[?(@.call!=null)].call');
        }
    }

    /**
     *  Check sequence of orders which was sorted by `sort` column per route.
     *
     */
    public function testCheckSequenceOrdersSortedPerRoute()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        $I->sendGET('/routes/1');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'orders' => [
                ['id' => 2],
                ['id' => 3],
                ['id' => 1]
            ]        
        ]);
    }

    /**
     *  Check access to another driver's route update.
     *
     */
    public function testCheckAccessToAnotherDriverRouteUpdate()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        foreach(['PUT', 'PATCH'] as $key => $method) {

            // Another routes id
            foreach([6, 7] as $id) {
                $I->{'send' . $method}('/routes/' . $id);
                $I->seeResponseIsJson();
                $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
                $I->seeResponseContainsJson([
                    'name' => 'Not Found'
                ]); 
            }

            // Routes id
            foreach([1, 2, 3, 4] as $id) {
                $I->{'send' . $method}('/routes/' . $id);
                $I->seeResponseIsJson();
                $I->dontSeeResponseCodeIs(HttpCode::NOT_FOUND);
                $I->dontSeeResponseContainsJson([
                    'name' => 'Not Found'
                ]);   
            }
        }
    }

    /**
     *  Bad validation attributes to update route.
     *
     */
    public function testBadValidationUpdateRoute()
    {
        $I = $this->tester;
        $attributes = [
            'miles_start' => [
                'text',
                false,
                true
            ],
            'miles_end' => [
                'text',
                false,
                true
            ],
            'time_start' => [
                '11:10 A',
                '9:17 P',
                1,
                '123',
                true,
                false,
            ],
            'time_end' => [
                '12:30 P',
                '7:30 A',
                1,
                '123',
                true,
                false,
            ],            
            'truck' => [
                'asdasdasdasdaasdnasnfanfksdjfnksdjdknfksdjfnsdkjfnsdkjdjkfnsdkjdkjfjkdfjkfnksdjnfksjdnfksdjfdkdjkfdjkfnsdjkfnsdjkfnsdjkfnskdmflksdmflksdmflksdmlkfmsdlkdlkmflsdkfmlsdkfmlsdkmflksdmflskdmflksdmfksdlfldfldfldfmlsdkfmlsdkfldkfldmflsdkmflsdkmflsdkmflsdkmflsdmflsdkmflskdfmlsdmflsdkflsfmlsdmflskdmflskdmflksdmflsdkfd',
                false,
                true
            ],
            'door' => [
                'asdasdasdasdaasdnasnfanfksdjfnksdjdknfksdjfnsdkjfnsdkjdjkfnsdkjdkjfjkdfjkfnksdjnfksjdnfksdjfdkdjkfdjkfnsdjkfnsdjkfnsdjkfnskdmflksdmflksdmflksdmlkfmsdlkdlkmflsdkfmlsdkfmlsdkmflksdmflskdmflksdmfksdlfldfldfldfmlsdkfmlsdkfldkfldmflsdkmflsdkmflsdkmflsdkmflsdmflsdkmflskdfmlsdmflsdkflsfmlsdmflskdmflskdmflksdmflsdkfd',
                false,
                true
            ]           
        ];
        $this->authByUsername('driverPortland');
        foreach(['PUT', 'PATCH'] as $key => $method) {
            foreach($attributes as $field => $values) {
                foreach($values as $value) {
                    $I->{'send' . $method}('/routes/1', [$field => $value]);
                    $I->seeResponseIsJson();
                    $I->seeResponseMatchesJsonType([
                        'field' => 'string',
                        'message' => 'string'
                    ], '$.*');
                    $I->seeResponseContainsJson([
                        ['field' => $field]
                    ]);
                }
            }
        }
    }


    /**
     *  Successful update route.
     *
     */
    public function testSuccessfulValidationUpdateRoute()
    {
        $I = $this->tester;
        $attributes = [
            'miles_start' => [
                10,
                "10"
            ],
            'miles_end' => [
                10,
                "10"
            ],
            'time_start' => [
                '11:10 AM',
                '9:17 PM'
            ],
            'time_end' => [
                '12:30 PM',
                '7:30 AM'
            ],
            'truck' => [
                'flsdkmflsdkmflsdkmflsdmflsdkmflskdfmlsdmflsdkflsfmlsdmflskdmflskdmflksdmflsdkfd'
            ],
            'door' => [
                'flsdkmflsdkmflsdkmflsdmflsdkmflskdfmlsdmflsdkflsfmlsdmflskdmflskdmflksdmflsdkfd'
            ]           
        ];
        $this->authByUsername('driverPortland');
        foreach(['PUT', 'PATCH'] as $key => $method) {
            foreach($attributes as $field => $values) {
                foreach($values as $value) {
                    $I->{'send' . $method}('/routes/1', [$field => $value]);
                    $I->seeResponseIsJson();
                    $I->seeResponseMatchesJsonType(self::rules());
                    $I->seeResponseContainsJson([$field => $value]);
                }
            }
        }
    }
}