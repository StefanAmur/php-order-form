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

```
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

```
if (!isset($_POST['email'])) {
        if (isset($_SESSION['email'])) {
            $_POST['email'] = $_SESSION['email'];
        }
    }
```
