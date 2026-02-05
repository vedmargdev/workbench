<div id="editOfflineTestPopup" class="popup-box add--party-popup" style="display: block">
    <div class="popup lg">
        <div class="popup-header">
            <h3 class="heading">Update Test</h3>
            <span class="close-popup hide-popup-box">
                <img src="{{ asset_url('images/icons/close-white.png') }}">
            </span>
        </div>
        {{-- @php
            $subjectData = json_decode($offlineTestDetails->sections_data, true);
            $sections = json_decode($offlineTestDetails->section ?? '[]', true);
        @endphp --}}
        <div class="popup-body">
            <form id="edit_offline_test" action="{{ route('user.offline-test.store') }}" method="post" autocomplete="off">
                @csrf
                <input type="hidden" name="uuid" value="{{$offlineTestDetails->uuid }}">

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="form-label">Name *</label>
                        <input type="text" name="test_name" value="{{$offlineTestDetails->test_name ?? ''}}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" name="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{$offlineTestDetails->date}}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label">From Time *</label>
                        <input type="time" name="from_time" value="{{ old('from_time', \Carbon\Carbon::parse($offlineTestDetails->from_time)->format('H:i')) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="form-label">To Time *</label>
                        <input type="time" name="to_time" value="{{ old('to_time', \Carbon\Carbon::parse($offlineTestDetails->to_time)->format('H:i')) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label">Class *</label>
                        <div>
                            <select class="classes_section select2 classes-section" name="classes_id">
                                <option value="">--Select--</option>
                                @foreach($apiResponse['classes'] ?? [] as $class)
                                    <option value="{{ $class['id'] }}" data-sections="{{ $class['sections'] }}"{{ $offlineTestDetails->classes_id == $class['id'] ? 'selected' : '' }}>{{ $class['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label">Section *</label>
                        <div>
                            <select class="section_select section-select select2">
                                <option value="">--Select--</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="existing_sections_data" value='@json($subjectData)'>
                <div class="dynamic-content">
                    @if($subjectData)
                        @foreach($subjectData as $subjectInfo)
                            <div class="mt-2 mb-2 section-wrapper seciton--subject-container"  style="border:1px solid black;">
                                <div class="row section-row"  style="margin:5px;">
                                    <div class="col-md-5 form-group">
                                        <div class="row">
                                          <div class="col-md-6">
                                               <label class="form-label">Section</label>
                                          </div>
                                          <div class="col-md-6">
                                               <input type="text" name="section[]" value="{{$subjectInfo['section']}}" readonly />
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
                                                    <option value="">--Select--</option>
                                                @php
                                                    $currentSection = $subjectInfo['section'];
                                                    $subjectsForSection = $sectionSubjectMap[$currentSection] ?? [];
                                                    $disabledSubjects = $selectedSubjects[$currentSection] ?? [];
                                                @endphp
                                                @foreach($subjectsForSection as $id => $name)
                                                    <option value="{{ $id }}" data-value="{{ $name }}" {{ in_array($id, $disabledSubjects) ? 'disabled' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
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
                                    @foreach($subjectInfo['subjects'] as $subject)
                                     <div class="row subject-section-row" data-subject="{{$subject['subject_id']}}" style="margin:5px;">
                                        <div class="col-md-5 form-group">
                                            <div class="row">
                                               <div class="col-md-6">
                                                  <label class="form-label">Subject</label>
                                               </div>
                                                @php
                                                  $subjectName = $subjectMap[$subject['subject_id']] ?? 'Unknown Subject';
                                                @endphp
                                               <div class="col-md-6">
                                                  <input type="text" name="subject[]" value="{{$subjectName}}" readonly />
                                               </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                   <label class="form-label">Max Marks *</label>
                                                </div>
                                                <div class="col-md-6">
                                                  <input type="text" name="max_marks[]" value="{{$subject['max_marks']}}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 form-group">
                                          <button type="button" class="remove-subject btn btn-warning">X</button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else

                    @endif
                </div>
                <div class="row">
                    <div class="col-md- 12 form-group">
                        <label class="form-label">Syllabus</label>
                        <textarea class="mytextarea" name="syllabus">{{$offlineTestDetails->syllabus}}</textarea>
                    </div>
                </div>
                <div class="row submit_close_button">
                    <div class="col-md-12" id="btnSubmit">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    let selectedSubjectsBySection = @json(
        collect($subjectData)->mapWithKeys(fn($item) => [$item['section'] => collect($item['subjects'])->pluck('subject_id')])
    );
    console.table([selectedSubjectsBySection]);

    $(document).ready(function () {
        $('.select2').select2();
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

       
        let classSelect = $(".classes-section");
        let sectionSelect = $(".section-select");
        function populateSections(selectedClassId) {
            sectionSelect.html('<option value="">--Select Section--</option>');

            let selectedOption = classSelect.find("option[value='" + selectedClassId + "']");
            let sections = selectedOption.data("sections") ? selectedOption.data("sections").split(",") : [];

            $.each(sections, function (index, section) {
                let trimmedSection = section.trim();
                console.log("Section is ",trimmedSection);
                sectionSelect.append(`<option value="${trimmedSection}" data-classid="${selectedClassId}">${trimmedSection}</option>`);
            });

            let selectedSection = "{{ $offlineTestDetails->section ?? '' }}";
            sectionSelect.val(selectedSection);
        }
        // Run on page load if editing an existing record
        let initialClassId = classSelect.val();
        if (initialClassId) {
            populateSections(initialClassId);
        }
         // Bind event to detect class selection change
        $("body").on("change", ".classes-section", function () {
            let selectedClassId = $(this).val();
            let subjectContainer = $(".seciton--subject-container"); // Select globally
            if (subjectContainer.length > 0) {
                subjectContainer.remove();
            }
            console.log("Selected Class ID:", selectedClassId);
            
            if (selectedClassId) {
                populateSections(selectedClassId);
            } else {
                sectionSelect.html('<option value="">--Select Section--</option>');
            }
        });

    //-----------------Selected section auto Disabling----------------------//
        let selectedSections = [];
        // Collect existing sections and normalize them
        $(".section-wrapper input[name='section[]']").each(function () {
            let val = $(this).val().trim().toLowerCase();
            if (val) {
                selectedSections.push(val);
            }
        });
        $(".section-select option").each(function () {
            let sectionValue = $(this).val().trim().toLowerCase();
            console.log("Checking Section Value:", `"${sectionValue}"`);

            if (selectedSections.includes(sectionValue)) {
                console.log("Inside the if block - Disabling:", `"${sectionValue}"`);
                $(this).prop("disabled", true);
            }
        });
        $(".section-wrapper").each(function () {
            let section = $(this).find("input[name='section[]']").val(); 
            let sectionSubjects = selectedSubjectsBySection[section] || [];
            $(this).find(".subject_chosen").each(function () {
                sectionSubjects.forEach(subject => {
                    $(this).find(`option[value="${subject}"]`).prop("disabled", true);
                });
            });
        });

    });
</script>


