<?php      

        define("DB_HOST","localhost");
        define("DB_USERNAME","root");
        define("DB_PASSWORD","root");
        define("DB_NAME","secchief");
          
        //$con = connectdb();
        function connectdb() {
            
            $con = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);  
            if(mysqli_connect_errno()) {  
                die("Failed to connect with MySQL: ". mysqli_connect_error());  
            }
            return $con;
            
        }
?>  