<?php

if (isset($_GET['q'])) {
    include 'search.php';
} else {
    include 'homepage.php';
}
