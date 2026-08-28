<?php

// ----------------------------------------------------
// Read environment configuration
// ----------------------------------------------------

$environment = getenv("APP_ENV");

if (!$environment) {
    $environment = "Local Development";
}


// ----------------------------------------------------
// Read Application Insights connection string
// ----------------------------------------------------

$appInsightsConnectionString =
    getenv("APPINSIGHTS_CONNECTION_STRING") ?: "";


// ----------------------------------------------------
// Process submitted deployment message
// ----------------------------------------------------

$submittedMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $message = trim($_POST["message"] ?? "");

    if ($message !== "") {

        $submittedMessage = htmlspecialchars(
            $message,
            ENT_QUOTES,
            "UTF-8"
        );
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>PHP Deployment Status App</title>


    <!-- ========================================= -->
    <!-- Application Insights Browser Monitoring -->
    <!-- ========================================= -->

    <?php if ($appInsightsConnectionString !== ""): ?>

        <script src="https://js.monitor.azure.com/scripts/b/ai.3.gbl.min.js"></script>

        <script>

            const appInsights =
                new Microsoft.ApplicationInsights.ApplicationInsights({

                    config: {

                        connectionString:
                            <?php
                            echo json_encode(
                                $appInsightsConnectionString
                            );
                            ?>

                    }

                });


            // Start Application Insights
            appInsights.loadAppInsights();


            // Track this PHP page
            appInsights.trackPageView({
                name: "PHP Deployment Status App"
            });

        </script>

    <?php endif; ?>


    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 40px;
        }


        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }


        h1 {
            margin-top: 0;
        }


        .status {
            padding: 15px;
            background: #e8f5e9;
            border-radius: 5px;
            margin-bottom: 20px;
        }


        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }


        button {
            padding: 10px 18px;
            cursor: pointer;
            margin-top: 5px;
        }


        .result {
            margin-top: 20px;
            padding: 15px;
            background: #e3f2fd;
            border-radius: 5px;
        }


        .monitoring {
            margin-top: 30px;
            padding: 20px;
            background: #fff3e0;
            border-radius: 5px;
        }


        .monitoring-status {
            margin-top: 15px;
            font-size: 14px;
        }

    </style>

</head>


<body>

<div class="container">


    <!-- ========================================= -->
    <!-- Application heading -->
    <!-- ========================================= -->

    <h1>PHP Deployment Status App</h1>

    <p>
        This application demonstrates a PHP 8.x deployment
        running alongside the .NET Student Grade Calculator.
    </p>


    <!-- ========================================= -->
    <!-- Runtime information -->
    <!-- ========================================= -->

    <div class="status">

        <strong>Application Status:</strong>
        Running

        <br><br>


        <strong>PHP Version:</strong>

        <?php
        echo htmlspecialchars(
            PHP_VERSION,
            ENT_QUOTES,
            "UTF-8"
        );
        ?>


        <br>


        <strong>Environment:</strong>

        <?php
        echo htmlspecialchars(
            $environment,
            ENT_QUOTES,
            "UTF-8"
        );
        ?>


        <br>


        <strong>Server Time:</strong>

        <?php
        echo date("Y-m-d H:i:s");
        ?>


        <br>


        <strong>Application Insights:</strong>

        <?php if ($appInsightsConnectionString !== ""): ?>

            Enabled

        <?php else: ?>

            Not Configured

        <?php endif; ?>

    </div>


    <!-- ========================================= -->
    <!-- Deployment message test -->
    <!-- ========================================= -->

    <h2>Deployment Message Test</h2>


    <form method="post">

        <input
            type="text"
            name="message"
            placeholder="Enter a deployment test message"
            required>


        <button type="submit">
            Submit Message
        </button>

    </form>


    <?php if ($submittedMessage !== ""): ?>

        <div class="result">

            <strong>
                Message processed by PHP:
            </strong>

            <br><br>

            <?php echo $submittedMessage; ?>

        </div>

    <?php endif; ?>


    <!-- ========================================= -->
    <!-- Application Insights monitoring test -->
    <!-- ========================================= -->

    <div class="monitoring">

        <h2>Application Monitoring Test</h2>

        <p>
            Use the button below to generate a controlled
            exception and send it to Azure Application
            Insights.
        </p>


        <button
            type="button"
            onclick="sendTestException()">

            Send Test Exception

        </button>


        <div class="monitoring-status">

            <?php if ($appInsightsConnectionString !== ""): ?>

                Application Insights monitoring is enabled.

            <?php else: ?>

                Application Insights connection has not
                been configured.

            <?php endif; ?>

        </div>

    </div>


</div>


<!-- ========================================= -->
<!-- Application Insights test exception -->
<!-- ========================================= -->

<script>

    function sendTestException() {

        try {

            throw new Error(
                "SWE40006 PHP Application Insights test exception"
            );

        }
        catch (error) {

            if (typeof appInsights !== "undefined") {

                // Send the controlled exception
                appInsights.trackException({
                    exception: error
                });


                // Force telemetry to be sent
                appInsights.flush();


                alert(
                    "Test exception sent to Application Insights."
                );

            }
            else {

                alert(
                    "Application Insights is not configured."
                );
            }
        }
    }

</script>


<!-- ========================================= -->
<!-- Track successful PHP form submission -->
<!-- ========================================= -->

<?php if (
    $submittedMessage !== "" &&
    $appInsightsConnectionString !== ""
): ?>

<script>

    if (typeof appInsights !== "undefined") {

        appInsights.trackEvent({
            name: "PHPDeploymentMessageSubmitted"
        });

    }

</script>

<?php endif; ?>


</body>

</html>