$(document).ready(function () {

    // Members Upload Form On Submit 
    $("#uploadForm_ExcelMembers").on("submit", function (e) {

        if($('#sheets').val() == "" || $('#sheets').val() == null || $('#sheets').val() == "undefined"){
            e.preventDefault();

            var formData = new FormData(this);
    
            $.ajax({
                url: "/members/loadSheets",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#response").html("");
                    response.forEach((sheet) => {
                        let newOption = $('<option>', { value: sheet, text: sheet }); 
                        if(sheet != "QUOTA"){ $('#sheets').append(newOption).trigger("chosen:updated"); }
                    });
                },
                error: function (xhr) {
                    $("#response").html("<p>No File has been Selected</p>");
                }
            });

            $("#uploadForm_ExcelMembers").attr("action", '/members/upload');
            $("#uploadButton_ExcelMembers").removeAttr('disabled');
        } else {
            
        }
    });

    // Excel New Sales Upload Form On Submit
    $("#uploadForm_excelNewSales").on("submit", function (e) {

        if($('#sheets').val() == "" || $('#sheets').val() == null || $('#sheets').val() == "undefined"){
            e.preventDefault();

            var formData = new FormData(this);
    
            $.ajax({
                url: "/excel-new-sales/loadSheets",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#response").html("");
                    
                    response.forEach((sheet) => {
                        let newOption = $('<option>', { value: sheet, text: sheet }); 
                        if(sheet.includes("NS")){ $('#sheets').append(newOption).trigger("chosen:updated"); }
                    });

                },
                error: function (xhr) {
                    $("#response").html("<p>No File has been Selected</p>");
                }
            });

            $("#uploadForm_excelNewSales").attr("action", '/excel-new-sales/upload');
            $("#uploadButton_excelNewSales").removeAttr('disabled');
        } else {
            e.preventDefault();

            $("#response").html("<p>Uploading... please wait...</p>");
            var formData = new FormData(this);
    
            $.ajax({
                url: "/excel-new-sales/upload",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#response").html("<p>Uploaded Successfully</p>");
                    location.reload();
                },
                error: function (xhr) {
                    $("#response").html("<p>No File has been Selected</p>");
                }
            });

            $("#uploadForm_excelNewSales").attr("action", '/excel-new-sales/upload');
            $("#uploadButton_excelNewSales").removeAttr('disabled');
        }
    });

    // Excel Collection Upload Form On Submit
    $("#uploadForm_excelCollection").on("submit", function (e) {

        if($('#sheets').val() == "" || $('#sheets').val() == null || $('#sheets').val() == "undefined"){
            e.preventDefault();

            var formData = new FormData(this);
    
            $.ajax({
                url: "/excel-collection/loadSheets",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#response").html("");
                    
                    response.forEach((sheet) => {
                        let newOption = $('<option>', { value: sheet, text: sheet }); 
                        if(sheet.includes("COL")){ $('#sheets').append(newOption).trigger("chosen:updated"); }
                    });

                },
                error: function (xhr) {
                    $("#response").html("<p>No File has been Selected</p>");
                }
            });

            $("#uploadForm_excelCollection").attr("action", '/excel-collection/upload');
            $("#uploadButton_excelCollection").removeAttr('disabled');
        } else {
            e.preventDefault();

            $("#response").html("<p>Uploading... please wait...</p>");
            var formData = new FormData(this);
    
            $.ajax({
                url: "/excel-collection/upload",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#response").html("<p>Uploaded Successfully</p>");
                    location.reload();
                },
                error: function (xhr) {
                    $("#response").html("<p>No File has been Selected</p>");
                }
            });

            $("#uploadForm_excelCollection").attr("action", '/excel-collection/upload');
            $("#uploadButton_excelCollection").removeAttr('disabled');
        }
    });


    // Collection Upload Form On Submit
    /*
    $("#uploadForm_collection").on("submit", function (e) {

            e.preventDefault();

            $("#response").html("<p>Uploading... please wait...</p>");
            var formData = new FormData(this);
    
            $.ajax({
                url: "/entries/import",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#response").html("<p>Uploaded Successfully</p>");
                    //location.reload();
                },
                error: function (xhr) {
                    $("#response").html("<p>No File has been Selected</p>");
                }
            });

            $("#uploadButton_collection").removeAttr('disabled');

    });
    */

});