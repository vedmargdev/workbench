$(document).ready(function(){
    
    tinymce.init({
	    selector: ".mytextarea",
	    plugins: "lists link image media",
	    toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image media",
	    // content_css:"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css",
	    skin: "bootstrap",
	    height: 200,
	    media_live_embeds: true, // Enables embedding of videos
	    setup: function (editor) {
	      editor.on("init", function () {
	        editor.getBody().classList.add("container");
	      });
	    },
    });



    $("body").on("submit", "form#add_offline_test,form#edit_offline_test", function (event) {
        event.preventDefault();
        if( !$(this).hasClass('active') ) {
          createOrUpdateOfflineTest(new FormData(this), $(this));
        }
    });
    async function createOrUpdateOfflineTest(data, $this) {

        $this.find('span._error').remove();
        let sectionsData = [];
        let error = false;
       
        $this.find('.seciton--subject-container').each(function(index, el) {
            // let section = $(this).find('.selected-section').val();
            let section = $(this).find('.selected-section').val() || $(this).find('input[name="section[]"]').val();
            console.log("section is ",section);
            subjects = [];
            if( $(this).find('.subject_container').find('.subject-section-row').length > 0 ) {
                $(this).find('.subject_container').find('.subject-section-row').each(function(index, el) {
                    let subject_id = parseInt($(this).attr('data-subject')) || $(this).find('input[name="subject[]"]').val();
                    console.log("Subject is ",subject_id);
                    let max_marks = parseInt($(this).find('.subject--max-marks').val()) || $(this).find('input[name="max_marks[]"]').val();
                    if( subject_id ) {
                        subjects.push({subject_id: subject_id, max_marks:max_marks});
                    }
                    if( !max_marks ) {
                        error = true;
                        _error('Please enter max marks *', $(this).find('.subject--max-marks'));
                    }
                });
            } 
            console.log("subject is ",subjects);
            if( subjects.length > 0 && section ) {
                sectionsData.push({section:section, subjects:subjects});
            }
            else {
                error = true;
                _error('Please select a subject *', $(this).find('.subject_chosen'));
            }
        });
        console.log("Section data is ",sectionsData);
        if( sectionsData.length > 0 && !error ) {
            data.append('sections_data', JSON.stringify(sectionsData));
            let params = {
              url: $this.attr("action"),
              type: $this.attr("method"),
              params: data,
              loader: true,
              selector: $this,
            };
            $this.addClass('active');
            let response = await ajaxCall(params);
            _show_result(response);
            $this.removeClass('active');

            if (response && response.status === "success") {
              let array = response.data;
              console.log("Data is ",array);
              if ( response.update ) {
                let row = $("#row-" + array.id);
                if (row.length > 0) {
                  
                }
                _remove_popup($this);
              }
              else {
                if( $('#totalOfflineTest').length > 0 ) {
                    let total = parseInt($('#totalOfflineTest').text());
                    total++;
                    $('#totalOfflineTest').text(total);
                }
                let row = response.data;
                console.log("Data is ",row);
                let table = $('.datatable-checkbox').DataTable();
                let length = table.page.info().recordsTotal;
                let checkbox=row.checkbox;
                console.log("Check box is ",checkbox);
                length =length ? ++length : 1;
               let checkboxWithLength = checkbox + ' ' + length;
                addDataTableRow('.datatable-checkbox', [checkboxWithLength, row.test_name, row.date,row.time,row.class,row.section,row.created_by,row.created_date, row.action], row.id);
                $this.trigger('reset');
                _hide_popup($this);
              }
            }
        }
    }
//--------------------------------------------Edit popup----------------------------------------//
    $("body").on("click", ".edit-offline-test", function (e) {
        e.preventDefault();
        let uuid = $(this).attr("data-uuid");
        console.log("edit")
        if (uuid) {
            let fd = new FormData();
            fd.append('uuid', uuid);
            let params = {
                url: base_url + `/offline-test/edit`,
                loader: true,
                type: "post",
                params: fd,
            };
            fetchOfflineTestEditPopup(params);
        }
    });
    async function fetchOfflineTestEditPopup(params) {
        let result = await ajaxCall(params);
        if (result && result.view) {
            $(result.view).appendTo("body");
            // if( $('#popupWrapper').find('.select2').length > 0 ) {
            //     $('#popupWrapper').find('.select2').select2();
            // }
        }
    }


//----------------------------------------------------------------------Saving Reason for Delete -------------------------------------//

    $("body").on("click", ".delete-offline-test",function (event) {
        event.preventDefault();
        let uuid = $(this).data('uuid');
        if( uuid ) {
            $('#deleteOfflineTest').show();
            let ids = [uuid];
            let v = JSON.stringify(ids);
            $('#deleteOfflineTest').find('#deletingOfflineTestIds').val(v);
        }
    });

    $("body").on("submit", "form#deletingOfflineTestForm",function (event) {
        event.preventDefault();
        if( confirm('Are you sure?') && !$(this).hasClass('active') ) {
            let fd = new FormData(this);
            let params = {
                url: base_url + '/offline-test/delete', 
                type: "post",
                params: fd,
                loader: true,
                selector: $(this),
            };
            offlineTestDeleteFunc(params, $(this));
        }
    });

    async function offlineTestDeleteFunc(params, $this) {
        var response = await ajaxCall(params);
        _show_result(response);
        if (response.status && response.status === "success") {
            $this.trigger('reset');
            _hide_popup($this);
            $('#deleteBulkButton').addClass('disabled');
            $('#select-all-flog-checkbox-').prop('checked', false);
            $('#deleteBulkButton').find('.total').text('');
            let count = response.ids.length; 
            if( $('#totalDeletedOfflineTest').length > 0 ) {
                let total = parseInt($('#totalDeletedOfflineTest').text());
                total = total + count;
                $('#totalDeletedOfflineTest').text(total);
            }
            if( $('#totalOfflineTest').length > 0 ) {
                let total = parseInt($('#totalOfflineTest').text());
                total = total - count;
                $('#totalOfflineTest').text(total);
            }

            $.each(response.ids, function(index, val) {
                let row = $('#allDataTable').find('#row-' + this);
                if( row.length > 0 && row.closest('.datatable-checkbox').length > 0 ) {
                    datatableRemoveRow( '.datatable-checkbox', row.find('td') );
                }
                else if( row.length > 0 ) {
                    row.remove();
                }
            });
                
        }
    }  
//--------------------------------------------------------------------------------------------//
    $('#select-all-flog-checkbox-').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */
        if( $(this).hasClass('active') ) {
            console.log("Inside the if condition of hasClass active");
            $(this).removeClass('active')
            $('input.select--flog-checkbox-').prop('checked', false);
        }
        else {
            console.log("Inside the else condition addClass active");
            $(this).addClass('active')
            $('input.select--flog-checkbox-').prop('checked', true);
        }
        console.log("after the else condition ");
        enableOfflineTestTrashButton();
    });
    function enableOfflineTestTrashButton() {
	console.log("Inside the enableTrashButton function");

        var uids = [];
        $('.select--flog-checkbox-').each(function(index, el) {
            console.log("Inside the select--flog-checkbox-");
            if( $(this).is(":checked") && $(this).val() ) {
                console.log("Inside the if");
                $(this).closest('tr').addClass('selected');
                uids.push($(this).val());
            }
            else {
                console.log("Inside the eles");
                $(this).closest('tr').removeClass('selected');
            }
        });
        if( uids.length < 1 ) {
            if( $('#deleteBulkButton').length > 0 ) {
                $('#deleteBulkButton').find('.total').html('');
                $('#deleteBulkButton').addClass('disabled');
            }
            else if( $('#restoreBulkButton').length > 0 ) {
                $('#restoreBulkButton').find('.total').html('');
                $('#restoreBulkButton').addClass('disabled');
            }
        }
        else {
            if( $('#deleteBulkButton').length > 0 ) {
                $('#deleteBulkButton').find('.total').html('(' + uids.length + ')');
                $('#deleteBulkButton').removeClass('disabled');
            }
            else if( $('#restoreBulkButton').length > 0 ) {
                $('#restoreBulkButton').find('.total').html('(' + uids.length + ')');
                $('#restoreBulkButton').removeClass('disabled');
            }
        }
        console.log("uuid is ",uids);
    }
    $('body').on('change', 'input.select--flog-checkbox-', function(event) {
        event.preventDefault();
        enableOfflineTestTrashButton();
    });

    $('body').on('click', '#deleteBulkButton', function(event) {
        event.preventDefault();
        let ids = [];
        $('input.select--flog-checkbox-').each(function(index, el) {
            if( $(this).is(':checked') ) {
                ids.push($(this).val());
            }
        });
        if( ids.length > 0 ) {
            $('#deleteOfflineTest').show();
            let v = JSON.stringify(ids);
            $('#deleteOfflineTest').find('#deletingOfflineTestIds').val(v);
        }
    });
    $('body').on('click', '#restoreBulkButton', function(event) {
        event.preventDefault();
        let ids = [];
        $('input.select--flog-checkbox-').each(function(index, el) {
            if( $(this).is(':checked') ) {
                ids.push($(this).val());
            }
        });
        if( ids.length > 0 && confirm('Are you sure?') && !$(this).hasClass('active') ) {
            $(this).addClass('active');
            let fd = new FormData();
            fd.append('uuids', JSON.stringify(ids));
            restoreOfflineTestFunc(fd, $(this));
        }
    });
    $('body').on('click', '.restore-offline-test', function(event) {
        event.preventDefault();
        /* Act on the event */
        let uuid = $(this).data('uuid');
        if( uuid && confirm('Are you sure?') && !$(this).hasClass('active') ) {
            $(this).addClass('active');
            let fd = new FormData();
            let ids = [uuid];
            fd.append('uuids', JSON.stringify(ids));
            restoreOfflineTestFunc(fd, $(this));
        }
    });
    async function restoreOfflineTestFunc(fd, $this) {
        let params = {
            type: 'post',
            url: base_url + '/offline-test/restore',
            params: fd,
            loader: true,
        };
        let response = await ajaxCall(params);
        _show_result(response);
        $this.removeClass('active');
        if (response.status && response.status === "success") {
            $('#restoreBulkButton').addClass('disabled');
            $('#select-all-flog-checkbox-').prop('checked', false);
            $('#restoreBulkButton').find('.total').text('');
            let count = response.ids.length; 
            if( $('#totalDeletedOfflineTest').length > 0 ) {
                let total = parseInt($('#totalDeletedOfflineTest').text());
                total = total - count;
                $('#totalDeletedOfflineTest').text(total);
            }
            $.each(response.ids, function(index, val) {
                let row = $('#allDataTable').find('#row-' + this);
                if( row.length > 0 && row.closest('.datatable-checkbox').length > 0 ) {
                    datatableRemoveRow( '.datatable-checkbox', row.find('td') );
                }
                else if( row.length > 0 ) {
                    row.remove();
                }
            });
        }
    }
//----------------------------------------Dynamic Row adding--------------------------------//
    // $("body").on("change", ".section_select", function () {
    //     let selectedSection = $(this).val();
    //     let classesId = $(this).find("option:selected").data("classid"); 
    //     console.log("Selected Section:", selectedSection, "Class ID is:", classesId);
    //     if (classesId && selectedSection) {
    //         let apiUrl = `https://devtd17.vedmarg.com/api/get-subjects?classes_id=${classesId}&sections=${selectedSection}`;
    //         // Fetching data from the API
    //         $.ajax({
    //             url: apiUrl,
    //             type: "GET",
    //             dataType: "json",
    //             success: function (response) {
    //                 let subjectDropdown = $("#subjectDropdown");
    //                 subjectDropdown.empty(); // Clear previous options
    //                 subjectDropdown.append('<option value="">Select Subject</option>'); // Default option

    //                 if (response && response.data && response.data.length > 0) {
    //                     response.data.forEach(subjectItem => {
    //                         let option = `<option value="${subjectItem.id}" data-value="${subjectItem.name}">${subjectItem.name}</option>`;
    //                         subjectDropdown.append(option);
    //                     });
    //                 } else {
    //                     subjectDropdown.append('<option value="">No subjects found</option>');
    //                 }
    //             },
    //             error: function (error) {
    //                 console.error("Error fetching subjects:", error);
    //             }
    //         });
    //     }
    //     if (selectedSection) {
    //         let dynamicContainer = $(".dynamic-content");
    //         // Append the new section entry
    //         let dynamicHTML = `
    //             <div class="mt-2 mb-2 section-wrapper seciton--subject-container"  style="border:1px solid black;" data-section="${selectedSection}">
    //                 <div class="row section-row"  style="margin:5px;">
    //                     <div class="col-md-5 form-group">
    //                         <div class="row">
    //                           <div class="col-md-6">
    //                                <label class="form-label">Section</label>
    //                           </div>
    //                           <div class="col-md-6">
    //                                <input type="text" class="selected-section" name="section[]" value="${selectedSection}" readonly />
    //                           </div>
    //                         </div>       
    //                     </div>
    //                     <div class="col-md-5 form-group">
    //                         <div class="row">
    //                           <div class="col-md-6">
    //                              <label class="form-label">Subject</label>
    //                           </div>
    //                           <div class="col-md-6">
    //                             <div>
    //                                 <select class="subject_chosen select2">
    //                                    ${subjectOptions}
    //                                 </select>
    //                             </div>
    //                           </div>
    //                         </div>  
    //                     </div>
    //                     <div class="col-md-2 form-group" style="text-align:end;">
    //                         <button type="button" class="remove-section btn btn-danger">X</button>
    //                     </div>
    //                 </div>
    //                 <div class="subject_container">
    //                 </div>
    //             </div>
    //         `;
    //         dynamicContainer.append(dynamicHTML);
    //         // Disable the selected option in the main section dropdown
    //         $(this).find(`option[value="${selectedSection}"]`).prop("disabled", true);
    //         $(this).val("");  
    //     }
    // });
    
    
    $("body").on("change", ".section_select", function () {
        let selectedSection = $(this).val();
        let classesId = $(this).find("option:selected").data("classid"); 
        console.log("Selected Section:", selectedSection, "Class ID:", classesId);
        if (classesId && selectedSection) {
            let apiUrl = `https://devtd17.vedmarg.com/api/get-subjects?classes_id=${classesId}&sections=${selectedSection}`;

            // Fetching data from the API
            $.ajax({
                url: apiUrl,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    let subjects = [];
                    if (Array.isArray(response.data)) {
                        subjects = response.data;
                    } else if (Array.isArray(response.subjects)) {
                        subjects = response.subjects;
                    } else if (response.subjects && typeof response.subjects === "object") {
                        subjects = Object.values(response.subjects); // Convert object to array
                    }

                    console.log("Final Subjects Array:", subjects); // Debugging

                    let subjectOptions = '<option value="">Select Subject</option>';
                    if (subjects.length > 0) {
                        subjects.forEach(subjectItem => {
                            subjectOptions += `<option value="${subjectItem.id}" data-value="${subjectItem.name}">${subjectItem.name}</option>`;
                        });
                    } else {
                        subjectOptions = '<option value="">No subjects found</option>';
                    }


                    let dynamicContainer = $(".dynamic-content");

                    // Append the new section entry
                    let dynamicHTML = `
                        <div class="mt-2 mb-2 section-wrapper seciton--subject-container"  style="border:1px solid black;" data-section="${selectedSection}">
                            <div class="row section-row"  style="margin:5px;">
                                <div class="col-md-5 form-group">
                                    <div class="row">
                                      <div class="col-md-6">
                                           <label class="form-label">Section</label>
                                      </div>
                                      <div class="col-md-6">
                                           <input type="text" class="selected-section" name="section[]" value="${selectedSection}" readonly />
                                      </div>
                                    </div>       
                                </div>
                                <div class="col-md-5 form-group">
                                    <div class="row">
                                      <div class="col-md-6">
                                         <label class="form-label">Subject</label>
                                      </div>
                                      <div class="col-md-6">
                                        <div>
                                            <select class="subject_chosen select2">
                                               ${subjectOptions}
                                            </select>
                                        </div>
                                      </div>
                                    </div>  
                                </div>
                                <div class="col-md-2 form-group" style="text-align:end;">
                                    <button type="button" class="remove-section btn btn-danger">X</button>
                                </div>
                            </div>
                            <div class="subject_container">
                            </div>
                        </div>
                    `;

                    dynamicContainer.append(dynamicHTML);
                    
                    // Disable the selected option in the main section dropdown
                    $(".section_select").find(`option[value="${selectedSection}"]`).prop("disabled", true);
                    $(".section_select").val(""); 
                },
                error: function (error) {
                    console.error("Error fetching subjects:", error);
                }
            });
        }
    });



    // Remove section dynamically
    $("body").on("click", ".remove-section", function () {
        let sectionWrapper = $(this).closest(".section-wrapper");
        let sectionValue = sectionWrapper.attr("data-section");
        $(".section_select").find(`option[value="${sectionValue}"]`).prop("disabled", false);
        // $(".chosen").trigger("chosen:updated"); // Update Chosen dropdown

        sectionWrapper.remove(); 
    });
    

    $("body").on("change", ".subject_chosen", function () {
        let subjectSelectedOption = $(this).val();
        let subjectSelectedText = $(this).find("option:selected").data("value");

        let sectionWrapper = $(this).closest(".section-wrapper"); 
        let subjectContainer = sectionWrapper.find(".subject_container");
        let section = sectionWrapper.find("input[name='section[]']").val();
        if (subjectSelectedOption) {
            let subjectHtml = `
                <div class="row subject-section-row" data-subject="${subjectSelectedOption}" style="margin:5px;">
                    <div class="col-md-5 form-group">
                        <div class="row">
                           <div class="col-md-6">
                              <label class="form-label">Subject</label>
                           </div>
                           <div class="col-md-6">
                              <input type="text" class="selected-subject" name="subject[${section}][]" value="${subjectSelectedText}" readonly />
                           </div>
                        </div>
                    </div>
                    <div class="col-md-5 form-group">
                        <div class="row">
                            <div class="col-md-6">
                               <label class="form-label">Max Marks *</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" class="subject--max-marks" name="max_marks[${section}][]" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 form-group">
                      <button type="button" class="remove-subject btn btn-warning">X</button>
                    </div>
                </div>
            `;
            subjectContainer.append(subjectHtml);
            $(this).find(`option[value="${subjectSelectedOption}"]`).prop("disabled", true);
        }
    });


    $("body").on("click", ".remove-subject", function () {
        let subjectRow = $(this).closest(".subject-section-row");
        let subjectValue = subjectRow.attr("data-subject");
        let sectionWrapper = $(this).closest(".section-wrapper");
        console.table([subjectRow,subjectValue,sectionWrapper]);
        sectionWrapper.find(".subject_chosen").find(`option[value="${subjectValue}"]`).prop("disabled", false);
        subjectRow.remove(); 
    });
//------------------------Api for classes and Section------------------------------------------------------//
        let classSelect = $(".classes_section");
        let sectionSelect = $(".section_select");
        let classFilter=$(".classes_section_filter");
        let sectionFilter=$('.section_filter');
        let params = {
            url: "https://devtd17.vedmarg.com/api/get-classes",
            type: "GET",
            dataType: "json", // Ensure JSON response
            loader: false,
        };
        fetchclassDetails(params, $(this));
        async function fetchclassDetails(params, $this){
              try{
                let result = await ajaxCall(params);
                if(classSelect){
                    $.each(result.classes, function (index, classItem) {
                    classSelect.append(`<option value="${classItem.id}" data-sections="${classItem.sections}">${classItem.name}</option>`);
                    });
                }
                if(classFilter){
                    $.each(result.classes, function (index, classItem) {
                    classFilter.append(`<option value="${classItem.id}" {{Route::get('classes')==${classItem.id} ? 'selected' :''}} data-sections="${classItem.sections}">${classItem.name}</option>`);
                    });
                }
              }
              catch(error){
                  console.log("error is ",error);
              }
        }
        
        // Handle Class Change Event
        $("body").on("change", ".classes_section,.classes_section_filter", function () {
            let selectedOption = $(this).find("option:selected");
            let sections = selectedOption.data("sections") ? selectedOption.data("sections").split(",") : [];
            let classesId=selectedOption.val();
            let subjectContainer = $(".seciton--subject-container"); 
            if (subjectContainer.length > 0) {
                subjectContainer.remove();
            }
            console.log("selcted option is ",selectedOption,"Classes_id is ",classesId,"sections are ",sections);
            if(sectionSelect){
                sectionSelect.html('<option value="">--Select Section--</option>');
                $.each(sections, function (index, section) {
                    sectionSelect.append(`<option value="${section.trim().toLowerCase()}" data-classid="${classesId}">${section.trim()}</option>`);
                });
            }
            if(sectionFilter){
                console.log("inside the if condition ");
                sectionFilter.html('<option value="">--Select Section--</option>');
                $.each(sections, function (index, section) {
                    console.log("Inside the loop");
                    sectionFilter.append(`<option value="${section.trim().toLowerCase()}" data-classid="${classesId}">${section.trim()}</option>`);
                });
            }
           
        });

//-------------------------------------Toggle for filter------------------------------------------------------//
    $('.toggleFilterBtn').click(function(){
      $('.filters--container').toggle();
    });

//-----------------------------------------Section form------------------------------------------------------//
    
});
function showPopupBoxOfflineTest(selector) {
    if( $(selector).length > 0 ) {
        $(selector).show();
    }
}