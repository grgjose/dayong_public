<!-- Custom Style Component -->
<style>
    
    .modal { 
        overflow-y:auto !important; 
    }
    
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    
    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }

</style>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                
                <!-- TABLE SECTION -->  
                <div class="card card-info" id="table">
                    <div class="card-header">
                        <h2 class="card-title" style="padding-top: 10px;">Collection Excel List</h2>
                        @if($my_user->usertype == 1)
                            <button class="btn btn-secondary float-right mr-3" onclick="importExcel()" data-toggle="modal" data-target="#ImportModal">
                                <span class="fas fa-upload"></span> Import Excel
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <table id="excelCollectionTable" class="table table-sm table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">Timestamp</th>
                                    <th style="width: 4%;">Branch</th>
                                    <th style="width: 8%;">MAS</th>
                                    <th style="width: 15%;">PH/MEMBER</th>
                                    <th style="width: 3%;">OR#</th>
                                    <th style="width: 8%;">OR Date</th>
                                    <th style="width: 8%;">MonthOf</th>
                                    <th style="width: 2%;">NOP</th>
                                    <th style="width: 3%;">Program</th>
                                    <th style="width: 24%;">Remarks</th>
                                    <th style="width: 15%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- VIEW ENTRY SECTION -->
                <div class="card card-primary" id="view" style="display: none;">
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Import Modal -->
<div class="modal fade" id="ImportModal" tabindex="-1" role="dialog" aria-labelledby="ImportModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uploadForm_excelCollection" action="/excel-collection/loadSheets" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label class="form-label">Database File:</label>
                            <div id="response"></div>
                            <div class="input-group">
                                <div class="custom-file">
                                  <input type="file" class="custom-file-input" id="upload_file" name="upload_file">
                                  <label class="custom-file-label" for="upload_file">Choose file</label>
                                </div>
                            </div> <br>

                            <div class="form-group col">
                                <label for="sheets" class="form-label">Sheets</label>
                                <select class="form-control chosen-select" id="sheets" name="sheetName">
                                </select>
                            </div>
                        </div>
                    </div> <br>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" id="uploadButton_excelCollection" class="btn btn-success" disabled>Upload</button>
                    <button type="submit" class="btn btn-warning">Load Sheets</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    
    function showForm()
    {
        $("#table").attr("style", "display: none;");
        $("#form").removeAttr("style");
    }

    function hideForm()
    {
        $("#form").attr("style", "display: none;");
        $("#view").attr("style", "display: none;");
        $("#view").html("");
        $("#table").removeAttr("style");
    }

    function viewFunction(id)
    {
        $("#view").load('/excel-collection/view/'+id);
        $("#table").attr("style", "display: none;");
        $("#view").removeAttr("style");
    }

    function editFunction(id)
    {
        $("#view").load('/excel-collection/edit/'+id);
        $("#table").attr("style", "display: none;");
        $("#view").removeAttr("style");
    }

    function deleteFunction(id)
    {
        var fname = $("#"+id+"_fname").html();
        var lname = $("#"+id+"_lname").html();
        $("#del_fname").html(fname);
        $("#del_lname").html(lname);
        $("#delete_id").val(id);
    }

    function printFunction(id){
        window.open('/members/print/'+id);
        //$("#soa_printer").attr('action','/members/print/'+id);
        //$("#soa_printer").submit();
    }

</script>