<?php
/*
|--------------------------------------------------------------------------
| DraggableComponent Model
|--------------------------------------------------------------------------
|
| This is the model for DraggableComponent table.
|
| @author Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
| Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
|
*/

namespace Modules\ReportGenerator\Entities;

use Illuminate\Database\Eloquent\Model;

class DraggableComponent extends Model
{
    protected $fillable = ['is_default', 'user', 'title', 'order', 'active', 'notes', 'toggle_sort', 'toggle_display'];

    /**
     * Convert notes column from jsona(as specified in migration file) to array.
     *
     * @var string
     */
    protected $casts = [
        'notes' => 'array'
    ];

    /**
     * The database connection name for DraggableComponent model.
     * TODO
     * @var string
     */
    protected $connection = 'mysql_report_generator';

    /**
    * The report formats that belong to the Draggable component.
    *
    * @return Response
    */
    public function report_formats()
    {
        return $this->belongsToMany('Modules\ReportGenerator\Entities\ReportFormat')->withTimestamps();
    }
}
