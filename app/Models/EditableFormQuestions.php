<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditableFormQuestions extends Model
{
  use HasFactory;

  protected $table = 'form_questions';
  protected $primaryKey = 'question_id';

  protected $fillable = [
    'form_type',
    'table_initials',
    'question',
    'question_order'
  ];
}