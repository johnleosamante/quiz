<div class="tab-pane fade<?php echo SetActiveNavigationTab(isset($_SESSION['pdstab']) && $_SESSION['pdstab'] === 'learning-development'); ?>" id="learning-development">
  <div class="d-sm-flex align-items-center justify-content-between">
    <h3 class="h4 mb-0">Learning &amp; Development (L&D) Interventions / Training Programs</h3>
    <a href="#AddTrainingModal" class="btn btn-primary btn-icon-split btn-sm" data-toggle="modal"><span class="icon text-white-50"><i class="fas fa-plus fa-fw"></i></span><span class="text">Add</span></a>
  </div>

  <div class="row mt-3">
    <div class="col table-responsive">
      <table width="100%" class="table table-striped table-bordered table-hover mb-0" cellspacing="0">
        <thead>
          <tr class="text-center">
            <th class="align-middle" width="30%" rowspan="2">Title Learning and Development Interventions / Training Programs</th>
            <th class="align-middle" width="10%" colspan="2">Inclusive Dates</th>
            <th class="align-middle" width="10%" rowspan="2">Number of Hours</th>
            <th class="align-middle" width="10%" rowspan="2">Type of Learning &amp; Development</th>
            <th class="align-middle" width="30%" rowspan="2">Conducted / Sponsored by</th>
            <th class="align-middle" rowspan="2" width="10%">Action</th>
          </tr>
          <tr class="text-center">
            <th class="align-middle" width="5%">From</th>
            <th class="align-middle" width="5%">To</th>
          </tr>
        </thead>

        <tbody>
          <?php
          $trainings = mysqli_query($con, "SELECT * FROM learning_and_development WHERE Emp_ID='" . $_SESSION['EmpID'] . "' ORDER BY `From` DESC;");

          if (mysqli_num_rows($trainings) > 0) {
            while ($training = mysqli_fetch_array($trainings)) { ?>
              <tr>
                <td class="text-center align-middle"><?php echo $training['Title_of_Training']; ?></td>
                <td class="text-center align-middle"><?php echo ToDateString($training['From']); ?></td>
                <td class="text-center align-middle"><?php echo ToDateString($training['To']); ?></td>
                <td class="text-center align-middle"><?php echo $training['Number_of_Hours']; ?></td>
                <td class="text-center align-middle"><?php echo $training['Managerial']; ?></td>
                <td class="text-center align-middle"><?php echo $training['Conducted']; ?></td>
                <td class="text-center align-middle">
                  <a class="btn btn-success my-1" onclick="viewdata('UpdateModal', 'pds/update/update-learning-development.php?id=<?php echo $training['No']; ?>')" data-toggle="modal" data-target="#UpdateModal" title="Edit"><i class="fas fa-edit fa-fw"></i></a>
                  <a class="btn btn-danger my-1" onclick="delete_LD(<?php echo $training['No']; ?>)" title="Remove"><i class="fas fa-trash fa-fw"></i></a>
                </td>
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

      <script>
        function delete_LD(id) {
          if (confirm("Are you sure you want to delete this entry?")) {
            window.location.href = 'pds/delete/delete-learning-development.php?id=' + id;
          }
        }
      </script>
    </div><!-- .col -->
  </div><!-- .row -->

  <div class="modal fade" id="AddTrainingModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Learning &amp; Development Intervention</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <form method="post" role="form" action="">
          <div class="modal-body">
            <div class="form-group">
              <label for="Title_learning" class="mb-0">Learning &amp; Development Intervention / Training Program (Write in full):</label>
              <input id="Title_learning" type="text" name="Title_learning" class="form-control" required>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="From" class="mb-0">Inclusive Date From:</label>
                  <input id="From" type="date" name="From" class="form-control" required>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="To" class="mb-0">Inclusive Date To:</label>
                  <input id="To" type="date" name="To" class="form-control" required>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="No_of_hours" class="mb-0">Number of Hours:</label>
              <input id="No_of_hours" type="text" name="No_of_hours" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="TrainingType" class="mb-0">Type of Learning &amp; Development:</label>
              <select name="TrainingType" id="TrainingType" class="form-control" required>
                <option value="Foundation">Foundation</option>
                <option value="Technical">Technical</option>
                <option value="Supervisory">Supervisory</option>
                <option value="Managerial">Managerial</option>
              </select>
            </div>

            <div class="form-group mb-0">
              <label for="Conducted" class="mb-0">Conducted / Sponsored by (Write in full): </label>
              <input id="Conducted" type="text" name="Conducted" class="form-control" required>
            </div>
          </div><!-- .modal-body -->

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" name="AddTraining">Save</button>
          </div><!-- .modal-footer -->
        </form>
      </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
  </div><!-- .modal -->
</div><!-- .tab-pane -->