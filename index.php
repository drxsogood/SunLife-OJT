<?php
// Load the database configuration file
include_once 'dbConfig.php';

// Get status message
if (!empty($_GET['status'])) {
    switch ($_GET['status']) {
        case 'succ':
            $statusType = 'alert-success';
            $statusMsg = 'Member data has been imported successfully.';
            break;
        case 'err':
            $statusType = 'alert-danger';
            $statusMsg = 'Something went wrong, please try again.';
            break;
        case 'invalid_file':
            $statusType = 'alert-danger';
            $statusMsg = 'Please upload a valid Excel file.';
            break;
        default:
            $statusType = '';
            $statusMsg = '';
    }
}

// Initialize variables
$totalTradPremiumRatio = 0;
$vulPremiumRatio = 0;
$originalAnnualPremiumSubTotal = 0;
$combinedPremiumRatio = 0;
$ttoap_voap = 0;

// Check if the Calculate button is clicked
if (isset($_GET['calculate']) && $_GET['calculate'] == 'true') {
    // Calculate total trad premium ratio
    $resultCurrent = $db->query("SELECT SUM(term_premium + net_premium) AS total FROM members");
    if ($resultCurrent && $resultCurrent->num_rows > 0) {
        $rowCurrent = $resultCurrent->fetch_assoc();
        $totalCurrentPremium = $rowCurrent['total'];
    }

    $resultNet = $db->query("SELECT SUM(net_premium) AS total FROM members");
    if ($resultNet && $resultNet->num_rows > 0) {
        $rowNet = $resultNet->fetch_assoc();
        $totalNetPremium = $rowNet['total'];
    }
    
    if ($totalCurrentPremium != 0) {
        $totalTradPremiumRatio = 100 * $totalNetPremium / $totalCurrentPremium;
    }

    // Calculate VUL Premium Ratio
    $resultX2 = $db->query("SELECT SUM(x2_original_annual_premium1month) AS total FROM members");
    if ($resultX2 && $resultX2->num_rows > 0) {
        $rowX2 = $resultX2->fetch_assoc();
        $totalX2OriginalAnnualPremium1Month = $rowX2['total'];
    }

    $resultRegular = $db->query("SELECT SUM(regular_premium_paid) AS total FROM members WHERE persistency_factor = 1");
    if ($resultRegular && $resultRegular->num_rows > 0) {
        $rowRegular = $resultRegular->fetch_assoc();
        $totalRegularPremiumPaid = $rowRegular['total'];
    }

    if ($totalX2OriginalAnnualPremium1Month != 0) {
        $vulPremiumRatio = 100 * $totalRegularPremiumPaid / $totalX2OriginalAnnualPremium1Month;
    }

    // Calculate Original Annual Premium Sub Total
    $resultOriginal = $db->query("SELECT SUM(original_annual_premium) AS total FROM members");
    if ($resultOriginal && $resultOriginal->num_rows > 0) {
        $rowOriginal = $resultOriginal->fetch_assoc();
        $originalAnnualPremiumSubTotal = $rowOriginal['total'];
    }

    // Calculate Combined Premium Ratio
    $combinedPremiumRatio = ($totalTradPremiumRatio / 100) * $totalCurrentPremium + ($vulPremiumRatio / 100) * $originalAnnualPremiumSubTotal;
    
    // Calculate TTOAP and VOAP
    $ttoap_voap = $totalCurrentPremium + $originalAnnualPremiumSubTotal;
}

// Check if denominator is not zero
if ($ttoap_voap != 0) {
    // Calculate Combined Premium Ratio Category
    $combinedPremiumRatioCategory = ($combinedPremiumRatio / $ttoap_voap) * 100;
} else {
    // Set category to zero if denominator is zero to avoid division by zero error
    $combinedPremiumRatioCategory = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Import Excel File Data with PHP</title>

    <!-- Bootstrap library -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Stylesheet file -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Show/hide Excel file upload form -->
    <script>
        function formToggle(ID) {
            var element = document.getElementById(ID);
            if (element.style.display === "none") {
                element.style.display = "block";
            } else {
                element.style.display = "none";
            }
        }

        function confirmDelete() {
            if (confirm("Are you sure you want to delete all data?")) {
                window.location.href = "deleteData.php";
            }
        }
    </script>
</head>
<body>

<div class="container-fluid p-3">
    <h1>SunLife Trad/Vul Calculator</h1>

    <h2>Insured Member List</h2>

    <!-- Display status message -->
    <?php if (!empty($statusMsg)) { ?>
        <div class="col-xs-12 p-3">
            <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
        </div>
    <?php } ?>

    <div class="row p-3">
        <!-- Import and Delete buttons -->
        <div class="col-md-12 head">
            <div class="float-end">
                <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrm');"><i class="plus"></i> Import Excel</a>
                <a href="javascript:void(0);" class="btn btn-danger" onclick="confirmDelete();"><i class="minus"></i> Delete Data</a>
                <a href="index.php?calculate=true" class="btn btn-primary"><i class="calculator"></i> Calculate</a>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>

        <!-- Excel file upload form -->
        <div class="col-md-12" id="importFrm" style="display: none;">
            <form class="row g-3" action="importData.php" method="post" enctype="multipart/form-data">
                <div class="col-auto">
                    <label for="fileInput" class="visually-hidden">File</label>
                    <input type="file" class="form-control" name="file" id="fileInput"/>
                </div>
                <div class="col-auto">
                    <input type="submit" class="btn btn-primary mb-3" name="importSubmit" value="Import">
                </div>
            </form>
            <div class="float-end" style="clear: both;">
                <a href="assets/Sample-Excel-Format.xlsx" download target="_blank">Download Sample Format</a>
            </div>
        </div>

        <!-- Data list table -->
        <div class="col-md-12">
            <h3>Data List</h3>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Insured Name</th>
                    <th>Policy Number</th>
                    <th>Issue Date</th>
                    <th>ST</th>
                    <th>MD</th>
                    <th>Current Premium</th>
                    <th>Term Premium</th>
                    <th>Net Premium</th>
                    <th>Regular Premium Paid</th>
                    <th>Original Annual Premium</th>
                    <th>X2 Original Annual Premium 1 Month</th>
                    <th>Share</th>
                    <th>Term Date</th>
                    <th>Persistency Factor</th>
                    <th>Projected Persistency Reinstated</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // Get member rows
                $result = $db->query("SELECT * FROM members ORDER BY id ASC");
                if ($result && $result->num_rows > 0) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row['insured_name']; ?></td>
                            <td><?php echo $row['policy_number']; ?></td>
                            <td><?php echo $row['issue_date']; ?></td>
                            <td><?php echo $row['st']; ?></td>
                            <td><?php echo $row['md']; ?></td>
                            <td><?php echo $row['current_premium']; ?></td>
                            <td><?php echo $row['term_premium']; ?></td>
                            <td><?php echo $row['net_premium']; ?></td>
                            <td><?php echo $row['regular_premium_paid']; ?></td>
                            <td><?php echo $row['original_annual_premium']; ?></td>
                            <td><?php echo $row['x2_original_annual_premium1month']; ?></td>
                            <td><?php echo $row['share']; ?></td>
                            <td><?php echo $row['term_date']; ?></td>
                            <td><?php echo $row['persistency_factor']; ?></td>
                            <td><?php echo $row['projected_persistency_reinstated']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="17">No member(s) found...</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Traditional Table -->
        <div class="col-md-6">
            <h3>Traditional</h3>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>Category</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Total Net Premium: <?php echo isset($_GET['calculate']) && $_GET['calculate'] == 'true' ? number_format($totalNetPremium, 2) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td>Total Current Premium: <?php echo isset($_GET['calculate']) && $_GET['calculate'] == 'true' ? number_format($totalCurrentPremium, 2) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td>Total Trad Premium Ratio: <?php echo isset($_GET['calculate']) && $_GET['calculate'] == 'true' ? number_format($totalTradPremiumRatio, 2) : 'N/A'; ?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- VUL Table -->
        <div class="col-md-6">
            <h3>VUL</h3>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>Category</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Total Regular Premium Paid: <?php echo isset($_GET['calculate']) && $_GET['calculate'] == 'true' ? number_format($totalRegularPremiumPaid, 2) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td>Total X2 Original Annual Premium 1 Month: <?php echo isset($_GET['calculate']) && $_GET['calculate'] == 'true' ? number_format($totalX2OriginalAnnualPremium1Month, 2) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td>VUL Premium Ratio: <?php echo isset($_GET['calculate']) && $_GET['calculate'] == 'true' ? number_format($vulPremiumRatio, 2) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td>Original Annual Premium Sub Total: <?php echo isset($_GET['calculate']) && $_GET['calculate'] == 'true' ? number_format($originalAnnualPremiumSubTotal, 2) : 'N/A'; ?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Combined Traditional and VUL Table -->
        <div class="col-md-12">
            <h3>Combined Traditional and VUL</h3>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>Category</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>(TPR and TTOAP) (VPR and TVOAP): <?php echo isset($_GET['calculate']) && $_GET['calculate'] == 'true' ? number_format($combinedPremiumRatio, 2) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td>(TTOAP and VOAP): <?php echo isset($_GET['calculate']) && $_GET['calculate'] == 'true' ? number_format($ttoap_voap, 2) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td>Combined Premium Ratio: <?php echo isset($_GET['calculate']) && $_GET['calculate'] == 'true' ? number_format($combinedPremiumRatioCategory, 2) : 'N/A'; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
