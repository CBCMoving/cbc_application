# Api for cbc application
Rest api with tests.
## Content:
- [`Authorization/Authentication`](https://github.com/CBCMoving/cbc_application/blob/master/Authenticate.md "Watch more") ([tests](https://github.com/CBCMoving/cbc_application#authenticatetest-view-source))
- [`Routes`](https://github.com/CBCMoving/cbc_application/blob/master/Routes.md "Watch more") ([tests](https://github.com/CBCMoving/cbc_application#routestest-view-source))
	- [Get five latest routes](https://github.com/CBCMoving/cbc_application/blob/master/Routes.md#get-5-the-latest-routes-starting-from-today)
	- [Load more detail of route](https://github.com/CBCMoving/cbc_application/blob/master/Routes.md#load-more-detail-of-route)
	- [Update route](https://github.com/CBCMoving/cbc_application/blob/master/Routes.md#update-route)
- [`Route notes`](https://github.com/CBCMoving/cbc_application/blob/master/RouteNotes.md "Watch more") ([tests](https://github.com/CBCMoving/cbc_application#routenotestest-view-source))
	- [Create route's note](https://github.com/CBCMoving/cbc_application/blob/master/RouteNotes.md#create-routes-note)
	- [Attach picture to note](https://github.com/CBCMoving/cbc_application/blob/master/RouteNotes.md#attach-picture-to-note)
- [`Orders`](https://github.com/CBCMoving/cbc_application/blob/master/Orders.md "Watch more") ([tests](https://github.com/CBCMoving/cbc_application#orderstest-view-source))
- [`Order notes`](https://github.com/CBCMoving/cbc_application/blob/master/OrderNotes.md "Watch more") ([tests](https://github.com/CBCMoving/cbc_application#ordernotestest-view-source))
	- [Create order's note](https://github.com/CBCMoving/cbc_application/blob/master/OrderNotes.md#create-orders-note)
	- [Attach picture to note](https://github.com/CBCMoving/cbc_application/blob/master/OrderNotes.md#attach-picture-to-note)
- [`Order survey`](https://github.com/CBCMoving/cbc_application/blob/master/OrderSurvey.md "Watch more") ([tests](https://github.com/CBCMoving/cbc_application#ordersurveytest-view-source))
	- [Create/update order's survey](https://github.com/CBCMoving/cbc_application/blob/master/OrderSurvey.md#createupdate-orders-survey)
	- [Attach signature's picture to survey](https://github.com/CBCMoving/cbc_application/blob/master/OrderSurvey.md#attach-signatures-picture-to-survey)
- [`Order calls`](https://github.com/CBCMoving/cbc_application/blob/master/OrderCalls.md "Watch more") ([tests](https://github.com/CBCMoving/cbc_application#callstest-view-source))

## Tests:

### AuthenticateTest ([view source](https://github.com/CBCMoving/cbc_application/blob/master/tests/AuthenticateTest.php)):
✔ AuthenticateTest: Test developer token (1.32s)

✔ AuthenticateTest: Blank password or login (1.55s)

✔ AuthenticateTest: Authenticate admin (1.26s)

✔ AuthenticateTest: Authenticate office (1.28s)

✔ AuthenticateTest: Authenticate spokane driver (3.44s)

✔ AuthenticateTest: Authenticate kent driver (3.23s)

✔ AuthenticateTest: Authenticate portland driver (3.48s)

✔ AuthenticateTest: Bearer auth (2.28s)

### RoutesTest ([view source](https://github.com/CBCMoving/cbc_application/blob/master/tests/RoutesTest.php)):
✔ RoutesTest: Allowed methods to get routes (7.70s)

✔ RoutesTest: Allowed methods to view or update route (8.44s)

✔ RoutesTest: Developer token to get routes (8.38s)

✔ RoutesTest: Developer token to update route (7.93s)

✔ RoutesTest: Developer token to get more information by route (8.75s)

✔ RoutesTest: Unauthorized access to routes (8.40s)

✔ RoutesTest: Authorized access to routes (9.82s)

✔ RoutesTest: Empty routes structure (9.68s)

✔ RoutesTest: Get last five routes (10.05s)

✔ RoutesTest: Old date routes (8.87s)

✔ RoutesTest: Access to another route (9.75s)

✔ RoutesTest: Structure more details route (9.56s)

✔ RoutesTest: Check sequence orders sorted per route (9.40s)

✔ RoutesTest: Check access to another driver route update (10.33s)

✔ RoutesTest: Bad validation update route (11.04s)

✔ RoutesTest: Successful validation update route (10.81s)

### RouteNotesTest ([view source](https://github.com/CBCMoving/cbc_application/blob/master/tests/RouteNotesTest.php)):
✔ RouteNotesTest: Allowed methods to create route note (4.52s)

✔ RouteNotesTest: Allowed methods to attach picture to route note (4.93s)

✔ RouteNotesTest: Developer token to create route note (4.50s)

✔ RouteNotesTest: Developer token to attach picture to route note (4.73s)

✔ RouteNotesTest: Unauthorized access to create route note (4.83s)

✔ RouteNotesTest: Unauthorized access to attach picture to route note (4.50s)

✔ RouteNotesTest: Authorized access to create route note (6.16s)

✔ RouteNotesTest: Authorized access to attach picture to route note (5.76s)

✔ RouteNotesTest: Access to another route (6.08s)

✔ RouteNotesTest: Access to another attach picture to route note (6.17s)

✔ RouteNotesTest: Bad validation to create route note (5.91s)

✔ RouteNotesTest: Successful create order note (5.91s)

✔ RouteNotesTest: Bad validation to attach picture to route note (5.54s)

✔ RouteNotesTest: Successful validate to attach picture to order note (5.48s)

### OrdersTest ([view source](https://github.com/CBCMoving/cbc_application/blob/master/tests/OrdersTest.php)):
✔ OrdersTest: Allowed methods to update order (7.48s)

✔ OrdersTest: Developer token to update order (7.55s)

✔ OrdersTest: Unauthorized access to update order (7.14s)

✔ OrdersTest: Authorized access to update order (8.55s)

✔ OrdersTest: Access to another order (8.81s)

✔ OrdersTest: Bad validation update order status (8.87s)

✔ OrdersTest: Successful update order status (11.43s)

### OrderNotesTest ([view source](https://github.com/CBCMoving/cbc_application/blob/master/tests/OrderNotesTest.php)):
✔ OrderNotesTest: Allowed methods to create order note (7.37s)

✔ OrderNotesTest: Allowed methods to attach picture to note (6.98s)

✔ OrderNotesTest: Developer token to create order note (8.02s)

✔ OrderNotesTest: Developer token to attach picture to note (7.37s)

✔ OrderNotesTest: Unauthorized access to create order note (7.20s)

✔ OrderNotesTest: Unauthorized access to attach picture to note (6.62s)

✔ OrderNotesTest: Authorized access to create order note (8.54s)

✔ OrderNotesTest: Authorized access to attach picture to note (8.49s)

✔ OrderNotesTest: Access to another order note (8.54s)

✔ OrderNotesTest: Access to another attach pictures to order note (7.59s)

✔ OrderNotesTest: Bad validation to create order note (8.10s)

✔ OrderNotesTest: Successful create order note (8.36s)

✔ OrderNotesTest: Bad validation to attach picture to route note (8.65s)

✔ OrderNotesTest: Successful validate to attach picture to order note (7.77s)

### OrderSurveyTest ([view source](https://github.com/CBCMoving/cbc_application/blob/master/tests/OrderSurveyTest.php)):
✔ OrderSurveyTest: Allowed methods to create survey (7.47s)

✔ OrderSurveyTest: Allowed methods to attach signature to survey (7.47s)

✔ OrderSurveyTest: Developer token to create survey (6.58s)

✔ OrderSurveyTest: Developer token to attach signature to survey (7.33s)

✔ OrderSurveyTest: Unauthorized access to create survey (7.06s)

✔ OrderSurveyTest: Unauthorized access to attach signature to survey (6.98s)

✔ OrderSurveyTest: Authorized access to create survey (8.89s)

✔ OrderSurveyTest: Authorized access to attach signature to note (8.36s)

✔ OrderSurveyTest: Access to another order (8.68s)

✔ OrderSurveyTest: Access to another attach signature to survey (8.21s)

✔ OrderSurveyTest: Required fields to create survey (8.42s)

✔ OrderSurveyTest: Bad validation to create survey (9.24s)

✔ OrderSurveyTest: Items validations (11.11s)

✔ OrderSurveyTest: Successful create survey (9.91s)

✔ OrderSurveyTest: Bad validation to attach signature to survey (8.04s)

✔ OrderSurveyTest: Successful validate to attach signature to survey (7.82s)

### CallsTest ([view source](https://github.com/CBCMoving/cbc_application/blob/master/tests/CallsTest.php)):
✔ CallsTest: Allowed methods to create call (7.10s)

✔ CallsTest: Developer token to create call (6.52s)

✔ CallsTest: Unauthorized access to create call (7.97s)

✔ CallsTest: Authorized access to create call (8.36s)

✔ CallsTest: Access to another order (8.86s)

✔ CallsTest: Required fields to create call (7.97s)

✔ CallsTest: Bad validation to create call (10.20s)

✔ CallsTest: Successful create call (9.00s)

*Time: 11.28 minutes, Memory: 82.00MB*

*OK (84 tests, 2196 assertions)*