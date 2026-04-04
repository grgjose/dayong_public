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
                    <input type="datetime-local" class="form-control" id="timestamp" value="{{ $entries->timestamp }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="branch">Branch:</label>
                    <input type="text" class="form-control" value="{{ $entries->branch }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="marketting_agent">MAS:</label>
                    <input type="text" class="form-control" value="{{ $entries->marketting_agent }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="status">Status:</label>
                    <input type="text" class="form-control" value="{{ $entries->status }}" disabled>
                </div>
            </div> <br>
            <div class="row">
                <div class="form-group col">
                    <label for="phmember">phmember:</label>
                    <input type="text" class="form-control" value="{{ $entries->phmember }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="or_number">OR Number:</label>
                    <input type="text" class="form-control" value="{{ $entries->or_number }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="or_date">OR Date:</label>
                    <input type="date" class="form-control" value="{{ $entries->or_date }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="amount_collected">Amount Collected:</label>
                    <input type="text" class="form-control" value="{{ $entries->amount_collected }}" disabled>
                </div>
            </div> <br>
            <div class="row">
                <div class="form-group col">
                    <label for="month_of">Month Of:</label>
                    <input type="text" class="form-control" value="{{ $entries->month_of }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="nop">NOP:</label>
                    <input type="text" class="form-control" value="{{ $entries->nop }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="date_remitted">Date Remitted:</label>
                    <input type="date" class="form-control" value="{{ $entries->date_remitted }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="dayong_program">Dayong Program:</label>
                    <input type="text" class="form-control" value="{{ $entries->dayong_program }}" disabled>
                </div>
            </div> <br>
            <div class="row">
                <div class="form-group col">
                    <label for="reactivation">Reactivation:</label>
                    <input type="text" class="form-control"  value="{{ $entries->reactivation }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="transferred">Transferred:</label>
                    <input type="text" class="form-control" value="{{ $entries->transferred }}" disabled>
                </div>
            </div> <br>
        </fieldset>

    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-secondary" style="margin-left: 10px;" onclick="hideForm()">Close</button>
    </div>
</form>