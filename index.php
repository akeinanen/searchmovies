<?php 
/*** COMMANDS THAT ARE EXECUTED TO DATABASE ***/

/*
CREATE INDEX average_rating ON Title_ratings(average_rating);
CREATE INDEX title_id ON Title_ratings(title_id);
CREATE INDEX primary_title ON Titles(primary_title);
CREATE INDEX start_year ON Titles(start_year);
CREATE INDEX title_id ON Writers(title_id);
*/


require_once("functions.php");

echo searchForm();

?>
