<?php
// (C) 2012 hush2 <hushywushy@gmail.com>

$host = "http://{$_SERVER['HTTP_HOST']}/";

if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $remote = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $remote = $_SERVER['REMOTE_ADDR'];
}
$robo_url = $host . $remote . '?set=set1&size=300x300';
?>

<!doctype html>
<html>

<head>
    <link href="css/styles.css" rel="stylesheet" />
    <script src='js/main.js' type='text/javascript'> </script>
    <script src='js/names.js' type='text/javascript'> </script>
    <title>R O B O H A S H</title>
</head>

<body>

<div class="container">

<div class="row">
    <div class='span10 offset1 logo'>
        <img src='images/logo.png'>   
    </div>
    
    <noscript>
        <div class='row'><div class='span4 offset4 alert alert-error'>
            <p>Javascript is required to view this page properly.</p>
        </div></div>
    </noscript>    

    <div class="span3">

    <form id='form1'>

        <div class='opts'>
            <label>Enter any text:</label>
            <input id='text' class='span3' type='text' value="<?= $remote ?>"/>
        </div>
        <div>
            <p class='grav'><input id='grav' type='checkbox' name='grav' />  &nbsp;Text is a Gravatar email or hash</p>
        </div>
        <div class='opts'>
            <label>Set:</label>
            <select name="sets" id='sets' >
                <option value="set1" selected="selected">Set 1</option>
                <option value="set2">Set 2</option>
                <option value="set3">Set 3</option>
                <option value="">Any</option>
            </select>
        </div>
        <div class='opts'>
            <label>Color:</label>
            <select name="colors" id='colors' >
                <option value="" selected="selected">Any</option>
                <option value="blue">Blue</option>
                <option value="brown">Brown</option>
                <option value="green">Green</option>
                <option value="grey">Grey</option>
                <option value="orange">Orange</option>
                <option value="pink">Pink</option>
                <option value="purple">Purple</option>
                <option value="red">Red</option>
                <option value="white">White</option>
                <option value="yellow">Yellow</option>
            </select>
        </div>
        <div class='opts'>
            <label>Background:</label>
            <select name="bgsets" id='bgsets' >
                <option value="" selected="selected">None</option>
                <option value="bg1">Set 1</option>
                <option value="bg2">Set 2</option>
                <option value="any">Any</option>
            </select>
        </div>
        <div class='opts'>
            <label>Size:</label>
            <input id='sizew' class='input-mini' type='text' value='300'> X <input id='sizeh' class='input-mini' type='text' value='300'>
        </div>
        </br>
        <div class='generate'>
            <input id='generate' type="submit" name="generate" class="btn-large btn-primary" value="Generate"/>
        </div>

    </form>

    </div>

    <div class="span6 robo">
        <img id='robo' src="<?= $robo_url ?>" />
    </div>
    <div class='span3 right_panel'>
        <p>Generate a random Robot.</p>
        <div class='opts'>
            <label>Set:</label><input type='checkbox' id='cb_set' name='cb_set' checked='checked'>
        </div>
        <div class='opts'>
            <label>Color:</label><input type='checkbox' id='cb_color' name='cb_color' checked='checked'>
        </div>
        <div class='opts'>
            <label>Background:</label><input id='cb_bg' type='checkbox' name='cb_bg'>
        </div>
        <br/>
    <div class='random'><input type="submit" id="random" class="btn-large btn-success" value="Random"/></div>
    </div>
</div>

<div class='row'>
    <div class='span8 offset4'>
        <a id='url' href="<?= $robo_url ?>"><?= $robo_url ?></a>
    </div>
</div>
<div class='row'>
    <div class="span12 footer">
    <p><a href='https://github.com/hush2/php-robohash'>php-robohash</a> &copy; jeff (<a href='mailto:hushywushy@gmail.com?subject=robohash'>hush2</a>)</p>
    <p><a href='http://robohash.org'>robohash</a> &copy; colin davis (<a href='https://github.com/e1ven/Robohash'>e1ven</a>)</p>
    </div>
</div>

</div>

</body>
</html>
