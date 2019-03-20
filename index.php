<?php //echo "hello world!";

// Script start
$rustart = getrusage();
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
include('simple_html_dom.php');

//gir også opplv.info + klasser
function getklasser($linken, $firma) {
  $html = file_get_html($linken);
  echo "Skrapt fra: <a href='" . $linken . "'>" . $linken . "</a><br>";
  $i = 0;
  foreach($html->find('table') as $e) {
    if($i == 0) { //firma detaljer, sorter...
      $count = 0;
      foreach($e->find('td') as $f) {
          //sertnr
          if($count == 0) {
            echo "Firmanavn: " . substr($f, 65, -5) . "<br>";
          }
          else if($count == 11) {
            echo "Sertifisering: " . substr($f, 53, -5);
          }
          $count++;
        }
    }
    if($i == 1) { //første tabell er søppel! vi trenger tabell nr2 med klasser
      //echo Firmanavn
      //echo $firma;
      echo "<br>";
      $foreklasse = 0;
      foreach($e->find('div') as $f) {
          //klasser
          if($foreklasse == 0 ) { echo substr($f, 20, -6); }
          else { echo " - " . substr($f, 20, -6); }
          $foreklasse++;
        }
    }
     $i++;
  }
}

//get utskrift versjon link fra alle opplvirks.
$html = file_get_html('https://sert555.no/soke2.cfm');
$i = 0;
foreach($html->find('table') as $e)
{
  if($i == 2) { //første tabell er søppel! vi trenger tabell nr2 med opplv.oversikt
    $opplvcounter = 0;
    foreach($e->find('a') as $f) {
        if ($opplvcounter > 2) break; //kjør bare x ganger, testing... comment for live.

        $link = $f;
        $firm = $f;
        preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $link, $result);

        if (!empty($result)) {
            # Found a link.
            $link = $result['href'][0];
            $link1 = str_replace("firmadetalj","firmadetalj2",$link);
            $link2 = "https://sert555.no" . $link1;
            //echo $link2 . "<br>"; //DONE!
            getklasser($link2, $firm);
            echo "<br><br>";
        }
        $opplvcounter++;
      }
  }
   $i++;
}

echo "<br><br><hr>";

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';

echo "<br>";
// Script end
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

$ru = getrusage();
echo "This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations\n";
echo "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls\n";

echo "<br >Sert555.no scraper by Martin Floden, 19.03.2019."
?>
