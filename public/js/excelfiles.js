
$(function(){
    var table = $("#excelCollectionTable").DataTable({
      processing: true,
      serverSide: true,
      paging: true,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: true,
      ajax: "excel-collection/retrieve",
      columns: [
        {data: "timestamp", name: "timestamp"},
        {data: "branch", name: "branch"},
        {data: "marketting_agent", name: "marketting_agent"},
        {data: "phmember", name: "phmember"},
        {data: "or_number", name: "or_number"},
        {data: "or_date", name: "or_date"},
        {data: "month_of", name: "month_of"},
        {data: "nop", name: "nop"},
        {data: "dayong_program", name: "dayong_program"},
        {data: "remarks", name: "remarks"},
        {
            data: null,
            name: "actions",
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                var viewBtn = `<button class='btn btn-outline-info view-btn' onclick='viewFunction(${row.id})'> 
                                  <span class='fas fa-eye'></span> 
                              </button>`
                var editBtn = `<button class='btn btn-outline-primary edit-btn' onclick='editFunction(${row.id})'> 
                                  <span class='fas fa-pen'></span> 
                              </button>`
                var delBtn = `<button class='btn btn-outline-danger del-btn' onclick='deleteFunction(${row.id})'> 
                                  <span class='fas fa-trash'></span> 
                              </button>`

                return viewBtn + editBtn + delBtn;
            }
        }
      ]
    });
});

$(function(){
    var table = $("#excelNewSalesTable").DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        ajax: "excel-new-sales/retrieve",
        columns: [
            {data: "timestamp", name: "timestamp"},
            {data: "branch", name: "branch"},
            {data: "marketting_agent", name: "marketting_agent"},
            {data: "phmember", name: "phmember"},
            {data: "birthdate", name: "birthdate"},
            {data: "dayong_program", name: "dayong_program"},
            {data: "application_no", name: "application_no"},
            {data: "or_number", name: "or_number"},
            {data: "or_date", name: "or_date"},
            {data: "remarks", name: "remarks"},
            {
                data: null,
                name: "actions",
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    var viewBtn = `<button class='btn btn-outline-info view-btn' onclick='viewFunction(${row.id})'> 
                                      <span class='fas fa-eye'></span> 
                                  </button>`
                    var editBtn = `<button class='btn btn-outline-primary edit-btn' onclick='editFunction(${row.id})'> 
                                      <span class='fas fa-pen'></span> 
                                  </button>`
                    var delBtn = `<button class='btn btn-outline-danger del-btn' onclick='deleteFunction(${row.id})'> 
                                      <span class='fas fa-trash'></span> 
                                  </button>`
    
                    return viewBtn + editBtn + delBtn;
                }
            }
        ]
    });
});