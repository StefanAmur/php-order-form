<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
session_start();

function whatIsHappening()
{
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

//your products with their price.
$products = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];

if (str_ends_with("$_SERVER[REQUEST_URI]", '?food=0')) {
    $products = [
        ['name' => 'Cola', 'price' => 2],
        ['name' => 'Fanta', 'price' => 2],
        ['name' => 'Sprite', 'price' => 2],
        ['name' => 'Ice-tea', 'price' => 3],
    ];
}

$totalValue = 0;

$error_array = [];
$ordered_products = [];
$email = $street = $streetnumber = $city = $zipcode = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    print_r($_POST);
    if (empty($_POST["email"])) {
        array_push($error_array, "Email required");
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($error_array, "Invalid email format");
        }
    }

    if (empty($_POST["street"])) {
        array_push($error_array, "Street name required");
    } else {
        $street = test_input($_POST["street"]);
        if (!preg_match("/^[a-zA-Z \d]+$/", $street)) {
            array_push($error_array, "Street name can only contain letters, numbers, spaces or dashes");
        }
    }

    if (empty($_POST["streetnumber"])) {
        array_push($error_array, "Street number required");
    } else {
        $streetnumber = test_input($_POST["streetnumber"]);
        if (!preg_match("/^[\d]+$/", $streetnumber)) {
            array_push($error_array, "Street number can only contain... numbers");
        }
    }

    if (empty($_POST["city"])) {
        array_push($error_array, "City is required");
    } else {
        $city = test_input($_POST["city"]);
        if (!preg_match("/^[a-zA-Z-' \d]+$/", $city)) {
            array_push($error_array, "City name can only contain letters, numbers or spaces");
        }
    }

    if (empty($_POST["zipcode"])) {
        array_push($error_array, "Zipcode is required");
    } else {
        $zipcode = test_input($_POST["zipcode"]);
        if (!preg_match("/^[\d]+$/", $zipcode)) {
            array_push($error_array, "Zipcode can only contain numbers");
        }
    }

    if (!isset($_POST["products"])) {
        array_push($error_array, "You need to buy something, bro!");
    } else {
        foreach ($_POST["products"] as $i => $product) {
            array_push($ordered_products, $products[$i]);
        }
    }

    if (sizeof($error_array) != 0) {
        foreach ($error_array as $value) {
            echo '<p class="alert alert-danger">' . $value . '</p>';
        }
    } else {
        echo '<p class="alert alert-success">Order has been placed succesfully!</p>';
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

require 'form-view.php';
