<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = "";
$name_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_did = trim($_POST["did"]);
	$input_did_array = explode (",", $input_did);
	
	$route_name_and_outcid = $_POST["route_name"];
	
	$str_arr = explode (",", $route_name_and_outcid); 
	
	$route_name_input = $str_arr[0];

	$route_cid_input = $str_arr[1];
	
    if(empty($input_did)){
        $name_err = "Please enter a valid DID number only.";
    } elseif(!filter_var($input_did, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9,]+$/")))){
        $name_err = "Please enter a valid DID number only.";
    } else{
        $did = $input_did;
		$route_name = $route_name_input;
		$route_cid = $route_cid_input;
		
		$size_of_did = sizeof($input_did_array);
		
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) ){
        // Prepare an insert statement
		if ($size_of_did === 1) {
        $sql = "INSERT INTO did_numbers (did,route_name,route_cid) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_did, $param_route_name, $param_route_cid);
            
            // Set parameters
            $param_did = $did;
			$param_route_name = $route_name;
			$param_route_cid = $route_cid;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
	}
	
	
	else {
	
	
    for ($i=0; $i < $size_of_did; $i++)
    {
	
        $sql = "INSERT INTO did_numbers (did,route_name,route_cid) VALUES (?, ?, ?)";
        
		$input_did = $input_did_array[$i];
		
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_did, $param_route_name, $param_route_cid);
            
            // Set parameters
            $param_did = $input_did;
			$param_route_name = $route_name;
			$param_route_cid = $route_cid;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                //exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        
	}
	mysqli_stmt_close($stmt);
	}
	
	
	
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create CallerID</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
						<?php 
						$sql = "SELECT name, outcid FROM outbound_routes";
						$result = $con->query($sql);

						
						  ?>
						<label>Route Name</label>
                              <select name="route_name" id="route_name">
                                <option>Select a route:</option>
							<?php
									if ($result->num_rows > 0) {
										// output data of each row
										while($row = $result->fetch_assoc()) {
											$route_cid_cup = $row['outcid'];
											echo $route_cid_cup;
							  ?>
									<option value="<?php echo $row['name'].",".$row['outcid'];?>"><?php echo $row['name'];?></option>
									
							  <?php
							//echo $row['name'].$row['outcid'];
									} 
								}
						?>							
								</select>
                        </div>
						
						
						<div class="form-group">
                            <label>DID Number</label>
                            <input type="text" name="did" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $did; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>