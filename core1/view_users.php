<?php
    include __DIR__.'/components/session.php';
    include "./components/settings.php";

    $client_id = $_GET["id"];
    $sql = "SELECT client_id, first_name, middle_name, last_name, sex, civil_status, birthdate, contact_number, address, barangay, city, province FROM client_info WHERE client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $client_info = $result->fetch_assoc();

    $sql = "SELECT employer_name, address, position FROM client_employment WHERE client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employment_info = $result->fetch_assoc();

    $sql = "SELECT fr_first_name, fr_last_name, fr_relationship, fr_contact_number, sr_first_name, sr_last_name, sr_relationship, sr_contact_number FROM client_references WHERE client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $references_info = $result->fetch_assoc();

    $sql = "SELECT source_of_funds, monthly_income FROM client_financial_info WHERE client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $financial_info = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="resources/css/app.css"rel="stylesheet">
    <link href="resources/css/sidebar.css"rel="stylesheet">
    <link href="resources/css/content.css"rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container-fluid px-0">
    <?php include 'components/sidebar.php'?>
        <div class="" style="margin-left: 25rem">
            <!--Content Here-->
                <section class="home-section">
                    <div class="dashboard-content">
                        <div class="info-section">
                                <h3>Client Information:</h3> <br>
                                <label for="email" class="form-label">Email address:</label>
                                <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($email) ?>" readonly>
                                <label for="first_name" class="form-label">First Name:</label>
                                <input type="text" class="form-control" name="first_name" id="first_name" value="<?= htmlspecialchars($client_info['first_name'] ?? ''); ?>" readonly>
                                <label for="middle_name" class="form-label">Middle Name:</label>
                                <input type="text" class="form-control" name="middle_name" id="middle_name" value="<?= htmlspecialchars($client_info['middle_name'] ?? ''); ?>" readonly>
                                <label for="last_name" class="form-label">Last Name:</label>
                                <input type="text" class="form-control" name="last_name" id="last_name" value="<?= htmlspecialchars($client_info['last_name'] ?? ''); ?>" readonly>
                                
                                <label for="sex" class="form-label">Sex:</label>
                                <select name="sex" id="sex" disabled>
                                    <option value="Male" <?= (isset($client_info['sex']) && $client_info['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?= (isset($client_info['sex']) && $client_info['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                </select><br>
                                <label for="civil_status" class="form-label">Civil Status:</label>
                                <select name="civil_status" id="civil_status" disabled>
                                    <option value="Single" <?= (isset($client_info['civil_status']) && $client_info['civil_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                                    <option value="Married" <?= (isset($client_info['civil_status']) && $client_info['civil_status'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                                    <option value="Seperated" <?= (isset($client_info['civil_status']) && $client_info['civil_status'] == 'Seperated') ? 'selected' : ''; ?>>Seperated</option>
                                    <option value="Widowed" <?= (isset($client_info['civil_status']) && $client_info['civil_status'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                                </select><br>
                                <label for="birthdate" class="form-label">Birthdate:</label>
                                <input type="date" class="form-control" name="birthdate" id="birthdate" value="<?= htmlspecialchars($client_info['birthdate'] ?? ''); ?>" readonly>
                                <label for="contact_number" class="form-label">Contact Number:</label>
                                <input type="number" class="form-control" name="contact_number" id="contact_number" value="<?= htmlspecialchars($client_info['contact_number'] ?? ''); ?>" readonly>
                                
                                <label for="address" class="form-label">Address:</label>
                                <input type="text" class="form-control" name="address" id="address" value="<?= htmlspecialchars($client_info['address'] ?? ''); ?>" readonly>
                                <label for="barangay" class="form-label">Barangay:</label>
                                <input type="text" class="form-control" name="barangay" id="barangay" value="<?= htmlspecialchars($client_info['barangay'] ?? ''); ?>" readonly>
                                <label for="city" class="form-label">City:</label>
                                <input type="text" class="form-control" name="city" id="city" value="<?= htmlspecialchars($client_info['city'] ?? ''); ?>" readonly>
                                <label for="province" class="form-label">Province:</label>
                                <input type="text" class="form-control" name="province" id="province" value="<?= htmlspecialchars($client_info['province'] ?? ''); ?>" readonly>
                                <br>
                                <h3>Client Employment Information:</h3> <br>
                                <label for="employer_name" class="form-label">Employer Name:</label>
                                <input type="text" class="form-control" name="employer_name" id="employer_name" value="<?= htmlspecialchars($employment_info['employer_name'] ?? ''); ?>" readonly>
                                <label for="address" class="form-label">Address:</label>
                                <input type="text" class="form-control" name="address" id="address" value="<?= htmlspecialchars($employment_info['address'] ?? ''); ?>" readonly>
                                <label for="position" class="form-label">Position:</label>
                                <input type="text" class="form-control" name="position" id="position" value="<?= htmlspecialchars($employment_info['position'] ?? ''); ?>" readonly>
                                <br>
                                <h3>Client Contact Reference:</h3> <br>
                                <label for="fr_first_name" class="form-label">First Name:</label>
                                <input type="text" class="form-control" name="fr_first_name" id="fr_first_name" value="<?= htmlspecialchars($references_info['fr_first_name'] ?? ''); ?>" readonly>
                                <label for="fr_last_name" class="form-label">Last Name:</label>
                                <input type="text" class="form-control" name="fr_last_name" id="fr_last_name" value="<?= htmlspecialchars($references_info['fr_last_name'] ?? ''); ?>" readonly>
                                <label for="fr_relationship" class="form-label">Relationship:</label>
                                <select name="fr_relationship" id="fr_relationship" disabled>
                                    <option value="Mother" <?= (isset($references_info['fr_relationship']) && $references_info['fr_relationship'] == 'Mother') ? 'selected' : ''; ?>>Mother</option>
                                    <option value="Father" <?= (isset($references_info['fr_relationship']) && $references_info['fr_relationship'] == 'Father') ? 'selected' : ''; ?>>Father</option>
                                    <option value="Siblings" <?= (isset($references_info['fr_relationship']) && $references_info['fr_relationship'] == 'Siblings') ? 'selected' : ''; ?>>Siblings</option>
                                    <option value="Friends" <?= (isset($references_info['fr_relationship']) && $references_info['fr_relationship'] == 'Friends') ? 'selected' : ''; ?>>Friends</option>
                                    <option value="Collegue" <?= (isset($references_info['fr_relationship']) && $references_info['fr_relationship'] == 'Collegue') ? 'selected' : ''; ?>>Collegue</option>
                                    <option value="Relatives" <?= (isset($references_info['fr_relationship']) && $references_info['fr_relationship'] == 'Relatives') ? 'selected' : ''; ?>>Relatives</option>
                                </select><br>
                                <label for="fr_contact_number" class="form-label">Contact Number:</label>
                                <input type="number" class="form-control" name="fr_contact_number" id="fr_contact_number" value="<?= htmlspecialchars($references_info['fr_contact_number'] ?? ''); ?>" readonly>
                                <br>
                                <label for="sr_first_name" class="form-label">First Name:</label>
                                <input type="text" class="form-control" name="sr_first_name" id="sr_first_name" value="<?= htmlspecialchars($references_info['sr_first_name'] ?? ''); ?>" readonly>
                                <label for="sr_last_name" class="form-label">Last Name:</label>
                                <input type="text" class="form-control" name="sr_last_name" id="sr_last_name" value="<?= htmlspecialchars($references_info['sr_last_name'] ?? ''); ?>" readonly>
                                <label for="sr_relationship" class="form-label">Relationship:</label>
                                <select name="sr_relationship" id="sr_relationship" disabled>
                                    <option value="Mother" <?= (isset($references_info['sr_relationship']) && $references_info['sr_relationship'] == 'Mother') ? 'selected' : ''; ?>>Mother</option>
                                    <option value="Father" <?= (isset($references_info['sr_relationship']) && $references_info['sr_relationship'] == 'Father') ? 'selected' : ''; ?>>Father</option>
                                    <option value="Siblings" <?= (isset($references_info['sr_relationship']) && $references_info['sr_relationship'] == 'Siblings') ? 'selected' : ''; ?>>Siblings</option>
                                    <option value="Friends" <?= (isset($references_info['sr_relationship']) && $references_info['sr_relationship'] == 'Friends') ? 'selected' : ''; ?>>Friends</option>
                                    <option value="Collegue" <?= (isset($references_info['sr_relationship']) && $references_info['sr_relationship'] == 'Collegue') ? 'selected' : ''; ?>>Collegue</option>
                                    <option value="Relatives" <?= (isset($references_info['sr_relationship']) && $references_info['sr_relationship'] == 'Relatives') ? 'selected' : ''; ?>>Relatives</option>
                                </select><br>
                                <label for="sr_contact_number" class="form-label">Contact Number:</label>
                                <input type="number" class="form-control" name="sr_contact_number" id="sr_contact_number" value="<?= htmlspecialchars($references_info['sr_contact_number'] ?? ''); ?>" readonly>
                                
                                <h3>Client Financial Information</h3>
                                <label for="source_of_funds" class="form-label">Source of funds:</label>
                                <select name="source_of_funds" id="source_of_funds" disabled>
                                    <option value="Employment" <?= (isset($financial_info['source_of_funds']) && $financial_info['source_of_funds'] == 'Employment') ? 'selected' : ''; ?>>Employment</option>
                                    <option value="Savings" <?= (isset($financial_info['source_of_funds']) && $financial_info['source_of_funds'] == 'Savings') ? 'selected' : ''; ?>>Savings</option>
                                    <option value="Allowance" <?= (isset($financial_info['source_of_funds']) && $financial_info['source_of_funds'] == 'Allowance') ? 'selected' : ''; ?>>Allowance</option>
                                    <option value="Business" <?= (isset($financial_info['source_of_funds']) && $financial_info['source_of_funds'] == 'Business') ? 'selected' : ''; ?>>Business</option>
                                    <option value="Pension" <?= (isset($financial_info['source_of_funds']) && $financial_info['source_of_funds'] == 'Pension') ? 'selected' : ''; ?>>Pension</option>
                                </select><br>
                                <label for="monthly_income" class="form-label">Monthly Income:</label>
                                <input type="number" class="form-control" name="monthly_income" id="monthly_income" value="<?= htmlspecialchars($financial_info['monthly_income'] ?? ''); ?>" readonly>
                                
                                <br>
                                <button onclick="window.location.href='users.php'" class="btn w-100 rounded-pill">Back</button>
                            </div>
                        </div>
                    </div>
                </section>
        </div>
    </div>
</body>
</html>