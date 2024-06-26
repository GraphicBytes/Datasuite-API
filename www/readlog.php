<?php

define('ROWS_PER_PAGE', 5000); // Adjust as needed
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

function parseLogLine($line)
{
    list($datetime, $message, $client_ip) = explode("|", $line);

    $datetime = trim($datetime);
    $message = trim($message);
    $client_ip = trim($client_ip, 'Client IP:');

    return [
        'datetime' => $datetime,
        'message' => $message,
        'ip' => $client_ip,
    ];
}

// Read the log file
$logFile = 'restrictions_parsed.log'; // Path to your log file
$logLines = file($logFile, FILE_IGNORE_NEW_LINES);

// Group data by IP and message
$data = [];

foreach ($logLines as $line) {
    $parsed = parseLogLine($line);

    $ip = $parsed['ip'];
    $message = $parsed['message'];
    $datetime = $parsed['datetime'];

    if (!isset($data[$ip])) {
        $data[$ip] = [];
    }

    if (!isset($data[$ip][$message])) {
        $data[$ip][$message] = [
            'count' => 0,
            'start_time' => $datetime,
            'end_time' => $datetime,
        ];
    }

    // Update the count
    $data[$ip][$message]['count'] += 1;

    // Update start and end times
    if ($datetime < $data[$ip][$message]['start_time']) {
        $data[$ip][$message]['start_time'] = $datetime;
    }
    if ($datetime > $data[$ip][$message]['end_time']) {
        $data[$ip][$message]['end_time'] = $datetime;
    }
}

$flatData = [];
foreach ($data as $ip => $messages) {
    if ($ip) {
        foreach ($messages as $message => $details) {
            $flatData[] = [
                'ip' => $ip,
                'message' => $message,
                'count' => $details['count'],
                'start_time' => $details['start_time'],
                'end_time' => $details['end_time'],
            ];
        }
    }
}

uasort($flatData, function ($a, $b) {
    return $b['count'] - $a['count'];
});

$startIndex = ($page - 1) * ROWS_PER_PAGE;
$endIndex = min($startIndex + ROWS_PER_PAGE, count($flatData));

// Slice the flatData array to get the current page's data
$pagedData = array_slice($flatData, $startIndex, ROWS_PER_PAGE);

$totalPages = ceil(count($flatData) / ROWS_PER_PAGE);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Log Analysis</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .pagination {
            margin: 20px 0;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: black;
        }

        .pagination a.active {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>Log Analysis</h1>
    <table>
        <thead>
            <tr>
                <th>Rows</th>
                <th>User IP</th>
                <th>Denied Request Reason</th>
                <th>Times IP attempted the same action in time range</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pagedData as $index => $entry): ?>
                <tr>
                    <td><?= $startIndex + $index + 1 ?></td>
                    <td><?= htmlspecialchars($entry['ip']) ?></td>
                    <td><?= htmlspecialchars($entry['message']) ?></td>
                    <td><?= $entry['count'] ?></td>
                    <td><?= htmlspecialchars($entry['start_time']) ?></td>
                    <td><?= htmlspecialchars($entry['end_time']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">Previous</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">Next</a>
        <?php endif; ?>
    </div>
</body>

</html>