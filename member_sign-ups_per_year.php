<?php

require_once 'vendor/autoload.php';
require_once 'generated-reversed-database/generated-conf/config.php';

// Determine range
$firstMember = MembersQuery::create()
    ->orderByJoinedDate()
    ->findOne();
$firstYear = $firstMember->getJoinedDate()->format('Y');

$lastMember = MembersQuery::create()
    ->orderByJoinedDate('desc')
    ->findOne();
$lastYear = $lastMember->getJoinedDate()->format('Y');

// Build empty array
$data = array();
for ($i = $firstYear; $i <= $lastYear; $i++) {
    $data[$i] = 0;
}

// Populate array
$members = MembersQuery::create()->find();
foreach($members as $member) {
    $year = $member->getJoinedDate()->format('Y');
    if (!array_key_exists($year, $data)) {
        $data[$year] = 0;
    }
    $data[$year]++;
}

// Format results
$results = array(
    'cols' => array(
        array(
            'label' => 'Year',
            'type' => 'string',
        ),
        array(
            'label' => 'Sign-ups',
            'type' => 'number',
        ),
    ),
    'rows' => array(),
);

foreach ($data as $year => $signups) {
    $results['rows'][] = array(
        'c' => array(
            array(
                'v' => $year,
            ),
            array(
                'v' => $signups,
            ),
        ),
    );
}

// Return results
echo json_encode($results);
