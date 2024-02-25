<?php
// include_once __DIR__ . "/../config/database.php";
// $client = mongodbConnect();

// // Select the "coursesDB" database and the "courses" collection
// $db = $client->coursesDB;
// $collection = $db->courses;

// // Define the aggregation pipeline
// $pipeline = [
//   [
//     '$group' => [
//       '_id' => '$udemyCourseId',
//       'doc' => ['$first' => '$$ROOT']
//     ]
//   ],
//   [
//     '$replaceRoot' => ['newRoot' => '$doc']
//   ],
//   [
//     '$out' => 'courses'
//   ]
// ];

// // Execute the aggregation pipeline
// $db->command([
//   'aggregate' => 'courses',
//   'pipeline' => $pipeline,
//   'cursor' => new stdClass
// ]);

// // Create a unique index on the udemyCourseId field to prevent future duplicates
// $collection->createIndex(['udemyCourseId' => 1], ['unique' => true]);
