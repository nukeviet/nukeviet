<!-- BEGIN: main -->
<div class="container>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<form class="col-sm-24 col-md-18">
					<div class="form-group">
						<label for="topic-title">Tiêu đề: (∗)</label>
						<input type="text" class="form-control" name="title" id="topic-title" aria-describedby="emailHelp">
					</div>
					<div class="form-group">
						<label for="static-link">Liên kết tĩnh:</label>
						<input type="text" class="form-control" name="alias" id="static-link">
					</div>
					<div class="form-group">
						<label for="event-following">Thuộc dòng sự kiện:</label>
						<input type="text" class="form-control" name="topicid" id="event-following">
					</div>
					<div class="form-group">
						<label for="imageFile">Hình minh họa</label>
						<div>
							<img src="" id="selected-image" style="width: 200px; margin-bottom: 8px;" />
						</div>
						<input type="file" accept="image/*" name="image" class="form-control" id="imageFile">
					</div>
					<div class="form-group">
						<label>Chú thích cho hình:</label>
						<input type="text" name="homeimgalt" class="form-control" >
					</div>
					<div class="form-group">
						<select class="form-control" name="imgposition">
							<option value="0">Không hiển thị</option>
							<option value="1">Hiển thị bên trái phần mở đầu</option>
							<option value="2" selected="selected">Hiển thị dưới phần mở đầu</option>
						</select>
					</div>
					<div class="form-group">
						<label>Giới thiệu ngắn gọn:</label>
						<textarea name="hometext" class="form-control" ></textarea>
					</div>
					<div class="form-group">
						<label>Nội dung chi tiết:</label>
						<textarea class="form-control" name="bodyhtml" ></textarea>
					</div>

					<script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>
					<script>
						CKEDITOR.replace( 'bodyhtml' );
					</script>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
				
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('#imageFile').change(function(e) {
			const file = URL.createObjectURL(event.target.files[0]);;
			$('#selected-image').attr('src', file);
		})
	})
</script>
<!-- END: main -->