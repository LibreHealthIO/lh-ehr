<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="_token" content="{{ csrf_token() }}" />
        <meta name="c-token" content="{!! csrf_token() !!}" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Tigpezeghe Rodrige K. @ tigrodrige@gmail.com">
        <meta name="description" content="LibreHealthEHR Report generator">
        <title>@yield('title') - LibreHealthEHR</title>

        <!-- Custom app.css in public/assets/css -->
        <link rel="stylesheet" href="{{URL::asset('assets/css/master.css')}}">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="{{URL::asset('assets/css/resource/jquery-ui.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('assets/css/resource/bootstrap.min.css')}}">

        <!-- jQuery and jQueryUI libraries -->
        <script src="{{URL::asset('assets/js/resource/jquery-3.3.1.min.js')}}"></script>
        <script src="{{URL::asset('assets/js/resource/jquery-ui.js')}}"></script>

        <!-- Popper JS -->
        <script src="{{URL::asset('assets/js/resource/popper.min.js')}}"></script>

        <!-- Latest compiled JavaScript -->
        <script src="{{URL::asset('assets/js/resource/bootstrap.min.js')}}"></script>

    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-warning" style="background-color:#fd7e14 !important">
            <!-- The button below opens the add system feature modal below. -->
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add-system-feature">Add system feature</button>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <a class="nav-link" href="{{ url('/reportgenerator') }}" role="button" aria-haspopup="true" aria-expanded="false"><strong>Home</strong></a>
                    @foreach($system_features as $system_feature)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $system_feature->name }}</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @foreach($system_feature->report_formats as $report_format)
                                    <a class="dropdown-item" href="{{ url('/reportgenerator/report_format/view/'.$report_format->id) }}">{{ $report_format->title }}</a>
                                @endforeach
                            </div>
                        </li>
                    @endforeach
                    <a class="nav-link" href="{{ url('reportgenerator/system_feature') }}" role="button" aria-haspopup="true" aria-expanded="false"><strong>System features</strong></a>

                    <a class="nav-link" href="{{ url('reportgenerator/report_format') }}" role="button" aria-haspopup="true" aria-expanded="false"><strong>Report formats</strong></a>
                </ul>
                <!-- <form class="form-inline my-2 my-lg-0">
                  <input class="form-control mr-sm-2" type="search" placeholder="Search system features" aria-label="Search">
                  <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
              </form> -->
            </div>
        </nav><!-- /.navbar -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible" id="alert" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" id="alert" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible" id="alert" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

        <!-- Add system feature modal-->
        <div class="modal fade" id="add-system-feature" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><b>New System Feature</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="add-system-feature-form" action="{{ url('reportgenerator/system_feature') }}" id="add-system-feature-form" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                            <div class="form-group">
                                <label for="feature_name">Feature name</label>
                                <input type="text" name="feature_name" class="form-control" id="feature_name" placeholder="Enter feature name">
                            </div>
                            <div class="form-group">
                                <label for="feature-name">Short description</label>
                                <textarea class="form-control" name="description" id="description" aria-label="Describe feature"></textarea>
                            </div>
                            <hr />
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-info">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /#add-system-feature -->

        <!-- Edit system feature modal-->
        <div class="modal fade" id="edit-system-feature" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><b>Edit System Feature</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="edit-system-feature-form" action="" id="edit-system-feature-form" method="post">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                            <div class="form-group"><input type="hidden" name="id" id="id"></div>
                            <div class="form-group">
                                <label for="feature_name">Feature name</label>
                                <input type="text" name="feature_name" class="form-control" id="feature_name" placeholder="Enter feature name">
                            </div>
                            <div class="form-group">
                                <label for="description">Short description</label>
                                <textarea class="form-control" name="description" id="description" aria-label="Describe feature"></textarea>
                            </div>
                            <hr />
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-info">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /#edit-system-feature -->

        <!-- Delete system feature modal-->
        <div class="modal fade" id="delete-system-feature" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><b>Delete System Feature</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="delete-system-feature-form" action="" id="delete-system-feature-form" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                            <div class="form-group"><input type="hidden" name="id" id="id"></div>
                            <div class="form-group">
                                <p>Are you sure you want to delete <strong><span id="feature_name"></span> ?</strong></p>
                            </div>
                            <hr />
                            <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /#delete-system-feature -->

        <!-- Edit report format modal-->
        <div class="modal fade" id="edit-report-format" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><b>Edit Report Format</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="edit-report-format-form" action="" id="edit-report-format-form" method="post">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                            <div class="form-group"><input type="hidden" name="id" id="id"></div>
                            <div class="form-group">
                                <label for="system_feature_name">Select system feature</label>
                                <select class="form-control" name="system_feature_id" id="system_feature_id">
                                    @foreach($system_features as $system_feature)
                                        <option value="{{ $system_feature->id }}">{{ $system_feature->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control" id="title" placeholder="Enter title">
                            </div>
                            <div class="form-group">
                                <label for="description">Short description</label>
                                <textarea class="form-control" name="description" id="description" aria-label="Describe feature"></textarea>
                            </div>
                            <hr />
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-info">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /#edit-report-format -->

        <!-- Delete report format modal-->
        <div class="modal fade" id="delete-report-format" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><b>Delete Report Format</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="delete-report-format-form" action="" id="delete-report-format-form" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                            <div class="form-group"><input type="hidden" name="id" id="id"></div>
                            <div class="form-group">
                                <p>Are you sure you want to delete <strong><span id="title"></span> ?</strong></p>
                            </div>
                            <hr />
                            <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /#delete-report-format -->
        <script src="{{ URL::asset('assets/js/master.js') }}"></script>
    </body>
</html>
