<?php

define('DOCROOT', dirname(__FILE__).'/');

require_once DOCROOT.'config/db.php';
require_once DOCROOT.'classes/Db.php';
require_once DOCROOT.'classes/Url.php';
$DB = new Db;
$URL = new Url;

// Получаем список всех тегов
$tags = $DB->sql('SELECT `id`, `name` FROM `tags` ORDER BY `name`')
	->get_all('id', 'name');
// формируем sql запрос
$sql_query ="SELECT |fields| FROM `images`";
$sep_where = '';
$where = ' WHERE ';
// Фильтруем изображения по включонным тегам
if (!empty($_GET['tag'])) foreach ($_GET['tag'] as $tag_id) {
	$sql_query .= $where.$sep_where."`id` IN (SELECT `image_id` FROM `image_tags` WHERE `tag_id` = {$tag_id})";
	$sep_where = ' AND ';
	$where = '';
}
// Фильтруем изображения по тегам исключениям
if (!empty($_GET['notag'])) foreach ($_GET['notag'] as $tag_id) {
	$sql_query .= $where.$sep_where."`id` NOT IN (SELECT `image_id` FROM `image_tags` WHERE `tag_id` = {$tag_id})";
	$sep_where = ' AND ';
	$where = '';
}
// Тип сортировки
if (isset($_GET['order']) && $_GET['order'] == 'like_count') $sql_order = '`like_count` DESC';
else $sql_order = '`date` DESC';

// Подсчитываем данные для пагинации
$count_rows = $DB->sql(str_replace('|fields|', 'COUNT(`id`)', $sql_query))
	->get_one();
$count_pages = ceil($count_rows / 20);
$page = (isset($_GET['p']) && $_GET['p']) ? $_GET['p'] : 1;
$start = ($page-1) ? (($page-1)*20-1) : 0;
// получаем изображения текущей страницы
$sql = $sql_query.' ORDER BY '.$sql_order.' LIMIT '.$start.', 20';
$images = $DB->sql(str_replace('|fields|', '`id`, `file`, `user_id`, `date`, `like_count`', $sql))
	->get_all('id');

$list_img_id = array_keys($images);

if (!empty($list_img_id)) {
	$list_img_id = implode(',', $list_img_id);
	// Получаем Лайки для изображений
	$likes = $DB->sql("SELECT `image_id`, (SELECT `user_name` FROM `users` WHERE `likes`.`user_id` = `id`) as 'user_name' FROM `likes` WHERE `image_id` IN(".$list_img_id.")")->get_all();
	foreach ($likes as $like) {
		$images[$like['image_id']]['likes'][] = $like['user_name'];
	}
	// Получаем теги для изображений
	$image_tags = $DB->sql("SELECT `image_id`, `tag_id` FROM `image_tags` WHERE `image_id` IN(".$list_img_id.")")->get_all();
	foreach ($image_tags as $image_tag) {
		$images[$image_tag['image_id']]['tags'][] = $tags[$image_tag['tag_id']];
	}
	// получаем пользователей для изображений
	foreach ($images as $image) {
		$list_img_user_id[] = $image['user_id'];
	}
	$list_img_user_id = implode(',', $list_img_user_id);
	$image_users = $DB->sql("SELECT `id`, `user_name` FROM `users` WHERE `id` IN(".$list_img_user_id.")")->get_all('id', 'user_name');
}

include DOCROOT.'views/template.php';

?>