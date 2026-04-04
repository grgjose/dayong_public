<div class="card-header">
    <h3 class="card-title" style="padding-top: 10px;">View Entry Details</h3>
    <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
        <span class="fas fa-times"></span> Close
    </button>
</div>
<form action="/excel-new-sales/update/{{ $newsale->id }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card-body">
        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
            <legend class="h5 pl-2 pr-2" style="width: auto; !important">Collection Details</legend>
            <div class="row">
                <div class="form-group col">
                    <label for="timestamp">Timestamp:</label>
                    <input type="datetime-local" class="form-control" name="timestamp" value="{{ $newsale->timestamp }}" required>
                </div>
                <div class="form-group col">
                    <label for="branch">Branch:</label>
                    <input type="text" class="form-control" name="branch" value="{{ $newsale->branch }}"  required>
                </div>
                <div class="form-group col">
                    <label for="marketting_agent">MAS:</label>
                    <input type="text" class="form-control" name="marketting_agent" value="{{ $newsale->marketting_agent }}"  required>
                </div>
                <div class="form-group col">
                    <label for="status">Status:</label>
                    <input type="text" class="form-control" name="status" value="{{ $newsale->status }}"  required>
                </div>
            </div> <br>
            <div class="row">
                <div class="form-group col">
                    <label for="phmember">phmember:</label>
                    <input type="text" class="form-control" name="phmember" value="{{ $newsale->phmember }}" required>
                </div>
                <div class="form-group col">
                    <label for="address">Address:</label>
                    <input type="text" class="form-control" name="address" value="{{ $newsale->address }}" required>
                </div>
                <div class="form-group col">
                    <label for="civil_status">Civil Status:</label>
                    <input type="text" class="form-control" name="civil_status" value="{{ $newsale->civil_status }}">
                </div>
                <div class="form-group col">
                    <label for="birthdate">Birthdate:</label>
                    <input type="date" class="form-control" name="birthdate" value="{{ $newsale->birthdate }}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" value="{{ $newsale->name }}" required>
                </div>
                <div class="form-group col">
                    <label for="contact_num">Contact Number:</label>
                    <input type="text" class="form-control" name="contact_num" value="{{ $newsale->contact_num }}" required>
                </div>
                <div class="form-group col">
                    <label for="type_of_transaction">Type of Transaction:</label>
                    <input type="text" class="form-control" name="type_of_transaction" value="{{ $newsale->type_of_transaction }}" required>
                </div>
                <div class="form-group col">
                    <label for="with_registration_fee">With Registration Fee:</label>
                    <input type="text" class="form-control" name="with_registration_fee" value="{{ $newsale->with_registration_fee }}" required>
                </div>
                <div class="form-group col">
                    <label for="registration_amount">Registration Amount:</label>
                    <input type="text" class="form-control" name="registration_amount" value="{{ $newsale->registration_amount }}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col">
                    <label for="dayong_program">Dayong Program:</label>
                    <input type="text" class="form-control" name="dayong_program" value="{{ $newsale->dayong_program }}" required>
                </div>
                <div class="form-group col">
                    <label for="application_no">Application Number:</label>
                    <input type="text" class="form-control" name="application_no" value="{{ $newsale->application_no }}">
                </div>
                <div class="form-group col">
                    <label for="or_number">OR Number:</label>
                    <input type="text" class="form-control" name="or_number" value="{{ $newsale->or_number }}" required>
                </div>
                <div class="form-group col">
                    <label for="or_date">OR Date:</label>
                    <input type="date" class="form-control" name="or_date" value="{{ $newsale->or_date }}" required>
                </div>
                <div class="form-group col">
                    <label for="amount_collected">Amount Collected:</label>
                    <input type="text" class="form-control" name="amount_collected" value="{{ $newsale->amount_collected }}" required>
                </div>
            </div> <br>

            @for($i = 0; $i <= 4; $i++)
                <div class="row">
                    <div class="form-group col">
                        <label for="name{{$i}}">Name:</label>
                        <input type="text" class="form-control" name="name{{$i}}" value="{{ $names[$i] }}">
                    </div>
                    <div class="form-group col">
                        <label for="age{{$i}}">Age:</label>
                        <input type="text" class="form-control" name="age{{$i}}" value="{{ $ages[$i] }}">
                    </div>
                    <div class="form-group col">
                        <label for="relationship{{$i}}">Relationship:</label>
                        <input type="text" class="form-control" name="relationship{{$i}}" value="{{ $relationships[$i] }}">
                    </div>
                </div> <br>
            @endfor
        </fieldset>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary" style="margin-left: 10px;">Submit</button>
        <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="hideForm()">Close</button>
    </div>
</form>