<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Student
 * @package App\Models
 * @version March 25, 2022, 1:36 am UTC
 *
 * @property string $name
 * @property string $photo
 */
class Student extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'students';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'photo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'photo' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'photo' => 'required'
    ];

    
}
