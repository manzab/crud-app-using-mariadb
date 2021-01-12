<?php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "sprint2";
$path = "?path";
$num = 12;

$conn = mysqli_connect($servername, $username, $password, $dbname); // Create connection

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";
mysqli_query($conn, "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

if (isset($_GET['action']) and $_GET['action'] == 'deleteProject') {
    $sql = 'DELETE FROM projects WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['id']);
    $res = $stmt->execute();
    $stmt->close();
    mysqli_close($conn);
    header("Location: ?path=projects");
    die();
};
if (isset($_GET['action']) and $_GET['action'] == 'deleteEmployee') {
    $sql = 'DELETE FROM employees WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['id']);
    $res = $stmt->execute();
    $stmt->close();
    mysqli_close($conn);
    header("Location: ?path=employees");
    die();
};


print("<head><link rel='stylesheet' href='styles.css'></head>");
print("<body>");
print("<br>");
print("<br>");
print("<header><div class='links'><a href='$path=projects'>PROJECTS</a><a href='$path=employees'>EMPLOYEES</a></div><div class='logo'><img src='https://cdn1.iconfinder.com/data/icons/youtuber/256/thumbs-up-like-gesture-512.png'></div></header>");
print("</header");
print("<br>");
if (isset($_GET['path']) and $_GET['path'] == 'projects') {
    $sql = "SELECT projects.id, GROUP_CONCAT(employees.name SEPARATOR ', ') as Employees, projects.name as Projects FROM projects RIGHT JOIN employees ON employees.project_id = projects.id WHERE projects.name != '' GROUP BY projects.name";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        print("<table>");
        print("<thead><tr><th>#</th><th>Projects</th><th>Employees</th><th>Actions</th></tr></thead><tbody>");
        while ($row = mysqli_fetch_assoc($result)) {
            print('<tr>' . '<td>' . $num++ . '</td>' . '<td>' . $row['Projects'] . '</td>' . '<td>' . $row['Employees'] . '</td>' . '<td>' . '<a href="?action=deleteProject&id=' . $row['id'] . '"><button>DELETE</button></a>' . '<a href="?path=projects&update=' . $row['id'] . '"><button>UPDATE</button></a>' . '</td>' . '</tr>');
        }
        print('</tbody></table>');
    } else {
        echo '0 results';
    }
};
if (isset($_GET['path']) and $_GET['path'] == 'employees') {
    $sql = "SELECT employees.id , employees.name as Employees, projects.name as Projects FROM employees LEFT JOIN projects ON employees.project_id = projects.id";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        print('<table>');
        print('<thead><tr><th>#</th><th>Name</th><th>Projects</th><th>Actions</th></tr></thead><tbody>');
        while ($row = mysqli_fetch_assoc($result)) {
            print('<tr>' . '<td>' . $num++ . '</td>' . '<td>' . $row['Employees'] . '</td>' . '<td>' . $row['Projects'] . '</td>' . '<td>' . '<a href="?action=deleteEmployee&id=' . $row['id'] . '"><button>DELETE</button></a>' . '<a href="?path=employees&update=' . $row['id'] . '"><button>UPDATE</button></a>' . '</td>' . '</tr>');
        }
        print("</tbody></table>");
    } else {
        echo "0 results";
    }
}
if (isset($_GET) and $_GET['update'] != '') {
    if ($_GET['path'] == 'projects') {
        $id = $_GET['update'];
        $sql = "SELECT projects.* FROM projects WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                print('<br>');
                print('<form action="" name="edit" method="POST">');
                print('<input type="text" class="hidden" name="id" value="' . $row['id'] . '">');
                print('<input type="text" name="pname" value="' . $row['name'] . '">');
                print('<button id="edit" type="submit">EDIT</button>');
                print('</form>');
            }
        }
    }
    if($_GET['path'] == 'employees') {
        $id = $_GET['update'];
        $sql = "SELECT employees.* FROM employees WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                print('<br>');
                print('<form action="" name="edit" method="POST">');
                print('<input type="text" class="hidden" name="id" value="' . $row['id'] . '">');
                print('<input type="text" name="fname" value="' . $row['name'] . '">');
                print('<select name="project">');
                print('<option value=""></option>');
                print('<option value=""></option>');
                print('<option value=""></option>');
                print('</select>');
                print('<button id="edit" type="submit">EDIT</button>');
                print('</form>');
            }
        }
    }
}

if (isset($_POST['pname']) and $_POST['pname'] != "") {
    $id = $_POST['id'];
    $name = $_POST['pname'];
    $sql = "UPDATE projects SET name= '$name' WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    header("Location: ?path=projects");
};

mysqli_close($conn);
print('</body>');
