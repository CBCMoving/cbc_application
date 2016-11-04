<?php
namespace app\tests\codeception\functional;
use \Codeception\Util\HttpCode;
use app\tests\codeception\functional\traits\ApiTrait;
use Yii;

/**
 *  Testing create order's note and attaching to it picture.
 *
 */
class OrderNotesTest extends \Codeception\Test\Unit
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
     *  OrderNotes fields rules.
     *  @return array list of rules
     */
    public static function rules()
    {
        return [
            'id' => 'integer',
            'text' => 'string:!empty',
            'image' => 'string:file_exists|null',
            'created_at' => 'string:date(m/d/y)',
            'created_by' => 'string'
        ];
    }

    /**
     *  Tests allowed methods to create order note.
     *
     */
    public function testAllowedMethodsToCreateOrderNote()
    {
        $I = $this->tester;

        // Bad methods
        foreach(['PATCH', 'PUT', 'DELETE', 'GET'] as $method) {
            $I->{'send' . $method}('/orders/1/notes', []);
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
            ]);
        }

        // Success method
        $I->sendPOST('/orders/1/notes', []);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
        ]);
    }

    /**
     *  Tests allowed methods to attaching picture to order note.
     *
     */
    public function testAllowedMethodsToAttachPictureToNote()
    {
        $I = $this->tester;

        // Bad methods
        foreach(['PATCH', 'PUT', 'DELETE', 'GET'] as $method) {
            $I->{'send' . $method}('/orders/notes/1/image', []);
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
            ]);
        }
        
        // Success method
        $I->sendPOST('/orders/notes/1/image', []);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
        ]);
    }

    /**
     *  Dev token test to create order note.
     *
     */
    public function testDeveloperTokenToCreateOrderNote()
    {
        // Failed
        $this->checkFailedDevToken('/orders/1/notes', 'POST');

        // Successful
        $this->checkDevToken('/orders/1/notes', 'POST');
    }

    /**
     *  Dev token test to attaching picture to order note.
     *
     */
    public function testDeveloperTokenToAttachPictureToNote()
    {
        // Failed
        $this->checkFailedDevToken('/orders/notes/1/image', 'POST');

        // Successful
        $this->checkDevToken('/orders/notes/1/image', 'POST');
    }

    /**
     *  Test for unauthorized access to create order note.
     *
     */
    public function testUnauthorizedAccessToCreateOrderNote()
    {
        $I = $this->tester;
        $I->haveHttpHeader('Dev-Token', $this->_developerToken);
        
        // Send request and validate
        $I->sendPOST('/orders/1/notes');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);
    }


    /**
     *  Test for unauthorized access to attaching picture to order note.
     *
     */
    public function testUnauthorizedAccessToAttachPictureToNote()
    {
        $I = $this->tester;
        $I->haveHttpHeader('Dev-Token', $this->_developerToken);
        
        // Send request and validate
        $I->sendPOST('/orders/notes/1/image');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);
    }

    /**
     *  Test for authorized access to create order note.
     *
     */
    public function testAuthorizedAccessToCreateOrderNote()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        
        // Send request and validate
        $I->sendPOST('/orders/1/notes');
        $I->seeResponseIsJson();
        $I->dontSeeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->dontSeeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);
    }

    /**
     *  Test for authorized access to attaching picture to order note.
     *
     */
    public function testAuthorizedAccessToAttachPictureToNote()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        
        // Send request and validate
        $I->sendPOST('/orders/notes/1/image');
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
    public function testAccessToAnotherOrderNote()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');

        // Bad identifiers
        foreach([11, 12, 13] as $id) {
            $I->sendPOST('/orders/' . $id . '/notes');
            $I->seeResponseIsJson();
            $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->seeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }

        // Relevant identifiers
        foreach([1, 2, 3, 4, 5, 6, 7] as $id) {
            $I->sendPOST('/orders/' . $id . '/notes');
            $I->seeResponseIsJson();
            $I->dontSeeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->dontSeeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }
    }

    /**
     *  Check access to another order's attaching pictures.
     *
     */
    public function testAccessToAnotherAttachPicturesToOrderNote()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');

        // Bad identifiers
        foreach([2, 4, 6, 9, 10, 11, 12, 13] as $id) {
            $I->sendPOST('/orders/notes/' . $id . '/image');
            $I->seeResponseIsJson();
            $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->seeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }

        // Relevant identifiers
        foreach([1, 3, 5, 7] as $id) {
            $I->sendPOST('/orders/notes/' . $id . '/image');
            $I->seeResponseIsJson();
            $I->dontSeeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->dontSeeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }
    }

    /**
     *  Bad validation attributes to create order note.
     *
     */
    public function testBadValidationToCreateOrderNote()
    {
        $I = $this->tester;
        $values = [
            '',
            'sadasdasdddanfadnfklandlfnlakdfnkjdsnfjkdsnfjksdfnlksdnfkladnfklasdnfkljsdfklnjnflksfnlkfnlkfnlksflkafnlkafnlkafndsfEt necessitatibus numquam minima eaque ipsum molestiae nihil perspiciatis. Adipisci enim rerum veniam maxime rerum. Omnis voluptatem labore rem corrupti.
Quasi fugit nihil ratione tempora accusamus explicabo quaerat magnam. Fuga et autem quis quisquam rerum dolorum. Illum et pariatur corrupti nostrum pariatur amet veniam dolorem.
Dolores fugiat repellat autem qui et error. Est recusandae non dolores doloremque qui quae. Illum culpa qui et aliquid amet laudantium nostrum molestias. Impedit reiciendis suscipit aut. In quasi vel ea doloribus est.
Quisquam sed voluptates explicabo et sit libero voluptas. Sint consequatur perferendis tenetur perferendis incidunt dicta et. Quia doloribus ducimus et assumenda earum architecto. Modi perspiciatis quam consequatur.
Quis perspiciatis perspiciatis rem sunt incidunt ex. Qui et ex ut dolor voluptates iste. Similique et sit et quisquam ipsa asperiores.
Quos voluptates suscipit necessitatibus et numquam tempore explicabo voluptatem. Sed debitis enim quis eligendi dicta architecto. Necessitatibus culpa perferendis qui molestiae nobis ipsam iste.
Est libero voluptatem qui omnis. Id rerum nesciunt mollitia distinctio consectetur. Et neque sapiente vel. Id odio id consequatur commodi nam sed laudantium aut.
Numquam sit provident minus officia. Qui inventore quasi non quia. Distinctio voluptate et rem.
Possimus nulla qui consequuntur velit. Sequi voluptatem veritatis delectus rem fugit ea. Sit qui saepe non accusantium sapiente suscipit.
Consequuntur quisquam eius at iure est commodi commodi. Eos quaerat quaerat ea omnis sed repudiandae. Voluptatum optio explicabo minima qui. Laudantium voluptas nesciunt id aut voluptatibus et autem.
Maxime error impedit placeat. Consequatur delectus eos possimus consequatur qui. Voluptatem ut est eaque architecto quam eligendi laudantium. Deleniti doloremque illum molestiae soluta dolor enim.
Ratione vel sit deserunt autem esse totam aut. Rerum iusto neque ipsum sed possimus animi. Deserunt quibusdam ut ipsa cumque. Officiis eveniet aut vero assumenda unde quia et.
Tenetur doloribus vel dolorem est. Dolores quos cum unde est expedita veritatis. Laudantium nisi repellendus temporibus assumenda autem nesciunt perferendis sed.
Rerum adipisci veniam omnis autem inventore id voluptatem autem. Accusamus velit eligendi quod sint fugit. Quia rerum officiis accusamus fugiat perferendis.
Odio dignissimos odit omnis aut libero debitis dolores. Modi porro reiciendis qui laboriosam nihil cupiditate aspernatur saepe. Aut eius vel est eum totam placeat. Voluptas qui at nemo quia ratione.
Ipsa animi iste facere officiis animi rerum. Nam maxime sed optio et qui natus. Aut numquam ea ut ipsum architecto iusto. Ipsam fuga eos sequi explicabo.
Ullam molestiae magnam aut dicta impedit ratione. Perferendis est repudiandae dolores.
Quis laborum et perferendis itaque. Placeat beatae quod nemo voluptatum. Error laudantium quisquam enim quas enim.
In saepe incidunt ut voluptatibus eos neque quod voluptas. Unde nihil culpa omnis rerum velit molestias molestiae reiciendis. Quo eum ex maxime quos vel.
Minima magnam qui modi rerum minus. Autem sequi odit possimus asperiores ut reiciendis. Sunt voluptatem accusamus dolore perspiciatis non provident earum. Commodi voluptas sit cumque optio.
Sapiente quo consequatur quo praesentium aut ut nesciunt qui. Ullam sunt nulla aut veritatis fugit. Omnis pariatur quae rerum ipsam harum aperiam. Consequatur laudantium in in voluptate et.
Sit veritatis accusantium dolor debitis est nam quasi. Culpa et commodi adipisci qui iure qui dignissimos. Commodi eum aut dolores amet accusantium. Consequatur sit dolorem natus autem sunt laborum voluptas.
Esse natus vel nulla itaque. Sit architecto rerum praesentium veritatis quidem facilis. Eum similique perferendis nam voluptate consectetur eius. Deleniti ratione aperiam eos mollitia est laboriosam.
Quaerat illo necessitatibus eos voluptatem. Voluptas blanditiis nihil eum. Corrupti qui quia iusto distinctio eligendi nihil. Et deleniti eius sit cumque in ea.
Est facere soluta omnis enim est veritatis laborum. Aliquid magni quos omnis distinctio. Et vitae consequatur magni illum quis. Eaque rerum deserunt quis totam eius minus impedit.
In aliquid atque consequuntur et laborum repudiandae. Autem labore blanditiis alias sint molestiae rerum. Asperiores sequi sit maiores quia et et impedit.
Velit omnis dolores quae sunt omnis nihil rerum. Itaque voluptatum rerum ut quia autem nobis. Architecto perferendis sint illum id.
Harum quia culpa et sequi laboriosam. Aut vel quia rem quo molestiae. Porro natus quis eaque illum soluta consequatur aut et. Repellat et commodi sed autem aspernatur in nihil itaque.
Aut velit iusto et voluptate voluptate nulla. Ad non nemo voluptatum quam quas suscipit.
Quia laboriosam distinctio et eos ut sint enim rerum. Non optio assumenda amet ut distinctio. Autem sed nemo consequatur quos adipisci',
    false,
    true
        ];
        $this->authByUsername('driverPortland');

        // Send request and validate
        foreach($values as $value) {
            $I->sendPOST('/orders/1/notes', ['text' => $value]);
            $I->seeResponseIsJson();
            $I->seeResponseMatchesJsonType([
                'field' => 'string',
                'message' => 'string'
            ], '$.*');
            $I->seeResponseContainsJson([
                ['field' => 'text']
            ]);
        }
    }

    /** 
     *  Successful validation attributes to create order note.
     *
     */
    public function testSuccessfulCreateOrderNote()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        $values = [
            'sdfsdfjksdnfjknasdjfnkjfnkjadnfkld kjsa djkskafnjksdf adsfnajksdfnladsnflakfnasdfjkn',
            'text'
        ];

        // Send requests
        foreach($values as $value) {
            $I->sendPOST('/orders/1/notes', ['text' => $value]);
            $I->seeResponseIsJson();
            $I->dontSeeResponseContainsJson([
                ['field' => 'text']
            ]);
            $I->seeResponseContainsJson([
                'text' => $value
            ]);
            $I->seeResponseMatchesJsonType(self::rules());
        }
    }

    /**
     *  Bad validation to attaching picture to order note.
     *
     */
    public function testBadValidationToAttachPictureToRouteNote()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        $this->tester->deleteHeader('Content-Type');

        $tests = [

            // Send ttf font
            function() use ($I) {
                $filename = '@app/tests/codeception/functional/files/font.ttf';
                $I->sendPOST('/orders/notes/1/image', null, ['image' => Yii::getAlias($filename)]);
            }, 

            // Too big image
            function() use ($I) {
                $filename = '@app/tests/codeception/functional/files/too_weight_image.jpg';
                $I->sendPOST('/orders/notes/1/image', null, ['image' => Yii::getAlias($filename)]);
            },

            // Empty request
            function() use ($I) {
                $I->sendPOST('/orders/notes/1/image');
            }
        ];

        // Run functions
        foreach($tests as $test) {
            $test();
            
            $I->seeResponseIsJson();
            $I->seeResponseMatchesJsonType([
                'field' => 'string',
                'message' => 'string'
            ], '$.*');

            $I->seeResponseContainsJson([
                ['field' => 'image']
            ]);
        }
    }

    /**
     *  Successful validation to attach picture to order note. 
     *
     */
    public function testSuccessfulValidateToAttachPictureToOrderNote()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        $this->tester->deleteHeader('Content-Type');

        // Send file
        $filename = '@app/tests/codeception/functional/files/delivery.png';
        $I->sendPOST('/orders/notes/1/image', null, ['image' => Yii::getAlias($filename)]);
        
        // Validate response
        $rules = self::rules();
        $rules['image'] = 'string:file_exists';
        $I->seeResponseMatchesJsonType($rules);

        // Check file is eqials
        $uploadedFile = $I->grabDataFromResponseByJsonPath('$.image');
        $uploadedFile = array_shift($uploadedFile);
        $this->assertTrue($this->equalFiles($filename, Yii::getAlias('@webroot' . $uploadedFile)));
    }
}