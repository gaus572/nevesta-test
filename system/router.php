<?

$uri = preg_replace("/\\?.*$/", '', $_SERVER['REQUEST_URI']);
$params = explode('/', $uri);
// значения по умолчанию
if (!isset($params[1]) || !$params[1]) $params[1] = 'gallery'; // Controller
if (!isset($params[2]) || !$params[2]) $params[2] = 'index'; // Method

// Подключаем контроллер $params[1] и запускаем метод $params[2]
$con_split = explode( '_', strtolower($params[1]) );
$con_file = CONTROLLERROOT.implode('/', $con_split).'.php';
if (!file_exists($con_file)) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
require_once $con_file;
$con_class = 'Controller_'.implode( '_', array_map('ucfirst', $con_split) );
if (!class_exists($con_class)) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$controller = new $con_class;
$con_method = strtolower($params[2]);
if (!method_exists($controller, $con_method)) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$controller->$con_method();

?>