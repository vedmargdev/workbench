<div class="popup-box " id="deleteOfflineTest" style="display:none;">

    <div class="popup">

        <div class="popup-header">
            <span class="close-popup hide-popup-box">
                <img src="{{ asset_url('images/icons/close-white.png') }}">
            </span>
            <h3 class="heading">Delete Offline Test</h3>
        </div>

        <form method="post" action="{{route('user.offline-test.delete')}}" accept-charset="UTF-8" id="deletingOfflineTestForm" autocomplete="off">

            @csrf

            <div class="popup-body">
                <div class="form-group">
                    <label class="form-label">Remark *</label>
                    <textarea name="remark" rows="2" class="form-control"></textarea>
                </div>

                <textarea style="display:none" name="uuids" id="deletingOfflineTestIds"></textarea>
            </div>

            <div class="popup-footer d-flex justify-content-between">
                <button class="btn btn-warning btn-default btn-sm hide-popup-box" type="button">Close</button>
                <button class="btn btn-warning btn-sm" type="submit">Delete</button>
            </div>

        </form>

    </div>

</div>