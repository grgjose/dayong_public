<style> .modal { overflow-y:auto !important; }</style>
  
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-info">
            <div class="card-header">
              <h2 class="card-title pb-2" style="padding-top: 10px;">Generate Report</h2>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              
                    <form action="/reports/generate" method="POST">
                        @csrf
                        <div class="row"> 
                            <div class="form-group col">
                                <label class="form-label">Type of Report</label>
                                <select class="form-select chosen-select" name="type">
                                    <option value="daily">Daily Report</option>
                                    <option value="weekly">Weekly Report</option>
                                    <option value="monthly">Monthly Report</option>
                                </select>
                            </div>
                            <div class="form-group col">
                                <label class="form-label">Branch Name</label>
                                <select class="form-select chosen-select" name="branch">
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->id; }}">{{ $branch->branch; }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col">
                                <label>Date From:</label>
                                <input type="date" name="date_from" class="form-control">
                            </div>
                            <div class="form-group col">
                                <label>Date To:</label>
                                <input type="date" name="date_to" class="form-control">
                            </div>
                            <div class="form-group col pt-1 pl-4">
                                <input type="submit" class="btn btn-info mt-4" value="Generate Report" />
                            </div>
                            <br> <br>
                        </div>
                        <label class="form-group col-12">Columns to Display: </label>
                        <div class="row ml-3">
                            
                            <div class="form-group col ml-2">
                                <input type="checkbox" class="form-check-input pr-5" name="amount" checked/>
                                <label class="form-check-label">Amount</label>
                            </div>
                            <div class="form-group col ml-2">
                                <input type="checkbox" class="form-check-input pr-5" name="incentives" checked/>
                                <label class="form-check-label">Incentives</label>
                            </div>
                            <div class="form-group col ml-2">
                                <input type="checkbox" class="form-check-input pr-5" name="cost" checked/>
                                <label class="form-check-label">Cost</label>
                            </div>
                            <div class="form-group col ml-2">
                                <input type="checkbox" class="form-check-input pr-5" name="collector" checked/>
                                <label class="form-check-label">Name of collector</label>
                            </div>
                        </div>
                        <div class="row ml-3">
                            <div class="form-group col ml-2">
                                <input type="checkbox" class="form-check-input pr-5" name="time_collected" checked/>
                                <label class="form-check-label">Time Collected</label>
                            </div>
                            <div class="form-group col ml-2">
                                <input type="checkbox" class="form-check-input pr-5" name="time_remitted" checked/>
                                <label class="form-check-label">Time Remitted</label>
                            </div>
                            <div class="form-group col ml-2">
                                <input type="checkbox" class="form-check-input pr-5" name="status" checked/>
                                <label class="form-check-label">Status</label>
                            </div>
                            <div class="form-group col ml-2">
                                <input type="checkbox" class="form-check-input pr-5" name="or_number" checked/>
                                <label class="form-check-label">OR Numbers</label>
                            </div>
                        </div>
                    </form>
                    
                    <br> <br> <br>

                    <table id="normalTable" class="table table-bordered table-hover">
                        <thead>
                            <th>#</th>
                            <th>Filename</th>
                            <th>Date Generated</th>
                            <th>Generated By</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td></td>
                                <td>{{ $report->filename; }}</td>
                                <td>{{ $report->created_at; }}</td>
                                @foreach($users as $user)
                                    @if($user->id == $report->user_id)
                                        <td>{{ $user->fname.' '.$user->mname.' '.$user->lname; }}</td>
                                    @endif
                                @endforeach
                                <td>
                                    <button type="button" class="btn btn-outline-info" onclick="downloadFunction('')">Download</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->



<!-- Add Modal -->
<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="AddModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Entry</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/fields/store" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label for="fname">Field Name</label>
                            <input type="text" name="field" placeholder="Filed Name" class="form-control" value="" required>
                        </div>
                    </div> <br>

                    <div class="form-row">
                        <div id="myDiv" class="col">
                            <label for="fname">Options:</label>
                            <input id="option-field" type="text" name="option[]" style="margin-bottom:10px;" placeholder="Field Values (Optional)" class="form-control option-fields">
                        </div>
                    </div> <br>
                    <div class="form-row">
                        <button type="button" class="btn btn-outline-info" onclick="add_field()">
                            <span class="fas fa-plus"></span>
                        </button>
                        <button type="button"  class="btn btn-outline-secondary" onclick="remove_field()">
                            <span class="fas fa-minus"></span>
                        </button>
                    </div> <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Save" />
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="EditModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Field Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" action="/fields/update" method="POST" >
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label for="fname">Field Name:</label>
                            <input type="hidden" class="form-control" id="edit_field_id" name="field_id" value="" required>
                            <input type="text" class="form-control" id="edit_field" placeholder="Field Name" name="field" required>
                        </div>
                    </div> <br>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Plus Modal -->
<div class="modal fade" id="PlusModal" tabindex="-1" role="dialog" aria-labelledby="PlusModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Option</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" action="/fields/plus" method="POST" >
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label for="fname">Option:</label>
                            <input type="hidden" class="form-control" id="plus_field_id" name="field_id" value="" required>
                            <input type="text" class="form-control" placeholder="Add Option" name="option" required>
                        </div>
                    </div> <br>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Minus Modal -->
<div class="modal fade" id="MinusModal" tabindex="-1" role="dialog" aria-labelledby="MinusModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Option</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" action="/fields/minus" method="POST" >
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label for="my_option">Option:</label>
                            <input type="hidden" class="form-control" name="field_id" value="" required>
                            <select id="my_option" name="option" class="form-control" required>
                            </select>
                        </div>
                    </div> <br>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form style="display: none;" id="deleteForm" action="/fields/destroy" method="POST">
    @csrf
    <input id="delete_id" type="hidden" name="id" value="" />
</form>

<form style="display: none;" id="downloadForm" action="/reports/download" method="POST">
    @csrf
    <input id="download_filename" type="hidden" name="filename" value="" />
</form>

<script>

    function downloadFunction(filename){
        $("#download_filename").val(filename);
        $("#downloadForm").submit();
    }

</script>