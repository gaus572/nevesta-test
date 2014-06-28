<?

require_once MODELROOT.'gallery.php';

class Controller_Gallery {

	public function index () {
		$gallery = new Model_Gallery;

		// Подсчитываем данные для пагинации
		$limit = 20;
		$count_rows = $gallery->get_images_count($_GET);
		$count_pages = ceil($count_rows / $limit);
		$page = (isset($_GET['p']) && $_GET['p']) ? $_GET['p'] : 1;
		$ofset = ($page-1) ? (($page-1)*$limit-1) : 0;

		$tags = $gallery->get_tags();
		$images = $gallery->get_page_images($_GET, $limit, $ofset);
		if (!empty($images)) {
			$image_ids = array_keys($images);
			$image_tags = $gallery->get_image_tags($image_ids);
			$image_likes = $gallery->get_image_likes($image_ids);
			$image_users = $gallery->get_image_users($images);
		}
		
		$URL = new Url;
		include VIEWROOT.'template.php';
	}
}

?>