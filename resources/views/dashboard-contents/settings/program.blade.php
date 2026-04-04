<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card card-info" id="table">
                    <div class="card-header">
                        <h2 class="card-title" style="padding-top: 10px;">Programs List</h2>
                        <button class="btn btn-success float-right" style="color: antiquewhite;" onclick="showForm()">
                            <span class="fas fa-plus"></span> Create Program
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="normalTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Beneficiaries Count</th>
                                    <th>Member Minimum Age</th>
                                    <th>Member Maximum Age</th>
                                    <th>Beneficiary Minimum Age</th>
                                    <th>Beneficiary Maximum Age</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($programs as $program)   
                                    <tr>
                                        <td><input type="checkbox" /></td>
                                        <td id="code_{{ $program->id; }}">{{ $program->code; }}</td>
                                        <td id="description_{{ $program->id; }}">{{ $program->description; }}</td>
                                        <td id="beneficiaries_count_{{ $program->id; }}">{{ $program->beneficiaries_count; }}</td>
                                        <td id="age_min_{{ $program->id; }}">{{ $program->age_min; }}</td>
                                        <td id="age_max_{{ $program->id; }}">{{ $program->age_max; }}</td>
                                        <td id="ben_age_min_{{ $program->id; }}">{{ $program->ben_age_min; }}</td>
                                        <td id="ben_age_max_{{ $program->id; }}">{{ $program->ben_age_max; }}</td>
                                        <td id="created_at_{{ $program->id; }}">{{ $program->created_at; }}</td>
                                        <td id="updated_at_{{ $program->id; }}">{{ $program->updated_at; }}</td>
                                        <td>
                                            <button class="btn btn-outline-primary" onclick="editFunction({{ $program->id; }})">
                                                <span class="fas fa-pen"></span>
                                            </button>
                                            <button class="btn btn-outline-danger" data-toggle="modal" data-target="#DeleteModal"
                                            onclick="deleteFunction({{ $program->id; }})">
                                                <span class="fas fa-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card card-primary" id="form" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top: 10px;">Create Program Form</h3>
                        <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>
                    <form id="form_tag" action="/program/store" method="post">
                        @csrf
                        <div class="card-body">
                            <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">Program Details</legend>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="code">Code:</label>
                                        <input type="text" class="form-control" id="code" name="code" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="description">Description:</label>
                                        <input type="text" class="form-control" id="description" name="description" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="beneficiaries_count">Beneficiaries Count</label>
                                        <input type="number" class="form-control" id="beneficiaries_count" name="beneficiaries_count" value="2" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="age_min">Minimum Age:</label>
                                        <input type="number" class="form-control" id="age_min" name="age_min" value="18">
                                    </div>
                                    <div class="form-group col">
                                        <label for="age_max">Maximum Age:</label>
                                        <input type="number" class="form-control" id="age_max" name="age_max" value="60">
                                    </div>
                                    <div class="form-group col">
                                        <label for="ben_age_min">Beneficiary Minimum Age:</label>
                                        <input type="number" class="form-control" id="ben_age_min" name="ben_age_min" value="18">
                                    </div>
                                    <div class="form-group col">
                                        <label for="ben_age_max">Beneficiary Maximum Age:</label>
                                        <input type="number" class="form-control" id="ben_age_max" name="ben_age_max" value="60">
                                    </div>
                                </div> <br>
                            </fieldset>

                        </div>
                        <div class="card-footer">
                            <button id="store_btn" type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="hideForm()">Cancel</button>
                        </div>
                    </form>
                </div>

                <div class="card card-primary" id="edit_form" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top: 10px;">Update Program Form</h3>
                        <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>
                    <form id="editForm" action="/program/update" method="post">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">Program Details</legend>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="code">Code:</label>
                                        <input type="text" class="form-control" id="edit_code" name="code" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="description">Description:</label>
                                        <input type="text" class="form-control" id="edit_description" name="description" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="beneficiaries_count">Beneficiaries Count:</label>
                                        <input type="number" class="form-control" id="edit_beneficiaries_count" name="beneficiaries_count" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="age_min">Minimum Age:</label>
                                        <input type="number" class="form-control" id="edit_age_min" name="age_min" value="">
                                    </div>
                                    <div class="form-group col">
                                        <label for="age_max">Maximum Age:</label>
                                        <input type="number" class="form-control" id="edit_age_max" name="age_max" value="">
                                    </div>
                                    <div class="form-group col">
                                        <label for="ben_age_min">Beneficiary Minimum Age:</label>
                                        <input type="number" class="form-control" id="edit_ben_age_min" name="ben_age_min" value="">
                                    </div>
                                    <div class="form-group col">
                                        <label for="ben_age_max">Beneficiary Maximum Age:</label>
                                        <input type="number" class="form-control" id="edit_ben_age_max" name="ben_age_max" value="">
                                    </div>
                                </div> <br>
                            </fieldset>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="hideForm()">Cancel</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Delete Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title">Delete Program</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteForm" action="/program/destroy" method="POST">
                @csrf
                <div class="modal-body">
                    <h6>Do you want to remove <span id="del_display"></span> Program?</h6>
                    <input type="hidden" id="delete_id" name="id" value="" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-danger" value="Delete" />
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    function hideForm(){
        $("#form").attr("style", "display: none;");
        $("#edit_form").attr("style", "display: none;");
        $("#table").removeAttr("style");
    }

    function showForm(){
        $("#table").attr("style", "display: none;");
        $("#form").removeAttr("style");
    }

    function editFunction(id){
        $("#table").attr("style", "display: none;");
        $("#edit_form").removeAttr("style");

        $("#edit_code").val($("#code_"+id).html());
        $("#edit_description").val($("#description_"+id).html());
        $("#edit_beneficiaries_count").val($("#beneficiaries_count_"+id).html());
        $("#edit_age_min").val($("#age_min_"+id).html());
        $("#edit_age_max").val($("#age_max_"+id).html());
        $("#edit_ben_age_min").val($("#ben_age_min_"+id).html());
        $("#edit_ben_age_max").val($("#ben_age_max_"+id).html());
        $("#editForm").attr("action", "/program/update/"+id);
    }

    function deleteFunction(id){
        var display = $("#code_"+id).html();
        $("#del_display").html(display);
        $("#delete_id").val(id);
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