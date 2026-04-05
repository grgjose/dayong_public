<!-- MAIN CONTENT -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">

				<!-- TABLE SECTION -->
				<div class="card card-info" id="table">
					<div class="card-header">
						<h2 class="card-title" style="padding-top: 10px;">Collection Table</h2>
						@if($my_user->usertype != 3)
							<button class="btn btn-success float-right" onclick="dateEntryShowForm()">
								<span class="fas fa-plus"></span> Add Collection
							</button>
							<button class="btn btn-secondary float-right mr-3" data-toggle="modal" data-target="#ImportModal">
								<span class="fas fa-upload"></span> Import From Excel Collection
							</button>
						@endif
					</div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>There were some problems with your input:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

					<div class="card-body">
						<table id="normalTable" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Branch</th>
									<th>Agent</th>
									<th>Member</th>
									<th>OR</th>
									<th>OR Date</th>
									<th>Amount</th>
									<th>NOP</th>
									<th>Program</th>
									<th>Is Reactivated</th>
									<th>Is Transferred</th>
									<th>Remarks</th>
									<th>Action</th>
								</tr>
							</thead>

							<tbody>
								@foreach ($entries as $entry)   
									<tr>
										<td><input type="checkbox" /></td>

										<td>
											@foreach($branches as $branch)
												@if($branch->id == $entry->branch_id)
													{{ $branch->branch; }}
													@break
												@endif
											@endforeach
										</td>

										<td>
											@foreach($users as $user)
												@if($user->id == $entry->agent_id)
													{{ $user->fname.' '.$user->lname; }}
													@break
												@endif
											@endforeach
										</td>

										<td>
											@foreach($members as $member)
												@if($member->id == $entry->member_id)
													{{ $member->fname.' '.$member->lname; }}
													@break
												@endif
											@endforeach
										</td>

										<td>{{ $entry->or_number; }}</td>
										<td>{{ date('Y-m-d', strtotime($entry->or_date)) }}</td>
										<td>{{ $entry->amount; }}</td>
										<td>{{ $entry->number_of_payment; }}</td>

										<td>
											@foreach($programs as $program)
												@if($program->id == $entry->program_id)
													{{ $program->code; }}
													@break
												@endif
											@endforeach
										</td>

										<td>{{ ($entry->is_reactivated == 1)?'YES':'NO'; }}</td>
										<td>{{ ($entry->is_transferred)?'YES':'NO'; }}</td>
										<td>{{ $entry->remarks; }}</td>

										<td>
											<button class="btn btn-outline-info class-with-tooltip"
												data-toggle="modal"
												data-target="#ViewModal"
												onclick="dataEntryViewFunction({{ $entry->id; }})"
												data-title="View Details">
												<span class="fas fa-eye"></span>
											</button>

											@if($my_user->usertype == 1)
												<button class="btn btn-outline-primary"
													data-toggle="modal"
													data-target="#EditModal"
													onclick="dataEntryEditFunction({{ $entry->id; }})"
													data-title="Edit Details">
													<span class="fas fa-pen"></span>
												</button>

												<button class="btn btn-outline-danger"
													data-toggle="modal"
													data-target="#DeleteModal"
													onclick="dataEntryDeleteFunction({{ $entry->id; }})"
													data-title="Delete">
													<span class="fas fa-trash"></span>
												</button>
											@endif
											
											<button class="btn btn-outline-success"
												data-toggle="modal"
												data-target="#RemitModal"
												onclick="dataEntryPushRemittance({{ $entry->id; }})"
												data-title="Remit">
												<span class="fas fa-money-bill-wave-alt"></span>
											</button>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>

				<!-- ADD ENTRY SECTION -->
				<div class="card card-primary" id="form" style="display: none;">
					<div class="card-header">
						<h3 class="card-title" style="padding-top: 10px;">New Collections Form</h3>
						<button class="btn btn-secondary float-right"
							style="color: white;"
							onclick="dateEntryHideForm()">
							<span class="fas fa-times"></span> Cancel
						</button>
					</div>

					<form id="form_tag" action="/entries/store" method="post">
						@csrf
						<div class="card-body">

							<fieldset class="border p-3 mb-2 rounded" style="--bs-border-opacity: .5;">
								<legend class="h5 pl-2 pr-2" style="width: auto; !important">Collection Details</legend>
								<div id="or-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>
                                <div id="amount-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>
								<div id="app-no-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>

								<div class="row">
									<div class="form-group col">
										<label>Member:</label>
										<select class="form-control chosen-select"
											id="member_id"
											name="member_id"
											onchange="dateEntryMemberChanged()">
											@foreach($members as $member)
												<option value="{{ $member->id; }}"
                                                        data-branch="{{ $member->branch_id }}"
                                                        data-agent="{{ $member->agent_id }}">
													{{ $member->fname.' '.$member->mname.' '.$member->lname; }}
												</option>
											@endforeach
										</select>
									</div>

                                    <input type="hidden" id="branch_id_hidden" name="branch_id">
									<div class="form-group col">
										<label>Branch:</label>
										<select class="form-control chosen-select" id="branch_id">
											@foreach($branches as $branch)
												<option value="{{ $branch->id; }}">{{ $branch->branch; }}</option>
											@endforeach
										</select>
									</div>

									<div class="form-group col">
										<label>Agent:</label>
										<select class="form-control chosen-select" id="agent_id" name="agent_id">
											@foreach($users as $user)
												<option value="{{ $user->id; }}">
													{{ $user->fname.' '.$user->mname.' '.$user->lname; }}
												</option>
											@endforeach
										</select>
									</div>
								</div>

								<br>

								<div class="row">
									<div class="form-group col">
										<label>Program:</label>
										<select class="form-control chosen-select"
											id="program_id"
											name="program_id"
											onchange="programChanged()">
											@foreach($programs as $program)
												<option value="{{ $program->id; }}">{{ $program->code; }}</option>
											@endforeach
										</select>
									</div>

									<div class="form-group col">
										<label>OR:</label>
										<input type="text" class="form-control" id="or_number" name="or_number" required>
									</div>

									<div class="form-group col">
										<label>Amount:</label>
										<input type="number" class="form-control" id="amount" name="amount" required>
									</div>
								</div>

								<br>

								<div class="row">
									<div class="form-group col">
										<label>How many Payments:</label>
										<input type="number" class="form-control" id="times" name="number_of_payment" required>
									</div>

									<div class="form-group col">
										<label>From (Month):</label>
										<input type="month" class="form-control" id="month_from" name="month_from" required>
									</div>

									<div class="form-group col">
										<label>To (Month):</label>
										<input type="month" class="form-control" id="month_to" name="month_to" required>
									</div>
								</div>

								<br>

								<div class="row">
									<div class="form-group col">
										<label>Incentive:</label>
										<input type="number" class="form-control" id="incentives" name="incentives" step="0.01">

									</div>

									<div class="form-group col">
										<div class="custom-control custom-switch custom-switch-on-warning"
											style="padding-left: 3.25rem; padding-top: 2.25rem;">
											<input type="checkbox" class="custom-control-input" id="reactivated" name="reactivated">
											<label for="reactivated" class="custom-control-label">Is Reactivated</label>
										</div>
									</div>

									<div class="form-group col">
										<div class="custom-control custom-switch custom-switch-on-success"
											style="padding-left: 3.25rem; padding-top: 2.25rem;">
											<input type="checkbox" class="custom-control-input" id="transferred" name="transferred">
											<label for="transferred" class="custom-control-label">Is Transferred</label>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="form-group col">
										<label>Remarks:</label>
										<input type="text" class="form-control" id="remarks" name="remarks">
									</div>
								</div>

								<br>

							</fieldset>
						</div>

						<div class="card-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
							<button type="button" class="btn btn-secondary" onclick="dateEntryHideForm()">Cancel</button>
						</div>
					</form>
				</div>

                <!-- VIEW SECTION -->
                <div class="card card-primary" id="view" style="display: none;">
                </div>

			</div>
		</div>
	</div>
</section>

<!-- IMPORT MODAL -->
<div class="modal fade" id="ImportModal" tabindex="-1" role="dialog" aria-labelledby="ImportModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uploadForm" action="/entries/import" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label for="data_count">Data Count (The higher the slower)</label>
                            <select class="form-control chosen-select" id="data_count" name="data_count" value="10">
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                    </div> <br>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<span style="display:none;" id="temp"></span>
