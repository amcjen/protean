<html>

  <head>

    <title></title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="KEYWORDS" content="Home" />
    <meta name="robots" content="index,follow" />
    <link rel="shortcut icon" href="/favicon.ico" />

    <style type="text/css" media="screen,projection">

      html  { margin: 0; padding: 0; border: 0; background-color: #f0f8ff; }

      body  { margin: 2em 3em; padding: 0; border: 0; font-family: candara, verdana, tahoma, sans-serif; }
      p     { margin: 1em 1.5em; padding: 0; border: 0; text-align: justify; line-height: 135%; }
      pre   { margin: 2em 3em; padding: 0.5em 1em; border: 1px solid #bdf; background-color: #def; font-family: consolas, lucida console, monospace; font-weight: bold; }
      tt    { margin: 2em 3em; padding: 0.5em 1em; border: 1px solid #dbf; background-color: #edf; font-family: consolas, lucida console, monospace; font-weight: bold; display: block; }
      h1    { margin: 3em 0 0 0; padding: 1em 0 0 0; border-top: 3px double #08f; }
      h1.f  { margin: 0; padding: 0; border: 0; }
      .tiny { font-size: 0.01%; color: #edf; }    /* even though they break correctly, internet sucksplorer displays zero-width spaces as the unknown character, so shrink them to tiny and color them like the background to hide */

    </style>

  </head>


  <body>

    <h1 class="f">Stone PHP SafeCrypt Test Program</h1>

      <p>
        This application loads and tests Stone PHP SafeCrypt.  The testing methodology is simple, but effective.  Our objective is to establish that all the
        rules of PHP's keying and PHP's object serialization work across serialization.  This means that we need to play with the default index on unkeyed
        elements, nest some arrays, add some objects with constructors, reset the default index and so on.
      </p>

      <p>
        So: first we add some integers, then an unkeyed array containing an unkeyed string, a string keyed string and a boolean.  Next, some more integers.
        Then, we add a string keyed string, and then an unkeyed string, a boolean value, and an unkeyed integer.  Next we set an integer keyed integer, to
        reset the default key.  A string keyed string and seven more unkeyed integers, an object, and then an array of objects.  The array of objects has
        an unkeyed, a string keyed and a sparse integer keyed object inside.  If the data structures are identical each time, then complex structures 
        including objects may be used safely.
      </p>





<?php

  require_once('StonePhpSafeCrypt.php');





  class TestObject {

      function __sleep()         { return array('contents'); }
      function __wakeup()        { }
      function TestObject($init) { $this->contents = $init; }

      var $contents;

  };





  $ObjTest1  = new TestObject('Check if this string survives');
  $ObjTest2a = new TestObject('This one too');
  $ObjTest2b = new TestObject('This one too');
  $ObjTest2c = new TestObject('This one too');





  $TestData = array(

    3,4,                                     // integers [0] and [1]
    array("Howdy", "doo"=>"doody", false),   // un-keyed array as value; will be placed as [2]
    2,999,                                   // more integers [3] and [4]

    "david" => "goliath",                    // string key, string value - does not take up a numeric index, ofcoz

    'peter',                                 // un-keyed string, will be placed as [5]
    true,                                    // un-keyed boolean; will be placed as [6] (print_r has a bug and prints this as 1, but it correctly passes type and will === true and !== 1)
    4,                                       // unkeyed integer, placed as [7]

    500 => 500,                              // sparse integer keyed integer; this sets the count-up index to 500, instead of 8 like it used to be

    'quux'=>'quuux',                         // string key, string value - does not take up a numeric index, ofcoz

    10, 10, 10, 10, 10, 10, 10,              // integers [501] through [507]

    $ObjTest1,                               // Object

    'Object by Key Type' => array(           // Keyed array of

      $ObjTest2a,                            // un-keyed object
      'text keyed object' => $ObjTest2b,     // string keyed object
      16=>$ObjTest2c                         // sparse integer keyed object

    )

  );

  $LocalKey = 'maccabre_2: I am the very model of a modern Major General.';





  // Display a binary string

  function StrToHex(&$data) {

    $datasize = strlen($data);                  // hex representations are double the size of octet representations, ofcoz
    $output   = str_repeat(' ', $datasize*2);   // pre-allocate output buffer to doubled size to prevent reallocation thrash

    $cursor   = 0;

    for ($i=0; $i<$datasize; ++$i) {
      $output[$cursor++] = dechex((ord($data[$i]) & 240) >> 4);
      $output[$cursor++] = dechex(ord($data[$i]) & 15);
    }

    return $output;

  }





  // insert zero-width spaces to make a long string wrap naturally

  function MakeWrap($inString) {

    $out = '';

    $len = strlen($inString);
    for ($i=0; $i<$len; ++$i) {
      $out .= '<span class="tiny">&#8203;</span>' . $inString[$i];
    }

    return $out;

  }





  echo '<h1>Startup</h1>';

  echo "<p>Our data:</p><pre>", print_r($TestData, true), "</pre>";
  echo "<p>Our key:</p><pre>$LocalKey</pre>";

  echo '<h2>Normal PackCrypt</h2>';

  $enc = PackCrypt($TestData, $LocalKey, array('cipher' => 'twofish'));
  echo "<p>Our data, encrypted:</p><pre>", print_r($enc, true), "</pre>";
  echo "<p>Our data, as the stream only, in hex:</p><tt>", MakeWrap(StrToHex($enc['output'])), "</tt>";

  $rawsize = strlen($enc['output']);

  echo '<h2>After Restore</h2>';

  $dec = UnpackCrypt($enc['output'], $LocalKey, array('cipher' => 'twofish'));
  echo "<p>Our data, restored: </p><pre>", print_r($dec, true), "</pre>";





  echo '<h1>Let\'s try compression</h1>';

  $enc = PackCrypt($TestData, $LocalKey, array('cipher' => 'twofish', 'compressor' => 'gz'));
  echo "<p>Our data, encrypted and compressed:</p><pre>", print_r($enc, true), "</pre>";
  echo "<p>Our data, as the stream only, in hex:</p><tt>", MakeWrap(StrToHex($enc['output'])), "</tt>";

  $compsize = strlen($enc['output']);

  $pct = 100 - (floor(($compsize / $rawsize) * 1000)/10);

  echo "<p>Uncompressed and encrypted, the datastream was $rawsize bytes.  Compressed and encrypted, it's $compsize bytes, for a compression rate of $pct %.  Notice that when you reload, the sizes stay the same, but the actual text changes.  That's because the initialization vector is changing, but is applied after the compression, so the compression size will never change for any encryption algorithm that leaves the same size output as input.  These numbers are being generated on the fly, so if you replace the source data, the ratio will be updated too.</p>";

  echo '<h2>And, undone</h2>';

  $dec = UnpackCrypt($enc['output'], $LocalKey, array('cipher' => 'twofish', 'compressor' => 'gz'));
  echo "<p>Our data, decompressed and restored: </p><pre>", print_r($dec, true), "</pre>";





  echo '<h1>And, testing block scramble manually</h1>';

  $bsc = BlockScramble(serialize($TestData), $LocalKey);
  echo "<p>Block scramble test:</p><tt>", MakeWrap(StrToHex($bsc)), "</tt><p>Notice that the block scramble data does not change on reload, unlike the encryption calls.  That's because it's only being reduced to noise to prevent a man-in-the-middle attack, and is not to be used on its own as encryption.  <b><u>Never use BlockScramble()</u></b>.  It is not for you.</p>";

  echo '<h2>And undone</h2>';

  $dsc = BlockDescramble($bsc, $LocalKey);
  echo "<p>Recovery:</p><pre>", print_r(unserialize($dsc), true), "</pre>";





?>

  </body>

</html>
