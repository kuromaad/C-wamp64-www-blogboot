<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class block extends Model
{
    public function Bureaus(){
        return $this->hasMany('\App\bureau');
    }
    public function Assets(){
        return $this->hasMany('\App\asset');
    }
    
}
