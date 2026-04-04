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
                    <label for="branch_id">Branch:</label>
                    <select class="form-control chosen-select" id="branch_id" name="branch_id" disabled>
                        <option value="{{ $branches->id; }}">{{ $branches->branch; }}</option>
                    </select>
                </div>
                <div class="form-group col">
                    <label for="member_id">Agent:</label>
                    <select class="form-control chosen-select" id="marketting_agent" name="marketting_agent" disabled>
                        <option value="{{ $agent->id; }}">{{ $agent->fname.' '.$agent->mname.' '.$agent->lname; }}</option>
                    </select>
                </div>
                <div class="form-group col">
                    <label for="member_id">Member:</label>
                    <select class="form-control chosen-select" id="member_id" name="member_id" disabled>
                        <option value="{{ $members->id; }}">{{ $members->fname.' '.$members->mname.' '.$members->lname; }}</option>
                    </select>
                </div>
            </div> <br>
            <div class="row">
                <div class="form-group col">
                    <label for="branch_id">Program:</label>
                    <select class="form-control chosen-select" id="program_id" name="program_id" disabled>
                        <option value="{{ $programs->id; }}">{{ $programs->code; }}</option>
                    </select>
                </div>
                <div class="form-group col">
                    <label for="or_number">OR:</label>
                    <input type="text" class="form-control" id="or_number" name="or_number" value="{{ $entries->or_number; }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="amount">Amount:</label>
                    <input type="number" class="form-control" id="amount" name="amount" value="{{ $entries->amount; }}" disabled>
                </div>
            </div> <br>
            <div class="row">
                <div class="form-group col">
                    <label for="times">How many Payments:</label>
                    <input type="number" class="form-control" id="times" name="number_of_payment" value="{{ $entries->number_of_payment; }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="month_from">From (Month):</label>
                    <input type="month" class="form-control" id="times" name="month_from" value="{{ $entries->month_from; }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="month_to">To (Month):</label>
                    <input type="month" class="form-control" id="times" name="month_to" value="{{ $entries->month_to; }}" disabled>
                </div>
            </div> <br>
            <div class="row">
                <div class="form-group col">
                    <label for="incentive">Incentive 1-50 (%):</label>
                    <input type="number" class="form-control" id="incentive" name="incentive"
                     onkeyup="enforceMinMax(this)" min="1" max="50" value="{{ $entries->incentives; }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="remarks">Remarks:</label>
                    <input type="text" class="form-control" id="remarks" name="remarks" value="{{ $entries->remarks; }}" disabled>
                </div>
            </div> <br>
        </fieldset>

    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="hideForm()">Close</button>
    </div>
</form>