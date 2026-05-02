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
                                    @if($my_user->usertype == 1)
                                        <th>Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendance as $attend)
                                    <tr>
                                        <td><input type="checkbox" /></td>
                                        <td>
                                            @foreach($users as $user)
                                                @if($user->id == $attend->user_id)
                                                    {{ $user->fname.' '.$user->mname.' '.$user->lname }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $attend->time_in }}</td>
                                        <td>{{ $attend->time_out ?? '—' }}</td>
                                        @if($my_user->usertype == 1)
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary"
                                                    data-toggle="modal"
                                                    data-target="#EditAttendanceModal"
                                                    onclick="attendanceEditFunction(
                                                        {{ $attend->id }},
                                                        '{{ $attend->time_in ? \Carbon\Carbon::parse($attend->time_in)->format('Y-m-d\TH:i') : '' }}',
                                                        '{{ $attend->time_out ? \Carbon\Carbon::parse($attend->time_out)->format('Y-m-d\TH:i') : '' }}'
                                                    )">
                                                    <span class="fas fa-pen"></span>
                                                </button>
                                            </td>
                                        @endif
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

{{-- ====================================================================
     MODAL: EDIT ATTENDANCE (Admin Only)
==================================================================== --}}
@if($my_user->usertype == 1)
<div class="modal fade" id="EditAttendanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    <span class="fas fa-pen"></span> Edit Attendance Record
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editAttendanceForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
 
                    <div class="alert alert-warning py-2">
                        <span class="fas fa-exclamation-triangle"></span>
                        You are editing an attendance record as <strong>Admin</strong>. This action is logged.
                    </div>
 
                    <div class="form-group">
                        <label>Time In <span class="text-danger">*</span></label>
                        <input type="datetime-local"
                               class="form-control"
                               id="edit_att_time_in"
                               name="time_in"
                               required>
                        <small class="form-text text-muted">Leave as-is if you only want to change Time Out.</small>
                    </div>
 
                    <div class="form-group">
                        <label>Time Out</label>
                        <input type="datetime-local"
                               class="form-control"
                               id="edit_att_time_out"
                               name="time_out">
                        <small class="form-text text-muted">Leave blank if the user has not timed out yet.</small>
                    </div>
 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fas fa-save"></span> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    // ── Admin: Populate Edit Attendance Modal ────────────────────────────
    function attendanceEditFunction(id, time_in, time_out) {
        // Point the form to the correct record
        $('#editAttendanceForm').attr('action', '/attendance/admin-update/' + id);
 
        // Populate datetime-local inputs
        // The format passed from Blade is already Y-m-d\TH:i (e.g. "2024-05-01T08:30")
        $('#edit_att_time_in').val(time_in);
        $('#edit_att_time_out').val(time_out);
    }
</script>

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