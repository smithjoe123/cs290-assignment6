<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>week 6 php assignment 2</title>
    </head>
    <body>
        <form action="index.php" method="get">
            Name: <input type="text" name="name" ><br>
            Category: <input type="text" name="category"><br>
            Length: <input type="text" name="length"><br>
            <button type="submit" name="add"> Add Video</button>
        </form>
        <?php
            
            error_reporting(E_ALL);
            //init_set('display_errors', 1);
            
            function displayVideos($mysqli)
            {
               echo "CURRENT VIDEO INVENTORY: <br>";
               
               $res = $mysqli->query("SELECT * FROM videos");
                
               $res->data_seek(0);
                
               while ($row = $res->fetch_assoc()) 
               {
                   
                   echo "<div id='$row[id]'>" . $row['id'] . " " . $row['name'] . " " . $row['category'] . " " . $row['length'] . "<form action='index.php' method='get'><button type='submit' name='delete' id='$row[id]'>delete</button><input type='hidden' name='id' value='$row[id]' /></form><br>";
                   
                   
                       
               }
              

                
                
                
                
                
            }
            
            function deleteVideo($mysqli, $id)
            {
               if ($mysqli->query("DELETE FROM videos WHERE id=$id") === true)
               {
                  echo "Record deleted successfully";
               }
               else 
               {
                   echo "Error deleting record: " . $mysqli->error;
               }                
                
            }
        
            $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "smithjoe-db", "quPNATmPBmdnbMQf", "smithjoe-db");
            //$mysqli = new mysqli("localhost", "root", "", "db1");

                if ($mysqli->connect_error) 
                {
                    die('Connect Error (' . $mysqli->connect_errno . ') '
                    . $mysqli->connect_error);
                }



            echo 'Success... ' . $mysqli->host_info . "<br>";
 
            if (!$mysqli->query("CREATE TABLE videos(id INT NOT NULL AUTO_INCREMENT, name VARCHAR(255), category VARCHAR(255), length INT, rented TINYINT, PRIMARY KEY (id)); "))
            {
                
                echo "Table creation failed: " . $mysqli->errno . ": " . $mysqli->error . "<br>";
                
            }
            
            if(isset($_GET['delete']))
            {
                
                $id = $_GET['id'];
                
                deleteVideo($mysqli, $id);
                

                
            }
            
            
            else if (isset($_GET['add']))
            {
                
                $name = $_GET['name'];
                $category = $_GET['category'];
                $length = $_GET['length'];
                
                if (!($stmt = $mysqli->prepare("INSERT INTO videos(name, category, length) values(?, ?, ?)")))
                {
                    
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                    
                }
                
                if (!($stmt->bind_param("ssi", $name, $category, $length)))
                {
                
                    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                    
                }
                
                if (!$stmt->execute())
                {
                    echo "Execute failed: (" . $stmt->errno . ") " .  $stmt->error;
                }                
                
            }
            
            
            displayVideos($mysqli);
            
            

            
        ?>
        
    </body>
</html>
