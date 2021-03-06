<?php
spl_autoload_register(function ($class) {
	$filename = '../src/' . str_replace('\\', '/', $class) . '.php';

	if (file_exists($filename)) {
		include $filename;
	}
});	

try {
	if (!array_filter(
		explode(',', $_SERVER['HTTP_ACCEPT']),
		function ($accept) {
			return 0 === strpos($accept, 'application/json') || 0 === strpos($accept, '*/*');
		}
	)) {
		throw new Exception\Http406;
	}

	$request = array_filter(explode('/', trim($_SERVER['PATH_INFO'], '/')));

	if (empty($request)) {
		throw new Exception\Http400;
	}

	$objectType = ucfirst($request[0]);

	$controllerName = 'Controller\\' . $objectType;

	if (!class_exists($controllerName)) {
		throw new Exception\Http400;
	}

	$controller = new $controllerName;
	$actionName = strtolower($_SERVER['REQUEST_METHOD']);

	if (!method_exists($controller, $actionName)) {
		throw new Exception\Http405;
	}

	$conn = new PDO('sqlite:../data/vid-pl.db');
	$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$repositoryName = 'Repository\\' . $objectType;

	$controller -> setRepository(new $repositoryName($conn));

	$response = $controller -> $actionName($request);
} catch (Exception\Http $e) {
	$response = ['code' => $e -> getCode(), 'message' => $e -> getMessage(), ];

	if ($e -> getPrevious() instanceof \Exception) {
		$response['reason'] = [
			'code' => $e -> getPrevious() -> getCode(),
			'message' => $e -> getPrevious() -> getMessage(),
		];
	}
} catch (\Exception $e) {
	$response = ['code' => Exception\Http500::CODE, Exception\Http500::MESSAGE, ];

	if ($e -> getPrevious() instanceof \Exception) {
		$response['reason'] = [
			'code' => $e -> getPrevious() -> getCode(),
			'message' => $e -> getPrevious() -> getMessage(),
		];
	}
}

header('HTTP/1.0 ' . $response['code'] . ' ' . $response['message']);
header('Content-Type: application/json');
echo json_encode($response);

