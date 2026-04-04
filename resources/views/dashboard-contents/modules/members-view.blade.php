<div class="card-header">
    <h3 class="card-title" style="padding-top: 10px;">View Member Details</h3>
    <button class="btn btn-secondary float-right" style="color: white;" onclick="membersHideForm()">
        <span class="fas fa-times"></span> Close
    </button>
</div>

<form>
    <div class="card-body">

        {{-- ===================== PERSONAL INFORMATION ===================== --}}
        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
            <legend class="h5 pl-2 pr-2" style="width: auto !important">Personal Information</legend>

            <div class="row">
                <div class="form-group col">
                    <label>First Name</label>
                    <input type="text" class="form-control" value="{{ $member->fname }}" disabled>
                </div>

                <div class="form-group col">
                    <label>Middle Name</label>
                    <input type="text" class="form-control" value="{{ $member->mname }}" disabled>
                </div>

                <div class="form-group col">
                    <label>Last Name</label>
                    <input type="text" class="form-control" value="{{ $member->lname }}" disabled>
                </div>

                <div class="form-group col">
                    <label>Ext Name</label>
                    <input type="text" class="form-control" value="{{ $member->ext }}" disabled>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label>Birthdate</label>
                    <input type="date" class="form-control"
                           value="{{ $member->birthdate ? substr($member->birthdate, 0, 10) : '' }}"
                           disabled>
                </div>

                <div class="form-group col">
                    <label>Sex</label>
                    <select class="form-control chosen-select" disabled>
                        <option value="MALE" {{ $member->sex == 'MALE' ? 'selected' : '' }}>Male</option>
                        <option value="FEMALE" {{ $member->sex == 'FEMALE' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="form-group col">
                    <label>Place of Birth</label>
                    <input type="text" class="form-control" value="{{ $member->birthplace }}" disabled>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label>Citizenship</label>
                    <input type="text" class="form-control" value="{{ $member->citizenship }}" disabled>
                </div>

                <div class="form-group col">
                    <label>Civil Status</label>
                    <select class="form-control chosen-select" disabled>
                        <option value="SINGLE" {{ $member->civil_status == 'SINGLE' ? 'selected' : '' }}>Single</option>
                        <option value="MARRIED" {{ $member->civil_status == 'MARRIED' ? 'selected' : '' }}>Married</option>
                    </select>
                </div>

                <div class="form-group col">
                    <label>Contact #</label>
                    <input type="text" class="form-control" value="{{ $member->contact_num }}" disabled>
                </div>

                <div class="form-group col">
                    <label>Email address</label>
                    <input type="email" class="form-control" value="{{ $member->email }}" disabled>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label>Address</label>
                    <input type="text" class="form-control" value="{{ $member->address }}" disabled>
                </div>
            </div>
        </fieldset>

        {{-- ===================== CLAIMANT INFORMATION ===================== --}}
        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
            <legend class="h5 pl-2 pr-2" style="width: auto !important">Claimant's Personal Information</legend>

            <div class="row">
                <div class="form-group col">
                    <label>First Name</label>
                    <input type="text" class="form-control"
                           value="{{ $claimant->fname ?? '' }}"
                           disabled>
                </div>

                <div class="form-group col">
                    <label>Middle Name</label>
                    <input type="text" class="form-control"
                           value="{{ $claimant->mname ?? '' }}"
                           disabled>
                </div>

                <div class="form-group col">
                    <label>Last Name</label>
                    <input type="text" class="form-control"
                           value="{{ $claimant->lname ?? '' }}"
                           disabled>
                </div>

                <div class="form-group col">
                    <label>Ext Name</label>
                    <input type="text" class="form-control"
                           value="{{ $claimant->ext ?? '' }}"
                           disabled>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label>Birthdate</label>
                    <input type="date" class="form-control"
                           value="{{ isset($claimant->birthdate) ? substr($claimant->birthdate, 0, 10) : '' }}"
                           disabled>
                </div>

                <div class="form-group col">
                    <label>Sex</label>
                    <select class="form-control chosen-select" disabled>
                        <option value="MALE" {{ (isset($claimant->sex) && $claimant->sex == 'MALE') ? 'selected' : '' }}>Male</option>
                        <option value="FEMALE" {{ (isset($claimant->sex) && $claimant->sex == 'FEMALE') ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="form-group col">
                    <label>Contact #</label>
                    <input type="text" class="form-control"
                           value="{{ $claimant->contact_num ?? '' }}"
                           disabled>
                </div>
            </div>
        </fieldset>

        {{-- ===================== BENEFICIARIES ===================== --}}
        @if(isset($beneficiaries) && count($beneficiaries) > 0)

            @foreach($beneficiaries as $index => $beneficiary)
                <fieldset class="border p-3 mb-2 rounded beneficiaries">
                    <legend class="h5 pl-2 pr-2" style="width: auto !important">
                        Beneficiary #{{ $index + 1 }}
                    </legend>

                    <div class="row">
                        <div class="form-group col">
                            <label>First Name</label>
                            <input type="text" class="form-control" value="{{ $beneficiary->fname }}" disabled>
                        </div>

                        <div class="form-group col">
                            <label>Middle Name</label>
                            <input type="text" class="form-control" value="{{ $beneficiary->mname }}" disabled>
                        </div>

                        <div class="form-group col">
                            <label>Last Name</label>
                            <input type="text" class="form-control" value="{{ $beneficiary->lname }}" disabled>
                        </div>

                        <div class="form-group col">
                            <label>Ext Name</label>
                            <input type="text" class="form-control" value="{{ $beneficiary->ext }}" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col">
                            <label>Birthdate</label>
                            <input type="date" class="form-control"
                                   value="{{ $beneficiary->birthdate ? substr($beneficiary->birthdate, 0, 10) : '' }}"
                                   disabled>
                        </div>

                        <div class="form-group col">
                            <label>Sex</label>
                            <input type="text" class="form-control" value="{{ $beneficiary->sex }}" disabled>
                        </div>

                        <div class="form-group col">
                            <label>Relationship</label>
                            <input type="text" class="form-control"
                                   value="{{ $beneficiary->pivot->relationship ?? '' }}"
                                   disabled>
                        </div>

                        <div class="form-group col">
                            <label>Contact #</label>
                            <input type="text" class="form-control" value="{{ $beneficiary->contact_num }}" disabled>
                        </div>
                    </div>
                </fieldset>
            @endforeach

        @else
            <fieldset class="border p-3 mb-2 rounded">
                <legend class="h5 pl-2 pr-2" style="width: auto !important">
                    Beneficiaries
                </legend>
                <p class="mb-0 text-muted">No beneficiaries found.</p>
            </fieldset>
        @endif
 
    </div>

    <div class="card-footer">
        <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="membersHideForm()">
            Close
        </button>
    </div>
</form>
