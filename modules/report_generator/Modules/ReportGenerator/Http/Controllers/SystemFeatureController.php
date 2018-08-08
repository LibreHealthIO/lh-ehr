<?php
/*
|--------------------------------------------------------------------------
| System feature controller.
|--------------------------------------------------------------------------
|
| This controller handles actions related to system features.
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

class SystemFeatureController extends Controller
{
    /**
     * Show all system features.
     * @return Response
     */
     public function index()
     {
         return view('reportgenerator::system_features')->with('system_features', SystemFeature::all());
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
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [ // Validate the input from new system feature form
            'feature_name' => 'required|max:255',
            'description' => 'required|max:255'
        ]);

        if($validate->fails()){ // Fire error if validation fails
            return back()->withErrors($validate);
        }

        $system_feature = new SystemFeature;

        $system_feature->name = $request->feature_name;
        $system_feature->description = $request->description;

        $system_feature->save(); // Save new system feature

        if(!$system_feature){ // If for some reason system feature isn't created, fire error message
            return back()->with('failure', 'An error occured while saving system feature. Fill all fields!!!');
        }

        return back()->with('success', 'Successfully created new system feature '.$request->feature_name);
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
     * TODO: Restrict users from deleting default system features.
     */
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [ // Validate the input from new system feature form
            'feature_name' => 'required|max:255',
            'description' => 'required|max:255'
        ]);

        if($validate->fails()){ // Fire error if validation fails
            return back()->withErrors($validate);
        }

        $system_feature = SystemFeature::find($request->id);

        $system_feature->name = $request->feature_name;
        $system_feature->description = $request->description;
        $system_feature->save();

        if(!$system_feature){ // If for some reason system feature isn't created, fire error message
            return back()->with('failure', 'An error occured while saving system feature. Fill all fields!!!');
        }

        return back()->with('success', 'Successfully updated system feature '.$request->feature_name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     *
     * TODO: Restrict users from deleting default system features.
     */
    public function destroy(Request $request)
    {
        $system_feature = SystemFeature::find($request->id);
        $system_feature->delete();

        if(!$system_feature){ // If for some reason system feature isn't created, fire error message
            return back()->with('failure', 'An error occured while deleting system feature. Try again!!!');
        }

        return back()->with('success', 'Deleted system feature, '.$system_feature->name);
    }

}
