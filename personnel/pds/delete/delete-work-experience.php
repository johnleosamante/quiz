<?php
include_once('../_includes_/function.php');
include_once('../_includes_/database/database.php');
mysqli_query($con,"DELETE FROM work_experience WHERE work_experience.Emp_ID='".$_SESSION['EmpID']."' AND work_experience.No ='".$_GET['id']."' LIMIT 1");
header('location:' . GetHashURL('personnel', 'Personal Data Sheet'));
?>