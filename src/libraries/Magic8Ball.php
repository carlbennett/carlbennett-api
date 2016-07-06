<?php

namespace CarlBennett\API\Libraries;

class Magic8Ball {

  protected $predictions;

  public function __construct() {
    $this->predictions = [];
    $this->addPrediction("It is certain.");
    $this->addPrediction("It is decidedly so.");
    $this->addPrediction("Without a doubt.");
    $this->addPrediction("Yes definitely.");
    $this->addPrediction("You may rely on it.");
    $this->addPrediction("As I see it, yes.");
    $this->addPrediction("Most likely.");
    $this->addPrediction("Outlook good.");
    $this->addPrediction("Yes.");
    $this->addPrediction("Signs point to yes.");
    $this->addPrediction("Reply hazy try again.");
    $this->addPrediction("Ask again later.");
    $this->addPrediction("Better not tell you now.");
    $this->addPrediction("Cannot predict now.");
    $this->addPrediction("Concentrate and ask again.");
    $this->addPrediction("Don't count on it.");
    $this->addPrediction("My reply is no.");
    $this->addPrediction("My sources say no.");
    $this->addPrediction("Outlook not so good.");
    $this->addPrediction("Very doubtful.");
  }

  public function addPrediction($prediction) {
    $this->predictions[] = $prediction;
  }

  public function getPrediction($question) {
    if (empty($question)) {
      return "What do you want me to predict?";
    }
    $lbound = 0;
    $ubound = count($this->predictions) - 1;
    if ($ubound < $lbound) {
      // There are no predictions.
      return "Please return me to the store, I am faulty.";
    }
    return $this->predictions[mt_rand($lbound, $ubound)];
  }

  public function removeAllPredictions() {
    unset($this->predictions);
    $this->predictions = [];
    return true;
  }

  public function removePrediction($prediction) {
    $k = 0;
    $j = count($this->predictions);
    for ($i = 0; $i < $j; ++$j) {
      if ($predictions[$i] == $prediction) {
        unset($predictions[$i]);
        ++$k;
        break;
      }
    }
    return $k;
  }

}
