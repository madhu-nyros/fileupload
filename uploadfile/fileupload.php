<?php
  if(isset($_POST['upload']))
  {
      $name=$_FILES['file']['name'];
      $type=$_FILES['file']['type'];
      $size=$_FILES['file']['size'];
      $tmp_name=$_FILES['file']['tmp_name'];
      $error=$_FILES['file']['error'];
      $location ="uploads/".$name; 
      $allowedExts = array("gif","jpg");
      $extension = end(explode(".", $_FILES["file"]["name"]));
      $target_file = $target_dir . basename($_FILES["file"]["name"]);
      $errors = array();
     
     if(in_array($extension ,$allowedExts) === false)
     {  
       $errors="Extension not allowed please choose jpg and gif files";
     }  
     if($size > 31457280)
     {
       $errors="your file is too long";
     }          
     if(empty($errors) == true)
     {
     	$fileNameNew = uniqid('',true).".".$extension;
     	$fileDestination = 'uploads/'.$fileNameNew;
        move_uploaded_file($tmp_name,$fileDestination);
        $load=  "successfully uploaded";    
     }
     if(isset($_FILES) && $_FILES['file']['name'] == '')
     {
        $errors="please choose the image to upload";
     }
  }
 ?>
<!DOCTYPE html>
<html>
<head>
    <title>Image upload</title>
    <link rel="stylesheet" type="text/css" href="imguploadstyle.css">
</head>
<body>
   <h2 align="center">Image Uploading</h2>
   <form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
      <input type="file" name="file" />
      <input type="submit"  name="upload" value="Image Upload"/>
    </form>
    <div id="img">
        <?php echo '<img src="uploads/'.$name .' " width="200" height="200"  alt="upload image">';
           echo "<h4>$load</h4>";
          if(!empty($errors))
           echo "<h4>$errors</h4>";
        ?>
    </div>
    <div id="display">
          <h2>uploaded images</h2>
        <?php 
          $directory = 'uploads/';
          if(is_dir($directory))
          {
           $f =scandir($directory);
              // this removes the first two folders . , ..
           $f=array_values(array_diff($f, array('.','..')));
              $per_page=5;        
              $page=$per_page;
              if(isset($_REQUEST['page']))
              {
              	$page  = $_REQUEST['page'] - 1;
  	  			$start = $page * $per_page;
  	  			$end   = $start + $per_page;	
  	  		  }
  	  		else
  	  		{
  	  			$start = 0;
  	  			$end = $per_page;
  	  		}
           for ($i=0;$i< count($f);$i++) {
              if($i >=$start && $i < $end)
              {
               echo "<ul id='images'>
                       <li><img src='http://10.90.90.40/mydata/tasks/php/uploadfile/uploads/".$f[$i]."' width='200' id='im' height='200'/></li>
                       <li id='im2'><h5>$f[$i]</h5></li>
                       <li><a href=?delete=$f[$i] name='delete'>DELETE</a></li>
                     </ul>";
                } 
             }
         } 
             // delete action done here
            if(isset($_REQUEST['delete']))
            {
                 $del= $_REQUEST['delete'];
                 $image= $directory.$del;
                  $u=unlink($image);
                 if(!$u)
                 {
                    echo ("not deleted");
                 }
                 else
                 {
                    header("location:fileupload.php");
                 }
            }
           if($f != false)
           {
               $filecount=count($f);
               echo "<h3>Total files:$filecount</h3>";    
           }
           else
           {
                echo  'no files';
           }          
        ?>
      </div>
      <div>
      <!-- pagination part/ -->
      <?php
         $per_page = 5;
            $number_of_files = count($f);
            $number_of_pages = ceil($number_of_files/$per_page);
              for($page = 1; $page <= $number_of_pages; $page++)
              {
                  echo '<ul ><li id="pagination"><a  href="fileupload.php?page='.$page.'">&nbsp;&nbsp;'.$page.'</a></li></ul>';
              }      
            ?>
      </div>
</body>
</html>
