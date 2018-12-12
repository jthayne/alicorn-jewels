<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$page = 'Parts';

require_once 'header.php';

require_once 'includes/class.parts.php';
require_once 'includes/class.location.php';
require_once 'includes/class.category.php';

$parts = new Parts($pdo);
$fullList = $parts->getAllParts();

$location = new Location($pdo);
$category = new Category($pdo);
// var_dump($fullList);
?>

          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"></i>
              Data Table Example</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Location</th>
                      <th>Category</th>
                      <th>Image</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Category</th>
                        <th>Image</th>
                    </tr>
                  </tfoot>
                    <tbody>
                    <?php

                    foreach ($fullList as $item) {
                        echo '<tr>
                            <td>' . $item['id'] . '</td>
                            <td>' . $item['name'] . '</td>
                            <td>' . $item['description'] . '</td>
                            <td>' . $location->getName($item['location']) . '</td>
                            <td>' . $category->getName($item['category']) . '</td>
                            <td>' . $item['image'] . '</td>
                        </tr>';
                    }

                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
          </div>

    <!-- Page level plugin JavaScript-->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Demo scripts for this page-->
    <script src="js/demo/datatables-demo.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>

<?php

require_once 'footer.php';
