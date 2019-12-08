<?php
namespace App\Service;

class AnswersFormatter
{
    public static function fromObjectArray(array $answers): array{
        $formattedArray = [];
        foreach($answers as $answer){
            $formattedArray[] = new Answer($answer->answer,$answer->correct);
        }
        return $formattedArray;
    }
    public static function toString(?array $answers): string
    {
        $answersStrings = [];
        /** @var Answer $answer */
        foreach($answers as $answer) {
            $answersStrings[] = ($answer->isCorrect()?"*":"").$answer->getAnswer();
        }
        return implode(PHP_EOL,$answersStrings);
    }

    public static function toArray(?string $answers): array
    {
        $formattedAnswers = [];
        if(!empty($answers)) {
            $answersList = explode(PHP_EOL, $answers);
            foreach ($answersList as $answer) {
                $re = '/^(\*)?(.+)$/m';
                preg_match_all($re, $answer, $matches, PREG_SET_ORDER, 0);
                $matches = $matches[0];
                $answerCorrect = !empty($matches[1]) ? true : false;
                $answerText = $matches[2];
                $formattedAnswers[] = new Answer($answerText, $answerCorrect);
            }
        }
        return $formattedAnswers;
    }
}
