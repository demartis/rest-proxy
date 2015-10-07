<?php
namespace RestProxy;

class HttpUtils
{

    public static function decodeOut($out)
    {
        /*
         * cure response if client send a 100-continue header:
         * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.20
         */
        $out=self::removeContinue($out);

        // It should be a fancy way to do that :(
        $headersFinished = FALSE;
        $headers         = $content = [];
        $data            = explode("\n", $out);
        foreach ($data as $line) {
            if (trim($line) == '') {
                $headersFinished = TRUE;
            } else {
                if ($headersFinished === FALSE && strpos($line, ':') > 0) {
                    $headers[] = $line;
                }

                if ($headersFinished) {
                    $content[] = $line;
                }
            }
        }

        return [$headers, implode("\n", $content)];
    }

    public static function removeContinue($out){

        $data  = explode("\n", $out);
        if(count($data)){
            if(stripos($data[0],"HTTP/1.1 100 Continue")!==false){
                array_shift($data); //remove header
                array_shift($data); //remove \n line
            }

        }
        return implode("\n", $data);
    }

}
