<?php

require_once 'vendor/autoload.php';
require_once 'generated-reversed-database/generated-conf/config.php';

// Build empty array
$data = array();
for ($i = 1; $i <= 12; $i++) {
    $dateObj = DateTime::createFromFormat('!m', $i);
    $month = $dateObj->format('F');
    $data[$month] = 0;
}

// Populate array
$members = MembersQuery::create()
    ->filterByJoinedDate(array('min' => $_GET['year'] . '-01-01', 'max' => $_GET['year'] . '-12-31'))
    ->find();
foreach($members as $member) {
    $month = $member->getJoinedDate()->format('F');
    $data[$month]++;
}

// Format results
$results = array(
    'cols' => array(
        array(
            'label' => 'Month',
            'type' => 'string',
        ),
        array(
            'label' => 'Sign-ups',
            'type' => 'number',
        ),
    ),
    'rows' => array(),
);

foreach ($data as $month => $signups) {
    $results['rows'][] = array(
        'c' => array(
            array(
                'v' => $month,
            ),
            array(
                'v' => $signups,
            ),
        ),
    );
}

// Return results
//echo json_encode($results);

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>FullStackTest - Customer Sign-ups per Month</title>
        <style>
            body {
                font-family: Helvetica, Arial, sans-serif;
            }
        </style>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = new google.visualization.DataTable(<?=json_encode($results)?>);

                var options = {
                    title: 'Customer Sign-ups per Month - <?=$_GET['year']?>',
                    hAxis: {title: 'Month',  titleTextStyle: {color: '#333'}},
                    vAxis: {minValue: 0}
                };

                var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            }
        </script>
    </head>
    <body>
        <p><a href='index.html'>Home</a></p>
        <div id="chart_div" style="width: 100%; height: 500px;"></div>
    </body>
</html>
