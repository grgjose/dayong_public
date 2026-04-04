<div class="card-header">
    <h3 class="card-title" style="padding-top: 10px;">View Entry Details</h3>
    <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
        <span class="fas fa-times"></span> Close
    </button>
</div>
<form>
    <div class="card-body">
        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
            <legend class="h5 pl-2 pr-2" style="width: auto; !important">Collection Details</legend>
            <div class="row">
                <div class="form-group col">
                    <label for="timestamp">Timestamp:</label>
                    <input type="datetime-local" class="form-control" value="{{ $newsale->timestamp }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="branch">Branch:</label>
                    <input type="text" class="form-control" value="{{ $newsale->branch }}"  disabled>
                </div>
                <div class="form-group col">
                    <label for="marketting_agent">MAS:</label>
                    <input type="text" class="form-control" value="{{ $newsale->marketting_agent }}"  disabled>
                </div>
                <div class="form-group col">
                    <label for="status">Status:</label>
                    <input type="text" class="form-control" value="{{ $newsale->status }}"  disabled>
                </div>
            </div> <br>
            <div class="row">
                <div class="form-group col">
                    <label for="phmember">phmember:</label>
                    <input type="text" class="form-control" value="{{ $newsale->phmember }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="address">Address:</label>
                    <input type="text" class="form-control" value="{{ $newsale->address }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="civil_status">Civil Status:</label>
                    <input type="text" class="form-control" value="{{ $newsale->civil_status }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="birthdate">Birthdate:</label>
                    <input type="date" class="form-control" value="{{ $newsale->birthdate }}" disabled>
                </div>
            </div>
            <div class="row">
                <div class="form-group col">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" value="{{ $newsale->name }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="contact_num">Contact Number:</label>
                    <input type="text" class="form-control" value="{{ $newsale->contact_num }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="type_of_transaction">Type of Transaction:</label>
                    <input type="text" class="form-control" value="{{ $newsale->type_of_transaction }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="with_registration_fee">With Registration Fee:</label>
                    <input type="text" class="form-control" value="{{ $newsale->with_registration_fee }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="registration_amount">Registration Amount:</label>
                    <input type="text" class="form-control" value="{{ $newsale->registration_amount }}" disabled>
                </div>
            </div>
            <div class="row">
                <div class="form-group col">
                    <label for="dayong_program">Dayong Program:</label>
                    <input type="text" class="form-control" value="{{ $newsale->dayong_program }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="application_no">Application Number:</label>
                    <input type="text" class="form-control" value="{{ $newsale->application_no }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="or_number">OR Number:</label>
                    <input type="text" class="form-control" value="{{ $newsale->or_number }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="or_date">OR Date:</label>
                    <input type="date" class="form-control" value="{{ $newsale->or_date }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="amount_collected">Amount Collected:</label>
                    <input type="text" class="form-control" value="{{ $newsale->amount_collected }}" disabled>
                </div>
            </div> <br>

            @for($i = 0; $i <= 4; $i++)
                <div class="row">
                    <div class="form-group col">
                        <label for="name{{$i}}">Name:</label>
                        <input type="text" class="form-control" value="{{ $names[$i] }}" disabled>
                    </div>
                    <div class="form-group col">
                        <label for="age{{$i}}">Age:</label>
                        <input type="text" class="form-control" value="{{ $ages[$i] }}" disabled>
                    </div>
                    <div class="form-group col">
                        <label for="relationship{{$i}}">Relationship:</label>
                        <input type="text" class="form-control" value="{{ $relationships[$i] }}" disabled>
                    </div>
                </div> <br>
            @endfor
        </fieldset>

    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="hideForm()">Close</button>
    </div>
</form>