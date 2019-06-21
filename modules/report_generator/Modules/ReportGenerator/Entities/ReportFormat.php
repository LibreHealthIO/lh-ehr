<?php
/*
|--------------------------------------------------------------------------
| ReportFormat Model
|--------------------------------------------------------------------------
|
| This is the model for ReportFormat table.
|
| @author Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
| Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
|
*/

namespace Modules\ReportGenerator\Entities;

use Illuminate\Database\Eloquent\Model;

class ReportFormat extends Model
{
    protected $fillable = ['user', 'title', 'system_feature_id', 'description', 'draggable_components_id'];

    /**
     * Convert draggable_components_id column from jsona(as specified in migration file) to array.
     *
     * @var string
     */
    protected $casts = [
        'draggable_components_id' => 'array'
    ];

    /**
     * The database connection name for ReportFormat model.
     * TODO
     * @var string
     */
    protected $connection = 'mysql_report_generator';

    /**
    * The draggable components that belong to the Report Format.
    *
    * @return Response
    */
    public function draggable_components()
    {
        return $this->belongsToMany('Modules\ReportGenerator\Entities\DraggableComponent')->withTimestamps();
    }

    /**
    * The system feature that this report format belongs to.
    *
    * @return Response
    */
    public function system_feature()
    {
        return $this->belongsTo('Modules\ReportGenerator\Entities\SystemFeature');
    }

}
