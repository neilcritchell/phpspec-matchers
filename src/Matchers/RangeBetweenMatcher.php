<?php

namespace Karriere\PhpSpecMatchers\Matchers;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\Matcher;
use PhpSpec\Wrapper\DelayedCall;

class RangeBetweenMatcher implements Matcher
{
    /**
     * Checks if matcher supports provided subject and matcher name.
     *
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return bool
     */
    public function supports(string $name, $subject, array $arguments): bool
    {
        return $name === 'rangeBetween' && count($arguments) === 2 && $this->isNumericArray($arguments);
    }

    /**
     * Evaluates positive match.
     *
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @throws FailureException
     */
    public function positiveMatch(string $name, $subject, array $arguments) : ?DelayedCall
    {
        if (!$this->isInRange($arguments[0], $arguments[1], $subject)) {
            throw new FailureException(
                sprintf(
                    'the return value %d should be in range %d-%d',
                    $subject,
                    $arguments[0],
                    $arguments[1]
                )
            );
        }

        return null;
    }

    /**
     * Evaluates negative match.
     *
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @throws FailureException
     */
    public function negativeMatch(string $name, $subject, array $arguments) : ?DelayedCall
    {
        if ($this->isInRange($arguments[0], $arguments[1], $subject)) {
            throw new FailureException(
                sprintf(
                    'the return value %d should not be in range %d-%d',
                    $subject,
                    $arguments[0],
                    $arguments[1]
                )
            );
        }

        return null;
    }

    /**
     * Returns matcher priority.
     *
     * @return int
     */
    public function getPriority(): int
    {
        return 0;
    }

    /**
     * check if all values of an array are numeric.
     *
     * @param $array array of values to check
     *
     * @return bool true if all contained values are numeric
     */
    private function isNumericArray($array)
    {
        foreach ($array as $value) {
            if (!is_numeric($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * check if the given value is in range of min and max value.
     *
     * @param $min int|float the minimum value
     * @param $max int|float the maximum value
     * @param $value int|float the value to check
     *
     * @return bool true if the value is in the range
     */
    private function isInRange($min, $max, $value)
    {
        return $value >= $min && $value <= $max;
    }
}
