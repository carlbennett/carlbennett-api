<?php

namespace CarlBennett\API\Libraries\Fun;

class Magic8Ball
{
    protected array $predictions = [];

    public function __construct()
    {
        $this->addPrediction('It is certain.');
        $this->addPrediction('It is decidedly so.');
        $this->addPrediction('Without a doubt.');
        $this->addPrediction('Yes definitely.');
        $this->addPrediction('You may rely on it.');
        $this->addPrediction('As I see it, yes.');
        $this->addPrediction('Most likely.');
        $this->addPrediction('Outlook good.');
        $this->addPrediction('Yes.');
        $this->addPrediction('Signs point to yes.');
        $this->addPrediction('Reply hazy try again.');
        $this->addPrediction('Ask again later.');
        $this->addPrediction('Better not tell you now.');
        $this->addPrediction('Cannot predict now.');
        $this->addPrediction('Concentrate and ask again.');
        $this->addPrediction('Don\'t count on it.');
        $this->addPrediction('My reply is no.');
        $this->addPrediction('My sources say no.');
        $this->addPrediction('Outlook not so good.');
        $this->addPrediction('Very doubtful.');
    }

    public function addPrediction(string $prediction): void
    {
        foreach ($this->predictions as $existing)
        {
            if ($existing == $prediction) return;
        }
        $this->predictions[] = $prediction;
    }

    public function getPrediction(string $question): string
    {
        if (empty($question))
        {
            return 'What do you want me to predict?';
        }

        $lbound = 0;
        $ubound = \count($this->predictions) - 1;

        if ($ubound < $lbound)
        {
            // There are no predictions.
            return 'Please return me to the store, I am faulty.';
        }

        return $this->predictions[\mt_rand($lbound, $ubound)];
    }

    public function removeAllPredictions(): void
    {
        $this->predictions = [];
    }

    public function removePrediction(string $prediction): bool
    {
        $index = \count($this->predictions) - 1;
        while ($index >= 0)
        {
            if ($this->predictions[$index] == $prediction)
            {
                unset($this->predictions[$index]);
                return true;
            }
        }
        return false;
    }
}
