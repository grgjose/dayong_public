<div class="card-header">
    <h3 class="card-title" style="padding-top: 10px;">Edit Member Details</h3>
    <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
        <span class="fas fa-times"></span> Close
    </button>
</div>

<form action="/new-sales/update/{{ $id; }}" method="POST">
    @method('PUT')
    @csrf
    <div class="card-body">
        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
            <legend class="h5 pl-2 pr-2" style="width: auto; !important">Location and Program</legend>
            <div class="row">
                <div class="form-group col">
                    <label for="branch_id">Branch</label>
                    <select class="form-control chosen-select" id="branch_id" name="branch_id">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id; }}" {{($member_program->branch_id == $branch->id)?'selected':''; }}>{{ $branch->branch; }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col">
                    <label for="program_id">Program</label>
                    <select class="form-control chosen-select" id="program_id" name="program_id" onchange="checkBeneficiaries()" value="{{ $member_program->program_id;  }}">
                        @foreach($programs as $program)
                            <option value="{{ $program->id; }}" {{($member_program->program_id == $program->id)?'selected':''; }}>{{ $program->code; }}</option>
                            <span style="display: none;" id="ben_{{ $program->id }}">{{ $program->with_beneficiaries; }}</span>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col">
                    <label for="or_num">OR #:</label>
                    <input type="number" class="form-control" id="or_number" name="or_number" placeholder="Enter OR Number" value="{{ $member_program->or_number; }}" >
                </div>
                <div class="form-group col">
                    <label for="or_num">Application #:</label>
                    <input type="number" class="form-control" id="app_no" name="app_no" placeholder="Enter Application Number" value="{{ $member_program->app_no; }}"  >
                </div>
            </div>
        </fieldset>
        
        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
            <legend class="h5 pl-2 pr-2" style="width: auto; !important">Personal Information</legend>
            <div class="row">
                <div class="form-group col">
                    <label for="fname">Members Name:</label>
                    <select class="form-control chosen-select" id="member_id" name="member_id" value="{{ $member_program->member_id; }}" >
                        @foreach($members as $member)
                            <option value="{{ $member->id; }}" {{($member_program->member_id == $member->id)?'selected':''; }}>
                                {{ $member->fname.' '.$member->mname.' '.$member->lname.' '.$member->ext; }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
            <legend class="h5 pl-2 pr-2" style="width: auto; !important">Others</legend>
            <div class="row">
                <div class="form-group col">
                    <label for="contact_person">Contact Person</label>
                    <input type="text" class="form-control" id="contact_person" name="contact_person" 
                    placeholder="Enter Contact Person" value="{{ $member_program->contact_person; }}" >
                </div>
                <div class="form-group col">
                    <label for="contact_person_num">Contact #:</label>
                    <input type="text" class="form-control" id="contact_person_num" name="contact_person_num" 
                    placeholder="Enter Contact Person's Number" value="{{ $member_program->contact_person_num; }}" >
                </div>
                <div class="form-group col">
                    <label for="registration_fee">Registration Fee:</label>
                    <input type="number" class="form-control" id="registration_fee" name="registration_fee" 
                    placeholder="Enter Registration Fee Amount" value="{{ $member_program->registration_fee; }}" >
                </div>
            </div>
            <div class="row">
                <div class="form-group col">
                    <label for="contact_person_num">MAS:</label>
                    <select class="form-control chosen-select" id="agent_id" name="agent_id" value="{{ $member_program->agent_id; }}" >
                        @foreach($users as $user)
                            <option value="{{ $user->id; }}" {{($member_program->agent_id == $user->id)?'selected':''; }}>{{ $user->fname.' '.$user->mname.' '.$user->lname; }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col">
                    <label for="contact_person_num">Amount Collected:</label>
                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter Amount" value="{{ $member_program->amount; }}" >
                </div>
                <div class="form-group col">
                    <label for="contact_person_num">Incentives (%):</label>
                    <input type="text" class="form-control" id="incentives" name="incentives" placeholder="Enter Incentive's Percentage" value="{{ $member_program->incentives; }}" >
                </div>
                <div class="form-group col">
                    <label for="contact_person_num">Fidelity (%):</label>
                    <input type="text" class="form-control" id="fidelity" name="fidelity" placeholder="Enter Fidelity" value="{{ $member_program->fidelity; }}" >
                </div>
            </div>
        </fieldset>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="hideForm()">Cancel</button>
    </div>
</form>