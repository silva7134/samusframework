<?php
/**
 * Description of CleanString
 *
 * @author Vinicius Fiorio - samusdev@gmail.com.br
 * @package util
 */
class CleanString {

    /**
     * Array com os termos que serão substituidos
     * @var array
     */
    private static $removeArray = array(
            " " => "_" ,
            "a" => "a" ,
            "A" => "A" ,
            "b" => "b" ,
            "B" => "B" ,
            "c" => "c" ,
            "C" => "C" ,
            "d" => "d" ,
            "D" => "D" ,
            "e" => "e" ,
            "E" => "E" ,
            "f" => "f" ,
            "F" => "F" ,
            "g" => "g" ,
            "G" => "G" ,
            "h" => "h" ,
            "H" => "H" ,
            "i" => "i" ,
            "I" => "I" ,
            "j" => "j" ,
            "J" => "J" ,
            "k" => "k" ,
            "K" => "K" ,
            "l" => "l" ,
            "L" => "L" ,
            "m" => "m" ,
            "M" => "M" ,
            "n" => "n" ,
            "N" => "N" ,
            "o" => "o" ,
            "O" => "O" ,
            "p" => "p" ,
            "P" => "P" ,
            "q" => "q" ,
            "Q" => "Q" ,
            "r" => "r" ,
            "R" => "R" ,
            "s" => "s" ,
            "S" => "S" ,
            "t" => "t" ,
            "T" => "T" ,
            "u" => "u" ,
            "U" => "U" ,
            "v" => "v" ,
            "V" => "V" ,
            "x" => "x" ,
            "X" => "X" ,
            "y" => "y" ,
            "Y" => "Y" ,
            "W" => "W" ,
            "z" => "z" ,
            "Z" => "Z" ,
            "á" => "a" ,
            "Á" => "A" ,
            "é" => "e" ,
            "É" => "E" ,
            "í" => "i" ,
            "Í" => "I" ,
            "ó" => "o" ,
            "Ó" => "O" ,
            "ú" => "u" ,
            "Ú" => "U" ,
            "à" => "a" ,
            "À" => "A" ,
            "è" => "e" ,
            "È" => "E" ,
            "ì" => "i" ,
            "Ì" => "I" ,
            "ò" => "o" ,
            "Ò" => "O" ,
            "ù" => "ù" ,
            "Ù" => "U" ,
            "ã" => "a" ,
            "Ã" => "A" ,
            "õ" => "o" ,
            "Õ" => "O" ,
            "â" => "a" ,
            "Â" => "A" ,
            "ê" => "e" ,
            "Ê" => "E" ,
            "î" => "i" ,
            "Î" => "I" ,
            "ô" => "o" ,
            "Ô" => "P" ,
            "û" => "u" ,
            "Û" => "U" ,
            "," => ""  ,
            "!" => "" ,
            "#" => "" ,
            "%" => "",
            "¬" => "" ,
            "-" => "_" ,
            "{" => "" ,
            "}" => "" ,
            "^" => ""  ,
            "´" => "" ,
            "`" => "" ,
            "\\" => "" ,
            "/" => "" ,
            ";" => "" ,
            ":" => "" ,
            "?" => "" ,
            "¹" => "1" ,
            "²" => "2" ,
            "³" => "3" ,
            "ª" => "a" ,
            "º" => "o" ,
            "ç" => "c" ,
            "Ç" => "c" ,
            "ü" => "u" ,
            "Ü" , "U" ,
            "ä" => "a" ,
            "Ä" , "A" ,
            "ï" => "i" ,
            "Ï" , "I" ,
            "ö" => "o" ,
            "Ö" , "O" ,
            "ë" => "e" ,
            "Ë" , "E" ,
            "$" => "s" ,
            "ÿ" => "y" ,
            "w" => "w" ,
            "<" => "" ,
            ">" => "" ,
            "[" => "" ,
            "]" => "" ,
            "&" => "e" ,
            "'" => '' ,
            '"' => ""  ,
            '1' => '1' ,
            '2' => '2' ,
            '3' => '3' ,
            '4' => '4' ,
            '5' => '5' ,
            '6' => '6' ,
            '7' => '7' ,
            '8' => '8' ,
            '9' => '9' ,
            '0' => '0'
    );

    private static $acentosArray = array(
            'á' => 'a' , 'Á' => 'A' ,
            'é' => 'e' , 'É' => 'E' ,
            'í' => 'i' , 'Í' => 'i' ,
            'ó' => 'o' , 'Ó' => 'O' ,
            'ú' => 'u' , 'Ú' => 'U' ,
            'â' => 'â' , 'â' => 'â' ,
            'ê' => 'ê' , 'Ê' => 'â' ,
            'ô' => 'ô' , 'Ô' => 'â' ,
            'à' => 'a' , 'À' => 'â' ,
            'ç' => 'c' , 'Ç' => 'C' ,
            'ã' => 'a' , 'Ã' => 'ã' ,
            'õ' => 'o' , 'Õ' => 'o'
    );

    /**
     * Limpa uma string para ser usada como termo de uma URL
     * @param string $string
     * @return string
     */
    public static function clean($string , $caseSensitive=false) {
        $finalString = "";

        if(!$caseSensitive) {
            $string = strtolower($string);
        }

        $string = str_replace("'", "", $string);
        $string = str_replace('"', "", $string);

        $string = trim($string);

        $string = filter_var($string, FILTER_SANITIZE_STRING);

        foreach(str_split($string) as $str) {
            $finalString .= self::$removeArray[$str];
        }

        $finalString = str_replace("__", "_", $finalString);
        $finalString = str_replace("__", "_", $finalString);

        if(substr($finalString, -1, 1)=="_") {
            $finalString = substr($finalString, 0, -1);
        }

        return $finalString;
    }



    /**
     * Remove os acentos de uma string
     *
     * @param string $string
     * @return string
     */
    public static function removeAcento($string) {
        $finalString = "";
        $string = str_replace("'", "", $string);
        $string = str_replace('"', "", $string);
        $string = str_replace('&', "", $string);

        $string = trim($string);

        $string = filter_var($string, FILTER_SANITIZE_STRING);

        foreach(str_split($string) as $str) {
            if(key_exists($str, self::$acentosArray)) {
                $finalString .= self::$acentosArray[$str];
            } else {
                $finalString .= $str;
            }
        }

        if(substr($finalString, -1, 1)=="_") {
            $finalString = substr($finalString, 0, -1);
        }

        return $finalString;
    }


}



?>
