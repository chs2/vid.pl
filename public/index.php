<?php
spl_autoload_register(function ($class) {
	include '../src/' . str_replace('\\', '/', $class) . '.php';
});	

header('Content-Type: application/json');

try {
	$request = array_filter(explode('/', trim($_SERVER['PATH_INFO'], '/')));

	if (empty($request)) {
		throw new \Exception('Bad Request', 400);
	}

	$objectType = ucfirst($request[0]);

	$controllerName = 'Controller\\' . $objectType;

	if (!class_exists($controllerName)) {
		throw new \Exception('Bad Request', 400);
	}

	$controller = new $controllerName;
	$actionName = strtolower($_SERVER['REQUEST_METHOD']);

	if (!method_exists($controller, $actionName)) {
		throw new \Exception('Method Not Allowed', 405);
	}

	echo json_encode($controller -> $actionName($request));
} catch (\Exception $e) {
	header('HTTP/1.0 ' . $e -> getCode() . ' ' . $e -> getMessage());
	echo json_encode(['code' => $e -> getCode(), 'message' => $e -> getMessage(), ]);
	exit;
}
