<?php
namespace app\tests\codeception\functional;
use \Codeception\Util\HttpCode;
use app\tests\codeception\functional\traits\ApiTrait;
use Yii;

/**
 *  Testing create order's survey and attaching signature picture.
 *
 */
class OrderSurveyTest extends \Codeception\Test\Unit
{
    use ApiTrait;

    /**
     * @var \FunctionalTester
     */
    protected $tester;

    /**
     *  @var array 
     */
    private $_successAttributes = [
        'name' => 'name name',
        'satisfied_delivery_team' => 'completely_unsatisfied',
        'exterior_packing' => 1,
        'two_people' => 0,
        'arrive_time_window' => 1,
        'comments' => 'asdasdasdasdasd',
        'items' => [
            [
                'id' => 6,
                'status' => 'short',
                'initials' => 'AB'
            ],
            [
                'id' => 15,
                'status' => 'refused',
                'initials' => 'KS'
            ],
        ]
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
     *  Survey fields rules.
     *  @return array list of rules
     */
    public static function rules()
    {
        return [
            'name' => 'string:!empty',
            'satisfied_delivery_team' => 'string:in(completely_unsatisfied|somewhat_unsatisfied|average|somewhat_satisfied|completely_satisfied)',
            'exterior_packing' => 'integer:=0|integer:=1|null',
            'two_people' => 'integer:=0|integer:=1|null',
            'arrive_time_window' => 'integer:=0|integer:=1|null',
            'comments' => 'string',
            'signature' => 'string:file_exists|null',
            'items' => 'array'
        ];
    }

    /**
     *  Tests allowed methods to create survey.
     *
     */
    public function testAllowedMethodsToCreateSurvey()
    {
        $I = $this->tester;

        // Bad methods
        foreach(['PATCH', 'PUT', 'DELETE', 'GET'] as $method) {
            $I->{'send' . $method}('/orders/1/survey', []);
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
            ]);
        }

        // Success method
        $I->sendPOST('/orders/1/survey', []);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
        ]);
    }

    /**
     *  Tests allowed methods to attaching signature to survey.
     *
     */
    public function testAllowedMethodsToAttachSignatureToSurvey()
    {
        $I = $this->tester;

        // Bad methods
        foreach(['PATCH', 'PUT', 'DELETE', 'GET'] as $method) {
            $I->{'send' . $method}('/orders/1/survey/signature', []);
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
            ]);
        }
        
        // Success method
        $I->sendPOST('/orders/1/survey/signature', []);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([
                'previous' => ['name' => 'Invalid Route'],
        ]);
    }

    /**
     *  Dev token test to create survey.
     *
     */
    public function testDeveloperTokenToCreateSurvey()
    {
        // Failed
        $this->checkFailedDevToken('/orders/1/survey', 'POST');

        // Successful
        $this->checkDevToken('/orders/1/survey', 'POST');
    }

    /**
     *  Dev token test to attaching signature to survey.
     *
     */
    public function testDeveloperTokenToAttachSignatureToSurvey()
    {
        // Failed
        $this->checkFailedDevToken('/orders/1/survey/signature', 'POST');

        // Successful
        $this->checkDevToken('/orders/1/survey/signature', 'POST');
    }

    /**
     *  Test for unauthorized access to create order note.
     *
     */
    public function testUnauthorizedAccessToCreateSurvey()
    {
        $I = $this->tester;
        $I->haveHttpHeader('Dev-Token', $this->_developerToken);
        
        $I->sendPOST('/orders/1/survey');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);
    }


    /**
     *  Test for unauthorized access to attaching signature to survey.
     *
     */
    public function testUnauthorizedAccessToAttachSignatureToSurvey()
    {
        $I = $this->tester;
        $I->haveHttpHeader('Dev-Token', $this->_developerToken);
        
        $I->sendPOST('/orders/1/survey/signature');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);
    }

    /**
     *  Test for authorized access to create survey.
     *
     */
    public function testAuthorizedAccessToCreateSurvey()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        
        $I->sendPOST('/orders/1/survey');
        $I->seeResponseIsJson();
        $I->dontSeeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->dontSeeResponseContainsJson([
            'name' => 'Unauthorized'
        ]);
    }

    /**
     *  Test for authorized access to attaching signature to survey.
     *
     */
    public function testAuthorizedAccessToAttachSignatureToNote()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        
        $I->sendPOST('/orders/1/survey/signature');
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
            $I->sendPOST('/orders/' . $id . '/survey');
            $I->seeResponseIsJson();
            $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->seeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }

        // Relevant identifiers
        foreach([1, 2, 3, 4, 5, 6, 7] as $id) {
            $I->sendPOST('/orders/' . $id . '/survey');
            $I->seeResponseIsJson();
            $I->dontSeeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->dontSeeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }
    }

    /**
     *  Check access to another survey order's attach signature's image.
     *
     */
    public function testAccessToAnotherAttachSignatureToSurvey()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');

        // Bad identifiers
        foreach([3] as $id) {
            $I->sendPOST('/orders/' . $id . '/survey/signature');
            $I->seeResponseIsJson();
            $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->seeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }

        // Relevant identifiers
        foreach([1, 2] as $id) {
            $I->sendPOST('/orders/' . $id . '/survey/signature');
            $I->seeResponseIsJson();
            $I->dontSeeResponseCodeIs(HttpCode::NOT_FOUND);
            $I->dontSeeResponseContainsJson([
                'name' => 'Not Found'
            ]); 
        }
    }

    /** 
     *  Check required fields to create survey.
     *
     */
    public function testRequiredFieldsToCreateSurvey()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        $attributes = [
            'name' => 'name name',
            'satisfied_delivery_team' => 'completely_unsatisfied',
            'items' => $this->_successAttributes['items']
        ];
        foreach ($attributes as $name => $value) {
            $tempFields = $attributes;
            unset($tempFields[$name]);
            $I->sendPOST('/orders/3/survey', $tempFields);
            $I->seeResponseIsJson();
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
     *  Bad create order survey.
     *
     */
    public function testBadValidationToCreateSurvey()
    {
        $I = $this->tester;
        $attributes = [
            'name' => [
                null,
                "2342342332e23e23e23e23eenrffnn38fn87fnfhirfriheiuahfaiuoehfaueihfauiehf7hia7hfi74hfihiuhfuhueihuiahfiuabfiuabibvaidbaudvbibaivubioadvbioadbvioabvoiabviaeuhfiahfadhfasduhfsduhfdshfiahfsduhfioaudhfauhf dfsfsdfsdfsdfsdfdsfsdfsdfsdfsdfsdfsdfsdfsd sdfsdfsdfsdfsdfsdfsdfsdfsdfsdf",
                true,
                false,
            ],
            'satisfied_delivery_team' => [
                'text',
                123,
                null,
                true,
                false,
            ],
            'exterior_packing' => [
                '123',
                'true',
                'false',
                123,
                true,
                false,
            ],
            'two_people' => [
                '123',
                'true',
                'false',
                123,
                true,
                false,
            ],
            'arrive_time_window' => [
                '123',
                'true',
                'false',
                123,
                true,
                false,
            ],
            'comments' => [
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
Quia laboriosam distinctio et eos ut sadasdasdddanfadnfklandlfnlakdfnkjdsnfjkdsnfjksdfnlksdnfkladnfklasdnfkljsdfklnjnflksfnlkfnlkfnlksflkafnlkafnlkafndsfEt necessitatibus numquam minima eaque ipsum molestiae nihil perspiciatis. Adipisci enim rerum veniam maxime rerum. Omnis voluptatem labore rem corrupti.
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
Quia laboriosam distinctio et eos ut sadasdasdddanfadnfklandlfnlakdfnkjdsnfjkdsnfjksdfnlksdnfkladnfklasdnfkljsdfklnjnflksfnlkfnlkfnlksflkafnlkafnlkafndsfEt necessitatibus numquam minima eaque ipsum molestiae nihil perspiciatis. Adipisci enim rerum veniam maxime rerum. Omnis voluptatem labore rem corrupti.
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
Quia laboriosam distinctio et eos ut'
            ],
            'items' => ['{}']
        ];
        $this->authByUsername('driverPortland');
        foreach($attributes as $attribute => $values) {
            foreach($values as $value) {
                $tempFields = $this->_successAttributes;
                $tempFields[$attribute] = $value;
                $I->sendPOST('/orders/3/survey', $tempFields);
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
     *  Validate items of survey.
     *
     */
    public function testItemsValidations()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');

        // Valid items
        $validItems = [
            [
                'id' => 6,
                'status' => 'short',
                'initials' => 'AB'
            ],
            [
                'id' => 15,
                'status' => 'refused',
                'initials' => 'KS'
            ],                    
        ];

        // Bad items
        $badItems = [
            'id' => [4, 5, 222, 'asdasdasd', null, 0, true, false],
            'status' => [123, '123', null, true, false, 123123, 'text', 0],
            'initials' => ['ABC', true, false, null, 123, 0]
        ];

        // Success items
        $successItems = [
            'status' => ['short', 'refused', 'received'],
            'initials' => ['KG', 'DB', 'JS']
        ];

        // Check number of items
        $items = $validItems;
        foreach($items as $key => $item) {
            $tempFields = $this->_successAttributes;
            if ($key == 0) {
                $tempFields['items'] = array_merge($validItems, [['id' => 12]]);
            } else {
                 unset($items[$key]);
                $tempFields['items'] = $items;
            }
            
            // Send and validate response
            $I->sendPOST('/orders/3/survey', $tempFields);
            $I->seeResponseIsJson();
                $I->seeResponseMatchesJsonType([
                    'field' => 'string',
                    'message' => 'string'
                ], '$.*');
                $I->seeResponseContainsJson([
                    ['field' => 'items']
                ]);
        }

        // Check bad validate of each item
        foreach($validItems as $itemKey => $fields) {
            foreach($fields as $field => $fieldValue) {
                foreach($badItems[$field] as $badValue) {
                    $items = $validItems;
                    $items[$itemKey][$field] = $badValue;
                    $tempFields = $this->_successAttributes;
                    $tempFields['items'] = $items;
                    $I->sendPOST('/orders/3/survey', $tempFields);
                    $I->seeResponseIsJson();
                    $I->seeResponseMatchesJsonType([
                        'field' => 'string',
                        'message' => 'string'
                    ], '$.*');
                    $I->seeResponseContainsJson([
                        ['field' => 'items']
                    ]);
                }
            }
        }

        // Check success validate of each item
        foreach($validItems as $itemKey => $fields) {
            foreach($fields as $field => $fieldValue) {
                if ($field == 'id') {
                    continue;
                }
                foreach($successItems[$field] as $goodValue) {
                    $items = $validItems;
                    $items[$itemKey][$field] = $goodValue;
                    $tempFields = $this->_successAttributes;
                    $tempFields['items'] = $items;
                    $I->sendPOST('/orders/3/survey', $tempFields);
                    $I->seeResponseIsJson();
                    $I->seeResponseMatchesJsonType(self::rules());
                    $I->seeResponseContainsJson([
                        [$field => $goodValue]
                    ]);
                }
            }
        }
    }


    /** 
     *  Successful create survey.
     *
     */
    public function testSuccessfulCreateSurvey()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        $attributes = [
            'name' => [
                'first name',
                'second name'
            ],
            'satisfied_delivery_team' => [
                'completely_unsatisfied', 
                'somewhat_unsatisfied', 
                'average', 
                'somewhat_satisfied', 
                'completely_satisfied'
            ],
            'exterior_packing' => [0, 1],
            'two_people' => [0, 1],
            'arrive_time_window' => [0, 1],
            'comments' => [
                'first comment',
                'second comment'
            ]
        ];

        // Send requests and validate
        foreach($attributes as $attribute => $values) {
            foreach($values as $value) {
                $tempFields = $this->_successAttributes;
                $tempFields[$attribute] = $value;
                $I->sendPOST('/orders/3/survey', $tempFields);
                $I->seeResponseIsJson();
                $I->seeResponseMatchesJsonType(self::rules());
                $I->seeResponseContainsJson([
                    $attribute => $value
                ]);
            }
        }
    }

    /**
     *  Bad validation to attaching signature image to survey.
     *
     */
    public function testBadValidationToAttachSignatureToSurvey()
    {
        $I = $this->tester;
        $I->deleteHeader('Content-Type');
        $this->authByUsername('driverPortland');
        
        $tests = [

            // Send ttf font
            function() use ($I) {
                $filename = '@app/tests/codeception/functional/files/font.ttf';
                $I->sendPOST('/orders/1/survey/signature', null, ['signature' => Yii::getAlias($filename)]);
            }, 

            // Too big image
            function() use ($I) {
                $filename = '@app/tests/codeception/functional/files/too_weight_image.jpg';
                $I->sendPOST('/orders/1/survey/signature', null, ['signature' => Yii::getAlias($filename)]);
            },

            // Empty request
            function() use ($I) {
                $I->sendPOST('/orders/1/survey/signature');
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
                ['field' => 'signature']
            ]);
        }
    }

    /**
     *  Successful validation to attaching signature to survey. 
     *
     */
    public function testSuccessfulValidateToAttachSignatureToSurvey()
    {
        $I = $this->tester;
        $this->authByUsername('driverPortland');
        $this->tester->deleteHeader('Content-Type');

        // Send file
        $filename = '@app/tests/codeception/functional/files/signature.jpg';
        $I->sendPOST('/orders/1/survey/signature', null, ['signature' => Yii::getAlias($filename)]);
        
        // Validate response
        $rules = self::rules();
        $rules['signature'] = 'string:file_exists';
        $I->seeResponseMatchesJsonType($rules);

        // Check files is eqials
        $uploadedFile = $I->grabDataFromResponseByJsonPath('$.signature');
        $uploadedFile = array_shift($uploadedFile);
        $this->assertTrue($this->equalFiles($filename, Yii::getAlias('@webroot' . $uploadedFile)));
    }
}