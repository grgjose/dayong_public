
    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{asset('admin_lte/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{asset('admin_lte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- overlayScrollbars -->
    <script src="{{asset('admin_lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('admin_lte/dist/js/adminlte.js')}}"></script>

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="{{asset('admin_lte/plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
    <script src="{{asset('admin_lte/plugins/raphael/raphael.min.js')}}"></script>
    <script src="{{asset('admin_lte/plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
    <script src="{{asset('admin_lte/plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>
    <!-- ChartJS -->
    <script src="{{asset('admin_lte/plugins/chart.js/Chart.min.js')}}"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('admin_lte/dist/js/demo.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{asset('admin_lte/dist/js/pages/dashboard2.js')}}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('admin_lte/plugins/datatables/jquery.dataTables.min.js'); }}"></script>
    <script src="{{ asset('admin_lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); }}"></script>
    <script src="{{ asset('admin_lte/plugins/datatables-responsive/js/dataTables.responsive.min.js'); }}"></script>
    <script src="{{ asset('admin_lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js'); }}"></script>
    <script src="{{ asset('admin_lte/plugins/datatables-buttons/js/dataTables.buttons.min.js'); }}"></script>
    <script src="{{ asset('admin_lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js'); }}"></script>
    <script src="{{ asset('admin_lte/plugins/jszip/jszip.min.js'); }}"></script>
    <script src="{{ asset('admin_lte/plugins/pdfmake/pdfmake.min.js'); }}"></script>
    <script src="{{ asset('admin_lte/plugins/pdfmake/vfs_fonts.js'); }}"></script>
    <script src="{{ asset('admin_lte/plugins/datatables-buttons/js/buttons.html5.min.js'); }}"></script>
    <script src="{{ asset('admin_lte/plugins/datatables-buttons/js/buttons.print.min.js'); }}"></script>
    <script src="{{ asset('admin_lte/plugins/datatables-buttons/js/buttons.colVis.min.js'); }}"></script>

    <!-- Chosen (For Select Multiple UI) -->
    <script src="{{ asset('admin_lte/chosen/chosen.jquery.js'); }}"></script>
    <script src="{{ asset('admin_lte/chosen/chosen.jquery.min.js'); }}"></script>

    <!-- Toast -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <!-- Custom Scripts -->
    <script src="{{asset('admin_lte/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script> $(function () { bsCustomFileInput.init(); }); </script>

    <script src="{{asset('js/importfiles.js')}}"></script>
    <script src="{{asset('js/excelfiles.js')}}"></script>


    <script>

      $(document).ready(function (){
      
        $(".chosen-select").chosen({
          no_results_text: "Oops, nothing found!",
          width: "100%"
        });

        $('#normalTable').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
        "buttons": ["excel", "pdf", "print"]
        }).buttons().container().appendTo('#normalTable .col-md-6:eq(0)');

        $('#anotherNormalTable').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
        "buttons": ["excel", "pdf", "print"]
        }).buttons().container().appendTo('#normalTable .col-md-6:eq(0)');



      });

      // Active Navbar UI
      $(document).ready(function(){
        var pathname = window.location.pathname;
        pathname = pathname.substring(1, pathname.length);
        
        $("a[href='/"+ pathname +"']").parent().addClass(" menu-open");
        $("a[href='/"+ pathname +"']").addClass(" active");

        switch(pathname){
          case "branch":
          case "program":
          case "user-accounts":
          case "matrix":
          case "excel-collection":
          case "excel-new-sales":
            $("a[href='/"+ pathname +"']").parent().parent().parent().addClass("menu-is-opening");
            $("a[href='/"+ pathname +"']").parent().parent().parent().addClass("menu-open");
            $("a[href='/"+ pathname +"']").attr("style", "width: 93%;")
            break;
        }



        $(".wrapper").removeAttr("style");
      });

    </script>

    <script>

      $("#store_password").on("keyup", function(){
        checkPassword();
      });

      $("#store_confirm_password").on("keyup", function(){
        checkPassword();
      });

      $("#store_password").click(function(){
        checkPassword();
      });

      $("#store_confirm_password").click(function(){
        checkPassword();
      });

      function checkPassword(){
            var x = $("#store_password").val();
            var y = $("#store_confirm_password").val();
            if(x != y){
              $("#store_btn").attr("disabled", true);
              $("#pass_ok").attr("style", "display: none; color: #90EE90;");
              $("#pass_ng").attr("style", "color: #FF474C;");
            } else {
              $("#store_btn").removeAttr("disabled");
              $("#pass_ok").attr("style", "color: #90EE90;");
              $("#pass_ng").attr("style", "display: none; color: #FF474C;");
            }
      }

      $("#edit_store_password").on("keyup", function(){
        checkPasswordEdit();
      });

      $("#edit_store_confirm_password").on("keyup", function(){
        checkPasswordEdit();
      });

      $("#edit_store_password").click(function(){
        checkPasswordEdit();
      });

      $("#edit_store_confirm_password").click(function(){
        checkPasswordEdit();
      });

      function checkPasswordEdit(){
            var x = $("#edit_store_password").val();
            var y = $("#edit_store_confirm_password").val();
            if(x != y){
              $("#edit_store_btn").attr("disabled", true);
              $("#edit_pass_ok").attr("style", "display: none; color: #90EE90;");
              $("#edit_pass_ng").attr("style", "color: #FF474C;");
            } else {
              $("#edit_store_btn").removeAttr("disabled");
              $("#edit_pass_ok").attr("style", "color: #90EE90;");
              $("#edit_pass_ng").attr("style", "display: none; color: #FF474C;");
            }
      }

    </script>

    <script>
      
      (async () => {

      const topology = await fetch(
          'https://code.highcharts.com/mapdata/countries/ph/ph-all.topo.json'
      ).then(response => response.json());

      // Prepare demo data. The data is joined to map using value of 'hc-key'
      // property by default. See API docs for 'joinBy' for more info on linking
      // data and map.
      const data = [
          ['ph-mn', 10], ['ph-4218', 11], ['ph-tt', 12], ['ph-bo', 13],
          ['ph-cb', 14], ['ph-bs', 15], ['ph-2603', 16], ['ph-su', 17],
          ['ph-aq', 18], ['ph-pl', 19], ['ph-ro', 20], ['ph-al', 21],
          ['ph-cs', 22], ['ph-6999', 23], ['ph-bn', 24], ['ph-cg', 25],
          ['ph-pn', 26], ['ph-bt', 27], ['ph-mc', 28], ['ph-qz', 29],
          ['ph-es', 30], ['ph-le', 31], ['ph-sm', 32], ['ph-ns', 33],
          ['ph-cm', 34], ['ph-di', 35], ['ph-ds', 36], ['ph-6457', 37],
          ['ph-6985', 38], ['ph-ii', 39], ['ph-7017', 40], ['ph-7021', 41],
          ['ph-lg', 42], ['ph-ri', 43], ['ph-ln', 44], ['ph-6991', 45],
          ['ph-ls', 46], ['ph-nc', 47], ['ph-mg', 48], ['ph-sk', 49],
          ['ph-sc', 50], ['ph-sg', 51], ['ph-an', 52], ['ph-ss', 53],
          ['ph-as', 54], ['ph-do', 55], ['ph-dv', 56], ['ph-bk', 57],
          ['ph-cl', 58], ['ph-6983', 59], ['ph-6984', 60], ['ph-6987', 61],
          ['ph-6986', 62], ['ph-6988', 63], ['ph-6989', 64], ['ph-6990', 65],
          ['ph-6992', 66], ['ph-6995', 67], ['ph-6996', 68], ['ph-6997', 69],
          ['ph-6998', 70], ['ph-nv', 71], ['ph-7020', 72], ['ph-7018', 73],
          ['ph-7022', 74], ['ph-1852', 75], ['ph-7000', 76], ['ph-7001', 77],
          ['ph-7002', 78], ['ph-7003', 79], ['ph-7004', 80], ['ph-7006', 81],
          ['ph-7007', 82], ['ph-7008', 83], ['ph-7009', 84], ['ph-7010', 85],
          ['ph-7011', 86], ['ph-7012', 87], ['ph-7013', 88], ['ph-7014', 89],
          ['ph-7015', 90], ['ph-7016', 91], ['ph-7019', 92], ['ph-6456', 93],
          ['ph-zs', 94], ['ph-nd', 95], ['ph-zn', 96], ['ph-md', 97],
          ['ph-ab', 98], ['ph-2658', 99], ['ph-ap', 100], ['ph-au', 101],
          ['ph-ib', 102], ['ph-if', 103], ['ph-mt', 104], ['ph-qr', 105],
          ['ph-ne', 106], ['ph-pm', 107], ['ph-ba', 108], ['ph-bg', 109],
          ['ph-zm', 110], ['ph-cv', 111], ['ph-bu', 112], ['ph-mr', 113],
          ['ph-sq', 114], ['ph-gu', 115], ['ph-ct', 116], ['ph-mb', 117],
          ['ph-mq', 118], ['ph-bi', 119], ['ph-sl', 120], ['ph-nr', 121],
          ['ph-ak', 122], ['ph-cp', 123], ['ph-cn', 124], ['ph-sr', 125],
          ['ph-in', [126]], ['ph-is', 127], ['ph-tr', 128], ['ph-lu', 129]
      ];

      // Create the chart
      Highcharts.mapChart('container3', {
          chart: {
              map: topology
          },

          title: {
              text: 'Philippine Map'
          },

          subtitle: {
              text: 'Source map: <a href="https://code.highcharts.com/mapdata/countries/ph/ph-all.topo.json">Philippines</a>'
          },

          mapNavigation: {
              enabled: true,
              buttonOptions: {
                  verticalAlign: 'bottom'
              }
          },

          colorAxis: {
              min: 0
          },

          series: [{
              data: data,
              name: 'Branches',
              states: {
                  hover: {
                      color: '#BADA55'
                  }
              },
              dataLabels: {
                  enabled: true,
                  format: '{point.name.age}'
              }
          }]
      });

      })();
    </script>

    @if(session()->has('error_msg'))
      <script>
          toastr.options.preventDuplicates = true;
          toastr.error("{{ session('error_msg') }}");
      </script>
    @endif

    @error('code')
      <script>
        toastr.options.preventDuplicates = true;
        toastr.error('Code already exists');
      </script>
    @enderror

    @if(session()->has('success_msg'))
      <script>
          toastr.options.preventDuplicates = true;
          toastr.success("{{ session('success_msg') }}");
      </script>
    @endif

    @if(session()->has('download_file'))
      <script>
          $("#download_filename").val("{{ session('download_file') }}");
          $("#downloadForm").submit();
      </script>
    @endif

    <script>
      function showErrorToast(val){
        toastr.options.preventDuplicates = true;
        toastr.error(val);
      }
    </script>

    <!-- Modules Scripts -->
    <script>

    let beneficiaryIndex = 0;
    const maxBeneficiaries = 20;
    let membersFormToSubmit = null;
    let orCheckTimer = null;

        function renumberBeneficiaries() 
        {
        document.querySelectorAll('.beneficiaries').forEach((fs, i) => {
            const number = i + 1;

            // Update legend
            fs.querySelector('.beneficiary-legend').textContent =
                `Beneficiaries #${number}`;

            // Remove previous color classes
            fs.classList.remove(
                'beneficiary-1',
                'beneficiary-2',
                'beneficiary-3',
                'beneficiary-4'
            );

            // Apply color sequence (1 → 4)
            const colorClass = `beneficiary-${((number - 1) % 4) + 1}`;
            fs.classList.add(colorClass);
        });

        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!",
            width: "100%"
        });

        }

        function toggleNewSales() 
        {
        if ($('#add_new_sales').is(':checked')) {
            $('.newsales').slideDown();
        } else {
            $('.newsales').slideUp();
        }
        }

        function toggleSubmit(valid)
        {
        $('button[type="submit"]').prop('disabled', !valid);
        }

        function membersShowForm()
        {
            $("#table").attr("style", "display: none;");
            $("#form").removeAttr("style");
        }

        function membersHideForm()
        {
            $("#form").attr("style", "display: none;");
            $("#view").attr("style", "display: none;");
            $("#view").html("");
            $("#table").removeAttr("style");
        }

        function membersLoadView(id)
        {
            $("#view").load('/members/view/' + id);
            $("#table").attr("style", "display: none;");
            $("#view").removeAttr("style");
        }

        function membersLoadEdit(id)
        {
        $("#table").attr("style", "display: none;");
        $("#view").removeAttr("style");

        $("#view").load('/members/edit/' + id, function () {

            // destroy previous chosen instance if any
            $(".chosen-select").chosen("destroy");

            // re-init
            $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!",
            width: "100%"
            });

        });
        }

        function membersPrepareDelete(id)
        {
            var fname = $("#" + id + "_fname").html();
            var lname = $("#" + id + "_lname").html();

            $("#del_fname").html(fname);
            $("#del_lname").html(lname);
            $("#delete_id").val(id);
        }

        function membersPrintSOA(id)
        {
            window.open('/members/print/' + id);
        }

        function membersConfirmRegistration()
        {
            // Get the Add Member form inside #form
            membersFormToSubmit = $("#form form");

            let summary = "";

            // ===================== MEMBER INFO =====================
            summary += "=== MEMBER INFORMATION ===\n";
            summary += "FIRST NAME: " + ($("#fname").val() || "") + "\n";
            summary += "MIDDLE NAME: " + ($("#mname").val() || "") + "\n";
            summary += "LAST NAME: " + ($("#lname").val() || "") + "\n";
            summary += "EXT: " + ($("#ext").val() || "") + "\n";
            summary += "BIRTHDATE: " + ($("#birthdate").val() || "") + "\n";
            summary += "SEX: " + ($("#sex").val() || "") + "\n";
            summary += "BIRTHPLACE: " + ($("#birthplace").val() || "") + "\n";
            summary += "CITIZENSHIP: " + ($("#citizenship").val() || "") + "\n";
            summary += "CIVIL STATUS: " + ($("#civil_status").val() || "") + "\n";
            summary += "CONTACT #: " + ($("#contact_num").val() || "") + "\n";
            summary += "EMAIL: " + ($("#email").val() || "") + "\n";
            summary += "ADDRESS: " + ($("#address").val() || "") + "\n\n";

            // ===================== CLAIMANT INFO =====================
            summary += "=== CLAIMANT INFORMATION ===\n";
            summary += "FIRST NAME: " + ($("#fname_c").val() || "") + "\n";
            summary += "MIDDLE NAME: " + ($("#mname_c").val() || "") + "\n";
            summary += "LAST NAME: " + ($("#lname_c").val() || "") + "\n";
            summary += "EXT: " + ($("#ext_c").val() || "") + "\n";
            summary += "BIRTHDATE: " + ($("#birthdate_c").val() || "") + "\n";
            summary += "SEX: " + ($("#sex_c").val() || "") + "\n";
            summary += "CONTACT #: " + ($("#contact_num_c").val() || "") + "\n\n";

            // ===================== BENEFICIARIES =====================
            summary += "=== BENEFICIARIES ===\n";

            let beneficiaryCount = 0;

            $("#beneficiaries-container fieldset").each(function (i) {
                beneficiaryCount++;

                const fname = $(this).find("input[name*='[fname]']").val() || "";
                const mname = $(this).find("input[name*='[mname]']").val() || "";
                const lname = $(this).find("input[name*='[lname]']").val() || "";
                const ext = $(this).find("input[name*='[ext]']").val() || "";

                const birthdate = $(this).find("input[name*='[birthdate]']").val() || "";
                const sex = $(this).find("select[name*='[sex]']").val() || "";
                const relationship = $(this).find("input[name*='[relationship]']").val() || "";
                const contact_num = $(this).find("input[name*='[contact_num]']").val() || "";

                summary += "BENEFICIARY #" + (i + 1) + "\n";
                summary += "NAME: " + fname + " " + mname + " " + lname + " " + ext + "\n";
                summary += "BIRTHDATE: " + birthdate + "\n";
                summary += "SEX: " + sex + "\n";
                summary += "RELATIONSHIP: " + relationship + "\n";
                summary += "CONTACT #: " + contact_num + "\n\n";
            });

            if (beneficiaryCount === 0) {
                summary += "NO BENEFICIARIES ADDED.\n\n";
            }

            // ===================== NEW SALES INFO =====================
            summary += "=== NEW SALES INFORMATION ===\n";
            summary += "BRANCH: " + ($("#branch_id option:selected").text() || "") + "\n";
            summary += "PROGRAM: " + ($("#program_id option:selected").text() || "") + "\n";
            summary += "OR #: " + ($("#or_number").val() || "") + "\n";
            summary += "OR DATE: " + ($("#or_date").val() || "") + "\n";
            summary += "APPLICATION #: " + ($("#app_no").val() || "") + "\n";
            summary += "REGISTRATION FEE: " + ($("#registration_fee").val() || "") + "\n";
            summary += "MAS: " + ($("#agent_id option:selected").text() || "") + "\n";
            summary += "AMOUNT: " + ($("#amount").val() || "") + "\n";
            summary += "INCENTIVES: " + ($("#incentives").val() || "") + "\n";

            // ✅ Force everything to ALL CAPS (labels + values)
            summary = summary.toUpperCase();

            // Put summary into modal
            $("#registration-summary").text(summary);

            // Show modal
            $("#ConfirmRegisterModal").modal("show");
        }

        function confirmMemberSubmit()
        {
            if (membersFormToSubmit) {
                membersFormToSubmit.submit();
            }
        }

        function membersCheckORNumber()
        {
            const orNumber = $("#or_number").val();

            // If empty, clear warning
            if (!orNumber) {
                $("#or-warning").hide().text("");
                $("#or_number").removeClass("is-invalid");
                return;
            }

            // Optional debounce (prevents too many requests)
            clearTimeout(orCheckTimer);

            orCheckTimer = setTimeout(function () {

                $.ajax({
                    url: "/members/check-or-number",
                    method: "GET",
                    data: { or_number: orNumber },
                    success: function (res) {
                        if (res.exists) {
                            $("#or-warning").show().text(res.message);
                            $("#or_number").addClass("is-invalid");
                        } else {
                            $("#or-warning").hide().text("");
                            $("#or_number").removeClass("is-invalid");
                        }
                    },
                    error: function () {
                        // If API fails, don't block user, just hide warning
                        $("#or-warning").hide().text("");
                        $("#or_number").removeClass("is-invalid");
                    }
                });

            }, 200);
        }

        function newSalesShowForm() 
        {
            $("#newSalesTable").hide();
            $("#newSalesForm").show();
        }

        function newSalesHideForm() 
        {
            $("#newSalesForm").hide();
            $("#newSalesView").hide().html("");
            $("#newSalesTable").show();
        }

        function newSalesViewFunction(id) 
        {
            $("#newSalesView").load('/new-sales/view/' + id).show();
            $("#newSalesTable").hide();
        }

        function newSalesEditFunction(id) 
        {
            $("#newSalesView").load('/new-sales/edit/' + id).show();
            $("#newSalesTable").hide();
        }

        function newSalesDeleteFunction(id) 
        {
            $("#delete_id").val(id);
        }

        function newSalesPrintFunction(id) 
        {
            window.open('/members/print/' + id);
        }

        function newSalesMemberChange()
        {
            let selected = $("#member_id option:selected");

            let branchId = selected.data("branch");
            let agentId  = selected.data("agent");

            // Set Branch
            $("#branch_id").val(branchId).trigger("chosen:updated");

            // update hidden field
            $("#branch_id_hidden").val(branchId);

            // disable visible select
            $("#branch_id").prop("disabled", true).trigger("chosen:updated");

            // Set Agent (NOT disabled)
            $("#agent_id").val(agentId).trigger("chosen:updated");

            // Load programs for this member
            loadMemberProgramsAlt($("#member_id").val());
        }

        function dataEntryHideForm()
        {
            $("#form").attr("style", "display: none;");
            $("#edit_form").attr("style", "display: none;");
            $("#view").attr("style", "display: none;");
            $("#view").html("");
            $("#table").removeAttr("style");
        }

        function dataEntryShowForm()
        {
            $("#table").attr("style", "display: none;");
            $("#form").removeAttr("style");
        }

        function dataEntryViewFunction(id)
        {
            $("#view").load('/entries/view/' + id);
            $("#table").attr("style", "display: none;");
            $("#view").removeAttr("style");
        }

        function dataEntryEditFunction(id)
        {
            $("#view").load('/entries/edit/' + id);
            $("#table").attr("style", "display: none;");
            $("#view").removeAttr("style");
        }

        function dataEntryDeleteFunction(id)
        {
            var display = $("#branch_" + id).html();
            $("#del_display").html(display);
            $("#delete_id").val(id);
        }

        function dataEntryCheckAutoFills()
        {
            $("#temp").load(
                "/entries/getIncentivesMatrix/" +
                $("#member_id").val() + "/" +
                $("#program_id").val(),
                function(response, status, xhr)
                {
                    if (status == "error")
                    {
                        var msg = "Sorry but there was an error: ";
                        $("#error").html(msg + xhr.status + " " + xhr.statusText);
                        window.alert(msg + xhr.status + " " + xhr.statusText);
                    }

                    if (status == "success")
                    {
                        $("#incentives").val(response);
                    }
                }
            );
        }

        function dataEntryFormatDate(date)
        {
            var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        }

        function dataEntryEnforceMinMax(el)
        {
            if (el.value != "")
            {
                if (parseInt(el.value) < parseInt(el.min))
                el.value = el.min;

                if (parseInt(el.value) > parseInt(el.max))
                el.value = el.max;
            }
        }

        function dataEntryMemberChanged()
        {
            let selected = $("#member_id option:selected");

            let branchId = selected.data("branch");
            let agentId  = selected.data("agent");

            // Set Branch
            $("#branch_id").val(branchId).trigger("chosen:updated");

            // update hidden field
            $("#branch_id_hidden").val(branchId);

            // disable visible select
            $("#branch_id").prop("disabled", true).trigger("chosen:updated");

            // Set Agent (NOT disabled)
            $("#agent_id").val(agentId).trigger("chosen:updated");

            // Load programs for this member
            loadMemberPrograms($("#member_id").val());
        }

        function formatDate(dateObj) 
        {
            let yyyy = dateObj.getFullYear();
            let mm = String(dateObj.getMonth() + 1).padStart(2, "0");
            let dd = String(dateObj.getDate()).padStart(2, "0");
            return yyyy + "-" + mm + "-" + dd;
        }

        function getBeneficiaryBirthdates() 
        {
            let birthdates = [];

            $('.beneficiary-birthdate').each(function () {
                const value = $(this).val();

                if (value) {
                    birthdates.push({
                        id: this.id,
                        birthdate: value
                    });
                }
            });

            return birthdates;
        }

        function validateProgram() 
        {
        let birthdate  = $('#birthdate').val();
        let program_id = $('#program_id').val();
        let birthdates = getBeneficiaryBirthdates();

        if (!birthdate || !program_id) return;

        $.ajax({
            url: '/members/validateProgram',
            type: 'POST',
            data: {
                birthdate: birthdate,
                program_id: program_id,
                beneficiaries: birthdates,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (res) {
                if(res.valid == true){
                    // valid
                    $('#program-age-warning').hide().text("");
                    $('#program_id').removeClass('is-invalid');
                    $('#birthdate').removeClass('is-invalid');
                    $('.beneficiary-birthdate').removeClass('is-invalid');

                    //toggleSubmit(true);
                } else {
                    // invalid
                    let message = res.messages.join('<br>');
                    $('#program-age-warning').show().html(message);
                    $('#program_id').addClass('is-invalid');

                    if(res.member_valid == false){
                        $('#birthdate').addClass('is-invalid');
                    } else {
                        $('#birthdate').removeClass('is-invalid');
                    }

                    // Clear all invalid classes first
                    $('.beneficiary-birthdate').removeClass('is-invalid');

                    if (Array.isArray(res.invalidBeneficiaries)) {
                    res.invalidBeneficiaries.forEach(b => {
                        $('#' + b.id).addClass('is-invalid');
                    });
                    }
                    //toggleSubmit(false);    
                }
                console.log(res.messages);
                console.log(res.valid);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let res = xhr.responseJSON;
                    alert(res.messages);
                }
                console.error('Program validation failed');
            }
        });
        }

        function calculateAmount()
        {
            let selectedProgram = $("#program_id option:selected");
            let amountMin = parseFloat(selectedProgram.data("amount")) || 0;

            let payments = parseInt($("#times").val());
            if (!payments || payments < 1) payments = 1;

            let computedAmount = amountMin * payments;

            $("#amount").val(computedAmount.toFixed(2));

            checkAmountWarning();
        }

        // For Data Entry, we want to load all programs (including those that may be disabled for new sales)
        function loadMemberPrograms(memberId)
        {
            $.get("/entries/getMemberPrograms/" + memberId, function(data)
            {
                let programSelect = $("#program_id");
                programSelect.empty();

                $.each(data, function(index, program)
                {
                    programSelect.append(
                        `<option value="${program.id}" 
                            data-amount="${program.amount_min}">
                            ${program.code}
                        </option>`
                    );
                });

                programSelect.trigger("chosen:updated");

                // Auto trigger calculation after load
                calculateAmount();
            });
        }

        // For New Sales
        function loadMemberProgramsAlt(memberId)
        {
            $.get("/entries/getMemberPrograms/" + memberId, function(data)
            {
                let programSelect = $("#program_id");
                programSelect.empty();

                $.each(data, function(index, program)
                {
                    programSelect.append(
                        `<option value="${program.id}" 
                            data-amount="${program.amount_min}">
                            ${program.code}
                        </option>`
                    );
                });

                programSelect.trigger("chosen:updated");

                // Auto trigger calculation after load
                calculateAmount();
            });
        }

        function checkAmountWarning()
        {
            let selectedProgram = $("#program_id option:selected");
            let amountMin = parseFloat(selectedProgram.data("amount")) || 0;

            let payments = parseInt($("#times").val());
            if (!payments || payments < 1) payments = 1;

            let expected = amountMin * payments;
            let currentAmount = parseFloat($("#amount").val()) || 0;

            if (currentAmount != expected)
            {
                $("#amount-warning").html(
                    `WARNING: Expected amount is ${expected.toFixed(2)} 
                    (${amountMin} × ${payments})`
                );
                $("#amount-warning").show();
                $('#amount').addClass('is-invalid');
            }
            else
            {
                $("#amount-warning").html("");
                $("#amount-warning").hide();
                $('#amount').removeClass('is-invalid');
            }
        }

        // Trigger check when user changes OR, leaves field, or types
        $(document).on("blur", "#or_number", function () {
            membersCheckORNumber();
        });

        const monthFromField = document.getElementById('month_from');

        if(monthFromField)
        {
            // Month From and Month to Calculation in Data Entry
            $("#times, #month_from").on("change keyup", function()
            {
                let times = parseInt($("#times").val());
                let from  = $("#month_from").val();

                if (!times || !from) return;

                let date = new Date(from + "-01");

                date.setMonth(date.getMonth() + (times - 1));

                let yyyy = date.getFullYear();
                let mm   = String(date.getMonth() + 1).padStart(2, '0');

                let computedTo = yyyy + "-" + mm;

                $("#month_to").val(computedTo);
            });

            $("#month_to").on("change", function()
            {
                let from = $("#month_from").val();
                let to   = $("#month_to").val();

                if (to < from)
                {
                    alert("To-month must be greater than or equal to From-month.");
                    $("#month_to").val(from);
                }
            });

            $("#program_id").on("change", function() {
                calculateAmount();
            });

            $("#times").on("change keyup", function() {
                calculateAmount();
            });

            $("#amount").on("keyup change", function() {
                checkAmountWarning();
            });
        }

        const addBtn = document.getElementById('add-beneficiary');

        if (addBtn) 
        {
            addBtn.addEventListener('click', () => {
                const container = document.getElementById('beneficiaries-container');

                if (!container) return;

                if (container.children.length >= maxBeneficiaries) {
                    toastr.error("Maximum number of " + maxBeneficiaries + " beneficiaries reached");
                    return;
                }

                const templateEl = document.getElementById('beneficiary-template');
                if (!templateEl) return;

                const html = templateEl.innerHTML.replaceAll('__INDEX__', beneficiaryIndex);

                const wrapper = document.createElement('div');
                wrapper.innerHTML = html;

                const fieldset = wrapper.firstElementChild;

                const removeBtn = fieldset.querySelector('.beneficiary-remove-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        fieldset.remove();
                        renumberBeneficiaries();
                        validateProgram();
                    });
                }

                container.appendChild(fieldset);
                beneficiaryIndex++;
                renumberBeneficiaries();
            });
        }

        const memberId = document.getElementById('add-member_id');

        if (memberId) 
        {
        $(document).on('change', '#member_id', function () {
            const memberId = $(this).val();
            const $programSelect = $('select[name="program_id"]');

            // Reset first (important)
            $programSelect.find('option').prop('disabled', false);

            if (!memberId || memberId === '0') {
                $programSelect.trigger('chosen:updated');
                return;
            }

            $.ajax({
            url: '/new-sales/check-member-programs',
            type: 'POST',
            data: {
                member_id: memberId,


                
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                if (!Array.isArray(res.registered_descriptions)) {
                    return;
                }
                // Loop program options
                $programSelect.find('option').each(function () {
                    const optionText = $(this).text().trim();

                    // Disable if description matches any registered description
                    if (res.registered_descriptions.includes(optionText)) {
                        $(this).prop('disabled', true);
                    }
                });

                // Refresh Chosen UI
                $programSelect.trigger('chosen:updated');
            },
            error: function () {
                console.error('Failed to validate member programs');
            }
            });
        });
        }


        $(document).ready(function () {

            //Data Entry Function
            try
            {
                if(window.location.pathname.includes("/entries")){
                    dataEntryMemberChanged();
                }
            } 
            catch(e)
            {
                //None
            }

            // Check if OR Date and Birthdates are empty, if yes set default values
            const today = formatDate(new Date());
            const defaultBirthdate = "2000-01-01";

            // OR Date = Today
            if ($("#or_date").length && $("#or_date").val() === "") {
                $("#or_date").val(today);
            }

            // Member Birthdate = 2000-01-01
            if ($("#birthdate").length && $("#birthdate").val() === "") {
                $("#birthdate").val(defaultBirthdate);
            }

            // Claimant Birthdate = 2000-01-01
            if ($("#birthdate_c").length && $("#birthdate_c").val() === "") {
                $("#birthdate_c").val(defaultBirthdate);
            }

            // Beneficiary Birthdate (Dynamic)
            $(document).on("focus", "input[name*='[birthdate]']", function () {
                if ($(this).val() === "") {
                    $(this).val(defaultBirthdate);
                }
            });

            $('#birthdate').on('blur', validateProgram);
            $('#program_id').on('change', validateProgram);
            $(document).on('blur', '.beneficiary-birthdate', validateProgram);

            // Check First Name + Last Name combination to prevent duplicates
            let checkTimeout = null;

            // Check App No to prevent duplicates
            $('#app_no').on('blur', function () {

                const appNo  = $('#app_no').val().trim();
                
                // Only check when required fields are filled
                if (!appNo) {
                    $('#app-no-warning').hide();
                    $('#app_no').removeClass('is-invalid');
                    return;
                }

                // Debounce to avoid rapid firing
                clearTimeout(checkTimeout);
                checkTimeout = setTimeout(() => {

                    $.ajax({
                        url: "{{ route('members.checkAppNo') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            app_no: appNo
                        },
                        success: function (res) {
                            if (res.exists) {
                                $('#app-no-warning').show().text(res.message);
                                $('#app_no').addClass('is-invalid');
                            } else {
                                $('#app-no-warning').hide().text("");
                                $('#app_no').removeClass('is-invalid');
                            }
                        },
                        error: function (xhr, status, error) {
                            $('#app-no-warning').hide().text("");
                            console.error('Validation app no check failed');
                        }
                    });

                }, 300);
            });

            $('#fname, #mname, #lname').on('blur', function () {

                const firstName  = $('#fname').val().trim();
                const middleName = $('#mname').val().trim();
                const lastName   = $('#lname').val().trim();

                // Only check when required fields are filled
                if (!firstName || !lastName) {
                    $('#member-warning').hide();
                    return;
                }

                // Debounce to avoid rapid firing
                clearTimeout(checkTimeout);
                checkTimeout = setTimeout(() => {

                    $.ajax({
                        url: "{{ route('members.checkName') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            first_name: firstName,
                            middle_name: middleName,
                            last_name: lastName
                        },
                        success: function (res) {
                            if (res.exists) {
                                $('#member-warning').show().text(res.message);
                                $('#fname, #mname, #lname').addClass('is-invalid');
                            } else {
                                $('#member-warning').hide().text("");
                                $('#fname, #mname, #lname').removeClass('is-invalid');
                            }
                        },
                        error: function (xhr, status, error) {
                            $('#member-warning').hide().text("");
                            console.error('Validation check failed');
                        }
                    });

                }, 300);
            });

            // Check Email to prevent duplicates
            $('#email').on('blur', function () {

                const email  = $('#email').val().trim();

                // Only check when required fields are filled
                if (!email) {
                    $('#email-warning').hide();
                    return;
                }

                // Debounce to avoid rapid firing
                clearTimeout(checkTimeout);
                checkTimeout = setTimeout(() => {

                    $.ajax({
                        url: "{{ route('members.checkEmail') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            email: email
                        },
                        success: function (res) {
                            if (res.exists) {
                                $('#email-warning').show().text(res.message);
                                $('#email').addClass('is-invalid');
                            } else {
                                $('#email-warning').hide().text("");
                                $('#email').removeClass('is-invalid');
                            }
                        },
                        error: function (xhr, status, error) {
                            $('#email-warning').hide().text("");
                            console.error('Validation email check failed');
                        }
                    });

                }, 300);
            });

            const confPass = document.getElementById("confirm_password");

            // If confirm_password does NOT exist, stop the script
            if (confPass != null) {

                const resetBtn = $("#resetBtn");

                $("#password, #confirm_password").on("blur", function () {

                    const password = document.getElementById("password").value;
                    const confirmPassword = confPass.value;

                    if (password != confirmPassword && confirmPassword !== "" && password !== "") 
                    {
                        toastr.options.preventDuplicates = true;
                        toastr.error("Passwords do not match!");
                        $('#password').removeClass('is-valid');
                        $('#password').addClass('is-invalid');

                        $('#confirm_password').removeClass('is-valid');
                        $('#confirm_password').addClass('is-invalid');
                        resetBtn.prop("disabled", true);

                    }
                    else if(password == "" && confirmPassword == "")
                    {
                        $('#password').removeClass('is-invalid is-valid');
                        $('#confirm_password').removeClass('is-invalid is-valid');
                        resetBtn.prop("disabled", true);
                    }
                    else
                    {
                        if(password != "" && confirmPassword == "")
                        {
                            $('#password').removeClass('is-invalid');
                            $('#password').addClass('is-valid');
                        }
                        
                        if(password == "" && confirmPassword != "")
                        {
                            $('#confirm_password').removeClass('is-invalid');
                            $('#confirm_password').addClass('is-valid');
                        }

                        if(password != "" && confirmPassword != "" && password == confirmPassword)
                        {
                            $('#password').removeClass('is-invalid');
                            $('#password').addClass('is-valid');
                            $('#confirm_password').removeClass('is-invalid');
                            $('#confirm_password').addClass('is-valid');
                            resetBtn.prop("disabled", false);
                        }
                        
                    }

                });

            }
        });

    </script>

    <!-- Custom scripts -->
    <script>

    // Login, Forgot Password, Register UI Toggle
    document.addEventListener("DOMContentLoaded", function () {

        const resetBtn = $("#register");
        const loginWrapper = document.querySelector(".login-wrapper");
        const forgotWrapper = document.querySelector(".forgot-password-wrapper");
        const registerWrapper = document.querySelector(".register-wrapper");

        function showOnly(wrapper) {
        loginWrapper.style.display = "none";
        forgotWrapper.style.display = "none";
        registerWrapper.style.display = "none";
        wrapper.style.display = "block";
        }

        // Forgot password links
        document.querySelectorAll(".forgot-password-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            showOnly(forgotWrapper);
        });
        });

        // Register links
        document.querySelectorAll(".login-wrapper-footer-text a").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            showOnly(registerWrapper);
        });
        });

        document.querySelectorAll(".back-to-login").forEach(link => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                showOnly(loginWrapper);
            });
        });

        // Check First Name + Last Name combination to prevent duplicates
        let checkTimeout = null;

        // Check Email to prevent duplicates
        $('#email_reg').on('blur', function () {

            const email  = $('#email_reg').val().trim();

            // Only check when required fields are filled
            if (!email) {
                $('#email-warning').hide();
                return;
            }

            // Debounce to avoid rapid firing
            clearTimeout(checkTimeout);
            checkTimeout = setTimeout(() => {

                $.ajax({
                    url: "{{ route('users.checkEmail') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        email: email
                    },
                    success: function (res) {
                        if (res.exists) {
                            $('#email-warning').show().text(res.message);
                            $('#email').addClass('is-invalid');
                            resetBtn.prop("disabled", true);
                        } else {
                            $('#email-warning').hide().text("");
                            $('#email').removeClass('is-invalid');
                            resetBtn.prop("disabled", false);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#email-warning').hide().text("");
                        console.error('Validation email check failed');
                    }
                });

            }, 300);
        });

        // Check Contact Number to prevent duplicates
        $('#contact_num_register').on('blur', function () {

            const contactNum = $('#contact_num_register').val().trim();

            // Only check when required fields are filled
            if (!contactNum) {
                $('#contact-num-warning').hide();
                return;
            }

            // Debounce to avoid rapid firing
            clearTimeout(checkTimeout);
            checkTimeout = setTimeout(() => {

                $.ajax({
                    url: "{{ route('users.checkContactNum') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        contact_num: contactNum
                    },
                    success: function (res) {
                        if (res.exists) {
                            $('#contact-num-warning').show().text(res.message);
                            $('#contact_num_register').addClass('is-invalid');
                            resetBtn.prop("disabled", true);
                        } else {
                            $('#contact-num-warning').hide().text("");
                            $('#contact_num_register').removeClass('is-invalid');
                            resetBtn.prop("disabled", false);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#contact-num-warning').hide().text("");
                        console.error('Validation contact number check failed');
                    }
                });

            }, 300);
        });


        const confPass = document.getElementById("confirm_password_reg");

        // If confirm_password does NOT exist, stop the script
        if (confPass != null) {

            $("#password_reg, #confirm_password_reg").on("blur", function () {

                const password = document.getElementById("password_reg").value;
                const confirmPassword = confPass.value;

                $('#password-warning').hide();

                if (password != confirmPassword && confirmPassword !== "" && password !== "") 
                {
                    $('#password-warning').show().text("Password do not match");
                    $('#password_reg').removeClass('is-valid');
                    $('#password_reg').addClass('is-invalid');

                    $('#confirm_password_reg').removeClass('is-valid');
                    $('#confirm_password_reg').addClass('is-invalid');
                    resetBtn.prop("disabled", true);

                }
                else if(password == "" && confirmPassword == "")
                {
                    $('#password_reg').removeClass('is-invalid is-valid');
                    $('#confirm_password_reg').removeClass('is-invalid is-valid');
                    resetBtn.prop("disabled", true);
                }
                else
                {
                    if(password != "" && confirmPassword == "")
                    {
                        $('#password-warning').show().text("Password do not match");
                        $('#password_reg').removeClass('is-invalid');
                        $('#password_reg').addClass('is-valid');
                        resetBtn.prop("disabled", true);
                    }
                    
                    if(password == "" && confirmPassword != "")
                    {
                        $('#password-warning').show().text("Password do not match");
                        $('#confirm_password_reg').removeClass('is-invalid');
                        $('#confirm_password_reg').addClass('is-valid');
                        resetBtn.prop("disabled", true);
                    }

                    if(password != "" && confirmPassword != "" && password == confirmPassword)
                    {
                        $('#password_reg').removeClass('is-invalid');
                        $('#password_reg').addClass('is-valid');
                        $('#confirm_password_reg').removeClass('is-invalid');
                        $('#confirm_password_reg').addClass('is-valid');
                        resetBtn.prop("disabled", false);
                    }
                    
                }

            });

        }

    });

    </script>

    <!-- All Pages Script -->
    <script>
        // ===================== ENFORCE UPPERCASE ON ALL .text-uppercase INPUTS =====================
        $(document).ready(function () {

            // On input: transform value to uppercase in real-time
            $(document).on('input', 'input.text-uppercase, textarea.text-uppercase', function () {
                var pos = this.selectionStart;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(pos, pos);
            });

            // On form submit: ensure all .text-uppercase fields are uppercased before sending
            $(document).on('submit', 'form', function () {
                $(this).find('input.text-uppercase, textarea.text-uppercase').each(function () {
                    $(this).val($(this).val().toUpperCase());
                });
            });

        });
    </script>

    <!-- Report Generation Script -->
    <script>
        $(document).ready(function() {
           if(window.location.href.includes("/reports")){

                $('#weekInput').hide();
                $('#monthInput').hide();

                $('#reportType').on('change', function() {
                    const selectedType = $(this).val();
                    $('#dateInput').hide();
                    $('#weekInput').hide();
                    $('#monthInput').hide();

                    if (selectedType === 'daily') {
                        $('#dateInput').show();
                    } else if (selectedType === 'weekly') {
                        $('#weekInput').show();
                    } else if (selectedType === 'monthly') {
                        $('#monthInput').show();
                    }
                });
            }
        });
    </script>

    {{-- ====================================================================
        JAVASCRIPT
    ==================================================================== --}}
    <script>

        // ── DataTables Init ──────────────────────────────────────────────────
        $(document).ready(function () {
            $('#remittanceTable').DataTable({ order: [[1, 'desc']] });
            $('#expenseTable').DataTable({ order: [[1, 'desc']] });

            // Re-init chosen selects inside modals when they open
            $('.modal').on('shown.bs.modal', function () {
                $(this).find('.chosen-select').chosen({ width: '100%' });
            });
        });

        // ── Toggle Bank / GCash fields ───────────────────────────────────────
        function toggleRemittanceFields(prefix) {
            var type = $('#' + prefix + '_rem_type').val();
            if (type === 'bank') {
                $('#' + prefix + '_bank_name_group').show();
                $('#' + prefix + '_gcash_number_group').hide();
            } else {
                $('#' + prefix + '_bank_name_group').hide();
                $('#' + prefix + '_gcash_number_group').show();
            }
        }

        // ── Remittance: Populate Edit Modal ──────────────────────────────────
        function remittanceEditFunction(id, branch_id, mas_id, mas_name, type, amount, bank_name, gcash_number, ref_no, date, remarks) {
            $('#editRemittanceForm').attr('action', '/expenses/remittance/update/' + id);
            $('#edit_rem_branch_id').val(branch_id).trigger('chosen:updated'); // ← add trigger
            $('#edit_rem_mas_id').val(mas_id).trigger('chosen:updated');
            $('#edit_rem_mas_name').val(mas_name);
            $('#edit_rem_type').val(type);
            $('#edit_rem_amount').val(amount);
            $('#edit_rem_bank_name').val(bank_name);
            $('#edit_rem_gcash_number').val(gcash_number);
            $('#edit_rem_reference').val(ref_no);
            $('#edit_rem_date').val(date);
            $('#edit_rem_remarks').val(remarks);
            toggleRemittanceFields('edit');
        }
        
        // ── Remittance: Populate Delete Modal ────────────────────────────────
        function remittanceDeleteFunction(id) {
            $('#delete_rem_id').val(id);
        }

        // ── Expense: Populate Edit Modal ─────────────────────────────────────
        function expenseEditFunction(id, branch_id, mas_id, member_id, type_of_expense, receipt_number, amount, date, remarks) {
            $('#editExpenseForm').attr('action', '/expenses/expense/update/' + id);
            $('#edit_exp_branch_id').val(branch_id).trigger('chosen:updated'); // ← add trigger
            $('#edit_exp_mas_id').val(mas_id).trigger('chosen:updated');
            $('#edit_exp_member_id').val(member_id).trigger('chosen:updated');
            $('#edit_exp_type').val(type_of_expense);
            $('#edit_exp_receipt').val(receipt_number);
            $('#edit_exp_amount').val(amount);
            $('#edit_exp_date').val(date);
            $('#edit_exp_remarks').val(remarks);
        }

        // ── Expense: Populate Delete Modal ───────────────────────────────────
        function expenseDeleteFunction(id) {
            $('#delete_exp_id').val(id);
        }

    </script>

