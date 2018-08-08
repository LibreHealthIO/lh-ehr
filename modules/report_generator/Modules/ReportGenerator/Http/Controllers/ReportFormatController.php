<?php
/*
|--------------------------------------------------------------------------
| Report format controller.
|--------------------------------------------------------------------------
|
| This controller handles actions related to report formats.
|
| @author Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
| Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
|
*/

namespace Modules\ReportGenerator\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Modules\ReportGenerator\Entities\ReportFormat as ReportFormat;
use Modules\ReportGenerator\Entities\SystemFeature as SystemFeature;
use Modules\ReportGenerator\Entities\DraggableComponent as DraggableComponent;

class ReportFormatController extends Controller
{
    /**
     * Display a listing of the resource, report formats.
     * @return Response
     */
    public function index()
    {
        return view('reportgenerator::report_formats')->with([
            'report_formats' => ReportFormat::all(),
            'system_features' => SystemFeature::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('reportgenerator::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [ // Validate the input from new report format form
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'system_feature_id' => 'required'
        ]);

        if($validate->fails()){ // Fire error if validation fails
            return back()->withErrors($validate);
        }

        // Save the report format
        $report_format = ReportFormat::create([
            'title' => $request->title,
            'description' => $request->description,
            'system_feature_id' => $request->system_feature_id
        ]);

        // Save the draggable components in the join table (Report format and draggable_components table)
        $new_report_format = ReportFormat::findOrFail($report_format->id); // Get the last inserted report format id
        $draggable_component_ids = []; // Store the ids of the draggable components
        $option_ids = unserialize($request->option_ids);

        foreach ($option_ids as $key => $option_id) { // foreach option id,
            $draggable_component_ids[] = DraggableComponent::where('option_id', $option_id)->first()->id; // get the id of the corresponding draggable component
        }

        // Populate the draggable_component_report_format join table
        $new_report_format->draggable_components()->attach($draggable_component_ids);

        if(!$report_format){ // If for some reason report format isn't created, fire error message
            return back()->with('failure', 'An error occured while saving report format. Fill all fields!!!');
        }

        return back()->with('success', 'Successfully created new report format '.$request->title);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('reportgenerator::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('reportgenerator::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     *
     * TODO: Restrict users from deleting default report formats.
     */
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [ // Validate the input from new report format form
            'title' => 'required|max:255',
            'description' => 'required|max:255'
        ]);

        if($validate->fails()){ // Fire error if validation fails
            return back()->withErrors($validate);
        }

        $report_format = ReportFormat::find($request->id);

        $report_format->title = $request->title;
        $report_format->description = $request->description;
        $report_format->system_feature_id = $request->system_feature_id;
        $report_format->save();

        if(!$report_format){ // If for some reason system feature isn't created, fire error message
            return back()->with('failure', 'An error occured while saving report format. Fill all fields!!!');
        }

        return back()->with('success', 'Successfully updated report format '.$request->title);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     *
     * TODO: Restrict users from deleting default report formats
     */
    public function destroy(Request $request)
    {
        $report_format = ReportFormat::find($request->id);
        $report_format->delete();

        if(!$report_format){ // If for some reason system feature isn't created, fire error message
            return back()->with('failure', 'An error occured while deleting report format. Try again!!!');
        }

        return back()->with('success', 'Deleted report format, '.$report_format->title);
    }
}
