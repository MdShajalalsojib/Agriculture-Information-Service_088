<?php

session_start();

include_once '../../assets/connect_db/connection.php';


// ==================================================
// CHECK ADMIN LOGIN
// ==================================================

// Change this according to your existing admin session.
if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginSingup/login.php");
    exit();
}


// ==================================================
// HANDLE ADMIN REPLY
// ==================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $message_id = $_POST['message_id'] ?? '';
    $reply = trim($_POST['reply'] ?? '');


    // Validate message ID
    if (empty($message_id) || !is_numeric($message_id)) {

        $_SESSION['reply_error'] = "Invalid message ID.";

        header("Location: replies.php");

        exit();
    }


    // Validate reply
    if (empty($reply)) {

        $_SESSION['reply_error'] = "Reply cannot be empty.";

        header("Location: replies.php");

        exit();
    }


    // Maximum 500 characters
    if (strlen($reply) > 500) {

        $_SESSION['reply_error'] =
            "Reply cannot be more than 500 characters.";

        header("Location: replies.php");

        exit();
    }


    // ==================================================
    // UPDATE REPLY
    // ==================================================

    $update_query = "
        UPDATE messages
        SET replies = ?
        WHERE id = ?
    ";

    $stmt = $conn->prepare($update_query);


    if (!$stmt) {

        $_SESSION['reply_error'] =
            "Database error: " . $conn->error;

        header("Location: replies.php");

        exit();
    }


    $stmt->bind_param(
        "si",
        $reply,
        $message_id
    );


    if ($stmt->execute()) {

        $_SESSION['reply_success'] =
            "Reply sent successfully.";

    } else {

        $_SESSION['reply_error'] =
            "Failed to send reply.";
    }


    $stmt->close();


    header("Location: replies.php");

    exit();
}


// ==================================================
// FETCH ALL MESSAGES
// ==================================================

$message_query = "
    SELECT
        id,
        name,
        email,
        subject,
        message,
        replies,
        date_time
    FROM messages
    ORDER BY date_time DESC
";


$result = $conn->query($message_query);


if (!$result) {

    die(
        "Failed to fetch messages: "
        . $conn->error
    );

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <link
        rel="shortcut icon"
        href="../assets/images/logo/favicon.ico"
        type="image/x-icon"
    >

    <title>Admin - Messages & Replies</title>


    <!-- Main CSS -->

    <link
        rel="stylesheet"
        href="../../assets/css/style.css"
    >


    <!-- Replies CSS -->

    <link
        rel="stylesheet"
        href="../../assets/css/replies.css"
    >

</head>


<body>


<!-- ==================================================
     HEADER
================================================== -->

<header>

     

</header>



<!-- ==================================================
     MAIN
================================================== -->

<main class="admin-replies-container">


    <!-- ==================================================
         PAGE HEADER
    ================================================== -->

    <div class="page-header">

        <div>

            <h1>
                Messages & Replies
            </h1>

            <p>
                View user messages and send replies.
            </p>

        </div>

    </div>



    <!-- ==================================================
         SUCCESS MESSAGE
    ================================================== -->

    <?php

    if (isset($_SESSION['reply_success'])) {

    ?>

        <div class="alert success-alert">

            <?php

            echo htmlspecialchars(
                $_SESSION['reply_success']
            );

            ?>

        </div>

    <?php

        unset($_SESSION['reply_success']);
    }

    ?>



    <!-- ==================================================
         ERROR MESSAGE
    ================================================== -->

    <?php

    if (isset($_SESSION['reply_error'])) {

    ?>

        <div class="alert error-alert">

            <?php

            echo htmlspecialchars(
                $_SESSION['reply_error']
            );

            ?>

        </div>

    <?php

        unset($_SESSION['reply_error']);
    }

    ?>



    <!-- ==================================================
         MESSAGES
    ================================================== -->

    <div class="messages-card">


        <?php

        if ($result->num_rows > 0) {

        ?>

            <div class="messages-list">


            <?php

            while ($row = $result->fetch_assoc()) {

                $message_id = $row['id'];

                $name = $row['name'];

                $email = $row['email'];

                $subject = $row['subject'];

                $message = $row['message'];

                $reply = $row['replies'];

                $date_time = $row['date_time'];

            ?>


                <!-- ==================================================
                     SINGLE MESSAGE
                ================================================== -->

                <div class="message-card">


                    <!-- MESSAGE HEADER -->

                    <div class="message-header">

                        <div>

                            <span class="message-number">

                                Message #

                                <?php
                                echo htmlspecialchars(
                                    $message_id
                                );
                                ?>

                            </span>


                            <h2>

                                <?php
                                echo htmlspecialchars(
                                    $subject
                                );
                                ?>

                            </h2>

                        </div>


                        <span class="message-date">

                            <?php

                            echo date(
                                "d M Y, h:i A",
                                strtotime($date_time)
                            );

                            ?>

                        </span>

                    </div>



                    <!-- USER INFORMATION -->

                    <div class="sender-info">

                        <div class="sender-item">

                            <span class="sender-label">
                                Sender
                            </span>

                            <strong>

                                <?php

                                echo htmlspecialchars(
                                    $name
                                );

                                ?>

                            </strong>

                        </div>


                        <div class="sender-item">

                            <span class="sender-label">
                                Email
                            </span>

                            <strong>

                                <?php

                                echo htmlspecialchars(
                                    $email
                                );

                                ?>

                            </strong>

                        </div>

                    </div>



                    <!-- USER MESSAGE -->

                    <div class="user-message">

                        <div class="content-title">

                            User Message

                        </div>


                        <p>

                            <?php

                            echo nl2br(
                                htmlspecialchars(
                                    $message
                                )
                            );

                            ?>

                        </p>

                    </div>



                    <!-- EXISTING REPLY -->

                    <?php

                    if (!empty($reply)) {

                    ?>

                        <div class="existing-reply">

                            <div class="content-title">

                                <span>
                                    ↩
                                </span>

                                Current Admin Reply

                            </div>


                            <p>

                                <?php

                                echo nl2br(
                                    htmlspecialchars(
                                        $reply
                                    )
                                );

                                ?>

                            </p>

                        </div>

                    <?php

                    }

                    ?>



                    <!-- ==================================================
                         REPLY FORM
                    ================================================== -->

                    <div class="reply-section">


                        <?php

                        if (!empty($reply)) {

                        ?>

                            <h3>
                                Update Reply
                            </h3>

                            <p class="reply-description">
                                You can replace the existing reply.
                            </p>

                        <?php

                        } else {

                        ?>

                            <h3>
                                Write a Reply
                            </h3>

                            <p class="reply-description">
                                Send a reply to this user.
                            </p>

                        <?php

                        }

                        ?>


                        <form
                            action="replies.php"
                            method="POST"
                            class="reply-form"
                        >


                            <!-- MESSAGE ID -->

                            <input
                                type="hidden"
                                name="message_id"
                                value="<?php
                                echo htmlspecialchars(
                                    $message_id
                                );
                                ?>"
                            >


                            <!-- REPLY -->

                            <textarea
                                name="reply"
                                maxlength="500"
                                required
                                placeholder="Write your reply here..."
                            ><?php

                            if (!empty($reply)) {

                                echo htmlspecialchars(
                                    $reply
                                );

                            }

                            ?></textarea>


                            <!-- FORM FOOTER -->

                            <div class="reply-form-footer">

                                <span class="character-limit">
                                    Maximum 500 characters
                                </span>


                                <button
                                    type="submit"
                                    class="send-reply-btn"
                                >

                                    <?php

                                    if (!empty($reply)) {

                                        echo "Update Reply";

                                    } else {

                                        echo "Send Reply";

                                    }

                                    ?>

                                </button>

                            </div>


                        </form>

                    </div>


                </div>


            <?php

            }

            ?>


            </div>


        <?php

        } else {

        ?>


            <!-- ==================================================
                 NO MESSAGES
            ================================================== -->

            <div class="empty-state">

                <div class="empty-icon">
                    💬
                </div>


                <h2>
                    No Messages
                </h2>


                <p>
                    There are currently no messages from users.
                </p>

            </div>


        <?php

        }

        ?>


    </div>


</main>



<!-- ==================================================
     FOOTER
================================================== -->

<footer>

    <p>

        &copy; 2024 Agriculture Information Service.
        All rights reserved.

    </p>

</footer>


</body>

</html>


<?php

$conn->close();

?>