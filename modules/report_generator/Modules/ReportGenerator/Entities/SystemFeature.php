<?php
/*
|--------------------------------------------------------------------------
| SystemFeature Model
|--------------------------------------------------------------------------
|
| This is the model for system_features table.
|
| @author Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
| Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
|
*/

namespace Modules\ReportGenerator\Entities;

use Illuminate\Database\Eloquent\Model;

class SystemFeature extends Model
{
    protected $fillable = ['name', 'description'];

    /**
     * Convert draggable_components_id column from jsona(as specified in migration file) to array.
     *
     * @var string
     */
    protected $casts = [];

    /**
     * The database connection name for ReportFormat model.
     * TODO
     * @var string
     */
    protected $connection = 'mysql_report_generator';

    /**
    * The report formats under this system feature.
    *
    * @return Response
    */
    public function report_formats()
    {
        return $this->hasMany('Modules\ReportGenerator\Entities\ReportFormat');
    }

}
