<section id="content">
<main class="main">
<form class="form-horizontal well" role="form" method="post" id="form_report">
	<div class="form-group">
		<label class="col-sm-1 control-label">No Perjanjian *</label>
		<div class="col-sm-1-half">
			<input type="text" name="no_perjanjian" class="form-control datepicker required"  placeholder="No Perjanjian" />
		</div>
		
	</div>
	<div class="form-group">
		<label class="col-sm-1 control-label">Aksi *</label>
		<div class="col-sm-1-half">
			<select name="aksi">
			 <option value="O">Jadwal Ulang</option>
			 <option value="-1">Batal</option>
			</select>
		</div>	
	</div>	
	<div class="form-group">
		<div class="col-sm-1"></div>
		<div class="col-sm-1">
			<input type="submit" name="submit" class="btn btn-primary filter-btn" value="submit" />
		</div>
	</div>
	</div>
</form>


</main>
</section>
<script>
$(document).ready(function () {
	$("#btnExport").click(function () {
		$("#tblExport").btechco_excelexport({
			containerid: "tblExport", 
			datatype: $datatype.Table
		});
	});
});

function displayRute(objClicked,id_perjanjian){
	swal({
		title: "Rute",
		type: "warning",
		text: $('div#rute_'+id_perjanjian).html(),
		html: true
	});
	return;
}
</script>