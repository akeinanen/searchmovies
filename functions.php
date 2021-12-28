<?php

function connectToDB() {
    // Connect to database
    try {
        $db = new PDO("mysql:host=localhost;dbname=IMDb", "root", "root");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch(PDOException $e) {
        echo $e;
        return $e;
    }
    

}

function searchForm() {
    $html = "<h2>Search movies</h2>";

    // Get parameters for the query with HTML form
    $html .= "<form action='getTitles.php' method='post'>";
    $html .= "<br/>";
    //start-year
    $html .= titlesByYearDropDown();
    $html .= "<br/>Minimum average rating <input type='number' step=0.01 name='min-avgrating'>";
    $html .= "<br/>Name <input type='text' name='title-name'>";
    $html .= "<br/><input type='submit'>";
    $html .= "</form>";

    return $html;
}

function titlesByYearDropDown() {

    $db = connectToDB();

    try {
        $sql = "SELECT DISTINCT start_year FROM Titles";

        $prepare = $db->prepare($sql);
        $prepare->execute();
        // Save results to a variable
        $rows = $prepare->fetchAll();

        /* Create variable for html and iterate through results 
        adding them as an options to the html variable. */
        $html = "Start year: <select name='start-year'>";
        foreach($rows as $row) {
            $html .= "<option>".$row["start_year"]."</option>";
        }
        $html .= "</select>";

        return $html;
    }
    catch(PDOException $e) {
        return $e;
    }
}

function pagination($fields, $page, $type = 'both') {
    $html = '';
    // Both buttons
    if($type == 'both') {
        $html .= "<form style='display:inline' action='getTitles.php' method='post'>";
        foreach($fields as $key => $value) {
            $html .= "<input type='hidden' name='".$key."' value='".$value."'/>";
        }
        $html .= "<button type='submit' name='page' value='".($page-1)."'>Previous</button>";
        $html .= "</form>";

        $html .= "<form style='display:inline' action='getTitles.php' method='post'>";
        foreach($fields as $key => $value) {
            $html .= "<input type='hidden' name='".$key."' value='".$value."'/>";
        }
        $html .= "<button type='submit' name='page' value='".($page+1)."'>Next</button>";
        $html .= "</form>";
    // Next button
    } else if($type == 'next') {
        $html .= "<form style='display:inline' action='getTitles.php' method='post'>";
        foreach($fields as $key => $value) {
            $html .= "<input type='hidden' name='".$key."' value='".$value."'/>";
        }
        $html .= "<button type='submit' name='page' value='".($page+1)."'>Next</button>";
        $html .= "</form>";
    // Previous button
    } else if($type == 'previous') {
        $html .= "<form style='display:inline' action='getTitles.php' method='post'>";
        foreach($fields as $key => $value) {
            $html .= "<input type='hidden' name='".$key."' value='".$value."'/>";
        }
        $html .= "<button type='submit' name='page' value='".($page-1)."'>Previous</button>";
        $html .= "</form>";
    } else  {
        return null;
    }

    return $html;
}
