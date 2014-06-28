<?

class Model_Gallery {
	public function __construct () {
		$this->DB = new Db;
	}

	// Получаем список всех тегов
	public function get_tags () {
		return $tags = $this->DB->sql('SELECT `id`, `name` FROM `tags` ORDER BY `name`')
			->get_all('id', 'name');
	}

	// Получаем теги для списка изображений
	public function get_image_tags ($ids = array()) {
		if (empty($ids)) return array();

		$tags = $this->DB->sql(
			"SELECT `image_id`, `tag_id`
			FROM `image_tags`
			WHERE `image_id` IN(".implode(',', $ids).")"
		)->get_all();
		foreach ($tags as $tag) {
			$image_tags[$tag['image_id']][$tag['tag_id']] = $tag['tag_id'];
		}

		return $image_tags;
	}

	// Получить лайки для списка изображений
	public function get_image_likes ($ids = array()) {
		if (empty($ids)) return array();

		$likes = $this->DB->sql(
			"SELECT `likes`.`image_id` as 'image_id', `users`.`user_name` as 'user_name'
			FROM `likes`, `users`
			WHERE `likes`.`user_id` = `users`.`id` AND `likes`.`image_id` IN(".implode(',', $ids).")"
		)->get_all();
		foreach ($likes as $like) {
			$image_likes[$like['image_id']][] = $like['user_name'];
		}

		return $image_likes;
	}

	// Получить пользователей для изображений
	public function get_image_users ($images = array()) {
		if (empty($images)) return array();

		foreach ($images as $image) {
			$ids[$image['user_id']] = $image['user_id'];
		}
		if (!empty($ids)) $ids = implode(',', $ids);
		return $this->DB->sql("SELECT `id`, `user_name` FROM `users` WHERE `id` IN(".$ids.")")
			->get_all('id', 'user_name');
	}

	// Сформировать условия фильтра по тегам
	protected function get_image_tag_filter ($filter = array()) {
		$sql_query = '';
		$sep = '';
		// Фильтруем изображения по включонным тегам
		if (!empty($filter['tag'])) foreach ($filter['tag'] as $tag_id) {
			$tag_id = (int) $tag_id;
			$sql_query .= $sep."`images`.`id` IN (SELECT `image_id` FROM `image_tags` WHERE `tag_id` = {$tag_id})";
			$sep = ' AND ';
		}
		// Фильтруем изображения по тегам исключениям
		if (!empty($filter['notag'])) foreach ($filter['notag'] as $tag_id) {
			$tag_id = (int) $tag_id;
			$sql_query .= $sep."`images`.`id` NOT IN (SELECT `image_id` FROM `image_tags` WHERE `tag_id` = {$tag_id})";
			$sep = ' AND ';
		}
		return $sql_query;
	}

	// Получить кол-во изображений по фильтру
	public function get_images_count ($filter = array()) {
		$sql_where = $this->get_image_tag_filter($filter);
		if ($sql_where) $sql_where = 'WHERE '.$sql_where;
		return $this->DB->sql("SELECT COUNT(`images`.`id`) FROM `images` ".$sql_where)
			->get_one();
	}

	// Получить пачку изображений
	public function get_page_images ($filter, $limit, $ofset) {
		$sql_where = $this->get_image_tag_filter($filter);
		if ($sql_where) $sql_where = ' WHERE '.$sql_where;

		// Сортировка
		if (isset($filter['order']) && $filter['order'] == 'like_count') $sql_order = ' ORDER BY `like_count` DESC ';
		else $sql_order = ' ORDER BY `date` DESC ';

		return $this->DB->sql(
			"SELECT  `id`, `file`, `user_id`, `date`, `like_count`
			FROM `images`".
			$sql_where.
			$sql_order.
			" LIMIT {$ofset}, {$limit}"
		)->get_all('id');
	}
}

?>