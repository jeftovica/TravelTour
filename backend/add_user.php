<?php
require_once __DIR__ . '/res/services/UserService.class.php';
$user_service = new UserService();
$user_service-> add_user();
