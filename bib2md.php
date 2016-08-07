<?php
 /*
  * Author: Eduardo Cruz Araújo
  * Version: 1.0.0
  * Date: 2016.08.07
  * Email: edcaraujo@gmail.com
  * Link: https://edcaraujo.github.io/
  */  
?>
<?php
  #
  # INCLUDES
  #
  require('deps/bib2tpl/bibtex_converter.php');

  #
  # SETTINGS
  #  
  error_reporting(0);

  #
  # DEFINES
  #
  $VERSION = '1.0.0';

  #
  # CORE
  #
  function convert($tpl, $bib, $md)
  {
    $tpl_content = file_get_contents($tpl);
    
    if ($tpl_content == FALSE || !isset($tpl_content)){
      echo "bib2md: [ERROR] Could not load template content from '".$tpl."'.\n";
      echo "Bye! :(\n";
      exit(1);
    } 
    
    $bib_content = file_get_contents($bib); 

    if ($bib_content == FALSE || !isset($bib_content)){
      echo "bib2md: [ERROR] Could not load bibtex content from '".$bib."'.\n";
      echo "Bye! :(\n";
      exit(1);
    }
  
    $parser = new BibtexConverter();  
    $md_content = $parser->convert($bib_content,$tpl_content); 
    
    file_put_contents($md, $md_content);
    
    if ($md_content == FALSE){
      echo "bib2md: [ERROR] Could not save markdown content in '".$md."'.\n";
      echo "Bye! :(\n";
      exit(1);
    }
  }

  #
  # UI
  # 
  function help()
  {
    echo "Usage: bib2md [OPTION]... [FILE]\n";
    echo "\n";
    echo "Avaiable options:\n";
    echo "\n";
    echo "    -o | --output <FILE>      set output file\n";
    echo "    -t | --template <FILE>    set template file\n";
    echo "    --help                    display this help and exit\n";
    echo "    --version                 display version information and exit\n";
    echo "\n";
    echo "Usage examples:\n";
    echo "\n";
    echo "    bib2md example.bib                       // output example.md\n"; 
    echo "    bib2md -o my.md example.bib              // output my.md\n";
    echo "    bib2md -t my.tpl -o new.md example.bib   // output new.md\n";
    echo "\n";
    echo "Report bib2md bugs to edcaraujo@gmail.com.\n";
    echo "More information: https://github.com/edcaraujo/bib2md/.\n";
  }

  function version()
  {
   echo "bib2md version ".$GLOBALS['VERSION'].".\n";  
  }
  
  function missing()
  {
    echo "bib2md: missing operand.\n";
    echo "Try 'nclstats --help' for more information.\n";
  }

  #
  # EXECUTION
  #
  function main($argc, $argv)
  {
    // This is not the best way to do this. Since the are only
    // few options, i am doing this way for simplicity.  

    if ($argc < 2){
      missing();
      exit();
    }
    
    if ($argv[1] == "--help"){
      help();
      exit();
    }
      
    if ($argv[1] == "--version"){
      version();
      exit();
    }
      
    if ((($argv[1] == "-o" || $argv[1] == "--output") ||
         ($argv[1] == "-t" || $argv[1] == "--template")) && $argc < 4){
      missing();
      exit();
    }
      
    if ((($argv[3] == "-o" || $argv[3] == "--output") ||
         ($argv[3] == "-t" || $argv[3] == "--template")) && $argc < 6){
      missing();
      exit();
    }

    $tpl = '../tpl/default.tpl';
    $bib = $argv[1];
    $md  = dirname($bib).'/'.basename($bib).'.md';

    if ($argc == 4){
      if ($argv[1] == "-t" || $argv[1] == "--template")
        $tpl = $argv[2];
        
      if ($argv[1] == "-o" || $argv[1] == "--output")
        $md = $argv[2];
        
      $bib = $argv[3];
    
    }else if ($argc == 6){
      if ($argv[1] == "-t" || $argv[1] == "--template"){
        $tpl = $argv[2];
        $md  = $argv[4];
      }
        
      if ($argv[1] == "-o" || $argv[1] == "--output"){
        $tpl = $argv[4];
        $md  = $argv[2];
      }
      
      $bib = $argv[5];
    }
  
    echo "Converting '".$bib."'.\n";  
    convert($tpl, $bib, $md); 
    echo "Done!\n";  
  }

  main($argc, $argv);
?>
