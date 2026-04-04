<div class="card-header">
    <h3 class="card-title" style="padding-top: 10px;">View Entry Details</h3>
    <button class="btn btn-secondary float-right" style="color: white;" onclick="hideForm()">
        <span class="fas fa-times"></span> Close
    </button>
</div>
<form action="/excel-collection/update/{{ $entries->id }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card-body">
        <fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
            <legend class="h5 pl-2 pr-2" style="width: auto; !important">Collection Details</legend>
            <div class="row">
                <div class="form-group col">
                    <label for="timestamp">Timestamp:</label>
                    <input type="datetime-local" class="form-control" name="timestamp" value="{{ $entries->timestamp }}">
                </div>
                <div class="form-group col">
                    <label for="branch">Branch:</label>
                    <input type="text" class="form-control" name="branch" value="{{ $entries->branch }}" required>
                </div>
                <div class="form-group col">
                    <label for="marketting_agent">MAS:</label>
                    <input type="text" class="form-control" name="marketting_agent" value="{{ $entries->marketting_agent }}" required>
                </div>
                <div class="form-group col">
                    <label for="status">Status:</label>
                    <input type="text" class="form-control" name="status" value="{{ $entries->status }}" required>
                </div>
            </div> <br>
            <div class="row">
                <div class="form-group col">
                    <label for="phmember">phmember:</label>
                    <input type="text" class="form-control" name="phmember" value="{{ $entries->phmember }}" required>
                </div>
                <div class="form-group col">
                    <label for="or_number">OR Number:</label>
                    <input type="text" class="form-control" name="or_number" value="{{ $entries->or_number }}" required>
                </div>
                <div class="form-group col">
                    <label for="or_date">OR Date:</label>
                    <input type="date" class="form-control" name="or_date" value="{{ $entries->or_date }}" required>
                </div>
                <div class="form-group col">
                    <label for="amount_collected">Amount Collected:</label>
                    <input type="text" class="form-control" name="amount_collected" value="{{ $entries->amount_collected }}" required>
                </div>
            </div> <br>
            <div class="row">
                <div class="form-group col">
                    <label for="month_of">Month Of:</label>
                    <input type="text" class="form-control" name="month_of" value="{{ $entries->month_of }}" required>
                </div>
                <div class="form-group col">
                    <label for="nop">NOP:</label>
                    <input type="text" class="form-control" name="nop" value="{{ $entries->nop }}" required>
                </div>
                <div class="form-group col">
                    <label for="date_remitted">Date Remitted:</label>
                    <input type="date" class="form-control" name="date_remitted" value="{{ $entries->date_remitted }}" required>
                </div>
                <div class="form-group col">
                    <label for="dayong_program">Dayong Program:</label>
                    <input type="text" class="form-control" name="dayong_program" value="{{ $entries->dayong_program }}" required>
                </div>
            </div> <br>
            <div class="row">
                <div class="form-group col">
                    <label for="reactivation">Reactivation:</label>
                    <input type="text" class="form-control" name="reactivation" value="{{ $entries->reactivation }}" required>
                </div>
                <div class="form-group col">
                    <label for="transferred">Transferred:</label>
                    <input type="text" class="form-control" name="transferred" value="{{ $entries->transferred }}" required>
                </div>
            </div> <br>
        </fieldset>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary" style="margin-left: 10px;">Submit</button>
        <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="hideForm()">Close</button>
    </div>
</form>