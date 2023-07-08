<?php

require_once 'controllers/template.controller.php';
require_once 'controllers/user.controller.php';
require_once 'controllers/categories.controller.php';
require_once 'controllers/product.controller.php';
require_once 'controllers/taxdis.controller.php';
require_once 'controllers/discount.controller.php';
require_once 'controllers/payments.controller.php';
require_once 'controllers/supplier.controller.php';
require_once 'controllers/expenses.controller.php';
require_once 'controllers/orders.controller.php';
require_once 'controllers/returns.controller.php';
require_once 'controllers/notifications.controller.php';
require_once 'controllers/activitylog.controller.php';


require_once 'models/user.models.php';
require_once 'models/categories.models.php';
require_once 'models/product.model.php';
require_once 'models/taxdis.models.php';
require_once 'models/discount.models.php';
require_once 'models/payment.model.php';
require_once 'models/supplier.model.php';
require_once 'models/expenses.model.php';
require_once 'models/orders.model.php';
require_once 'models/notifications.model.php';
require_once 'models/activitylog.model.php';

$template= new templateController();
$template-> ctrTemplate();
