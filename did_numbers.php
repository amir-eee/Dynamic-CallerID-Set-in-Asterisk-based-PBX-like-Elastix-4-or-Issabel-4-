<?php
require_once "config.php";
// Check connection

$route_cid = $argv[1];

$flag_check = mysqli_query($con, "select count(*) as count from did_numbers where flag=0 and route_cid='$route_cid'");

$flag_check_row = mysqli_fetch_assoc($flag_check);

$count = $flag_check_row['count'];

if ($count == 0){
$update_all_flag_true = "update did_numbers set flag = '0' where route_cid='$route_cid'";

            if (mysqli_query($con, $update_all_flag_true))
            {
                //echo "Flag Update Successfully";
            }
}

$did_sql = mysqli_query($con, "select did from did_numbers where flag=0 and route_cid='$route_cid' limit 1");

$did_row = mysqli_fetch_assoc($did_sql);

echo $did_number = $did_row['did'];

$update_flag_true = "update did_numbers set flag = '1' where did='$did_number' and route_cid='$route_cid'";

            if (mysqli_query($con, $update_flag_true))
            {
                //echo "Flag Update Successfully";
            }
?>