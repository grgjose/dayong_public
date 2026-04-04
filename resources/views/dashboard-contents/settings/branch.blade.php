<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <!-- TABLE SECTION -->  
                <div class="card card-info" id="table">
                    <div class="card-header">
                        <h2 class="card-title" style="padding-top: 10px;">Branches List</h2>
                        <button class="btn btn-success float-right" onclick="showForm()">
                            <span class="fas fa-plus"></span> Create Branch
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="normalTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>City</th>
                                    <th>Branch</th>
                                    <th>Address</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($branches as $branch)   
                                    <tr>
                                        <td><input type="checkbox" /></td>
                                        <td id="code_{{ $branch->id }}">{{ $branch->code; }}</td>
                                        <td id="city_{{ $branch->id; }}">{{ $branch->city; }}</td>
                                        <td id="branch_{{ $branch->id; }}">{{ $branch->branch; }}</td>
                                        <td id="address_{{ $branch->id; }}">{{ $branch->address; }}</td>
                                        <td id="description_{{ $branch->id; }}">{{ $branch->description; }}</td>
                                        <td id="created_at_{{ $branch->id; }}">{{ $branch->created_at; }}</td>
                                        <td id="updated_at_{{ $branch->id; }}">{{ $branch->updated_at; }}</td>
                                        <td>
                                            <button class="btn btn-outline-primary"
                                            onclick="editFunction({{ $branch->id; }})">
                                                <span class="fas fa-pen"></span>
                                            </button>
                                            <button class="btn btn-outline-danger" data-toggle="modal" data-target="#DeleteModal"
                                            onclick="deleteFunction({{ $branch->id; }})">
                                                <span class="fas fa-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ADD SECTION -->  
                <div class="card card-primary" id="form" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top: 10px;">Create Branch Form</h3>
                        <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>
                    <form id="form_tag" action="/branch/store" method="post">
                        @csrf
                        <div class="card-body">
                            <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">Branch Details</legend>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="code">Code:</label>
                                        <input type="number" class="form-control" id="code" name="code" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="city">City:</label>
                                        <input type="text" class="form-control" id="city" name="city" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="city">Branch:</label>
                                        <input type="text" class="form-control" id="branch" name="branch" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="address">Address:</label>
                                        <input type="text" class="form-control" id="address" name="address" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="description">Description:</label>
                                        <input type="text" class="form-control" id="description" name="description" value="" required>
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

                <!-- VIEW SECTION -->  
                <div class="card card-primary" id="view_form" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top: 10px;">Create Branch Form</h3>
                        <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>
                    <form id="form_tag" action="/branch/store" method="post">
                        @csrf
                        <div class="card-body">
                            <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">Branch Details</legend>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="code">Code:</label>
                                        <input type="number" class="form-control" id="view_code" name="code" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="city">City:</label>
                                        <input type="text" class="form-control" id="view_city" name="city" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="city">Branch:</label>
                                        <input type="text" class="form-control" id="view_branch" name="branch" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="address">Address:</label>
                                        <input type="text" class="form-control" id="view_address" name="address" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="description">Description:</label>
                                        <input type="text" class="form-control" id="view_description" name="description" value="">
                                    </div>
                                </div> <br>
                            </fieldset>

                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="hideForm()">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- EDIT SECTION -->  
                <div class="card card-primary" id="edit_form" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top: 10px;">Update Branch Form</h3>
                        <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>
                    <form id="editForm" action="/branch/update" method="post">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">Branch Details</legend>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="code">Code:</label>
                                        <input type="number" class="form-control" id="edit_code" name="code" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="city">City:</label>
                                        <input type="text" class="form-control" id="edit_city" name="city" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="city">Branch:</label>
                                        <input type="text" class="form-control" id="edit_branch" name="branch" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="address">Address:</label>
                                        <input type="text" class="form-control" id="edit_address" name="address" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="description">Description:</label>
                                        <input type="text" class="form-control" id="edit_description" name="description" value="" required>
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

            </div>
        </div>
    </div>
</section>

<!-- Delete Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title">Delete Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteForm" action="/branch/destroy" method="POST">
                @csrf
                <div class="modal-body">
                    <h6>Do you want to remove <span id="del_display"></span> Branch?</h6>
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

    function viewFunction(id){
        $("#table").attr("style", "display: none;");
        $("#view_form").removeAttr("style");

        $("#view_code").val($("#code_"+id).html());
        $("#view_city").val($("#city_"+id).html());
        $("#view_branch").val($("#branch_"+id).html());
        $("#view_address").val($("#address_"+id).html());
        $("#view_description").val($("#description_"+id).html());
    }

    function editFunction(id){
        $("#table").attr("style", "display: none;");
        $("#edit_form").removeAttr("style");

        $("#edit_code").val($("#code_"+id).html());
        $("#edit_city").val($("#city_"+id).html());
        $("#edit_branch").val($("#branch_"+id).html());
        $("#edit_address").val($("#address_"+id).html());
        $("#edit_description").val($("#description_"+id).html());

        $("#editForm").attr("action", "/branch/update/"+id);
    }

    function deleteFunction(id){
        var display = $("#branch_"+id).html();
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