<?php
  $filename = ".env";
  $file_handle = fopen($filename, "w"); 

  if ($file_handle) {
      fwrite($file_handle, "
      ENV=dev
      JWT_SECRET=mysecret

      DB_HOST=db
      DB_NAME=chucknorris_db
      DB_USER=chuck_user
      DB_PASSWORD=root@root_db67");
      fclose($file_handle);
      echo "File '$filename' created and content written successfully.";
  } else {
      echo "Error creating file '$filename'.";
  }
?>
