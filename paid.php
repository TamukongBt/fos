<?php
session_start();
error_reporting(0);
include_once('includes/dbconnection.php');
if (strlen($_SESSION['fosuid']==0)) {
  header('location:logout.php');
  } else{ 
    $post = json_decode(file_get_contents('php://input'), true);

    $post_id=$post['transaction_id'];
    $post_amt=$post['amount'];
    $post_transactionref=$post['transaction_reference'];
    $post_status=$post['status'];
    $post_date=$post['date'];
    $post_clientname=$post['client_name'];
    $post_clientphone=$post['client_phone'];
    $post_email=$post['client_email'];
    $post_vendor=$post['vendor_name'];
    $query.="insert into tbltransact(transaction_id,amount,transaction_refrence,status,date,
    clientname,clientemail,clientphone,vendorname) values('$post_id','$post_amt','$post_transactionref','$post_status','$post_date','$post_clientname'
    ,'$post_clientphone','$post_email','$post_vendor');";

$result = mysqli_multi_query($con, $query);

if ($result) {

echo '<script>alert("Your Paymenyt is successful")</script>';
echo "<script>window.location.href='my-order.php'</script>";

}
}   


    ?>