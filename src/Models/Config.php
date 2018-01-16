<?php

namespace Acr\Acr_fl\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Config extends Model

{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $options;
    protected $table = 'Acr_fl_config';

}
