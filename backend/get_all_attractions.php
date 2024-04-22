<?php
require_once __DIR__ . '/rest/services/AttractionService.class.php';

$attraction_service = new AttractionService();
$attractions = $attraction_service-> get_all();
echo json_encode(['data'=> $attractions]);

