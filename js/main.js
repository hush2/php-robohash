// (C) 2012 hush2 <hushywushy@gmail.com>
//
// My first Javascript program!!! :)

window.onload = function() {

    img = document.getElementById('robo');
    url = document.getElementById('url');

    var set = document.getElementById('sets');

    set.onchange = function() {
        color = document.getElementById('colors');
        color.disabled = set.value != 'set1';
    }

    // Random button
    var rnd = document.getElementById('random');

    rnd.onclick = function() {

        var colors = ['blue', 'brown', 'green', 'grey', 'orange', 'pink', 'purple', 'red', 'white', 'yellow'];
        var sets   = ['set1','set2', 'set3'];
        var bgsets = ['bg1', 'bg2'];

        var color = document.getElementById('cb_color').checked;
        var bg    = document.getElementById('cb_bg').checked;

        var text  = document.getElementById('text');

        var id_sets   = document.getElementById('sets');
        var id_colors = document.getElementById('colors');
        var id_bgsets = document.getElementById('bgsets');

        id_sets.value    =  _set   = '';
        id_colors.value  =  _color = '';
        id_bgsets.value  =  _bg    = '';

        var set   = document.getElementById('cb_set').checked;
        if (set) {
            var _set = array_rand(sets);
            id_sets.value = _set;
            _set = 'set=' + _set;
        }

        if (color && _set == 'set=set1') {
            var _color = array_rand(colors);
            id_colors.value = _color;
            _color = '&color=' + _color;
        } else {
            // disable check box?
            color = document.getElementById('colors');
            color.disabled = set.value != 'set1';
        }      

        if (bg) {
            var _bg =  array_rand(bgsets);
            id_bgsets.value = _bg;
            _bg = '&bgset=' + _bg;
        }

        _text = array_rand(adjectives) + ' ' + array_rand(names)

        text.value = _text;

        url.href =  _text + '?' + _set + _color + _bg + '&size=300x300';

        img.src = url.innerHTML = url.href;

    }

    // Generate button
    var gen = document.getElementById('generate');

    gen.onclick = function() {

        var text = document.getElementById('text').value;

        var grav = document.getElementById('grav').checked;
        var re = /\S+@\S+/; // weak email check

        if (grav) {
            if (re.test(text)) {
                grav_url =  text + "?gravatar=yes";
            } else {
                grav_url =  text + "?gravatar=hashed";
            }
            url.href = grav_url;
            img.src = url.innerHTML = url.href;
            return false;
        }

        var set   = document.getElementById('sets').value;
        var color = document.getElementById('colors').value;
        var bg    = document.getElementById('bgsets').value;
        var sizew = document.getElementById('sizew').value;
        var sizeh = document.getElementById('sizeh').value;

        var _set = ''
        if (set != '') {
            _set = 'set=' + set;
        }

        var _color = '';
        if (color != '' && set == 'set1') {
            _color = '&color=' + color;
        }

        var _bg = '';
        if (bg != '') {
            _bg = '&bgset=' + bg;
        }

        var sizew = parseInt(sizew);
        if (isNaN(sizew) || sizew > 1024 || sizew < 10) {
            sizew = 300;
        }

        var sizeh = parseInt(sizeh);
        if (isNaN(sizeh) || sizeh > 1024 || sizeh < 10) {
            sizeh = 300;
        }

        var _size = '&size=' + sizew + 'x' + sizeh;

        var _text = '/' + text + '?';

        // construct the url
        url.href = _text + _set + _color + _bg + _size;
        img.src = url.innerHTML = url.href;

        return false;
    }

    names = names.split(',')
    adjectives = adjectives.split(',')
}

function array_rand(array)
{
    return array[Math.floor(Math.random() * array.length)];
}

//function rand_text(length)
//{
    //var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
    //var text = '';

    //for (var i = 0; i < length; i++) {
        //n = Math.floor(Math.random() * chars.length);
        //text += chars.charAt(n);
    //}
    //return text;
//}

