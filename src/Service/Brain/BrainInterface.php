<?php

namespace App\Service\Brain;

interface BrainInterface {
    /**
     * @param string $query
     * @return string
     */
    public function reflection(string $query): string;

    /**
     * @param string $question
     * @param string $answer
     * @return string
     */
    public function answer(string $question, string $answer): string;

    /**
     * @param string $query
     * @return string
     */
    public function reReflection(string $query): string;
}
