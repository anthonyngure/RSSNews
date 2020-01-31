<?php
	/**
	 * Created by PhpStorm.
	 * User: Tosh
	 * Date: 28/08/2017
	 * Time: 11:14
	 */
	
	require_once 'db_config.php';
	
	if (!isset($_GET['category'])) {
		exit(403);
	}
	
	$category = $_GET['category'];
	$perPage = $_GET['perPage'];
	$before = $_GET['before'];
	$after = $_GET['after'];
	$recent = $_GET['recent'] == 'true';
	
	$items = array();
	
	$whereClause = "WHERE category = '" . $category . "'";
	$order = '';
	
	if ($recent || ($after == 0 && $before == 0)) {
		if ($after != 0) {
			$whereClause = $whereClause . ' AND id > ' . $after;
			$order = 'asc';
		} else {
			$order = 'desc';
		}
	} else {
		
		$whereClause = $whereClause . ' AND id < ' . $before;
		$order = 'desc';
	}
	
	$query = "SELECT * FROM news_items $whereClause ORDER BY id $order LIMIT $perPage";
	
	//echo $query;
	
	$result = mysqli_query($link, $query);
	if ($result) {
		while ($row = mysqli_fetch_assoc($result)) {
			array_push($items, $row);
		}
		
		if ($recent) {
			$maxId = getMaxId($items);
			if ($before == 0) {
				$minId = getMinId($items);
			} else {
				$minId = $before;
			}
		} else {
			if ($after == 0) {
				$maxId = getMaxId($items);
			} else {
				$maxId = $after;
			}
			$minId = getMinId($items);
		}
		
		$response = [
			'meta' => [
				'cursors' => [
					'count'  => (int)$result->num_rows,
					'before' => (int)$minId,
					'after'  => (int)$maxId,
				],
			],
			'data' => $items,
		];
		
		$result->free();
		
		header('Content-Type: application/json');
		echo json_encode($response);
	} else {
		die(mysqli_error($link));
		//echo "An error occurred";
	}
	
	function getMaxId(array $items)
	{
		$max = 0;
		foreach ($items as $item) {
			if ($item['id'] > $max) {
				$max = $item['id'];
			}
		}
		
		return $max;
		
	}
	
	function getMinId(array $items)
	{
		$min = 0;
		foreach ($items as $item) {
			if ($item['id'] < $min) {
				$min = $item['id'];
			}
		}
		
		return $min;
	}
	