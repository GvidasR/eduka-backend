<?php

namespace App\Tests\Service;

use App\Service\Answer;
use App\Service\AnswersFormatter;
use PHPUnit\Framework\TestCase;

class AnswersFormatterTest extends TestCase
{
    private $testCase = '[{"correct":1,"answer":"Test"}]';

    public function testFormatter()
    {
        $test = json_decode($this->testCase);
        $fromJsonObject = AnswersFormatter::fromObjectArray($test);
        $this->assertFormatted($fromJsonObject);

        $toString = AnswersFormatter::toString($fromJsonObject);
        $this->assertEquals('*Test',$toString);

        $toArray = AnswersFormatter::toArray($toString);
        $this->assertFormatted($toArray);

    }

    private function assertFormatted(array $answers) {
        $this->assertIsArray($answers);
        $this->assertInstanceOf(Answer::class,$answers[0]);
    }
}
