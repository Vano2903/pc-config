<?php
    //x get config id from uuid
    //x get component from component id
    //x check if the component is in the config, if yes do nothing
    //x check if there is another component of the same category, if yes replace it, if no add it
    //x update the config in the db

    session_start();
    if (!isset($_SESSION["config-id"])){
        //set response code to 400
        http_response_code(400);
        echo "config id not found in session";
        exit();
    }

    include "config.php";
    $uuid = $_SESSION["config-id"];
    $componentID = $_GET["cid"];
    
    //get config id from uuid
    $sql = "SELECT * FROM configs WHERE uuid='" . $uuid . "'";
    $result = $con->query($sql);
    if ($result->num_rows == 0){
        http_response_code(400);
        echo "config not found";
        exit();
    }
    $config = $result->fetch_assoc();

    //get component from component id
    $sql = "SELECT * FROM components WHERE ID=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $componentID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0){
        http_response_code(400);
        echo "component not found";
        exit();
    }
    $component = $result->fetch_assoc();

    //check if the component is in the config, if yes do nothing
    $sql = "SELECT * FROM components WHERE ID IN (SELECT componentID FROM configContents WHERE configurationID=" . $config["ID"] . ")";
    $result = $con->query($sql);
    if ($result->num_rows > 0){
        foreach($result as $row){
            if ($row["ID"] == $component["ID"]){
                http_response_code(400);
                echo "component already in config, nothing to do";
                exit();
            }
        }
    }

    //check if there is another component of the same category, if yes update it, if no add it
    $sql = "SELECT * FROM components WHERE categoryID=" . $component["categoryID"] . " AND ID IN (SELECT componentID FROM configContents WHERE configurationID=" . $config["ID"] . ")";
    $result = $con->query($sql);
    if ($result->num_rows > 0){
        //UPDATE configContents SET componentID=newcompid WHERE configurationID=configid AND componentID=oldcompid
        $sql="UPDATE configContents SET componentID=" . $componentID . " WHERE configurationID=" . $config["ID"] . " AND componentID=" . $result->fetch_assoc()["ID"];
        $result = $con->query($sql);
        if ($con->affected_rows == 0){
            http_response_code(500);
            echo "error updating config";
            exit();
        }
    }else{
        //INSERT INTO configContents (configurationID, componentID) VALUES (configid, compid)
        $sql = "INSERT INTO configContents (configurationID, componentID) VALUES (" . $config["ID"] . ", " . $component["ID"] . ")";
        $result = $con->query($sql);
        if ($con->affected_rows == 0){
            http_response_code(500);
            echo "error inserting config";
            exit();
        }
    }

    http_response_code(200);
    echo "config updated successfully";
?>