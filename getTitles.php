<?php
require_once('functions.php');

    $db = connectToDB();

try {

    $offset = 0;
    $items_per_page = 10;
    $page =  1;

    if(!empty($_POST["page"])) {
        $page = $_POST["page"];
        $offset = ($page-1) * $items_per_page;
    }

    // SQL query
    $sql = "SELECT primary_title, Title_ratings.average_rating FROM Titles INNER JOIN Title_ratings ON Title_ratings.title_id = Titles.title_id";
    
    // Boolean to check whether to use WHERE or &&
        $isNotFirst = false;

        if($_POST["min-avgrating"]) {
            $sql .= " WHERE Title_ratings.average_rating >= ".$_POST["min-avgrating"];
            $isNotFirst = true;
        }

        if($_POST["start-year"]) {
            if($isNotFirst) {
                $sql .= " && ";
            } else {
                $isNotFirst = true;
                $sql .= " WHERE";
            }
            $sql .= " start_year = ".$_POST["start-year"];
        }

        // No outer if statement here so the bindValue() above won't throw an error
        // If keyword is not declared the sql will not affect to results
        if(isset($isNotFirst)) {
            $sql .= " &&";
        } else {
            $isNotFirst = true;
            $sql .= " WHERE";
        }
        $sql .= " primary_title LIKE :keyword";

    
    // Show limited amount of records per page
    $sql .= " LIMIT ".$offset.",".$items_per_page;
    echo searchForm();

    // Handle binding and execute sql
    $query = $db->prepare($sql);
    $query->bindValue(':keyword', "%".$_POST['title-name']."%", PDO::PARAM_STR);
    $query->execute();
    $output = $query->fetchAll();
  
    // Display results
    if($query->rowCount() > 0) {
        echo "<table>";
        echo "<tr><th>Title</th><th>Average rating</th></tr>";
        foreach($output as $row) {
            echo "<tr>";
            echo "<td>".$row['primary_title']."</td>";
            echo "<td>".$row['average_rating']."</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Pagination
        // Fields for the inputs that are generated in pagination() function
        $fields = array(
            //'start-year' => $_POST['start-year'],
            'min-avgrating' => $_POST['min-avgrating'],
            'title-name' => $_POST['title-name']
        );

        // Check which pagination buttons are necessary
        if($page == 1) {
            if($query->rowCount() == $items_per_page) {
                echo pagination($fields, $page, 'next');
            }
        } else if($query->rowCount() < $items_per_page) {
            echo pagination($fields, $page, 'previous');
        } else {
            echo pagination($fields, $page);
        }

    } 
    else {
        echo "<b>No results found.</b>";
        echo pagination($fields, $page, 'previous');
    }
 
} catch(PDOException $e) {
    echo "Connection failed: ".$e->getMessage();
}
?>
