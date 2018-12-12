<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$page = 'Add Parts';

require_once 'header.php';

require_once 'includes/class.category.php';

$categories = new Category($pdo);
$fullCats = $categories->getAll();

require_once 'includes/class.location.php';

$locations = new Location($pdo);
$fullLocs = $locations->getAll();

?>
    <script type="text/css" src="css/animate.css"></script>

      <div class="card mx-auto mt-5">
        <div class="card-header">Add New Part</div>
        <div class="card-body">
          <form id="addPartForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="inputEmail">Part Name</label>
                <input type="text" id="inputName" name="name" class="form-control" required="required">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="3" required="required"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="ddCategory">Category</label>
                    <select id="ddCategory" name="category" class="form-control" required="required">
                        <option value=""></option>
<?php
foreach ($fullCats as $cat) {
    echo '<option value="' . $cat['id'] . '">' . $cat['name'] . '</option>';
}
?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="ddPurchased">Purchased From</label>
                    <select id="ddPurchased" name="purchased" class="form-control" required="required">
                        <option value=""></option>
<?php
foreach ($fullLocs as $loc) {
    echo '<option value="' . $loc['id'] . '">' . $loc['name'] . '</option>';
}
?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputQuantity">Quantity</label>
                    <input type="number" id="inputQuantity" name="quantity" class="form-control" required="required" min="0">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPrice">Price Paid For</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="number" id="inputPrice" name="price" class="form-control" required="required" step="0.01" min="0">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPicture">Picture</label>
                <input type="file" id="inputPicture" name="picture" class="form-control-file">
            </div>



            <a class="btn btn-primary btn-block" id="addpart" href="#">Add Part</a>
          </form>
        </div>
      </div>

    <script src="js/bootstrap-notify.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#addpart").click(function(e) {
                e.preventDefault();

                // var fd = $("#addPartForm").serialize();
                var fd = new FormData(document.getElementById("addPartForm"));
                // fd.append("label", "WEBUPLOAD");

                $.ajax({
                    type: "POST",
                    url: "saveparts.php",
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        var resp = JSON.parse(JSON.stringify(data));
                        if (resp['successful'] == "yes") {
                            // window.location.href = "addparts.php";
                            $("#addPartForm")[0].reset();
                            var notify = $.notify('There was an error with submission. Please verify the data and submit again.', {
                                type: 'info'
                            });
                        } else {
                            var notify = $.notify('There was an error with submission. Please verify the data and submit again.', {
                                type: 'danger'
                            });
                        }
                    },
                    dataType: "json"
                });
            });
        });

    </script>










<?php

require_once 'footer.php';
