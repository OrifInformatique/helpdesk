<?php
$users = [
    [
        'id' => 1, 
        'name' => 'Billy', 
        'presences' => [100,100,1,1,0,0,0,0,0,0,0,0,100,100,100,100,0,0,0,0]
    ],
    [
        'id' => 2, 
        'name' => 'Jimmy', 
        'presences' => [100,100,1,1,0,0,0,0,0,0,100,100,1,1,1,1,0,0,0,0]
    ],
    [
        'id' => 3, 
        'name' => 'John', 
        'presences' => [100,0,1,1,100,100,100,100,100,100,100,100,1,1,1,1,0,0,0,0]
    ],
    [
        'id' => 4, 
        'name' => 'Jane', 
        'presences' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
    ],
];

foreach ($users as $user) {
    $score = array_sum($user['presences']);
    echo $user['name'] . " à un score de " . $score . "\n";
}

echo "\n";
// No logic for $score index of $users; demonstrate sorting by total presence score
usort($users, function($a, $b) {
    return array_sum($a['presences']) <=> array_sum($b['presences']);
});
foreach ($users as $user) {
    echo $user['name'] . " total: " . array_sum($user['presences']) . "\n";
}
