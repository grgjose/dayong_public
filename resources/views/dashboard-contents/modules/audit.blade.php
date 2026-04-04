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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title" style="padding-top: 10px;">Audit</h2>
                        <button class="btn btn-outline-info float-right" data-toggle="modal" data-target="#AddModal">
                            <span class="fas fa-plus"></span> Generated Remittance Slip
                        </button>
                        <button class="btn btn-outline-success float-right" data-toggle="modal" data-target="#AddModal">
                            <span class="fas fa-plus"></span> 
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="example3" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Branch Code</th>
                                    <th>City</th>
                                    <th>Branch</th>
                                    <th>Program Code</th>
                                    <th>Program Description</th>
                                    <th>Generated Remittance Slip</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($entries as $entry)
                                    <tr>
                                        <td><input type="checkbox" /></td>
                                        @foreach ($branches as $branch)
                                            @if($entry->branch_id == $branch->id)
                                                @php 
                                                    $branch_code = $branch->code;
                                                    $branch_city = $branch->city;
                                                    $branch_branch = $branch->branch;
                                                @endphp
                                            @endif
                                        @endforeach
                                        @foreach ($programs as $program)
                                            @if($entry->program_id == $program->id)
                                                @php 
                                                    $program_code = $program->code;
                                                    $program_desc = $program->description;
                                                @endphp
                                            @endif
                                        @endforeach
                                        <td id="{{ $entry->id; }}_branch_code">{{ $branch_code; }}</td>
                                        <td id="{{ $entry->id; }}_branch_city">{{ $branch_city; }}</td>
                                        <td id="{{ $entry->id; }}_branch_branch">{{ $branch_branch; }}</td>
                                        <td id="{{ $entry->id; }}_program_code">{{ $program_code; }}</td>
                                        <td id="{{ $entry->id; }}_program_desc">{{ $program_desc; }}</td>
                                        <td id="{{ $entry->id; }}_or_code">None</td>
                                        <td id="{{ $entry->id; }}_created_at">{{ $branch->created_at; }}</td>
                                        <td id="{{ $entry->id; }}_updated_at">{{ $branch->updated_at; }}</td>
                                        <td>
                                            <button class="btn btn-outline-primary" data-toggle="modal" data-target="#EditModal"
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
            </div>
        </div>
    </div>
</section>

<!-- Add Modal -->
<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="AddModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create OR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/branch/store" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label for="code">Code:</label>
                            <input type="number" class="form-control" id="code" name="code" value="" required>
                        </div>
                        <div class="col">
                            <label for="city">City:</label>
                            <input type="text" class="form-control" id="city" name="city" value="" required>
                        </div>
                        <div class="col">
                            <label for="city">Branch:</label>
                            <input type="text" class="form-control" id="branch" name="branch" value="" required>
                        </div>
                    </div> <br>
                    <div class="form-row">
                        <div class="col">
                            <label for="address">Address:</label>
                            <input type="text" class="form-control" id="address" name="address" value="" required>
                        </div>
                    </div> <br>
                    <div class="form-row">
                        <div class="col">
                            <label for="description">Description:</label>
                            <input type="text" class="form-control" id="description" name="description" value="" required>
                        </div>
                    </div> <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Save" />
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="EditModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" action="/branch/update" method="POST" >
                @method('PUT')
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label for="code">Code:</label>
                            <input type="number" class="form-control" id="edit_code" name="code" value="" required>
                        </div>
                        <div class="col">
                            <label for="city">City:</label>
                            <input type="text" class="form-control" id="edit_city" name="city" value="" required>
                        </div>
                        <div class="col">
                            <label for="city">Branch:</label>
                            <input type="text" class="form-control" id="edit_branch" name="branch" value="" required>
                        </div>
                    </div> <br>
                    <div class="form-row">
                        <div class="col">
                            <label for="address">Address:</label>
                            <input type="text" class="form-control" id="edit_address" name="address" value="" required>
                        </div>
                    </div> <br>
                    <div class="form-row">
                        <div class="col">
                            <label for="description">Description:</label>
                            <input type="text" class="form-control" id="edit_description" name="description" value="" required>
                        </div>
                    </div> <br>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteForm" action="/branch/destroy" method="POST" >
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <h5>Are you sure you want to Delete <span id="branch_name"></span> branch?</h5>
                    </div> <br>
                    <input id="delete_id" type="hidden" name="id" value="" />
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    function editFunction(id){
        $('#edit_code').val($('#'+id+'_code').html());
        $('#edit_city').val($('#'+id+'_city').html());
        $('#edit_branch').val($('#'+id+'_branch').html());
        $('#edit_address').val($('#'+id+'_address').html());
        $('#edit_description').val($('#'+id+'_description').html());
        $("#editForm").attr("action", "/branch/update/"+id);
    }

    function deleteFunction(id){
        $('#delete_id').val(id);
        $('#branch_name').html($('#'+id+'_branch').html());
    }

</script>