<?php
	/**
	 * Created by PhpStorm.
	 * User: Tosh
	 * Date: 28/08/2017
	 * Time: 11:14
	 */

	require_once 'db_config.php';
	require_once('OpenGraph.php');
	include_once 'Rss_Item.php';
	include_once 'Rss_Parser.php';

	if (!isset($_GET['category'])) {
		exit(403);
	}

	$category = $_GET['category'];
	$url = '';
	switch ($category) {
		case 'europe_news':
			$url = 'http://rss.cnn.com/rss/edition_europe.rss';
			break;
		case 'asia_news':
			$url = 'http://rss.cnn.com/rss/edition_asia.rss';
			break;
		case 'business_reuters':
			$url = 'http://feeds.reuters.com/reuters/businessNews';
			break;
		case 'business_nasdaq':
			$url = 'http://articlefeeds.nasdaq.com/nasdaq/categories?category=Business&amp;format=xml';
			break;
		case 'technology_cnn':
			$url = 'http://rss.cnn.com/rss/edition_technology.rss';
			break;
        case 'technology_reuters':
        case 'technology_uk':
			$url = 'http://feeds.reuters.com/reuters/technologyNews';
			break;
        case 'entertainment_uk':
			$url = 'http://feeds.reuters.com/reuters/UKEntertainment';
			break;
		case 'entertainment_cnn':
			$url = 'http://rss.cnn.com/rss/edition_entertainment.rss';
			break;
		case 'entertainment_reuters':
			$url = 'http://feeds.reuters.com/reuters/entertainment';
			break;
	}

	$rss = new Rss_Parser();
	$rss->load($url);

	$items = array();

	foreach ($rss->getItems() as $item) {
		//echo '<a target="_blank" href="' . $item->getLink() . '">' . $item->getTitle() . '</a><br />';
		$graph = OpenGraph::fetch($item->getLink());
		if ($graph) {
			$itemArray = array();
			$columns = 'category';
			$values = '"' . $category . '"';
			foreach ($graph as $key => $value) {
				//echo "$key => $value" . '<br />';
				//array_push($itemArray, [$key => $value]);
				$itemArray[$key] = $value;
				$columns = $columns . ',' . $key;
				$values = $values . ',"' . mysqli_real_escape_string($link, $value) . '"';
			}

			$validColumns = str_replace(':', '_', $columns);
			$query = "INSERT IGNORE INTO news_items ($validColumns) VALUES ($values)";
			$result = mysqli_query($link, $query);
			if (!$result) {
				die(mysqli_error($link));
			}
			array_push($items, $itemArray);
		}
		break;
	}

	header('Content-Type: application/json');
	echo json_encode($items);
