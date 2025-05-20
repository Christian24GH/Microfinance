<?php include 'db_connect.php' ?>
<style>
   
</style>

<div class="container-fluid py-4">
  <div class="row">
    <!-- Card: Total Applicants -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <h5 class="card-title">Total Applicants</h5>
          <p class="display-6 fw-bold text-primary">
            <?php
              include 'db_connect.php';
              $applicants = $conn->query("SELECT COUNT(*) AS total FROM application")->fetch_assoc();
              echo $applicants['total'];
            ?>
          </p>
        </div>
      </div>
    </div>

    <!-- Card: Total Positions -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <h5 class="card-title">Open Positions</h5>
          <p class="display-6 fw-bold text-success">
            <?php
              $positions = $conn->query("SELECT COUNT(*) AS total FROM positions")->fetch_assoc();
              echo $positions['total'];
            ?>
          </p>
        </div>
      </div>
    </div>

    <!-- Card: New Hires -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <h5 class="card-title">New Hired Applicants</h5>
          <p class="display-6 fw-bold text-warning">
            <?php
              $hired = $conn->query("SELECT COUNT(*) AS total FROM application WHERE process_id = 9")->fetch_assoc();
              echo $hired['total'];
            ?>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Optional: Table of Latest Applicants -->
  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">
          Recent Applicants
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover border mb-0">
              <thead class="table-secondary text-center border-light">
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Position</th>
                  <th>Email</th>
                  <th>Date Applied</th>
                </tr>
              </thead>
              <tbody class="table-light text-center border">
                <?php
                $applicants = $conn->query("
                    SELECT a.id, CONCAT(a.firstname, ' ', a.lastname) AS full_name,
                        p.position_name, a.email, a.date_created
                    FROM application a
                    LEFT JOIN positions p ON a.position_id = p.position_id
                    ORDER BY a.date_created DESC
                    LIMIT 5
                ");

                $i = 1;
                if ($applicants && $applicants->num_rows > 0):
                    while($row = $applicants->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['full_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['position_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                    <td><?= date("M d, Y", strtotime($row['date_created'])) ?></td>
                </tr>
                <?php
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="5" class="text-muted">No Recent Applicant</td>
                </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
