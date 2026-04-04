<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <!-- TABLE SECTION -->
                <div class="card card-info" id="newSalesTable">
                    <div class="card-header">

                        <h2 class="card-title" style="padding-top: 10px;">New Sales Table</h2>

                        @if($my_user->usertype != 3)
                            <button class="btn btn-success float-right" onclick="newSalesShowForm()">
                                <span class="fas fa-plus"></span> Add New Sales
                            </button>
                        @endif

                        @if($my_user->usertype == 1)
                            <button class="btn btn-secondary float-right mr-3" data-toggle="modal" data-target="#ImportModal">
                                <span class="fas fa-upload"></span> Import from Excel New Sales
                            </button>
                        @endif
                    </div>

                    <div class="card-body">
                        <table id="normalTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 25%;">Full Name</th>
                                    <th style="width: 10%;">Branch</th>
                                    <th style="width: 10%;">Program</th>
                                    <th style="width: 10%;">Contact No.</th>
                                    <th style="width: 10%;">OR #</th>
                                    <th style="width: 15%;">MAS</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members_program as $mp)
                                    <tr>
                                        <td><input type="checkbox" /></td>
                                        <td>
                                            @foreach($members as $member)
                                                @if($member->id == $mp->member_id)
                                                    {{ strtoupper($member->fname.' '.$member->mname.' '.$member->lname.' '.$member->ext) }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($branches as $branch)
                                                @if($branch->id == $mp->branch_id)
                                                    {{ strtoupper($branch->branch) }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($programs as $program)
                                                @if($program->id == $mp->program_id)
                                                    {{ strtoupper($program->code) }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($members as $member)
                                                @if($member->id == $mp->member_id)
                                                    {{ $member->contact_num }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $mp->or_number }}</td>
                                        <td>
                                            @foreach($members as $member)
                                                @if($member->id == $mp->member_id)
                                                    @foreach($users as $user)
                                                        @if($user->id == $member->agent_id)
                                                            {{ strtoupper($user->fname.' '.$user->mname.' '.$user->lname) }}
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-info" data-toggle="modal" data-target="#ViewModal"
                                                    onclick="newSalesViewFunction({{ $mp->id }})">
                                                <span class="fas fa-eye"></span>
                                            </button>

                                            @if($my_user->usertype == 1)
                                                <button class="btn btn-outline-primary" data-toggle="modal" data-target="#EditModal"
                                                        onclick="newSalesEditFunction({{ $mp->id }})">
                                                    <span class="fas fa-pen"></span>
                                                </button>
                                                <button class="btn btn-outline-danger" data-toggle="modal" data-target="#DeleteModal"
                                                        onclick="newSalesDeleteFunction({{ $mp->id }})">
                                                    <span class="fas fa-trash"></span>
                                                </button>
                                            @endif

                                            <button class="btn btn-outline-success" onclick="newSalesPrintFunction({{ $mp->id }})">
                                                <span class="fas fa-money-bill-wave-alt"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ADD NEW SALES SECTION -->
                <div class="card card-primary" id="newSalesForm" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top: 10px;">New Sales Form</h3>
                        <button class="btn btn-secondary float-right" onclick="newSalesHideForm()">
                            <span class="fas fa-times"></span> Cancel
                        </button>
                    </div>

                    <form action="/new-sales/store" method="POST">
                        @csrf
                        <div class="card-body">

                            <fieldset class="border p-3 mb-2 rounded">
                                <legend class="h5">Location and Program</legend>
                                <!-- WARNING MESSAGES (hidden by default) -->
                                <div id="or-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>
                                <div id="app-no-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>
                                <div class="row">
                                    <div class="form-group col">
                                        <label>Branch</label>
                                        <select class="form-control chosen-select" name="branch_id">
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ strtoupper($branch->branch) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col">
                                        <label>Program</label>
                                        <select class="form-control chosen-select" name="program_id">
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id }}">{{ strtoupper($program->code) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col">
                                        <label>OR #</label>
                                        <input type="number" class="form-control" id="or_number" name="or_number" placeholder="ENTER OR NUMBER">
                                    </div>

                                    <div class="form-group col">
                                        <label>Application #</label>
                                        <input type="number" class="form-control" id="app_no" name="app_no" placeholder="ENTER APPLICATION NUMBER">
                                    </div>

                                    <div class="form-group col">
                                        <label>Creation Date</label>
                                        <input type="date" class="form-control" name="created_at" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border p-3 mb-2 rounded">
                                <legend class="h5">Personal Information</legend>
                                <div class="form-group">
                                    <label>Member</label>
                                    <select class="form-control chosen-select" id="member_id" name="member_id">
                                        <option value="0">NONE</option>
                                        @foreach($members as $member)
                                            <option value="{{ $member->id }}">
                                                {{ strtoupper($member->fname.' '.$member->mname.' '.$member->lname.' '.$member->ext) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>

                            <fieldset class="border p-3 mb-2 rounded">
                                <legend class="h5">Others</legend>

                                <div class="row">
                                    <div class="form-group col">
                                        <label>Contact Person</label>
                                        <input type="text" class="form-control text-uppercase" name="contact_person" placeholder="CONTACT PERSON NAME">
                                    </div>
                                    <div class="form-group col">
                                        <label>Contact #</label>
                                        <input type="text" class="form-control" name="contact_person_num" placeholder="CONTACT PERSON NUMBER">
                                    </div>
                                    <div class="form-group col">
                                        <label>Registration Fee</label>
                                        <input type="number" class="form-control" name="registration_fee" placeholder="ENTER REGISTRATION FEE">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col">
                                        <label>MAS</label>
                                        <select class="form-control chosen-select" name="agent_id">
                                            @foreach($users as $user)
                                                @if($user->usertype == 3)
                                                    <option value="{{ $user->id }}">
                                                        {{ strtoupper($user->fname.' '.$user->mname.' '.$user->lname) }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label>Amount Collected</label>
                                        <input type="number" class="form-control" name="amount" placeholder="ENTER AMOUNT COLLECTED">
                                    </div>
                                    <div class="form-group col">
                                        <label>Incentives (%)</label>
                                        <input type="number" class="form-control" name="incentives" placeholder="ENTER INCENTIVES PERCENTAGE">
                                    </div>
                                </div>
                            </fieldset>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" onclick="newSalesHideForm()">Cancel</button>
                        </div>
                    </form>
                </div>

                <div class="card card-primary" id="newSalesView" style="display:none;"></div>

            </div>
        </div>
    </div>
</section>
