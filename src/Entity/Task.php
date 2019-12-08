<?php

namespace App\Entity;

use App\Service\AnswersFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tasks")
 * @ORM\Entity
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="question", type="text", nullable=true)
     */
    private $question;

    /**
     * @ORM\Column(name="answers", type="text", nullable=true)
     */
    private $answers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswers(): ?array
    {
        return AnswersFormatter::toArray($this->answers);
    }

    public function setAnswers(?array $answers): self
    {
        $this->answers = AnswersFormatter::toString($answers);

        return $this;
    }

}
