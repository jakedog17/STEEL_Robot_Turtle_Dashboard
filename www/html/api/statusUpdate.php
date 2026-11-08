
<?php

    $inData = getRequestInfo();

    $boardInfo = $inData["UserBoards"];
    $motors = $inData["Motors"] ?? [];
    $servos = $inData["Servos"];

    $conn = new mysqli(
        "localhost",
        "TheBeast",
        getenv("DB_PASSWORD"),
        "STEEL_Turtle"
    );

    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
        exit();
    }

    $stmt = $conn->prepare(
        "SELECT ID, boardName
         FROM UserBoards
         WHERE Login=? AND Password=?"
    );

    $stmt->bind_param(
        "ss",
        $boardInfo["login"],
        $boardInfo["password"]
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        // Get actual board ID
        $boardID = $row["ID"];

        // Process Motors
        foreach ($motors as $motor) {

            $motorStmt = $conn->prepare(
                "SELECT ID FROM Motors
                 WHERE boardID=? AND MotorName=?"
            );

            $motorStmt->bind_param(
                "is",
                $boardID,
                $motor["MotorName"]
            );

            $motorStmt->execute();

            $result = $motorStmt->get_result();

            if ($result->fetch_assoc()) {

                // Update existing motor
                $motorUpdateStmt = $conn->prepare(
                    "UPDATE Motors
                     SET Speed=?, Direction=?
                     WHERE boardID=? AND MotorName=?"
                );

                $motorUpdateStmt->bind_param(
                    "iiis",
                    $motor["Speed"],
                    $motor["Direction"],
                    $boardID,
                    $motor["MotorName"]
                );

                $motorUpdateStmt->execute();
                $motorUpdateStmt->close();

            } else {

                // Insert new motor
                $insertMotorStmt = $conn->prepare(
                    "INSERT INTO Motors
                     (MotorName, Speed, Direction, BoardID)
                     VALUES (?, ?, ?, ?)"
                );

                $insertMotorStmt->bind_param(
                    "siii",
                    $motor["MotorName"],
                    $motor["Speed"],
                    $motor["Direction"],
                    $boardID
                );

                $insertMotorStmt->execute();
                $insertMotorStmt->close();
            }

            $motorStmt->close();
        }


        // Process single Servo
        $servoStmt = $conn->prepare(
            "SELECT ID FROM Servos
             WHERE boardID=? AND ServoName=?"
        );

        $servoStmt->bind_param(
            "is",
            $boardID,
            $servos["ServoName"]
        );

        $servoStmt->execute();

        $result = $servoStmt->get_result();

        if ($result->fetch_assoc()) {

            // Update existing servo
            $servoUpdateStmt = $conn->prepare(
                "UPDATE Servos
                 SET Angle=?
                 WHERE boardID=? AND ServoName=?"
            );

            $servoUpdateStmt->bind_param(
                "iis",
                $servos["Angle"],
                $boardID,
                $servos["ServoName"]
            );

            $servoUpdateStmt->execute();
            $servoUpdateStmt->close();

        } else {

            // Insert new servo
            $insertServoStmt = $conn->prepare(
                "INSERT INTO Servos
                 (ServoName, Angle, BoardID)
                 VALUES (?, ?, ?)"
            );

            $insertServoStmt->bind_param(
                "sii",
                $servos["ServoName"],
                $servos["Angle"],
                $boardID
            );

            $insertServoStmt->execute();
            $insertServoStmt->close();
        }

        $servoStmt->close();

		// Process Controller

		// Process Controller

		$connected = !empty($inData["Controller"]["Connected"]) ? 1 : 0;

		$controllerStmt = $conn->prepare(
			"INSERT INTO Controllers (boardID, Connected)
			VALUES (?, ?)
			ON DUPLICATE KEY UPDATE Connected=?"
		);

		$controllerStmt->bind_param(
			"iii",
			$boardID,
			$connected,
			$connected
		);

		$controllerStmt->execute();
		$controllerStmt->close();


        sendResultInfoAsJson(json_encode([
            "success" => true,
            "error" => ""
        ]));

    } else {

        returnWithError("No Records Found");
    }

    $stmt->close();
    $conn->close();


    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithError($err)
    {
        sendResultInfoAsJson(json_encode([
            "success" => false,
            "error" => $err
        ]));
    }

?>
