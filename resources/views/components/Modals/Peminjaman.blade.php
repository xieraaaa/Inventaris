@push('styles')
	<link href="{{ asset('../assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
@endpush

<div class="modal fade" id="barang-modal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="barangModal"></h4>
			</div>
			<div class="modal-body">
				<form action="javascript:void(0)" id="barangForm" name="barangForm" class="form-horizontal"
					method="POST" enctype="multipart/form-data">
					<input type="hidden" name="id" id="id">
					<input type="hidden" name="id_user" value="<?= Auth::user()->id ?>">
					<input type="hidden" name='id_barang' id="id_barang">
					<div class="form-group">
						<div class="col-sm-12">
							<div class="row">
								<div class="col-md-6">
									<label class="m-t-20 form-label">Dari</label>
									<input type="text" class="form-control" placeholder="2017-06-04" name = "mdate" id="mdate">
								</div>
								<div class="col-md-6">
									<label class="m-t-20 form-label">Sampai</label>
									<input type="text" class="form-control" placeholder="2017-06-04" name="pdate" id="pdate">
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="keterangan" class="col-sm-8 mb-2 control-label">Keterangan</label>
						<div class="col-sm-12">
							<textarea class="form-control" id="keterangan" name="keterangan" placeholder="keterangan" rows="4" maxlength="500" required=""></textarea>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary" id="btn-save">Save Changes</button>
					</div>
				</form>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>

@push('scripts')
	<!-- Plugin JavaScript -->
	<script src="{{ asset('../assets/node_modules/moment/moment.js') }}"></script>
	<script src="{{ asset('../assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
	<!-- Clock Plugin JavaScript -->
	<script src="{{ asset('../assets/node_modules/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>

	<script src="{{ asset('assets/node_modules/moment/moment.js') }}"></script>
	<script src="{{ asset('assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
	<!-- Clock Plugin JavaScript -->
	<script src="{{ asset('assets/node_modules/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
	<!-- Color Picker Plugin JavaScript -->
	<script src="{{ asset('assets/node_modules/jquery-asColor/dist/jquery-asColor.js') }}"></script>
	<script src="{{ asset('assets/node_modules/jquery-asGradient/dist/jquery-asGradient.js') }}"></script>
	<script src="{{ asset('assets/node_modules/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>
	<!-- Date Picker Plugin JavaScript -->
	<script src="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
	<!-- Date range Plugin JavaScript -->
	<script src="{{ asset('assets/node_modules/timepicker/bootstrap-timepicker.min.js') }}"></script>
	<script src="{{ asset('assets/node_modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

	<script defer>
		$('#mdate').bootstrapMaterialDatePicker({
			weekStart: 0,
			time: false
		});
		$('#pdate').bootstrapMaterialDatePicker({
			weekStart: 0,
			time     : false
		});
		$('#timepicker').bootstrapMaterialDatePicker({
			format: 'HH:mm',
			time: true,
			date: false
		});
		$('#date-format').bootstrapMaterialDatePicker({
			format: 'dddd DD MMMM YYYY - HH:mm'
		});
		$('#min-date').bootstrapMaterialDatePicker({
			format: 'DD/MM/YYYY HH:mm',
			minDate: new Date()
		});
	</script>
@endpush
