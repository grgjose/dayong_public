<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

            <!-- TABLE SECTION --> 
            <div class="card card-info" id="table">
                <div class="card-header">
                    <h2 class="card-title" style="padding-top: 10px;">User Accounts</h2>

                    <button class="btn btn-success float-right" style="color: white;" onclick="userShowForm()">
                    <span class="fas fa-plus"></span> Add User
                    </button>

                    <!-- 
                    <button class="btn btn-outline-info float-right" style="margin-right: 10px !important;" data-toggle="modal" data-target="#ManageModal">
                        <span class="fas fa-universal-access"></span> Manage Usertypes
                    </button>
                    -->

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="normalTable" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Usertype</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
            
                    @foreach ($users as $user)
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>{{ $user->fname.' '.$user->mname.' '.$user->lname }}</td>
                        <td>{{ $user->username }}</td>
                        <td>
                            {{ $user->usertype_name }}
                        </td>
                        <td>{{ $user->email; }}</td>
                        <td>{{ $user->status; }}</td>
                        <td>
                            <button class="btn btn-outline-secondary"
                            onclick="userViewFunction({{ $user->id; }})" >
                                <span class="fas fa-eye"></span>
                            </button>
                            <button class="btn btn-outline-info"
                            onclick="userEditFunction({{ $user->id; }})" >
                                <span class="fas fa-pen"></span>
                            </button>
                            <button class="btn btn-outline-danger" data-toggle="modal" data-target="#DeleteModal"
                            onclick="userDeleteFunction({{ $user->id; }})" >
                                <span class="fas fa-trash"></span>
                            </button>
                            @if($user->status == "PENDING")
                            <button class="btn btn-outline-success"
                            onclick="userApproveFunction({{ $user->id; }})" >
                                <span class="fas fa-check"></span>
                            </button>
                            @endif
                        </td>

                        <span id="username_{{ $user->id; }}" style="display:none;">{{ $user->username; }}</span>
                        <span id="usertype_{{ $user->id; }}" style="display:none;">{{ $user->usertype; }}</span>
                        <span id="fname_{{ $user->id; }}" style="display:none;">{{ $user->fname; }}</span>
                        <span id="mname_{{ $user->id; }}" style="display:none;">{{ $user->mname; }}</span>
                        <span id="lname_{{ $user->id; }}" style="display:none;">{{ $user->lname; }}</span>
                        <span id="email_{{ $user->id; }}" style="display:none;">{{ $user->email; }}</span>
                        <span id="birthdate_{{ $user->id; }}" style="display:none;">{{ $user->birthdate; }}</span>
                        <span id="contact_num_{{ $user->id; }}" style="display:none;">{{ $user->contact_num; }}</span>

                    </tr>
                    @endforeach
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

            <!-- ADD SECTION --> 
            <div class="card card-primary" id="add_table" style="display: none;">
                <div class="card-header">
                    <h3 class="card-title" style="padding-top: 10px;">Create User Form</h3>
                    <button class="btn btn-secondary float-right" style="color: white;" onclick="userHideForm()">
                        <span class="fas fa-times"></span> Cancel
                    </button>
                </div>
                <form id="add_form" action="/user-accounts/store" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                            <legend class="h5 pl-2 pr-2" style="width: auto; !important">User Details</legend>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                </div>
                                <div class="form-group col">
                                    <label for="usertype">Usertype:</label>
                                    <select name="usertype" class="form-control chosen-select">
                                        <option value="3">Marketting Agent</option>
                                        <option value="2">Encoder</option>
                                        <option value="1">Admin</option>
                                    </select>
                                </div>
                                <div class="form-group col">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                </div>
                            </div> <br>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="fname">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" required>
                                </div>
                                <div class="form-group col">
                                    <label for="mname">Middle Name</label>
                                    <input type="text" class="form-control" id="mname" name="mname" placeholder="Middle name" required>
                                </div>
                                <div class="form-group col">
                                    <label for="lname">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" required>
                                </div>
                            </div> <br>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="store_password">Password</label>
                                    <input type="password" class="form-control" id="store_password" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-group col">
                                    <label for="store_confirm_password">
                                        Confirm Password:
                                        <span id="pass_ok" style="display: none; color: #90EE90;">Password Match</span>
                                        <span id="pass_ng" style="display: none; color: #FF474C;">Password Do Not Match!</span>
                                    </label>
                                    <input type="password" class="form-control" id="store_confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                                </div>
                            </div> <br>
        
                            <div class="row">
                                <div class="form-group col">
                                    <label for="birthdate">Birthdate:</label>
                                    <input type="date" class="form-control" style="height: 45px;" id="birthdate" name="birthdate" >
                                </div>
                                <div class="form-group col">
                                    <label for="contact_num">Contact Number:</label>
                                    <input type="number" class="form-control" style="height: 45px;" id="contact_num" name="contact_num">
                                </div>
                                <div class="form-group col">
                                    <label for="profile_pic">Profile Picture</label>
                                    <input type="file" class="form-control" style="height: 45px;" id="profile_pic" name="profile_pic" accept="image/*">
                                </div>
                            </div> <br>
                        </fieldset>

                    </div>
                    <div class="card-footer">
                        <button id="store_btn" type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="userHideForm()">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- VIEW SECTION --> 
            <div class="card card-primary" id="view_table" style="display: none;">
                <div class="card-header">
                    <h3 class="card-title" style="padding-top: 10px;">Update User Form</h3>
                    <button class="btn btn-secondary float-right" style="color: white;" onclick="userHideForm()">
                        <span class="fas fa-times"></span> Cancel
                    </button>
                </div>
                <form id="view_form" action="/user-accounts/update" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="card-body">

                        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                            <legend class="h5 pl-2 pr-2" style="width: auto; !important">User Details</legend>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" id="view_username" name="username" placeholder="Username" disabled>
                                </div>
                                <div class="form-group col">
                                    <label for="usertype">Usertype:</label>
                                    <select id="view_usertype" name="usertype" class="form-control chosen-select" disabled>
                                        <option value="3">Collector</option>
                                        <option value="2">Encoder</option>
                                        <option value="1">Admin</option>
                                    </select>
                                </div>
                                <div class="form-group col">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="view_email" name="email" placeholder="Email" disabled>
                                </div>
                            </div> <br>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="fname">First Name</label>
                                    <input type="text" class="form-control" id="view_fname" name="fname" placeholder="First name" disabled>
                                </div>
                                <div class="form-group col">
                                    <label for="mname">Middle Name</label>
                                    <input type="text" class="form-control" id="view_mname" name="mname" placeholder="Middle name" disabled>
                                </div>
                                <div class="form-group col">
                                    <label for="lname">Last Name</label>
                                    <input type="text" class="form-control" id="view_lname" name="lname" placeholder="Last name" disabled>
                                </div>
                            </div> <br>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="birthdate">Birthdate:</label>
                                    <input type="date" class="form-control" style="height: 45px;" id="view_birthdate" name="birthdate" disabled>
                                </div>
                                <div class="form-group col">
                                    <label for="contact_num">Contact Number:</label>
                                    <input type="number" class="form-control" style="height: 45px;" id="view_contact_num" name="contact_num" disabled>
                                </div>
                            </div> <br>
                        </fieldset>

                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="userHideForm()">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- EDIT SECTION --> 
            <div class="card card-primary" id="edit_table" style="display: none;">
                <div class="card-header">
                    <h3 class="card-title" style="padding-top: 10px;">Update User Form</h3>
                    <button class="btn btn-secondary float-right" style="color: white;" onclick="userHideForm()">
                        <span class="fas fa-times"></span> Cancel
                    </button>
                </div>
                <form id="edit_form" action="/user-accounts/update" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="card-body">

                        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                            <legend class="h5 pl-2 pr-2" style="width: auto; !important">User Details</legend>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" id="edit_username" name="username" placeholder="Username" required>
                                </div>
                                <div class="form-group col">
                                    <label for="usertype">Usertype:</label>
                                    <select id="edit_usertype" name="usertype" class="form-control chosen-select">
                                        <option value="3">Collector</option>
                                        <option value="2">Encoder</option>
                                        <option value="1">Admin</option>
                                    </select>
                                </div>
                                <div class="form-group col">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="edit_email" name="email" placeholder="Email" required>
                                </div>
                            </div> <br>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="fname">First Name</label>
                                    <input type="text" class="form-control" id="edit_fname" name="fname" placeholder="First name" required>
                                </div>
                                <div class="form-group col">
                                    <label for="mname">Middle Name</label>
                                    <input type="text" class="form-control" id="edit_mname" name="mname" placeholder="Middle name" required>
                                </div>
                                <div class="form-group col">
                                    <label for="lname">Last Name</label>
                                    <input type="text" class="form-control" id="edit_lname" name="lname" placeholder="Last name" required>
                                </div>
                            </div> <br>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="store_password">Password</label>
                                    <input type="password" class="form-control" id="edit_store_password" name="password" placeholder="Password">
                                </div>
                                <div class="form-group col">
                                    <label for="store_confirm_password">
                                        Confirm Password:
                                        <span id="edit_pass_ok" style="display: none; color: #90EE90;">Password Match</span>
                                        <span id="edit_pass_ng" style="display: none; color: #FF474C;">Password Do Not Match!</span>
                                    </label>
                                    <input type="password" class="form-control" id="edit_store_confirm_password" name="confirm_password" placeholder="Confirm Password">
                                </div>
                            </div> <br>
        
                            <div class="row">
                                <div class="form-group col">
                                    <label for="birthdate">Birthdate:</label>
                                    <input type="date" class="form-control" style="height: 45px;" id="edit_birthdate" name="birthdate">
                                </div>
                                <div class="form-group col">
                                    <label for="contact_num">Contact Number:</label>
                                    <input type="number" class="form-control" style="height: 45px;" id="edit_contact_num" name="contact_num">
                                </div>
                                <div class="form-group col">
                                    <label for="profile_pic">Profile Picture</label>
                                    <input type="file" class="form-control" style="height: 45px;" id="edit_profile_pic" name="profile_pic" accept="image/*">
                                </div>
                            </div> <br>
                        </fieldset>

                    </div>
                    <div class="card-footer">
                        <button id="store_btn" type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="userHideForm()">Cancel</button>
                    </div>
                </form>
            </div>

        </div>
      </div>
    </div>
</section>
<!-- / Main Content -->

<!-- DELETE MODAL -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title">Delete Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="delete_form" action="/user-accounts/destroy" method="POST">
                @csrf
                <div class="modal-body">
                    <h6>Do you want to remove <span id="del_fname"></span> <span id="del_lname"></span> as a System User?</h6>
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

    function userHideForm(){
        $("#add_table").attr("style", "display: none;");
        $("#edit_table").attr("style", "display: none;");
        $("#view_table").attr("style", "display: none;");
        $("#table").removeAttr("style");
    }

    function userShowForm(){
        $("#table").attr("style", "display: none;");
        $("#view_table").attr("style", "display: none;");
        $("#edit_table").attr("style", "display: none;");
        $("#add_table").removeAttr("style");
    }

    function userViewFunction(id){
        $("#table").attr("style", "display: none;");
        $('#add_table').attr("style", "display: none;");
        $("#edit_table").attr("style", "display: none;");
        $("#view_table").removeAttr("style");

        $("#view_username").val($("#username_"+id).html());
        $("#view_usertype").val($("#usertype_"+id).html()).trigger("chosen:updated");;
        $("#view_email").val($("#email_"+id).html());
        $("#view_fname").val($("#fname_"+id).html());
        $("#view_mname").val($("#mname_"+id).html());
        $("#view_lname").val($("#lname_"+id).html());
        $("#view_birthdate").val(formatDate(new Date($("#birthdate_"+id).html())));
        $("#view_contact_num").val($("#contact_num_"+id).html());
    }

    function userEditFunction(id){
        $("#table").attr("style", "display: none;");
        $('#add_table').attr("style", "display: none;");
        $("#view_table").attr("style", "display: none;");
        $("#edit_table").removeAttr("style");

        $("#edit_username").val($("#username_"+id).html());
        $("#edit_usertype").val($("#usertype_"+id).html()).trigger("chosen:updated");;
        $("#edit_email").val($("#email_"+id).html());
        $("#edit_fname").val($("#fname_"+id).html());
        $("#edit_mname").val($("#mname_"+id).html());
        $("#edit_lname").val($("#lname_"+id).html());
        $("#edit_birthdate").val(formatDate(new Date($("#birthdate_"+id).html())));
        $("#edit_contact_num").val($("#contact_num_"+id).html());
        $("#edit_form").attr("action", "/user-accounts/update/"+id);
    }

    function userDeleteFunction(id){
        var fname = $("#"+id+"_fname").html();
        var lname = $("#"+id+"_lname").html();
        $("#del_fname").html(fname);
        $("#del_lname").html(lname);
        $("#delete_id").val(id);
    }

    function userApproveFunction(id){
        window.location.href="/user-accounts/approve/"+id;
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