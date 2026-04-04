<!-- Custom Style Component -->
<style> 
    .modal { 
        overflow-y:auto !important; 
    }
    
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    
    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
</style>
  
<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h2 class="card-title" style="padding-top: 10px;">TIME IN / TIME OUT BUTTONS</h2>
                    </div>
                    <div class="card-body">
                        @if($existing == 0)
                            <button class="btn btn-lg btn-success btn-block" onclick="time_in()">
                                <span class="far fa-calendar-check mr-1"></span> TIME IN
                            </button> <br>
                        @else
                            <button class="btn btn-lg btn-danger btn-block" data-toggle="modal" data-target="#DeleteModal">
                                <span class="far fa-times-circle mr-1"></span> TIME OUT
                            </button> <br>
                        @endif
                        
                        @if($my_user->usertype == 1)
                            <button class="btn btn-lg btn-primary btn-block" onclick="openOverall()">
                                <span class="far fa-calendar-alt mr-1"></span> OVERALL ATTENDANCE
                            </button>
                        @endif
                        <!--
                        <button class="btn btn-danger float-right" onclick="time_out()" data-toggle="modal" data-target="#DeleteModal">
                            <span class="fas fa-times-circle"></span> TIME OUT
                        </button>
                        <button class="btn btn-success float-right mr-3" onclick="time_in()">
                            <span class="far fa-calendar-check"></span> TIME IN
                        </button>
                        -->
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-info" id="table">
                    <div class="card-header">
                        <h2 class="card-title" style="padding-top: 10px;">My Attendance</h2>
                    </div>
                    <div class="card-body">
                        <table id="normalTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Time-In</th>
                                    <th>Time-Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendance as $attend)
                                    @if($attend->user_id == $my_user->id)
                                        <tr>
                                            <td><input type="checkbox" /></td>
                                            <td>{{ $attend->time_in }}</td>
                                            <td>{{ $attend->time_out }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card card-info" id="all" style="display: none;">
                    <div class="card-header">
                        <h2 class="card-title" style="padding-top: 10px;">Overall Attendance</h2>
                        <button class="btn btn-secondary float-right" onclick="closeOverall()">
                            <span class="fas fa-times"></span> Close
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="anotherNormalTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Time-In</th>
                                    <th>Time-Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendance as $attend)
                                    <tr>
                                        <td><input type="checkbox" /></td>
                                        <td>
                                            @foreach($users as $user)
                                                @if($user->id == $attend->user_id)
                                                    {{ $user->fname.' '.$user->mname.' '.$user->lname; }}
                                                @endif
                                            @endforeach  
                                        </td>
                                        <td>{{ $attend->time_in; }}</td>
                                        <td>{{ $attend->time_out; }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Time Out -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title">TIME OUT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="time_out" action="/attendance/update/{{ $my_user->id; }}" method="POST">
                @method('PUT')
                @csrf
                <div class="modal-body">
                    <h6>Are you sure you want to TIME OUT?</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-danger" value="Yes" />
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Time In -->
<form id="time_in" action="/attendance/store" method="POST" style="display: none;">
    @csrf
</form>

<script>

    function openOverall(){
        $('#table').attr("style", "display: none;");
        $('#all').removeAttr("style");
    }

    function closeOverall(){
        $('#all').attr("style", "display: none;");
        $('#table').removeAttr("style");
    }

    function time_in(){
        $("#time_in").submit();
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year, month, day].join('-');
    }
 
</script>