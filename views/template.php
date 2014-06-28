<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Галерея изображений</title>
	<link rel="stylesheet" type="text/css" href="/media/css/main.css">
	<script type="text/javascript" src="/media/js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="/media/js/main.js"></script>
</head>
<body>
	
	<div class="wrapper">

		<header>
			<p>Клик по тегу - отметить для поиска</p>
			<p>Повторный клик по тегу - отметить как исключение</p>
		</header>

		<div class="filter">
			
			<div class="tags-list">
				<? foreach ($tags as $id => $name) : ?>
					<span
						data-id="<?= $id ?>"
						class="<?
							if (!empty($_GET['tag']) && array_search($id, $_GET['tag']) !== false) echo 'active';
							if (!empty($_GET['notag']) && array_search($id, $_GET['notag']) !== false) echo 'exclude';
						?>"
					><?= $name ?></span>
				<? endforeach ?>
			</div>

			<div class="order-list">
				<span class="<?= isset($_GET['order']) && $_GET['order'] == 'like_count' ? 'active' : '' ?>" data-order="like_count">По количеству лайков</span>
				<span class="<?= !isset($_GET['order']) || $_GET['order'] == 'date' ? 'active' : '' ?>" data-order="date">По дате добавления</span>
			</div>

			<a class="filter-btn" href="#">Применить</a>

		</div>

		<div class="content">
			<div class="image-list">
				
				<? foreach ($images as $image_id => $image) : ?>
					<div class="image-block">
						<div class="image-info">
							<span class="image-user">
								<?= isset($image_users[$image['user_id']]) ? $image_users[$image['user_id']] : '' ?>
							</span>
							<span class="image-date">
								<?= date('d.m.Y', $image['date']) ?>
							</span>
						</div>
						<div class="img">
							<img src="<?= $image['file'] ?>">
							<div class="image-like">
								<a href="#"></a>
								<div class="like-block">
									<div class="like-list">
										<?= !empty($image_likes[$image_id]) ? implode(', ', $image_likes[$image_id]) : '' ?>
									</div>
									<span class="like-count">Понравилось: <b><?= $image['like_count'] ?></b> человек</span>
								</div>
							</div>
						</div>
						<div class="image-tags">
							<? if (!empty($image_tags[$image_id])) foreach ($image_tags[$image_id] as $tag_id) : ?>
								<span><?= $tags[$tag_id] ?></span>
							<? endforeach ?>
						</div>
					</div>
				<? endforeach ?>

				<? if (!count($images)) : ?>
					Фотографии не найдены
				<? endif ?>

				<? if ($count_pages > 1) : ?>
					<div class="pagination">
						<? if ($page == 1) : ?>
							<span>1</span>
						<? else : ?>
							<a href="<?= $URL->get_query(array('p'=>1)) ?>">1</a>
						<? endif ?>
						
						<? for ($i = 2; $i < $count_pages; $i++) : ?>
							<? if ($i < $page-4) : ?>
								<span>...</span>
								<? $i = $page-4 ?>
							<? endif ?>

							<? if ($page == $i) : ?>
								<span><?= $i ?></span>
							<? else : ?>
								<a href="<?= $URL->get_query(array('p'=>$i)) ?>"><?= $i ?></a>
							<? endif ?>

							<? if ($i > $page+3 && $i < $count_pages-1) : ?>
								<span>...</span>
								<? break ?>
							<? endif ?>
						<? endfor ?>

						<? if ($page == $count_pages) : ?>
							<span><?= $count_pages ?></span>
						<? else : ?>
							<a href="<?= $URL->get_query(array('p'=>$count_pages)) ?>"><?= $count_pages ?></a>
						<? endif ?>
					</div>
				<? endif ?>
			</div>
		</div>

		<footer></footer>

	</div>

</body>
</html>