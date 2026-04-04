<div class="card-header">
    <h3 class="card-title" style="padding-top: 10px;">Edit Member Details</h3>
    <button class="btn btn-secondary float-right" style="color: white;" onclick="membersHideForm()">
        <span class="fas fa-times"></span> Close
    </button>
</div>

<form action="/members/update/{{ $id }}" method="POST">
    @method('PUT')
    @csrf

    <div class="card-body">

        {{-- ===================== PERSONAL INFORMATION ===================== --}}
        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
            <legend class="h5 pl-2 pr-2" style="width: auto !important">Personal Information</legend>

            <div class="row">
                <div class="form-group col">
                    <label for="fname">First Name</label>
                    <input type="text" class="form-control text-uppercase" id="fname" name="fname"
                           placeholder="ENTER FIRST NAME"
                           value="{{ old('fname', $member->fname) }}">
                </div>

                <div class="form-group col">
                    <label for="mname">Middle Name</label>
                    <input type="text" class="form-control text-uppercase" id="mname" name="mname"
                           placeholder="ENTER MIDDLE NAME"
                           value="{{ old('mname', $member->mname) }}">
                </div>

                <div class="form-group col">
                    <label for="lname">Last Name</label>
                    <input type="text" class="form-control text-uppercase" id="lname" name="lname"
                           placeholder="ENTER LAST NAME"
                           value="{{ old('lname', $member->lname) }}">
                </div>

                <div class="form-group col">
                    <label for="ext">Ext Name</label>
                    <input type="text" class="form-control text-uppercase" id="ext" name="ext"
                           placeholder="ENTER EXT. NAME (JR, SR, ETC.)"
                           value="{{ old('ext', $member->ext) }}">
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate"
                           value="{{ old('birthdate', $member->birthdate ? substr($member->birthdate, 0, 10) : '') }}">
                </div>

                <div class="form-group col">
                    <label>Marketing Agent</label>
                    <select class="form-control chosen-select" id="agent_id" name="agent_id" disabled>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('agent_id', $member->agent_id) == $user->id ? 'selected' : '' }}>
                                {{ strtoupper($user->fname.' '.$user->mname.' '.$user->lname) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col">
                    <label for="sex">Sex</label>
                    <select class="form-control chosen-select" id="sex" name="sex">
                        <option value="MALE" {{ old('sex', $member->sex) == 'MALE' ? 'selected' : '' }}>MALE</option>
                        <option value="FEMALE" {{ old('sex', $member->sex) == 'FEMALE' ? 'selected' : '' }}>FEMALE</option>
                    </select>
                </div>

                <div class="form-group col">
                    <label for="birthplace">Place of Birth</label>
                    <input type="text" class="form-control text-uppercase" id="birthplace" name="birthplace"
                           placeholder="ENTER PLACE OF BIRTH"
                           value="{{ old('birthplace', $member->birthplace) }}">
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="citizenship">Citizenship</label>
                    <input type="text" class="form-control text-uppercase" id="citizenship" name="citizenship"
                           placeholder="ENTER CITIZENSHIP (FILIPINO, AMERICAN, ETC.)"
                           value="{{ old('citizenship', $member->citizenship) }}">
                </div>

                <div class="form-group col">
                    <label for="civil_status">Civil Status</label>
                    <select class="form-control chosen-select" id="civil_status" name="civil_status">
                        <option value="SINGLE" {{ old('civil_status', $member->civil_status) == 'SINGLE' ? 'selected' : '' }}>Single</option>
                        <option value="MARRIED" {{ old('civil_status', $member->civil_status) == 'MARRIED' ? 'selected' : '' }}>Married</option>
                    </select>
                </div>

                <div class="form-group col">
                    <label for="contact_num">Contact #</label>
                    <input type="text" class="form-control" id="contact_num" name="contact_num"
                           placeholder="ENTER CONTACT NUMBER (+63)"
                           value="{{ old('contact_num', $member->contact_num) }}">
                </div>

                <div class="form-group col">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="ENTER EMAIL (OPTIONAL)"
                           value="{{ old('email', $member->email) }}">
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="address">Address</label>
                    <input type="text" class="form-control text-uppercase" id="address" name="address"
                           placeholder="ENTER CURRENT ADDRESS"
                           value="{{ old('address', $member->address) }}">
                </div>
            </div>
        </fieldset>

        {{-- ===================== CLAIMANT INFORMATION ===================== --}}
        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
            <legend class="h5 pl-2 pr-2" style="width: auto !important">Claimant's Personal Information</legend>

            <div class="row">
                <div class="form-group col">
                    <label for="fname_c">First Name</label>
                    <input type="text" class="form-control text-uppercase" id="fname_c" name="fname_c"
                           value="{{ old('fname_c', $claimant->fname ?? '') }}">
                </div>

                <div class="form-group col">
                    <label for="mname_c">Middle Name</label>
                    <input type="text" class="form-control text-uppercase" id="mname_c" name="mname_c"
                           value="{{ old('mname_c', $claimant->mname ?? '') }}">
                </div>

                <div class="form-group col">
                    <label for="lname_c">Last Name</label>
                    <input type="text" class="form-control text-uppercase" id="lname_c" name="lname_c"
                           value="{{ old('lname_c', $claimant->lname ?? '') }}">
                </div>

                <div class="form-group col">
                    <label for="ext_c">Ext Name</label>
                    <input type="text" class="form-control text-uppercase" id="ext_c" name="ext_c"
                           value="{{ old('ext_c', $claimant->ext ?? '') }}">
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="birthdate_c">Birthdate</label>
                    <input type="date" class="form-control" id="birthdate_c" name="birthdate_c"
                           value="{{ old('birthdate_c', isset($claimant->birthdate) ? substr($claimant->birthdate, 0, 10) : '') }}">
                </div>

                <div class="form-group col">
                    <label for="sex_c">Sex</label>
                    <select class="form-control chosen-select" id="sex_c" name="sex_c">
                        <option value="MALE" {{ old('sex_c', $claimant->sex ?? '') == 'MALE' ? 'selected' : '' }}>MALE</option>
                        <option value="FEMALE" {{ old('sex_c', $claimant->sex ?? '') == 'FEMALE' ? 'selected' : '' }}>FEMALE</option>
                    </select>
                </div>

                <div class="form-group col">
                    <label for="contact_num_c">Contact #</label>
                    <input type="text" class="form-control" id="contact_num_c" name="contact_num_c"
                           value="{{ old('contact_num_c', $claimant->contact_num ?? '') }}">
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

                    <input type="hidden" name="beneficiaries[{{ $index }}][id]" value="{{ $beneficiary->id }}">

                    <div class="row">
                        <div class="form-group col">
                            <label>First Name</label>
                            <input type="text" class="form-control text-uppercase"
                                   name="beneficiaries[{{ $index }}][fname]"
                                   value="{{ old("beneficiaries.$index.fname", $beneficiary->fname) }}">
                        </div>

                        <div class="form-group col">
                            <label>Middle Name</label>
                            <input type="text" class="form-control text-uppercase"
                                   name="beneficiaries[{{ $index }}][mname]"
                                   value="{{ old("beneficiaries.$index.mname", $beneficiary->mname) }}">
                        </div>

                        <div class="form-group col">
                            <label>Last Name</label>
                            <input type="text" class="form-control text-uppercase"
                                   name="beneficiaries[{{ $index }}][lname]"
                                   value="{{ old("beneficiaries.$index.lname", $beneficiary->lname) }}">
                        </div>

                        <div class="form-group col">
                            <label>Ext Name</label>
                            <input type="text" class="form-control text-uppercase"
                                   name="beneficiaries[{{ $index }}][ext]"
                                   value="{{ old("beneficiaries.$index.ext", $beneficiary->ext) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col">
                            <label>Birthdate</label>
                            <input type="date" class="form-control"
                                   name="beneficiaries[{{ $index }}][birthdate]"
                                   value="{{ old("beneficiaries.$index.birthdate", $beneficiary->birthdate ? substr($beneficiary->birthdate, 0, 10) : '') }}">
                        </div>

                        <div class="form-group col">
                            <label>Sex</label>
                            <select class="form-control chosen-select"
                                    name="beneficiaries[{{ $index }}][sex]">
                                <option value="MALE" {{ old("beneficiaries.$index.sex", $beneficiary->sex) == 'MALE' ? 'selected' : '' }}>MALE</option>
                                <option value="FEMALE" {{ old("beneficiaries.$index.sex", $beneficiary->sex) == 'FEMALE' ? 'selected' : '' }}>FEMALE</option>
                            </select>
                        </div>

                        <div class="form-group col">
                            <label>Relationship</label>
                            <input type="text" class="form-control text-uppercase"
                                   name="beneficiaries[{{ $index }}][relationship]"
                                   value="{{ old("beneficiaries.$index.relationship", $beneficiary->pivot->relationship ?? '') }}">
                        </div>

                        <div class="form-group col">
                            <label>Contact #</label>
                            <input type="text" class="form-control"
                                   name="beneficiaries[{{ $index }}][contact_num]"
                                   value="{{ old("beneficiaries.$index.contact_num", $beneficiary->contact_num) }}">
                        </div>
                    </div>
                </fieldset>
            @endforeach
        @else
            <fieldset class="border p-3 mb-2 rounded">
                <legend class="h5 pl-2 pr-2" style="width: auto !important">Beneficiaries</legend>
                <p class="mb-0 text-muted">No beneficiaries found.</p>
            </fieldset>
        @endif

    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="membersHideForm()">Cancel</button>
    </div>
</form>
