<?php
spl_autoload_register(function ($class) {
    include '../entity/' . $class . '.php';
});	

header('Content-Type: application/json');

$request = array_filter(explode('/', trim($_SERVER['PATH_INFO'], '/')));

if (empty($request)) {
	header('HTTP/1.0 400 Bad Request');
	echo json_encode(['code' => 400, 'message' => 'Bad Request', ]);
	exit;
}

if (!class_exists($request[0])) {
	header('HTTP/1.0 400 Bad Request');
	echo json_encode(['code' => 400, 'message' => 'Bad Request', ]);
	exit;
}

$entityClass = $request[0];

$controller = '../controller/' . strtolower($_SERVER['REQUEST_METHOD']) . '_' . $entityClass . '.php';

if (!file_exists($controller)) {
	header('HTTP/1.0 400 Bad Request');
	echo json_encode(['code' => 400, 'message' => 'Bad Request', ]);
	exit;
}

try {
	echo json_encode(include $controller);
} catch (\Exception $e) {
	header('HTTP/1.0 ' . $e -> getCode() . ' ' . $e -> getMessage());
	echo json_encode(['code' => $e -> getCode(), 'message' => $e -> getMessage(), ]);
	exit;
}
