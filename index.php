<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Filtered Records</title>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons JS -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js"></script>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f8f9fa;
}

form {
    margin: 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

form input[type="date"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

form input[type="submit"] {
    padding: 10px 20px;
    border: 1px solid #007BFF;
    background-color: #007BFF;
    color: white;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

form input[type="submit"]:hover {
    background-color: #0056b3;
}

table.dataTable thead th {
    text-transform: uppercase;
    padding: 10px;
    background-color: #007BFF;
    color: white;
}

table.dataTable {
    width: 100%;
    margin: 20px 0;
    border-collapse: collapse;
    background-color: #ffffff;
}

table.dataTable th, table.dataTable td {
    padding: 15px;
    border: 1px solid #ddd;
    text-align: left;
    font-size: 16px;
}

.table-container {
    margin: 20px 0;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
</head>
<body>

<!-- Date selection form -->
<form id="filterForm">
  Entry: Start Date: <input type="date" name="start_date" id="start_date">
  End Date: <input type="date" name="end_date" id="end_date">
  <input type="submit" value="Generate">
</form>

<div class="table-container">
  <!-- Display filtered records -->
  <table id="myTable" width="100%" class="display">
    <!-- Table headers -->
    <thead>
      <tr>
        <th>S.No</th>
        <!-- Output dynamic headers -->
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "csdexpre_csddb";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        // Query to get field names from the entry table
        $query = "SHOW COLUMNS FROM entry";
        $result = $conn->query($query);

        // Output headers
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<th>" . strtoupper($row["Field"]) . "</th>";
          }
        }

        // Close connection
        $conn->close();
        ?>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<!-- DataTables initialization script -->
<script type="text/javascript">
$(document).ready(function() {
    var table = $('#myTable').DataTable({
        "dom": 'Bfrtip', // Buttons first, then filter, table, info, pagination
        "buttons": [
            {
                extend: 'excelHtml5',
                title: 'Data export'
            },
            {
                extend: 'csvHtml5',
                title: 'Data export'
            },
            {
                extend: 'pdfHtml5',
                title: 'Data export'
            },
            {
                extend: 'print',
                title: 'Data export'
            }
        ],
    });

    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'fetch_records.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                table.clear().draw();
                if (data.length > 0) {
                    let count = 1;
                    data.forEach(row => {
                        table.row.add([
                            count,
                            ...Object.values(row)
                        ]).draw(false);
                        count++;
                    });
                }
            }
        });
    });
});
</script>

</body>
</html>
