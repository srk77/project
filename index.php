<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

class autoloadModel 
{
  public static function autoload($class)
  {
    include $class . '.php';
  }
}

spl_autoload_register(array('autoloadModel', 'autoload'));
$obj = new main();

class main 
{
  public function __construct()
  {
    $pageRequest = 'mainPage';
    $table = 'table';
       
    if(isset($_REQUEST['newPage'])) 
    {         
      $pageRequest = $_REQUEST['newPage'];
    }
      $newPage = new $pageRequest;

    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
      $newPage->get();
    } 
    else 
    {
      $newPage->post();
    }
  }
}

abstract class newPage
{
  protected $html;

  public function __construct()
  {
    $this->html .= '<html>';
    $this->html .= '<body>';
  }
  public function __destruct()
  {
    $this->html .= '</body></html>';
    stringFunctions::printThis($this->html);
  }  
}

class mainPage extends newPage
{
  public function get()
  {
    $form = '<form method="post" enctype="multipart/form-data">';
    $form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
    $form .= '<input type="submit" value="Upload file" name="submit">';
    $form .= '</form> ';
    $this->html .= '<h1>Upload Form</h1>';
    $this->html .= $form;
  }

  public function post() 
  {
    $target_dir = "UPLOADS/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $imageFileName = pathinfo($target_file,PATHINFO_BASENAME);
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);

    header("Location: https://web.njit.edu/~srk77/project/untitled.php?page=table&filename=".$_FILES["fileToUpload"]["name"]);
  }
}

class stringFunctions
{
  static public function printThis($inputText) 
  {
    return print($inputText);
  }

  static public function stringLength($text) 
  {
    return strLen($text);
  }
}

class table extends newPage
{
  public function get()
  {
    $firstRow = true;
    $this->html .= '<table border=1>';

    $name= "UPLOADS/".$_REQUEST['filename'];  
    $file = fopen($name,"r");
    while (($line = fgetcsv($file)) !== false)
    {
      $this->html .= '<tr>';
      if($firstRow)
      {
        foreach ($line as $cell)
        {
          $this->html .= '<th>' . htmlspecialchars($cell) . '</th>';
        }
          $firstRow = false;
      }
      else
      {
        foreach ($line as $cell)
        {
          $this->html .= '<td>' . htmlspecialchars($cell) . '</td>';
        }
      }        
          $this->html .= '</tr>';
    }
      fclose($file);
      $this->html .= '</table';
  }
}

?>
