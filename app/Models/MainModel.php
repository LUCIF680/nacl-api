<?php 

trait MainModel{
    static function matchToken($data){
        if($data != '4PbEDUNhxJhxiMOnc2R2Ob6JorZcrY7d')
         throw new Exception(Strings::TOKEN_ERROR);
    }
}