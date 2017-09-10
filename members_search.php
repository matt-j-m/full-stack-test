<?php

require_once 'vendor/autoload.php';
require_once 'generated-reversed-database/generated-conf/config.php';

$searchFields = array('firstname', 'surname', 'email');

// Inititate search
$members = MembersQuery::create();

// Build conditions
$conditionsCount = 0;
foreach ($searchFields as $field) {
    $include = false;
    if ((isset($_GET[$field])) && (!empty($_GET[$field]))) {
        $include = true;
        $value = $_GET[$field];
    }
    if ((isset($_POST[$field])) && (!empty($_POST[$field]))) {
        $include = true;
        $value = $_POST[$field];
    }
    if ($include) {
        $conditionsCount++;
        $members->condition('cond' . $conditionsCount, 'members.' . $field . ' LIKE ?', '%' . $value . '%');
    }
}
if ($conditionsCount > 0) {
    $conditions = array();
    for ($i = 1; $i <= $conditionsCount; $i++) {
        $conditions[] = 'cond' . $i;
    }
    $members->where($conditions, 'and');
}

// Execute search
$members->find();

// Build results array
$results = array();
foreach ($members as $member) {
    $results[] = array(
        'id' => (string)$member->getId(),
        'firstname' => $member->getFirstname(),
        'surname' => $member->getSurname(),
        'email' => $member->getEmail(),
        'gender' => $member->getGender(),
        'joined_date' => $member->getJoinedDate()->format('Y-m-d'),
    );
}

// Return results
echo json_encode($results);
