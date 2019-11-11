<?php

namespace WalkingDirections;

use Exception;

/**
 * Class Instruction
 * @property string $name
 * @property string $type
 * @property float $value
 * @package WalkingDirections
 */
class Instruction
{
    const MIN_OF_RANGE = -1000;
    const MAX_OF_RANGE = 1000;
    const MAX_COUNT = 25;

    const NAME_X = 'x';
    const NAME_Y = 'y';
    const NAME_START = 'start';
    const NAME_WALK = 'walk';
    const NAME_TURN = 'turn';

    const TYPE_NUMBER = 'number';
    const TYPE_METHOD = 'method';

    private $name;
    private $type;
    private $value;

    /** @var self[]  */
    private static $commands = [];

        /**
     * Instruction constructor.
     * @param $name
     * @param $type
     * @param $value
     * @throws Exception
     */
    public function __construct($name, $type, $value)
    {
        if ($value < self::MIN_OF_RANGE && $value > self::MAX_OF_RANGE) {
            throw new Exception("Value of variable '$name' out of range");
        }
        $this->name = $name;
        $this->type = $type;
        $this->value = round(trim($value), 4);
    }

    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * @param string $command
     * @param string $name
     * @throws Exception
     */
    private static function addInstructionByMethod(string $command, string $name): void
    {
        if (strpos($command, $name) !== false) {
            $valueAfterMethod = substr($command,
                strpos($command, $name) + strlen($name),
                strlen($command) - strlen($name));
            self::$commands[] = new self($name, self::TYPE_METHOD, $valueAfterMethod);
        }
    }

    /**
     * @param string $wholeCommand
     * @return self[]
     * @throws Exception
     */
    public static function generateCommands(string $wholeCommand): array
    {
        $commands = explode('|', $wholeCommand);
        for ($i = 0; $i < count($commands); $i++) {
            if ($i == 0) {
                self::$commands[] = new self(self::NAME_X, self::TYPE_NUMBER, $commands[$i]);
            }
            if ($i == 1) {
                self::$commands[] = new self(self::NAME_Y, self::TYPE_NUMBER, $commands[$i]);
            }
            self::addInstructionByMethod($commands[$i], self::NAME_START);
            self::addInstructionByMethod($commands[$i], self::NAME_TURN);
            self::addInstructionByMethod($commands[$i], self::NAME_WALK);
            if ($i + 1 >= self::MAX_COUNT) {
                break;
            }
        }
        return self::$commands;
    }

}