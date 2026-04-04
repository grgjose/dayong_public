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
                <div class="card card-info" id="table">
                    <div class="card-header">
                        <h2 class="card-title" style="padding-top: 10px;">Fidelity Monitoring</h2>
                        @if($my_user->usertype != 3)
                            <button class="btn btn-success float-right" onclick="showForm()">
                                <span class="fas fa-plus"></span> Register Agent
                            </button>
                            <button class="btn btn-secondary float-right mr-2" onclick="showOther()">
                                <span class="fas fa-eye"></span> Show Agents with Fidelity
                            </button>
                        @endif
                    </div>
                    <div class="card-body" id="fidelity_table">
                        <p style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                            Note: Fidelity is a 10% Deduction that will act as a Savings for the Agent
                        </p>
                        <table id="normalTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Timestamp</th>
                                    <th>Branch</th>
                                    <th>Marketting Account Staff</th>
                                    <th>Date Remitted</th>
                                    <th>Amount</th>
                                    <!-- th>Action</th -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fidelity as $fidel)
                                    <tr>
                                        <td><input type="checkbox" /></td>
                                        <td id="{{ $fidel->id; }}_timestamp">{{ $fidel->created_at; }}</td>
                                        <td id="{{ $fidel->id; }}_branch">
                                            @foreach($branches as $branch)
                                                @if($branch->id == $fidel->branch_id)
                                                    {{ $branch->branch; }}
                                                    @break
                                                @endif
                                            @endforeach
                                        </td>
                                        <td id="{{ $fidel->id; }}_staff">
                                            @foreach($users as $user)
                                                @if($user->id == $fidel->user_id)
                                                    {{ $user->fname.' '.$user->mname.' '.$user->lname; }}
                                                    @break
                                                @endif
                                            @endforeach
                                        </td>
                                        <td id="{{ $fidel->id; }}_date_remitted">{{ $fidel->date_remitted; }}</td>
                                        <td id="{{ $fidel->id; }}_amount">{{ $fidel->amount; }}</td>
                                        <!-- td>
                                            <button class="btn btn-outline-info" data-toggle="modal" data-target="#ViewModal"
                                            title="View Member" onclick="viewFunction()" >
                                                <span class="fas fa-eye"></span>
                                            </button>
                                            <button class="btn btn-outline-primary" data-toggle="modal" data-target="#EditModal"
                                            title="Edit Member Info" onclick="editFunction()" >
                                                <span class="fas fa-pen"></span>
                                            </button>
                                            <button class="btn btn-outline-danger" data-toggle="modal" data-target="#DeleteModal"
                                            title="Remove Member" onclick="deleteFunction()" >
                                                <span class="fas fa-trash"></span>
                                            </button>
                                            <button class="btn btn-outline-success" data-toggle="modal" data-target="#DeleteModal"
                                            title="Print Statement of Account" onclick="deleteFunction(})" >
                                                <span class="fas fa-print"></span>
                                            </button>
                                        </td -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card card-primary" id="other" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top: 10px;">Staff with Fidelity</h3>
                        <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>

                    <div class="card-body" id="staff_table">
                        <table id="anotherNormalTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Marketting Account Staff</th>
                                    <th>Usertype</th>
                                    <!-- th>Action</th -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    @if($user->with_fidelity == true)
                                    <tr>
                                        <td><input type="checkbox" /></td>
                                        <td id="{{ $user->id; }}_name">{{ $user->fname.' '.$user->mname.' '.$user->lname; }}</td>
                                        <td id="{{ $user->id; }}_type">
                                            @if($user->usertype == 1)
                                                {{ 'Admin'; }}
                                            @elseif($user->usertype == 2)
                                                {{ 'Encoder'; }}
                                            @else
                                                {{ 'Collector'; }}
                                            @endif
                                        </td>
                                       
                                        <!-- td>
                                            <button class="btn btn-outline-info" data-toggle="modal" data-target="#ViewModal"
                                            title="View Member" onclick="viewFunction()" >
                                                <span class="fas fa-eye"></span>
                                            </button>
                                            <button class="btn btn-outline-primary" data-toggle="modal" data-target="#EditModal"
                                            title="Edit Member Info" onclick="editFunction()" >
                                                <span class="fas fa-pen"></span>
                                            </button>
                                            <button class="btn btn-outline-danger" data-toggle="modal" data-target="#DeleteModal"
                                            title="Remove Member" onclick="deleteFunction()" >
                                                <span class="fas fa-trash"></span>
                                            </button>
                                            <button class="btn btn-outline-success" data-toggle="modal" data-target="#DeleteModal"
                                            title="Print Statement of Account" onclick="deleteFunction(})" >
                                                <span class="fas fa-print"></span>
                                            </button>
                                        </td -->
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card card-primary" id="form" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top: 10px;">Register Staff to Fidelity</h3>
                        <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>
                    <form action="/fidelity/register" method="POST">
                        @csrf
                        <div class="card-body">
                            <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">Add User to Fidelity</legend>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="branch_id">User</label>
                                        <select class="form-control chosen-select" id="user_id" name="user_id" required>
                                            @foreach($users as $user)
                                                @if($user->with_fidelity == false || $user->with_fidelity == null)
                                                    <option value="{{ $user->id; }}">{{ $user->fname.' '.$user->mname.' '.$user->lname; }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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
                <h5 class="modal-title">Delete Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteForm" action="/members/destroy" method="POST">
                @csrf
                <div class="modal-body">
                    <h6>Do you want to remove <span id="del_fname"></span> <span id="del_lname"></span> as a Member?</h6>
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

<!-- Import Modal -->
<div class="modal fade" id="ImportModal" tabindex="-1" role="dialog" aria-labelledby="ImportModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uploadForm" action="/members/upload" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label for="formFileSm">Database Excel:</label>
                            <input class="form-control form-control-sm" id="upload_file" name="upload_file" type="file" style="padding-bottom: 30px;">
                        </div>
                    </div> <br>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    
    function showForm(){
        $("#table").attr("style", "display: none;");
        $("#form").removeAttr("style");
    }

    function hideForm(){
        $("#form").attr("style", "display: none;");
        $("#other").attr("style", "display: none;");
        $("#table").removeAttr("style");
    }

    function showOther(){
        $("#table").attr("style", "display: none;");
        $("#other").removeAttr("style");
    }

    function checkBeneficiaries(){
        var program = $("#program_id").val();
        var x = $("#ben_" + program).html();
        if(x == 0){
            $(".beneficiaries").removeAttr("style");
            $(".beneficiaries").attr("style", "display: none;");
        } else {
            $(".beneficiaries").removeAttr("style");
            $(".beneficiaries").attr("style", "--bs-border-opacity: .5;");
        }
    }

    function deleteFunction(id){
        var fname = $("#"+id+"_fname").html();
        var lname = $("#"+id+"_lname").html();
        $("#del_fname").html(fname);
        $("#del_lname").html(lname);
        $("#delete_id").val(id);
    }

</script>