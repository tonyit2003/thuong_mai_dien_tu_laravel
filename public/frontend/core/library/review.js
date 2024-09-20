(function($) {
	"use strict";
	var HT = {}; // Khai báo là 1 đối tượng
	var timer;
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.review = () => {

        var modal = UIkit.modal("#review");

        $(document).on('click', '.btn-send-review', function(){
            let option = {
                score: $('.rate:checked').val(),
                description : $('.review-textarea').val(),
                gender: $('.gender:checked').val(),
                fullname: $('.product-preview input[name=fullname]').val(),
                email: $('.product-preview input[name=email]').val(),
                phone: $('.product-preview input[name=phone]').val(),
                reviewable_type: $('.reviewable_type').val(),
                reviewable_id: $('.reviewable_id').val(),
                _token: _token,
                parent_id: $('.review_parent_id').val()
            }

            $.ajax({
				url: 'ajax/review/create', 
				type: 'POST', 
				data: option, 
				dataType: 'json', 
				beforeSend: function() {
					
				},
				success: function(res) {
					if(res.code === 10){
                        toastr.success(res.messages, 'Thông báo từ hệ thống!')
                        modal.hide()
                        location.reload()

                     }else{
                        toastr.error(res.messages, 'Thông báo từ hệ thống!')
                     }
				},
			});
        })
    }
	

	$(document).ready(function(){
		HT.review()
	});

})(jQuery);

