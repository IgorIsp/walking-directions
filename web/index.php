<?php

namespace WalkingDirections\Web;

use WalkingDirections\Instruction;
use WalkingDirections\PersonDirection;
use WalkingDirections\Point;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$worstPoints = [];
for ($i = 1; $i <= 100; $i++) {
    $destinationPoints = [];
    $wholeCommands = [
        '2.6762 | 75.2811 | start -45.0 | walk 40 | turn 40.0 | walk 60',
        '22.6742 | 15.2811 | start -40.0 | walk 30 | turn 50.0 | walk 50',
        '21.672 | 25.2811 | start 35.0 | walk 35 | turn -40.0 | walk 12',
        '521.452 | 125.581 | start 75.0 | walk 350',
        ".452 | 125.581 | start 75.0 | walk 350",
        '213.562 | 250.2815 | start -85.0 | walk 315 | turn 90.0 | walk 112 | turn -52.0 | walk 712',
    ];
    foreach ($wholeCommands as $wholeCommand) {
        $commands = Instruction::generateCommands($wholeCommand);
        $personDirection = new PersonDirection($commands, new Point());
        $walked = $personDirection->walkToEndPoint();
        if ($walked) {
            $destinationPoints[] = $personDirection->getCurrentCoordinates();
        }
    }
    $avrPoint = Point::getAveragePoint($destinationPoints);
    $worstPoint = Point::getWorstPoint($destinationPoints, $avrPoint);
    $worstPoints[] = $worstPoint;
    echo 'X: ' . $worstPoint->x .' Y: ' . $worstPoint->y . PHP_EOL;
}

