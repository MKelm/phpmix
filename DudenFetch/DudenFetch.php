<?php
error_reporting(E_ERROR | E_PARSE);

include(__DIR__.'/_require.php');
include(__DIR__.'/FluentDOM.php');

class DudenFetch {
  private $_urlTpl;
  private $_findStatement;
  private $_maxDepth;
  private $_words;
  private $_wordsStartPos;
  private $_wordsEndPos;
  private $_maxWordLength;

  public function __construct($maxDepth = 2, $maxWordLength = 7) {
    $this->_urlTpl = 'http://www.duden.de/rechtschreibung/%s';
    $this->_findStatement = sprintf(
      '//div[@id="%s"]//div[@class="bi-kollokation-div-matrixItem"]/a',
      "substantive"
    );
    $this->_maxDepth = $maxDepth;

    $this->_words = array();
    $this->_wordsStartPos = 0;
    $this->_wordsEndPos = 0;
    $this->_maxWordLength = $maxWordLength;
  }

  public function loadWords($file) {
    $content = file_get_contents($file);
    $this->addWords(explode("\n", $content));
  }

  public function addWords($words) {
    $this->_wordsStartPos = count($this->_words);
    foreach ($words as $word) {
      if (strlen($word) > 0)
        $this->_words[] = $word;
    }
    $this->_wordsEndPos = (count($this->_words) > 0) ?
      count($this->_words) - 1 : 0;
  }

  public function getWords() {
    return $this->_words;
  }

  public function wordExists($checkWord) {
    foreach ($this->_words as $word) {
      if (strcmp($word, $checkWord) == 0)
        return TRUE;
    }
    return FALSE;
  }

  public function perform($depth = 0) {
    $dom = new \FluentDOM\Document();
    $maxI = $this->_wordsEndPos;
    for ($i = $this->_wordsStartPos; $i <= $this->_wordsEndPos; $i++) {
      $url = sprintf($this->_urlTpl, $this->_words[$i]);
      $dom->loadHTMLFile($url);
      foreach ($dom->find($this->_findStatement) as $word) {
        if (strlen($word) <= $this->_maxWordLength &&
            $this->wordExists($word) == FALSE) {
          $this->_words[] = $word;
          printf("depth %d (%d%%): %s\n", $depth, 100 / $maxI * $i, $word);
        }
      }
    }
    if (count($this->_words) - 1 > $this->_wordsEndPos && $depth < $this->_maxDepth) {
      $this->_wordsStartPos = $this->_wordsEndPos + 1;
      $this->_wordsEndPos = count($this->_words) - 1;
      $this->perform($depth + 1);
    }
  }

  public function saveWords($file) {
    $fileContent = "";
    foreach ($this->_words as $word) {
      $fileContent .= $word."\n";
    }
    file_put_contents($file, $fileContent);
  }
}



$fetch = new DudenFetch();
$fetch->loadWords("words/input.txt");
$fetch->perform();
$fetch->saveWords("words/output.txt");
