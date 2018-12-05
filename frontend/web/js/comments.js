jQuery(document).ready(function ($) {


    /*====== AJAX methods for create and realtime add comments on the Post page view.php ======*/
    
    $('#main-comment-btn').on('click', function(event) {
        event.preventDefault();
        var comment = $('#main-comment').val();
        var id = $('#main-comment').attr('data-id');
		$.ajax({
 			url: '/post/default/create-comment',
 			data: {
 				text: comment,
 				post_id: id,
 			},
 			type: 'POST',
 			success: function(res) {
 				if(!res) {
 					//swal(nameProduct, "Ошибка добавления в корзину!", "error");
 					alert('Ошибка связи с сервером!');
 				}
 				var result = $.parseJSON(res);
 				if(!result.success) {
 					alert('Ошибка добавления комментария!');
 				}
 				else {
 				//	$('.js-show-cart').data('notify', result.productsCount).attr('data-notify', result.productsCount);
 				//	console.log(result.productsCount);
 					//swal(nameProduct, "добавлено в корзину!", "success");
 				}
 						
 					
 			},
 			error: function() {
 				alert('Error!');
 			}
 		});
        return false;
    });

    
});