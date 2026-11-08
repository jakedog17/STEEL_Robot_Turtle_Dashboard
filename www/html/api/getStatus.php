
<?php

    $inData = getRequestInfo();
    $boardInfo = $inData["UserBoards"];

    $conn = new mysqli(
        "localhost",
        "TheBeast",
        "WeLoveSTEELTurtle",
        "STEEL_Turtle"
    );

    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
        exit();
    }

    // Find user's board
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

        $boardID = $row["ID"];

        $returnJson = [];

        // Retrieve all motors
        $motorStmt = $conn->prepare(
            "SELECT ID, MotorName, Speed, Direction
             FROM Motors
             WHERE boardID=?"
        );

        $motorStmt->bind_param("i", $boardID);

        $motorStmt->execute();

        $motorResult = $motorStmt->get_result();

        while ($row = $motorResult->fetch_assoc()) {

            $returnJson["Motors"][$row["MotorName"]]["Speed"] =
                $row["Speed"];

            $returnJson["Motors"][$row["MotorName"]]["Direction"] =
                $row["Direction"];
        }

        // Retrieve all servos
        $servoStmt = $conn->prepare(
            "SELECT ID, ServoName, Angle
             FROM Servos
             WHERE boardID=?"
        );

        $servoStmt->bind_param("i", $boardID);

        $servoStmt->execute();

        $servoResult = $servoStmt->get_result();

        while ($row = $servoResult->fetch_assoc()) {

            $returnJson["Servos"][$row["ServoName"]]["Angle"] =
                $row["Angle"];
        }

        // Retrieve Controller status

        $controllerStmt = $conn->prepare(
            "SELECT Connected FROM Controllers WHERE boardID=?"
        );

        $controllerStmt->bind_param("i", $boardID);

        $controllerStmt->execute();

        $controllerResult = $controllerStmt->get_result();

        if ($controllerRow = $controllerResult->fetch_assoc()) {

            $returnJson["Controller"]["Connected"] =
                (bool)$controllerRow["Connected"];

        } else {

            $returnJson["Controller"]["Connected"] = false;
        }

        $controllerStmt->close();

        // Send JSON response
        sendResultInfoAsJson(json_encode($returnJson));

        $motorStmt->close();
        $servoStmt->close();

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
            "id" => 0,
            "error" => $err
        ]));
    }

?>
