<?php
$success = true;
$message = '';
$showPrompt = false;
$log = '';
$fromdata = '';
$todata = '';

if (isset($_POST['search'])) {
	$fromdata = $_SESSION[GetSiteAlias() . '_From'] = $_POST['from'];
	$todata = $_SESSION[GetSiteAlias() . '_To']	= $_POST['to'];
} else {
	$fromdata = $_SESSION[GetSiteAlias() . '_From'] = date('Y-m') . '-01';
	$todata = $_SESSION[GetSiteAlias() . '_To']	= date('Y-m') . '-31';
}

if (isset($_POST['attendance'])) {
	$time = 0;
	$hour = date("H");
	$minute = date("i");
	$second = 0;

	if ($hour == 13) {
		$time = '01:' . $minute . ':' . $second;
	} elseif ($hour == 14) {
		$time = '02:' . $minute . ':' . $second;
	} elseif ($hour == 15) {
		$time = '03:' . $minute . ':' . $second;
	} elseif ($hour == 16) {
		$time = '04:' . $minute . ':' . $second;
	} elseif ($hour == 17) {
		$time = '05:' . $minute . ':' . $second;
	} elseif ($hour == 18) {
		$time = '06:' . $minute . ':' . $second;
	} elseif ($hour == 19) {
		$time = '07:' . $minute . ':' . $second;
	} elseif ($hour == 20) {
		$time = '08:' . $minute . ':' . $second;
	} elseif ($hour == 21) {
		$time = '09:' . $minute . ':' . $second;
	} elseif ($hour == 22) {
		$time = '10:' . $minute . ':' . $second;
	} else {
		$time = $hour . ':' . $minute . ':' . $second;
	}

	$record = mysqli_query($con, "SELECT * FROM tbl_dtr WHERE DTRDate='" . date("Y-m-d") . "'  AND Emp_ID='" . $_SESSION[GetSiteAlias() . '_EmpID'] . "'");

	if (mysqli_num_rows($record) == 0) {
		if ($_POST['Status'] == 'Morning Login') {
			mysqli_query($con, "INSERT INTO tbl_dtr VALUES(NULL,'" . date("Y-m-d") . "','" . $time . "','0','0','0','" . $_SESSION[GetSiteAlias() . '_EmpID'] . "','" . date("l") . "')") or die("Error Login");
			$log = 'Logged In this morning';
		} elseif ($_POST['Status'] == 'Morning Logout') {
			mysqli_query($con, "INSERT INTO tbl_dtr VALUES(NULL,'" . date("Y-m-d") . "','0','" . $time . "','0','0','" . $_SESSION[GetSiteAlias() . '_EmpID'] . "','" . date("l") . "')");
			$log = 'Logged Out this morning';
		} elseif ($_POST['Status'] == 'Afternoon Login') {
			mysqli_query($con, "INSERT INTO tbl_dtr VALUES(NULL,'" . date("Y-m-d") . "','0','0','" . $time . "','0','" . $_SESSION[GetSiteAlias() . '_EmpID'] . "','" . date("l") . "')");
			$log = 'Logged In this afternoon';
		} elseif ($_POST['Status'] == 'Afternoon Logout') {
			mysqli_query($con, "INSERT INTO tbl_dtr VALUES(NULL,'" . date("Y-m-d") . "','0','0','0','" . date("H:i:s") . "','" . $_SESSION[GetSiteAlias() . '_EmpID'] . "','" . date("l") . "')");
			$log = 'Logged Out this afternoon';
		}
	} else {
		if ($_POST['Status'] == 'Morning Logout') {
			mysqli_query($con, "UPDATE tbl_dtr SET TimeOUTAM ='" . $time . "' WHERE DTRDate='" . date("Y-m-d") . "'  AND Emp_ID='" . $_SESSION[GetSiteAlias() . '_EmpID'] . "'");
			$log = 'Logged Out this morning';
		} elseif ($_POST['Status'] == 'Afternoon Login') {
			mysqli_query($con, "UPDATE tbl_dtr SET TimeINPM ='" . $time . "' WHERE DTRDate='" . date("Y-m-d") . "'  AND Emp_ID='" . $_SESSION[GetSiteAlias() . '_EmpID'] . "'");
			$log = 'Logged In this afternoon';
		} elseif ($_POST['Status'] == 'Afternoon Logout') {
			mysqli_query($con, "UPDATE tbl_dtr SET TimeOUTPM ='" . $time . "' WHERE DTRDate='" . date("Y-m-d") . "'  AND Emp_ID='" . $_SESSION[GetSiteAlias() . '_EmpID'] . "'");
			$log = 'Logged Out this afternoon';
		}
	}

	if (mysqli_affected_rows($con) == 1) {
		$success = true;
		$message = "You have successfully $log!";
		$showPrompt = true;
	}
}
?>

<div class="row mt-3 mb-4">
	<div class="col">
		<div class="card">
			<div class="card-header">
				<div class="d-sm-flex align-items-center justify-content-between">
					<h3 class="h4 mb-0">Daily Time Records</h3>

					<div class="d-inline-block">
						<a href="#newattendance" class="btn btn-success btn-icon-split btn-sm" data-toggle="modal"><span class="icon text-white-50"><i class="fas fa-plus fa-fw"></i></span><span class="text">WFM Attendance</span></a>

						<a href="<?php echo GetSiteURL(); ?>/print/dtr" class="btn btn-primary btn-icon-split btn-sm" target="_blank"><span class="icon text-white-50"><i class="fas fa-print fa-fw"></i></span><span class="text">Print Preview</span></a>
					</div>
				</div>
			</div><!-- .card-header -->

			<div class="card-body">
				<?php
				if ($showPrompt) {
					AlertBox($message, $success ? 'success' : 'danger', 'left');
				}
				?>

				<form action="" Method="POST">
					<div class="row">
						<div class="form-group col-12 col-md-6 col-lg-6">
							<label class="mb-0" for="from">From:</label></tr>
							<input type="date" class="form-control d-inline-block" id="from" name="from" value="<?php echo $fromdata; ?>" required>
						</div>

						<div class="form-group col-12 col-md-6 col-lg-6">
							<label class="mb-0" for="to">To:</label>
							<input type="date" class="form-control d-inline-block" id="to" name="to" value="<?php echo $todata; ?>" required>
						</div>
					</div>

					<button type="submit" name="search" class="btn btn-primary btn-block"><i class="fas fa-search fa-fw"></i> Show Data</button>
				</form>

				<div class="table-responsive mt-3">
					<table width="100%" class="table table-striped table-bordered table-hover mb-0">
						<thead>
							<tr>
								<th width="20%" rowspan="2" class="text-center align-middle">Date </th>
								<th width="40%" colspan="2" class="text-center">Morning</th>
								<th width="40%" colspan="2" class="text-center">Afternoon</th>
							</tr>
							<tr>
								<th class="text-center">In</th>
								<th class="text-center">Out</th>
								<th class="text-center">In</th>
								<th class="text-center">Out</th>
							</tr>
						</thead>

						<tbody>
							<?php
							$myinfoDTR = mysqli_query($con, "SELECT * FROM tbl_dtr WHERE tbl_dtr.DTRDate BETWEEN '" . $_SESSION[GetSiteAlias() . '_From'] . "' AND '" . $_SESSION[GetSiteAlias() . '_To'] . "' AND tbl_dtr.Emp_ID='" . $_SESSION[GetSiteAlias() . '_EmpID'] . "' ORDER BY tbl_dtr.DTRDate Desc");

							if (mysqli_num_rows($myinfoDTR) > 0) {
								while ($row = mysqli_fetch_array($myinfoDTR)) { ?>
									<tr>
										<td class="text-center"><?php echo $row['DTRDate']; ?></td>
										<td class="text-center"><?php echo $row['TimeINAM']; ?></td>
										<td class="text-center"><?php echo $row['TimeOUTAM']; ?></td>
										<td class="text-center"><?php echo $row['TimeINPM']; ?></td>
										<td class="text-center"><?php echo $row['TimeOUTPM']; ?></td>
									</tr>
								<?php
								}
							} else { ?>
								<tr>
									<td class="text-center align-middle" colspan="7">No data available in table</td>
								</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div><!-- .card-body -->
		</div><!-- .card -->
	</div><!-- .col -->
</div><!-- .row -->


<!-- Modal for Re-assign-->
<div class="panel-body">

	<!-- Modal -->
	<div class="modal fade" id="newattendance" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" onclick="window.location.reload()">&times;</button>
					<h4 class="modal-title">Daily Attendance Record </h4>
				</div>
				<div class="modal-body">
					<form action="" Method="POST" enctype="multipart/form-data">
						<?php



						echo '<label style="width:250px;height:250px;border:solid 1px black;float:right;padding:4px;margin:4px;font-size:25px;text-align:center;border-radius:.3em;">';
						echo date("l") . '<br/>';
						echo date("F  d, Y") . '<br/>';
						echo date("H:i:s") . '<br/>';


						echo '</label>';

						if (date("H:i:s") >= '04:30:00' and date("H:i:s") <= '10:30:00') {
							echo '<input type="hidden" name="Status" value="Morning Login">';
							echo '<button style="width:250px;height:250px;font-size:35px;border-radius:.3em;" name="attendance">MORNING LOGIN</button>';
						} elseif (date("H:i:s") >= '11:00:00' and date("H:i:s") <= '12:30:00') {
							echo '<input type="hidden" name="Status" value="Morning Logout">';
							echo '<button style="width:250px;height:250px;font-size:35px;border-radius:.3em;" name="attendance">LOGOUT</button>';
						} elseif (date("H:i:s") >= '12:31:00' and date("H:i:s") <= '14:31:00') {
							echo '<input type="hidden" name="Status" value="Afternoon Login">';
							echo '<button style="width:250px;height:250px;font-size:35px;border-radius:.3em;" name="attendance">LOGIN</button>';
						} else {
							echo '<input type="hidden" name="Status" value="Afternoon Logout">';
							echo '<button style="width:250px;height:250px;font-size:35px;border-radius:.3em;" name="attendance">LOGOUT</button>';
						}


						?>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>