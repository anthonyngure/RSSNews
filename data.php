<?php
/**
 * Created by PhpStorm.
 * User: Tosh
 * Date: 28/08/2017
 * Time: 11:14
 */

require_once 'db_config.php';
require_once 'errors.php';

if (!isset($_GET['category'])) {
    badRequest('category is required');
}

$category = $_GET['category'];
$perPage = isset($_GET['perPage']) ? $_GET['perPage'] : 10;
$before = isset($_GET['before']) ? $_GET['before'] : 0;
$after = isset($_GET['after']) ? $_GET['after'] : 0;

$items = array();

$whereClause = "WHERE category = '" . $category . "'";
$order = '';

if ($after != 0 && $before != 0) {
    badRequest('Invalid cursors: both after and before can not be more than 0');
}

if ($after != 0 && $before == 0) {
    //Loading data that is added after what the user is seeing
    $whereClause = $whereClause . ' AND id > ' . $after;
    $order = 'asc';

} else if ($before != 0 && $after == 0) {
    //Loading data added before what the user is seeing
    $whereClause = $whereClause . ' AND id > ' . $before;
    $order = 'desc';
} else {
    //This is definitely an initial load where both before and after are zero
    //So we return a bigger size for the first page
    $perPage = $perPage * 2;
    $order = 'desc';
}

if (($after == 0 && $before == 0)) {
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
    $result->free();
    header('Content-Type: application/json');
    echo json_encode($items);
} else {
    //die(mysqli_error($link));
    //echo "An error occurred";
    badRequest('An error occurred');
}
