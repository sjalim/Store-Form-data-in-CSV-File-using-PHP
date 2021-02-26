<?php

$error = '';
$name = '';
$email = '';
$phone = '';
$message = '';
$time = '';

function clean_text($string)
{
    $string = trim($string);
    $string = stripslashes($string);
    $string = htmlspecialchars($string);
    return $string;
}

if (isset($_POST["submit"])) {
    if (empty($_POST["name"])) {
        $error .= '<p><label class="text-danger">Please Enter your Name</label></p>';
    } else {
        $name = clean_text($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $error .= '<p><label class="text-danger">Only letters and white space allowed</label></p>';
        }
    }
    if (empty($_POST["email"])) {
        $error .= '<p><label class="text-danger">Please Enter your Email</label></p>';
    } else {
        $email = clean_text($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error .= '<p><label class="text-danger">Invalid email format</label></p>';
        }
    }
    if (empty($_POST["phone"])) {
        $error .= '<p><label class="text-danger">phone is required</label></p>';
    } else {
        $phone = clean_text($_POST["phone"]);
    }
    if (empty($_POST["message"])) {
        $error .= '<p><label class="text-danger">Message is required</label></p>';
    } else {
        $message = clean_text($_POST["message"]);
    }

    if ($error == '') {
        $file_open = fopen("contact_data.csv", "a");
        $no_rows = count(file("contact_data.csv"));
        if ($no_rows > 1) {
            $no_rows = ($no_rows - 1) + 1;
        }

        $time = date("Y-m-d h:i:sa");
        $form_data = array(
            'sr_no'  => $no_rows,
            'name'  => $name,
            'email'  => $email,
            'phone' => $phone,
            'message' => $message,
            'time' => $time
        );
        fputcsv($file_open, $form_data);
        $error = '<label class="text-success">Thank you for contacting us</label>';
        $name = '';
        $email = '';
        $phone = '';
        $message = '';
        $time = '';
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
    <br />
    <div class="container">
        <div class="col-md-6" style="margin:0 auto; float:none;">
            <form method="post">
                <?php echo $error; ?>
                <div class="form-group">
                    <label>Enter Name</label>
                    <input type="text" name="name" placeholder="Enter Name" class="form-control" value="<?php echo $name; ?>" />
                </div>
                <div class="form-group">
                    <label>Enter Email</label>
                    <input type="text" name="email" class="form-control" placeholder="Enter Email" value="<?php echo $email; ?>" />
                </div>
                <div class="form-group">
                    <label>Enter phone</label>
                    <input type="text" name="phone" class="form-control" placeholder="Enter phone" value="<?php echo $phone; ?>" />
                </div>
                <div class="form-group">
                    <label>Enter Message</label>
                    <textarea name="message" class="form-control" placeholder="Enter Message"><?php echo $message; ?></textarea>
                </div>
                <div class="form-group" align="center">
                    <input type="submit" name="submit" class="btn btn-info" value="Submit" />
                </div>
            </form>
        </div>



        <?php
        $csv = array_map("str_getcsv", file("contact_data.csv", FILE_SKIP_EMPTY_LINES));
        $keys = array_shift($csv);
        ?>
        <table border="2px solid black">
            <th>
            <td>Name</td>
            <td>Email</td>
            <td>Phone</td>
            <td>Message</td>
            <td>Time</td>
            <!-- <td>Serial</td> -->
            <?php
            foreach ($csv as $i => $row) {
            ?>
                <tr>


                    <?php

                    $csv[$i] = array_combine($keys, $row);
                    foreach ($csv[$i] as $data) {
                    ?>
                        <td class="text-center">
                            <?php echo $data ?>
                        </td>
                    <?php
                        // echo $data;
                    }
                    // echo "\n";
                    ?>
                </tr>
            <?php }

            ?>
        </table>


    </div>


</body>

</html>