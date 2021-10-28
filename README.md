# PHP Order Form

## The mission

You need to make a form for a webshop, and add validation to the form. The customer can order various sandwiches, and then both the restaurant owner and the customer receive an e-mail with the details of the order.

You will add a counter at the bottom of the page that shows the total amount of money that has been spent on this page for this user. This counter should keep going up even when the user closes his browser.

## Learning objectives

Note about the icons/emojis:  
✔ - means that I consider the objective reached or the feature requirement met  
❌ - means it's still work in progress 🤫

- ✔ Be able to tell the difference between the superglobals `$_GET`, `$_POST`, `$_COOKIE` and `$_SESSION` variable.
- ✔ Be able to write basic validation for PHP.
- ✔ Be able to sent an email with PHP

## Assignment duration & type

We had 3 days to complete it (25/10 - 27/10) and it was a solo exercise.

## Required features

### Step 1: Validation

> ✔ Validate that the email address is a valid email
>
> ✔ Make sure that all fields are required
>
> ✔ Make sure that street number and postal code are only numbers
>
> ✔ After sending the form, if there are errors display them to the user
>
> ✔ If the form is invalid, make sure that the values entered previously are still there so the user doesn't have to enter them again
>
> ✔ If the form is valid, show the user a message

#### How did it go?

Went well, for email validation I used `FILTER_VALIDATE_EMAIL` as it was a tip in the assignment.  
For the validity part of the other fields, I used RegEx to validate them.  
For the "fields required" I just checked if they were empty.  
If there were any errors due to empty/invalid fields I stored them in an array and display each of them to the user.

Code example for validation:

```json
if (empty($_POST["streetnumber"])) {
        array_push($error_array, "Street number required");
    } else {
        $streetnumber = test_input($_POST["streetnumber"]);
        if (!preg_match("/^[\d]+$/", $streetnumber)) {
            array_push($error_array, "Street number can only contain... numbers");
        }
    }
```

### Step 2: Make sure the address is saved

> ✔ Save all the address information as long as the user doesn't close the browser.
>
> ✔ Pre fill the address fields with the saved address.

#### How did it go?

I used `$_SESSION` to save all address information, but ofc, only if there were no errors

Code example for pre filling input field:

```json
if (!isset($_POST['email'])) {
        if (isset($_SESSION['email'])) {
            $_POST['email'] = $_SESSION['email'];
        }
    }
```

### Step 3: ✔ Switch between drinks and food

There are 2 different $product arrays, one with drinks, the other with food. Depending on which link at the top of the page you click, you should be able to order food or drinks (never both). The food items should be the default.

#### How did it go?

In the exercise, the code that we got had 2 arrays with the same name, and each contained either the foods or the drinks. Because the drinks array came after the food one, the drinks were were always displayed on page load.

First choice:

```
if (str_ends_with("$_SERVER[REQUEST_URI]", '?food=0')) {
    $products = [
        ['name' => 'Cola', 'price' => 2],
        ['name' => 'Fanta', 'price' => 2],
        ['name' => 'Sprite', 'price' => 2],
        ['name' => 'Ice-tea', 'price' => 3],
    ];
}
```

Second try after I was advised to use `$_GET`

```
if (isset($_GET['food'])) {
    if ($_GET['food'] == '0')
        $products = [
            ['name' => 'Cola', 'price' => 2],
            ['name' => 'Fanta', 'price' => 2],
            ['name' => 'Sprite', 'price' => 2],
            ['name' => 'Ice-tea', 'price' => 3],
        ];
}
```

This way the drinks array was displayed only if the user clicked on the drinks link.

### Step 4: ✔ Calculate the delivery time

Calculate the expected delivery time for the product. For normal delivery all orders are fulfilled in 2 hours, for express delivery it is only 45 minutes. Add this expected time to the confirmation message. If you are wondering: they deliver with drones.

#### How did it go?

I checked whether the user ticked the express delivery checkbox and updated the total order price and the estimated delivery time based on that.

```
if (isset($_POST['express_delivery'])) {
            $totalOrderPrice = $orderPrice + $expressCost;
            $deliveryTime = date("H:i", time() + 2700);
        } else {
            $totalOrderPrice = $orderPrice;
            $deliveryTime = date("H:i", time() + 7200);
        }
```

### Step 5: ✔ Total revenue counter

Add a counter at the bottom of the page that shows the total amount of money that has been spent on this page from this browser. Should you use a `COOKIE` or a `SESSION` variable for this?

#### How did it go?

It was obvious that I should use a `cookie` since it will persist even after browser restart, however, initially I was having a hard time making it work but Sicco (my teacher🙏) quickly pointed out what my debugger kept telling me so many times: I was echo-ing something before setting up the cookie. After I fixed that, it worked like magic.

```
if (!isset($_COOKIE['history'])) {
            $totalValue = $totalOrderPrice;
            setcookie('history', strval($totalOrderPrice), time() + (60 * 60 * 24 * 30), '/');
        } else {
            $totalValue = $_COOKIE['history'] + $totalOrderPrice;
            setcookie('history', strval($totalValue), time() + (60 * 60 * 24 * 30), '/');
        }
```

PS: `$totalValue` represents the total amount spent by the user with all his orders, not just the last one.

### Step 6: ✔ Send the email

Use the `mail()` function in PHP to send an email with a summary of the order. The email should contain all information filled in by the user + the total price of all ordered items. Display the expected delivery time. Make sure to not forget the extra cost for express delivery! Sent this email to the user + a predefined email of the restaurant owner.

#### How did it go?

Initially I expected it to be harder than it actually was. The PHP `mail()` function is pretty straightforward and easy to use.  
I just needed to set up SMTP and enable access for less secure apps in the new Gmail account created only for this 😂.  
The "predefined email" part was basically a requirement to use define in PHP

```
define("HamEmail", "Bcc: stefan.amur@yahoo.com");
```

```
mail($email, 'Your order from "The Personal Ham Processor"', $message, HamEmail);
```

### Nice to have features

- ❌ Change the checkboxes to an input field to accept a quantity. Change the price calculation and email to support buying multiple items
- ❌ Make it possible to buy both drinks and food in one order. Still have the selections toggleable (no javascript!)
- ❌ Change the products to use Objects instead of an array

<img src="./img/arnold.jpg" alt="Arnold Schwarzenegger I'll be back" height="300"/>
