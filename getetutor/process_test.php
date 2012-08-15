<?php

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename = Senabi-Data.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Lead ID', 'Lead Pack', 'Phone', 'Area Code', 'First Name', 'Last Name', 'Street Address 1', 'Street Address 2', 'City', 'County', 'State', 'Zip'));

?>