<?php

function strtotitle($title) // Converts $title to Title Case, and returns the result.
{
    // Our array of 'small words' which shouldn't be capitalised if
    // they aren't the first word. Add your own words to taste.

    $smallwordsarray = array( 'of','a','the','and','an','or','nor','but','is','if','then','else','when', 'at','from','by','on','off','for','in','out','over','to','into','with' );
    // Split the string into separate words
    $words = explode(' ', $title);

    foreach ($words as $key => $word)
    {

    // If this word is the first, or it's not one of our small words, capitalise it
    // with ucwords().

        if ($key == 0 or !in_array($word, $smallwordsarray))
        {
            $words[$key] = ucwords($word);
        }

    }

    // Join the words back into a string
    $newtitle = implode(' ', $words);

    return $newtitle;

}

    function strtoslug($str)
    {
        $str = strtolower($str);
        $str = trim($str);
        $str = str_replace(' ', '-', $str);
        $str = str_split($str);
        $allowed_charas = 'abcdefghijklmnopqrstuvwxyz1234567890-';
        $allowed_charas = str_split($allowed_charas);
        $slug_array = array();
        foreach($str as $chara)
        {
            if (in_array($chara, $allowed_charas))
            {
                $slug_array[] = $chara;
            }
        }
        $str = implode('', $slug_array);
        $str = str_replace('--', '-', $str);
        $str = trim($str, '-');

        return $str;
    }

?>
