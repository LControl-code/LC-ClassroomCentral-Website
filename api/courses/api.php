<?php
include_once __DIR__ . "/../config/database.php";
$client = mongodbConnect();

$collection = $client->coursesDB->courses;

$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$limit = filter_input(INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_INT);
$categories = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
$instructor = filter_input(INPUT_GET, 'instructor', FILTER_SANITIZE_STRING);
$components = filter_input(INPUT_GET, 'component', FILTER_SANITIZE_STRING);

list($page, $limit, $categories, $filter) = validateInput($page, $limit, $categories, $search, $instructor);

if ($page == null) {
  http_response_code(400);
  return;
}
$totalCount = $collection->countDocuments($filter);
$skip = ($page - 1) * $limit;

if ($skip >= $totalCount && $totalCount != 0) {
  echo "Page number is out of range.";
  return;
}


$options = ['sort' => ['createdAt' => -1], 'limit' => $limit, 'skip' => $skip, 'projection' => ['_id' => 0]];

// Only set the projection if the components field is not empty
if (!empty($components)) {
  $projection = array_fill_keys(explode(',', $components), 1);
  $options['projection'] = array_merge(['_id' => 0], $projection);
}

$courses = $collection->find($filter, $options);
$coursesArray = iterator_to_array($courses);

$explainCommand = [
  'find' => $collection->getCollectionName(),
  'sort' => ['createdAt' => -1],
  'limit' => $limit,
  'skip' => $skip,
  'projection' => $options['projection'],
];

// Only include the 'filter' field if the $filter array is not empty
if (!empty($filter)) {
  $explainCommand['filter'] = $filter;
}

$command = new MongoDB\Driver\Command([
  'explain' => $explainCommand,
  'verbosity' => 'executionStats'
]);

$explain = $collection->getManager()->executeCommand($collection->getDatabaseName(), $command)->toArray()[0];



// Always include these components
$response = [
  'totalCourses' => $totalCount,
  'debug' => [
    'page' => $page,
    'limit' => $limit,
    'skip' => $skip,
    'categories' => $categories ?? 'any',
    'filter' => empty($filter) ? 'none' : $filter,
    'queryPerformance' => $explain
  ],
  'courses' => $coursesArray
];


header('Content-Type: application/json');
echo json_encode($response);


function validateInput($page, $limit, $categories, $search, $instructor)
{
  $filter = [];

  $page = $page ?? 1;
  $limit = $limit ?? 10;
  $limit = min($limit, 100);

  if ($categories != null && !empty($categories)) {
    $categories = explode(',', $categories);

    $allCategories = [
      "linguistics",
      "ethicalHacking",
      "dataScience",
      "coding",
      "others",
      "design",
      "digitalMarketing"
    ];

    foreach ($categories as $category) {
      if (!in_array($category, $allCategories)) {
        echo "Invalid category: $category. <br>Valid categories are: " . implode(', ', $allCategories);
        return null;
      }
    }

    $filter['courseInfo.category'] = ['$in' => $categories];
  }

  if ($search != null && !empty($search)) {
    $filter['$text'] = ['$search' => $search];
  }
  if ($instructor != null && !empty($instructor)) {
    $filter['instructor.name'] = $instructor;
  }

  $page = (int) $page;
  $limit = (int) $limit;

  return [$page, $limit, $categories, $filter];
}
