<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- TABLE SECTION -->
                <div class="card card-info" id="table">
                    <div class="card-header">

                        <h2 class="card-title" style="padding-top: 10px;">Members List</h2>

                        @if($my_user->usertype != 3)
                            <button class="btn btn-success float-right" onclick="membersShowForm()">
                                <span class="fas fa-plus"></span> Register a Member
                            </button>
                        @endif

                        @if($my_user->usertype == 1)
                            <button class="btn btn-secondary float-right mr-3" data-toggle="modal" data-target="#ImportModal">
                                <span class="fas fa-upload"></span> Import a Members List
                            </button>
                        @endif
                    </div>

                    <div class="card-body">
                        <table id="normalTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 10%">First Name</th>
                                    <th style="width: 10%">Middle Name</th>
                                    <th style="width: 10%">Last Name</th>
                                    <th style="width: 20%">Address</th>
                                    <th style="width: 10%">Contact No.</th>
                                    <th style="width: 10%">MAS</th>
                                    <th style="width: 10%">Encoder</th>
                                    <th style="width: 15%">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($members as $member)
                                    <tr>
                                        <td><input type="checkbox" /></td>
                                        <td id="{{ $member->id }}_fname">{{ $member->fname }}</td>
                                        <td id="{{ $member->id }}_mname">{{ $member->mname }}</td>
                                        <td id="{{ $member->id }}_lname">{{ $member->lname }}</td>
                                        <td id="{{ $member->id }}_address">{{ $member->address }}</td>
                                        <td id="{{ $member->id }}_contact_num">{{ $member->contact_num }}</td>

                                        <td id="{{ $member->id }}_agent">
                                            @foreach($users as $user)
                                                @if($user->id == $member->agent_id)
                                                    <span style="display: none;" id="{{ $member->id }}_agent_id">{{ $member->agent_id }}</span>
                                                    {{ strtoupper($user->fname.' '.$user->mname.' '.$user->lname) }}
                                                @endif
                                            @endforeach
                                        </td>

                                        <td id="{{ $member->id }}_encoder">
                                            @foreach($users as $user)
                                                @if($user->id == $member->encoder_id)
                                                    <span style="display: none;" id="{{ $member->id }}_encoder_id">{{ $member->encoder_id }}</span>
                                                    {{ strtoupper($user->fname.' '.$user->mname.' '.$user->lname) }}
                                                @endif
                                            @endforeach
                                        </td>

                                        <td>
                                            <button class="btn btn-outline-info"
                                                    title="View Member"
                                                    onclick="membersLoadView({{ $member->id }})">
                                                <span class="fas fa-eye"></span>
                                            </button>

                                            @if($my_user->usertype == 1)
                                                <button class="btn btn-outline-primary"
                                                        title="Edit Member Info"
                                                        onclick="membersLoadEdit({{ $member->id }})">
                                                    <span class="fas fa-pen"></span>
                                                </button>

                                                <button class="btn btn-outline-danger"
                                                        data-toggle="modal"
                                                        data-target="#DeleteModal"
                                                        title="Remove Member"
                                                        onclick="membersPrepareDelete({{ $member->id }})">
                                                    <span class="fas fa-trash"></span>
                                                </button>
                                            @endif

                                            {{-- <button class="btn btn-outline-success"
                                                    title="Print Statement of Account"
                                                    onclick="membersPrintSOA({{ $member->id }})">
                                                <span class="fas fa-print"></span>
                                            </button> --}}

                                            <!-- Add Combo Box Options -->
                                            <select class="form-control" id="combo_options" name="combo_options">
                                                <option value="">Select Action</option>
                                                <option value="option1"><a href="#">Mark as Suspended</a></option>
                                                <option value="option1"><a href="#">Mark as Deceased</a></option>
                                                <option value="option2"><a href="#">Print SOA</a></option>
                                            </select>

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>

                <!-- ADD MEMBER SECTION -->
                <div class="card card-primary" id="form" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top: 10px;">Membership Application Form</h3>
                        <button class="btn btn-secondary float-right" style="color: white;" onclick="membersHideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>

                    <form action="/members/store" method="POST">
                        @csrf
                        <div class="card-body">

                            <!-- Checkbox to Add NewSales Info -->
                            <div class="form-group">
                                <div class="custom-control custom-switch custom-switch-on-success" style="padding-left: 3.25rem; padding-top: 0.75rem;">
                                    <input type="checkbox" class="custom-control-input" id="add_new_sales" name="add_new_sales" value="add_new_sales" onclick="toggleNewSales()" checked>
                                    <label class="custom-control-label" for="add_new_sales">Add To New Sales After Member Registration</label>
                                </div>
                            </div>

                            <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">Personal Information</legend>
                                <!-- WARNING MESSAGES (hidden by default) -->
                                <div id="email-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>
                                <div id="member-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>

                                <div class="row">
                                    <div class="form-group col">
                                        <label for="fname">First Name</label>
                                        <input type="text" class="form-control text-uppercase" id="fname" name="fname" placeholder="ENTER FIRST NAME" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="mname">Middle Name</label>
                                        <input type="text" class="form-control text-uppercase" id="mname" name="mname" placeholder="ENTER MIDDLE NAME">
                                    </div>
                                    <div class="form-group col">
                                        <label for="lname">Last Name</label>
                                        <input type="text" class="form-control text-uppercase" id="lname" name="lname" placeholder="ENTER LAST NAME" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="ext">Ext Name</label>
                                        <input type="text" class="form-control text-uppercase" id="ext" name="ext" placeholder="ENTER EXT. NAME (JR, SR, ETC.)">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col">
                                        <label for="birthdate">Birthdate</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" placeholder="ENTER BIRTHDATE" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="sex">Sex</label>
                                        <select class="form-control chosen-select" id="sex" name="sex" required>
                                            <option value="MALE">MALE</option>
                                            <option value="FEMALE">FEMALE</option>
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="birthplace">Place of Birth</label>
                                        <input type="text" class="form-control text-uppercase" id="birthplace" name="birthplace" placeholder="ENTER PLACE OF BIRTH">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col">
                                        <label for="citizenship">Citizenship</label>
                                        <input type="text" class="form-control text-uppercase" id="citizenship" name="citizenship" placeholder="ENTER CITIZENSHIP (FILIPINO, AMERICAN, ETC.)" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="civil_status">Civil Status</label>
                                        <select class="form-control chosen-select" id="civil_status" name="civil_status" required>
                                            <option value="single">SINGLE</option>
                                            <option value="married">MARRIED</option>
                                            <option value="widowed">WIDOWED</option>
                                            <option value="divorced">DIVORCED</option>
                                            <option value="separated">SEPARATED</option>
                                            <option value="live-in">LIVE-IN</option>
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="contact_num">Contact #</label>
                                        <input type="number" class="form-control" id="contact_num" name="contact_num" placeholder="ENTER CONTACT NUMBER (+63)" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="email">Email address</label>
                                        <input type="email" class="form-control text-uppercase" id="email" name="email" placeholder="ENTER EMAIL (OPTIONAL)">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control text-uppercase" id="address" name="address" placeholder="ENTER CURRENT ADDRESS" required>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">Claimant's Personal Information</legend>


                                <div class="row">
                                    <div class="form-group col">
                                        <label for="fname_c">First Name</label>
                                        <input type="text" class="form-control text-uppercase" id="fname_c" name="fname_c" placeholder="ENTER FIRST NAME" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="mname_c">Middle Name</label>
                                        <input type="text" class="form-control text-uppercase" id="mname_c" name="mname_c" placeholder="ENTER MIDDLE NAME" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="lname_c">Last Name</label>
                                        <input type="text" class="form-control text-uppercase" id="lname_c" name="lname_c" placeholder="ENTER LAST NAME" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="ext_c">Ext Name</label>
                                        <input type="text" class="form-control text-uppercase" id="ext_c" name="ext_c" placeholder="ENTER EXT. NAME (JR, SR, ETC.)">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col">
                                        <label for="birthdate_c">Birthdate</label>
                                        <input type="date" class="form-control" id="birthdate_c" name="birthdate_c" placeholder="ENTER BIRTHDATE" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="sex_c">Sex</label>
                                        <select class="form-control chosen-select" id="sex_c" name="sex_c" required>
                                            <option value="MALE">MALE</option>
                                            <option value="FEMALE">FEMALE</option>
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="contact_num_c">Contact #</label>
                                        <input type="number" class="form-control" id="contact_num_c" name="contact_num_c" placeholder="ENTER CONTACT NUMBER (+63)" required>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border p-3 mb-2 rounded newsales" style="--bs-border-opacity: .5;">
                                <legend class="h5 pl-2 pr-2" style="width: auto; !important">New Sales Information</legend>
                                <!-- WARNING MESSAGES (hidden by default) -->
                                <div id="or-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>
                                <div id="app-no-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>
                                <div id="program-age-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="branch_id">Branch</label>
                                        <select class="form-control chosen-select text-uppercase" id="branch_id" name="branch_id">
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->branch }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col">
                                        <label for="program_id">Program</label>
                                        <select class="form-control chosen-select text-uppercase" id="program_id" name="program_id" onchange="checkBeneficiaries()">
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id }}">{{ $program->code }}</option>
                                            @endforeach
                                        </select>

                                        @foreach($programs as $program)
                                            <span style="display: none;" id="ben_{{ $program->id }}">{{ $program->beneficiaries_count }}</span>
                                        @endforeach
                                    </div>

                                    <div class="form-group col">
                                        <label for="or_num">OR #:</label>
                                        <input type="number" class="form-control" id="or_number" name="or_number" placeholder="ENTER OR NUMBER">
                                    </div>

                                    <div class="form-group col">
                                        <label for="or_date">OR Date:</label>
                                        <input type="date" class="form-control" id="or_date" name="or_date" placeholder="ENTER OR DATE">
                                    </div>

                                    <div class="form-group col">
                                        <label for="app_no">Application #:</label>
                                        <input type="number" class="form-control" id="app_no" name="app_no" placeholder="ENTER APPLICATION NUMBER">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col">
                                        <label for="registration_fee">Registration Fee:</label>
                                        <input type="number" class="form-control" id="registration_fee" name="registration_fee" placeholder="ENTER REGISTRATION FEE AMOUNT">
                                    </div>

                                    <div class="form-group col">
                                        <label for="contact_person_num">MAS:</label>
                                        <select class="form-control chosen-select text-uppercase" id="agent_id" name="agent_id">
                                            @foreach($users as $user)
                                                @if($user->usertype == 3)
                                                    <option class="text-uppercase" value="{{ $user->id }}">{{ strtoupper($user->fname.' '.$user->mname.' '.$user->lname) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col">
                                        <label for="contact_person_num">Amount Collected:</label>
                                        <input type="text" class="form-control" id="amount" name="amount" placeholder="ENTER AMOUNT">
                                    </div>

                                    <div class="form-group col">
                                        <label for="contact_person_num">Incentives Amount:</label>
                                        <input type="text" class="form-control" id="incentives" name="incentives" placeholder="ENTER INCENTIVE'S AMOUNT">
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Add Button for Beneficiaries -->
                            <div class="form-group mb-3 mt-3">
                                <button type="button" class="btn btn-info" id="add-beneficiary" onclick="addBeneficiary()">
                                    <span class="fas fa-plus"></span> Add Beneficiary
                                </button>
                            </div>

                            <div id="beneficiaries-container"></div>

                        </div>

                        <div class="card-footer">
                            <button type="button" class="btn btn-primary" onclick="membersConfirmRegistration()">
                                Submit
                            </button>
                            <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="membersHideForm()">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- VIEW SECTION -->
                <div class="card card-primary" id="view" style="display: none;">
                </div>

            </div>
        </div>
    </div>

    <!-- Confirm Registration Modal -->
    <div class="modal fade" id="ConfirmRegisterModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header bg-info">
                    <h5 class="modal-title">Confirm Member Registration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-warning mb-3">
                        Please review all details before submitting. Scroll down to see full registration summary.
                    </div>

                    <pre id="registration-summary" style="white-space: pre-wrap; font-family: monospace; font-size: 14px;"></pre>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="confirmMemberSubmit()">
                        Confirm & Submit
                    </button>
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

            <form id="uploadForm_ExcelMembers" action="/members/loadSheets" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label class="form-label">Membership Accounts Monitoring File</label>
                            <div id="response"></div>
                            <div class="input-group">
                                <div class="custom-file">
                                  <input type="file" class="custom-file-input" id="upload_file" name="upload_file">
                                  <label class="custom-file-label" for="upload_file">Choose file</label>
                                </div>
                            </div>
                            <br>

                            <div class="form-group col">
                                <label for="sheets" class="form-label">Sheets</label>
                                <select class="form-control chosen-select" id="sheets" name="sheetName"></select>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="uploadButton_ExcelMembers" class="btn btn-success" disabled>Upload</button>
                    <button type="submit" class="btn btn-warning">Load Sheets</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Beneficiary Template (Hidden) -->
<template id="beneficiary-template">
    <fieldset class="border p-3 mb-2 rounded beneficiaries position-relative">
        <button type="button"
                class="btn btn-sm btn-danger beneficiary-remove-btn"
                title="Remove Beneficiary">
            &times;
        </button>

        <legend class="h5 pl-2 pr-2 beneficiary-legend" style="width:auto;">
            Beneficiaries #__INDEX__
        </legend>

        <div class="row">
            <div class="form-group col">
                <label>First Name</label>
                <input type="text" class="form-control text-uppercase" placeholder="FIRST NAME"
                       name="beneficiaries[__INDEX__][fname]">
            </div>
            <div class="form-group col">
                <label>Middle Name</label>
                <input type="text" class="form-control text-uppercase" placeholder="MIDDLE NAME"
                       name="beneficiaries[__INDEX__][mname]">
            </div>
            <div class="form-group col">
                <label>Last Name</label>
                <input type="text" class="form-control text-uppercase" placeholder="LAST NAME"
                       name="beneficiaries[__INDEX__][lname]">
            </div>
            <div class="form-group col">
                <label>Ext Name</label>
                <input type="text" class="form-control text-uppercase" placeholder="EXT NAME (JR, SR, ETC.)"
                       name="beneficiaries[__INDEX__][ext]">
            </div>
        </div>

        <div class="row">
            <div class="form-group col">
                <label>Birthdate</label>
                <input type="date" class="form-control beneficiary-birthdate" id="beneficiary___INDEX___birthdate"
                       name="beneficiaries[__INDEX__][birthdate]">
            </div>

            <div class="form-group col">
                <label>Sex</label>
                <select class="form-control chosen-select"
                        name="beneficiaries[__INDEX__][sex]">
                    <option value="MALE">MALE</option>
                    <option value="FEMALE">FEMALE</option>
                </select>
            </div>

            <div class="form-group col">
                <label>Relationship</label>
                <input type="text" class="form-control text-uppercase" placeholder="RELATIONSHIP"
                       name="beneficiaries[__INDEX__][relationship]">
            </div>

            <div class="form-group col">
                <label>Contact #</label>
                <input type="number" class="form-control text-uppercase" placeholder="CONTACT NUMBER (+63)"
                       name="beneficiaries[__INDEX__][contact_num]">
            </div>
        </div>
    </fieldset>
</template>

<form id="soa_printer" method="POST">
    @csrf
</form>
