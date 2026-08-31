<?php

session_start();

include_once '../assets/connect_db/connection.php';


// ==================================================
// CHECK LOGIN
// ==================================================

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// ==================================================
// GET USER INFORMATION FROM SESSION
// ==================================================

$user_id = $_SESSION['user_id'];

$user_full_name = $_SESSION['user_full_name'] ?? 'User';

$username = $_SESSION['username'] ?? '';

$user_email = $_SESSION['user_email'] ?? '';


// ==================================================
// FETCH ONLY LOGGED-IN USER'S MESSAGES
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
    WHERE email = ?
    ORDER BY date_time DESC
";


$stmt = $conn->prepare($message_query);


if (!$stmt) {
    die("Database error: " . $conn->error);
}


$stmt->bind_param("s", $user_email);

$stmt->execute();

$result = $stmt->get_result();

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

    <title>AIS - User Profile</title>


    <!-- Main CSS -->
    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

    <!-- Home CSS -->
    <link
        rel="stylesheet"
        href="../assets/css/home.css"
    >

    <!-- Profile CSS -->
    <link
        rel="stylesheet"
        href="../assets/css/profile.css"
    >

</head>


<body>


<!-- ==================================================
     HEADER
================================================== -->

<header>

    <?php include '../header.php'; ?>

</header>



<!-- ==================================================
     MAIN
================================================== -->

<main>

    <section class="profile-container">


        <!-- ==================================================
             USER PROFILE
        ================================================== -->

        <div class="profile-card">


            <div class="profile-avatar">

                <?php

                echo strtoupper(
                    substr($user_full_name, 0, 1)
                );

                ?>

            </div>



            <div class="profile-info">

                <h1>

                    Welcome,
                    <?php
                    echo htmlspecialchars($user_full_name);
                    ?>

                </h1>


                <p>

                    <strong>Username:</strong>

                    <?php
                    echo htmlspecialchars($username);
                    ?>

                </p>


                <p>

                    <strong>Email:</strong>

                    <?php
                    echo htmlspecialchars($user_email);
                    ?>

                </p>


                <p>

                    <strong>Member ID:</strong>

                    <?php
                    echo htmlspecialchars($user_id);
                    ?>

                </p>

            </div>

        </div>



        <!-- ==================================================
             MESSAGES
        ================================================== -->

        <div class="messages-section">


            <div class="section-header">

                <div>

                    <h2>My Messages</h2>

                    <p>
                        View your messages and admin replies.
                    </p>

                </div>

            </div>



            <!-- ==================================================
                 TABLE
            ================================================== -->

            <div class="table-container">

                <table class="messages-table">


                    <thead>

                        <tr>

                            <th>
                                Message ID
                            </th>

                            <th>
                                Subject
                            </th>

                            <th>
                                Message
                            </th>

                            <th>
                                Reply
                            </th>

                            <th>
                                Date
                            </th>

                        </tr>

                    </thead>



                    <tbody>


                    <?php

                    if ($result->num_rows > 0) {

                        while ($row = $result->fetch_assoc()) {

                            $message_id = $row['id'];

                            $subject = $row['subject'];

                            $message = $row['message'];

                            $reply = $row['replies'];

                            $date_time = $row['date_time'];

                    ?>


                        <!-- ==================================================
                             MESSAGE ROW
                        ================================================== -->

                        <tr>


                            <!-- MESSAGE ID -->

                            <td
                                data-label="Message ID"
                                class="message-id"
                            >

                                #

                                <?php
                                echo htmlspecialchars($message_id);
                                ?>

                            </td>



                            <!-- SUBJECT -->

                            <td
                                data-label="Subject"
                            >

                                <span class="message-subject">

                                    <?php

                                    echo htmlspecialchars(
                                        $subject
                                    );

                                    ?>

                                </span>

                            </td>



                            <!-- MESSAGE -->

                            <td
                                data-label="Message"
                                class="message-column"
                            >

                                <?php

                                echo nl2br(
                                    htmlspecialchars($message)
                                );

                                ?>

                            </td>



                            <!-- REPLY -->

                            <td
                                data-label="Reply"
                                class="reply-column"
                            >


                                <?php

                                if (!empty($reply)) {

                                ?>


                                    <!-- VIEW REPLY BUTTON -->

                                    <button
                                        type="button"
                                        class="reply-btn replied"
                                        onclick="showReply(<?php echo $message_id; ?>)"
                                    >

                                        View Reply

                                    </button>



                                    <!-- REPLY BOX -->

                                    <div
                                        id="reply-<?php echo $message_id; ?>"
                                        class="reply-box"
                                    >

                                        <div class="reply-title">

                                            <span class="reply-icon">
                                                ↩
                                            </span>

                                            Admin Reply

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

                                } else {

                                ?>


                                    <!-- NO REPLY BUTTON -->

                                    <button
                                        type="button"
                                        class="reply-btn no-reply"
                                        onclick="showReply(<?php echo $message_id; ?>)"
                                    >

                                        Check Reply

                                    </button>



                                    <!-- NO REPLY BOX -->

                                    <div
                                        id="reply-<?php echo $message_id; ?>"
                                        class="reply-box no-reply-box"
                                    >

                                        <div class="reply-title">

                                            <span class="reply-icon">
                                                ⏳
                                            </span>

                                            Admin Reply

                                        </div>


                                        <p class="waiting-message">

                                            Admin has not replied yet.

                                        </p>

                                    </div>


                                <?php

                                }

                                ?>

                            </td>



                            <!-- DATE -->

                            <td
                                data-label="Date"
                                class="date-column"
                            >

                                <?php

                                echo date(
                                    "d M Y, h:i A",
                                    strtotime($date_time)
                                );

                                ?>

                            </td>


                        </tr>


                    <?php

                        }

                    } else {

                    ?>


                        <!-- ==================================================
                             NO MESSAGES
                        ================================================== -->

                        <tr>

                            <td
                                colspan="5"
                                class="no-messages"
                            >

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        💬
                                    </div>


                                    <h3>
                                        No Messages Yet
                                    </h3>


                                    <p>
                                        You have not sent any
                                        messages to the admin yet.
                                    </p>

                                </div>

                            </td>

                        </tr>


                    <?php

                    }

                    ?>


                    </tbody>

                </table>

            </div>

        </div>

    </section>

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



<!-- ==================================================
     JAVASCRIPT
================================================== -->

<script>

function showReply(messageId) {

    const replyBox =
        document.getElementById(
            "reply-" + messageId
        );


    if (!replyBox) {
        return;
    }


    if (replyBox.classList.contains("show")) {

        replyBox.classList.remove("show");

    } else {

        replyBox.classList.add("show");

    }

}

</script>


</body>

</html>


<?php

$stmt->close();

$conn->close();

?>