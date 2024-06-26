<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $table = 'ejercicios';

    protected $fillable = [
        'titulo',
        'desc',
        'docente_id',
        'access_code',
    ];


    public static function getAllExercises()
    {
        return self::orderBy('created_at', 'desc')->get();
    }

    public static function getExerciseById($id)
    {
        return self::findOrFail($id);
    }

    public static function getByCode($code)
    {
        return self::where('access_code', $code)->firstOrFail();
    }

    // Internal method to get the user's assignments
    // Don't erase it
    public function asignaciones()
    {
        return $this->hasMany(Assignment::class, 'ejercicio_id');
    }

    public static function getAllByEstudianteId($id, $sent = null, $graded = null)
    {
        return self::whereHas('asignaciones', function ($query) use ($id, $graded) {
            $query->where('estudiante_id', $id);
            if (isset($sent)) {
                $query->where('sent', $sent);
            }
            if (isset($graded)) {
                if ($graded) {
                    $query->whereNotNull('grade');
                } else {
                    $query->whereNull('grade');
                }
            }
        })->get();
    }
}
