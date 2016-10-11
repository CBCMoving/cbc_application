<?php
namespace app\tests\codeception\functional;
use \Codeception\Util\HttpCode;
use app\tests\codeception\functional\traits\ApiTrait;

/**
 *  Testing authentication/authorization across api.
 *
 */
class AuthenticateTest extends \Codeception\Test\Unit
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
        $this->tester->haveHttpHeader('Content-Type', 'application/json');
        $this->loadFixtures();
    }

    /**
     *  Apply fixtures.
     *  @return array
     */
    public function fixtures()
    {
        return ['app\tests\codeception\fixtures\UserFixture'];
    }

    /**
     *  Unload fixtures.
     */
    protected function _after()
    {
        $this->unloadFixtures();
    }

    /**
     *  Dev token
     */
    public function testTestDeveloperToken()
    {
        $I = $this->tester;

        // Forbidden
        $this->checkFailedDevToken('/auth');
        
        // Success
        $this->checkDevToken('/auth');
    }

    /**
     *  Checking for a blank password or login.
     */
    public function testBlankPasswordOrLogin()
    {
        $I = $this->tester;
        $this->tester->haveHttpHeader('Dev-Token', $this->_developerToken);

        // Both blank.
        $I->sendPOST('/auth', []);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            [
                'field' => 'username',
                'message' => 'Username cannot be blank.'
            ],
            [
                'field' => 'password',
                'message' => 'Password cannot be blank.'
            ]
        ]);

        // Blank password
        $I->sendPOST('/auth', ['username' => 'user']);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            [
                'field' => 'password',
                'message' => 'Password cannot be blank.'
            ]
        ]);

        // Blank login
        $I->sendPOST('/auth', ['password' => 'some password']);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            [
                'field' => 'username',
                'message' => 'Username cannot be blank.'
            ]
        ]);
    }

    /**
     *  Trying authenticate by admin user.
     */
    public function testAuthenticateAdmin()
    {
        $I = $this->tester;
        $this->tester->haveHttpHeader('Dev-Token', $this->_developerToken);

        $I->sendPOST('/auth', ['username' => 'adminUser', 'password' => 'emailemail']);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
            'username' => 'adminUser'
        ]);
        $I->dontSeeResponseMatchesJsonType([
            'access_token' => 'string',
            'office' => 'string'
        ]); 
    }

    /**
     *  Trying authenticate by office user.
     */
    public function testAuthenticateOffice()
    {
        $I = $this->tester;
        $this->tester->haveHttpHeader('Dev-Token', $this->_developerToken);

        $I->sendPOST('/auth', ['username' => 'officeUser', 'password' => 'emailemail']);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
            'username' => 'adminUser'
        ]);
        $I->dontSeeResponseMatchesJsonType([
            'access_token' => 'string',
            'office' => 'string'
        ]); 
    }    


    /**
     *  Trying to authenticate by spokane driver.
     */
    public function testAuthenticateSpokaneDriver()
    {
        $this->authenticate('Spokane');
    }

    /**
     *  Trying to authenticate by kent driver.
     */
    public function testAuthenticateKentDriver()
    {
        $this->authenticate('Kent');
    }

    /**
     *  Trying to authenticate by kent driver.
     */
    public function testAuthenticatePortlandDriver()
    {
        $this->authenticate('Portland');
    }

    /**
     *  Test bearer auth.
     */
    public function testBearerAuth()
    {
        $I = $this->tester;
        $this->tester->haveHttpHeader('Dev-Token', $this->_developerToken);

        // Valid driver data
        $this->authByUsername('driverSpokane');
        $I->sendPOST('/auth', ['username' => 'driverSpokane', 'password' => 'emailemail']);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    /** 
     *  Authenticate test.
     */
    private function authenticate(string $username)
    {
        $I = $this->tester;
        $this->tester->haveHttpHeader('Dev-Token', $this->_developerToken);

        // Invalid driver data
        $I->sendPOST('/auth', ['username' => 'driver' . $username, 'password' => 'error password']);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            [
                'field' => 'password',
                'message' => 'Incorrect username or password.'
            ]
        ]);

        // Valid driver data
        $I->sendPOST('/auth', ['username' => 'driver' . $username, 'password' => 'emailemail']);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
            [
                'field' => 'password',
                'message' => 'Incorrect username or password.'
            ]
        ]); 
        $I->seeResponseContainsJson([
            'username' => 'driver' . $username
        ]);
        $I->seeResponseContainsJson([
            'office' => $username
        ]);
        $I->seeResponseMatchesJsonType([
            'access_token' => 'string'
        ]);        
    }
}