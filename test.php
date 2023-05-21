<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

class binder{
    private $filePath;

    function __construct($path){
        $this->filePath = $path;
        if(!file_exists($path)){
            $this->createFile();
        }
    }

    function appendObjectToFile($object){
        $data = file_get_contents($this->filePath);
        if ($data === false) {
            throw new Exception("Error reading file");
        }
        $data = json_decode($data, true);
        $data[] = $object;
        file_put_contents($this->filePath, json_encode($data));
    }

    function getObjectFromId($id){
        $data = file_get_contents($this->filePath);
        if ($data === false) {
            throw new Exception("Error reading file");
        }
        $data = json_decode($data, true);
        foreach($data as $object){
            if($object['id'] == $id){
                return $object;
            }
        }
        throw new Exception("no such image binded to this id");
    }

    function deleteImageById($id){
        $data = file_get_contents($this->filePath);
        if ($data === false) {
            throw new Exception("Error reading file");
        }
        $data = json_decode($data, true);
        $newData = array();
        foreach($data as $object){
            if($object['id'] != $id){
                $newData[] = $object;
                $deleted = unlink($object['name']);
                if(!$deleted){
                    throw new Exception("Error deleting iamge: ".$object['name']);
                }
                break;
            }
        }
        file_put_contents($this->filePath, json_encode($newData));
    }
}

class httpResp{
    //good status
    public $OK = 200;
    public $CREATED = 201;
    public $ACCEPTED = 202;

    //bad status client side
    public $BAD_REQUEST = 400;
    public $UNAUTHORIZED = 401;
    public $FORBIDDEN = 403;
    public $NOT_FOUND = 404;
    public $PAYLOAD_TOO_LARGE = 413;
    public $UNSUPPORTED_MEDIA_TYPE = 415;
    public $TOO_MANY_REQUESTS = 429;

    //bad status server side
    public $INTERNAL_SERVER_ERROR = 500;
    public $NOT_IMPLEMENTED = 501;

    function __construct(){
    }

    function sendImage($code, $im, $object){
        header("Content-Type: image/".$object["fileType"]);
        header("HTTP/1.1 ".$code);

        switch ($object["fileType"]) {
            case "jpeg":
                imagejpeg($im);
                break;
            case "png":
                imagepng($im);
                break;
            case "gif":
                imagegif($im);
                break;
            default:
                $this->sendError($this->UNSUPPORTED_MEDIA_TYPE, "unsupported media type");
        }
        imagedestroy($im);
    }

    function sendError($code, $message){
        header('Content-Type: application/json');
        http_response_code($code);
        $error->error = true;
        $error->msg = $message;
        echo json_encode($error);
    }

    function sendErrorObject($code, $obj){
        header('Content-Type: application/json');
        http_response_code($code);
        $success->error = true;
        foreach($obj as $key=>$value){
            $success->$key = $value;
        }
        echo json_encode($success);
    }

    function sendSuccess($code, $message){
        header('Content-Type: application/json');
        http_response_code($code);
        $success->error = false;
        $success->msg = $message;
        echo json_encode($success);
    }

    function sendSuccessObject($code, $obj){
        header('Content-Type: application/json');
        http_response_code($code);
        $success->error = false;
        foreach($obj as $key=>$value){
            $success->$key = $value;
        }
        echo json_encode($success);
    }
}

class requests{
    private $file;
    private $tooManyRequests;
    private $timeout;

    function __construct($file, $tooManyRequests, $timeout){
        $this->file = $file;
        $this->tooManyRequests = $tooManyRequests;
        $this->timeout = $timeout;
    }

    function getAll(){
        $data = file_get_contents($this->file);
        if ($data === false) {
            throw new Exception("Error reading file");
        }
        $data = json_decode($data, true);
        return $data;
    }

    function getByIP($ip){
        $data = file_get_contents($this->file);
        if ($data === false) {
            throw new Exception("Error reading file");
        }
        $data = json_decode($data, true);
        foreach($data as $object){
            if($object['ip'] == $ip){
                return $object;
            }
        }
        return false;
    }

    function increaseRequest($ip){
        $data=$this->getByIP($ip);

        $object;

        if ($data === false) {
            $object->ip=$ip;
            $object->requests=1;
            $object->lastRequest=time();
            $this->appendObjectToFile($object);
        }else{
            if(time()-$data["lastRequest"] <= $this->timeout){
                if ($data['requests'] >= $this->tooManyRequests) {
                    throw new Exception("too many requests");
                }
                $data["requests"]++;
                $this->updateFile($data);
            }else{
                $data["lastRequest"] = time();
                $data["requests"] = 1;
                $this->updateFile($data);
            }
        }
    }

    function decreaseRequestCount($ip){
        $data=$this->getByIP($ip);
        if ($data === false) {
            throw new Exception("no such ip");
        }
        if ($data['requests'] <= 0) {
            throw new Exception("no requests");
        }
        $data["requests"]--;
        $this->updateFile($data);
    }

    function updateFile($obj){
        $data = file_get_contents($this->file);
        if ($data === false) {
            throw new Exception("Error reading file");
        }
        $data = json_decode($data, true);
        $newData = array();
        foreach($data as $object){
            if($object['ip'] == $obj['ip']){
                $newData[] = $obj;
            }else{
                $newData[] = $object;
            }
        }
        file_put_contents($this->file, json_encode($newData));
    }

    function appendObjectToFile($object){
        $data = file_get_contents($this->file);
        if ($data === false) {
            throw new Exception("Error reading file");
        }
        $data = json_decode($data, true);
        $data[] = $object;
        file_put_contents($this->file, json_encode($data));
    }
}

class images{
    private $baseFolder;
    public $httpHandler;
    private $bindHandler;
    private $requestsHandler;

    function __construct($baseFolder){
        $this->baseFolder = $baseFolder;
        $this->httpHandler = new httpResp();
        $this->bindHandler = new binder("binds.json");
        $this->requestsHandler = new requests("requests.json", 20, 3600);
    }
    
    function generateGDObjectFromID($id){
        $obj = $this->bindHandler->getObjectFromId($id);
        switch ($obj["fileType"]) {
            case "jpeg":
                $image = imagecreatefromjpeg($obj["fileName"]);
                break;
            case "png":
                $image = imagecreatefrompng($obj["fileName"]);
                break;
            case "gif":
                $image = imagecreatefromgif($obj["fileName"]);
                break;
            default:
                $this->httpHandler->sendError($this->httpHandler->UNSUPPORTED_MEDIA_TYPE, "unsupported media type");
                throw new Exception("unsupported media type");
        }
        return $image;
    }

    function createNewImage(){
        $object="";
        $object->originalName = basename($_FILES["upload"]["name"]);
        $targetFile = $this->baseFolder . basename($_FILES["upload"]["name"]);
        $fileDetails = pathinfo($targetFile);
        $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

        //check if the image is actually an image
        $check = getimagesize($_FILES["upload"]["tmp_name"]);
        if($check === false) {
            $this->httpHandler->sendError($this->httpHandler->BAD_REQUEST, "File is not an image.");
            return;
        }

        //check the file extension
        if(strtolower($imageFileType) != "gif" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpeg") {
            $this->httpHandler->sendError($this->httpHandler->UNSUPPORTED_MEDIA_TYPE, "File extension not supported, required gif, jpeg, png, given: " . $imageFileType);
            return;
        }

        if ($_FILES["upload"]["size"] > 500000) { //500kb
            $this->httpHandler->sendError($this->httpHandler->PAYLOAD_TOO_LARGE, "File is too large, must be smaller then 100KB, size of this file is: ". $_FILES["upload"]["size"]);
            return;
        }

        //assing a unique name
        $counter = 0;
        while(file_exists($targetFile)){
            $targetFile = $this->baseFolder . $fileDetails["filename"].$counter.".".$imageFileType;
            $counter++;
        }

        $ip=getRealIPAddress();

        try{
            $this->requestsHandler->increaseRequest($ip);
        }catch(Exception $e){
            $this->httpHandler->sendError($this->httpHandler->TOO_MANY_REQUESTS, "too many requests");
            return;
        }

        //move the file in the folder
        if (!move_uploaded_file($_FILES["upload"]["tmp_name"], $targetFile)) {
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error moving the file, upload failed.");
            $this->requestsHandler->decreaseRequestCount($ip);
            return;
        }

        $object->id=uniqid("file_");
        $object->fileName=$targetFile;
        $object->fileType=$imageFileType;
        try{
            $this->bindHandler->appendObjectToFile($object);
        }catch(Exception $e){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, $e->getMessage());
            $this->requestsHandler->decreaseRequestCount($ip);
            return;
        }
        
        $this->httpHandler->sendSuccessObject($this->httpHandler->CREATED, $object);
        return $object;
    }

    function crop($im, $id, $x, $y, $width, $height){
        $im2 = imagecrop($im, ['x'=>$x, 'y'=>$y, 'width'=>$width, 'height'=>$height]);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error cropping the image");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im2, $this->bindHandler->getObjectFromId($id));
    }

    //TODO add color option
    //mode must be between 0 and 3
    function cropAuto($im, $id, $mode){
        $im2 = imagecropauto($im, $mode);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error cropping the image");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im2, $this->bindHandler->getObjectFromId($id));
    }

    //given image, id, and mode (between 0 and 2) it will return the image flipped in the requested direction
    function flip($im, $id, $mode){
        $im2 = imageflip($im, $mode+1);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error flipping the image");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function gamma($im, $id, $inGamma, $outGamma) {
        $im2 = imagegammacorrect($im, $inGamma, $outGamma);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying gamma");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function scale($im, $id, $newWidth, $newHeight){
        $im2 = imagescale($im, $newWidth, $newHeight);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error scaling the image");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im2, $this->bindHandler->getObjectFromId($id));
    }

    function rotate($im, $id, $deg){
        $im2 = imagerotate($im, $deg, 0);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error rotating the image");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im2, $this->bindHandler->getObjectFromId($id));
    }

    function negative($im, $id){
        $im2 = imagefilter($im, IMG_FILTER_NEGATE);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying negative filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function grayScale($im, $id){
        $im2 = imagefilter($im, IMG_FILTER_GRAYSCALE);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying gray scale  filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function brightness($im, $id, $brightness){
        $im2 = imagefilter($im, IMG_FILTER_BRIGHTNESS, $brightness);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying brightness filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function contrast($im, $id, $contrast){
        $im2 = imagefilter($im, IMG_FILTER_CONTRAST, $contrast);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying contrast filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function colorize($im, $id, $red, $green, $blue){
        $im2 = imagefilter($im, IMG_FILTER_COLORIZE, $red, $green, $blue);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying colorize filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function edgeDetection($im, $id){
        $im2 = imagefilter($im, IMG_FILTER_EDGEDETECT);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying edge detection filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function emboss($im, $id){
        $im2 = imagefilter($im, IMG_FILTER_EMBOSS);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying emboss filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function gaussianBlur($im, $id){
        $im2 = imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying gaussian blur filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function selectiveBlur($im, $id){
        $im2 = imagefilter($im, IMG_FILTER_SELECTIVE_BLUR);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying selective blur filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function meanRemoval($im, $id){
        $im2 = imagefilter($im, IMG_FILTER_MEAN_REMOVAL);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying mean removal filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function smooth($im, $id, $smooth){
        $im2 = imagefilter($im, IMG_FILTER_SMOOTH, $smooth);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying smooth filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function pixelate($im, $id, $pixelate){
        $im2 = imagefilter($im, IMG_FILTER_PIXELATE, $pixelate, true);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying pixelate filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    function scatter($im, $id, $scatter1, $scatter2){
        $im2 = imagefilter($im, IMG_FILTER_SCATTER, $scatter1, $scatter2);
        if($im2 === false){
            $this->httpHandler->sendError($this->httpHandler->INTERNAL_SERVER_ERROR, "error applying scatter filter");
            return;
        }
        $this->httpHandler->sendImage($this->httpHandler->OK, $im, $this->bindHandler->getObjectFromId($id));
    }

    // function convert($im, $id, $type){
    //     $convert->fileType=$type;
    //     $this->httpHandler->sendImage($this->httpHandler->OK, $im, $convert);
    // }

}

function getRealIPAddress() {
    $ip= "";
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip= $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$imageHandler = new images("uploads/");
//upload a new image
if(isset($_POST["submit"])) {
    $imageHandler->createNewImage();
}

if(isset($_GET["id"])){
    $id = $_GET["id"];
    if(isset($_GET["function"])){
        $function = $_GET["function"];
        $image = $imageHandler->generateGDObjectFromID($id);

        switch($function){
            case "crop"://✔️
                if(!isset($_GET["x"]) || !isset($_GET["y"]) || !isset($_GET["width"]) || !isset($_GET["height"])){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameters, must define 'x', 'y', 'height', 'width'");
                    return;
                }
                $imageHandler->crop($image, $id, $_GET["x"], $_GET["y"], $_GET["width"], $_GET["height"]);
                break;
            case "crop-auto"://✔️
                if(!isset($_GET["mode"])){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameter, must define 'mode' (between 0 and 4)");
                    return;
                }
                if ($_GET["mode"] < 0 || $_GET["mode"] > 4) {
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "invalid mode, must be between 0 and 4");
                    return;
                }
                $imageHandler->cropAuto($image, $id, $_GET["mode"]);
                break;
            case "flip"://✔️
                if(!isset($_GET["mode"])){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameter, must define 'mode' (between 0 and 2)");
                    return;
                }
                if ($_GET["mode"] < 0 || $_GET["mode"] > 2) {
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "invalid mode, must be between 0 and 2");
                    return;
                }
                $imageHandler->flip($image, $id, $_GET["mode"]);
                break;
            case "gamma"://✔️
                if(!isset($_GET["gin"]) || !isset($_GET["gout"])){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameter, must define gin (gamma input) and gout (gamma output)");
                    return;
                }
                $imageHandler->gamma($image, $id, $_GET["gin"], $_GET["gout"]);
                break;
            case "scale"://✔️
                if(!isset($_GET["width"])){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameters, must define 'width' and 'height' (height is optional)");
                    return;
                }
                $height = isset($_GET["height"]) ? $_GET["height"] : -1;
                $imageHandler->scale($image, $id, $_GET["width"], $height);
                break;
            case "rotate"://✔️
                if(!isset($_GET["deg"])){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameter, must define 'deg' (degrees to rotate)");
                    return;
                }
                $imageHandler->rotate($image, $id, $_GET["deg"]);
                break;
            case "negative"://✔️
                $imageHandler->negative($image, $id);
                break;
            case "gray-scale"://✔️
                $imageHandler->grayScale($image, $id);
                break;
            case "brightness"://✔️
                if(!isset($_GET["brightness"])){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameter, must define 'brightness' (brightness to apply, must be between -255 and 255)");
                    return;
                }
                if ($_GET["brightness"] < -255 || $_GET["brightness"] > 255){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "invalid brightness, must be between -255 and 255");
                    return;
                }
                $imageHandler->brightness($image, $id, $_GET["brightness"]);
                break;
            case "contrast"://✔️
                if(!isset($_GET["contrast"])){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameter, must define 'contrast' (contrast to apply, must be between -100 and 100)");
                    return;
                }
                if ($_GET["contrast"] < -100 || $_GET["contrast"] > 100){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "invalid contrast, must be between -100 (max) and 100 (min)");
                    return;
                }
                $imageHandler->contrast($image, $id, $_GET["contrast"]);
                break;
            case "colorize"://✔️
                if(!isset($_GET["red"]) || !isset($_GET["green"]) || !isset($_GET["blue"])){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameters, must define 'red', 'green', 'blue'");
                    return;
                }
                if ($_GET["red"] < -255 || $_GET["red"] > 255 || $_GET["green"] < -255 || $_GET["green"] > 255 || $_GET["blue"] < -255 || $_GET["blue"] > 255){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "invalid color, must be between -255 and 255");
                    return;
                }
                $imageHandler->colorize($image, $id, $_GET["red"], $_GET["green"], $_GET["blue"]);
                break;
            case "edge-detect"://✔️
                $imageHandler->edgeDetection($image, $id);
                break;
            case "emboss"://✔️
                $imageHandler->emboss($image, $id);
                break;
            case "gaussian-blur"://✔️
                $imageHandler->gaussianBlur($image, $id);
                break;
            case "selective-blur"://✔️
                $imageHandler->selectiveBlur($image, $id);
                break;
            case "mean-removal"://✔️ //sketch effect
                $imageHandler->meanRemoval($image, $id);
                break;
            case "smoothing"://✔️
                if(!isset($_GET["smoothing"])){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameter, must define 'smoothing'");
                    return;
                }
                $imageHandler->smooth($image, $id, $_GET["smoothing"]);
                break;
            case "pixelate"://✔️
                if(!isset($_GET["pixelate"])){
                    $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameter, must define 'pixelate'");
                    return;
                }
                $imageHandler->pixelate($image, $id, $_GET["pixelate"]);
                break;
            case "scatter"://✔️ ❌non so bene come funzioni
                // if(!isset($_GET["scatter1"])  || !isset($_GET["scatter2"])){
                //     $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameters, must define 'scatter1' and 'scatter2'");
                //     return;
                // }
                // $imageHandler->scatter($image, $id, $_GET["scatter1"], $_GET["scatter2"]);
                $imageHandler->httpHandler->sendError($imageHandler->httpHandler->NOT_IMPLEMENTED, "method not implemented");
                break;
            case "convert"://❌
                // if(!isset($_GET["format"])){
                //     $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing parameter, must define 'format'");
                //     return;
                // }
                // if($_GET["format"] != "jpeg" && $_GET["format"] != "png" && $_GET["format"] != "gif"){
                //     $imageHandler->httpHandler->sendError($imageHandler->httpHandler->UNSUPPORTED_MEDIA_TYPE, "invalid format, must be jpeg, png or gif");
                //     return;
                // }

                // $imageHandler->convert($image, $id, $_GET["format"]);
                // break;
                $imageHandler->httpHandler->sendError($imageHandler->httpHandler->NOT_IMPLEMENTED, "method not implemented");
            default://✔️
                $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "function not supported");
            }
    }else{
        $imageHandler->httpHandler->sendError($imageHandler->httpHandler->BAD_REQUEST, "missing function name");
    }
}
?>