<div id="offlineTestPopup" class="popup-box add--party-popup" style="display: none">
    <div class="popup lg">
        <div class="popup-header">
            <h3 class="heading">Create Test</h3>
            <span class="close-popup hide-popup-box">
                <img src="{{ asset_url('images/icons/close-white.png') }}">
            </span>
        </div>
        <div class="popup-body">
            <form id="add_offline_test" action="{{ route('user.offline-test.store') }}" method="post" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="form-label">Name *</label>
                        <input type="text" name="test_name">
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" name="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label">From Time *</label>
                        <input type="time" name="from_time">
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="form-label">To Time *</label>
                        <input type="time" name="to_time">
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label">Class *</label>
                        <div>
                            <select class="classes_section select2" name="classes_id">
                                <option value="">--Select--</option>
                               
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label">Section *</label>
                        <div>
                            <select class="section_select select2">
                                
                            </select>
                        </div>
                    </div>
                </div>
                 <div class="dynamic-content"></div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label class="form-label">Syllabus</label>
                        <textarea class="mytextarea" name="syllabus"></textarea>
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