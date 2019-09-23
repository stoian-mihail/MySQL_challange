<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
      <form name="form" method="post">
      <input type="submit" name="button1" value="Customer most orders" />
      <input type="submit" name="button2" value="Most sold product" />
      <input type="submit" name="button3" value="Top customers" />
      <input type="submit" name="button4" value="Top 100 by last order"/>
      <input type="submit" name="button5" value="Customers with least orders"/>
      </form>
    <?php


function cust_most_orders(){
  $servername = "localhost";
  $username = "mihai";
  $password = "password";
  $dbname="my_shop";
    // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  echo "<h2>Customers with most orders</h2>";
  echo "<table><tr><th>Customer Id</th><th>Customer name</th><th>Number of orders</th></tr>";
  $sql = "SELECT t2.first_name, t2.last_name, t1.customer_id, COUNT(*) AS Total_orders FROM all_orders t1 INNER JOIN customers t2 ON t1.customer_id=t2.customer_id GROUP BY t1.customer_id ORDER BY Total_orders DESC;";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
          echo "<tr><td>".$row["customer_id"]."</td>"."<td>".$row["first_name"]. " " . $row["last_name"]. "</td>" . "<td>".$row["Total_orders"]. "</td></tr>";
      }
  } else {
      echo "0 results";
  }
  echo "</table>";
  $conn->close();
}


function most_sold_product(){
  $servername = "localhost";
  $username = "mihai";
  $password = "password";
  $dbname="my_shop";
    // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  echo "<h2>Most sold products</h2>";
  echo "<table><tr><th>Product Id</th><th>Product name</th><th>Quantity ordered</th></tr>";
  $sql = "SELECT t2.product_name, t1.product_id, SUM(t1.quantity) AS TotalQuantity FROM ordered_items t1 INNER JOIN products t2 ON t1.product_id=t2.product_id GROUP BY product_id ORDER BY TotalQuantity DESC;";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
          echo "<tr><td>" . $row["product_id"] . "</td>". "<td>".$row["product_name"]. "</td>". "<td>".$row["TotalQuantity"]."</td></tr>";
      }
  } else {
      echo "0 results";
  }
  echo "</table>";
  $conn->close();
}

function top_customers(){
  $servername = "localhost";
  $username = "mihai";
  $password = "password";
  $dbname="my_shop";
    // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  $sql = "SELECT t1.first_name, t1.last_name, SUM(t2.order_value) AS TotalValue FROM all_orders t2 INNER JOIN customers t1 ON t1.customer_id=t2.customer_id GROUP BY t2.customer_id ORDER BY TotalValue DESC;";
  $result = $conn->query($sql);
  echo "<h2>Customers with highest order value</h2>";
  echo "<table><tr><th>Name</th><th>Total ordered</th></tr>";
  if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
          echo "<tr><td>".$row["first_name"]." ".$row["last_name"]."</td>"."<td>".$row["TotalValue"]."</td></tr>";
      }
  } else {
      echo "0 results";
  }
  echo "</table>";
  $conn->close();
}

function top_100_by_last_order(){
  $servername = "localhost";
  $username = "mihai";
  $password = "password";
  $dbname="my_shop";
    // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  $sql = "SELECT  t2.first_name, t2.last_name, t1.customer_id, t1.order_id, t1.order_value, t1.order_date FROM all_orders t1 INNER JOIN customers t2 ON t1.customer_id=t2.customer_id WHERE t1.order_date IN (SELECT MAX(order_date) FROM all_orders GROUP BY customer_id) GROUP BY t1.customer_id ORDER BY t1.order_value DESC LIMIT 100;";
  $result = $conn->query($sql);
  echo "<h2>Top 100 customers by last order value</h2>";
  echo "<table><tr><th>Customer ID</th><th>Customer Name</th><th>Order ID</th><th>Order date</th><th>Order value</th></tr>";
  if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
          echo "<tr><td>".$row["customer_id"]."</td>"."<td>".$row["first_name"]." ".$row["last_name"]."</td>"."<td>".$row["order_id"]."</td>"."<td>".$row["order_date"]."</td>"."<td>".$row["order_value"]."</td></tr>";
      }
  } else {
      echo "0 results";
  }
  echo "</table>";
  $conn->close();
}

function cust_least_orders(){
  $servername = "localhost";
  $username = "mihai";
  $password = "password";
  $dbname="my_shop";
    // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  $sql = "SELECT  t1.customer_id, t2.first_name, t2.last_name, COUNT(*) AS orders_last_6months FROM all_orders t1 INNER JOIN customers t2 ON t1.customer_id=t2.customer_id WHERE t1.order_date IN (SELECT order_date FROM all_orders where order_date > curdate() - interval 6 month GROUP BY customer_id) GROUP BY t1.customer_id ORDER BY orders_last_6months ASC;";
  $result = $conn->query($sql);
  echo "<h2>Customers with least orders in the last 6 months</h2>";
  echo "<table><tr><th>Customer ID</th><th>Customer Name</th><th>Number of orders</th></tr>";
  if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
          echo "<tr><td>".$row["customer_id"]."</td>"."<td>".$row["first_name"]." ".$row["last_name"]."</td>"."<td>".$row["orders_last_6months"]."</td></tr>";
      }
  } else {
      echo "0 results";
  }
  echo "</table>";
  $conn->close();
}




if(isset($_POST['button1'])){
cust_most_orders();
}
if(isset($_POST['button2'])){
most_sold_product();
}
if(isset($_POST['button3'])){
top_customers();
}
if(isset($_POST['button4'])){
top_100_by_last_order();
}
if(isset($_POST['button5'])){
cust_least_orders();
}


    ?>
  </body>
</html>
