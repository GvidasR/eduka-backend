<?php
namespace App\Service;

class Answer
{
    private $correct = false;
    private $answer = "";

    public function __construct($answer, $correct = false)
    {
        $this->answer = $answer;
        $this->correct = $correct;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCorrect(): bool
    {
        return $this->correct;
    }

    /**
     * @param bool $correct
     */
    public function setCorrect(bool $correct): void
    {
        $this->correct = $correct;
    }

    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     */
    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }
}
