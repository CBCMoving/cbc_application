<?php
namespace app\tests\codeception\functional\traits;
use \Codeception\Util\HttpCode;
use \Codeception\Util\JsonType;
use yii\test\FixtureTrait;
use DateTime;
use Yii;

trait ApiTrait {

    use FixtureTrait {
        FixtureTrait::loadFixtures as parentLoadFixtures;
    }

    /**
     *  @var string
     */
    private $_developerToken = 'DfmSkf5fo6M3Sfjn12DNfjkbasd532dFsd';

    /**
     *  Check to access for specified url with develop token.
     *  @param string $url
     */
    public function checkDevToken(string $url, string $method = 'POST')
    {
        $I = $this->tester;
        $this->tester->haveHttpHeader('Dev-Token', $this->_developerToken);
        $I->{'send' . $method}($url, []);
        $I->dontSeeResponseContainsJson([
            'message' => 'Dev token authentication has failed.'
        ]);
    }

    /**
     *  Load fixstures without foreign key checks.
     *
     */
    public function loadFixtures()
    {
        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        $this->parentLoadFixtures();
        Yii::$app->db->createCommand('set foreign_key_checks=1')->execute();
    }

    
    /**
     *  Check to access for specified url with develop token.
     *  @param string $url
     */    
    public function checkFailedDevToken(string $url, string $method = 'POST')
    {
        $I = $this->tester;
        $I->{'send' . $method}($url, []);
        $I->deleteHeader('Dev-Token');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => 'Dev token authentication has failed.'
        ]);
    }


    /**
     *  Set auth bearer token.
     *  @param string $token
     */
    public function authByUsername(string $username)
    {
        $I = $this->tester;
        $I->deleteHeader('Authorization');
        $this->tester->haveHttpHeader('Dev-Token', $this->_developerToken);
        $I->sendPOST('/auth', ['username' => $username, 'password' => 'emailemail']);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'username' => $username
        ]);
        $I->seeResponseMatchesJsonType([
            'access_token' => 'string',
            'office' => 'string'
        ]); 
        $grabArray = $I->grabDataFromResponseByJsonPath('$.access_token');
        $token = array_pop($grabArray);
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);   
    }

    /**
     *  Add custom filters to json validate.
     *
     */
    public function addCustomFilters()
    {   
        // Data validate by format
        JsonType::addCustomFilter('/date\((.*?)\)/', function($date, $format) {
            $DateTime = DateTime::createFromFormat($format, $date);
            return $DateTime && ($DateTime->format($format) == $date || ltrim($DateTime->format($format), '0') == $date);
        });

        // Length validate string
        JsonType::addCustomFilter('/len\((.*?)\)/', function($value, $len) {
            return strlen($value) == $len;
        });

        // Length validate string
        JsonType::addCustomFilter('/in\((.*?)\)/', function($value, $in) {
            $array = explode('|', $in);
            return in_array($value, $array);
        });

        // File exists
        JsonType::addCustomFilter('/file_exists/', function($path) {
            return is_file(Yii::getAlias('@webroot' . $path));
        });
    }

    /**
     *  Clean all custom filters.
     *
     */
    public function cleanCustomFilters()
    {
        JsonType::cleanCustomFilters();
    }

    /**
     *  Check equal files.
     *  @param string $file1 path to first file
     *  @param string $file2 path to second file
     *  @return boolean
     */
    public function equalFiles($file1, $file2)
    {
        $file1 = Yii::getAlias($file1);
        $file2 = Yii::getAlias($file2);
        return md5_file($file1) === md5_file($file2);
    }
}