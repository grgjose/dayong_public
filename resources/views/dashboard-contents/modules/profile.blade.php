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

    .chosen-container-single {
        border-radius: 5px;
        height: calc(2.25rem + 2px);
        padding-top: 10px;
    }

</style>
  
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card card-primary" id="form" style="background-color: transparent; box-shadow: none;">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle fill" style="width: 100px !important; height: 100px !important;" 
                                        src="{{asset('storage/profile_pic/'.$my_user["profile_pic"])}}" 
                                        onerror="this.onerror=null;this.src='{{ asset('storage/profile_pic/default.jpg') }}';" alt="User profile picture">
                                    </div>
                                    <h3 class="profile-username text-center">{{ $my_user->fname.' '.$my_user->mname.' '.$my_user->lname; }}</h3>
                                    <p class="text-muted text-center">{{ $my_user->email; }}</p>
                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item p-2">
                                            <b>Sales</b>
                                            <a class="float-right">(Soon)</a>
                                        </li>
                                        <li class="list-group-item p-2">
                                            <b>Fidelity</b>
                                            <a class="float-right">(Soon)</a>
                                        </li>
                                        <li class="list-group-item p-2">
                                            <b>Attendance</b>
                                            <a class="float-right">(Soon)</a>
                                        </li>
                                    </ul>
                                    

                                    <a class="btn btn-primary btn-block" onclick="changeProfilePic()">
                                        <b>Change Profile Picture</b>
                                    </a>

                                    <form id="changePicForm" action="/user-accounts/change_pic" method="post" enctype="multipart/form-data" style="display: none;">
                                        @csrf
                                        <input type="file" id="inputfile" name="profile_pic" oninput="changeProfilePic_submit()" />
                                    </form>
                                </div>
                            </div>
                            <div class="card card-primary" style="display: none;">
                                <div class="card-header">
                                    <h3 class="card-title">About Me</h3>
                                </div>
                                <div class="card-body">
                                    <strong>
                                        <i class="fas fa-book mr-1"></i> Education </strong>
                                    <p class="text-muted"> B.S. in Computer Science from the University of Tennessee at Knoxville </p>
                                    <hr>
                                    <strong>
                                        <i class="fas fa-map-marker-alt mr-1"></i> Location </strong>
                                    <p class="text-muted">Malibu, California</p>
                                    <hr>
                                    <strong>
                                        <i class="fas fa-pencil-alt mr-1"></i> Skills </strong>
                                    <p class="text-muted">
                                        <span class="tag tag-danger">UI Design</span>
                                        <span class="tag tag-success">Coding</span>
                                        <span class="tag tag-info">Javascript</span>
                                        <span class="tag tag-warning">PHP</span>
                                        <span class="tag tag-primary">Node.js</span>
                                    </p>
                                    <hr>
                                    <strong>
                                        <i class="far fa-file-alt mr-1"></i> Notes </strong>
                                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#settings" data-toggle="tab">Settings</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link disabled" href="#activity" data-toggle="tab">Activity</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link disabled" href="#timeline" data-toggle="tab">Timeline</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="settings">
                                            <form class="form-horizontal" method="POST" action="/user-accounts/update/{{ $my_user->id; }}">
                                                @method('PUT')
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col">
                                                        <label for="username">Username:</label>
                                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="{{ $my_user->username; }}">
                                                    </div>
                                                    <div class="form-group col">
                                                        <label for="email">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ $my_user->email; }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col">
                                                        <label for="fname">First Name</label>
                                                        <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" value="{{ $my_user->fname; }}">
                                                    </div>
                                                    <div class="form-group col">
                                                        <label for="mname">Middle Name</label>
                                                        <input type="text" class="form-control" id="mname" name="mname" placeholder="Middle Name" value="{{ $my_user->mname; }}">
                                                    </div>
                                                    <div class="form-group col">
                                                        <label for="lname">Last Name</label>
                                                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" value="{{ $my_user->lname; }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col">
                                                        <label for="store_password">Password (For Change Password Only)</label>
                                                        <input type="password" class="form-control" id="store_password" name="password" placeholder="Password">
                                                    </div>
                                                    <div class="form-group col">
                                                        <label for="store_confirm_password">
                                                            Confirm Password:
                                                            <span id="pass_ok" style="display: none; color: #90EE90;">Password Match</span>
                                                            <span id="pass_ng" style="display: none; color: #FF474C;">Password Do Not Match!</span>
                                                        </label>
                                                        <input type="password" class="form-control" id="store_confirm_password" name="confirm_password" placeholder="Confirm Password">
                                                    </div>
                                                </div>
                            
                                                <div class="row">
                                                    <div class="form-group col">
                                                        <label for="birthdate">Birthdate:</label>
                                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="{{ date('Y-m-d', strtotime($my_user->birthdate)); }}">
                                                    </div>
                                                    <div class="form-group col">
                                                        <label for="contact_num">Contact Number:</label>
                                                        <input type="number" class="form-control" id="contact_num" name="contact_num" value="{{ $my_user->contact_num; }}" >
                                                    </div>
                                                </div> <br> <br>
                                                
                                                <div class="row">
                                                    <div style="position: absolute; right: 0; bottom: 0;" class="mr-4 mb-3">
                                                        <button id="store_btn" type="submit" class="btn btn-lg btn-success">Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane" id="activity">
                                            <div class="post">
                                                <div class="user-block">
                                                    <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                                                    <span class="username">
                                                        <a href="#">Jonathan Burke Jr.</a>
                                                        <a href="#" class="float-right btn-tool">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </span>
                                                    <span class="description">Shared publicly - 7:30 PM today</span>
                                                </div>
                                                <p> Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans. </p>
                                                <p>
                                                    <a href="#" class="link-black text-sm mr-2">
                                                        <i class="fas fa-share mr-1"></i> Share </a>
                                                    <a href="#" class="link-black text-sm">
                                                        <i class="far fa-thumbs-up mr-1"></i> Like </a>
                                                    <span class="float-right">
                                                        <a href="#" class="link-black text-sm">
                                                            <i class="far fa-comments mr-1"></i> Comments (5) </a>
                                                    </span>
                                                </p>
                                                <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                                            </div>
                                            <div class="post clearfix">
                                                <div class="user-block">
                                                    <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                                                    <span class="username">
                                                        <a href="#">Sarah Ross</a>
                                                        <a href="#" class="float-right btn-tool">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </span>
                                                    <span class="description">Sent you a message - 3 days ago</span>
                                                </div>
                                                <p> Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans. </p>
                                                <form class="form-horizontal">
                                                    <div class="input-group input-group-sm mb-0">
                                                        <input class="form-control form-control-sm" placeholder="Response">
                                                        <div class="input-group-append">
                                                            <button type="submit" class="btn btn-danger">Send</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="post">
                                                <div class="user-block">
                                                    <img class="img-circle img-bordered-sm" src="../../dist/img/user6-128x128.jpg" alt="User Image">
                                                    <span class="username">
                                                        <a href="#">Adam Jones</a>
                                                        <a href="#" class="float-right btn-tool">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </span>
                                                    <span class="description">Posted 5 photos - 5 days ago</span>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-6">
                                                        <img class="img-fluid" src="../../dist/img/photo1.png" alt="Photo">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <img class="img-fluid mb-3" src="../../dist/img/photo2.png" alt="Photo">
                                                                <img class="img-fluid" src="../../dist/img/photo3.jpg" alt="Photo">
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <img class="img-fluid mb-3" src="../../dist/img/photo4.jpg" alt="Photo">
                                                                <img class="img-fluid" src="../../dist/img/photo1.png" alt="Photo">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p>
                                                    <a href="#" class="link-black text-sm mr-2">
                                                        <i class="fas fa-share mr-1"></i> Share </a>
                                                    <a href="#" class="link-black text-sm">
                                                        <i class="far fa-thumbs-up mr-1"></i> Like </a>
                                                    <span class="float-right">
                                                        <a href="#" class="link-black text-sm">
                                                            <i class="far fa-comments mr-1"></i> Comments (5) </a>
                                                    </span>
                                                </p>
                                                <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="timeline">
                                            <div class="timeline timeline-inverse">
                                                <div class="time-label">
                                                    <span class="bg-danger"> 10 Feb. 2014 </span>
                                                </div>
                                                <div>
                                                    <i class="fas fa-envelope bg-primary"></i>
                                                    <div class="timeline-item">
                                                        <span class="time">
                                                            <i class="far fa-clock"></i> 12:05 </span>
                                                        <h3 class="timeline-header">
                                                            <a href="#">Support Team</a> sent you an email
                                                        </h3>
                                                        <div class="timeline-body"> Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle quora plaxo ideeli hulu weebly balihoo... </div>
                                                        <div class="timeline-footer">
                                                            <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <i class="fas fa-user bg-info"></i>
                                                    <div class="timeline-item">
                                                        <span class="time">
                                                            <i class="far fa-clock"></i> 5 mins ago </span>
                                                        <h3 class="timeline-header border-0">
                                                            <a href="#">Sarah Young</a> accepted your friend request
                                                        </h3>
                                                    </div>
                                                </div>
                                                <div>
                                                    <i class="fas fa-comments bg-warning"></i>
                                                    <div class="timeline-item">
                                                        <span class="time">
                                                            <i class="far fa-clock"></i> 27 mins ago </span>
                                                        <h3 class="timeline-header">
                                                            <a href="#">Jay White</a> commented on your post
                                                        </h3>
                                                        <div class="timeline-body"> Take me to your leader! Switzerland is small and neutral! We are more like Germany, ambitious and misunderstood! </div>
                                                        <div class="timeline-footer">
                                                            <a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="time-label">
                                                    <span class="bg-success"> 3 Jan. 2014 </span>
                                                </div>
                                                <div>
                                                    <i class="fas fa-camera bg-purple"></i>
                                                    <div class="timeline-item">
                                                        <span class="time">
                                                            <i class="far fa-clock"></i> 2 days ago </span>
                                                        <h3 class="timeline-header">
                                                            <a href="#">Mina Lee</a> uploaded new photos
                                                        </h3>
                                                        <div class="timeline-body">
                                                            <img src="https://placehold.it/150x100" alt="...">
                                                            <img src="https://placehold.it/150x100" alt="...">
                                                            <img src="https://placehold.it/150x100" alt="...">
                                                            <img src="https://placehold.it/150x100" alt="...">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <i class="far fa-clock bg-gray"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card card-primary" id="form" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top: 10px;">New Collections Form</h3>
                        <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>
                    <form id="form_tag" action="/entries/store" method="post">
                        @csrf
                        <div class="card-body">
                            <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">Collection Details</legend>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="branch_id">Branch:</label>
                                        <select class="form-control chosen-select" id="branch_id" name="branch_id">
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id; }}">{{ $branch->branch; }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="member_id">Agent:</label>
                                        <select class="form-control chosen-select" id="marketting_agent" name="marketting_agent">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id; }}">{{ $user->fname.' '.$user->mname.' '.$user->lname; }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="member_id">Member:</label>
                                        <select class="form-control chosen-select" id="member_id" name="member_id">
                                            @foreach($members as $member)
                                                <option value="{{ $member->id; }}">{{ $member->fname.' '.$member->mname.' '.$member->lname; }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="branch_id">Program:</label>
                                        <select class="form-control chosen-select" id="program_id" name="program_id">
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id; }}">{{ $program->code; }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="or_number">OR:</label>
                                        <input type="text" class="form-control" id="or_number" name="or_number" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="amount">Amount:</label>
                                        <input type="number" class="form-control" id="amount" name="amount" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="times">How many Payments:</label>
                                        <input type="number" class="form-control" id="times" name="number_of_payment" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="month_from">From (Month):</label>
                                        <input type="month" class="form-control" id="times" name="month_from" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="month_to">To (Month):</label>
                                        <input type="month" class="form-control" id="times" name="month_to" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="remarks">Remarks:</label>
                                        <input type="text" class="form-control" id="remarks" name="remarks" value="">
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
                        <h3 class="card-title" style="padding-top: 10px;">New Collections Form</h3>
                        <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>
                    <form id="editForm" action="/entries/update" method="post">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">Collection Details</legend>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="branch_id">Branch:</label>
                                        <select class="form-control chosen-select" id="edit_branch_id" name="branch_id">
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id; }}">{{ $branch->branch; }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="member_id">Agent:</label>
                                        <select class="form-control chosen-select" id="edit_marketting_agent" name="marketting_agent">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id; }}">{{ $user->fname.' '.$user->mname.' '.$user->lname; }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="member_id">Member:</label>
                                        <select class="form-control chosen-select" id="edit_member_id" name="member_id">
                                            @foreach($members as $member)
                                                <option value="{{ $member->id; }}">{{ $member->fname.' '.$member->mname.' '.$member->lname; }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="branch_id">Program:</label>
                                        <select class="form-control chosen-select" id="edit_program_id" name="program_id">
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id; }}">{{ $program->code; }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="or_number">OR:</label>
                                        <input type="text" class="form-control" id="edit_or_number" name="or_number" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="amount">Amount:</label>
                                        <input type="number" class="form-control" id="edit_amount" name="amount" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="times">How many Payments:</label>
                                        <input type="number" class="form-control" id="edit_times" name="number_of_payment" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="month_from">From (Month):</label>
                                        <input type="month" class="form-control" id="edit_month_from" name="month_from" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="month_to">To (Month):</label>
                                        <input type="month" class="form-control" id="edit_month_to" name="month_to" value="" required>
                                    </div>
                                </div> <br>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="remarks">Remarks:</label>
                                        <input type="text" class="form-control" id="edit_remarks" name="remarks" value="">
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
                <h5 class="modal-title">Delete Collection Data?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteForm" action="/entries/destroy" method="POST">
                @csrf
                <div class="modal-body">
                    <h6>Do you want to remove this Collection?</h6>
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
            <form id="uploadForm" action="/entries/upload" method="POST" enctype="multipart/form-data">
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
 
    function changeProfilePic(){
        $('#inputfile').click();
    }

    function changeProfilePic_submit(){
        $('#changePicForm').submit();
    }

</script>