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
  
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <!-- MAIN TABLE -->
                <div class="card card-info" id="table">
                    <div class="card-header">
                        <h2 class="card-title" style="padding-top: 10px;">Incentives Matrix</h2>
                        <button class="btn btn-success float-right" style="color: antiquewhite;" onclick="showForm()">
                            <span class="fas fa-plus"></span> Add Matrix Item
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="normalTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Program</th>
                                    <th>NOP</th>
                                    <th>Percentage (%)</th>
                                    <th>For Reactivated</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($matrix as $m)
                                    <tr>
                                        <td><input type="checkbox" /></td>
                                        <td>
                                            @foreach($programs as $program)
                                                @if($program->id == $m->program_id)
                                                    {{ $program->code; }}
                                                    <span id="program_id_{{ $m->id; }}" style="display: none;">{{ $program->id; }}</span>
                                                @endif
                                            @endforeach
                                            
                                        </td>
                                        <td id="nop_{{ $m->id; }}">{{ $m->nop; }}</td>
                                        <td id="percentage_{{ $m->id; }}">{{ $m->percentage; }}</td>
                                        <td id="is_reactivated_{{ $m->id; }}">{{ $m->is_reactivated == false?"NO":"YES"; }}</td>
                                        <td id="created_at_{{ $m->id; }}">{{ $m->created_at; }}</td>
                                        <td id="updated_at_{{ $m->id; }}">{{ $m->updated_at; }}</td>
                                        <td>
                                            <button class="btn btn-outline-primary" onclick="editFunction({{ $m->id; }})">
                                                <span class="fas fa-pen"></span>
                                            </button>
                                            <button class="btn btn-outline-danger" data-toggle="modal" data-target="#DeleteModal"
                                            onclick="deleteFunction({{ $m->id; }})">
                                                <span class="fas fa-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ADD TABLE -->
                <div class="card card-primary" id="form" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top: 10px;">Create Matrix Item Form</h3>
                        <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>
                    <form id="form_tag" action="/matrix/store" method="post">
                        @csrf
                        <div class="card-body">
                            <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">Matrix Details</legend>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="program_id">Program:</label>
                                        <select type="text" class="form-control chosen-select" id="program_id" name="program_id" value="" required>
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id; }}">{{ $program->code; }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="nop">NOP:</label>
                                        <input type="text" class="form-control" onblur="checkNOPFormat(this.value)" id="nop" name="nop" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="percentage">Percentage (%):</label>
                                        <input type="number" class="form-control" id="percentage" name="percentage" onkeyup="enforceMinMax(this)" min="1" max="50">
                                    </div>
                                    <div class="form-group col">
                                        <div class="custom-control custom-switch custom-switch-on-warning" style="padding-left: 3.25rem; padding-top: 2.25rem;">
                                            <input type="checkbox" class="custom-control-input" id="reactivated" name="reactivated" value="reactivated">
                                            <label for="reactivated" class="custom-control-label">Is Reactivated</label>
                                        </div>
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

                <!-- UPDATE TABLE -->
                <div class="card card-primary" id="edit_form" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top: 10px;">Update Matrix Item Form</h3>
                        <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>
                    <form id="editForm" action="/matrix/update" method="post">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">Matrix Details</legend>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="edit_program_id">Program:</label>
                                        <select type="text" class="form-control chosen-select" id="edit_program_id" name="program_id" disabled required>
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id; }}">{{ $program->code; }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="edit_nop">NOP:</label>
                                        <input type="text" class="form-control" onblur="checkNOPFormat(this.value)" id="edit_nop" name="nop" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="edit_percentage">Percentage (%):</label>
                                        <input type="number" class="form-control" id="edit_percentage" name="percentage" onkeyup="enforceMinMax(this)" min="1" max="50">
                                    </div>
                                    <div class="form-group col">
                                        <div class="custom-control custom-switch custom-switch-on-warning" style="padding-left: 3.25rem; padding-top: 2.25rem;">
                                            <input type="checkbox" class="custom-control-input" id="edit_reactivated" name="reactivated" value="reactivated">
                                            <label for="edit_reactivated" class="custom-control-label">Is Reactivated</label>
                                        </div>
                                    </div>
                                </div> <br>
                            </fieldset>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="hideForm()">Cancel</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- DELETE CONFIRMATION -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title">Delete Matrix Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteForm" action="/matrix/destroy" method="POST">
                @csrf
                <div class="modal-body">
                    <h6>Do you want to remove <span id="del_display"></span> Matrix?</h6>
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

        $("#edit_program_id").val($("#program_id_"+id).html().toLowerCase().trim()).trigger("chosen:updated");;
        $("#edit_nop").val($("#nop_"+id).html());
        $("#edit_percentage").val($("#percentage_"+id).html());
        var x = $("#is_reactivated_"+id).html();
        $("#edit_reactivated").attr("checked", $("#is_reactivated_"+id).html() == "YES"?true:false);

        $("#editForm").attr("action", "/matrix/update/"+id);
    }

    function deleteFunction(id){
        var display = $("#program_id_"+id).html() + ' ' + $("#nop_"+id).html();
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

    function checkNOPFormat(val){
        const regex = /^\d+-(\d+|up)$/;
        
        if(!regex.test(val) && val != ""){
            $("#nop").val("");
            $("#edit_nop").val("");
            showErrorToast('Invalid NOP Value');
        }

    }

    function enforceMinMax(el) {
        if (el.value != "") {
            if (parseInt(el.value) < parseInt(el.min)) {
            el.value = el.min;
            }
            if (parseInt(el.value) > parseInt(el.max)) {
            el.value = el.max;
            }
        }
    }
 
</script>