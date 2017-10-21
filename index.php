<?php
//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class Manage 
{
  public static function autoloadModel($class)
  {
    include $class . '.php';
  }
}

spl_autoload_register(array('Manage', 'autoloadModel'));
$obj = new main();

class main 
{
  public function __construct()
  {
    $pageRequest = 'mainPage';
    $table = 'table';

    if(isset($_REQUEST['page'])) 
    {
      $pageRequest = $_REQUEST['page'];
    }
    $page = new $pageRequest;

  if($_SERVER['REQUEST_METHOD'] == 'GET') 
  {
    $page->get();
  } 
  else 
  {
    $page->post();
  }
  }
}

abstract class page
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

class mainPage extends page
{
  public function get()
  {
    $form = '<form method="post" enctype="multipart/form-data">';
    $form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
    $form .= '<input type="submit" value="Upload file" name="submit">';
    $form .= '</form> ';
    $this->html .= '<h1>UPLOAD FORM</h1>';
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

    header("Location: https://web.njit.edu/~srk77/project/index.php?page=table&filename=".$_FILES["fileToUpload"]["name"]);
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

class table extends page
{
  public function get()
  {
    $firstRow = true;
    $this->html .= '<table border=2>';
    $name= "UPLOADS/".$_REQUEST['filename'];
    $f = fopen($name,"r");
    while (($line = fgetcsv($f)) !== false)
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
      fclose($f);
      $this->html .= '</table';
  }
}

?>