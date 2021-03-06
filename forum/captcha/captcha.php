<?php

// We don't need to start a session since SMF has already.
//session_start();
$captcha = new captcha();

class captcha
{
    function __construct()
    {   
        if(isset($_GET['new'])){
            unset($_SESSION['captchaSuccess']);
            unset($_SESSION['randomID']);
            unset($_SESSION['checkSuccess']);
        }
        if(isset($_SESSION['randomID'])) //if session has been created, checks to see if the success condition has been met
        {
            Header("Content-type: text/plain");
            if($this->checkSuccess())
            {
                echo 'true';
                $_SESSION['captchaSuccess']=true;
            }
        }
        else //generates new challenge
        {
            $_SESSION['randomID']=$this->generateID();
            for($i = 1; $i <= 5; $i++) 
            {
                array_push($_SESSION['randomID']['fish'], $this->generateFish());
            }
            $this->generateCaptcha();
        }
        exit();
    }

    //generates a new and 'unique' random fish
    private function generateFish()
    {
        $traits = array('striped', 'dead', 'normal');
        $fishData = array('trait' => $traits[array_rand($traits)], 'x' => rand(0,324), 'y' => rand(2,120), 'left' => (bool)rand(0,1));
        
        while($fishData['trait'] === $_SESSION['randomID']['challenge'])
        {
            $fishData['trait'] = $traits[array_rand($traits)];
        }
        $attempts = 0;
        while($this->overlaps($fishData['x'], $fishData['y'], $fishData['left']))
        {
            $attempts++;
            if($attempts>=25)
            {
                $fishData['left'] = !($fishData['left']);
            }
            $fishData['x'] = rand(0,324);
            $fishData['y'] = rand(2,120);
        }
        return $fishData;
    }

    //checks to see if a hypothetical fish would overlap an existing fish
    private function overlaps($x, $y, $left)
    {
        foreach($_SESSION['randomID']['fish'] as $fishy){
            if(($left == $fishy['left']) && //check if on same layer
                (
                    (($x>=$fishy['x']&&$x<=($fishy['x']+96)) || ($x<=$fishy['x']&&($x+96)>=$fishy['x'])) && //check if x position is overlapping
                    (($y>=$fishy['y']&&($y-54)<=$fishy['y']) || ($y<=$fishy['y']&&$y>=($fishy['y']-54))) // check if y position is overlapping
                )
            )
            {
                return true;
            }
        }
        return false;
    }

    //generates randomID/captcha challenge
    private function generateID()
    {
        $challenges = array('striped', 'dead', 'dark', 'cool');
        $challenge = $challenges[array_rand($challenges)];
        $generatedID = //instantiate the random data for the captcha
        [
            'challenge' => $challenge,
            'generated_at' => date('m/d/Y h:i:s', time()),
            'fish' => array(
                ['trait' => $challenge, 'x' => rand(0,324), 'y' => rand(2,120), 'left' => (bool)rand(0,1)]
            )
        ];
        
        return $generatedID;
    }

    //generates captcha data and sends it to client as a JSON
    private function generateCaptcha()
    {
        $fishImages = 
        [
            0 => imagecreatefrompng('captcha/captcha-assets/normal.png'),
            1 => imagecreatefrompng('captcha/captcha-assets/stripe.png'),
            2 => imagecreatefrompng('captcha/captcha-assets/dark.png'),
            3 => imagecreatefrompng('captcha/captcha-assets/cool.png')
        ];
        $layers = 
        [
            0 => imagecreatefrompng('captcha/captcha-assets/bglayer.png'),
            1 => imagecreatetruecolor(420, 240),
            2 => imagecreatetruecolor(420, 240)
        ];

        $textColor = imagecolorallocate($layers[0], 160, 15, 150);
        $font = imagettftext($layers[0], 30, 0, 15, 220, $textColor, 'captcha/captcha-assets/dpcomic.ttf', "Catch the " . $_SESSION['randomID']['challenge'] . " fish!");

        for($i=1; $i <= 2; $i++) 
        {   
            imagealphablending($layers[$i], false);
            $transparency = imagecolorallocatealpha($layers[$i], 0, 0, 0, 127);
            imagefilledrectangle($layers[$i], 0, 0, 420, 240, $transparency);
            imagealphablending($layers[$i], true);
            imagesavealpha($layers[$i], true);
            
            if($i>1)
            {
                for($j=0; $j<count($fishImages); $j++)
                {
                    imageflip($fishImages[$j], IMG_FLIP_HORIZONTAL);
                }
            } 
            foreach($_SESSION['randomID']['fish'] as $fishy)
            {
                if($i==1 && !$fishy['left'])
                {
                    $this -> drawFish($fishImages, $layers[$i], $fishy);
                }
                if($i==2 && $fishy['left'])
                {
                    $this -> drawFish($fishImages, $layers[$i], $fishy);
                }
            }
        }

        Header("Content-type: application/json");
        $response_json = array
        (
            'background' => captcha::getLayerBase64($layers[0]),
            'right' => captcha::getLayerBase64($layers[1]),
            'left' => captcha::getLayerBase64($layers[2]),
        );
        echo json_encode($response_json);
    }

    //draws fish to respective layers
    private function drawFish($fishImages, $layer, $fishy){
        $fish_width = 96;
        $fish_height = 54;
        switch($fishy['trait'])
        {
            case 'normal':
                imagecopy($layer, $fishImages[0], $fishy['x'], $fishy['y'], 0, 0, $fish_width, $fish_height);
                break;
            case 'striped':
                imagecopy($layer, $fishImages[1], $fishy['x'], $fishy['y'], 0, 0, $fish_width, $fish_height);
                break;
            case 'dead':
                imageflip($fishImages[0], IMG_FLIP_VERTICAL);
                imagecopy($layer, $fishImages[0], $fishy['x'], $fishy['y'], 0, 0, $fish_width, $fish_height);
                imageflip($fishImages[0], IMG_FLIP_VERTICAL);
                break;
            case 'dark':
                imagecopy($layer, $fishImages[2], $fishy['x'], $fishy['y'], 0, 0, $fish_width, $fish_height);
                break;
            case 'cool':
                imagecopy($layer, $fishImages[3], $fishy['x'], $fishy['y'], 0, 0, $fish_width, $fish_height);
                break;
        }
    }

    //converts a php gd image object to a base64 png
    private static function getLayerBase64($img){
        ob_start(); 
        imagepng($img);
        $data = base64_encode(ob_get_contents());
        ob_end_clean();
        imagedestroy($img);
        return 'data:image/png;base64,' . $data;
    }
    
    //returns true if user has dragged net and held over success fish for at least 3 seconds
    private function checkSuccess()
    {
        $pixelsPerSec = 65; //corresponds to pixelsPerSec in captcha.js

        if(!isset($_SESSION['checkSuccess']))
        {
            $_SESSION['checkSuccess']['successTimer'] = 0; //initialize timer to keep track of how long the success condition has been true
            $_SESSION['checkSuccess']['startTime'] = round(microtime(true)*1000); //record time challenge started
        }
        $_SESSION['mouse'] = ['x' => $_GET['x'], 'y' => $_GET['y']]; //retrieve mouse data
        $targetFish = $_SESSION['randomID']['fish'][0]; //correct fish is always 1st in the array
        $dxPos = $pixelsPerSec * (round(microtime(true)*1000) - $_SESSION['checkSuccess']['startTime'])/1000; //magnitude of change in x position since start of challenge

        if($targetFish['left']) //calculated position of the target fish
        {
            if($targetFish['x']-$dxPos) //xPos based on direction fish is moving
            {
                $fishPos['x'] = (($targetFish['x']-$dxPos) % 420) + 420;
            }
            else $fishPos['x'] = (($targetFish['x']-$dxPos) % 420);
        }
        else $fishPos['x'] = ($targetFish['x']+$dxPos) % 420;
        $fishPos['y'] = $targetFish['y'];

        if((($_SESSION['mouse']['x'] >= $fishPos['x'] && $_SESSION['mouse']['x'] <= $fishPos['x']+96) //check to see if user is over the target fish
                || ($fishPos['x']+96 >= 420 && $_SESSION['mouse']['x'] <= 420-$fishPos['x']+96))
            && ($_SESSION['mouse']['y'] >= $fishPos['y'] && $_SESSION['mouse']['y'] <= $fishPos['y']+54))
        {
            if($_SESSION['checkSuccess']['successTimer'] == 0) //if first success, start timer
            {
                $_SESSION['checkSuccess']['successTimer'] = round(microtime(true)*1000);
            }

            if((round(microtime(true)*1000) - $_SESSION['checkSuccess']['successTimer'])/1000 >= 1.25) //else check timer (challenge success happens at 2 seconds)
            {
                return true;
            }
            else return false; //not yet 2 seconds, no success
        }
        else
        {
            $_SESSION['checkSuccess']['successTimer'] = 0; //reset timer on fail
            return false; //no success
        }

        
    }
}

?>